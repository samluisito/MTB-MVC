<?php

declare(strict_types=1);

class Views
{
    /**
     * El constructor utiliza la promoción de propiedades del constructor de PHP 8.1,
     * lo que simplifica la declaración de la propiedad del nombre del controlador.
     */
    public function __construct(private string $controllerName)
    {
    }

    /**
     * Renderiza un archivo de vista.
     *
     * Este método localiza el archivo de vista basándose en el controlador que lo llama.
     * Extrae el array de datos proporcionado en variables para que la vista las utilice.
     *
     * @param string $view El nombre del archivo de vista (sin la extensión .php).
     * @param array  $data Los datos que estarán disponibles para la vista.
     */
    public function getView(string $view, array $data = []): void
    {
        $viewPath = __DIR__ . '/../../Views/';

        // El controlador 'Home' es un caso especial donde las vistas están en la raíz del directorio Views.
        // Para todos los demás controladores, las vistas están en un subdirectorio con el nombre del controlador.
        if ($this->controllerName === 'Home') {
            $viewFile = $viewPath . $view . '.php';
        } else {
            $viewFile = $viewPath . $this->controllerName . '/' . $view . '.php';
        }

        if (file_exists($viewFile)) {
            // Extrae las claves del array de datos en variables para el archivo de vista.
            // Por ejemplo, $data['page_title'] se convierte en $page_title en la vista.
            extract($data, EXTR_SKIP);
            require_once $viewFile;
        } else {
            // Si no se encuentra el archivo de vista, es un error crítico.
            die("Error: La vista '{$viewFile}' no fue encontrada.");
        }
    }
}