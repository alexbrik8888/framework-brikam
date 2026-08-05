<?php

namespace database;

use App\Engine\BaseController;
use App\Engine\DB;

/**
 * Класс Seeders предназначен для инициализации структуры базы данных (миграции)
 * и первичного наполнения таблиц данными (сидинг).
 */
class Seeders extends BaseController
{
    /**
     * Экземпляр подключения к базе данных для переиспользования во всех методах
     */
    private DB $db;

    public function __construct()
    {
        // Вызов конструктора родительского класса, если требуется
        parent::__construct();

        // Переиспользуем одно подключение к БД на весь процесс сидинга
        $this->db = new DB();
    }

    /**
     * Главный метод запуска создания таблиц, индексов и вставки данных.
     * Заворачивает весь процесс в единую транзакцию.
     *
     * @return string Логи выполнения вывода (HTML)
     */
    public function runAction(): string
    {
        // Буферизация вывода для перехвата сообщений об успехе/ошибках
        ob_start();

        // Отключаем строгий режим для автоинкремента при вставке явных ID (0)
        $this->db->statment('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";');

        // Начало транзакции для атомарности всех операций
        $this->db->statment('START TRANSACTION;');
        $this->db->statment('SET time_zone = "+00:00";');

        // --- 1. Создание структуры таблиц ---
        $this->createTableArticles();
        $this->createTableArticleViews();
        $this->createTableCategories();
        $this->createTableCategoryArticle();
        $this->createTableFiles();
        $this->createTableUsers();

        // --- 2. Заполнение таблиц первоначальными данными ---
        $this->insertArticles();
        $this->insertCategories();
        $this->insertCategoryArticle();
        $this->insertUsers();

        // --- 3. Фиксация транзакции ---
        $this->db->statment('COMMIT;');

        // Возвращаем перехваченный HTML-вывод
        return ob_get_clean();
    }

