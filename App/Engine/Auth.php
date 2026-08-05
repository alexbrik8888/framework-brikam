<?php

namespace App\Engine;

use App\Engine\Extension\AuthFunction;

class Auth
{
    use AuthFunction;

    /**
     * Единственный экземпляр класса (Singleton)
     */
    private static ?self $instance = null;

    private ?int $id = null;
    private ?int $group = null;
    private mixed $gate = null; // Поправлено с $gait (права/шлюз авторизации)
    private ?object $user = null;

    /**
     * Объявлено свойство model для исключения ошибок Deprecated в PHP 8.2+
     */
    private ?object $model = null;

    /**
     * Закрываем конструктор для реализации паттерна Singleton
     */
    private function __construct()
    {
    }

    /**
     * Запрещаем клонирование объекта
     */
    private function __clone()
    {
    }

    /**
     * Получение единственного экземпляра класса
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Установка модели работы с пользователями
     *
     * @param string|object $model Имя класса или экземпляр класса
     * @return $this
     */
    public function setModel(string|object $model): static
    {
        // Проверяем, является ли переданный класс наследником App\Engine\Model
        if (is_subclass_of($model, 'App\\Engine\\Model')) {
            $this->model = is_string($model) ? new $model() : $model;
        }

        return $this;
    }

    /**
     * Получение ID текущего пользователя
     */
    public function getUserID(): ?int
    {
        return $this->id;
    }

    /**
     * Получение ID группы/роли пользователя
     */
    public function getGroupID(): ?int
    {
        return $this->group;
    }

    /**
     * Получение объекта текущего пользователя
     */
    public function getUser(): ?object
    {
        return $this->user;
    }
}