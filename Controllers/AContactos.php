<?php

declare(strict_types=1);

class AContactos extends Controllers {

  private $idModul = 9;

  public function __construct() {

    if (empty($_SESSION['login'])) {
      require_once "Login.php";
      $login = new Login();
      $login->Login();
      exit();
    }
    parent::__construct();
  }

  public function AContactos() {

    $data["modulo"] = $this->idModul;

    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 0) { // validamos que tenga el permiso ver 
      header('location:' . base_url() . 'dashboard');
      exit();
    } else {
      //$empresa = $_SESSION['info_empresa'];
      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'Contactos';
      $data['page_title'] = $data['page_name'];
      $data['logo_desktop'] = $empresa['url_logoMenu'];
      $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

      include __DIR__ . '/../Controllers/Notificacion.php';
      $notificacion = new Notificacion();
      $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
      // las funciones de la pagina van de ultimo 
      $data["page_css"] = array("plugins/datatables/css/datatables.min.css");

      $data["page_functions_js"] = array(
        "plugins/jquery/jquery-3.6.0.min.js",
        "plugins/datatables/js/datatables.min.js",
        "js/functions_aContactos.js");

      $this->views->getView("AContactos", $data);
    }
  }

  public function getContactos() {
    $arrData = $this->model->selectContactos();
    include __DIR__ . '/../Controllers/Notificacion.php';
    $notificacion = new Notificacion();
    //reemplaza los valores 0 y 1 por inactivo - Activo 
    for ($i = 0; $i < count($arrData); $i++) {
      // INDICADOR DE ESTADO 
//            if ($arrData[$i]['status'] == 1) {
//                $arrData[$i]['status'] = '<span class="badge badge-success"> Activo </span>';
//            } else {
//                $arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
//            }
      $id_not = $notificacion->getIdNotificacionesTipo('contacto', $arrData[$i]['idcontacto']); //$_SESSION['userData'];    

      $arrData[$i]['origen'] = $id_not > 0 ? '<span class="badge bg-success font-size-14 me-1">' . $arrData[$i]['origen'] . '</span>' : $arrData[$i]['origen'];

      $ver = '<button class="btn btn-secondary btn-sm btnViewUser" '
          . 'onClick="fntVerContact(' . $arrData[$i]['idcontacto'] . ')" '
          . 'title="Ver contacto" type="button" ><i class="fa fa-eye"></i></button>';
      $arrData[$i]['options'] = '<div class= "text-center">' . $ver . '</div>';
    }

    echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
    exit();
  }

  public function getCto(int $id) {
    $id = intval($id);
    if ($id > 0) {
      //buscamos los datos que correspondan a este id
      $arrData = $this->model->selectContacto($id);

      include __DIR__ . '/../Controllers/Notificacion.php';
      $notificacion = new Notificacion();
      $notificacion->updateNotificacionID('contacto', $arrData['idcontacto'], 1); //$_SESSION['userData'];    


      $direc = $arrData['localidad'] ? $arrData['localidad'] . ', ' : '';
      $direc .= $arrData['ciudad'] ? $arrData['ciudad'] . ', ' : '';
      $direc .= $arrData['pais'] ? $arrData['pais'] : '';
      $arrData['localidad'] = $direc;
      unset($arrData['ciudad']);
      unset($arrData['pais']);
      //si no devuelve ningun dato, respondemos con una array json de dato no encontrado
      if (empty($arrData)) {
        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
      } else {
        $arrResponse = array('status' => true, 'data' => $arrData);
      }
      //IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    exit();
  }

}
