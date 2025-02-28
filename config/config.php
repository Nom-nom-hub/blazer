<?php

return [
    'app' => [
        'name' => 'Blazer Application',
        'env' => 'development', // 'production', 'development', 'testing'
        'debug' => true,
        'url' => 'http://localhost:8000',
    ],
    
    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'blazer',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    
    'views' => [
        'path' => __DIR__ . '/../app/Views',
        'cache' => __DIR__ . '/../storage/cache/views',
    ],
    
    // Add more configuration sections as needed
]; 