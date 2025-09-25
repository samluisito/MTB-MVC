<?php

declare(strict_types=1);

class Homebanner extends Controllers {

  private $idModul = 4;

  public function __construct() {
    if (empty($_SESSION['login'])) {
      require_once "Login.php";
      $login = new Login();
      $login->Login();
      exit();         //header('location:' . base_url() . 'login');
    }
    parent::__construct();
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function Homebanner($params) {
    //ejecuta el contenido del archivo home
    //echo 'Mensaje desde el controlador home';

    $data["modulo"] = $this->idModul;
    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1) {
      //$empresa = $_SESSION['info_empresa'];
      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'Configuracion Home banner';
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
        "vadmin/libs/choices.js/css/choices.min.css",
        "plugins/datatables/css/datatables.min.css",
        "plugins/cropper/css/cropper.min.css",
      );
      $data["page_functions_js"] = array(
        "plugins/jquery/jquery-3.6.0.min.js",
        "vadmin/libs/choices.js/js/choices.min.js",
        "plugins/datatables/js/datatables.min.js",
        "plugins/cropper/js/cropper.min.js",
        "plugins/tinymce/tinymce.min.js",
        "js/functions_config_home.js");

      $this->views->getView("Homebanner", $data);
    } else {
      header('location:' . base_url() . 'dashboard');
      exit();
    }
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  //CREAR - ACTUALIZAR ROL
  public function setBanner() {
//      dep($_POST);
//      dep($_FILES);
//      exit();

    if ($_POST) { //Validamos que no este vacio el post
      if (empty($_POST['txtNombre']) || empty($_POST['txtDescripcion'])) {  // validamos que los datos no esten vacios 
        $arrResponse = array("status" => false, "msg" => "Datos incompletos");  // si uno de los datos esta vacio. entonces se devuelve un mensaje 
      } else {
        //recibe los datos por medio de url y devuelve un mensaje json segun su resultado
        //los datos enviados los almacenamos en variables
        $idUnico = uniqid();
        $intId = intval($_POST['idBanner']);
        $strNombre = strClean($_POST['txtNombre']);
        $strDescripcion = strClean($_POST['txtDescripcion']);
        $strListTpo = strClean($_POST['listTpo']); //
        $intListItem = intval($_POST['listItem']); //
        $intStatus = intval($_POST['listStatus']); //
        // pasamos a variables los datos de la foto 
        $foto_actual = $_POST['foto_actual'];
        $foto_remove = $_POST['foto_remove'];
        $nombre_foto = $_POST['foto_blob_name'] == '' ? '' : $_POST['foto_blob_name']; // $foto['name']; // pasamos a variable el nombre 
        $type = $_POST['foto_blob_type'] == '' ? '' : explode('/', strClean($_POST['foto_blob_type']))[1]; //el tipo de archivo // $foto['type']
        $type = $type == 'jpeg' ? 'jpg' : $type; //

        $foto = $_FILES['foto']; //
        $url_temp = $foto['tmp_name']; //la url temporal donde se sube la foto al server

        $imgNombre = "banner.png"; //
//            $ruta = strtolower(clear_cadena($strBanner));
        $ruta_completa = $this->model->selectUrlItem($strListTpo, $intListItem, 'ruta');
        $nombre_img = $this->model->selectUrlItem($strListTpo, $intListItem, 'nombre');
        //$ruta = str_replace(" ", "-", $ruta);

        /* ESTADO DE FOTOS---------------------------------- */
        $estado_foto = '';
        if ($nombre_foto != '' && $nombre_foto != $foto_actual && $foto_remove == 1) {
          $estado_foto = 'actualizada';
          $imgNombre = 'banner_' . $nombre_img . '-' . $idUnico . '.' . $type; // le generamos un nombre aleatorio a la imagen
        }
        if ($nombre_foto != '' && $foto_actual == '') {
          $estado_foto = 'nueva';
          $imgNombre = 'banner_' . $nombre_img . '-' . $idUnico . '.' . $type; // le generamos un nombre aleatorio a la imagen
        }
        if ($foto_actual != 'banner.png' && $foto_remove == '') {
          $estado_foto = 'sin_mov';
          $imgNombre = $foto_actual; // le generamos un nombre aleatorio a la imagen
        }
        if ($nombre_foto == '' && $foto_actual == 'banner.png' || $nombre_foto == '' && $foto_actual == '') {
          $estado_foto = 'sin_mov_def';
          $imgNombre = 'banner.png'; // le generamos un nombre aleatorio a la imagen
        }
        if ($nombre_foto == '' && $foto_actual != 'banner.png' && $foto_remove == 1) {
          $estado_foto = 'eliminada';
          $imgNombre = 'banner.png';
        }


        if ($intId == 0) {//NUEVO BANNER validamos por medio del id si es un nuevo Banner o si se actualiza una Banner.
          //creamos una una categoria, enviamos los datos al modelo
          $request = $this->model->insertBanner($strNombre, $strDescripcion, $imgNombre, $strListTpo, $intListItem, $ruta_completa, $intStatus);
          $id_img = $request;
          $option = 'new';
        } else { //ACTUALIZAR BANNER si intId es distinto de cero arctuelizamos un categoria
          //Actualiamos una categoria, enviam,os los datos al modelo
          $request = $this->model->updateBanner($intId, $strNombre, $strDescripcion, $imgNombre, $strListTpo, $intListItem, $ruta_completa, $intStatus);
          $id_img = $intId;

          $option = 'update';
        }
        // depemdiendo de la respuesta enviamos un mensaje 
        /* hacemos las minuaturas */


        if ($request === 'exist') {
          $arrResponse = array('status' => false, 'msg' => 'Atencion la Banner Ya Existe');
        } else if ($request > 0) {

          if ($option == 'new') {
            if ($estado_foto == 'nueva') { // si esta variable foto es diferente de vacio
              $uploadImage = uploadImage($foto, $imgNombre); // movemos el archivo del temporal a la carpeta image/upload
            }
            $arrResponse = array('status' => true, 'msg' => 'Datos Guardados Correctamente');
          } elseif ($option == 'update') {
            if ($estado_foto == 'nueva' || $estado_foto == 'actualizada') { //movemos la imagen del 
              uploadImage($foto, $imgNombre); // movemos el archivo del temporal a la carpeta image/upload
            }
            if ($estado_foto == 'eliminada' || $estado_foto == 'actualizada') { //indica que estamos subiendo una imagen que no es la imagen por defecto
              if ($foto_actual != 'banner.png') {
                deleteFile($foto_actual);
                $imgNombre2 = 'thumb_1' . $foto_actual;
                $deleteFile = deleteFile($imgNombre2);
                $imgNombre2 = 'thumb_2_' . $foto_actual;
                $deleteFile = deleteFile($imgNombre2);
                $imgNombre2 = 'thumb_3_' . $foto_actual;
                $deleteFile = deleteFile($imgNombre2);
              }
            }
          }
          if ($estado_foto == 'nueva' || $estado_foto == 'actualizada') {

            $img_orig = './uploads/' . FILE_SISTEM_CLIENTE . $imgNombre; // direccion y nombre de la imagen 
            //$nombre = pathinfo($imgArchivo, PATHINFO_FILENAME);
            $extension = pathinfo($imgNombre, PATHINFO_EXTENSION);

            if ($_SESSION['info_empresa']['guardar_webp']) {// si la extension es distinta de webp: 1 crea una imagen webp, 2 actualiza el dato en la base, 3 borra la imagen de origen, 4 actualiza la img_origin para crear las miniaturas
              $imgwebp = convertImageToWebP($img_orig); // entrega la direccion de la imagen y hace una copia webp, devuelve el nombre de la imagen con la extension webp
              $actualizaImgBD = $this->model->actualizaImagenBanner($id_img, $imgwebp); // actualizamos el nuevo nombre de la imagen en la base de datos

              if ($actualizaImgBD) {//si la actualizacion es correcta borramos la imagen de origen jpg, gif, png
                deleteFile($imgNombre);
                $imgNombre = $imgwebp;
                $img_orig = './uploads/' . FILE_SISTEM_CLIENTE . $imgwebp; //actualiza la img_origin para crear las miniaturas
              }
            }
            $ancho = 1920;
            $alto = 930;

            thumbImage($img_orig, "1_$imgNombre", $ancho, $alto, 80);
            thumbImage($img_orig, "2_$imgNombre", $ancho * 0.70, $alto * 0.70, 70);
            thumbImage($img_orig, "3_$imgNombre", $ancho * 0.40, $alto * 0.40, 60);
          }
          $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados Correctamente');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'No es posible Guardar el Banner');
        }
      }
      //dep($arrResponse);
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    } exit();
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function recreaThumb() {
    $requestLista = $this->model->listarImagenesBanners();

    foreach ($requestLista as $banner) {
      print "Banner: {$banner['idbanner']} " . $banner['nombre'] . '<br>';

      $nombre = $banner['img'];
      $img_orig = $nombre; // obtiene el nombre de la imagen 
      $extension = pathinfo($img_orig, PATHINFO_EXTENSION); //obtiene la extension
      $img_orig = './uploads/' . FILE_SISTEM_CLIENTE . $banner['img']; //armamos la direccion de la imagen

      if ($_SESSION['info_empresa']['guardar_webp'] && $extension != 'webp') {// si la extension es distinta de webp: 1 crea una imagen webp, 2 actualiza el dato en la base, 3 borra la imagen de origen, 4 actualiza la img_origin para crear las miniaturas
        print "imagen de origen: " . $img_orig . '<br>';
        $imgwebp = convertImageToWebP($img_orig); // entrega la direccion de la imagen y hace una copia webp, devuelve el nombre de la imagen con la extension webp
        print "imagen creada: " . $imgwebp . '<br>';
        $actualizaImgBD = $this->model->actualizaImagenBanner($banner['idbanner'], $imgwebp); // actualizamos el nuevo nombre de la imagen en la base de datos
        if ($actualizaImgBD) {//si la actualizacion es correcta borramos la imagen de origen jpg, gif, png
          deleteFile($banner['img']) ? print "imagen borrada: " . $banner['img'] . '<br>' : '';
          deleteFile('thumb_1_' . $banner['img']) ? print "imagen borrada: thumb_1_" . $banner['img'] . '<br>' : '';
          deleteFile('thumb_2_' . $banner['img']) ? print "imagen borrada: thumb_2_" . $banner['img'] . '<br>' : '';
          deleteFile('thumb_3_' . $banner['img']) ? print "imagen borrada: thumb_3_" . $banner['img'] . '<br>' : '';

//               deleteFile('thumb_og_' . $banner['img']);
//               print "imagen borrada: thumb_og_" . $banner['img'] . '<br>';
        }
        $nombre = $imgwebp;
        $img_orig = './uploads/' . FILE_SISTEM_CLIENTE . $imgwebp; //actualiza la img_origin para crear las miniaturas
      }


      dep($img_orig);

      $ancho = 1920;
      $alto = 930;

      $imgNombre1 = '1_' . $nombre;
      $thumb1 = thumbImage($img_orig, $imgNombre1, $ancho, $alto, 80);
      print 'imagen creada: <a href="' . base_url() . $thumb1 . '" target="_blank">' . $imgNombre1 . '</a><br>';

      $imgNombre2 = '2_' . $nombre;
      $thumb2 = thumbImage($img_orig, $imgNombre2, $ancho * 0.70, $alto * 0.70, 70);
      print 'imagen creada: <a href="' . base_url() . $thumb2 . '" target="_blank">' . $imgNombre2 . '</a><br>';

      $imgNombre3 = '3_' . $nombre;
      $thumb3 = thumbImage($img_orig, $imgNombre3, $ancho * 0.30, $alto * 0.30, 60);
      print 'imagen creada: <a href="' . base_url() . $thumb3 . '" target="_blank">' . $imgNombre3 . '</a><br>';

      dep($img_orig);
    }
  }

  public function recreaBanner() {
    $requestLista = $this->model->listarImagenesBanners();
    // dep($requestLista);
    foreach ($requestLista as $item) {
      //$img_orig = './uploads/' . FILE_SISTEM_CLIENTE . '/' . $image['img'];
      //$listPublicar = $image['publicar'];
      dep($item);
      $strNombre = $item['nombre'];
      $strDescripcion = $item['descripcion'];
      $img_banner = $item['img'];
      $strListTpo = $item['tipo_cat'] == 'prod' ? 'categ' : 'blog';
      $intListItem = $item['idcategoria'];
      $ruta_completa = $this->model->selectUrlItem($strListTpo, $intListItem, 'ruta');
      dep($ruta_completa);
      $intStatus = $item['status'];

      $request = $this->model->insertBanner($strNombre, $strDescripcion, $img_banner, $strListTpo, $intListItem, $ruta_completa, $intStatus);
      dep($request);
    }
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  //DEVUELVE UN ARRAY CON LOS DATOS DE CATEGORIAS Y BOTONES DE OPCION BOOSTRAP PARA INSERTAR EN DATATABLE
  public function getBanners() {
    $arrData = $this->model->selectBanners(); //consultamos la tabla y traemos todos los registros 
    //reemplaza los valores 0 y 1 por inactivo - Activo    
    foreach ($arrData as $i => $item) {
      $id = $item['idbanner'];
      $arrData[$i]['img'] = $item['img'] != '' ?
          "<img class='minlistprod_img' src='" . DIR_IMAGEN . "thumb_3_{$item["img"]}'> " :
          "<img class='minlistprod_img' src='" . DIR_MEDIA . "images/producto_sin_foto.png'> ";

      // BOTONES DE OPCIONES
      $opciones = "<div class= 'text-center'>";
      $opciones .= "<button class='btn btn-secondary m-1' onClick='fntVer({$id})' title='Ver' type='button' ><i class='fas fa-eye'></i></button>";
      $opciones .= $_SESSION['userPermiso'][$this->idModul]['actualizar'] == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar' type='button'><i class='fas fa-edit'></i></button>" : '';
      $opciones .= $item['status'] == 1 ?
          "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado'    type='button' id='btnStatus{$id}' value='1'><i class='fa fa-power-off' aria-hidden='true'></i></button>" :
          "<button class='btn btn-danger m-1 ' onClick='fntStatus({$id})' title='Desactivado' type='button' id='btnStatus{$id}' value='0'><i class='fa fa-power-off' aria-hidden='true'></i></button>";
      if ($_SESSION['userPermiso'][$this->idModul]['eliminar'] == 1) { // si el rol esta en uso solo podra ser activado o desactivado
        $opciones .= $this->model->bannerEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar' type='button'><i class='fas fa-trash-alt'></i></button>";
      }

      $arrData[$i]['options'] = $opciones . "</div>";
      // INDICADOR DE ESTADO reemplaza los valores 0 y 1 por inactivo - Activo 
      $arrData[$i]['status'] = $item['status'] == 1 ? "<span class='badge bg-success'> Activo </span>" : "<span class='badge bg-danger'>Inactivo</span>";
    }
    exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function getBanner(int $id) {//DEVUELVE UN ARRAY JSON CON LOS DATOS DEL BANNER
    $intId = intval($id); //limpiamos los datos que vienen dentro de la variable $id
    if ($intId > 0) {      //si el contenido de la variable es mayor a 0 significa que hay un id a buscar      
      $arrData = $this->model->selectBanner($intId); //buscamos los datos que correspondan a este id
      if (empty($arrData)) {//si no devuelve ningun dato, respondemos con una array json de dato no encontrado
        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
      } else {
        //agregamos u nuevo elemento en el array con la ruta completa de la imagen 
        if ($arrData['img'] == 'banner.png') {
          $arrData['url_img'] = DIR_MEDIA . 'images/' . $arrData['img'];
        } else {
          $arrData['url_img'] = DIR_IMAGEN . $arrData['img'];
        }
      }$arrResponse = array('status' => true, 'data' => $arrData,);
      //IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON 
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
      exit();
    }
    exit();
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */



  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function getItemPorTipo($data = null) {
    //Segun el tipo de item, reliza una consulta del id y el nombre, devuelve una lista de items (producto, categoria o entraddas de blog) 

    $data = explode(',', $data);
    $tipo = $data[0];
    $id = isset($data[1]) ? $data[1] : 0;

    $htmlOption = "";
    $arrData = $this->model->selectItem($tipo);
    if (count($arrData) > 0) {
      for ($index = 0; $index < count($arrData); $index++) { //repasamos la lista y creamos un array html con el valor y el nombre 
        $selected = $arrData[$index]['id'] == $id ? 'selected' : '';
        $htmlOption .= '<option value ="' . $arrData[$index]['id'] . '" ' . $selected . ' >' . $arrData[$index]['nombre'] . '</option>';
        // }
      }
    }

    exit($htmlOption);
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function getUrlItem($data) {
    //realiza una consulta de la URL segun el tipo de item, devuelve un json con status y 
    $data = explode(',', $data);
    $tipo = strClean($data[0]);
    $id = intval($data[1]);

    $id == 0 ? exit(json_encode(array('status' => false, 'msg' => ''), JSON_UNESCAPED_UNICODE)) : '';

    $arrData = $this->model->selectUrlItem($tipo, $id, 'ruta');
    $arrResponse = $arrData == false ? array('status' => false, 'msg' => '') :
        array('status' => true, 'url' => $arrData);
    exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
  }

  ///------------------------------------------------------------------------------------------------------------------------------

  public function statusBannerChange() {
    if (isset($_GET['id']) && isset($_GET['intStatus'])) {
      $intId = intval($_GET['id']);
      $intStatus = intval($_GET['intStatus']);
      $requestStatus = $this->model->editBannerStatus($intId, $intStatus);
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

  public function delBanner() {
    empty($_POST) ? exit(json_encode(array('status' => false, 'msg' => 'no hay datos'), JSON_UNESCAPED_UNICODE)) : '';
    $intId = intval($_POST['id']);
    $imagen = $this->model->selectImgBanner($intId);
    $img_del = 1;
    if ($imagen) {
      if ($imagen !== 'portada_categoria.png') {
        $del1 = deleteFile('thumb_1_' . $imagen);
        $del2 = deleteFile($imagen);
        $img_del = $del1 === 1 && $del2 === 1 ? 1 : 0;
      }
    }//array('status' => true, 'msg' => 'Se ha eliminado el Producto'),
    if ($img_del) {
      $arrResponse = $this->model->deleteBanner($intId) ?
          array('status' => true, 'msg' => 'Banner Exterminado') :
          array('status' => false, 'msg' => 'Error al borrar Banner');
    } else {
      $arrResponse = array('status' => false, 'msg' => 'Error al borrar imagenes');
    }
    exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE)); //inval convierte en entero el parametro que le ingresen
  }

}
