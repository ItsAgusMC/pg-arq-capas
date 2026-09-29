<?php

// Sólo hace falta si tu MySQL NO usa los valores de fábrica de XAMPP (root sin contraseña).
// Copiar este archivo como config.local.php y cambiar lo que corresponda.
// Tip: el usuario y la contraseña que usa tu phpMyAdmin están en C:\xampp\phpMyAdmin\config.inc.php
// config.local.php está en .gitignore: nunca se sube al repositorio.
return [
    'db' => [
        'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=tickets_db;charset=utf8mb4',
        'usuario' => 'root',
        'clave' => '',
    ],
];
