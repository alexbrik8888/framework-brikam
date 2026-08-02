<?php
use App\Engine\Route;
Route::getInstance()->addRoute('/',\HTTP\Front\Controller::class,'indexAction');
Route::getInstance()->addRoute('/catalog',\HTTP\Front\TestController::class,'catalogAction');
Route::getInstance()->addRoute('/articl',\HTTP\Front\TestController::class,'articlAction');


Route::getInstance()->addRoute('/admin',\HTTP\Admin\Controller::class,'indexAction');
Route::getInstance()->addRoute('/admin/category',\HTTP\Admin\Controller::class,'categoryAction');
Route::getInstance()->addRoute('/admin/articl',\HTTP\Admin\Controller::class,'articleAction');
Route::getInstance()->addRoute('/admin/articl',\HTTP\Admin\Controller::class,'loginAction');