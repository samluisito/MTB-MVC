<?php

declare(strict_types=1);

//define('TIME_INI', microtime(true));  Ultima version en produccion
session_unset();

$url = $_SERVER['REQUEST_URI'];

function dep($data, $json = false) {
  $trace = debug_backtrace();
  $file = str_replace($_SERVER['DOCUMENT_ROOT'], '', $trace[0]['file']);
  $line = $trace[0]['line'];
  $output = '<pre><br><b>';
  if ($json) {
    $output .= json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  } else {
    $output .= print_r($data, true);
  }
  $output .= '</b>';
  $output .= '<br>Archivo: ' . $file . ' - línea ' . $line;
  $output .= '<hr></pre>';
  echo $output;
}

function dep_time($arr_dep_time = null) {
  $trace = debug_backtrace();
  $pos = isset($trace[1]['file']) ? 0 : 0;
  $file = str_ireplace('/opt/lampp/htdocs/mitiendabit', '', $trace[$pos]['file']);
  $line = $trace[$pos]['line'];
  $time = round(microtime(true) - TIME_INI, 6);
  print('<pre>');
  if ($arr_dep_time === null) {
    print($time . " - time");
    print('<br>');
    print 'Archivo: ' . $file . ' - línea ' . $line;
  } else if (is_array($arr_dep_time)) {

//    print($arr_dep_time[0] . ' - time linea ' . $arr_dep_time[2] . '<br>');
    print(($time - $arr_dep_time[0]) . " - time entre linea $arr_dep_time[2] y $line" . '<br>');
    print ($time . " - time total <br>");
    print 'Archivo: ' . $file . ' - línea ' . $line;
  }
  print('<hr>');
  print('</pre>');
  return array($time, $file, $line);
}

// Obtenemos el controlador y el método
$arrUrl = explode('/', $_GET['url'] ?? 'home/home');
$controller = $arrUrl[0];
$method = (isset($arrUrl[1]) && ($arrUrl[1] != '')) ? $arrUrl[1] : $controller;

// Obtenemos los parámetros
$params = '';
if (count($arrUrl) > 2) {
  $params = implode(',', array_slice($arrUrl, 2));
}

// Definimos la base de la URL
$localhost = $_SERVER['HTTP_HOST'];

// Seleccionamos la base de datos del cliente
$arrHost = explode('.', $localhost);

if ($arrHost[0] === 'www') {
  array_shift($arrHost);
}
//dep_time();

if (preg_match('/^(127\.|192\.|localhost$)/', count($arrHost) == 1 ? $arrHost[0] : $arrHost[1])) {
  // Si estamos en un servidor local, definimos la carpeta raíz
  $carp_raiz = explode('/', $_SERVER['REQUEST_URI'])[1];
  define('TPO_SERV_LOCAL', 1);
  define('BASE_URL', $_SERVER['REQUEST_SCHEME'] . '://' . $localhost . '/' . $carp_raiz);
} else {
  define('TPO_SERV_LOCAL', 0);
  define('BASE_URL', $_SERVER['REQUEST_SCHEME'] . '://' . $localhost);
}



/* SELECCION DE BD CLIENTE DINAMICO POR URL===================================== */
if (session_status() === PHP_SESSION_NONE) {
  session_cache_expire(30);
  session_start();
//  $cache_expire = session_cache_expire();
//  dep($cache_expire);
}
$bdselect = in_array($arrHost[0], ['127', '192', 'localhost']) ? 'mitiendabit' : $arrHost[0];

define('BD_SELECT', strtolower($bdselect));
define('BASE_CLIENTE', '/');
//session_unset();
//session_destroy();
require_once __DIR__ . '/Helpers/Helpers.php';
require_once __DIR__ . '/Librerias/Core/Autoload.php';
require_once __DIR__ . '/Librerias/Core/Load.php';

//    dep('pruebas de hora ...');
//    $query = "SELECT CURRENT_TIMESTAMP AS server_time";
//    $resp = $controller->getModel()->getConexion()->query($query);
//    dep($resp->fetch_assoc()['server_time']);
//
//    dep("Zona horaria actual: " . date_default_timezone_get());
//    $hora_actual = date('Y-m-d H:i:s');
//    dep($hora_actual);


($controller->getModel()->getConexion()->close());
//dep_time();
//session_unset();
//session_destroy();
