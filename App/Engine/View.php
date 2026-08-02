<?php

namespace App\Engine;
use Smarty\Smarty;

class View {
    private $viewEngine;
    public function __construct() {
        $config = Config::getInstance()->getConfig('smarty');
        $this->viewEngine = new Smarty();
        $this->viewEngine->setTemplateDir($config['templateDir']);
        $this->viewEngine->setCacheDir($config['cacheDir']);
        $this->viewEngine->setConfigDir($config['configDir']);
        $this->viewEngine->setCompileDir($config['compiledDir']);
    }

    public function render($template, $data = array()) {
        $this->viewEngine->
    }
}