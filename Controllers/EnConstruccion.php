<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;
use App\Models\TContacto;
use App\Models\TCategoria;

class EnConstruccion extends Controllers
{
    use TCategoria;
    use TContacto;

    public function __construct()
    {
        parent::__construct();
    }

    public function enConstruccion()
    {
        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'enConstruccion';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $meta = $data["empresa"];

        $data['meta'] = [
            'robots' => 'noindex, nofollow, noarchive',
            'title' => $meta['nombre_comercial'],
            'description' => substr(strClean(strip_tags($meta['descripcion'])), 0, 160),
            'keywords' => $meta['tags'],
            'url' => base_url(),
            'image' => $meta['url_logoImpreso'],
            'image:type' => 'image/' . pathinfo($meta['logo_imp'], PATHINFO_EXTENSION),
            'og:type' => 'website'
        ];

        $data['footer_cat'] = $this->getCategoriasFooterT();

        $data["page_css"] = ['simplyCountdown.min.css'];
        $data["page_functions_js"] = ['plugins/simplyCountdown.min.js', 'functions_enConstruccion.js'];

        $this->views->getView("EnConstruccion", $data);
    }
}