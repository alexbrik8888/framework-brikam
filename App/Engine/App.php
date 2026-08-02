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

    public function Init() {
        $this->request = \App\Engine\Request::getInstance();
        $this->route = \App\Engine\Route::getInstance();
        $this->config = \App\Engine\Config::getInstance();
        $this->findController();
    }

    private function findController() {
        if(strpos($this->request->getPath(),'/public/') ===false) {
            $startTime = microtime(true);
            $startMemory = memory_get_usage();
            $this->useController = $this->route->getByUrlRoute($this->request->getPath());
            if (!$this->useController)
                throw  new \Exception('Даного адреса нет в системе');
            $nameControler = str_replace('\\\\', '\\', $this->useController->getController());
            $action = $this->useController->getAction();
            echo (new  ('\\' . $nameControler)())->callAction($action, $this->request->getAllParams());
            $executionTime = (microtime(true) - $startTime) * 1000; // в ms
            $memoryUsed = (memory_get_usage() - $startMemory) / 1024; // в KB
            echo "Время выполнения: {$executionTime} ms\n";
            echo "Использовано памяти: {$memoryUsed} KB\n";
        } else {
            echo file_get_contents(dirname(__DIR__,2).$this->request->getPath());
        }
    }
}