<?php

namespace App\Model\Filter;

use App\Engine\DbExceptionSQL;
use App\Engine\Model;

class CategoryArticl extends Model{
    protected string $table = 'category_articl';
    protected array $fields = ['id','category_id','articl_id'];
// 'id','name','text','description','published_at','created_at','updated_at'
public  function  coonectImageArticl() {
    $join =  '(SELECT  JSON_ARRAYAGG(JSON_OBJECT(
        "id", file.id,
        "file_name",file.file_name,
        "system_name", file.system_name,
        "extension", file.extension,
        "file_path", file.file_path
        )) as "image", file.abstract_id, file.type_id   FROM file  GROUP BY file.abstract_id, file.type_id  ) as aricle_file ON  aricle_file.abstract_id = '.$this->table.'.articl_id  AND aricle_file.type_id = 1 ';
    $this->Query()->joinLeft((new DbExceptionSQL($join)));
    $this->Query()->select('aricle_file.image');
    return $this;
}
    public  function  coonectArticl() {
        $this->Query()->joinLeft('articl',' '.$this->table.'.articl_id = articl.id ');
        $this->Query()->whereISNOTNULL('articl.id');
        $this->Query()->select('articl.*');
        return $this;
    }

}