    /**
     * Создание таблицы статей `articles`
     */
    public function createTableArticles(): void
    {
        if (!$this->db->tableExists('articles')) {
            $this->db->statment("CREATE TABLE `articles` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(400) NOT NULL,
              `description` text NOT NULL,
              `text` mediumtext NOT NULL,
              `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
              `is_hidden` tinyint(1) NOT NULL DEFAULT 0,
              `published_at` timestamp NOT NULL,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              `updated_at` timestamp NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            echo "<p>Table 'articles' created successfully.</p>";
        } else {
            echo "<p>Table 'articles' already exists.</p>";
        }
    }

    /**
     * Создание таблицы пользователей `users`
     */
    public function createTableUsers(): void
    {
        if (!$this->db->tableExists('users')) {
            $this->db->statment("CREATE TABLE `users` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `email` varchar(150) NOT NULL,
              `password` varchar(50) NOT NULL,
              `group` tinyint(4) NOT NULL DEFAULT 1,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            echo "<p>Table 'users' created successfully.</p>";
        } else {
            echo "<p>Table 'users' already exists.</p>";
        }
    }

    /**
     * Создание таблицы категорий `categories`
     */
    public function createTableCategories(): void
    {
        if (!$this->db->tableExists('categories')) {
            $this->db->statment("CREATE TABLE `categories` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(150) NOT NULL,
              `parent_id` int(11) DEFAULT NULL,
              `description` text NOT NULL,
              `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              `updated_at` timestamp NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            echo "<p>Table 'categories' created successfully.</p>";
        } else {
            echo "<p>Table 'categories' already exists.</p>";
        }
    }

    /**
     * Создание связующей таблицы `category_article` (Многие-ко-многим)
     */
    public function createTableCategoryArticle(): void
    {
        if (!$this->db->tableExists('category_article')) {
            $this->db->statment("CREATE TABLE `category_article` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `category_id` int(11) NOT NULL,
              `article_id` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            echo "<p>Table 'category_article' created successfully.</p>";
        } else {
            echo "<p>Table 'category_article' already exists.</p>";
        }
    }

    /**
     * Создание таблицы файлов `files`
     */
    public function createTableFiles(): void
    {
        if (!$this->db->tableExists('files')) {
            $this->db->statment("CREATE TABLE `files` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `file_name` varchar(255) NOT NULL,
              `system_name` varchar(255) NOT NULL,
              `extension` varchar(8) NOT NULL,
              `file_path` varchar(400) NOT NULL,
              `type_id` tinyint(4) NOT NULL,
              `abstract_id` int(11) NOT NULL,
              `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
              `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
              `updated_at` timestamp NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            echo "<p>Table 'files' created successfully.</p>";
        } else {
            echo "<p>Table 'files' already exists.</p>";
        }
    }

    /**
     * Создание таблицы просмотров статей `article_views`
     */
    public function createTableArticleViews(): void
    {
        if (!$this->db->tableExists('article_views')) {
            $this->db->statment("CREATE TABLE `article_views` (
              `article_id` int(11) NOT NULL,
              `view` int(11) NOT NULL DEFAULT 0,
              PRIMARY KEY (`article_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            echo "<p>Table 'article_views' created successfully.</p>";
        } else {
            echo "<p>Table 'article_views' already exists.</p>";
        }
    }

    /**
     * Вставка связей категорий и статей
     */
    public function insertCategoryArticle(): void
    {
        $categoryArticleData = [
            ['id' => 1, 'category_id' => 1, 'article_id' => 1],
            ['id' => 2, 'category_id' => 1, 'article_id' => 2],
            ['id' => 3, 'category_id' => 1, 'article_id' => 3],
            ['id' => 4, 'category_id' => 2, 'article_id' => 4],
            ['id' => 5, 'category_id' => 2, 'article_id' => 5],
            ['id' => 6, 'category_id' => 2, 'article_id' => 6],
            ['id' => 7, 'category_id' => 3, 'article_id' => 7],
            ['id' => 8, 'category_id' => 3, 'article_id' => 8],
            ['id' => 9, 'category_id' => 3, 'article_id' => 9],
            ['id' => 11, 'category_id' => 4, 'article_id' => 11],
            ['id' => 12, 'category_id' => 4, 'article_id' => 12],
            ['id' => 13, 'category_id' => 5, 'article_id' => 13],
            ['id' => 14, 'category_id' => 5, 'article_id' => 14],
            ['id' => 15, 'category_id' => 5, 'article_id' => 15],
            ['id' => 16, 'category_id' => 6, 'article_id' => 16],
            ['id' => 17, 'category_id' => 1, 'article_id' => 16],
            ['id' => 18, 'category_id' => 6, 'article_id' => 17],
            ['id' => 19, 'category_id' => 1, 'article_id' => 17],
            ['id' => 20, 'category_id' => 6, 'article_id' => 18],
            ['id' => 21, 'category_id' => 1, 'article_id' => 18],
            ['id' => 22, 'category_id' => 7, 'article_id' => 19],
            ['id' => 23, 'category_id' => 1, 'article_id' => 19],
            ['id' => 24, 'category_id' => 7, 'article_id' => 20],
            ['id' => 25, 'category_id' => 1, 'article_id' => 20],
            ['id' => 26, 'category_id' => 7, 'article_id' => 21],
            ['id' => 27, 'category_id' => 1, 'article_id' => 21],
            ['id' => 28, 'category_id' => 8, 'article_id' => 22],
            ['id' => 29, 'category_id' => 1, 'article_id' => 22],
            ['id' => 30, 'category_id' => 8, 'article_id' => 23],
            ['id' => 31, 'category_id' => 1, 'article_id' => 23],
            ['id' => 32, 'category_id' => 8, 'article_id' => 24],
            ['id' => 33, 'category_id' => 1, 'article_id' => 24],
            ['id' => 34, 'category_id' => 9, 'article_id' => 25],
            ['id' => 35, 'category_id' => 6, 'article_id' => 25],
            ['id' => 36, 'category_id' => 1, 'article_id' => 25],
            ['id' => 37, 'category_id' => 9, 'article_id' => 26],
            ['id' => 38, 'category_id' => 6, 'article_id' => 26],
            ['id' => 39, 'category_id' => 1, 'article_id' => 26],
            ['id' => 40, 'category_id' => 9, 'article_id' => 27],
            ['id' => 41, 'category_id' => 6, 'article_id' => 27],
            ['id' => 42, 'category_id' => 1, 'article_id' => 27],
            ['id' => 43, 'category_id' => 10, 'article_id' => 28],
            ['id' => 44, 'category_id' => 6, 'article_id' => 28],
            ['id' => 45, 'category_id' => 1, 'article_id' => 28],
            ['id' => 46, 'category_id' => 10, 'article_id' => 29],
            ['id' => 47, 'category_id' => 6, 'article_id' => 29],
            ['id' => 48, 'category_id' => 1, 'article_id' => 29],
            ['id' => 49, 'category_id' => 10, 'article_id' => 30],
            ['id' => 50, 'category_id' => 6, 'article_id' => 30],
            ['id' => 51, 'category_id' => 1, 'article_id' => 30],
            ['id' => 52, 'category_id' => 11, 'article_id' => 31],
            ['id' => 53, 'category_id' => 2, 'article_id' => 31],
            ['id' => 54, 'category_id' => 11, 'article_id' => 32],
            ['id' => 55, 'category_id' => 2, 'article_id' => 32],
            ['id' => 56, 'category_id' => 11, 'article_id' => 33],
            ['id' => 57, 'category_id' => 2, 'article_id' => 33],
            ['id' => 58, 'category_id' => 12, 'article_id' => 34],
            ['id' => 59, 'category_id' => 2, 'article_id' => 34],
            ['id' => 60, 'category_id' => 12, 'article_id' => 35],
            ['id' => 61, 'category_id' => 2, 'article_id' => 35],
            ['id' => 62, 'category_id' => 12, 'article_id' => 36],
            ['id' => 63, 'category_id' => 2, 'article_id' => 36],
            ['id' => 64, 'category_id' => 13, 'article_id' => 37],
            ['id' => 65, 'category_id' => 3, 'article_id' => 37],
            ['id' => 66, 'category_id' => 13, 'article_id' => 38],
            ['id' => 67, 'category_id' => 3, 'article_id' => 38],
            ['id' => 68, 'category_id' => 13, 'article_id' => 39],
            ['id' => 69, 'category_id' => 3, 'article_id' => 39],
            ['id' => 70, 'category_id' => 14, 'article_id' => 40],
            ['id' => 71, 'category_id' => 3, 'article_id' => 40],
            ['id' => 72, 'category_id' => 14, 'article_id' => 41],
            ['id' => 73, 'category_id' => 3, 'article_id' => 41],
            ['id' => 74, 'category_id' => 14, 'article_id' => 42],
            ['id' => 75, 'category_id' => 3, 'article_id' => 42],
            ['id' => 76, 'category_id' => 2, 'article_id' => 24],
            ['id' => 77, 'category_id' => 3, 'article_id' => 34],
            ['id' => 78, 'category_id' => 6, 'article_id' => 14],
            ['id' => 79, 'category_id' => 10, 'article_id' => 41],
            ['id' => 80, 'category_id' => 4, 'article_id' => 10],
        ];

        if ($this->db->tableExists('category_article')) {
            if ($this->db->insertMultiple('category_article', $categoryArticleData)) {
                echo "<p>Category-Article relations inserted successfully.</p>";
            } else {
                echo "<p>Error inserting Category-Article relations.</p>";
            }
        }
    }

    /**
     * Вставка начальных пользователей системного доступа
     */
    public function insertUsers(): void
    {
        $users = [
            [
                'id' => 1,
                'email' => 'test@work.com',
                'password' => '827ccb0eea8a706c4c34a16891f84e7b',
                'group' => '1'
            ]
        ];

        if ($this->db->tableExists('users')) {
            if ($this->db->insertMultiple('users', $users)) {
                echo "<p>Users inserted successfully.</p>";
            } else {
                echo "<p>Error inserting Users.</p>";
            }
        }
    }

    /**
     * Вставка структуры категорий
     */
    public function insertCategories(): void
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Языки программирования',
                'parent_id' => 0,
                'description' => 'Раздел с популярными языками разработки и их экосистемами',
                'is_deleted' => 0,
                'created_at' => '2026-08-04 17:12:16',
                'updated_at' => '2026-08-04 17:12:16',
            ],
            [
                'id' => 2,
                'name' => 'Архитектура и Паттерны',
                'parent_id' => 0,
                'description' => 'Шаблоны проектирования, чистая архитектура и лучшие практики',
                'is_deleted' => 0,
                'created_at' => '2026-08-04 17:12:16',
                'updated_at' => '2026-08-04 17:12:16',
            ],
            [
                'id' => 3,
                'name' => 'Инструменты разработки',
                'parent_id' => 0,
                'description' => 'IDE, системы контроля версий и инструменты для CI/CD',
                'is_deleted' => 0,
                'created_at' => '2026-08-04 17:12:16',
                'updated_at' => '2026-08-04 17:12:16',
            ],
            [
                'id' => 4,
                'name' => 'Базы данных',
                'parent_id' => 0,
                'description' => 'Реляционные и NoSQL системы управления базами данных',
                'is_deleted' => 0,
                'created_at' => '2026-08-04 17:12:16',
                'updated_at' => '2026-08-04 17:12:16',
            ],
            [
                'id' => 5,
                'name' => 'Устаревшие технологии',
                'parent_id' => 0,
                'description' => 'Раздел с устаревшими фреймворками и библиотеками',
                'is_deleted' => 1,
                'created_at' => '2022-01-10 06:00:00',
                'updated_at' => '2023-05-14 09:00:00',
            ],
            [
                'id' => 6,
                'name' => 'JavaScript',
                'parent_id' => 1,
                'description' => 'Скриптовый язык программирования для веб-разработки',
                'is_deleted' => 0,
                'created_at' => '2026-08-04 17:12:16',
                'updated_at' => '2026-08-04 17:12:16',
            ],
            [
                'id' => 7,
                'name' => 'Python',
                'parent_id' => 1,
                'description' => 'Высокоуровневый язык для backend-разработки, Data Science и скриптов',
                'is_deleted' => 0,
                'created_at' => '2026-08-04 17:12:16',
                'updated_at' => '2026-08-04 17:12:16',
            ],
            [
                'id' => 8,
                'name' => 'PHP',
                'parent_id' => 1,
                'description' => 'Серверный скриптовый язык для создания динамических веб-сайтов',
                'is_deleted' => 0,
                'created_at' => '2026-08-04 17:12:16',
                'updated_at' => '2026-08-04 17:12:16',
            ],
            [
                'id' => 9,
                'name' => 'React',
                'parent_id' => 6,
                'description' => 'JavaScript-библиотека для создания пользовательских интерфейсов',
                'is_deleted' => 0,
                'created_at' => '2026-08-04 17:12:16',
                'updated_at' => '2026-08-04 17:12:16',
            ],
            [
                'id' => 10,
                'name' => 'Node.js',
                'parent_id' => 6,
                'description' => 'Среда выполнения JavaScript на стороне сервера',
                'is_deleted' => 0,
                'created_at' => '2026-08-04 17:12:16',
                'updated_at' => '2026-08-04 17:12:16',
            ],
            [
                'id' => 11,
                'name' => 'Порождающие паттерны',
                'parent_id' => 2,
                'description' => 'Шаблоны проектирования, отвечающие за удобную и безопасную создаваемость объектов (Factory, Singleton, Builder)',
                'is_deleted' => 0,
                'created_at' => '2026-08-04 17:12:16',
                'updated_at' => '2026-08-04 17:12:16',
            ],
            [
                'id' => 12,
                'name' => 'Микросервисы',
                'parent_id' => 2,
                'description' => 'Архитектурный подход к созданию приложений в виде набора небольших независимых сервисов',
                'is_deleted' => 0,
                'created_at' => '2026-08-04 17:12:16',
                'updated_at' => '2026-08-04 17:12:16',
            ],
            [
                'id' => 13,
                'name' => 'Git',
                'parent_id' => 3,
                'description' => 'Распределенная система контроля версий исходного кода',
                'is_deleted' => 0,
                'created_at' => '2026-08-04 17:12:16',
                'updated_at' => '2026-08-04 17:12:16',
            ],
            [
                'id' => 14,
                'name' => 'Docker',
                'parent_id' => 3,
                'description' => 'Платформа для контейнеризации приложений и настройки окружения',
                'is_deleted' => 0,
                'created_at' => '2026-08-04 17:12:16',
                'updated_at' => '2026-08-04 17:12:16',
            ],
        ];

        if ($this->db->tableExists('categories')) {
            if ($this->db->insertMultiple('categories', $categories)) {
                echo "<p>Categories inserted successfully.</p>";
            } else {
                echo "<p>Error inserting Categories.</p>";
            }
        }
    }

    /**
     * Вставка начального набора статей
     */
    public function insertArticles(): void
    {
        $articles = [
            [
                'id' => 1,
                'name' => 'Эволюция языков программирования: От ассемблера до современных мультипарадигменных языков',
                'description' => 'Обзор того, как развивались концепции языков высокого уровня и какие тренды преобладают сегодня.',
                'text' => 'История языков программирования — это постоянный поиск баланса между производительностью исполнения и скоростью разработки. Начиная с машинных кодов и ассемблера, индустрия стремилась к абстрагированию от железа. Появление процедурных языков, затем объектно-ориентированного программирования (ООП), а в последние годы — активный переход к функциональным концепциям формируют современную экосистему IT.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-01 07:00:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 2,
                'name' => 'Компилируемые vs Интерпретируемые языки: Архитектурные различия и компромиссы',
                'description' => 'Подробный анализ механизмов исполнения кода, роли JIT-компиляции и влияния на быстродействие.',
                'text' => 'Выбор между компиляцией в машинный код и интерпретацией в реальном времени определяет многие свойства языка. Компилируемые языки (C++, Rust, Go) гарантируют максимальную скорость работы и выявление ошибок на этапе сборки. Интерпретируемые языки предлагают гибкость и динамическую типизацию, а современная JIT-компиляция в V8 или PyPy размывает грань между этими подходом.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-02 08:30:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 3,
                'name' => 'Как выбрать первый язык программирования в 2024 году',
                'description' => 'Сравнение популярных направлений: веб-разработка, мобильная разработка, анализ данных и системное программирование.',
                'text' => 'Для начинающих разработчиков выбор первого языка определяет порог входа в профессию. Python идеален для легкого старта и анализа данных, JavaScript необходим для фронтенда, а Go или Java открывают двери в высоконагруженную бэкенд-разработку. В статье рассматриваются карьерные перспективы для каждого направления.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-03 11:00:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 4,
                'name' => 'Принципы SOLID на практических примерах',
                'description' => 'Разбор пяти базовых принципов объектно-ориентированного дизайна, повышающих поддерживаемость кода.',
                'text' => 'Принципы SOLID (Single Responsibility, Open/Closed, Liskov Substitution, Interface Segregation, Dependency Inversion) образуют фундамент гибкой архитектуры. В руководстве подробно разбираются типовые ошибки разработчиков и показывается, как рефакторинг с применением SOLID предотвращает накопление технического долга.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-04 06:15:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 5,
                'name' => 'Чистая архитектура (Clean Architecture): Разделение ответственности и слоев',
                'description' => 'Как строить приложения, независимые от фреймворков, БД и внешних интерфейсов.',
                'text' => 'Чистая архитектура Роберта Мартина предлагает разделить приложение на изолированные слои: Entities, Use Cases, Interface Adapters и Frameworks. Главное правило — правило зависимостей: внутренние слои ничего не должны знать о внешних. Это делает систему устойчивой к изменениям стека технологий.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-05 13:45:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 6,
                'name' => 'Событийно-ориентированная архитектура (Event-Driven Architecture)',
                'description' => 'Проектирование слабосвязанных систем с помощью событий и брокеров сообщений.',
                'text' => 'Event-Driven Architecture (EDA) основана на публикации и обработке событий. В отличие от традиционного синхронного запроса, компоненты EDA общаются асинхронно через шину сообщений. Это обеспечивает отличную масштабируемость и отказоустойчивость при пиковых нагрузках.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-06 09:20:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 7,
                'name' => 'Оптимизация работы в IDE: Горячие клавиши, плагины и кастомизация',
                'description' => 'Повышаем продуктивность работы в разработческих средах JetBrains и VS Code.',
                'text' => 'Инструментарий разработчика напрямую влияет на скорость написания и отладки кода. В статье собраны лучшие практики по настройке горячих клавиш, мультикурсоров, автодополнения на базе ИИ и интеграции линтеров, позволяющих сократить рутинные действия в разы.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-07 07:00:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 8,
                'name' => 'Основы CI/CD: Автоматизация сборки, тестирования и деплоя',
                'description' => 'Пошаговый разбор пайплайнов непрерывной интеграции и доставки ПО.',
                'text' => 'Continuous Integration и Continuous Deployment превратили релизный процесс из рискованного события в повседневную рутину. Рассматривается организация пайплайнов, автоматический прогон модульных и интеграционных тестов, а также стратегический выкатывание (Blue-Green, Canary deployments).',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-08 12:30:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 9,
                'name' => 'Статический анализ кода и линтинг в корпоративных проектах',
                'description' => 'Внедрение SonarQube, ESLint и PHPStan для контроля качества кода в команде.',
                'text' => 'Автоматизированный поиск багов, уязвимостей и дубликатов кода на этапе сборки позволяет предотвратить попадание дурно пахнущего кода в продакшн. В материале разбираются правила настройки линтеров и интеграция их с системами контроля версий.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-09 10:10:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 10,
                'name' => 'Реляционные vs NoSQL базы данных: Как сделать правильный выбор',
                'description' => 'Сравнительный анализ ACID-требований, BASE-модели, шардинга и репликации.',
                'text' => 'Правильный выбор хранилища данных зависит от характера нагрузки и требований к согласованности. Реляционные БД (PostgreSQL, MySQL) гарантируют строгость строения и транзакционность, тогда как NoSQL (MongoDB, Redis, Cassandra) предлагают горизонтальное масштабирование и гибкие схемы документов.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2026-08-03 21:00:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:33:46',
            ],
            [
                'id' => 11,
                'name' => 'Оптимизация SQL-запросов и индексация: Практический гид',
                'description' => 'Как анализировать EXPLAIN, избегать Full Table Scan и строить составные индексы.',
                'text' => 'Низкая скорость работы базы данных — частая причина медлительности всей системы. В статье подробно изучаются механизмы работы B-Tree и Hash индексов, правила построения покривающих индексов и предотвращение проблемы N+1 запросов.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-11 14:00:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 12,
                'name' => 'Транзакции и уровни изоляции в СУБД',
                'description' => 'Разбор эффектов Грязного чтения, Неповторяемого чтения и Фантомов.',
                'text' => 'Транзакции обеспечивают целостность данных при одновременном доступе нескольких пользователей. Мы рассмотрим 4 классических уровня изоляции (Read Uncommitted, Read Committed, Repeatable Read, Serializable) и их влияние на блокировки и производительность.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-12 06:40:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 13,
                'name' => 'История Adobe Flash: Взлет, падение и наследие мультимедийной платформы',
                'description' => 'Как Flash сформировал ранний интерактивный веб и почему он прекратил существование.',
                'text' => 'Adobe Flash долгое время являлся стандартом для онлайн-игр, анимаций и видеоплееров. Однако проблемы с безопасностью, высокое потребление ресурсов и появление стандартов HTML5/CSS3 привели к окончательному закрытию технологии в 2020 году.',
                'is_deleted' => 1,
                'is_hidden' => 1,
                'published_at' => '2021-05-10 07:00:00',
                'created_at' => '2021-05-10 07:00:00',
                'updated_at' => '2022-01-10 06:00:00',
            ],
            [
                'id' => 14,
                'name' => 'Миграция с jQuery на чистый JavaScript (Vanilla JS)',
                'description' => 'Почему библиотеку jQuery больше не стоит использовать в новых проектах.',
                'text' => 'В 2000-х годах jQuery спасала разработчиков от несовместимости браузеров. Сегодня современные стандарты ECMAScript и Fetch API предлагают native-решения, работающие быстрее и не требующие подключения сторонних зависимостей.',
                'is_deleted' => 1,
                'is_hidden' => 0,
                'published_at' => '2022-03-15 09:00:00',
                'created_at' => '2022-03-15 09:00:00',
                'updated_at' => '2023-05-14 09:00:00',
            ],
            [
                'id' => 15,
                'name' => 'Закат SOAP в эпоху RESTful API и gRPC',
                'description' => 'Проблемы избыточности XML-протоколов и переход к легким форматам обмена.',
                'text' => 'Протокол SOAP с его сложными WSDL-схемами и тяжеловесными XML-структурами уступил место более простому REST (JSON) и высокопроизводительному gRPC. В статье анализируются причины устаревания стандарта.',
                'is_deleted' => 1,
                'is_hidden' => 1,
                'published_at' => '2021-11-20 13:30:00',
                'created_at' => '2021-11-20 13:30:00',
                'updated_at' => '2023-05-14 09:00:00',
            ],
            [
                'id' => 16,
                'name' => 'Понимание асинхронности в JS: Event Loop, Promises и Async/Await',
                'description' => 'Глубокий разбор работы очереди микрозадач и макрозадач в движке V8.',
                'text' => 'Однопоточная природа JavaScript компенсируется событийно-ориентированной моделью с Event Loop. В руководстве детально на примерах разбирается порядок выполнения стека вызовов, микротасок (Promises) и макротасок (setTimeout, I/O).',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-13 07:30:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 17,
                'name' => 'Замыкания (Closures) и контекст выполнения в JavaScript',
                'description' => 'Как работает LexicalEnvironment и где применяются замыкания на практике.',
                'text' => 'Замыкание — это комбинация функции и ссылок на ее окружение. Понимание замыканий необходимо для создания приватных переменных, каррирования, фабкричных функций и избежания утечек памяти.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-14 11:15:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 18,
                'name' => 'Современные фичи ECMAScript 2022-2024',
                'description' => 'Обзор новых возможностей: Top-level await, Private fields, Array.prototype.at и Record/Tuple.',
                'text' => 'Стандарт JavaScript стремительно развивается. В этой статье рассматриваются свежие синтаксические улучшения, сделавшие код чище и безопаснее, а также экспериментальные предложения TC39.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-15 15:00:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 19,
                'name' => 'Генераторы и итераторы в Python: Эффективная работа с памятью',
                'description' => 'Использование ключевого слова yield и выражения-генераторы для больших наборов данных.',
                'text' => 'При работе с гигабайтными файлами или бесконечными последовательностями списки Python потребляют слишком много RAM. Генераторы позволяют вычислять элементы по требованию (lazy evaluation), сохраняя память устройства.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-16 08:20:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 20,
                'name' => 'Декораторы в Python: От простых функций до классов-декораторов',
                'description' => 'Паттерн Decorator на языке Python для логирования, кэширования и проверки прав.',
                'text' => 'Декораторы позволяют оборачивать функции и изменять их поведение без вмешательства в исходный код. В статье разбираются декораторы с аргументами, functools.wraps и использование `__call__` в классах.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-17 12:50:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 21,
                'name' => 'Погружение в Asyncio: Создание асинхронных веб-скрейперов и API',
                'description' => 'Параллельное выполнение I/O-операций с помощью aiohttp и asyncio.gather.',
                'text' => 'Модуль asyncio кардинально меняет подходы к написанию сетевых сервисов на Python. Узнайте, как выполнять тысячи HTTP-запросов параллельно, эффективно обходя GIL при работе с сетевыми задержками.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-18 06:10:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 22,
                'name' => 'Что нового в PHP 8.3: Typed class constants, json_validate и перформанс',
                'description' => 'Обзор свежего релиза PHP и улучшений системы типов.',
                'text' => 'PHP продолжает динамично развиваться как строго типизированный язык для веб-разработки. В статье подробно рассматриваются явные типы констант классов, функция валидации JSON без полной декодирования и оптимизация JIT.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-19 10:40:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 23,
                'name' => 'Работа с памятью и сборщик мусора (Garbage Collector) в PHP',
                'description' => 'Анализ подсчета ссылок (refcount) и циклического сборщика мусора.',
                'text' => 'Для долгоживущих CLI-скриптов и Worker-процессов крайне важно понимать, как PHP выделяет и освобождает оперативную память. Статья описывает устройство zval-структур и способы выявления утечек.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-20 13:00:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 24,
                'name' => 'Архитектура современного приложение на Laravel: Service Layer и DTO',
                'description' => 'Как вынести бизнес-логику из контроллеров и моделей в чистые PHP-классы.',
                'text' => 'Паттерн "Толстые модели, тонкие контроллеры" часто приводит к спагетти-коду в крупных проектах. Мы разберем паттерны Data Transfer Object (DTO), Action и Service для построения масштабируемых Laravel-приложений.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-21 07:15:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 25,
                'name' => 'Глубокое погружение в React Hooks: useMemo, useCallback и useRef',
                'description' => 'Правильная оптимизация повторных рендеров и управления ссылками.',
                'text' => 'Неправильное использование хуков оптимизации может ухудшить производительность вместо ее улучшения. В статье объясняются механизмы поверхностного сравнения (shallow comparison) и кейсы, когда реселект компонентов действительно необходим.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-22 09:00:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 26,
                'name' => 'Управление состоянием в React: Redux Toolkit, Zustand или TanStack Query?',
                'description' => 'Сравнение подходов к клиентскому и серверному состоянию.',
                'text' => 'Экосистема React отошла от концепции хранения всего приложения в едином Redux-сторе. В материале разбирается разделение состояния на UI-state (Zustand) и Server-state (React Query / RTK Query).',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-23 11:50:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 27,
                'name' => 'Server Components в Next.js и React 18',
                'description' => 'Новая парадигма гибридного рендеринга на сервере и клиенте.',
                'text' => 'React Server Components (RSC) позволяют исполнять компоненты непосредственно на сервере, уменьшая размер бандла, отправляемого клиенту. Разбираемся в разнице между SSR, SSG и RSC.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-24 06:30:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 28,
                'name' => 'Архитектура Worker Threads в Node.js для CPU-bound задач',
                'description' => 'Решение проблемы блокировки основного потока вычислений.',
                'text' => 'Node.js славится своей асинхронной обработкой сетевых запросов, но пасует перед тяжелыми математическими расчетами. Использование модуля worker_threads позволяет выносить сложные задачи в параллельные потоки.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-25 08:10:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 29,
                'name' => 'Потоки данных (Streams) и Buffer в Node.js',
                'description' => 'Обработка больших файлов без переполнения оперативной памяти.',
                'text' => 'Node.js Streams дают возможность читать и транслировать файлы по частям (чанкам). В статье рассматриваются Readable, Writable, Transform потоки и функция pipeline для эффективной работы с I/O.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-26 12:00:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 30,
                'name' => 'Безопасность Node.js приложений: Защита от ReDoS, XSS и Injection',
                'description' => 'Практические рекомендации по защите продуктового сервера.',
                'text' => 'Анализ распространенных уязвимостей в Node.js экосистеме. Использование пакета Helmet, лимитирование запросов (rate-limiting), очистка пользовательского ввода и аудит npm-зависимостей.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-27 14:30:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 31,
                'name' => 'Паттерн Фабричный метод (Factory Method) и Абстрактная фабрика (Abstract Factory)',
                'description' => 'Гибкое создание семейств взаимосвязанных объектов без привязки к конкретным классам.',
                'text' => 'Порождающие паттерны абстрагируют процесс инстанцирования объектов. В статье рассматривается, как замена прямого вызова new на вызов фабричных методов повышает масштабируемость и упрощает проведение Unit-тестирования.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-28 07:00:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 32,
                'name' => 'Паттерн Строитель (Builder): Пошаговое конструирование сложных объектов',
                'description' => 'Избавляемся от телескопических конструкторов с десятком параметров.',
                'text' => 'Паттерн Builder позволяет пошагово создавать сложные объекты с гибкими конфигурациями. Материал демонстрирует реализацию паттерна и создание Fluent Interface (цепочек вызовов).',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-29 10:20:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 33,
                'name' => 'Паттерн Прототип (Prototype): Клонирование объектов в языках программирования',
                'description' => 'Поверхностное (shallow) и глубокое (deep) копирование состояния объектов.',
                'text' => 'Когда создание объекта с нуля обойдется слишком дорого с точки зрения ресурсов, на помощь приходит паттерн Prototype. В статье показана реализация интерфейса клонирования и решение проблемы закольцованных ссылок.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-30 13:15:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 34,
                'name' => 'Паттерны взаимодействия в микросервисах: REST, gRPC и RabbitMQ',
                'description' => 'Выбор между синхронными вызовами и брокерами сообщений.',
                'text' => 'Переход от монолита к микросервисам требует проработки межсервисного взаимодействия. В статье сравниваются накладные расходы протоколов HTTP/JSON и Protobuf/gRPC, а также асинхронный обмен через AMQP.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-10-31 06:00:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 35,
                'name' => 'Паттерн Saga: Управление распределенными транзакциями',
                'description' => 'Как обеспечить согласованность данных без использования двухфазного коммита (2PC).',
                'text' => 'В микросервисной архитектуре каждая база данных изолирована. Паттерн Saga решает задачу распределенной транзакции через последовательность локальных транзакций с механизмом компенсирующих действий при сбоях.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-11-01 08:45:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 36,
                'name' => 'API Gateway: Единая точка входа для микросервисной системы',
                'description' => 'Маршрутизация, аутентификация, балансировка и Rate Limiting на уровне шлюза.',
                'text' => 'API Gateway выступает прокси-сервером между внешними клиентами и внутренними микросервисами. Рассматривается реализация паттерна с помощью Nginx, Kong и Yarp.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-11-02 11:30:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 37,
                'name' => 'Продвинутая работа с Git: Interactive Rebase, Stash и Cherry-Pick',
                'description' => 'Инструменты для поддержания чистоты истории коммитов перед Merge Request.',
                'text' => 'Команда `git rebase -i` позволяет переписывать, объединять (squash) и переупорядочивать коммиты. Статья содержит пошаговое руководство по использованию полезных флагов и спасению случайно удаленных веток через reflog.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-11-03 07:10:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 38,
                'name' => 'Стратегии ветвления: Git Flow, GitHub Flow и Trunk-Based Development',
                'description' => 'Выбор модели ветвления под размер команды и частоту релизов.',
                'text' => 'Сравнение классического Git Flow с релизными ветками и более современного Trunk-Based Development, при котором разработчики часто сливают изменения в main-ветку под прикрытием Feature Flags.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-11-04 09:40:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 39,
                'name' => 'Разрешение сложных конфликтов слияния в Git',
                'description' => 'Использование 3-way merge, diff3 и инструмента git rerere.',
                'text' => 'Конфликты слияния — неизбежная часть командной разработки. В материале рассказывается, как включить опцию `rerere` для автоматического запоминания и разрешения повторяющихся конфликтов.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-11-05 12:15:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 40,
                'name' => 'Docker Compose: Локальное окружение разработки в один клик',
                'description' => 'Оркестрация контейнеров приложений, баз данных и кэшей с помощью docker-compose.yml.',
                'text' => 'Docker Compose позволяет развернуть всю инфраструктуру проекта (App, PostgreSQL, Redis, Nginx) одной командой `docker compose up`. Рассматриваются правила монтирования volume, сетей (networks) и переменных окружения.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-11-06 06:50:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 41,
                'name' => 'Оптимизация Dockerfile: Кэширование слоев и Multi-Stage builds',
                'description' => 'Сокращаем время сборки образов и уменьшаем их размер с сотен мегабайт до десятков.',
                'text' => 'Каждая инструкция в Dockerfile создает отдельный слой. В руководстве показывается, как правильный порядок инструкций позволяет повторно использовать кэш Docker и как собрать бинарник в одном образе, а запустить в минималистичном Alpine или Scratch.',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-11-07 10:00:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
            [
                'id' => 42,
                'name' => 'Безопасность Docker-контейнеров в Production',
                'description' => 'Запуск от имени не-root пользователя, ограничение ресурсов и сканирование уязвимостей.',
                'text' => 'Запуск процессов внутри контейнера от root-пользователя создает серьезные риски утечки на хост-систему. Разбираем использование директивы USER, лимитов CPU/RAM и сканеров образцов (Trivy, Grype).',
                'is_deleted' => 0,
                'is_hidden' => 0,
                'published_at' => '2023-11-08 13:20:00',
                'created_at' => '2026-08-04 17:17:55',
                'updated_at' => '2026-08-04 17:17:55',
            ],
        ];

        if ($this->db->tableExists('articles')) {
            if ($this->db->insertMultiple('articles', $articles)) {
                echo "<p>Articles inserted successfully.</p>";
            } else {
                echo "<p>Error inserting Articles.</p>";
            }
        }
    }
}