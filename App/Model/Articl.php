<?php

namespace App\Model;

use App\Engine\DbExceptionSQL;
use App\Engine\Model;

class Articl extends Model {
protected string $table = 'articl';
protected array $fields =[
    'id','name','text','description','published_at','created_at','updated_at'
];
    public function connectImage() {
        $join =  '(SELECT   JSON_ARRAYAGG(JSON_OBJECT(
        "id", file.id,
        "file_name",file.file_name,
        "system_name", file.system_name,
        "extension", file.extension,
        "file_path", file.file_path
        )) as "image", file.abstract_id, file.type_id   FROM file  GROUP BY file.abstract_id, file.type_id  ) as aricle_file ON  aricle_file.abstract_id = '.$this->table.'.id  AND aricle_file.type_id = 1 ';
        $this->Query()->joinLeft((new DbExceptionSQL($join)));
        $this->Query()->select('aricle_file.image');
        return $this;
    }
    public function connectCategory() {
        $join =  '(SELECT   JSON_ARRAYAGG(JSON_OBJECT(
        "id", category_articl.id,
        "category_id",category_articl.category_id, 
        "articl_id", category_articl.articl_id,
        "name", c.name        
        )) as "category" , category_articl.articl_id,category_articl.category_id  FROM   category_articl  
        LEFT JOIN category AS c ON category_articl.category_id = c.id 
           
           GROUP BY category_articl.articl_id  ) as aricle_category ON   aricle_category.articl_id = '.$this->table.'.id ';
        $this->Query()->joinLeft((new DbExceptionSQL($join)));
        $this->Query()->select('aricle_category.category');
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




