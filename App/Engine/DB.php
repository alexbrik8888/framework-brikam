<?php

namespace App\Engine;

use PDOStatement;

class DbExceptionSQL {
    public  $sql = '';
    public function __construct($sql) {
        $this->sql =   addslashes($sql);
    }
}

class DB {

    private static ?\PDO $pdo = null;
    private $config = null;
    private $dbObj;
    protected array $select = [];
    protected array $from = [];
    protected array $join = [];
    protected array $where = [];
    protected  array $group = [];
    protected  array $having = [];
    protected  array $order = [];
    protected  int|string|null $limit = null;
    protected  int|string|null $offset = null;

    protected array $bindings = [];

    protected $strQusery = '';

    public function __construct() {
            $this->config = Config::getInstance()->getConfig('db');
            $dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s",
                $this->config['host'],
                $this->config['dbname'],
                $this->config['charset'] ?? 'utf8mb4'
            );
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        self::$pdo = new \PDO($dsn, $this->config['user'], $this->config['pass'], $options);
     }
    public function __destruct() {
        self::$pdo = null;
    }


    private function  bilderSelct() {
        $select = (!empty($this->select))?$this->select:['*'];
        $where  = (!empty($this->where))?implode('  ',$this->where):['1=1'];
        $group  = (!empty($this->group))? (' GROUP BY ' .implode(' , ',$this->group)):'';
        $having  = (!empty($this->group))? (' HAVING ' .implode('  ',$this->group)):'';
        $order  = (!empty($this->order))? (' ORDER BY ' .implode(' ',$this->order)):'';
        $limit  = (!empty($this->limit))? (' LIMIT ' .$this->limit):'';
        $offset = (!empty($this->offset))? (' OFFSET ' .$this->offset):'';
        $this->strQusery = sprintf("SELECT  %s  FROM  %s  %s  WHERE  %s %s  %s %s %s %s ",
            mplode(' , ',$select),
            implode(' , ',$this->from),
            implode('  ',$this->join),
            $where, $group, $having, $order, $limit, $offset
        );
    }

    public static function raw($sql):DbExceptionSQL {return new DbExceptionSQL($sql);}

    public function select(string|array $select): self{
        if(is_string($select))
            $this->select[] = $select;
        if(is_array($select))
            $this->select  =  array_merge($this->select,$select);
        return $this;
    }

    public function from( string|array $from) :self{
        if(is_string($from))
            $this->from[] = $from;
        if(is_array($from))
            $this->from[] =array_merge($this->from,$from);
        return $this;
    }


    protected function join(string $table, string|array $on = '', string $type = 'INNER') :self {
        if(!empty($table))
            throw new \Exception('table empty');
        if(!empty($on)) {
            if(is_array($on)){
                if(key_exists($on[0], ['field','type','operator','value'])){
                    $where ='';
                    for ($i = 0; $i < count($on); $i++) {
                        $where =  printf(" %s %s %s '%s' ",
                           (count($on) > 1 && $i >1) ? $on[$i]['type'] : '',$on[$i]['field'], $on[$i]['value'], $on[$i]['operator']);
                   }
                } else {
                    throw new \Exception("Формат масива не верен [['field'=>'','type'=>'','operator'=>'','value'=>''],...]");
                }
            }
            $this->join[] = sprintf('%s JOIN  %s ON %s ',$type, $table, $on);
            return $this;
        }
        if(is_string($table)){
            $this->join[] = sprintf('%s JOIN  %s ',$type, $table);
            return $this;
        }
        if($table instanceof DbExceptionSQL) {
            $this->join[] = $type.' JOIN '. $table->sql;
            return $this;
        }
        return $this;
    }


    public function joinLeft($table,$where = ''){
           return $this->join($table,$where,'LEFT');
    }
    public function joinRight($table,$where = ''){
       return $this->join($table,$where,'RIGHT');
    }
    public function joinCross($table,$where = ''){
        return $this->join($table,$where,'CROSS');
    }
    public function joinInner($table,$where = ''){
        return $this->join($table,$where,'INNER');
    }

    protected function where_( string $field, mixed $value, string $operator = '=', string $type = 'and') :string{
        $type = strtoupper($type);
        $prefix = empty($this->where) ? '' : "{$type} ";
        $paramName = ':' . str_replace('.', '_', $field) . '_' . count($this->bindings);
        $this->bindings[$paramName] = $value;
        return "{$prefix}{$field} {$operator} {$paramName}";
    }

    public function where(string $field, mixed $value, string $operator = '=', string $type = 'and') :self {
        $this->where[] = $this->where_( $field,$value,$operator,$type);
        return $this;
    }
    public function whereMultiple( array $whereArr) :self {
        if(is_array($whereArr) && array_diff_key($whereArr[0],['field'=>1,'value'=>1,'operator'=>1,'type']) ==  0) {
            foreach($whereArr as $where){
                $this->where[] = $this->where_( $where['field'],$where['value'],$where['operator'],$where['type']);
            }
        } else {
            throw new \Exception("Формат масива не верен [['field'=>'','type'=>'','operator'=>'','value'=>''],...]");
        }
        return $this;
    }

    public function whereIN(string $field, array $value,string $type = 'and') :self {
         $inParama = [];
         foreach ($value as $item){
             $paramName = ':' . str_replace('.', '_', $field) . '_' . count($this->bindings);
             $inParama[] = $paramName;
             $this->bindings[$paramName] = $item;
         }
        $this->where[] =  sprintf(" %s %s IN ( %s ) ", (count($this->where) > 1) ? $type : '',$field, implode(' , ',$inParama));
        return $this;
    }
    public function whereNOT_IN(string $field, array $value ,$type = 'and') :self {
        $inParama = [];
        foreach ($value as $item){
            $paramName = ':' . str_replace('.', '_', $field) . '_' . count($this->bindings);
            $inParama[] = $paramName;
            $this->bindings[$paramName] = $item;
        }
        $this->where[] =  sprintf(" %s %s NOT IN ( %s ) ", (count($this->where) > 1) ? $type : '',$field, implode(' , ',$inParama));
        return $this;
    }
    public function whereBetween(string $field,mixed $value1, mixed  $value2,string $type = 'and') :self {
        $val1 =  ':' . str_replace('.', '_', $field) . '_b1_' . count($this->bindings);
        $val2 =  ':' . str_replace('.', '_', $field) . '_b1_' . count($this->bindings);
        $this->bindings[$val1] = $value1;
        $this->bindings[$val2] = $value2;
        $this->where[] =  sprintf(" %s  %s  BETWEEN  '%s' AND '%s' ",(count($this->where) > 1) ? $type : '', $field,$val1,$val2);
        return $this;
    }

    public function whereNotBetween(string $field,mixed $value1, mixed  $value2,string $type = 'and') :self{
        $val1 =  ':' . str_replace('.', '_', $field) . '_b1_' . count($this->bindings);
        $val2 =  ':' . str_replace('.', '_', $field) . '_b1_' . count($this->bindings);
        $this->bindings[$val1] = $value1;
        $this->bindings[$val2] = $value2;
        $this->where[] =  sprintf(" %s  %s  NOT BETWEEN  '%s' AND '%s' ",(count($this->where) > 1) ? $type : '', $field,$val1,$val2);
        return $this;
    }
    public function whereRaw($sql,$type = 'and') :self{
        if($sql  instanceof DbExceptionSQL) {
            $this->where[] = ((count($this->where) > 1) ? $type : '').$sql->sql;
        }
        return $this;
    }

    public function group(string|array $field) :self{
        $this->group = (!is_array($field))?[$field]:$field;
        return $this;
    }
    public function having( string $field, mixed $value, string$operator = '=', string $type = 'and') :self{
        $type = strtoupper($type);
        $prefix = empty($this->where) ? '' : "{$type} ";
        $paramName = ':' . str_replace('.', '_', $field) . '_' . count($this->bindings);
        $this->bindings[$paramName] = $value;;
        $this->having[] = "{$prefix}{$field} {$operator} {$paramName}";
        return $this;
    }
    public function havingRaw(string $sql,$type = 'and') :self {
        if(is_string($sql)) {
            $this->having[] = ((count($this->having) > 1) ? $type : '').$sql;
        }
        return $this;
    }

    public function order(string $field, string $sort = 'asc'):self {
        $this->order[] = printf(" %s %s",$field,$sort);
        return $this;
    }
    public function limit($limit){
        $this->limit = $limit;
        return $this;
    }
    public function offset($offset):self {
        $this->offset = $offset;
        return $this;
    }
    public function get() : array {
         $stmt =  $this->execute();
         $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
         $stmt = null;
         return $result;
    }
    public function count() : int{
        $temp = $this->select;
        $this->select = ['COUNT(id) as \'count\''];
        $stmt = $this->execute();
        $result = $stmt->fetchColumn(0);
        $this->select = $temp;
        $stmt = null;
        return (($result)??0);
    }


    protected function execute(): PDOStatement {
       $this->getQueryString();
       dd($this->strQusery);
       $stmt  = self::$pdo->prepare($this->strQusery);
       $stmt->execute($this->bindings);
       return $stmt;
    }
    public function getQueryString(): self {
        $selectField = empty($this->select)?'*':implode(' , ',$this->select);
        $fromElement = empty($this->from)?'': implode(' , ',$this->from);;
        $this->strQusery = "SELECT {$selectField} FROM {$fromElement}";
        if(!empty($this->join))
            $this->strQusery .= implode(' ',$this->join);
        if(!empty($this->where))
            $this->strQusery .= " WHERE ".implode(' ',$this->where);
        if(!empty($this->group))
            $this->strQusery .= " GROUP BY ".implode(' , ',$this->group);
        if(!empty($this->having))
            $this->strQusery .= " HAVING ".implode(' ',$this->having);
        if(!empty($this->order))
            $this->strQusery .= " ORDER BY ".implode(' , ',$this->order);
        if(!empty($this->limit))
            $this->strQusery .= " LIMIT ".$this->limit;
        if (!empty($this->offset))
            $this->strQusery .= " OFFSET ".$this->offset;
        return $this;
    }

    public function insert( string $table,array $data): bool {
        $field =  implode(' , ',array_keys($data)) ;
        $pamBindings =  implode(' , ',array_map(function($k){return  ":$k" ;},array_keys($data)));
        $this ->strQusery = "INSERT INTO {$table} ({$field}) VALUES ({$pamBindings})";
        $stmt = self::$pdo->prepare($this ->strQusery);
        $result = $stmt->execute($data);
        $stmt = null;
        return  $result;
    }
    public function insertGetIncriment(string $table,array $data):int|bool{
        if($this->insert($table,$data))
            return self::$pdo->lastInsertId();
        return false;

    }

    public function insertIgnore(string $table,array $data) {
        return$this;
    }
    public function insertOrUpdate(string $table,array $data): bool {
        return$this;
    }

    public function insertMultiple(string $table,array $data) :bool {
        if(!array_is_list($data))
            throw new \Exception('Не индексированій массив');
        $field =  implode(' , ',array_keys($data[0])) ;
        $size = count($data);
        $countField = count($data[0]);
        $values = $params = [];
        for ( $i = 0 ;$i< $size; $i++) {
            $pamBindings = array_fill(0,$countField,'?');
            $values[] = '('.implode(' , ',$pamBindings).')';
            $params = array_merge($params,array_values($data[$i]));
        }
        $this ->strQusery = "INSERT INTO {$table} ({$field}) VALUES ".implode(',',$values);
        $stmt = self::$pdo->prepare($this ->strQusery);
        $result =  $stmt->execute($params);
        $stmt = null;
        return  $result;
    }

    public function beginTransaction(){
       self::$pdo->beginTransaction();
    }
    public function commit(){
        self::$pdo->commit();
    }

    public function rollback(){
        self::$pdo->rollBack();
    }

    public function update(string $table,array $data): bool {
        $this ->strQusery = "UPDATE {$table} SET  ";
        if(!empty($this->where))
            $this ->strQusery .= "WHERE ".implode(' ', $this->where);
        $stmt = self::$pdo->prepare($this ->strQusery);
        $result =  $stmt->execute($this->bindings);
        $stmt = null;
        return  $result;

    }
    public function delete(string $table){

        $this ->strQusery = "DELETE FROM {$table} ";
        if(!empty($this->where))
            $this ->strQusery .= "WHERE ".implode(' ', $this->where);
        $stmt = self::$pdo->prepare($this ->strQusery);
        $result =  $stmt->execute($this->bindings);
        $stmt = null;
        return  $result;
    }

    public function clear():self{
            $this->select = [];
            $this->from = [];
            $this->join = [];
            $this->where = [];
            $this->group = [];
            $this->having = [];
            $this->order = [];
            $this->limit = null;
            $this->offset = null;
            $this->bindings = [];
            return $this;
    }
}