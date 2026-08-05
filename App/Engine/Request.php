<?php

namespace App\Engine;

/**
 * Класс HTTP-запроса (Singleton).
 * Инкапсулирует данные текущего HTTP-запроса (GET, POST, JSON-тело, заголовки, IP).
 */
class Request
{
    private string $method;
    private string $uri;
    private array $params = [];
    private array|string|null $body = null;
    private array $headers = [];
    private array $cookies = [];
    private string $protocol;
    private string $path;
    private string $query;

    /**
     * Единый экземпляр класса Request
     */
    private static ?self $instance = null;

    /**
     * Приватный конструктор для реализации паттерна Singleton.
     */
    private function __construct()
    {
        $this->method   = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri      = $_SERVER['REQUEST_URI'] ?? '/';
        $this->protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
        $this->cookies  = $_COOKIE;
        $this->query    = $_SERVER['QUERY_STRING'] ?? '';

        // Разбор пути запроса без GET-параметров
        $this->path     = parse_url($this->uri, PHP_URL_PATH) ?? '/';

        $this->headers  = $this->parseHeaders();
        $this->params   = $this->getAllParams();
    }

    /**
     * Запрещаем клонирование объекта
     */
    private function __clone()
    {
    }

    /**
     * Возвращает единственный экземпляр класса Request
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Сбор и парсинг всех входных параметров (GET, POST, JSON, Raw Body)
     */
    public function getAllParams(): array
    {
        if (!empty($this->params)) {
            return $this->params;
        }

        $this->params = $_GET;

        if (!empty($_POST)) {
            $this->params = array_merge($this->params, $_POST);
        } else {
            // Если $_POST пуст (например, PUT/DELETE или JSON-запрос)
            $rawInput = file_get_contents('php://input');

            if (!empty($rawInput)) {
                $decodedJson = json_decode($rawInput, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedJson)) {
                    $this->body   = $decodedJson;
                    $this->params = array_merge($this->params, $decodedJson);
                } else {
                    $this->body = $rawInput;
                    parse_str($rawInput, $parsedInput);
                    if (is_array($parsedInput)) {
                        $this->params = array_merge($this->params, $parsedInput);
                    }
                }
            }
        }

        return $this->params;
    }

    /**
     * Получение конкретного параметра по ключу
     *
     * @param string $key Имя параметра
     * @param mixed $default Значение по умолчанию
     * @return mixed
     */
    public function getParam(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * Возвращает HTTP-метод запроса (GET, POST, PUT, DELETE)
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Возвращает полный URI запроса
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Возвращает версию протокола
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * Возвращает путь запроса без GET-параметров
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Возвращает строку GET-запроса (QUERY_STRING)
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Возвращает тело запроса (декодированный JSON-массив или сырую строку)
     */
    public function getBody(): array|string|null
    {
        return $this->body;
    }

    /**
     * Возвращает массив всех заголовков запроса
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Возвращает массив Cookie
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * Определение IP-адреса клиента с учетом прокси-заголовков и валидацией
     */
    public function getUserIP(): string
    {
        $headersToCheck = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($headersToCheck as $header) {
            if (!empty($_SERVER[$header])) {
                // Если передано несколько IP через запятую — берем первый
                foreach (explode(',', $_SERVER[$header]) as $ip) {
                    $ip = trim($ip);
                    // Валидация IP-адреса (IPv4 или IPv6)
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE | FILTER_FLAG_NO_PRIV_RANGE) !== false) {
                        return $ip;
                    }
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return 'UNKNOWN';
    }

    /**
     * Совместимое извлечение заголовков HTTP-запроса
     */
    private function parseHeaders(): array
    {
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            if ($headers !== false) {
                return $headers;
            }
        }

        // Fallback-парсинг заголовков из $_SERVER для Nginx / FastCGI
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $headers[$name] = $value;
            }
        }

        return $headers;
    }
}