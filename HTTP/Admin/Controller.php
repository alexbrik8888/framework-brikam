<?php
namespace HTTP\Admin;
use App\Engine\Auth;
use App\Engine\BaseController;
use App\Engine\Cache;
use App\Engine\DB;
use App\Engine\Request;
use App\Engine\View;
use App\Model\Articl;
use App\Model\File;
use App\Model\Filter\Category;
use App\Model\Filter\CategoryArticl;
use App\Model\User\UserAmin;

class Controller extends BaseController {
   public function callAction($action, $params){
            if($action == 'loginAction'){
                return parent::callAction($action, $params);
            } else {
                if(Auth::getInstance()->setModel(UserAmin::class)->check())
                    return parent::callAction($action, $params);
                else
                    return parent::callAction('loginAction', $params);
            }
   }

    public function indexAction() {
        return  (new  View())->render('/Admin/main.tpl');
    }
    public function categoryAction() {
        $categor = new Category();
        $categor->setLimit(10);
        $param =  Request::getInstance()->getAllParams();

        if(Request::getInstance()->getMethod() == 'POST') {
            $categor->save($param);
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit; // Обязательно завершаем скрипт
        }
        if(Request::getInstance()->getMethod() == 'GET'){
            if(isset($param['name']) && !empty($param['name']))
                $categor->where('name','%'.$param['name'].'%','LIKE');
            if(isset($param['description']) && !empty($param['description']))
                 $categor->where('description','%'.$param['description'].'%','LIKE');
            if(isset($param['parent_id']) && !empty($param['parent_id']))
                $categor->where('parent_id',$param['parent_id'],'=');
            if(isset($param['page']) && !empty($param['page']))
                $categor->setPage($param['page']);
        }
        if(Request::getInstance()->getMethod() == 'DELETE'){
            $categor->where('id',Request::getInstance()->getParam('id'))->delete();
        }
        return  (new  View())->render('/Admin/category.tpl',[
            'category_list' =>$categor->getList() ,
            'pagination' =>$categor->getPagination(),
            'parent' =>  $categor->newQuery()->getList()
        ]);
    }

    public function articleListAction() {
        $article = new Articl();
        $article->setLimit(10);
        $param =  Request::getInstance()->getAllParams();
        if(Request::getInstance()->getMethod() == 'GET'){
            if(isset($param['name']) && !empty($param['name']))
                $article->where('name','%'.$param['name'].'%','LIKE','OR');
            if(isset($param['description']) && !empty($param['description']))
                $article->where('description','%'.$param['description'].'%','LIKE' ,'OR');
            if(isset($param['category_id']) && !empty($param['category_id']))
                $article->Query()->whereIN('category_id',$param['category_id'],'OR');
            if(isset($param['page']) && !empty($param['page'] ))
                $article->setPage($param['page']);
        }
        if(Request::getInstance()->getMethod() == 'DELETE'){
            $article->where('id',Request::getInstance()->getParam('id'))->delete();
        }
        return  (new  View())->render('/Admin/list_article.tpl',[
            'article_list' =>$article->getList() ,
            'pagination' =>$article->getPagination(),
            'category' =>  (new Category())->setLimit('')->getList(),
        ]);
    }

    public function articleAction() {
        $article = new Articl();
        $error = '';
        $param =  Request::getInstance()->getAllParams();
        if(Request::getInstance()->getMethod() == 'POST') {
            try {

                $dataArticle = $article->save($param);

                if ($dataArticle) {
                    $dataArticle['abstract_id'] = $dataArticle['id'];
                    $dataArticle['type_id'] = 1;
                    unset($dataArticle['id']);
                    $category  = [];
                    foreach ($param['category_id'] as $cId){
                        $category[] = ['category_id'=> $cId ,'articl_id'=>$dataArticle['abstract_id']];
                    }
                    (new  CategoryArticl())->find('articl_id',$dataArticle['abstract_id'])->delete();
                    $result =  (new  CategoryArticl())->insertMultiple($category);
                    if(!$result) {
                        $error = 'ошибка при сохранении категорий';
                    }
                    $result = (new File())->save(array_merge($dataArticle, $_FILES[array_key_first($_FILES)]));
                    if ($result) {
                        header('Location: ' . '/admin/list/article');
                        exit; // Обязательно завершаем скрипт
                    } else {
                        $error = 'ошибка при сохранении файла';
                    }
                } else {
                    $error = 'ошибка при сохранении данных';
                }
            } catch (\Throwable $e){
                d($e->getMessage(),$e->getCode(),$e->getFile(),$e->getLine());
            }
        }
        if(Request::getInstance()->getMethod() == 'GET')
            $article->find('id',Request::getInstance()->getParam('id'));
        return  (new  View())->render('/Admin/article.tpl',[
            'article' =>  $article->getFirst() ,
            'category' =>  (new Category())->setLimit('')->getList(),
            'error' => $error
        ]);
    }

    public function loginAction() {
       $error ='';
       if(Request::getInstance()->getMethod() == 'POST') {
           $params = Request::getInstance()->getAllParams();
           if(isset($params['email']) && isset($params['password'])) {
              $result = Auth::getInstance()->setModel(UserAmin::class)->checkCreadantion(['email'=> $params['email'],'password'=> md5($params['password'])]);
               if($result){
                   header('Location: /admin');
                   exit();
              } else {
                   $error = 'Неверный  логин  пароль';
               }
           } else  {
               $error = 'Нет заполнены данные';
           }
       }

        return  (new  View())->render('/Admin/login.tpl',['error_message'=>$error]);
    }

}