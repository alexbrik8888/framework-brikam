<?php

namespace App\Engine\User;

use app\Engine\extension\AuthFunction;
use App\Engine\Model;

class  UserAmin extends Model {
    use AuthFunction;
    protected string $table = 'user_admin';
    protected array $fields = [
            'id',
            'email',
            'password',
            'group',
    ];
     protected array $hiddenFields = [
         'password'
     ];
}