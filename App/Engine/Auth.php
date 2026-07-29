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
    public function attachment(array $credentials) {

    }
    public function getUserID(){}

    public function getGroupID(){}
    public function getUser(){}
}