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
        $join =  "(SELECT   JSON_ARRAYAGG(JSON_OBJECT(
        'id', file.id,
        'file_name',file.file_name
        'system_name', file.system_name,
        'extension', file.extension,
        'file_path', file.file_path
        )) as 'image'   FROM file  GROUP BY file.abstract_id, file.type_id  ) as aricle_file ON  aricle_file.abstract_id = {$this->table}.id  AND aricle_file.type_id = 1 ";
        $this->Query()->joinLeft((new DbExceptionSQL($join)));
        return $this;
    }
}




