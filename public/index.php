<?php

require_once  '../AutoLoad.php';
$startTime = microtime(true);
$startMemory = memory_get_usage();
session_start();
$app = new \App\Engine\App();


$executionTime = (microtime(true) - $startTime) * 1000; // в ms
$memoryUsed = (memory_get_usage() - $startMemory) / 1024; // в KB
 echo "Время выполнения: {$executionTime} ms\n";
 echo "Использовано памяти: {$memoryUsed} KB\n";

