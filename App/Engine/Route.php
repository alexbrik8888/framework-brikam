<?php

namespace App\Engine;

use Exception;

/**
 * Класс, представляющий отдельную точку маршрутизации (узел).
 */
class RouteNode
{
    /**
     * Создает экземпляр маршрута.
     * Используется Feature PHP 8.0+ (Property Promotion).
     *
     * @param string $url URL-адрес маршрута
     * @param string $controller Имя или путь к контроллеру
     * @param string $action Метод (действие) контроллера
     */
    public function __construct(
        private string $url,
        private string $controller,
        private string $action
    ) {}

    /**
     * Возвращает имя контроллера.
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * Возвращает действие (метод) контроллера.
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Возвращает URL маршрута.
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}

/**
 * Класс менеджер маршрутов (Роутер).
 * Реализует паттерн Singleton.
 */
class Route
{
    /** @var string Ключ для хранения маршрутов в кэше */
    public const KEY_CACHE = 'route_site';

    /** @var array<string, RouteNode> Ассоциативный массив маршрутов [url => RouteNode] */
    private array $route = [];

    /** @var bool Флаг, указывающий, были ли данные загружены из кэша */
    private bool $flagCache = false;

    /** @var Route|null Единственный экземпляр класса (Singleton) */
    private static ?Route $inst = null;

    /**
     * Закрытый конструктор для запрета создания через new.
     */
    private function __construct()
    {
        $this->init();
    }

    /**
     * Запрещаем клонирование объекта (Singleton).
     */
    private function __clone() {}

    /**
     * Запрещаем десериализацию объекта (Singleton).
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    /**
     * Получение единственного экземпляра класса (Singleton).
     *
     * @return Route
     */
    public static function getInstance(): Route
    {
        if (self::$inst === null) {
            self::$inst = new self();
        }
        return self::$inst;
    }

    /**
     * Проверяет, закружены ли маршруты из кэша.
     */
    public function isLoadCache(): bool
    {
        return $this->flagCache;
    }

    /**
     * Инициализация маршрутов: из переданного массива или из кэша.
     *
     * @param array<RouteNode>|null $arrRouteNode Массив объектов RouteNode
     * @throws Exception Если передан некорректный элемент массива
     */
    public function init(?array $arrRouteNode = null): void
    {
        // 1. Загрузка из переданного массива
        if ($arrRouteNode !== null) {
            $this->route = [];
            foreach ($arrRouteNode as $routeNode) {
                if (!($routeNode instanceof RouteNode)) {
                    throw new Exception('$arrRouteNode должен содержать только объекты RouteNode');
                }
                $this->route[$routeNode->getUrl()] = $routeNode;
            }
            $this->flagCache = false;
            return;
        }

        // 2. Если массив не передан — пробуем загрузить из кэша
        $cachedRoutes = Cache::getInstance()->get(self::KEY_CACHE, []);

        if (is_array($cachedRoutes) && !empty($cachedRoutes)) {
            $firstElement = reset($cachedRoutes);
            if ($firstElement instanceof RouteNode) {
                $this->route = $cachedRoutes;
                $this->flagCache = true;
            }
        }
    }

    /**
     * Добавляет новый маршрут в реестр.
     *
     * @param string $url URL-адрес
     * @param string $pathController Путь/Имя контроллера
     * @param string $action Действие (метод)
     * @return $this Возвращает $this для цепочки вызовов (Fluent Interface)
     */
    public function addRoute(string $url, string $pathController, string $action): self
    {
        $this->route[$url] = new RouteNode($url, $pathController, $action);
        return $this;
    }

    /**
     * Сохраняет текущий список маршрутов в кэш.
     *
     * @return bool Успешность сохранения
     */
    public function saveCache(): bool
    {
        return Cache::getInstance()->set(self::KEY_CACHE, $this->route);
    }

    /**
     * Возвращает список всех зарегистрированных URL-адресов.
     *
     * @return array<string>
     */
    public function getListUrl(): array
    {
        return array_keys($this->route);
    }

    /**
     * Находит первый узел маршрута по имени контроллера.
     *
     * @param string $controllerName Имя контроллера
     * @return RouteNode|null Узел маршрута или null, если не найден
     */
    public function getByController(string $controllerName): ?RouteNode
    {
        foreach ($this->route as $route) {
            if ($route->getController() === $controllerName) {
                return $route;
            }
        }
        return null;
    }

    /**
     * Получает узел маршрута по URL.
     *
     * @param string $url
     * @return RouteNode|null
     */
    public function getByUrlRoute(string $url): ?RouteNode
    {
        return $this->route[$url] ?? null;
    }

    /**
     * Получает имя контроллера по URL.
     *
     * @param string $url
     * @return string|null Имя контроллера или null
     */
    public function getByUrlController(string $url): ?string
    {
        return isset($this->route[$url]) ? $this->route[$url]->getController() : null;
    }

    /**
     * Получает имя экшена по URL.
     *
     * @param string $url
     * @return string|null Имя действия или null
     */
    public function getByUrlAction(string $url): ?string
    {
        return isset($this->route[$url]) ? $this->route[$url]->getAction() : null;
    }
}