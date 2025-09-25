<?php
declare(strict_types=1);
class Calendar extends Controllers {

  public function __construct() {
    if ($_SESSION['info_empresa']['fecha_mantenimiento_hasta'] > date("Y-m-d H:i:s")) {
      header("Location:" . base_url() . 'enConstruccion');
      exit();
    }
    if (empty($_SESSION['login'])) {
      require_once "Login.php";
      $login = new Login();
      $login->Login();
      exit();      
    }
    parent::__construct();
  }

  public function Calendar() {
    $empresa = $_SESSION['info_empresa'];

    $data['page_name'] = 'Calendar';
    $data['page_title'] = $data['page_name'];
    $data['logo_desktop'] = $empresa['url_logoMenu'];
    $data['shortcut_icon'] = $empresa['url_shortcutIcon'];
          /*       * ******************************************* */
      include __DIR__ . '/../Controllers/Notificacion.php';
      $notificacion = new Notificacion();
      $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
      /*       * ******************************************* */


    // las funciones de la pagina van de ultimo 
    $data["page_css"] = array("fullcalendar.css");
    $data["page_functions_js"] = array("plugins/fullcalendar5.min.js", "plugins/moment-with-locales.min.js", "functions_categorias.js");

    $this->views->getView("Calendar", $data);
  }

}
