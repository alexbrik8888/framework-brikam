<?php

namespace App\Engine;

use Exception;

/**
 * Класс менеджер конфигурации приложения (Singleton).
 * Отвечает за загрузку и предоставление настроек из файла конфигурации.
 */
class Config
{
    /**
     * Путь к файлу конфигурации
     */
    private string $pathConfig;

    /**
     * Массив загруженных параметров конфигурации
     */
    private array $config = [];

    /**
     * Единый экземпляр класса Config
     */
    private static ?self $instance = null;

    /**
     * Приватный конструктор для реализации паттерна Singleton.
     * Автоматически загружает файл конфигурации при инициализации.
     *
     * @throws Exception Если файл конфигурации не найден
     */
    private function __construct()
    {
        // Абсолютный путь от корневой директории приложения
        $this->pathConfig = dirname(__DIR__, 2) . '/config/config.php';
        $this->init();
    }

    /**
     * Запрещаем клонирование объекта
     */
    private function __clone()
    {
    }

    /**
     * Возвращает единственный экземпляр класса Config.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Загрузка параметров из файла конфигурации в память
     *
     * @throws Exception Если файл конфигурации не существует
     */
    public function init(): void
    {
        if (!file_exists($this->pathConfig)) {
            throw new Exception("Файл конфигурации не найден по пути: {$this->pathConfig}");
        }

        $this->config = include $this->pathConfig;
    }

    /**
     * Установка пользовательского пути к файлу конфигурации и его перезагрузка
     *
     * @param string $pathConfig Абсолютный или относительный путь к файлу
     * @return $this
     * @throws Exception Если файл по новому пути не найден
     */
    public function setPathConfig(string $pathConfig): static
    {
        $this->pathConfig = $pathConfig;
        $this->init(); // Перезагружаем конфигурацию по новому пути

        return $this;
    }

    /**
     * Получение всей конфигурации или конкретной секции/ключа
     *
     * @param string|null $section Имя секции/ключа конфигурации
     * @param mixed $default Значение по умолчанию, если секция не найдена
     * @return mixed
     */
    public function getConfig(?string $section = null, mixed $default = null): mixed
    {
        // Если ключ не указан — возвращаем весь массив конфигурации
        if ($section === null) {
            return $this->config;
        }

        // Возвращаем конкретный раздел, если он существует, иначе $default
        return $this->config[$section] ?? $default;
    }
}