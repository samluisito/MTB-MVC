<?php

declare(strict_types=1);

class Modulos extends Controllers {

  private $idModul = 7;

  public function __construct() {

    if (empty($_SESSION['login'])) {
      require_once "Login.php";
      $login = new Login();
      $login->Login();
      exit(); //header('location:' . base_url() . 'login');
    }
    parent::__construct();
  }

  public function Modulos($params) {
//ejecuta el contenido del archivo home
//echo 'Mensaje desde el controlador home';

    $data["modulo"] = $this->idModul;
    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1) {
      //$empresa = $_SESSION['info_empresa'];
      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'Modulos';
      $data['page_title'] = $data['page_name'];
      $data['logo_desktop'] = $empresa['url_logoMenu'];
      $data['shortcut_icon'] = $empresa['url_shortcutIcon'];
            /*       * ******************************************* */
      include __DIR__ . '/../Controllers/Notificacion.php';
      $notificacion = new Notificacion();
      $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
      /*       * ******************************************* */


      // las funciones de la pagina van de ultimo 
      $data["page_css"] = array(
        "plugins/datatables/css/datatables.min.css");

      $data["page_functions_js"] = array(
        "plugins/jquery/jquery-3.6.0.min.js",
        "plugins/datatables/js/datatables.min.js",
        "js/functions_modulos.js");

      $this->views->getView("Modulos", $data);
    } else {
      header('location:' . base_url() . 'dashboard');
      exit();
    }
  }

  //CREAR - ACTUALIZAR MODULO
  public function setModulo() {

    //recibe los datos por medio de url y devuelve un mensaje json segun su resultado
    //los datos enviados los almacenamos en variables
    $intIdModulo = intval($_POST['idModulo']);
    $strTitulo = strClean($_POST['txtTitulo']);
    $strDescripcion = strClean($_POST['txtDescripcion']);
    $intStatus = intval($_POST['listStatus']);

    //validamos por medio del id si es un nuevo rol o si se actualiza un rol.
    if ($intIdModulo == 0) {

      //creamos un nuevo Permisi, enviamos los datos al modelo
      $request_modulo = $this->model->insertModulo($strTitulo, $strDescripcion, $intStatus);
      $option = 1;
    } else {
      //Actualiamos un rol
      $request_modulo = $this->model->updateModulo($intIdModulo, $strTitulo, $strDescripcion, $intStatus);
      $option = 2;
    }

    // depemdiendo de la respuesta enviamos un mensaje 
    if ($request_modulo > 0) {
      if ($option == 1) {
        $arrResponse = array('status' => true, 'msg' => 'Datos Guardados Correctamente');
      } else {
        $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados Correctamente');
      }
    } else if ($request_modulo == 'exist') {
      $arrResponse = array('status' => false, 'msg' => 'Atencion El Modulo Ya Existe');
    } else {
      $arrResponse = array('status' => false, 'msg' => 'No es posible Guardar el Modulo');
    }
    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    exit();
  }

  public function getModulos() {
    //DEVUELVE UN ARRAY CON LOS DATOS DE ROLES Y BOTONES DE OPCION BOOSTRAP PARA INSERTAR EN DATATABLE
    $arrData = $this->model->selectModulos();
    //reemplaza los valores 0 y 1 por inactivo - Activo 
    foreach ($arrData as $i => $item) {
      $id = $arrData[$i]['idmodulo'];
      // BOTONES DE OPCIONES
      $opciones = "<div class= 'text-center'>";
      //$opciones .= "<button class='btn btn-secondary m-1' onClick='fntVer({$id})' title='Ver' type='button' ><i class='fas fa-eye'></i></button>";
      $opciones .= $_SESSION['userPermiso'][$this->idModul]['actualizar'] == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar' type='button'><i class='fas fa-edit'></i></button>" : '';
      $opciones .= $item['status'] == 1 ?
          "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado'    type='button' id='btnStatus{$id}' value='1'><i class='fa fa-power-off' aria-hidden='true'></i></button>" :
          "<button class='btn btn-danger m-1 ' onClick='fntStatus({$id})' title='Desactivado' type='button' id='btnStatus{$id}' value='0'><i class='fa fa-power-off' aria-hidden='true'></i></button>";
//      if ($_SESSION['userPermiso'][$this->idModul]['eliminar'] == 1) { // si el rol esta en uso solo podra ser activado o desactivado
      $opciones .= $this->model->moduloEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar' type='button'><i class='fas fa-trash-alt'></i></button>";
//      }
      $arrData[$i]['options'] = $opciones . "</div>";
      // INDICADOR DE ESTADO reemplaza los valores 0 y 1 por inactivo - Activo 
      $arrData[$i]['status'] = $item['status'] == 1 ? "<span class='badge bg-success'> Activo </span>" : "<span class='badge bg-danger'>Inactivo</span>";
    }
    exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
  }

  //BUSCA UN SOLO MODILO 
  public function getModulo(int $idModulo) {
    $intIdModulo = intval($idModulo); //limpiamos los datos que vienen dentro de la variable $intIdModulo
    if ($intIdModulo > 0) { //si el contenido de la variable es mayor a 0 significa que hay un id a buscar
      $arrData = $this->model->selectModulo($intIdModulo); //buscamos los datos que correspondan a este id
      if (empty($arrData)) {//si no devuelve ningun dato, respondemos con una array json de dato no encontrado
        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
      } else {//POR EL CONTRARIO si devuelve CON DATOS, respondemos con una array json CON LOS DATOS encontrado
        $arrResponse = array('status' => true, 'data' => $arrData,);
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE); //IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON 
    }
    exit();
  }

  //SETEA UN MODULO COMO BORRADO
  public function delModulo() {

    if ($_POST) {
      $intIdModulo = intval($_POST['idModulo']); //inval convierte en entero el parametro que le ingresen

      $requestDel = $this->model->deleteModulo($intIdModulo);

      if ($requestDel == 'OK') {
        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Modulo');
      } else if ($requestDel == 'exist') {
        $arrResponse = array('status' => false, 'msg' => 'No es posile eliminar el Modulo');
      } else {
        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Modulo');
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    exit();
  }

  //SETEA EL ESTATUS DE MODULO ENTRE ACTIVO E INACTIVO 
  public function statusModulo() {

    $verdadero = intval($_GET);

    if ($verdadero) {

      $intIdModulo = intval($_GET['idModulo']); //inval convierte en entero el parametro que le ingresen
      $intStatus = intval($_GET['intStatus']);

      $requestStatus = $this->model->statusModulo($intIdModulo, $intStatus);

      if ($requestStatus == 'OK') {
        if ($intStatus == 1) {
          $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado el Modulo');
        } elseif ($intStatus == 0) {
          $arrResponse = array('status' => true, 'msg' => 'Se ha Activado el Modulo');
        }
      } else if ($requestDel == 'error') {
        $arrResponse = array('status' => false, 'msg' => 'No es posile desactivar el Modulo');
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    exit();
  }

}
