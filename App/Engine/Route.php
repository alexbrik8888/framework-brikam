<?php


namespace App\Engine;

class RouteNode {

    private $controller;
    private $action;
    private $url;

    public function __construct($url, $controller, $action) {
        $this->controller = $controller;
        $this->action = $action;
        $this->url = $url;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getUrl()
    {
        return $this->url;
    }
}

class Route
{
    const KEY_CACHE = 'route_site';
    private $route = [];
    private $flagCache = false;

    public static $inst = null;

    public function __construct()
    {
        $this->Init();
    }

    public static function getInstance()
    {
        if (self::$inst == null) {
            self::$inst = new Route();
        }
        return self::$inst;
    }

    public function isLoadCache()
    {
        return $this->flagCache;
    }

    public function Init($arrRouteNode = null)
    {
        if (!is_null($arrRouteNode)) {
            if (is_array($arrRouteNode) && count($arrRouteNode) && $arrRouteNode[0] instanceof RouteNode) {
                foreach ($arrRouteNode as $route) {
                    $this->route[$route->getUrl()] = $route;
                }
            } else {
                throw  new \Exception('$arrRouteNode должен быть массивом и каждый элкмент должен бьть RouteNode');
            }
        }
        $this->route = \App\Engine\Cache::getInstance()->get(self::KEY_CACHE, []);
        if (count($this->route) != 0 && ($this->route[array_key_first($this->route)]) instanceof RouteNode) {
            $this->flagCache = true;
        }
    }

    public function addRoute($url, $pathController, $action)
    {
        if (!is_string($url) && !is_string($pathController) && !is_string($action))
            throw  new \Exception('Не верные данные для функции должны быть строкой');
        $this->route[$url] = new RouteNode($url, $pathController, $action);;
        return $this;
    }

    public function saveCache()
    {
        return \App\Engine\Cache::getInstance()->set(self::KEY_CACHE, $this->route);
    }

    public function getListUrl()
    {
        return array_keys($this->route);
    }


    public function getByController($controllerNama)
    {
        if (!is_string($controllerNama))
            return false;
        foreach ($this->route as $route) {
            if ($route->getController() == $controllerNama) {
                return $route;
            }
        }
        return false;
    }

    public function getByUrlRoute($url)
    {
        if (!isset($this->route[$url]))
            return false;
        return $this->route[$url];
    }

    public function getByUrlController($url)
    {
        if (!isset($this->route[$url]))
            return false;
        return $this->route[$url]->getController();
    }

    public function getByUrlAction($url){
        dd($this->route);
        if (!isset($this->route[$url]))
            return false;
        return $this->route[$url]->getAction();
    }
}