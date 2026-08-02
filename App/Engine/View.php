<?php

namespace App\Engine;
use Smarty\Smarty;

class View {
    private $viewEngine;
    private $config = null;
    public function __construct() {
        $this->config = Config::getInstance()->getConfig('smarty');
        $this->viewEngine = new Smarty();
        $this->viewEngine->setTemplateDir($this->config['templateDir']);
        $this->viewEngine->setCacheDir($this->config['cacheDir']);
        $this->viewEngine->setConfigDir($this->config['configDir']);
        $this->viewEngine->setCompileDir($this->config['compiledDir']);
        $this->viewEngine->setTemplateDir($this->config['templateDir']);
        $this->viewEngine->addTemplateDir($this->config['pages'], 'pages');
    }

    public function render($template, $data = array()) {
        $result = null;
        ob_start();
        $this->viewEngine->display($this->config['pages'].$template);
        $result = ob_get_clean();
        return $result;
    }
}