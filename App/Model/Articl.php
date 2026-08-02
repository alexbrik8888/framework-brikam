<?php

namespace App\Model;

use App\Engine\Model;

class Articl extends Model {
protected string $table = 'articl';
protected array $fields =[
    'id','name','text','description'
];
}