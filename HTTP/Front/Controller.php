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
        $articl->setLimit(5)
            ->coonectArticlView()
            ->coonectImageArticl()
            ->coonectArticl()->where($articl->getTable().'.category_id',$catgory_id);
        if(isset($param['order']) && !empty($param['order'] )){
            switch ($param['order']) {
                case 'date':
                    $articl->Query()->order('articl.created_at','DESC');
                    break;
                case 'view':
                    $articl->Query()->order('articl_view.view','DESC');
                    break;
            }
        } else {
            $articl->Query()->order('articl.created_at','DESC');
        }
        if(isset($param['page']) && !empty($param['page'] ))
            $articl->setPage($param['page']);
        return  (new  View())->render('/Front/catalog.tpl',[
            'main_category' =>$this->mainCategory,
            'category' => (new Category())->find('id',$catgory_id)->getFirst(),
            'list_articl' => $articl->getList(),
            'pagination' => $articl->getPagination(),
            'query_param' => $param,
        ]);
    }
    public function detailsAction() {
        $articl_id =  Request::getInstance()->getParam('id');
        $articl_ = new Articl('*');
        $articl_->connectImageSimple()->connectCategory()->coonectArticlView()->where($articl_->getTable().'.id',$articl_id);
        $articl = $articl_ ->getFirst();
        $param = Request::getInstance()->getAllParams();
        $categories =  array_column($articl['category'],'id');
        $recomnendation_ = new CategoryArticl();
        $recomnendation_->Query()->whereIN($recomnendation_->getTable().'.category_id',$categories)
            ->order($articl_->getTable().'.created_at','DESC');
        $recomnendation = $recomnendation_->setLimit(3)->coonectImageArticl()->coonectArticl()->getList();
        if($articl) {
            (new ArticleView())->save(['articl_id' => $articl['id'], 'view' => $articl['view']+1]);
            return (new View())->render('/Front/details.tpl', [
                'main_category' => $this->mainCategory,
                'query_param' => $param,
                'articl' => $articl,
                'recommendation'=>$recomnendation
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