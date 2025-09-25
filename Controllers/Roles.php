<?php

declare(strict_types=1);

class Roles extends Controllers {

  private $idModul = 2;

  public function __construct() {

    if (empty($_SESSION['login'])) {
      require_once "Login.php";
      $login = new Login();
      $login->Login();
      exit();         //header('location:' . base_url() . 'login');
    }
    parent::__construct();
  }

  public function Roles($params) {
    //ejecuta el contenido del archivo home
    //echo 'Mensaje desde el controlador home';

    $data["modulo"] = $this->idModul;
    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1) {
      //$empresa = $_SESSION['info_empresa'];
      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'Rol_Usuario';
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
        "js/functions_roles.js");
      $this->views->getView("Roles", $data);
    } else {
      header('location:' . base_url() . 'dashboard');
      exit();
    }
  }

  //DEVUELVE UN ARRAY CON LOS DATOS DE ROLES Y BOTONES DE OPCION BOOSTRAP PARA INSERTAR EN DATATABLE
  public function getRoles() {
    $arrData = $this->model->selectRoles();

    foreach ($arrData as $i => $item) {
      $id = $item['idrol'];
      // BOTONES DE OPCIONES
      $opciones = "<div class= 'text-center'>";
      $opciones .= "<button class='btn btn-secondary m-1' onClick='fntPermisos({$id})' title='Ver' type='button' ><i class='fas fa-eye'></i></button>";
      $opciones .= $_SESSION['userPermiso'][$this->idModul]['actualizar'] == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar' type='button'><i class='fas fa-edit'></i></button>" : '';
      $opciones .= $item['status'] == 1 ?
          "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado'    type='button' id='btnStatus{$id}' value='1'><i class='fa fa-power-off' aria-hidden='true'></i></button>" :
          "<button class='btn btn-danger m-1 ' onClick='fntStatus({$id})' title='Desactivado' type='button' id='btnStatus{$id}' value='0'><i class='fa fa-power-off' aria-hidden='true'></i></button>";
//      if ($_SESSION['userPermiso'][$this->idModul]['eliminar'] == 1) { // si el rol esta en uso solo podra ser activado o desactivado
      $opciones .= $this->model->rolEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar' type='button'><i class='fas fa-trash-alt'></i></button>";
//      }
      $arrData[$i]['options'] = $opciones . "</div>";
      // INDICADOR DE ESTADO reemplaza los valores 0 y 1 por inactivo - Activo 
      $arrData[$i]['status'] = $item['status'] == 1 ? "<span class='badge bg-success'> Activo </span>" : "<span class='badge bg-danger'>Inactivo</span>";
      ;
    }
    exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
  }

  //RETORNA UN JSON CON LOS DATOS DEL ROL
  public function getRol(int $idRol) {
    //limpiamos los datos que vienen dentro de la variable $idRol

    $intIdRol = intval($idRol);

    //si el contenido de la variable es mayor a 0 significa que hay un id a buscar
    if ($intIdRol > 0) {
      //buscamos los datos que correspondan a este id
      $arrData = $this->model->selectRol($intIdRol);

      //si no devuelve ningun dato, respondemos con una array json de dato no encontrado
      if (empty($arrData)) {
        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
      } else {
        $arrResponse = array('status' => true, 'data' => $arrData,);
      }
      //IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON 
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    exit();
  }

  //CREAR - ACTUALIZAR ROL
  public function setRol() {
    //recibe los datos por medio de url y devuelve un mensaje json segun su resultado
    //los datos enviados los almacenamos en variables
    $intIdRol = intval($_POST['idRol']);
    $strRol = strClean($_POST['txtNombre']);
    $strDescripcion = strClean($_POST['txtDescripcion']);
    $intStatus = intval($_POST['listStatus']);

    //validamos por medio del id si es un nuevo rol o si se actualiza un rol.
    if ($intIdRol == 0) {

      //creamos un nuevo rol, enviamos los datos al modelo
      $request_rol = $this->model->insertRol($strRol, $strDescripcion, $intStatus);
      $option = 1;
    } else {
      //Actualiamos un rol
      $request_rol = $this->model->updateRol($intIdRol, $strRol, $strDescripcion, $intStatus);
      $option = 2;
    }

    // depemdiendo de la respuesta enviamos un mensaje 
    if ($request_rol > 0) {
      if ($option == 1) {
        $arrResponse = array('status' => true, 'msg' => 'Datos Guardados Correctamente');
      } else {
        $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados Correctamente');
      }
    } else if ($request_rol == 'exist') {
      $arrResponse = array('status' => false, 'msg' => 'Atencion El Rol Ya Existe');
    } else {
      $arrResponse = array('status' => false, 'msg' => 'No es posible Guardar el rol');
    }
    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    exit();
  }

  public function delRol() {

    if ($_POST) {
      $intIdRol = intval($_POST['idrol']); //inval convierte en entero el parametro que le ingresen

      $requestDel = $this->model->deleteRol($intIdRol);

      if ($requestDel == 'OK') {
        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Rol');
      } else if ($requestDel == 'exist') {
        $arrResponse = array('status' => false, 'msg' => 'No es posile eliminar el rol');
      } else {
        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el rol');
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    exit();
  }

  public function getSelectRolesTipo() {
    //Realiza una consulta a la tabla roles y devuelve una lista html ID Nombre, para developer.snapappointments.com
    $htmlOption = "";
    $arrData = $this->model->selectRoles();
    if (count($arrData) > 0) {
      for ($index = 0; $index < count($arrData); $index++) { //repasamos la lista y creamos un array html con el valor y el nombre 
        if ($arrData[$index]['status'] == 1) { //si el status es 1 creamos un array html con el id como valor y el nombre 
          $htmlOption .= '<option value ="' . $arrData[$index]['idrol'] . '">' . $arrData[$index]['nombrerol'] . '</option>';
        }
      }
    }
    echo $htmlOption;
    exit();
  }

  public function statusRol() {
    $verdadero = intval($_GET);
    if ($verdadero) {
      $intIdRol = intval($_GET['idRol']); //inval convierte en entero el parametro que le ingresen
      $intStatus = intval($_GET['intStatus']);
      $requestStatus = $this->model->editStatus($intIdRol, $intStatus);
      if ($requestStatus == 'OK') {
        $arrResponse = $intStatus === 1 ?
            $arrResponse = array('status' => true, 'msg' => 'Se ha Desactivado el item') :
            $arrResponse = array('status' => true, 'msg' => 'Se ha Activado el item');
      } else if ($requestStatus == 'error') {
        $arrResponse = array('status' => false, 'msg' => 'No es posile desactivar el item');
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
  }

  public function setPermiso() {
    $intIdPerm = intval($_POST['idPermiso']);
    $strTpoPerm = strClean($_POST['tpoPermiso']);
    $intEstado = intval($_POST['estado']);
    $request_estado = $this->model->editPermiso($intIdPerm, $strTpoPerm, $intEstado);

    if ($request_estado) {
      $arrResponse = array('status' => true, 'value' => $intEstado);
    } else {
      $arrResponse = array('status' => false, 'msg' => 'No es posile editar el pemiso');
    }

    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  private function checked($valor) {
    return $valor == 1 ? "checked" : $checked = "";
  }

  public function getPermisos() {

    $idRol = intval($_GET['idrol']); //capturamos el parametro dado
    if ($idRol > 0) { //si el parametro es mayor a 0, hacemos la consulta 
      $data = $this->model->selectModulos($idRol);

      for ($i = 0; $i < count($data); $i++) { //Repasamos el array y cambiamos los 1 y 0 , botones 
        //$status = $data[$i]['ver']; 
        $ver = "'ver'";
        $data[$i]['ver'] = '<div class="toggle-flip">
                                <label><input id="ver' . $data[$i]['idmodulo'] . '" name="" type="checkbox" ' . $this->checked($data[$i]['ver']) . ' value="' . $data[$i]['ver'] . '" onClick="fntActionPermiso(' . $ver . ' ,' . $data[$i]['idmodulo'] . ')"><span class="flip-indecator" data-toggle-on="SI" data-toggle-off="NO" ></span></label></div>';
        $crear = "'crear'";
        $data[$i]['crear'] = '<div class="toggle-flip">
                                <label><input id="crear' . $data[$i]['idmodulo'] . '" name="" type="checkbox" ' . $this->checked($data[$i]['crear']) . ' value="' . $data[$i]['crear'] . '"  onClick="fntActionPermiso(' . $crear . ' ,' . $data[$i]['idmodulo'] . ')"><span class="flip-indecator" data-toggle-on="SI" data-toggle-off="NO" ></span></label></div>';
        $actualizar = "'actualizar'";
        $data[$i]['actualizar'] = '<div class="toggle-flip">
                                <label><input id="actualizar' . $data[$i]['idmodulo'] . '" name="" type="checkbox" ' . $this->checked($data[$i]['actualizar']) . ' value="' . $data[$i]['actualizar'] . '"onClick="fntActionPermiso(' . $actualizar . ' ,' . $data[$i]['idmodulo'] . ')"><span class="flip-indecator" data-toggle-on="SI" data-toggle-off="NO" ></span></label></div>';
        $eliminar = "'eliminar'";
        $data[$i]['eliminar'] = '<div class="toggle-flip">
                                <label><input id="eliminar' . $data[$i]['idmodulo'] . '" name="" type="checkbox" ' . $this->checked($data[$i]['eliminar']) . ' value="' . $data[$i]['eliminar'] . '" onClick="fntActionPermiso(' . $eliminar . ' ,' . $data[$i]['idmodulo'] . ')"><span class="flip-indecator" data-toggle-on="SI" data-toggle-off="NO" ></span></label></div>';
      }

      /* <h5>Disabled state</h5>
        <div class="toggle-flip">
        <label>
        <input type="checkbox" disabled=""><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
        </label>
        </div>
       */
      echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else { // si el id pasado es 0 o null emitimos el error falta parametro
      echo "falta parametro";
    }
    exit();
  }

}
