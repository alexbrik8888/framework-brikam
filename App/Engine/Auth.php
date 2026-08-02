<?php

namespace App\Engine;
class Auth {
    private $id;
    private $group;
    private $gait;
    private $user;


    public static $inst = null;

    public static function getInstance(){
        if(self::$inst == null)
            self::$inst = new Auth();

        return self::$inst;
    }

    public function setModel($model) {
        if(in_array('App\\Engine\\Model' ,class_parents($model)));
        $this->model = (new $model());
        d($this->model);
    }
    public function attachment(array $credentials) {

    }
    public function getUserID(){}

    public function getGroupID(){}
    public function getUser(){}
}