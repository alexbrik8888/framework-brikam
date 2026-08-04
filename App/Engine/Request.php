<?php

namespace App\Engine;
class Request
{
    private $method;
    private $uri;
    private $params;
    private $body;
    private $headers;
    private $cookies;
    private $protocol;
    private $path;
    private $query;
    public static $inst = null;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->params = $this->getAllParams();
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->protocol = $_SERVER['SERVER_PROTOCOL'];
        $this->headers = getallheaders();
        $this->cookies = $_COOKIE;
        $this->path = explode("?", $_SERVER['REQUEST_URI'])[0];
        $this->query = $_SERVER['QUERY_STRING'];;
    }

    public function getAllParams(): array
    {
        $this->params = $_GET;
        if (!empty($_POST)) {
            $this->params = array_merge($this->params, $_POST);
        } else {
            $rawInput = file_get_contents('php://input');
            if (!empty($rawInput)) {
                $this->body = json_decode($rawInput, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($this->body)) {
                     $this->params = array_merge($this->params, $this->body);
                } else {
                    parse_str($rawInput, $parsedInput);
                    if (is_array($parsedInput)) {
                        $this->params = array_merge($this->params, $parsedInput);
                    }
                }
            }
        }
        return $this->params;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getProtocol()
    {
        return $this->protocol;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function getParam($key)
    {
        if (empty($this->params))
            return false;
        if (!isset($this->params[$key]))
            return false;
        return $this->params[$key];
    }

    function getUserIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public static function getInstance()
    {
        if (self::$inst == null) {
            self::$inst = new self();
        }
        return self::$inst;
    }

}