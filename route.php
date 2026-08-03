<?php
use App\Engine\Route;
Route::getInstance()->addRoute('/',\HTTP\Front\Controller::class,'indexAction');
Route::getInstance()->addRoute('/catalog',\HTTP\Front\Controller::class,'catalogAction');
Route::getInstance()->addRoute('/article',\HTTP\Front\Controller::class,'detailsAction');

Route::getInstance()->addRoute('/file/image',\HTTP\Front\Controller::class,'getImageAction');



Route::getInstance()->addRoute('/admin',\HTTP\Admin\Controller::class,'indexAction');
Route::getInstance()->addRoute('/admin/category',\HTTP\Admin\Controller::class,'categoryAction');
Route::getInstance()->addRoute('/admin/article',\HTTP\Admin\Controller::class,'articleAction');
Route::getInstance()->addRoute('/admin/list/article',\HTTP\Admin\Controller::class,'articleListAction');
Route::getInstance()->addRoute('/admin/login',\HTTP\Admin\Controller::class,'loginAction');
Route::getInstance()->addRoute('/admin/logout',\HTTP\Admin\Controller::class,'logoutAction');