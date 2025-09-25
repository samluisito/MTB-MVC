<?php

declare(strict_types=1);
require("Models/TCategoria.php");  // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 
require("Models/TProducto.php");  // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 
require("Models/TClientes.php");  // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 
require("Models/LoginModel.php");  // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 

class Nosotros extends Controllers {

  use TCategoria,
      TClientes,
      TProducto; //llama al uso de metodos de trait

  public function __construct() {
    if (empty($_SESSION)) {
      session_start();
    }
    parent::__construct();
  }

  /* ----------------------------------------------------------------------------------------------------------------------- */

  public function nosotros($param) {
//        if (empty($_SESSION['dataorden'])) {
//            header("Location:" . base_url()); exit();
//        } else {

    $empresa = $_SESSION['info_empresa'];
    if ($empresa['fecha_mantenimiento_hasta'] > date("Y-m-d H:i:s")) {
      header("Location:" . base_url() . 'enConstruccion');
      exit();
    }

    $data['page_name'] = "Solicitud Aprobada";
    $data['empresa'] = $empresa;
    $data['page_title'] = $data['page_name'];
    $data['logo_desktop'] = $empresa['url_logoMenu'];
    $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

    $meta = $empresa;
    $data['meta'] = array(
      'robots' => 'index, follow, archive',
      'title' => $meta['nombre_comercial'],
      'description' => substr(strClean(strip_tags($meta['descripcion'])), 0, 160),
      'keywords' => $meta['tags'],
      'url' => base_url(),
      'image' => $meta['url_logoImpreso'],
      'image:type' => explode('.', $meta['logo_imp'])[1],
      'og:type' => 'website'
    );
    $data['menu_categorias'] = $this->getCategoriasMenuTienda();

    $data['footer_cat'] = $this->getCategoriasFooterT();

    $this->views->getView("Nosotros", $data);
//        }
  }

  /* ----------------------------------------------------------------------------------------------------------------------- */
}
