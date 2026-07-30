<?php

namespace App\Engine;

class DbExceptionSQL {
    public  $sql = '';
    public function __construct($sql) {
        $this->sql = $sql;
    }
}

class DB {

    private $connection;
    private $config = null;
    private $dbObj;
    protected  $select = [];
    protected  $from = [];

    protected  $join = [];

    protected  $where = [];
    protected  $group = [];
    protected  $having = [];
    protected  $order = [];
    protected  $limit = null;
    protected  $offset = null;

    protected $strQusery = '';

    public function __construct() {
            $this->config = Cache::getInstance()->get('db');
            $this->connection = new \mysqli($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['dbname'],'utf8');
            if($this->connection->connect_errno)
                throw  new \Exception($this->connection->connect_errno);
     }

    public function __destruct() {
        $this->connection->close();
    }


    private function  bilderSelct() {

        $select = (empty($this->select))
        $this->strQusery = sprintf("SELECT  %s  ",)
    }

    public static function raw($sql) {return new DbExceptionSQL($sql);}

    public function select($select){
        if(is_string($select))
            $this->select[] = $select;
        if(is_array($select))
            $this->select[] = $select;
        return $this;
    }

    public function from($from){
        if(is_string($from))
            $this->select[] = $from;
        if(is_array($from))
            $this->select[] = $from;
        return $this;
    }

    public function joinLeft($table,$where = ''){
           if(!empty($table))
               throw new \Exception('table empty');
           if(is_string($table) &&  (is_string($where) || is_array($table)) && !empty($where)) {
               $this->join[] = sprintf('LEFT JOIN  %s ON %s ', $table, $where);
                return $this;
           }
           if(is_string($table)){
               $this->join[] = sprintf('LEFT JOIN  %s ', $table);
               return $this;
           }
           if($table instanceof DbExceptionSQL) {
               $this->join[] = 'LEFT JOIN '. $table->sql;
               return $this;
           }
           return $this;
    }
    public function joinRight($table,$where = ''){
        if(!empty($table))
            throw new \Exception('table empty');
        if(is_string($table) &&  (is_string($where) || is_array($table)) && !empty($where)) {
            $this->join[] = sprintf('LEFT RIGHT  %s ON %s ', $table, $where);
            return $this;
        }
        if(is_string($table)){
            $this->join[] = sprintf('LEFT RIGHT  %s ', $table);
            return $this;
        }
        if($table instanceof DbExceptionSQL) {
            $this->join[] = 'LEFT RIGHT '.$table->sql;
            return $this;
        }
        return $this;
    }
    public function joinCross($table,$where = ''){
        if(!empty($table))
            throw new \Exception('table empty');
        if(is_string($table) &&  (is_string($where) || is_array($table)) && !empty($where)) {
            $this->join[] = sprintf('CROSS JOIN %s ON %s ', $table, $where);
            return $this;
        }
        if(is_string($table)){
            $this->join[] = sprintf('CROSS JOIN %s ', $table);
            return $this;
        }
        if($table instanceof DbExceptionSQL) {
            $this->join[] = 'CROSS JOIN '.$table->sql;
            return $this;
        }
        return $this;
    }
    public function joinInner($table,$where = ''){
        if(!empty($table))
            throw new \Exception('table empty');
        if(is_string($table) &&  (is_string($where) || is_array($table)) && !empty($where)) {
            $this->join[] = sprintf('INNER JOIN  %s ON %s ', $table, $where);
            return $this;
        }
        if(is_string($table)){
            $this->join[] = sprintf('INNER JOIN JOIN  %s ', $table);
            return $this;
        }
        if($table instanceof DbExceptionSQL) {
            $this->join[] = $table->sql;
            return $this;
        }
        return $this;
    }



    public function where($field,$value,$operator = '=',$type = 'and'){
        if(is_string($field) && is_string($operator) && is_string($type)) {
            $this->where[] = sprintf(" %s %s %s '%s' ",(count($this->where) > 1) ? $type : '', $field, $operator, $value );
        }
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