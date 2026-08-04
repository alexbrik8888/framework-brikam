<?php

namespace App\Model\Filter;

use App\Engine\DbExceptionSQL;
use App\Engine\Model;

class CategoryArticl extends Model{
    protected string $table = 'category_articl';
    protected array $fields = ['id','category_id','articl_id'];
// 'id','name','text','description','published_at','created_at','updated_at'
public  function  coonectImageArticl() {
    $this->Query()->joinLeft('file',' '.$this->table.'.articl_id = file.abstract_id AND file.type_id = 1 ');
    $this->Query()->select('file.id as "file_id"');
    return $this;
}
    public  function  coonectArticl() {
        $this->Query()->joinLeft('articl',' '.$this->table.'.articl_id = articl.id ');
        $this->Query()->whereISNOTNULL('articl.id');
        $this->Query()->select('articl.*');
        return $this;
    }

    public  function  coonectArticlView() {
        $this->Query()->joinLeft('articl_view',' '.$this->table.'.articl_id = articl_view.articl_id ');
        $this->Query()->select(' IFNULL(articl_view.view,0) as "view"');
        return $this;
    }
    public function getList()   {
        $rez =   parent::getList();
        foreach ($rez as &$article) {
            if(isset($article['image']))
                $article['image'] = json_decode($article['image'],true);
            if(isset($article['category']))
                $article['category'] = json_decode($article['category'],true);
        }
        return $rez;
    }

    public function getFirst() {
        $rez = parent::getFirst();
        if(isset($rez['image']))
            $rez['image'] = json_decode($rez['image'],true);
        if(isset($rez['category']))
            $rez['category'] = json_decode($rez['category'],true);
        return $rez;
    }
}