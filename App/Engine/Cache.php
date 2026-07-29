<?php

namespace App\Engine;

class Cache
{
    private $object = null;

    public static $inst = null;

    public function __construct()
    {
        $config = \App\Engine\Config::getInstance()->getConfig('cache');
        $this->object = new \Memcached();
        $this->object->addServer($config['host'], $config['port']);
    }

    public static function getInstance()
    {
        if (self::$inst == null)
            self::$inst = new Cache();
        return self::$inst;
    }

    public function get($key, $default = null)
    {
        $data = $this->object->get($key);
        if ($data === false)
            return $default;
        return $data;
    }

    public function set($key, $value, $timeSec = 3600)
    {
        return $this->object->set($key, $value, $timeSec);
    }

    public function delete($key, $callback = null)
    {
        return $this->object->delete($key);
    }

    public function flash()
    {
        $this->object->flush();
    }
}