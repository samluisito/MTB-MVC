<?php

declare(strict_types=1);
require_once("Librerias/vendor/autoload.php");

use Spipu\Html2Pdf\Html2Pdf;

class Factura extends Controllers {

  private $idModul = 5;

  public function __construct() {

    if (empty($_SESSION['login'])) {
      require_once "Login.php";
      $login = new Login();
      $login->Login();
      exit();         //header('location:' . base_url() . 'login');
    }
    parent::__construct();
  }

  public function generarFactura($id) {

    $idpedido = strClean($id);
    $empresa = $_SESSION['info_empresa'];
    if (is_numeric($id)) {
      $data = $this->model->selectPedidoId($idpedido);
      $rutaDirVista = 'Template/Modals/comptobantePDF';
      $data['infoEmpresa'] = $empresa;
      ob_end_clean();

      $html = getFile($rutaDirVista, $data);

      $html2pdf = new Html2Pdf();
      $html2pdf->writeHTML($html);
      $html2pdf->output();

      dep($data);
    } else {
      
    }
    $data["modulo"] = $this->idModul;

    $data["empresa"] = $empresa;

    $data['page_name'] = 'Pedidos';
    $data['page_title'] = $data['page_name'];
    $data['logo_desktop'] = $empresa['url_logoMenu'];
    $data['shortcut_icon'] = $empresa['url_shortcutIcon'];
    /*     * ******************************************* */
    include __DIR__ . '/../Controllers/Notificacion.php';
    $notificacion = new Notificacion();
    $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
    /*     * ******************************************* */

    // las funciones de la pagina van de ultimo 
    $data["page_css"] = array("plugins/datatables/css/datatables.min.css");

    $data["page_functions_js"] = array(
      "plugins/jquery/jquery-3.6.0.min.js",
      "plugins/datatables/js/datatables.min.js",
      "functions_pedidos.js");

    /* /$this->views->getView("Pedidos", $data);

      }

      /* ======================================================================================================================================== */
  }

}
