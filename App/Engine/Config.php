<?php

namespace App\Engine;
class Config
{
    private $path_config = '../config/config.php';

    private $config = [];

    public static $inst = null;


    public function __construct()
    {
        $this->Init();
    }

    public static function getInstance()
    {
        if (is_null(self::$inst))
            self::$inst = new Config();
        return self::$inst;
    }

    public function Init()
    {
        if (!file_exists($this->path_config))
            throw new \Exception('Конфиг файла нет');
        $this->config = include $this->path_config;
    }

    public function SetPathConfig($path_config)
    {
        $this->path_config = $path_config;
        return $this;
    }

    public function getConfig($section = null)
    {
        if (is_null($section))
            return $this->config;
        if (isset($this->config[$section]))
            return $this->config[$section];
        return false;
    }
}