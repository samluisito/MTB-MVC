<?php

// ejecutamos un autoad para una clase hija de otra clase.
// y recibe como parametro una funcion que recibe una variable, 
// esta funcion comprobara que el aechivo exista antes de requerirlo
// el cual sera una extencion de la clase home que es hija de controler y se ecuentra en otra carpeta
spl_autoload_register(function ($class) {
  $file = __DIR__ . '/./' . str_replace('\\', '/', $class) . '.php';
  file_exists($file) ? require_once $file : '';
  //file_exists('Librerias/Core/' . $class . '.php') ? require_once __DIR__.'/Librerias/Core/' . $class . '.php' : null;
});
