<?php
namespace HTTP\Admin;
use App\Engine\Auth;
use App\Engine\BaseController;
use App\Model\User\UserAmin;

class Controller extends BaseController {
   public function callAction($action, $params){
            if($action == 'loginAction'){
                return parent::callAction($action, $params);
            } else {
                if(Auth::getInstance()->setModel(UserAmin::class)->chech())
                    return parent::callAction($action, $params);
                else
                    return parent::callAction('loginAction', $params);
            }
   }

    public function indexAction() {
        dd('main');
    }
    public function categoryAction() {

    }
    public function articleAction() {

    }

    public function loginAction() {
            dd('login');
    }

}