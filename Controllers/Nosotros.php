<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;
use App\Models\TCategoria;
use App\Models\TClientes;
use App\Models\TProducto;

class Nosotros extends Controllers
{
    use TCategoria;
    use TClientes;
    use TProducto;

    public function __construct()
    {
        // session_start() is already handled in index.php
        parent::__construct();
    }

    public function nosotros($param)
    {
        $empresa = $_SESSION['info_empresa'];
        if (($empresa['fecha_mantenimiento_hasta'] ?? '') > date("Y-m-d H:i:s")) {
            header("Location:" . base_url() . 'enConstruccion');
            exit();
        }

        $data['page_name'] = "Nosotros";
        $data['empresa'] = $empresa;
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $meta = $empresa;
        $data['meta'] = [
            'robots' => 'index, follow, archive',
            'title' => $meta['nombre_comercial'],
            'description' => substr(strClean(strip_tags($meta['descripcion'])), 0, 160),
            'keywords' => $meta['tags'],
            'url' => base_url(),
            'image' => $meta['url_logoImpreso'],
            'image:type' => 'image/' . pathinfo($meta['logo_imp'], PATHINFO_EXTENSION),
            'og:type' => 'website'
        ];
        $data['menu_categorias'] = $this->getCategoriasMenuTienda();
        $data['footer_cat'] = $this->getCategoriasFooterT();

        $this->views->getView("Nosotros", $data);
    }
}