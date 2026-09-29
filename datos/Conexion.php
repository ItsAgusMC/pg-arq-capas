<?php

declare(strict_types=1);

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

    public static function describirError(PDOException $ex): string
    {
        $codigo = (int) ($ex->errorInfo[1] ?? $ex->getCode());

        return match ($codigo) {
            2002 => 'No se pudo conectar con MySQL. Verifique que esté iniciado (XAMPP Control Panel → MySQL → Start).',
            1045, 1698 => sprintf(
                'MySQL rechazó al usuario "%s"%s. Use el mismo usuario y contraseña que su phpMyAdmin '
                . '(ver C:\\xampp\\phpMyAdmin\\config.inc.php) en config/config.local.php.',
                preg_match("/for user '([^']*)'/", $ex->getMessage(), $m) ? $m[1] : '?',
                match (true) {
                    str_contains($ex->getMessage(), 'using password: YES') => ' con la contraseña configurada',
                    str_contains($ex->getMessage(), 'using password: NO') => ' sin contraseña',
                    default => '',
                }
            ),
            1049 => 'La base de datos no existe. Importe datos/schema.sql desde phpMyAdmin.',
            1146 => 'La tabla ticket no existe. Importe datos/schema.sql desde phpMyAdmin.',
            default => 'No se pudo guardar el Ticket. Intente nuevamente más tarde.',
        };
    }
}
