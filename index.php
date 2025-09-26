<?php

declare(strict_types=1);

// Define el tiempo de inicio para la depuración del rendimiento.
define('TIME_INI', microtime(true));

// Inicia la sesión si no está ya activa.
if (session_status() === PHP_SESSION_NONE) {
    session_cache_expire(30);
    session_start();
}

// --- FUNCIONES DE DEPURACIÓN ---

/**
 * Función de utilidad para depuración de variables.
 * Imprime el contenido de una variable de forma legible.
 *
 * @param mixed $data Los datos a mostrar.
 * @param bool  $json Si se debe mostrar la salida como JSON.
 */
function dep($data, bool $json = false): void
{
    $trace = debug_backtrace();
    $file = str_replace($_SERVER['DOCUMENT_ROOT'] ?? '', '', $trace[0]['file']);
    $line = $trace[0]['line'];
    echo '<pre><br><b>';
    if ($json) {
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        echo print_r($data, true);
    }
    echo '</b>';
    echo '<br>Archivo: ' . $file . ' - Línea: ' . $line;
    echo '<hr></pre>';
}

/**
 * Función de utilidad para medir tiempos de ejecución entre puntos del código.
 *
 * @param array|null $arr_dep_time Un array opcional con el tiempo anterior para calcular la diferencia.
 * @return array Devuelve un array con el tiempo actual, el archivo y la línea.
 */
function dep_time(?array $arr_dep_time = null): array
{
    $trace = debug_backtrace();
    $pos = isset($trace[1]['file']) ? 0 : 0;
    $file = str_ireplace('/opt/lampp/htdocs/mitiendabit', '', $trace[$pos]['file']);
    $line = $trace[$pos]['line'];
    $time = round(microtime(true) - TIME_INI, 6);

    echo '<pre>';
    if ($arr_dep_time === null) {
        echo "{$time} - time<br>";
        echo "Archivo: {$file} - línea {$line}";
    } else if (is_array($arr_dep_time)) {
        $diferencia = $time - $arr_dep_time[0];
        echo "{$diferencia} - time entre línea {$arr_dep_time[2]} y {$line}<br>";
        echo "{$time} - time total <br>";
        echo "Archivo: {$file} - línea {$line}";
    }
    echo '<hr></pre>';

    return [$time, $file, $line];
}


// --- PARSEO DE URL Y ENRUTAMIENTO ---
$url = $_GET['url'] ?? 'home/home';
$arrUrl = explode('/', $url);
$controller = $arrUrl[0];
$method = $arrUrl[1] ?? $controller;
$method = ($method === '') ? $controller : $method;
$params = '';
if (count($arrUrl) > 2) {
    $params = implode(',', array_slice($arrUrl, 2));
}

// --- CONFIGURACIÓN DE ENTORNO Y URL BASE ---
$localhost = $_SERVER['HTTP_HOST'];
$arrHost = explode('.', $localhost);

if ($arrHost[0] === 'www') {
    array_shift($arrHost);
}

// Determina si se ejecuta en un servidor local y define las constantes correspondientes.
$isLocal = preg_match('/^(127\.|192\.|localhost$)/', $arrHost[0] ?? '');
if ($isLocal) {
    $carp_raiz = explode('/', $_SERVER['REQUEST_URI'])[1];
    define('TPO_SERV_LOCAL', 1);
    define('BASE_URL', $_SERVER['REQUEST_SCHEME'] . '://' . $localhost . '/' . $carp_raiz);
} else {
    define('TPO_SERV_LOCAL', 0);
    define('BASE_URL', $_SERVER['REQUEST_SCHEME'] . '://' . $localhost);
}

// --- SELECCIÓN DE BASE DE DATOS MULTI-INQUILINO ---
$bdselect = TPO_SERV_LOCAL ? 'mitiendabit' : $arrHost[0];
define('BD_SELECT', strtolower($bdselect));
define('BASE_CLIENTE', '/');

// --- CARGA DE ARCHIVOS DEL NÚCLEO ---
// El orden de estos require es importante para el arranque de la aplicación.
require_once __DIR__ . '/Helpers/Helpers.php';
require_once __DIR__ . '/Librerias/Core/Autoload.php';
require_once __DIR__ . '/Librerias/Core/Load.php';

/**
 * NOTA IMPORTANTE: La siguiente línea, aunque parezca extraña, es el mecanismo que
 * desencadena la ejecución de toda la aplicación. El script `Load.php` sobreescribe
 * la variable `$controller` (que es un string) con una instancia del objeto controlador.
 * Al llamar a `getModel()` en esa instancia, se desencadena la creación del modelo
 * y, a su vez, la conexión a la base de datos.
 * NO ELIMINAR esta línea sin refactorizar completamente el ciclo de vida de la solicitud.
 */
($controller->getModel()->getConexion()->close());