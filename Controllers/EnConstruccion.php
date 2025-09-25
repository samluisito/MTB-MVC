<?php
declare(strict_types=1);
require_once('Models/TContacto.php');   // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 
require_once("Models/TCategoria.php");    // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 

class EnConstruccion extends Controllers {

  use TCategoria,
      TContacto; //llama al uso de metodos de trait

  public function __construct() {
    parent::__construct();
  }

  public function EnConstruccion() {

    $data["empresa"] = $_SESSION['info_empresa'];
    $data['page_name'] = 'enConstruccion';
    $data['page_title'] = $data['page_name'];
    $data['logo_desktop'] = $empresa['url_logoMenu'];
    $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

    $meta = $data["empresa"];

    $data['meta'] = array(
      'robots' => 'noindex, nofollow, noarchive',
      'title' => $meta['nombre_comercial'],
      'description' => substr(strClean(strip_tags($meta['descripcion'])), 0, 160),
      'keywords' => $meta['tags'],
      'url' => base_url(),
      'image' => $meta['url_logoImpreso'],
      'image:type' => explode('.', $meta['logo_imp'])[1],
      'og:type' => 'website'
    );

    $data['footer_cat'] = $this->getCategoriasFooterT();

    $data["page_css"] = array('simplyCountdown.min.css');
    $data["page_functions_js"] = array('plugins/simplyCountdown.min.js', 'functions_enConstruccion.js');
    $this->views->getView("EnConstruccion", $data);
  }

}
