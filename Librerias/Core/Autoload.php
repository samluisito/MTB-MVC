<?php

declare(strict_types=1);

/**
 * Una función simple de autocarga que carga dinámicamente los archivos de clase.
 *
 * Asume que el archivo de la clase se encuentra en el mismo directorio
 * ('Librerias/Core') que este archivo.
 */
spl_autoload_register(function (string $class) {
    // El str_replace se mantiene por compatibilidad, aunque es poco probable que sea necesario
    // para la estructura de clases plana de este proyecto en el directorio Core.
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});