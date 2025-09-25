<?php

// Usar declaración de tipos estrictos
declare(strict_types=1);

class Views {

  // Usar la sintaxis de declaración de propiedad
  private string $controller;

  // Inyectar la clase de controlador como parámetro
  function __construct(string $class) {
    $this->controller = $class;
  }

  // Usar la sintaxis de plantilla para la función de vista
  function getView(string $view, $data = ''): void {

    // Usar constantes en lugar de cadenas de texto duro
    $viewPath = __DIR__ . '/../../Views/';
    $controllerPath = $viewPath . $this->controller . '/';

    // Usar la sintaxis de ternario para la inclusión de vistas
    $this->controller === 'Home' ?
            require_once $viewPath . $view . '.php' :
            require_once $controllerPath . $view . '.php';
  }

}
