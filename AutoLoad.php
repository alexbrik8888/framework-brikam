<?php
require_once  'App\Engine\FunGlobal\funGlobal.php';
spl_autoload_register(function ($class) {
    $path = __DIR__.'/'.str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

session_set_cookie_params(\App\Engine\Config::getInstance()->getConfig('session'));
session_start();

$inc = \App\Engine\Config::getInstance()->getConfig('boot_include');
$rootDir = \App\Engine\Config::getInstance()->getConfig('root_dir');
if(is_array($inc )) {
     foreach ($inc as $file) {
         require_once $rootDir.$file;
     }
}