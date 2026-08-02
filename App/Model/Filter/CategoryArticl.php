<?php

namespace App\Model\Filter;

use App\Engine\Model;

class CategoryArticl extends Model{
    protected string $table = 'category_articl';
    protected array $fields = ['id','category_id','articl_id'];
}