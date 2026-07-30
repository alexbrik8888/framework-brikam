<?php
namespace HTTP\Front;
use App\Engine\BaseController;
class Controller extends BaseController {
   public function callAction($action, $params){
            return parent::callAction($action, $params);
   }

    public function indexAction() {
        $smarty = new \Smarty\Smarty();
    }
    public function catalogAction() {

    }
    public function detailsAction() {

    }
}