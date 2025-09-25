<?php

declare(strict_types=1);

class Clientes extends Controllers {

  private $idModul = 3;

  public function __construct() {

    if (empty($_SESSION['login'])) {
      require_once "Login.php";
      $login = new Login();
      $login->Login();
      exit();         //header('location:' . base_url() . 'login');
    }
    parent::__construct();
  }

  public function Clientes($params) {

    $data["modulo"] = $this->idModul;

    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 0) { // validamos que tenga el permiso ver 
      header('location:' . base_url() . 'dashboard');
      exit();
    } else {
      //$empresa = $_SESSION['info_empresa'];
      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'Clientes';
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
        "js/functions_clientes.js");

      $this->views->getView("Clientes", $data);
    }
  }

  public function setCliente() {
    if ($_POST) {  // validamos que post sea true 
      if (//validamos que los datos no esten vacios 
          empty($_POST['txtIdentificacion']) || empty($_POST['txtNombre']) ||
          empty($_POST['txtApellido']) || empty($_POST['txtEmail']) ||
          empty($_POST['txtTelefono']) || empty($_POST['texNit']) ||
          empty($_POST['txtNombreFiscal']) || empty($_POST['txtDirFiscal'])
      ) {// si estan vacios 
        $arrResponse = array("status" => false, "msg" => 'Revisa el formulario, faltan datos o estan incorrectos');
      } else {// si los datos estan ok pasamos los datos de post a variables
        $idUser = intval($_POST['idUsuario']);

        $strIdentificacion = strClean($_POST['txtIdentificacion']);
        $strNombre = ucwords(strClean($_POST['txtNombre']));
        $strApellido = ucwords(strClean($_POST['txtApellido']));
        $strEmail = strtolower(strClean($_POST['txtEmail']));
        $intTelefono = intval($_POST['txtTelefono']);
        $intNit = strClean($_POST['texNit']);
        $srtNombreFiscal = strClean($_POST['txtNombreFiscal']);
        $srtDirFiscal = strClean($_POST['txtDirFiscal']);
        $idTpoRol = 2; // en los clientes el rol por defecto es 2


        if ($idUser == 0) {// si el idpersona es cero null o vacio, se crea un nuevo usuario
          $option = 'nuevo';
          $strPassword = empty($_POST['txtPassword']) ? passGenerator() : $_POST['txtPassword'];
          $strPasswordEncript = hash("SHA256", $strPassword);

          $request_user = $this->model->insertCliente(
              $strIdentificacion,
              $strNombre,
              $strApellido,
              $intTelefono,
              $strEmail,
              $strPasswordEncript,
              $idTpoRol,
              $intNit,
              $srtNombreFiscal,
              $srtDirFiscal);

          $empresa = $_SESSION['info_empresa'];

          $arrDataEmail = array(// preparamos el array con los datos requeridos
            'empresa' => $empresa, // un array con los datos de la empresa, y configuracion
            'nombreUsuario' => $strNombre . ' ' . $strApellido, //nombre del usuario
            'email' => $strEmail, //email del usuario (email destino)
            'asunto' => 'Recuperar cuenta en ' . $empresa['nombre_comercial'],
            'password' => $strPassword,
          );
          $bodyMail = getFile("Template/Email/email_bienvenida", $arrDataEmail);
          $send_mail = sendEMail($arrDataEmail, $bodyMail); // ENVIAMOS EL MAIL / recibimos un 1 si es OK o un 0 se hay un error
        } else { // si $idUser es mayo a cero se actualiza el Usuario segun su id
          $option = 'actualizado';
          $strPassword = empty($_POST['txtPassword']) ? "" : hash("SHA256", $_POST['txtPassword']);
          dep(($strIdentificacion));
          dep(gettype($strIdentificacion));
          $request_user = $this->model->updateCliente(
              $idUser,
              $strIdentificacion,
              $strNombre,
              $strApellido,
              $intTelefono,
              $strEmail,
              $strPassword,
              $intNit,
              $srtNombreFiscal,
              $srtDirFiscal);
        }

        if ($request_user > 0) {// si se ejecuto el elvio de datos a la base de datos se enviara una respuesta al cliente
          if ($option == 'nuevo') { // si opcion es nuevo, quiere decir que se inserto un cliente y se
            $arrResponse = array('status' => true, 'msg' => $strNombre . ' ya es un nuevo cliente');
          } elseif ($option == 'actualizado') {
            $arrResponse = array('status' => true, 'msg' => 'los datos de ' . $strNombre . ' ha sido actualizado');
          }
        } elseif ($request_user == 'exist') {
          $arrResponse = array('status' => false, 'msg' => '!ATENCION¡ el Email o Identificacion ya existen');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'No se pudo guargar el usuario, intente mas tarde');
        }
//IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON 
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
      }
    }
    exit();
  }

  public function getClientes() {
    $arrData = $this->model->selectClientes();
    //reemplaza los valores 0 y 1 por inactivo - Activo 
    foreach ($arrData as $i => $item) {
      $id = $item['idpersona'];
      // BOTONES DE OPCIONES
      $opciones = "<div class= 'text-center'>";
      $opciones .= "<button class='btn btn-secondary m-1' onClick='fntVer({$id})' title='Ver' type='button' ><i class='fas fa-eye'></i></button>";
      $opciones .= $_SESSION['userPermiso'][$this->idModul]['actualizar'] == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar' type='button'><i class='fas fa-edit'></i></button>" : '';
      $opciones .= $item['status'] == 1 ?
          "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado'    type='button' id='btnStatus{$id}' value='1'><i class='fa fa-power-off' aria-hidden='true'></i></button>" :
          "<button class='btn btn-danger m-1 ' onClick='fntStatus({$id})' title='Desactivado' type='button' id='btnStatus{$id}' value='0'><i class='fa fa-power-off' aria-hidden='true'></i></button>";
//      if ($_SESSION['userPermiso'][$this->idModul]['eliminar'] == 1) { // si el rol esta en uso solo podra ser activado o desactivado
      $opciones .= $this->model->cteEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar' type='button'><i class='fas fa-trash-alt'></i></button>";
//      }
      $arrData[$i]['options'] = $opciones . "</div>";
      // INDICADOR DE ESTADO reemplaza los valores 0 y 1 por inactivo - Activo 
      $arrData[$i]['status'] = $item['status'] == 1 ? "<span class='badge bg-success'> Activo </span>" : "<span class='badge bg-danger'>Inactivo</span>";
    }
    exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
  }

  public function getCte(int $idpersona) {

    $id = intval($idpersona);

    if ($id > 0) {
      //buscamos los datos que correspondan a este id
      $arrData = $this->model->selectCliente($id);

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

  public function delCliente() {

    if ($_POST) {
      $intIdUser = intval($_POST['idUsuario']); //inval convierte en entero el parametro que le ingresen

      $requestDel = $this->model->deleteCliente($intIdUser);

      if ($requestDel) {
        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Usuario');
      } else {
        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Usuario');
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    exit();
  }

  public function statusRol() {

    $verdadero = intval($_GET);

    if ($verdadero) {

      $intIdRol = intval($_GET['idRol']); //inval convierte en entero el parametro que le ingresen
      $intStatus = intval($_GET['intStatus']);

      $requestStatus = $this->model->statusRol($intIdRol, $intStatus);

      if ($requestStatus == 'OK') {
        if ($intStatus == 1) {
          $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado el Rol');
        } elseif ($intStatus == 0) {
          $arrResponse = array('status' => true, 'msg' => 'Se ha Activado el Rol');
        }
      } else if ($requestDel == 'error') {
        $arrResponse = array('status' => false, 'msg' => 'No es posile desactivar el rol ');
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    exit();
  }

}
