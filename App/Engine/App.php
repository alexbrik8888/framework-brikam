<?php

namespace App\Engine;

use Exception;

class App
{
    private Request $request;
    private Route $route;
    private Config $config;

    /**
     * @var object|null Найденный контроллер/маршрут
     */
    private ?object $useController = null;

    public function __construct()
    {
        $this->init();
    }

    /**
     * Инициализация базовых компонентов приложения
     */
    public function init(): void
    {
        $this->request = Request::getInstance();
        $this->route = Route::getInstance();
        $this->config = Config::getInstance();

        $this->findController();
    }

    /**
     * Поиск и запуск нужного контроллера или отдаче статического файла
     *
     * @throws Exception Если маршрут не найден
     */
    private function findController(): void
    {
        $path = $this->request->getPath();

        // Проверка: запрос к статике или к динамическому контроллеру
        if (!str_contains($path, '/public/')) {
            $startTime = microtime(true);
            $startMemory = memory_get_usage();

            $this->useController = $this->route->getByUrlRoute($path);

            if (!$this->useController) {
                header('HTTP/1.1 404 Not Found');
                exit();
            }

            // Нормализация имени класса контроллера
            $controllerClass = '\\' . ltrim(str_replace('\\\\', '\\', $this->useController->getController()), '\\');
            $action = $this->useController->getAction();

            // Исправление синтаксиса: выражение создания экземпляра обёрнуто в скобки
            $controllerInstance = new $controllerClass();
            echo $controllerInstance->callAction($action, $this->request->getAllParams());

            // Расчёт времени и памяти
            $executionTime = number_format((microtime(true) - $startTime) * 1000, 2); // в мс
            $memoryUsed = number_format((memory_get_usage() - $startMemory) / 1024, 2);  // в КБ

            echo "<div style='position: fixed; left: 0; bottom: 0; background: rgba(0,0,0,0.8); color: #fff; padding: 5px 10px; z-index: 99999; font-family: monospace; size: 12px;'>
                    Время: {$executionTime} ms | Память: {$memoryUsed} KB
                  </div>";
        } else {
            $this->serveStaticFile($path);
        }
    }

    /**
     * Отдача статических файлов из директории public
     */
    private function serveStaticFile(string $path): void
    {
        $baseDir = dirname(__DIR__, 2);
        $filePath = realpath($baseDir . $path);

        // Защита от Directory Traversal и проверка наличия файла
        if ($filePath && str_starts_with($filePath, $baseDir) && is_file($filePath)) {
            // Установка корректного MIME-типа
            $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
            header('Content-Type: ' . $mimeType);
            readfile($filePath);
        } else {
            http_response_code(404);
            echo "Файл не найден";
        }
    }
}