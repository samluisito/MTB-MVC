<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;
use App\Models\TCategoria;
use App\Models\TProducto;
use App\Models\TTipoPago;
use App\Models\TClientes;
use App\Models\TBlog;

class Blog extends Controllers
{
    use TCategoria;
    use TTipoPago;
    use TClientes;
    use TProducto;
    use TBlog;

    public function __construct()
    {
        if (($_SESSION['info_empresa']['fecha_mantenimiento_hasta'] ?? '') > date("Y-m-d H:i:s")) {
            header("Location:" . base_url() . 'enConstruccion');
            exit();
        }
        parent::__construct();
    }

    public function blog()
    {
        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;

        $data['page_name'] = "Blog";
        $data['cantCarrito'] = cantCarrito();
        $data['path'] = path();
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $meta = $empresa;
        $data['meta'] = [
            'robots' => 'index, follow, archive',
            'title' => $meta['nombre_comercial'] . ' Blog',
            'description' => substr(strClean(strip_tags($meta['descripcion'])), 0, 160),
            'keywords' => $meta['tags'],
            'url' => base_url(),
            'image' => DIR_MEDIA . 'images/bg-02.jpg',
            'image:type' => 'image/jpeg',
            'og:type' => 'blog'
        ];
        $data['entradas'] = $this->getEntradas();
        $data['productos_destacados'] = $this->getProducBlog(4, 'r');
        $data['menu_categorias'] = $this->getCategoriasMenuTienda();
        $data['footer_cat'] = $this->getCategoriasFooterT();
        $data["page_css"] = [];
        $data["page_functions_js"] = [];
        $this->views->getView("Blog", $data);
    }

    public function entrada($url)
    {
        if (empty($url)) {
            header("HTTP/1.0 404 Not Found");
            sleep(3);
            header("Location:" . base_url());
            exit();
        }

        $empresa = $_SESSION['info_empresa'];
        if (($empresa['fecha_mantenimiento_hasta'] ?? '') > date("Y-m-d H:i:s")) {
            header("Location:" . base_url() . 'enConstruccion');
            exit();
        }

        $data['empresa'] = $empresa;
        $data['page_name'] = "Blog";
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $data['entrada'] = $this->getEntrada($url);
        $meta = $data['entrada'];
        $data['meta'] = [
            'robots' => 'index, follow, archive',
            'title' => $meta['titulo'],
            'description' => substr(strClean(strip_tags($meta['descripcion'])), 0, 160),
            'keywords' => $meta['tags'],
            'url' => $meta['url'],
            'image' => $meta['img_url'],
            'image:type' => 'image/' . pathinfo($meta['img'], PATHINFO_EXTENSION),
            'og:type' => 'article'
        ];

        $data['productos_destacados'] = $this->getProducBlog(4, 'r');
        $data['menu_categorias'] = $this->getCategoriasMenuTienda();
        $data['footer_cat'] = $this->getCategoriasFooterT();
        $data["page_css"] = ["tinymce.min.css"];
        $data["page_functions_js"] = [];

        $this->views->getView("Entrada", $data);
        exit();
    }
}