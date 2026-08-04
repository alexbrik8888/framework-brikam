<?php
if(file_exists( '../vendor/autoload.php'))
    require_once  '../vendor/autoload.php';
require_once  '../AutoLoad.php';
session_start();
$app = new \App\Engine\App();

