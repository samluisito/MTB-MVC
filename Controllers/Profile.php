<?php

declare(strict_types=1);

class Profile extends Controllers {

  public function __construct() {

    if (empty($_SESSION['userData']['idpersona'])) {
      header("Location:" . base_url());
    }
    if ($_SESSION['info_empresa']['fecha_mantenimiento_hasta'] > date("Y-m-d H:i:s")) {
      header("Location:" . base_url() . 'enConstruccion');
    }
    parent::__construct();
  }

  /* ----------------------------------------------------------------------------------------------------------------------- */

  public function Profile() {

    /*     * ******************************************* */
    include __DIR__ . '/../Controllers/Home.php';
    $this->data = new Home();
    $data['header'] = $this->data->data_header('Profile');
    $data['footer'] = $this->data->data_footer();
    /*     * ******************************************* */


    $empresa = $_SESSION['info_empresa'];
    $data['empresa'] = $empresa;

    $data['meta'] = array(
      'robots' => 'index, follow, archive',
      'title' => $empresa['nombre_comercial'],
      'description' => substr(strClean(strip_tags($empresa['descripcion'])), 0, 160),
      'keywords' => $empresa['tags'],
      'url' => base_url(),
      'image' => $empresa['url_logoImpreso'],
      'image:type' => explode('.', $empresa['logo_imp'])[1],
      'og:type' => 'website'
    );

    $data['tbPedidos'] = $this->model->ultimosPedidos();

    /* paginador */
//    $pagina = 0;
//    if (isset($_GET['page'])) {
//      $pagina = is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
//    }
//    $pagina = $pagina == 0 ? 1 : $pagina;

    // las funciones de la pagina van de ultimo 
    $data["page_css"] = array();
    $data["page_functions_js"] = array('functionsProfile.js');

    $this->views->getView("Profile", $data);
  }

  /* ----------------------------------------------------------------------------------------------------------------------- */
}
