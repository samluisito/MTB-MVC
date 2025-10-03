<?php

declare(strict_types=1);

/**
 * Carga e instancia un controlador.
 *
 * Esta función busca el archivo del controlador correspondiente, lo incluye
 * y crea una instancia de la clase del controlador. Si el controlador o
 * el archivo no se encuentran, redirige a una página de error.
 *
 * @param string $controller El nombre del controlador a cargar.
 * @return object|null La instancia del controlador o null si no se encuentra.
 */
function loadController(string $controller): ?object
{
    $controllerName = ucwords($controller);
    $controllerFile = __DIR__ . '/../../Controllers/' . $controllerName . '.php';

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        // Devuelve una nueva instancia del controlador.
        return new $controllerName();
    }

    // Si el archivo del controlador no existe, no se puede continuar.
    return null;
}