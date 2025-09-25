<?php

declare(strict_types=1);

class Errors extends Controllers {

  public function __construct() {
    parent::__construct();
  }

  public function notFound() {
    if (isset($_SESSION['info_empresa'])) {
      /*       * ******************************************* */
      include_once __DIR__ . '/../Controllers/Home.php';
      $this->data = new Home();
      $data['header'] = $this->data->data_header('ERROR 404');
      $data['footer'] = $this->data->data_footer();
      /*       * ******************************************* */
      $empresa = $_SESSION['info_empresa'];
      $data['empresa'] = $empresa;
      $this->views->getView("error", $data);
    } else {
      $data['page_name'] = 'ERROR 404';
      $data['page_title'] = $data['page_name'];
      $data['logo_desktop'] = DIR_MEDIA . 'images/upss-error.png';
      $data['shortcut_icon'] = DIR_MEDIA . 'images/upss-error.png';
      $this->views->getView("error", $data);
    }
  }

}

$notFound = new Errors();
$notFound->notFound();

