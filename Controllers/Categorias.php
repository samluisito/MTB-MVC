<?php

//declare(strict_types=1);

class Categorias extends Controllers {

  private $idModul = 4;

  function __construct() {
    if (empty($_SESSION['login'])) {
      require_once "Login.php";
      $login = new Login();
      $login->Login();
      exit();         //header('location:' . base_url() . 'login');
    }
    parent::__construct();
  }

  function Categorias() {
    $data["modulo"] = $this->idModul;
    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1) {
      //$empresa = $_SESSION['info_empresa'];
      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'Categorias de Productos';
      $data['page_title'] = $data['page_name'];
      $data['logo_desktop'] = $empresa['url_logoMenu'];
      $data['shortcut_icon'] = $empresa['url_shortcutIcon'];
      /*       * ******************************************* */
      include __DIR__ . '/../Controllers/Notificacion.php';
      $notificacion = new Notificacion();
      $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
      /*       * ******************************************* */


      $data['categorias_facebook_n1'] = $this->getArrayCatFbGgNivel('facebook');
      $data['categorias_google_n1'] = $this->getArrayCatFbGgNivel('google'); // las funciones de la pagina van de ultimo 
      $data["page_css"] = array(
        "plugins/datatables/css/datatables.min.css",
        "plugins/cropper/css/cropper.min.css");

      $data["page_functions_js"] = array(
        "plugins/jquery/jquery-3.6.0.min.js",
        "plugins/datatables/js/datatables.min.js",
        "plugins/cropper/js/cropper.min.js",
        "js/functions_categorias.js");

      $this->views->getView("Categorias", $data);
    } else {
      header('location:' . base_url() . 'dashboard');
    }
  }

  /* ------------------------------------------------------------------------------------------------------------------------------- */

  //CREAR - ACTUALIZAR 
  function setCategoria() {
    !empty($_POST) ? '' : exit(array("status" => false, "msg" => "Datos incompletos"));  //Validamos que no este vacio el post
    if (empty($_POST['txtNombre']) || empty($_POST['txtDescripcion'])) {  // validamos que los datos no esten vacios 
      $arrResponse = array("status" => false, "msg" => "Datos incompletos");  // si uno de los datos esta vacio. entonces se devuelve un mensaje 
    } else {
      //recibe los datos por medio de url y devuelve un mensaje json segun su resultado
      //los datos enviados los almacenamos en variables
      $idUnico = uniqid();
      $intIdCat = intval($_POST['idCategoria']) ?: 0;
      $intIdCatPadre = intval($_POST['idCatPadre']) ? intval($_POST['idCatPadre']) : null;
      $strCat = strClean($_POST['txtNombre']);
      $strDescripcion = strClean($_POST['txtDescripcion']);
      $strTags = strClean($_POST['txtTags']); //

      $intCatFb = intval($_POST['listCatFB']); //
      $intCatGg = intval($_POST['listCatGoogle']); //

      $intStatus = intval($_POST['listStatus']); //
      // pasamos a variables los datos de la foto 
      $foto_actual = $_POST['foto_actual'];
      $foto_remove = intval($_POST['foto_remove']);
      $nombre_foto = $_POST['foto_blob_name'] === '' ? '' : $_POST['foto_blob_name']; // $foto['name']; // pasamos a variable el nombre 
      $type = $_POST['foto_blob_type'] === '' ? '' : explode('/', strClean($_POST['foto_blob_type']))[1]; //el tipo de archivo // $foto['type']
      $type = $type === 'jpeg' ? 'jpg' : $type; //

      $foto = $_FILES['foto']; //
//      $url_temp = $foto['tmp_name']; //la url temporal donde se sube la foto al server

      $img_portada = "portada_categoria.png"; //

      $ruta = str_replace(" ", "-", strtolower(clear_cadena($strCat)));
      /* ESTADO DE FOTOS---------------------------------- */
      $estado_foto = estadoFoto($nombre_foto, $foto_actual, $foto_remove);

      /* ESTADO DE IMG PORTADA ----------------------------- */
      if ($estado_foto === 'nueva') {
        $img_portada = 'img-' . $ruta . '-' . $idUnico . '.' . $type; // le generamos un nombre aleatorio a la imagen
      }
      if ($estado_foto === 'sin_mov') {
        $img_portada = $foto_actual; // le generamos un nombre aleatorio a la imagen
      }
      if ($estado_foto === 'sin_mov_def') {
        $img_portada = 'portada_categoria.png'; // le generamos un nombre aleatorio a la imagen
      }
      if ($estado_foto === 'actualizada') {
        $img_portada = 'img-' . $ruta . '-' . $idUnico . '.' . $type; // le generamos un nombre aleatorio a la imagen
      }
      if ($estado_foto === 'eliminada') {
        $img_portada = 'portada_categoria.png';
      }
      /* hacemos las minuaturas */
      // nueva categoria 

      if ($intIdCat === 0) {//validamos por medio del id si es un nuevo Categoria o si se actualiza una Categoria.
        //creamos una una categoria, enviamos los datos al modelo
        $request_cat = $this->model->insertCategoria($intIdCatPadre, $strCat, $strDescripcion, $intCatFb, $intCatGg, $img_portada, $strTags, $ruta, $intStatus);

        $idcat = $request_cat;
        $option = 'new';
        //ACTUALIZAR CATEGORIA 
      } else { // si intIdCat es distinto de cero arctuelizamos un categoria
        //Actualiamos una categoria, enviam,os los datos al modelo
        $request_cat = $this->model->updateCategoria($intIdCat, $intIdCatPadre, $strCat, $strDescripcion, $intCatFb, $intCatGg, $img_portada, $strTags, $ruta, $intStatus);

        $idcat = $intIdCat;
        $option = 'update';
      }
      // depemdiendo de la respuesta enviamos un mensaje 
      if ($request_cat === 'e') {
        $arrResponse = array('status' => false, 'msg' => 'Atencion la Categoria Ya Existe');
      } elseif ($request_cat > 0) {
        if ($option === 'new') {
          if ($estado_foto === 'nueva') { // si esta variable foto es diferente de vacio
            $uploadImage = uploadImage($foto, $img_portada); // movemos el archivo del temporal a la carpeta image/upload
          }
          //$categoria=$intIdCatPadre >0?$this->model->selectCat($intId):'';
          $arrResponse = array('status' => true, 'id' => $idcat, 'nombre' => $strCat, 'msg' => "Se a creado la categoria $strCat");
        } elseif ($option === 'update') {
          if ($estado_foto === 'nueva' || $estado_foto === 'actualizada') { //movemos la imagen del 
            uploadImage($foto, $img_portada); // movemos el archivo del temporal a la carpeta image/upload
          }
          if ($estado_foto === 'eliminada' || $estado_foto === 'actualizada') { //indica que estamos subiendo una imagen que no es la imagen por defecto
            if ($foto_actual != 'portada_categoria.png') {
              deleteFile($foto_actual);
              $imgNombre2 = 'thumb_1' . $foto_actual;
              $deleteFile = deleteFile($imgNombre2);
              $imgNombre2 = 'thumb_2_' . $foto_actual;
              $deleteFile = deleteFile($imgNombre2);
              $imgNombre2 = 'thumb_3_' . $foto_actual;
              $deleteFile = deleteFile($imgNombre2);
            }
          }
          $arrResponse = array('status' => true, 'id' => $idcat, 'nombre' => $strCat, 'msg' => "Categoria $strCat Actualizado");
        }

        if ($estado_foto === 'nueva' || $estado_foto === 'actualizada') {
          $img_orig = './uploads/' . FILE_SISTEM_CLIENTE . '/' . $img_portada;
          thumbImage($img_orig, '1_' . $img_portada, 720, 460);
          thumbImage($img_orig, '2_' . $img_portada, 432, 276);
          thumbImage($img_orig, '3_' . $img_portada, 144, 92);
        }
      } else {
        $arrResponse = array('status' => false, 'msg' => 'No es posible Guardar la categoria');
      }
    }
    exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
  }

  function recreaThumb() {
    $requestLista = $this->model->listarImagenesCategorias();

    foreach ($requestLista as $image) {
      $img_orig = './uploads/' . FILE_SISTEM_CLIENTE . '/' . $image['img'];
      thumbImage($img_orig, '1_' . $image['img'], 720, 460);
      thumbImage($img_orig, '2_' . $image['img'], 432, 276);
      thumbImage($img_orig, '3_' . $image['img'], 144, 92);
      dep($img_orig);
    }
  }

  //DEVUELVE UN ARRAY CON LOS DATOS DE CATEGORIAS Y BOTONES DE OPCION BOOSTRAP PARA INSERTAR EN DATATABLE
  function getCategorias() {
    $arrData = $this->model->selectCategorias(); //consultamos la tabla y traemos todos los registros 
    foreach ($arrData as $i => $item) {
      $arrData[$i]['img'] = ($item["img"] != 'portada_categoria.png') ?
          '<img class="minlistprod_img" src=" ' . DIR_IMAGEN . 'thumb_3_' . $item["img"] . ' "> ' :
          '<img class="minlistprod_img" src=" ' . DIR_MEDIA . 'images/portada_categoria.png"> ';
      $id = $arrData[$i]['idcategoria'];
      // BOTONES DE OPCIONES
      $opciones = "<div class= 'text-center'>";
      $opciones .= "<button class='btn btn-secondary m-1' onClick='fntVer({$id})' title='Ver' type='button' ><i class='fas fa-eye'></i></button>";
      $opciones .= $_SESSION['userPermiso'][$this->idModul]['actualizar'] == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar' type='button'><i class='fas fa-edit'></i></button>" : '';
      $opciones .= $item['status'] == 1 ?
          "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado'    type='button' id='btnStatus{$id}' value='1'><i class='fa fa-power-off' aria-hidden='true'></i></button>" :
          "<button class='btn btn-danger m-1 ' onClick='fntStatus({$id})' title='Desactivado' type='button' id='btnStatus{$id}' value='0'><i class='fa fa-power-off' aria-hidden='true'></i></button>";
      $opciones .= $this->model->categoriaEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar' type='button'><i class='fas fa-trash-alt'></i></button>";
      $arrData[$i]['options'] = $opciones . "</div>";
      // INDICADOR DE ESTADO reemplaza los valores 0 y 1 por inactivo - Activo 
      $arrData[$i]['status'] = $item['status'] == 1 ? "<span class='badge bg-success'> Activo </span>" : "<span class='badge bg-danger'>Inactivo</span>";
    }
    exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
  }

  function get(int $id) {
    //limpiamos los datos que vienen dentro de la variable $id
    $intId = intval($id);
    //si el contenido de la variable es mayor a 0 significa que hay un id a buscar
    if ($intId > 0) {
      //buscamos los datos que correspondan a este id
      $arrData = $this->model->selectCat($intId);
      //si no devuelve ningun dato, respondemos con una array json de dato no encontrado
      if (empty($arrData)) {
        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
      } else {
        //agregamos u nuevo elemento en el array con la ruta completa de la imagen 
        if ($arrData['img'] === 'portada_categoria.png') {
          $arrData['url_img'] = DIR_MEDIA . 'images/' . $arrData['img'];
        } else {
          $arrData['url_img'] = DIR_IMAGEN . $arrData['img'];
        }
        // para el paginado de items hacemos 3 consultas
        $arrData['prev'] = $this->model->selectCatPrevProx('prev', $arrData['idcategoria'])['idcategoria'];
        $arrData['prox'] = $this->model->selectCatPrevProx('prox', $arrData['idcategoria'])['idcategoria'];
        $arrData['posicion'] = $this->model->selectCatPosicion($arrData['idcategoria']);
      }
      $arrData['subCategorias'] = $this->model->selectAllSubCat($intId);

      $arrResponse = array('status' => true, 'data' => $arrData,);
      //IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON 
      //dep($arrData);         exit();
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    exit();
  }

  function delCategoria() {
    empty($_POST) ? exit(json_encode(array('status' => false, 'msg' => 'no hay datos'), JSON_UNESCAPED_UNICODE)) : '';
    $intId = intval($_POST['id']);
    $imagen = $this->model->selectImgCategoria($intId);
    $img_del = 1;
    if ($imagen) {
      if ($imagen !== 'portada_categoria.png') {
        $del1 = deleteFile('thumb_1_' . $imagen);
        $del2 = deleteFile($imagen);
        $img_del = $del1 === 1 && $del2 === 1 ? 1 : 0;
      }
    }//array('status' => true, 'msg' => 'Se ha eliminado el Producto'),
    if ($img_del) {
      $arrResponse = $this->model->deleteCategoria($intId) ?
          array('status' => true, 'msg' => 'Proveedor Exterminado') :
          array('status' => false, 'msg' => 'Error al borrar Proveedor');
    } else {
      $arrResponse = array('status' => false, 'msg' => 'Error al borrar imagenes');
    }
    exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE)); //inval convierte en entero el parametro que le ingresen
  }

  function getSelectCategorias() {
//Realiza una consulta a la tabla cates y devuelve una lista html ID Nombre, para developer.snapappointments.com
    $htmlOption = "";
    $arrData = $this->model->selectCategorias();
    if (count($arrData) > 0) {
      foreach ($arrData as $cat) {//si el status es 1 creamos un array html con el id como valor y el nombre 
        $htmlOption .= $cat['status'] == 1 ? "<option value ='{$cat['idcategoria']}'>{$cat['nombre']}</option>" : '';
      }
    }
    exit($htmlOption);
  }

  function getSelectCategoriasChoise() {
//Realiza una consulta a la tabla cates y devuelve una lista html ID Nombre, para developer.snapappointments.com
    $arrResponse = array();
    $arrCats = $this->model->selectCategorias();
    if (count($arrCats) > 0) {
      foreach ($arrCats as $cat) {//si el status es 1 creamos un array html con el id como valor y el nombre 
        //agregamos a la respuesta la categoria actual
        array_push($arrResponse, array('value' => $cat['idcategoria'], 'label' => strtoupper($cat['nombre']), 'disabled' => $cat['status'] == 0 ? true : false));
        $arrSubCats = $this->model->selectCategorias($cat['idcategoria']); //consultamo si hay sub categorias para esta idcategoria
        if (is_array($arrSubCats) && isset($arrSubCats[0])) { //Si recibe una matriz vacía, simplemente no se ejecutará
          foreach ($arrSubCats as $subCat) {//Recorremos un ciclo para ordenar id, nombre y estado antes de agregar a la rerspuesta 
            array_push($arrResponse, array('value' => $subCat['idcategoria'],
              'label' => '- ' . ucwords($subCat['nombre']),
              'disabled' => $subCat['status'] == 0 ? true : false));
          }
        }
      }
    }
    echo (json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
  }

  function statusCategoriaChange() {
    if (isset($_GET['id']) && isset($_GET['intStatus'])) {
      $intId = intval($_GET['id']);
      $intStatus = intval($_GET['intStatus']);
      $requestStatus = $this->model->editCategoriaStatus($intId, $intStatus);
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

  function setCategoriasGoogleFacebookXls() {
    //exit('metodo usado');
//    $destino = 'facebook';
    $destino = 'google';
    if ($destino === 'google') {
      $inputFileName = './uploads/google-taxonomy-with-ids.es-ES.xls';
    } else if ($destino === 'facebook') {
//      $inputFileName = './uploads/prueba_fb_product_categories_es_LA.xlsx';
      $inputFileName = './uploads/fb_product_categories_es_LA.xlsx';
    } else {
      exit('Falta parametro de destino');
    }
    $arrData = convertirXlsCSvEnArray($inputFileName);

    $arrDataLimpia = array();
    $nivel_cat = 0;

    foreach ($arrData as $categoria) {
      if (intval($categoria[0]) > 0) {
        $cat = array();
        foreach ($categoria as $k => $value) {
          if ($value != null && $value != '') {
            array_push($cat, strClean($value));
            $nivel_cat = $k > $nivel_cat ? $k : $nivel_cat;
          }
        }
        array_push($arrDataLimpia, $cat);
      }
    }
    $nivel_cat = $nivel_cat + 1;
    dep("niveles $nivel_cat");

    for ($index1 = 0; $index1 < $nivel_cat; $index1++) {
      foreach ($arrDataLimpia as $key => $fila_col) {//repasamos la lista
        $c = count($fila_col) - 1;
        if ($c == $index1) {
          $id_cat = intval($fila_col[0]);
          /* Seteamos las variables a insertar */
          $arr_max_pos = count($fila_col) - 1;
          $nombre_cat = strClean($fila_col[$arr_max_pos]);
          $id_padre = null;
          /* comprobamos que item categoria no exista */
          if ($destino === 'google') {
            $exist = $this->model->getIdCategoriaGooglePorId($id_cat);
          } else if ($destino === 'facebook') {
            $exist = $this->model->getIdCategoriaFacebookPorId($id_cat);
          }

          if (!$exist) {

            if ($arr_max_pos == 0) {//si la maxima posicion del item en el array es 0 entonces es una categoria padre
              if ($destino === 'google') {
                $this->model->insertar_categoria_google($id_cat, $nombre_cat);
              } else if ($destino === 'facebook') {
                $ret = $this->model->insertar_categoria_Facebook($id_cat, $nombre_cat);
              }
            } else {//si la maxima posicion del item en el array es distinto de 0 entonces es una categoria hija
              if ($destino === 'google') {
                $nombre_abue = $index1 > 2 ? $fila_col[$arr_max_pos - 2] : null;
                $nombre_padre = $fila_col[$arr_max_pos - 1];
                $id_padre = $this->model->getIdCategoriaGooglePorNombre($nombre_padre, $nombre_abue);
                $this->model->insertar_categoria_google($id_cat, $nombre_cat, $id_padre);
              } else if ($destino === 'facebook') {
                $nombre_abue = $index1 > 2 ? $fila_col[$arr_max_pos - 2] : null;
                $nombre_padre = $fila_col[$arr_max_pos - 1];
                $id_padre = $this->model->getIdCategoriaFacebookPorNombre($nombre_padre, $nombre_abue);
                $ret = $this->model->insertar_categoria_Facebook($id_cat, $nombre_cat, $id_padre);
              }
            }
            dep("Nivel $index1 / Insertado = $id_cat, $nombre_cat, $id_padre");
          } else {
            dep("Nivel $index1 / Existe = $id_cat, $nombre_cat, $id_padre");
          }
        }
      }
    }
  }

  /* =============================================================================================== */

  function getCateFb_paraProductos($param) {
    //recibe un parametro tipo debe de ser facebook o google
    //id categoria o prefijo subnivel puede ser null
    //retorna un html option para un elemento select
    $param = (explode(',', $param));

    $idCat = isset($param[0]) ? intval($param[0]) : null;
    if (!$idCat) {
      exit('Falta parametro de destino');
    } else {
      $cat = $this->model->selectCat($idCat);
      $id_fb = intval($cat['cat_facebook_id']);
      $id_gg = intval($cat['cat_google_id']);
      $return = array('facebook' => '', 'google' => '');

      $catN0 = $this->model->getCatFbGgID('facebook', $id_fb);
      $return['facebook'] .= "<option value ='{$catN0['id_fb']}'>" . strtoupper($catN0['nombre']) . "</option>";
      $return['facebook'] .= $this->recreaSelect('facebook', $id_fb, '-')['html'] ?? '';

      $catN0 = $this->model->getCatFbGgID('google', $id_gg);
      $return['google'] .= "<option value ='{$catN0['id_cat_gg']}'>" . strtoupper($catN0['nombre']) . "</option>";
      $return['google'] .= $this->recreaSelect('google', $id_gg, '-')['html'] ?? '';

      echo json_encode($return, JSON_UNESCAPED_UNICODE);
    }
  }

  /* ------------------------------------------------------ */

  function getSubCategoriasFb($param) {
    //recibe un parametro tipo debe de ser facebook o google
    //id categoria o prefijo subnivel puede ser null
    //retorna un html option para un elemento select
    $param = (explode(',', $param));
    $tipo = $param[0];
    $idCat = isset($param[1]) ? $param[1] : null;
    if (!($tipo === 'google' || $tipo === 'facebook')) {
      exit('Falta parametro de destino');
    } else {
      $data = $this->recreaSelect($tipo, $idCat);
      dep($data['html']); //
    }
  }

  /* ------------------------------------------------------ */

  private function recreaSelect($tipo, $idCat = null, $prefijoNivel = '') {
    //recibe un parametro tipo debe de ser facebook o google
    //id categoria o prefijo subnivel puede ser null
    //retorna un html option para un elemento select
    $id = $tipo === 'google' ? 'id_cat_gg' : 'id_fb';
    $cats = $this->model->getCatFacebookNivelpp($tipo, $idCat);
    $cuenta = count($cats);
    if ($cuenta) {
      $return = array('data' => array(), 'html' => '');
      foreach ($cats as $cat) {
        array_push($return['data'], $cat);
        $nombre = $prefijoNivel == '' ? strtoupper($cat['nombre']) : ucwords($cat['nombre']);
        $return['html'] .= "<option value ='{$cat[$id]}'>$prefijoNivel{$nombre}</option>";
        $data = $this->recreaSelectCallBack($tipo, $cat[$id], $prefijoNivel);
        if ($data) {
          $return['html'] .= $data['html'];
          array_push($return['data'], $data);
        }
      }
      return $return;
    } else {
      $return = null;
    }
  }

  private function recreaSelectCallBack($tipo, $idCat, $prefijoNivel) {
    $prefijoNivel = $prefijoNivel . '-';
    return $this->recreaSelect($tipo, $idCat, $prefijoNivel);
  }

  /* -------------------------------------------------------------------------- */

  function getCategoriasRS($param) {
    //devuelve un listado de categorias segun el id padre, y el tipo de categoria facebook o google
    //si esta seteado el id activo devuelve la opcopn cmo select
    $param = (explode(',', $param));
    $tipo = $param[0];
    $idCat = isset($param[1]) ? $param[1] : null;
    $select = isset($_GET['activo']) && $_GET['activo'] > 0 ? $_GET['activo'] : null;

    if ($tipo === 'facebook') {
      $arrData = $this->getArrayCatFbGgNivel('facebook', $idCat);
      dep($arrData);
      $response = $this->recreaSlectCategoriasRS($arrData, 'facebook', $select);
    } else if ($tipo === 'google') {
      $arrData = $this->getArrayCatFbGgNivel('google', $idCat);
      $response = $this->recreaSlectCategoriasRS($arrData, 'google', $select);
    } else {
      exit('Falta parametro de destino');
    }
    echo $response;
  }

  private function recreaSlectCategoriasRS($array, $tipo, $select) {
    $id = $tipo === 'google' ? 'id_cat_gg' : 'id_fb';
    $return = '';
    foreach ($array as $cat) {
      if (isset($cat[$id])) {
        $selected = $cat[$id] == $select ? 'selected' : '';
        $return .= "<option value ='{$cat[$id]}' $selected>{$cat['nombre']}</option>";
      }
    }
    return $return;
  }

  function getCategoriasFBGGProductos($param) {
//devuelve solo 2 niveles de la liata de categorias
    $idCat = intval($param);
    $cat = $this->model->selectCat($idCat);

    $id_fb = intval($cat['cat_facebook_id']);
    $id_gg = intval($cat['cat_google_id']);

    $return = array(
      'facebook' => '',
      'google' => ''
    );
    $arrData = array($this->model->getCatFbGgID("facebook", $id_fb));
    $return['facebook'] .= $this->recreaSlectCategoriasRS($arrData, 'facebook', 0);

    $arrData = $this->getArrayCatFbGgNivel('facebook', $id_fb);
    $return['facebook'] .= $this->recreaSlectCategoriasRS($arrData, 'facebook', 0);

    $arrData = array($this->model->getCatFbGgID("google", $id_gg));
    $return['google'] .= $this->recreaSlectCategoriasRS($arrData, 'google', 0);

    $arrData = $this->getArrayCatFbGgNivel('google', $id_gg);
    $return['google'] .= $this->recreaSlectCategoriasRS($arrData, 'google', 0);

    dep($return);
  }

  /* -------------------------------------------------------------------------- */
  /* ------------------------------------------------------------------------------------------------------------------------------- */

  private function getArrayCatFbGgNivel($tipo, $padre_id = null) {
//    devuelve un array con as categorias y su primer niver de subcategorias separados por un gion
    $arrData = array();
    $cat_id = $tipo === 'google' ? 'id_cat_gg' : 'id_fb';
    $array0 = $tipo == 'facebook' ? $this->model->getCatFacebookNivel($padre_id) : $this->model->getCatGoogleNivel($padre_id);
    foreach ($array0 as $cat0) {
      $cat0['nombre'] = strtoupper($cat0['nombre']);
      array_push($arrData, $cat0);
      $array1 = $tipo == 'facebook' ? $this->model->getCatFacebookNivel($cat0[$cat_id]) : $this->model->getCatGoogleNivel($cat0[$cat_id]);
      if (count($array1)) {
        foreach ($array1 as $cat1) {
          $cat1['nombre'] = '- ' . ucwords($cat1['nombre']);
          array_push($arrData, $cat1);
          $array2 = $tipo == 'facebook' ? $this->model->getCatFacebookNivel($cat1[$cat_id]) : $this->model->getCatGoogleNivel($cat1[$cat_id]);
          if (count($array2)) {
            foreach ($array2 as $cat2) {
              $cat2['nombre'] = '- - ' . ucwords($cat2['nombre']);
              array_push($arrData, $cat2);
            }
          }
        }
      }
    }

    return $arrData;
  }

}

//fin clase  



  