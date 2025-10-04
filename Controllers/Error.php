<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Error extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function notFound()
    {
        if (isset($_SESSION['info_empresa'])) {
            // Cargar datos del controlador Home para el encabezado y pie de página
            $homeController = new Home();
            $data['header'] = $homeController->data_header('ERROR 404');
            $data['footer'] = $homeController->data_footer();

            $empresa = $_SESSION['info_empresa'];
            $data['empresa'] = $empresa;
            $this->views->getView("error", $data);
        } else {
            // Vista de error simple si la información de la empresa no está disponible
            $data['page_name'] = 'ERROR 404';
            $data['page_title'] = $data['page_name'];
            $data['logo_desktop'] = DIR_MEDIA . 'images/upss-error.png';
            $data['shortcut_icon'] = DIR_MEDIA . 'images/upss-error.png';
            $this->views->getView("error", $data);
        }
    }
}