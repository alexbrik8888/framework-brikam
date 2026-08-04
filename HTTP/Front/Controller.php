<?php
namespace HTTP\Front;
use App\Engine\BaseController;

use App\Engine\DbExceptionSQL;
use App\Engine\Request;
use App\Engine\View;

use App\Model\Articl;
use App\Model\ArticleView;
use App\Model\File;
use App\Model\Filter\Category;
use App\Model\Filter\CategoryArticl;


class Controller extends BaseController {

    private $mainCategory;
    public function __construct() {
        $catgory  =  new Category();
        $catgory->Query()->where('parent_id',0);
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
        $param = Request::getInstance()->getAllParams();
        $articl = new CategoryArticl();
        $articl->setLimit(2)
            ->coonectArticlView()
            ->coonectImageArticl()
            ->coonectArticl()->where($articl->getTable().'.category_id',$catgory_id);
        if(isset($param['order']) && !empty($param['order'] )){
            switch ($param['order']) {
                case 'date':
                    $articl->Query()->OrderBy('create_date','DESC');
                    break;
                case 'view':
                    $articl->Query()->OrderBy('view','DESC');
                    break;
            }
        }
        if(isset($param['page']) && !empty($param['page'] ))
            $articl->setPage($param['page']);
        return  (new  View())->render('/Front/catalog.tpl',[
            'main_category' =>$this->mainCategory,
            'category' => (new Category())->find('id',$catgory_id)->getFirst(),
            'list_articl' => $articl->getList(),
            'pagination' => $articl->getPagination()
        ]);
    }
    public function detailsAction() {
        $articl_id =  Request::getInstance()->getParam('id');
        $articl = (new Articl())->coonectArticlView()->find('id',$articl_id)->getFirst();
        if($articl) {
            (new ArticleView())->save(['articl_id' => $articl['id'], 'view' => $articl['view']+1]);
            return (new  View())->render('/Front/details.tpl', [
                'main_category' => $this->mainCategory,
            ]);
        } else {
            header('Location: /');
            exit();
        }
    }


    public function getImageAction() {
                $id = Request::getInstance()->getParam('id');
                $file = (new File())->find('id',$id)->getFirst();
                header('Content-Type: image/png');
                return  file_get_contents($file['file_path'].$file['system_name']);
    }
}