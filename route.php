<?php
use App\Engine\Route;
Route::getInstance()->addRoute('/',\HTTP\Front\Controller::class,'indexAction');
Route::getInstance()->addRoute('/test',\HTTP\Front\TestController::class,'testAction');