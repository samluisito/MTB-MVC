<?php

declare(strict_types=1);

/**
 * Carga e instancia un controlador utilizando el autoloader de Composer.
 *
 * Esta función construye el nombre de clase completo para un controlador,
 * verifica si la clase existe (lo que activa la autocarga PSR-4), y si es así,
 * crea y devuelve una instancia del controlador.
 *
 * @param string $controller El nombre corto del controlador a cargar (ej. 'Home').
 * @return object|null La instancia del controlador o null si la clase no existe.
 */
function loadController(string $controller): ?object
{
    // Construye el nombre de clase completamente cualificado (FQCN).
    // ej: 'Home' se convierte en 'App\Controllers\Home'.
    $controllerName = "App\\Controllers\\" . ucwords($controller);

    // class_exists() activará el autoloader de Composer para cargar el archivo.
    if (class_exists($controllerName)) {
        return new $controllerName();
    }

    // Si la clase no puede ser autocargada, devuelve null.
    return null;
}