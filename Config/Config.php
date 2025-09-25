<?php

/* SELECCION DE BD CLIENTE DINAMICO POR URL===================================== */
session_status() < 2 ? session_start() : '';
if (empty($_SESSION['base'])) {
  require 'DB_Config.php';
  $base = new selectBase();
  $consul = $base->consulTab($bdselect, $serv_local);
  if ($consul === 0) {//si la base de datos no se encuentra, entonces muestra pagina de error
    $url = $_SERVER['HTTP_HOST']; // capturamos de la url la variable url, si esta esta iniciada !empty,sera igual ' ? ' a url,  de lo contrario ' : ' sera igual a 'Home'
    $localschema = $_SERVER['REQUEST_SCHEME'];
    $localhost = $_SERVER['HTTP_HOST'];
    if ($serv_local) { // validamos si la url contien localhost, para definirla base url      
      $carp_raiz = explode('/', $_SERVER['REQUEST_URI'])[1]; //definimos la carpeta raiz
      define('BASE_URL', $localschema . '://' . $localhost . '/' . $carp_raiz);
    } else {
      define('BASE_URL', $localschema . '://' . $localhost);
    }
    define('BASE_CLIENTE', '/');
    define('FILE_SISTEM_CLIENTE', $bdselect . '/');
    include 'Propiedades.php';
    require_once('./Controllers/Error.php');
    exit;
  }
}

/* DEFINICION DE URL DINAMICA PARA UBICACION DE ARCHIVOS Y URL================== */
$url = $_SERVER['HTTP_HOST']; // capturamos de la url la variable url, si esta esta iniciada !empty,sera igual ' ? ' a url,  de lo contrario ' : ' sera igual a 'Home'
$localschema = $_SERVER['REQUEST_SCHEME'];
$localhost = $_SERVER['HTTP_HOST'];

if ($serv_local) { // validamos si la url contien localhost, para definirla base url      
  $carp_raiz = explode('/', $_SERVER['REQUEST_URI'])[1]; //definimos la carpeta raiz
  define('BASE_URL', $localschema . '://' . $localhost . '/' . $carp_raiz);
} else {
  define('BASE_URL', $localschema . '://' . $localhost);
}

define('BASE_CLIENTE', '/');
define('FILE_SISTEM_CLIENTE', $bdselect . '/');

 
include 'Propiedades.php';

