<?php
namespace HTTP\Front;
use App\Engine\BaseController;
use App\Engine\DB;

class Controller extends BaseController {
   public function callAction($action, $params){
            return parent::callAction($action, $params);
   }

    public function indexAction() {
      $t =  new DB();
      $t->insertMultiple('articl',[
          ['name'=>'test1',
          'text'=>'test1',
          'description'=>'test1',
              'updated_at'=>date('Y-m-d H:i:s'),],
          ['name'=>'tes2',
              'text'=>'tes2',
              'description'=>'tes2',
              'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'test3',
              'text'=>'test3',
              'description'=>'test3',
              'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'test4',
              'text'=>'test5',
              'description'=>'test5',
              'updated_at'=>date('Y-m-d H:i:s')],
          ['name'=>'test6',
              'text'=>'test7',
              'description'=>'test8',
              'updated_at'=>date('Y-m-d H:i:s')],
        ]);
    }
    public function catalogAction() {

    }
    public function detailsAction() {

    }
}