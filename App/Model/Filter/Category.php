<?php

namespace App\Model\Filter;

use App\Engine\Model;

class Category extends Model {
    protected string $table = 'categories';
    protected array $fields = [
        'id', 'name', 'parent_id', 'description', 'is_deleted', 'created_at', 'updated_at'
    ];
}