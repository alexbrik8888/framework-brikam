<?php
namespace HTTP\Front;
use App\Engine\BaseController;

use App\Engine\DbExceptionSQL;
use App\Engine\Request;
use App\Engine\View;

use App\Model\Articl;
use App\Model\File;
use App\Model\Filter\Category;
use App\Model\Filter\CategoryArticl;


class Controller extends BaseController {

    private $mainCategory;
    public function __construct() {
        $catgory  =  new Category();
        $catgory->Query()->whereRaw( (new DbExceptionSQL(' parent_id IS NULL ')));
        $this->mainCategory = $catgory->getList();
    }

    public function callAction($action, $params){
            return parent::callAction($action, $params);

   }

    public function indexAction() {


        $listcategory  = new Articl();
        return  (new  View())->render('/Front/main.tpl',[
            'main_category' =>$this->mainCategory,
            'list_category' =>$listcategory->getLastTheeArticlCategoy(),
        ]);

   }
    public function catalogAction() {
        $catgory_id =  Request::getInstance()->getParam('id');
        if(!$catgory_id){
            header('Location: /');
            exit();
        }

        $articl = new CategoryArticl();
        $articl->setLimit(2)->coonectImageArticl()->coonectArticl()->where($articl->getTable().'.category_id',$catgory_id);
        return  (new  View())->render('/Front/catalog.tpl',[
            'main_category' =>$this->mainCategory,
            'category' => (new Category())->find('id',$catgory_id)->getFirst(),
            'list_articl' => $articl->getList(),
            'pagination' => $articl->getPagination()
        ]);
    }
    public function detailsAction() {
        return  (new  View())->render('/Front/details.tpl',[
            'main_category' =>$this->mainCategory,
        ]);
    }


    public function getImageAction() {
                $id = Request::getInstance()->getParam('id');
                $file = (new File())->find('id',$id)->getFirst();
                header('Content-Type: image/png');
                return  file_get_contents($file['file_path'].$file['system_name']);
    }
}