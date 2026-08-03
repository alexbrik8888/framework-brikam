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
        $this->query->from($this->table);
    }

    public function insertMultiple($data):bool {
        return $this->query->insertMultiple($this->table, $data);
    }
    public function save(array $data) {
        if(!isset( $data['updated_at'] ))
            $data['updated_at'] = date('Y-m-d H:i:s');
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
        $this->query->clear();
        $this->query->from($this->table);
        return$this;
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
            $result = $this->query->getOne();
            if($result) {
                if(!empty($this->hiddenFields)) {
                    $result = array_intersect_key($result, array_diff_key(array_fill_keys($this->fields, 1), array_fill_keys($this->hiddenFields, 1)));
                    return $result;
                }
                return $result;
            }
            return null;
     }


     public function setLimit($limit){
         $this->query->limit(  $limit);
         return $this;
     }
     public function setPage($page) {
        if($page <= 0)
            $page = 1;
        $this->query->offset(  ($page-1) * $this->query->getLimit());
        return $this;
     }

     public function getPagination() {
        $total =   $this->query->count();
        $limit = $this->query->getLimit();
         return  [
                 'total' => $total,
                 'page' =>($this->query->getOffset() / $limit)+1,
                 'pageSize'=> $limit,
                 'countPage' => (  ((int)($total / $limit))  + (($total % $limit == 0)?0:1) ),
             ];
     }
     public function getList() {
        $result =  $this->query->get();
        if(!empty($this->hiddenFields) && $result) {
            $result = emoveKeysRecursive($result, $this->hiddenFields);
            return $result;
        } else if ($result){
            return $result;
        }
        return null;
     }
     public function where($field,$value,$operation = '=',$type ='AND') {
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