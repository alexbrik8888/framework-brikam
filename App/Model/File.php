<?php

namespace App\Model;

use App\Engine\Config;
use App\Engine\DB;
use App\Engine\Model;

class File extends Model {
    private  $type = [
        'img'=>1,
        'video'=>2,
        'audio'=>3,
        'document'=>4,
        'file' => 5
    ];
    protected string $table = 'file';
    protected $dirSotege = 'storage';
    protected array $fields = [
        'id',
        'file_name',
        'system_name',
        'extension',
        'file_path',
        'type_id',
        'abstract_id',
        'is_deleted',
        'created_at',
        'updated_at'
    ];
    public function save(array $data):array|bool {
        try {
            if (!isset($data['file']) && !isset($data['type_id']))
                return false;
            $dir = Config::getInstance()->getConfig('root_dir');
            $key = array_search($data['type_id'], $this->type);
            $data['file_path'] = $dir .DIRECTORY_SEPARATOR . $this->dirSotege . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR;
            $data['file_name'] = $data['name'];
            $data['extension'] = strtolower(pathinfo($data['name'], PATHINFO_EXTENSION));
            $data['system_name'] = $data['type_id'] . '_' . $data['abstract_id'] . '.' . $data['extension'];
            if (!is_dir($data['file_path'])) {
                mkdir($data['file_path'], 0755, true);
            }
            if (move_uploaded_file($data['tmp_name'], $data['file_path'] . $data['system_name'])) {
                $data = parent::save($data);
                return $data;
            }
        }catch (\Throwable $e){
            d($e->getMessage(), $e->getTrace());
        }
        return  false;
    }

    public function delete():bool {
        $file  =  $this->getList();
        foreach ($file as  $value)
            unlink($value['file_path'].$value['system_name']);
        return parent::delete();
    }


}