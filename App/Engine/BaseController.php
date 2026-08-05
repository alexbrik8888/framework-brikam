<?php
namespace App\Engine;
class BaseController
{
    protected $params;
    protected $section;
    public function __construct() {}
    public function callAction($method, $parameters) {
            if(method_exists($this, $method)) {}
                return $this->{$method}($parameters);
            return null;
    }

}