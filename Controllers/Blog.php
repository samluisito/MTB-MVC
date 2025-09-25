<?php

declare(strict_types=1);
require_once("Models/TCategoria.php");  // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 
require_once("Models/TProducto.php");  // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 
require_once("Models/TTipoPago.php");  // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 
require_once("Models/TClientes.php");  // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 
require_once("Models/TBlog.php");  // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 

class Blog extends Controllers {

  use TCategoria,
      TTipoPago,
      TClientes,
      TProducto, //llama al uso de metodos de trait
      TBlog; //llama al uso de metodos de trait

  public function __construct() {
    if ($_SESSION['info_empresa']['fecha_mantenimiento_hasta'] > date("Y-m-d H:i:s")) {
      header("Location:" . base_url() . 'enConstruccion');
      exit();
    }
    parent::__construct();
  }

  /* ------------------------------------------------------------------------------------------------------------------------------------ */

  public function Blog() {
    $empresa = $_SESSION['info_empresa'];
    $data["empresa"] = $empresa;

    $data['page_name'] = "Blog";
    $data['cantCarrito'] = cantCarrito();
    $data['path'] = path();
    $data['page_title'] = $data['page_name'];
    $data['logo_desktop'] = $empresa['url_logoMenu'];
    $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

    $meta = $empresa;
    $data['meta'] = array(
      'robots' => 'index, follow, archive',
      'title' => $meta['nombre_comercial'] . ' Blog',
      'description' => substr(strClean(strip_tags($meta['descripcion'])), 0, 160),
      'keywords' => $meta['tags'],
      'url' => base_url(),
      'image' => DIR_MEDIA . 'images/bg-02.jpg', //$meta['url_logoImpreso'],
      'image:type' => 'bg-02.jpg', //explode('.', $meta['logo_imp'])[1],
      'og:type' => 'blog'
    );
    $data['entradas'] = $this->getEntradas();
    $data['productos_destacados'] = $this->getProducBlog(4, 'r');

    $data['menu_categorias'] = $this->getCategoriasMenuTienda();

    $data['footer_cat'] = $this->getCategoriasFooterT();
    $data["page_css"] = array();
    $data["page_functions_js"] = array();
    $this->views->getView("Blog", $data);
  }

  /* ------------------------------------------------------------------------------------------------------------------------------ */

  public function Entrada($url) {

    if (empty($url)) {
      header("HTTP/1.0 404 Not Found");
      exit();
      sleep(3);
      header("Location:" . base_url());
      exit();
    }

    $empresa = $_SESSION['info_empresa'];
    if ($empresa['fecha_mantenimiento_hasta'] > date("Y-m-d H:i:s")) {
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
    $data['meta'] = array(
      'robots' => 'index, follow, archive',
      'title' => $meta['titulo'],
      'description' => substr(strClean(strip_tags($meta['descripcion'])), 0, 160),
      'keywords' => $meta['tags'],
      'url' => $meta['url'],
      'image' => $meta['img_url'],
      'image:type' => explode('.', $meta['img'])[1],
      'og:type' => 'article'
    );

    $url = strClean($url);

    $data['productos_destacados'] = $this->getProducBlog(4, 'r');

    $data['menu_categorias'] = $this->getCategoriasMenuTienda();

    $data['footer_cat'] = $this->getCategoriasFooterT();

    // las funciones de la pagina van de ultimo 
    $data["page_css"] = array("tinymce.min.css");
    $data["page_functions_js"] = array();

    $this->views->getView("Entrada", $data);

    exit();
  }

  /* ============================================================================================================================ */


  /* ============================================================================================================================ */
}
