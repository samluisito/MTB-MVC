<?php

declare(strict_types=1);

class Usuarios extends Controllers {

  private $idModul = 1;

  public function __construct() {

    if (empty($_SESSION['login'])) {
      require_once "Login.php";
      $login = new Login();
      $login->Login();
      exit();         //header('location:' . base_url() . 'login');
    }
    parent::__construct();
  }

  public function Usuarios($params) {

    $data["modulo"] = $this->idModul;

    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1) {

      //$empresa = $_SESSION['info_empresa'];
      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'Usuarios';
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
        "js/functions_usuarios.js");

      $this->views->getView("Usuarios", $data);
    } else {
      header('location:' . base_url() . 'dashboard');
      exit();
    }
  }

  public function Perfil($params) {
//ejecuta el contenido del archivo home
//echo 'Mensaje desde el controlador home';
//$views = new Views;
    $empresa = $_SESSION['info_empresa'];
    $data["empresa"] = $empresa;

    $data['page_name'] = 'Perfil';
    $data['page_title'] = 'Perfil de Usuario';
    $data['logo_desktop'] = $empresa['url_logoMenu'];
    $data['shortcut_icon'] = $empresa['url_shortcutIcon'];
    /*     * ******************************************* */
    include __DIR__ . '/../Controllers/Notificacion.php';
    $notificacion = new Notificacion();
    $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
    /*     * ******************************************* */

    // las funciones de la pagina van de ultimo 
    $data["page_css"] = array(
      "vadmin/libs/sweetalert2/sweetalert2.min.css",
      "plugins/datatables/css/datatables.min.css");

    $data["page_functions_js"] = array(
      "plugins/jquery/jquery-3.6.0.min.js",
      "vadmin/libs/sweetalert2/sweetalert2.min.js",
      "plugins/datatables/js/datatables.min.js",
      "js/functions_usuarios.js");

    $this->views->getView("Perfil", $data);
  }

  /*   * --------------------------------------------------------------------------- */

  public function setUsuario() {

    if ($_POST) {//validamos que vengan los datos post
    
      if (// valida la exitencia minima de datos
          empty($_POST['txtNombre']) || // empty($_POST['txtIdentificacion']) ||
          empty($_POST['txtApellido']) || empty($_POST['txtEmail']) ||
          empty($_POST['listRolid']) //|| // empty($_POST['txtTelefono']) ||
      //empty($_POST['listStatus']
      ) {
        $arrResponse = array("status" => false, "msg" => 'Datos incorrectos');
      } else {
        $idUser = intval($_POST['idUsuario']);

        $strIdentificacion = intval($_POST['txtIdentificacion']);
        $strNombre = ucwords(strClean($_POST['txtNombre']));
        $strApellido = ucwords(strClean($_POST['txtApellido']));
        $strEmail = strtolower(strClean($_POST['txtEmail']));
        $intTelefono = intval($_POST['txtTelefono']);
        $intTipoRolId = intval($_POST['listRolid']);
        $intStatus = intval($_POST['listStatus']);

        if ($idUser == 0) {// se crea un nuevo usuario
          $option = 'nuevo';
          $strPassword = empty($_POST['txtPassword']) ? hash("SHA256", passGenerator()) : hash("SHA256", $_POST['txtPassword']);

          $request_user = $this->model->insertUsuario(
              $strIdentificacion,
              $strNombre,
              $strApellido,
              $intTelefono,
              $strEmail,
              $strPassword,
              $intTipoRolId,
              $intStatus);
        } else { // si $idUser es mayo a cero se actualiza el Usuario segun su id
          $option = 'actualizado';
          $strPassword = empty($_POST['txtPassword']) ? "" : hash("SHA256", $_POST['txtPassword']);

          $request_user = $this->model->updateUsuario(
              $idUser,
              $strIdentificacion,
              $strNombre,
              $strApellido,
              $intTelefono,
              $strEmail,
              $strPassword,
              $intTipoRolId,
              $intStatus);
        }

        if ($request_user > 0) {
          if ($option == 'nuevo') {
            $arrResponse = array('status' => true, 'msg' => 'Datos guardador corrctamente');
          } elseif ($option == 'actualizado') {
            $arrResponse = array('status' => true, 'msg' => 'Datos guardador corrctamente');
          }
        } elseif ($request_user == 'exist') {
          $arrResponse = array('status' => false, 'msg' => '!ATENCION¡ el Email o Identificacion ya existen');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'No se pudo guargar el usuario, intente mas tarde');
        }
      }//IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON 
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function getUsuarios() {
    $arrData = $this->model->selectListUsuarios();
    foreach ($arrData as $i => $item) {
      $id = $arrData[$i]['idpersona'];
      // BOTONES DE OPCIONES
      $opciones = "<div class= 'text-center'>";
      $opciones .= "<button class='btn btn-secondary m-1' onClick='fntVer({$id})' title='Ver' type='button' ><i class='fas fa-eye'></i></button>";
      $opciones .= $_SESSION['userPermiso'][$this->idModul]['actualizar'] == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar' type='button'><i class='fas fa-edit'></i></button>" : '';
      $opciones .= $item['status'] == 1 ?
          "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado'    type='button' id='btnStatus{$id}' value='1'><i class='fa fa-power-off' aria-hidden='true'></i></button>" :
          "<button class='btn btn-danger m-1 ' onClick='fntStatus({$id})' title='Desactivado' type='button' id='btnStatus{$id}' value='0'><i class='fa fa-power-off' aria-hidden='true'></i></button>";
//      if ($_SESSION['userPermiso'][$this->idModul]['eliminar'] == 1) { // si el rol esta en uso solo podra ser activado o desactivado
      $opciones .= $this->model->usuarioEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar' type='button'><i class='fas fa-trash-alt'></i></button>";
//      }
      $arrData[$i]['options'] = $opciones . "</div>";
      // INDICADOR DE ESTADO reemplaza los valores 0 y 1 por inactivo - Activo 
      $arrData[$i]['status'] = $item['status'] == 1 ? "<span class='badge bg-success'> Activo </span>" : "<span class='badge bg-danger'>Inactivo</span>";
    }
    exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
  }

  public function getUser(int $idpersona) {
    $id = intval($idpersona);

    if ($id > 0) {
//buscamos los datos que correspondan a este id
      $arrData = $this->model->selectUser($id);

//si no devuelve ningun dato, respondemos con una array json de dato no encontrado
      if (empty($arrData)) {
        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
      } else {
        $arrResponse = array('status' => true, 'data' => $arrData);
      }

//IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON 
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function delUser() {

    if ($_POST) {
      $intIdUser = intval($_POST['idUsuario']); //inval convierte en entero el parametro que le ingresen

      $requestDel = $this->model->deleteUser($intIdUser);

      if ($requestDel) {
        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Usuario');
      } else {
        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Usuario');
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function putperfil() {
    if ($_POST) {  // VALIDAMOS LA VARIABLE METODO POST
      if (empty($_POST['txtIdentificacion']) || //validamos que los datos no vengan en vacios
          empty($_POST['txtNombre']) ||
          empty($_POST['txtApellido']) ||
          empty($_POST['txtTelefono'])
      ) {
        $arrResponse = array('status' => false, 'msg' => 'Datos incompletos');
      } else { // pasamos los datos a variables para trabajar con ellos
        $idUsusario = $_SESSION['idUser'];
        $strIdentificacion = strClean($_POST['txtIdentificacion']);
        $strNombre = strClean($_POST['txtNombre']);
        $strApellido = strClean($_POST['txtApellido']);
        $strTelefono = intval($_POST['txtTelefono']);
        $strPassword = ""; //inicamos la variable paaword vacia
        if (!empty($_POST['txtPassword'])) { // validamos qie la variable passwor traiga datos
          if ($_POST['txtPassword'] != $_POST['txtPassword']) { //si pasword y passwordConfirm son distintos se emite un mensaje ade alerta
            $arrResponse = array('status' => false, 'msg' => 'Las contraseñas no son iguales');
          } else {
            $strPassword = hash("SHA256", $_POST['txtPassword']); //de lo contrario si son iguales, se encripta el dato y se guarda enla variable correspondiente
          }
        }

        $request_user = $this->model->updatePerfil(
            $idUsusario,
            $strIdentificacion,
            $strNombre,
            $strApellido,
            $strTelefono,
            $strPassword);

        if ($request_user) {
          sessionUser($_SESSION['idUser']);
          $arrResponse = array('status' => true, 'msg' => 'Perfil actualizado correctamente');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'Error al actulizar el Perfil');
        }
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  public function putdatfiscal() {
    if ($_POST) {  // VALIDAMOS LA VARIABLE METODO POST
      if (empty($_POST['texNit']) || //validamos que los datos no vengan en vacios
          empty($_POST['txtNombreFiscal']) ||
          empty($_POST['txtDirFiscal'])) {

        $arrResponse = array('status' => false, 'msg' => 'Datos incompletos');
      } else { // pasamos los datos a variables para trabajar con ellos
        $idUsusario = $_SESSION['idUser'];
        $strNit = strClean($_POST['texNit']);
        $strNombreFiscal = strClean($_POST['txtNombreFiscal']);
        $strDirFiscal = strClean($_POST['txtDirFiscal']);

        $request_datafiscal = $this->model->updateDataFiscal(
            $idUsusario,
            $strNit,
            $strNombreFiscal,
            $strDirFiscal);

        if ($request_datafiscal) {
          sessionUser($_SESSION['idUser']);
          $arrResponse = array('status' => true, 'msg' => 'Datos Fiscales actualizado correctamente');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'Error al actulizar los daros');
        }
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    die();
  }
}
