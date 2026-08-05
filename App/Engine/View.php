<?php

namespace App\Engine;

use Smarty\Smarty;

/**
 * Класс-обертка над шаблонизатором Smarty.
 * Отвечает за инициализацию движка представлений и рендеринг шаблонов.
 */
class View
{
    /**
     * Экземпляр шаблонизатора Smarty.
     * @var Smarty
     */
    private Smarty $viewEngine;

    /**
     * Настройки конфигурации Smarty из файла конфигурации.
     * @var array|null
     */
    private ?array $config = null;

    /**
     * Конструктор класса View.
     * Загружает конфигурацию, инициализирует Smarty, задает директивы и регистрирует плагины.
     */
    public function __construct()
    {
        // Загрузка конфигурации для Smarty из центрального конфига
        $this->config = Config::getInstance()->getConfig('smarty');

        // Инициализация основного объекта Smarty
        $this->viewEngine = new Smarty();

        // Установка стандартных директорий для работы шаблонизатора
        $this->viewEngine->setTemplateDir($this->config['templateDir']);
        $this->viewEngine->setCacheDir($this->config['cacheDir']);
        $this->viewEngine->setConfigDir($this->config['configDir']);
        $this->viewEngine->setCompileDir($this->config['compiledDir']);

        // Добавление именованной директории 'pages' для страниц
        $this->viewEngine->addTemplateDir($this->config['pages'], 'pages');

        // Регистрация кастомного модификатора 'build_http_url' для генерации GET-параметров в шаблонах
        $this->viewEngine->registerPlugin(
            Smarty::PLUGIN_MODIFIER,
            'build_http_url',
            function (array $array, array $add = []): string {
                return http_build_query(array_merge($array, $add));
            }
        );
    }

    /**
     * Рендерит указанный шаблон с переданными данными.
     *
     * @param string $template Имя файла шаблона (относительно директории страниц)
     * @param array $data Переменные для передачи в шаблон [ключ => значение]
     * @return string Сформированный HTML-код
     */
    public function render(string $template, array $data = []): string
    {
        // Передача массива данных в шаблон Smarty
        foreach ($data as $key => $value) {
            // Передаем значение, если оно не пустое (0, false и '0' также считаются за валидные данные)
            if ($value !== null && $value !== '') {
                $this->viewEngine->assign($key, $value);
            }
        }

        // Автоматическая передача текущего пути в шаблон для подстветки активного меню
        $this->viewEngine->assign('active_page', Request::getInstance()->getPath());

        // Формирование полного пути к файлу страницы
        $templatePath = $this->config['pages'] . $template;

        // Использование родного метода fetch() вместо буфера вывода (ob_start)
        return $this->viewEngine->fetch($templatePath);
    }
}