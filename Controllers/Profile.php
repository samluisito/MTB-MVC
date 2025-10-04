<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Profile extends Controllers
{
    public function __construct()
    {
        if (empty($_SESSION['userData']['idpersona'])) {
            header("Location:" . base_url());
            exit();
        }
        if (($_SESSION['info_empresa']['fecha_mantenimiento_hasta'] ?? '') > date("Y-m-d H:i:s")) {
            header("Location:" . base_url() . 'enConstruccion');
            exit();
        }
        parent::__construct();
    }

    public function profile()
    {
        $homeController = new Home();
        $data['header'] = $homeController->data_header('Profile');
        $data['footer'] = $homeController->data_footer();

        $empresa = $_SESSION['info_empresa'];
        $data['empresa'] = $empresa;

        $data['meta'] = [
            'robots' => 'index, follow, archive',
            'title' => $empresa['nombre_comercial'],
            'description' => substr(strClean(strip_tags($empresa['descripcion'])), 0, 160),
            'keywords' => $empresa['tags'],
            'url' => base_url(),
            'image' => $empresa['url_logoImpreso'],
            'image:type' => 'image/' . pathinfo($empresa['logo_imp'], PATHINFO_EXTENSION),
            'og:type' => 'website'
        ];

        $data['tbPedidos'] = $this->model->ultimosPedidos();

        $data["page_css"] = [];
        $data["page_functions_js"] = ['functionsProfile.js'];

        $this->views->getView("Profile", $data);
    }
}