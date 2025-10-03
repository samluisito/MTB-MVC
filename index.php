<?php

declare(strict_types=1);

/**
 * --------------------------------------------------------------------------
 * Front Controller
 * --------------------------------------------------------------------------
 *
 * Este archivo es el único punto de entrada para todas las solicitudes a la
 * aplicación. Se encarga de inicializar el entorno, parsear la URL y
 * delegar el control al script de carga principal que manejará el enrutamiento.
 *
 * @version 1.4.0
 * @author Jules
 */

// Define el tiempo de inicio para la depuración del rendimiento.
if (!defined('TIME_INI')) {
    define('TIME_INI', microtime(true));
}

// Inicia la sesión si no está ya activa.
if (session_status() === PHP_SESSION_NONE) {
    session_cache_expire(30);
    session_start();
}

/*
|--------------------------------------------------------------------------
| Carga de Archivos del Núcleo
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/Helpers/Helpers.php';
require_once __DIR__ . '/Librerias/Core/Autoload.php';
require_once __DIR__ . '/Librerias/Core/Load.php';

/*
|--------------------------------------------------------------------------
| Parseo de la URL
|--------------------------------------------------------------------------
*/
$url = $_GET['url'] ?? 'home/home';
$arrUrl = explode('/', rtrim($url, '/'));

$controllerName = $arrUrl[0] ?: 'home';
$methodName = $arrUrl[1] ?? $controllerName;
$methodName = $methodName === '' ? $controllerName : $methodName;

$params = '';
if (count($arrUrl) > 2) {
    $params = implode(',', array_slice($arrUrl, 2));
}

/*
|--------------------------------------------------------------------------
| Configuración de Entorno y Selección de Inquilino (Tenant)
|--------------------------------------------------------------------------
*/
$host = $_SERVER['HTTP_HOST'];

// La detección del entorno local ahora es más robusta para soportar subdominios locales.
// Comprueba si el host es 'localhost', una IP local, o si termina en '.localhost'.
$isLocal = ($host === 'localhost'
    || str_starts_with($host, '127.0.0.1')
    || str_starts_with($host, '192.')
    || str_ends_with($host, '.localhost')
);

$hostParts = explode('.', $host);
if (count($hostParts) > 2 && $hostParts[0] === 'www') {
    array_shift($hostParts);
}

if ($isLocal) {
    $requestUriParts = explode('/', $_SERVER['REQUEST_URI']);
    $rootFolder = $requestUriParts[1] ?? '';

    define('TPO_SERV_LOCAL', 1);
    define('BASE_URL', $_SERVER['REQUEST_SCHEME'] . '://' . $host . '/' . $rootFolder);

    // En local, el identificador del inquilino puede ser el subdominio o un valor por defecto.
    // Si es 'localhost' sin subdominio, o solo una IP, usamos 'mitiendabit'.
    $tenantIdentifier = (count($hostParts) > 1 && $hostParts[0] !== 'localhost') ? $hostParts[0] : 'mitiendabit';

} else {
    define('TPO_SERV_LOCAL', 0);
    define('BASE_URL', $_SERVER['REQUEST_SCHEME'] . '://' . $host);
    // En producción, el subdominio es el identificador del inquilino.
    $tenantIdentifier = $hostParts[0];
}

define('BD_SELECT', strtolower($tenantIdentifier));
define('BASE_CLIENTE', '/');

/*
|--------------------------------------------------------------------------
| Inicialización y Cierre de la Conexión a la Base de Datos
|--------------------------------------------------------------------------
*/
// Se instancia la clase `Conexion` para establecer la conexión a la BD.
new Conexion();

// Se registra una función para cerrar la conexión al final del script.
register_shutdown_function(function () {
    $conexion = new Conexion(); // No creará una nueva conexión gracias al patrón Singleton.
    $connection = $conexion->getConexion();
    if ($connection instanceof mysqli && $connection->thread_id) {
        $connection->close();
    }
});

/*
|--------------------------------------------------------------------------
| Arranque de la Aplicación
|--------------------------------------------------------------------------
*/
$controller = loadController($controllerName);

if ($controller === null || !method_exists($controller, $methodName)) {
    // Si el controlador o el método no existen, se carga el script de error
    // que se encarga de mostrar la página 404 y finalizar la ejecución.
    require_once __DIR__ . '/Controllers/Error.php';
    exit();
}

// Se ejecuta el método del controlador con los parámetros.
$controller->{$methodName}($params);