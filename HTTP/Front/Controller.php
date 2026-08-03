<?php
namespace HTTP\Front;
use App\Engine\BaseController;
use App\Engine\DB;
use App\Engine\Request;
use App\Engine\View;
use App\Model\Articl;
use App\Model\File;

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


    public function getImageAction() {
                $id = Request::getInstance()->getParam('id');
                $file = (new File())->find('id',$id)->getFirst();
                header('Content-Type: image/png');
                return  file_get_contents($file['file_path'].$file['system_name']);
    }
}