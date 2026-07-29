<?php

namespace App\Engine;
class ErrorLog
{
    private $path_log = __DIR__ . '../log.txt';
    public static $inst = null;
    private $contextFile = null;

    public function __construct()
    {
        if (file_exists($this->path_log)) {
            $this->contextFile = fopen($this->path_log, 'a');
        } else
            throw new \Exception('Файла нет!!!!! log.txt');
    }

    public function addLog($log)
    {
        if (!is_null($this->contextFile) && file_exists($this->path_log))
            fwrite($this->contextFile, var_export($log, true));
    }

    public function __destruct()
    {
        if (!is_null($this->contextFile))
            fclose($this->contextFile);
    }

    public static function getInstance()
    {
        if (self::$inst == null) {
            self::$inst = new ErrorLog();
        }
        return self::$inst;
    }


}