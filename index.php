<?php

declare(strict_types=1);

use App\Librerias\Core\Conexion;

/**
 * --------------------------------------------------------------------------
 * Front Controller
 * --------------------------------------------------------------------------
 *
 * @version 2.0.1
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
| Carga de Archivos del Núcleo y Autoloader de Composer
|--------------------------------------------------------------------------
*/
// Se utiliza DIRECTORY_SEPARATOR para asegurar la compatibilidad entre sistemas operativos.
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Librerias' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'Helpers.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Librerias' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Load.php';

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

    $tenantIdentifier = (count($hostParts) > 1 && $hostParts[0] !== 'localhost') ? $hostParts[0] : 'mitiendabit';

} else {
    define('TPO_SERV_LOCAL', 0);
    define('BASE_URL', $_SERVER['REQUEST_SCHEME'] . '://' . $host);
    $tenantIdentifier = $hostParts[0];
}

define('BD_SELECT', strtolower($tenantIdentifier));
define('BASE_CLIENTE', '/');

/*
|--------------------------------------------------------------------------
| Inicialización y Cierre de la Conexión a la Base de Datos
|--------------------------------------------------------------------------
*/
$dbConnection = new Conexion();

register_shutdown_function(function () use ($dbConnection) {
    $connection = $dbConnection->getConexion();
    if ($connection instanceof \mysqli && $connection->thread_id) {
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
    $errorController = loadController('Error');
    $errorController->notFound();
} else {
    $controller->{$methodName}($params);
}