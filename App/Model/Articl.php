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


    public function getLastTheeArticlCategoy() {
       $rez =  $this->newQuery()->Query()->statment('WITH RankedArticles AS (
                SELECT 
                    c.id AS category_id,
                    c.name AS category_name,
                    a.id AS article_id,
                    a.description AS article_description,
                    a.name AS article_title,
                    f.id AS file_id,
                    a.created_at,
      
                    -- Нумеруем статьи отдельно для каждой категории по дате (от новых к старым)
                    ROW_NUMBER() OVER (
                        PARTITION BY c.id 
                        ORDER BY a.created_at DESC, a.id DESC
                    ) AS rn
                FROM category c
                JOIN category_articl ac ON c.id = ac.category_id
                JOIN articl a ON ac.articl_id = a.id
                JOIN file f ON ac.articl_id = f.abstract_id AND f.type_id = 1
            )
            SELECT 
                category_id,
                category_name,
                article_id,
                article_title,
                article_description,
                created_at,
                file_id
            FROM RankedArticles
            WHERE rn <= 3 
            ORDER BY category_id, created_at DESC');
       $group = [];
        $groupName = [];
       foreach ($rez as $item){
           if(key_exists($item['category_id'],$group)){
               $group[$item['category_id']][] = $item;
           } else {
               $group[$item['category_id']] = [$item];
           }
           $groupName[$item['category_id']] = $item['category_name'];
       }
        asort($groupName);
        return ['group' => $group,'group_name'=> $groupName];
    }
}




