<?php

namespace App\Engine;


class Model {
    protected $primaryKey = 'id';
    protected string $table;
    protected array $fields = [];
    protected array $hiddenFields =  [];
    protected $query = null;


    public function __construct() {
        $this->query = new DB();
        $this->query->limit(100);
    }

    public function save(array $data) {
        $data =  array_intersect_key($data,array_fill_keys($this->fields,1));
        if(empty($data))
            return false;
        if(isset($data[$this->primaryKey])) {
            $countUp =  $this->query->where($this->primaryKey, $data[$this->primaryKey], '=')->update($this->table, $data);
            if(!$countUp)
                return false;
            return $data;
        }else {
            $data[$this->primaryKey] = $this->query->insertGetIncriment($this->table,$data);
            if(!$data[$this->primaryKey])
                return false;
            return $data;
        }
    }
    public function &Query() {
        return $this->query;
    }

    public function newQuery() {

    }
     public function insert($data) {
         $data =  array_intersect_key($data,array_fill_keys($this->fields,1));
         if(empty($data))
             return false;
         $data[$this->primaryKey] = $this->query->insertGetIncriment($this->table,$data);
         if(!$data[$this->primaryKey])
             return false;
         return $data;
     }

     public function getFirst() {
            $this->query->from($this->table);
            $result = $this->query->getOne();
            $result = array_intersect_key($result,  array_diff_key(array_fill_keys($this->fields,1),array_fill_keys($this->hiddenFields,1)));
            return $result;
     }

     public function getPagination() {
         return  [
                 'total' => $this->query->count(),
                 'page' =>1,
                 'pageSize'=>$this->query->getLimit()
             ];
     }
     public function getList() {
        $this->query->from($this->table);
        $result =  $this->query->get();
        if(!empty($this->hiddenFields))
            $result = emoveKeysRecursive( $result ,$this->hiddenFields);
        return $result;
     }
     public function where($field,$value,$operation,$type ='AND') {
         $this->query->where($field,$value,$operation,$type);
         return $this;
    }
     public function find($field,$value) {
         if(!in_array($field,$this->fields))
             return false;
        $this->query->where($field,$value);
         return $this;
    }
    public function findAll($field,$value) {
        if(!in_array($field,$this->fields))
            return false;
        $this->query->where($field,$value);
        return $this;
    }


    public function update($data) {
        $data =  array_intersect_key($data,array_fill_keys($this->fields,1));
        if(empty($data))
            return false;
        $countUp =  $this->query->update($this->table, $data);
            if(!$countUp)
                return false;
            return $data;
    }
    public function delete() {
        $this->query->delete($this->table);
    }

    public function getFiled() {
        return $this->fields;
    }
    public function getTable() {
        return $this->table;
    }
}