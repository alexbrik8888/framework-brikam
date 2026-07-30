<?php
    return [
        'cache' => [
            'host' => '127.0.0.1',
            'port' => '11211',
            'minLifeTime' => 100
        ],

        'root_dir' => dirname(__DIR__,1),

        'boot_include' => [
                '/route.php',
        ],
        'auth' =>[
            'admin'=>[],
            'web' =>[],
        ],
        'db' =>[
            'host' => '127.0.0.1',
            'port' => '11211',
            'user' => 'root',
            'pass' => 'root',
            'dbname' => 'testwork'
        ]
    ];