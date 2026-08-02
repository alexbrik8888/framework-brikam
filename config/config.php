<?php
    return [
        'cache' => [
            'host' => '127.0.0.1',
            'port' => '11211',
            'minLifeTime' => 100
        ],

        'root_dir' => dirname(__DIR__,1),

        'smarty' =>[
            'templateDir' => dirname(__DIR__,1).DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'templates',
            'compiledDir' => dirname(__DIR__,1).DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'compiled',
            'configDir' => dirname(__DIR__,1).DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'config',
            'cacheDir' => dirname(__DIR__,1).DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'cache',
        ],

        'session' =>[
            'lifetime' => 600,
            'path'     => '/',
            'domain'   => $_SERVER['HTTP_HOST'] ?? '',
            'secure'   => true,     // Отправлять только по HTTPS
            'httponly' => true,     // Запретить доступ JS к cookie (защита от XSS)
            'samesite' => 'Lax'     // Защита от CSRF
        ],
        'boot_include' => [
                'App/Engine/FunGlobal/session.php',
                '/route.php',
        ],
        'db' =>[
            'host' => '127.0.0.1',
            'port' => '11211',
            'user' => 'root',
            'pass' => 'root',
            'dbname' => 'testwork'
        ]
    ];