<?php
spl_autoload_register(function ($class) {
    $class = str_replace("\\", "/", $class);
    if (file_exists(__DIR__ . '/../controllers/' . $class . '.php')) {
        require_once __DIR__ . '/../controllers/' . $class . '.php';
    } elseif (file_exists(__DIR__ . '/../models/' . $class . '.php')) {
        require_once __DIR__ . '/../models/' . $class . '.php';
    }
});