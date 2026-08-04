<?php

namespace App\Model;

use App\Engine\Model;

class ArticleView extends Model {
    protected string $table = 'article_view';
    protected string $primaryKey = 'article_id';
    protected array $fields = [
        'article_id',
        'view'
    ];
    public function  save(array $data) {
        $data =  array_intersect_key($data,array_fill_keys($this->fields,1));
        if(empty($data))
            return false;
        if(isset($data[$this->primaryKey])) {
            $countUp =  $this->query->where($this->primaryKey, $data[$this->primaryKey], '=')->update($this->table, $data);
            if($countUp ==  false)
                return false;
            if($countUp == 0)
                $data[$this->primaryKey] = $this->query->insert($this->table,$data);
            return $data;
        }
     }
}