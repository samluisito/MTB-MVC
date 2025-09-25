<?php

declare(strict_types=1);

/**
 * Este script maneja la lógica de enrutamiento principal. Carga el controlador
 * apropiado y ejecuta el método solicitado según las variables de la URL
 * definidas en index.php.
 */

$controllerName = ucwords($controller);
$controllerFile = __DIR__ . '/../../Controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    // La variable original $controller (un string) ahora se reemplaza por la instancia del objeto.
    $controller = new $controllerName();

    if (method_exists($controller, $method)) {
        // El método solicitado existe, así que lo llamamos con los parámetros.
        $controller->{$method}($params);
    } else {
        // El método no existe, así que mostramos una página de error 404.
        // Se espera que el archivo Error.php se autoejecute.
        require_once __DIR__ . '/../../Controllers/Error.php';
    }
} else {
    // El archivo del controlador no existe, así que mostramos una página de error 404.
    // Se espera que el archivo Error.php se autoejecute.
    require_once __DIR__ . '/../../Controllers/Error.php';
}