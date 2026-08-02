<?php
namespace app\Engine\extension;

trait  AuthFunction {
    public function check(){
        if(isset( $_SESSION['id']) && !empty( $_SESSION['id'] ))
            return true;
        return false;
    }
    public function getUserId() {
            return $_SESSION['id'];
    }
    public function checkCreadantion(array $credantion,$remember = false) {
        foreach ($credantion as $key => $value) {
            $this->model->where($key ,$value);
        }
        $user =  $this->model->getFirst();
        if($user) {
            $this->saveSession($user);
            if($remember) {
                $lifetime = 30 * 24 * 60 * 60;
                ini_set('session.cookie_lifetime', $lifetime);
                ini_set('session.gc_maxlifetime', $lifetime);
            }
            return true;
        }
        return false;
    }
    private function  saveSession($user){
        $_SESSION['id'] =   $user['id'];
        $_SESSION['user_info'] =  $user;
    }
}