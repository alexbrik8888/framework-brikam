<?php
namespace HTTP\Front;
use App\Engine\BaseController;
use App\Engine\DB;
use App\Engine\View;
use App\Model\Articl;

class Controller extends BaseController {
    public function __construct() {
    }

    public function callAction($action, $params){
            return parent::callAction($action, $params);
   }

    public function indexAction() {
        return  (new  View())->render('/Front/main.tpl');

   }
    public function catalogAction() {
        return  (new  View())->render('/Front/catalog.tpl');
    }
    public function detailsAction() {
        return  (new  View())->render('/Front/details.tpl');
    }
}