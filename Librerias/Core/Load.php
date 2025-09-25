<?php

/* Este código busca el archivo del controlador, 
 * verifica si existe y luego crea una instancia del controlador. 
 * A continuación, verifica si el método existe y lo ejecuta con los parámetros dados, 
 * o muestra una página de error si el método no existe. */
// Ruta del archivo del controlador
$controllerFile = __DIR__ . '/../../Controllers/' . ucwords($controller) . '.php';

// Verifica si el archivo del controlador existe
if (file_exists($controllerFile)) {

  // Requiere el archivo del controlador
  require_once $controllerFile;

  // Crea una instancia del controlador
  $controller = new $controller();

  // Verifica si el método existe y lo ejecuta con los parámetros dados
  method_exists($controller, $method) ? $controller->{$method}($params) : require('Controllers/Error.php');
    
} else {
  // Si el archivo del controlador no existe, muestra la página de error
  require_once __DIR__ . '/../../Controllers/Error.php';
}
