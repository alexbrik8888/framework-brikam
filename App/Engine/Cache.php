<?php

namespace App\Engine;

use Memcached;

/**
 * Класс-обертка для работы с кэшем Memcached (Singleton).
 */
class Cache
{
    /**
     * Экземпляр драйвера Memcached
     */
    private ?Memcached $driver = null;

    /**
     * Единый экземпляр класса Cache
     */
    private static ?self $instance = null;

    /**
     * Приватный конструктор для реализации паттерна Singleton.
     * Инициализирует подключение к Memcached на основе конфигурации.
     */
    private function __construct()
    {
        $config = Config::getInstance()->getConfig('cache');

        // Используем persistent_id для предотвращения повторных открытий соединений
        $this->driver = new Memcached('app_cache_pool');

        // Добавляем сервер только если список серверов ещё пуст
        if (empty($this->driver->getServerList())) {
            $host = $config['host'] ?? '127.0.0.1';
            $port = (int)($config['port'] ?? 11211);

            $this->driver->addServer($host, $port);
        }
    }

    /**
     * Запрещаем клонирование объекта
     */
    private function __clone()
    {
    }

    /**
     * Возвращает единственный экземпляр класса Cache.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Получает значение из кэша по ключу.
     *
     * @param string $key Ключ кэша
     * @param mixed $default Значение по умолчанию, если ключ не найден
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $data = $this->driver->get($key);

        // Точная проверка: найден ли ключ в Memcached (учитывает случай, когда в кэше записано boolean false)
        if ($this->driver->getResultCode() !== Memcached::RES_SUCCESS) {
            return $default;
        }

        return $data;
    }

    /**
     * Сохраняет значение в кэш.
     *
     * @param string $key Ключ кэша
     * @param mixed $value Сохраняемые данные
     * @param int $ttl Время жизни кэша в секундах (по умолчанию 3600 сек / 1 час)
     * @return bool True в случае успеха, false при ошибке
     */
    public function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        return $this->driver->set($key, $value, $ttl);
    }

    /**
     * Удаляет элемент из кэша по ключу.
     *
     * @param string $key Ключ для удаления
     * @return bool
     */
    public function delete(string $key): bool
    {
        return $this->driver->delete($key);
    }

    /**
     * Полностью очищает весь кэш.
     *
     * @return bool
     */
    public function flush(): bool
    {
        return $this->driver->flush();
    }

    /**
     * Псевдоним (Alias) для метода flush() для сохранения обратной совместимости.
     *
     * @deprecated Используйте метод flush()
     */
    public function flash(): bool
    {
        return $this->flush();
    }
}