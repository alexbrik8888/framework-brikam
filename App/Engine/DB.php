<?php

namespace App\Engine;

class DbExceptionSQL {
    public  $sql = '';
    public function __construct($sql) {
        $this->sql = $sql;
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
            $this->config = Cache::getInstance()->get('db');
            $dsn = sprintf("mysql:host=%s;dbname=%s;charset=%s",
                $this->config['host'],
                $this->config['dbname'],
                $this->config['charset'] ?? 'utf8mb4'
            );
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        self::$pdo = new PDO($dsn, $this->config['user'], $this->config['pass'], $options);
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

    public static function raw($sql) {return new DbExceptionSQL($sql);}

    public function select(string|array $select): self{
        if(is_string($select))
            $this->select[] = $select;
        if(is_array($select))
            $this->select  =  array_merge($this->select,$select);
        return $this;
    }

    public function from( string|array $from){
        if(is_string($from))
            $this->select[] = $from;
        if(is_array($from))
            $this->select[] =array_merge($this->from,$from);
        return $this;
    }


    protected function join(string $table, string|array $on = '', string $type = 'INNER') {
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

    public function where( string $field, mixed $value, string $operator = '=', string $type = 'and'){
            $this->where[] = sprintf(" %s %s %s '%s' ",(count($this->where) > 1) ? $type : '', $field, $operator, $value );
    }
    public function whereMultiple($whereArr){
        if(is_array($whereArr) && array_diff_key($whereArr[0],['field'=>1,'value'=>1,'operator'=>1,'type']) ==  0) {
            foreach($whereArr as $where){
                $this->where[] =  sprintf(" %s %s %s '%s' " ,
                    (count($whereArr) > 1)?$where['type']:'',$where['field'], $where['value'], $where['operator']);
            }
        }
        return $this;
    }

    public function whereIN($field,$value,$type = 'and') {
            if(is_array($value) && is_string($field)) {
                $this->where[] =  sprintf(" %s %s IN ( %s ) ", (count($this->where) > 1) ? $type : '',$field, implode(',',$value));
            }
        return $this;
    }
    public function whereNOT_IN($field,$value,$type = 'and') {
        if(is_array($value) && is_string($field)) {
            $this->where[] =  sprintf("%s  %s NOT IN ( %s ) ", (count($this->where) > 1) ? $type : '',$field, implode(',',$value));
        }
    }
    public function whereBetween($field,$value1,$value2,$type = 'and') {
        if(is_string($field)) {
            $this->where[] =  sprintf(" %s %s  BETWEEN  '%s' AND '%s' ", (count($this->where) > 1) ? $type : '',$field,$value1,$value2);
        }
        return $this;
    }

    public function whereNotBetween($field,$value1,$value2,$type = 'and'){
        if(is_string($field)) {
            $this->where[] =  sprintf(" %s  %s  NOT BETWEEN  '%s' AND '%s' ",(count($this->where) > 1) ? $type : '', $field,$value1,$value2);
        }
        return $this;
    }
    public function whereRaw($sql,$type = 'and'){
        if(is_string($sql)) {
            $this->where[] = ((count($this->where) > 1) ? $type : '').$sql;
        }
        return $this;
    }

    public function group($field){
            if(!is_array($field))
                $field = [$field];
            $this->group = 'GROUP BY '.implode(' , ',$field);
            return $this;
    }
    public function having($field,$value,$operator = '=',$type = 'and'){
        if(is_string($field) && is_string($operator) && is_string($type)) {
        $this->having[] = sprintf(" %s %s %s '%s' ",(count($this->having) > 1) ? $type : '', $field, $operator, $value );
        }
        return $this;
    }
    public function havingRaw($sql,$type = 'and'){
        if(is_string($sql)) {
            $this->having[] = ((count($this->having) > 1) ? $type : '').$sql;
        }
        return $this;
    }

    public function order($field,$sort='asc'){
        if(is_string($field) && is_string($sort))
            $this->order[] = printf(" %s %s",$field,$sort);
        return $this;
    }
    public function limit($limit){
        $this->limit = " LIMIT $limit ";
        return $this;
    }
    public function offset($offset) {
        $this->offset = " OFFSET $offset ";
        return $this;
    }
    public function get(){



    }
    public function count(){}

    public function insert($dataArr){

    }
    public function insertGetIncriment($dataArr){}
    public function insertMultiple($dataArr){}

    public function beginTransaction(){
        $this->connection->begin_transaction();
    }
    public function commit(){
        $this->connection->commit();
    }

    public function rollback(){
        $this->connection->rollback();
    }

    public function update(){}
    public function delete(){}
    public function getQueryString(){}
    public function clear(){}
    public function execute(){}


}