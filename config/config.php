<?php

declare(strict_types=1);

/**
 * Configuración de la aplicación (fuente única de verdad).
 *
 * Las credenciales NO se suben al repositorio: se leen de
 * config/config.local.php (ignorado por git) o de variables de entorno.
 * Copiar config.local.example.php como config.local.php y completarlo.
 */
$local = is_file(__DIR__ . '/config.local.php')
    ? require __DIR__ . '/config.local.php'
    : [];

return [
    'db' => [
        'dsn' => $local['db']['dsn']
            ?? getenv('DB_DSN')
            ?: 'mysql:host=localhost;dbname=tickets_db;charset=utf8mb4',
        'usuario' => $local['db']['usuario'] ?? getenv('DB_USUARIO') ?: 'root',
        'clave' => $local['db']['clave'] ?? getenv('DB_CLAVE') ?: '',
    ],
];
