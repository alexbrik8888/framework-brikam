<?php

namespace App\Engine;

use Route;

class App
{
    private $request;
    private $route;

    private $config;
    private $view;

    private $useController = null;

    public function __construct()
    {
        $this->Init();
    }

    public function Init()
    {
        $this->request = \App\Engine\Request::getInstance();
        $this->route = \App\Engine\Route::getInstance();
        $this->config = \App\Engine\Config::getInstance();
        $this->findController();
    }

    private function findController()
    {
        $this->useController = $this->route->getByUrlRoute($this->request->getPath());
        if (!$this->useController)
            throw  new \Exception('Даного адреса нет в системе');
        $nameControler = str_replace('\\\\', '\\', $this->useController->getController());
        $action = $this->useController->getAction();
         (new  ('\\'.$nameControler)())->callAction($action,$this->request->getAllParams());
    }


    public function run()
    {

    }
}