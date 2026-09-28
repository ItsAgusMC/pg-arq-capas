<?php

declare(strict_types=1);

/**
 * CAPA DE PERSISTENCIA
 *
 * Único lugar donde se crea la conexión a la base de datos (DRY / SSOT).
 * Los datos de conexión se leen de config/config.php.
 */
class Conexion
{
    private static ?PDO $pdo = null;

    public static function obtener(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../config/config.php';
            $db = $config['db'];

            self::$pdo = new PDO($db['dsn'], $db['usuario'], $db['clave'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }

        return self::$pdo;
    }
}
