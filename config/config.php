<?php

declare(strict_types=1);

$local = is_file(__DIR__ . '/config.local.php')
    ? require __DIR__ . '/config.local.php'
    : [];

return [
    'db' => [
        'dsn' => $local['db']['dsn']
            ?? getenv('DB_DSN')
            ?: 'mysql:host=127.0.0.1;port=3306;dbname=tickets_db;charset=utf8mb4',
        'usuario' => $local['db']['usuario'] ?? getenv('DB_USUARIO') ?: 'root',
        'clave' => $local['db']['clave'] ?? getenv('DB_CLAVE') ?: '',
    ],
];
