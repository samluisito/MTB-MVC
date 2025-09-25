<?php

declare(strict_types=1);

class ABlog extends Controllers {

  private $idModul = 10;

  public function __construct() {
    if (empty($_SESSION['login'])) {
      require_once "Login.php";
      $login = new Login();
      $login->Login();
      exit();
    }
    parent::__construct();
  }

  public function ABlog($params) {
    //ejecuta el contenido del archivo home
    //echo 'Mensaje desde el controlador home';

    $data["modulo"] = $this->idModul;
    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1) {
      //$empresa = $_SESSION['info_empresa'];
      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'aBlog';
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
        "plugins/tinymce/tinymce.min.js",
        "js/functions_aBlog.js");

      $this->views->getView("ABlog", $data);
    } else {
      // header('location:' . base_url() . 'dashboard');exit();
    }
  }

  //DEVUELVE UN ARRAY CON LOS DATOS DE PRODUCTO Y BOTONES DE OPCION BOOSTRAP PARA INSERTAR EN DATATABLE
  public function getEntradas() {

    $data = $this->model->selectEntradas(); //consultamos la tabla y traemos todos los registros 
    //reemplaza los valores 0 y 1 por inactivo - Activo 

    $permiso = $_SESSION['userPermiso'];

    for ($i = 0; $i < count($data); $i++) {

      /* seleccion de imagen -------------------------------- */
      $recuest_img = $this->model->imgEntrada($data[$i]['identrada']); //consultamos la tabla y traemos todos los registros 

      if ($recuest_img['img'] != '') {
        $data[$i]['img_entrada'] = '<img class="minlistprod_img" src=" ' . DIR_IMAGEN . $recuest_img["img"] . ' "> ';
      } else {
        $data[$i]['img_entrada'] = '<img class="minlistprod_img" src=" ' . DIR_MEDIA . 'images/entrada_sin_foto.png"> ';
      }


      $data[$i]['options'] = '<div class= "text-center">';
//            if ($permiso[$this->idModul]['ver'] == 1) {//corremos los permisos para ver para midificar editat permisos del 
//                $data[$i]['options'] .= '<button class="btn btn-info btn-sm " onClick="fntVer(' . $data[$i]['identrada'] . ')" title="Ver Entrada" type="button" ><i class="fa fa-eye"></i></button>';
//            }
      if ($permiso[$this->idModul]['actualizar'] == 1) {
        $data[$i]['options'] .= '<button class="btn btn-primary btn-sm " onClick="fntEdit(' . $data[$i]['identrada'] . ')" title="Editar" type="button" ><i class="fa fa-pencil"></i></button>';
      }

      /* Botones de accion  -------------------------------- */
// cambiar entrada en uso por , entrada con likes y entrada con comentarios           
//          $this->intIdEntrada = $data[$i]['identrada'];
//          $request_entrada_en_uso = $this->model->entradaEnUso($this->intIdEntrada); // consultamos si el entrada esta en uso
      $request_entrada_en_uso = 1;

      $status = $data[$i]['status'];
      if ($request_entrada_en_uso) { // si el entrada esta en uso solo podra ser activado o desactivado
        if ($status == 1) {
          $data[$i]['options'] .= '<button class="btn btn-success btn-sm " '
              . 'onClick="fntStatus(' . $data[$i]['identrada'] . ')" title="Activado" type="button" '
              . 'id="btnStatus' . $data[$i]['identrada'] . '" value="1" >
                                    <i class="fa fa-power-off" aria-hidden="true"></i></i></button>

                        </div>';
        } elseif ($status == 0) {
          $data[$i]['options'] .= '<button class="btn btn-danger btn-sm btnStatus" '
              . 'onClick="fntStatus(' . $data[$i]['identrada'] . ')" title="Desactivado" type="button" '
              . 'id="btnStatus' . $data[$i]['identrada'] . '" value="0">
                                    <i class="fa fa-power-off" aria-hidden="true"></i></button>
                        </div>';
        }
      } else { // si el entrada no esta en uso podra ser eliminado 
        $data[$i]['options'] .= '<button class="btn btn-danger btn-sm btnDel" '
            . 'onClick="fntDel(' . $data[$i]['identrada'] . ')" title="Eliminar" type="button" ><i class="fa fa-trash"></i></button>
                        </div>';
      }
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
  }

  //CREAR - ACTUALIZAR PRODUCTO
  public function setEntrada() {
//        dep($_POST);
//        exit;
    if ($_POST) { //Validamos que no este vacio el post
      if (empty($_POST['txtTitulo']) || empty($_POST['txtDescripcion']) || empty($_POST['txtTexto'])) {
        $arrResponse = array("status" => false, "msg" => "Datos incompletos o con valor cero");
      } else {
        //recibe los datos por medio de url y devuelve un mensaje json segun su resultado
        //los datos enviados los almacenamos en variables
        $intIdEntrada = intval($_POST['idEntrada']);
        $autorid = $_SESSION['idUser'];

        $strTitulo = strClean(trim($_POST['txtTitulo']));
        $url = strtolower(clear_cadena($strTitulo));
        $url = str_replace(" ", "-", $url);

        $strDescripcion = substr(strClean($_POST['txtDescripcion']), 0, 200);
        $strTexto = strClean($_POST['txtTexto']);
        $strTags = strClean($_POST['txtTags']);

        $intCategoriaId = intval(strClean($_POST['listCategoria']));
        $intStatus = intval(strClean($_POST['listStatus']));

        if ($intIdEntrada == 0) {//validamos por medio del id si es un nuevo Entrada o si se actualiza una Entrada.
          //creamos un nuevo entrada, enviamos los datos al modelo
          $option = 1;
          $request = $this->model->insertEntrada(
              $autorid,
              $strTitulo,
              $url,
              $strDescripcion,
              $strTexto,
              $strTags,
              $intCategoriaId,
              $intStatus);
        } else { // si intIdEntrada es distinto de cero arctuelizamos un entrada
          //Actualiamos un entrada
          $option = 2;
          $request = $this->model->updateEntrada(
              $intIdEntrada,
              $strTitulo,
              $url,
              $strDescripcion,
              $strTexto,
              $strTags,
              $intCategoriaId,
              $intStatus);
        }
        // depemdiendo de la respuesta enviamos un mensaje
        if ($request > 0) {
          if ($option == 1) {
            $arrResponse = array('status' => true, 'identrada' => $request, 'msg' => 'Entrada Guardado Correctamente');
          } else {
            $arrResponse = array('status' => true, 'identrada' => $intIdEntrada, 'msg' => 'Datos Actualizados Correctamente');
          }
        } else if ($request == 'exist') {
          $arrResponse = array('status' => false, 'msg' => 'Atencion el Entrada Ya Existe, segun el codigo ingresado');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'No es posible Guardar el entrada');
        }
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    } exit();
  }

  public function getEntrada($id) {
    $intId = intval(strClean($id)); //limpiamos los datos que vienen dentro de la variable $idEntrada
    if ($intId > 0) { //si el contenido de la variable es mayor a 0 significa que hay un id a buscar
      $arrData = $this->model->selectEntrada($intId); //buscamos los datos que correspondan a este id
      if (empty($arrData)) {            //si no devuelve ningun dato, respondemos con una array json de dato no encontrado
        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
      } else {// de lo contrario, estraemos las imagenes
        $arrImg = $this->model->selectImages($intId); //buscamos los datos que correspondan a este id

        if (count($arrImg) > 0) {
          for ($index = 0; $index < count($arrImg); $index++) {
            $arrImg[$index]['url_image'] = DIR_IMAGEN . $arrImg[$index]['img'];
          }
        }
        $arrData['images'] = $arrImg;

        $arrData['prev'] = $this->model->selectEntradaPrevProx('prev', $arrData['identrada'])['identrada'];
        $arrData['prox'] = $this->model->selectEntradaPrevProx('prox', $arrData['identrada'])['identrada'];
        $arrData['posicion'] = $this->model->selectEntradaPosicion($arrData['identrada']);

        $arrResponse = array('status' => true, 'data' => $arrData);
      }
      //IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
//dep($arrData);            exit();            $arrData['fotodir'] = './Assets/images/uploads/' . $arrData["img"];

    exit();
  }

  public function setImage($param) {
    //   dep($_POST);
    // dep($_FILES);
    //exit();
    if ($_POST) {
      if ($_POST['identrada']) {
        $idEntrada = intval($_POST['identrada']);

        $data_foto = $_FILES['foto'];
        $foto_tipo = $data_foto['type'];

        $ext1 = explode('/', $foto_tipo)['1'];
        $imgTitulo = 'entrada_' . md5(date('d-m-Y H:m:s')) . '.' . $ext1;

        $request_image = $this->model->insertImage($idEntrada, $imgTitulo);

        if ($request_image) {
          $uploadImage = uploadImage($data_foto, $imgTitulo);
          $arrResponse = array('status' => true, 'imgname' => $imgTitulo, 'msg' => 'Archivo cargado');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'error al cargar la foto');
        }
      } else {
        $arrResponse = array('status' => false, 'msg' => 'Error de Carga Entrada no identificado');
      }
      sleep(1);
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }


    exit();
  }

  public function delFile() {
    if ($_POST) {
      if (empty($_POST['identrada']) || empty($_POST['file'])) {
        $arrResponse = array('status' => false, 'msg' => 'datos incorectos / incompletos');
      } else {
        // Eliminar de BD
        $idEntrada = intval($_POST['identrada']);
        $imgTitulo = strClean($_POST['file']);

        $request = $this->model->deleteImage($idEntrada, $imgTitulo);

        if ($request) {
          $deleteFile = deleteFile($imgTitulo);
          $arrResponse = array('status' => true, 'msg' => 'Archivo Eliminado');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el archivo');
        }
      }
    }echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  public function delEntrada() {

    if ($_POST) {
      $intIdEntrada = intval($_POST['identrada']); //inval convierte en entero el parametro que le ingresen

      $requestDel = $this->model->deleteEntrada($intIdEntrada);

      if ($requestDel == 'OK') {
        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Entrada');
      } else if ($requestDel == 'exist') {
        $arrResponse = array('status' => false, 'msg' => 'No es posile eliminar el entrada');
      } else {
        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el entrada');
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    exit();
  }

  /* ---------------------------------------------------------------------------------------


    dep($_POST);
    exit();




    public function getFoto($param) {
    //  $arrData['foto'] = file_get_contents('./Assets/images/uploads/' . $arrData["img"]);
    $arrData = file_get_contents("./Assets/images/uploads/imgcategoria0a26dd8007a94cabce14753bb1704fc5.jpg");

    return $arrData;

    }






    public function getSelectEntradasTipo() {
    //Realiza una consulta a la tabla entradaes y devuelve una lista html ID Titulo, para developer.snapappointments.com
    $htmlOption = "";
    $arrData = $this->model->selectEntradas();
    if (count($arrData) > 0) {
    for ($index = 0; $index < count($arrData); $index++) { //repasamos la lista y creamos un array html con el valor y el nombre
    if ($arrData[$index]['status'] == 1) { //si el status es 1 creamos un array html con el id como valor y el nombre
    $htmlOption .= '<option value ="' . $arrData[$index]['identrada'] . '">' . $arrData[$index]['nombreentrada'] . '</option>';
    }
    }
    }
    echo $htmlOption;
    exit();
    }

    public function statusEntrada() {

    $verdadero = intval($_GET);


    if ($verdadero) {

    $intIdEntrada = intval($_GET['idEntrada']); //inval convierte en entero el parametro que le ingresen
    $intStatus = intval($_GET['intStatus']);



    $requestStatus = $this->model->statusEntrada($intIdEntrada, $intStatus);

    if ($requestStatus == 'OK') {
    if ($intStatus == 1) {
    $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado el Entrada');
    } elseif ($intStatus == 0) {
    $arrResponse = array('status' => true, 'msg' => 'Se ha Activado el Entrada');
    }
    } else if ($requestDel == 'error') {
    $arrResponse = array('status' => false, 'msg' => 'No es posile desactivar el entrada');
    }
    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    exit();
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
    if ($valor == 1) {
    $checked = "checked";
    } else if ($valor == 0) {
    $checked = "";
    }
    return $checked;
    }

    public function getPermisos() {

    $idEntrada = intval($_GET['identrada']); //capturamos el parametro dado
    if ($idEntrada > 0) { //si el parametro es mayor a 0, hacemos la consulta
    $data = $this->model->selectModulos($idEntrada);

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


    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else { // si el id pasado es 0 o null emitimos el error falta parametro
    echo "falta parametro";
    }




    exit();
    }
   */
}

//dep($data[0]['status']);        
//<span class="badge badge-success">Success</span>

    