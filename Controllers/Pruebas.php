<?php

declare(strict_types=1);
    //dep((microtime(true)) - TIME_INI . ' inicio doc controlador ');  

class Pruebas extends Controllers {

  public function __construct() {
        //dep((microtime(true)) - TIME_INI . ' antes de construct model-> ');  

    parent::__construct();
        //dep((microtime(true)) - TIME_INI . ' despues de construc model-> ');  

  }

  public function Pruebas($params) {

    $empresa = $_SESSION['info_empresa'];

    $data["empresa"] = $empresa;

    $the_date = strtotime($empresa['fecha_mantenimiento_hasta']);

    $data["empresa"] = $empresa;
    $data['page_name'] = 'Dashboard';
    $data['page_title'] = $data['page_name'];
    $data['logo_desktop'] = $empresa['url_logoMenu'];
    $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

    $data["page_css"] = array();
    $data["page_functions_js"] = array();
    
    //dep((microtime(true)) - TIME_INI . ' controlador antes operaciones ');  

//    $data['tipos_pagos'] = $this->getTPDetalles();
    //dep((microtime(true)) - TIME_INI . ' controlador antes vistas ');  

    $this->views->getView("ordenar", $data);  
  }

  /* -------------------------------------------------------------------------------- */

  public function useragent($param) {
    //dep($_SERVER['HTTP_USER_AGENT']);
    //dep(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
    //dep(detectar_dispositivo());
    
    
  }

  public function varsession($param) {


    $s = str_repeat('1', 255); // Genera una cadena que consta de 255 unidades
    $m = memory_get_usage(); // Obtiene la memoria ocupada actual
    unset($s);
    $mm = memory_get_usage(); // unset () y luego verifica la memoria ocupada actual
    //dep($m);
    //dep($mm);

    echo $m - $mm;
    //dep('a');

    //dep(dispositivoTipo());
    //dep(dispositivoOS());

    gd_info();

    echo '$_SESSION';
    //dep($_SESSION);

    echo '$_SERVER';

    $ipcli = getUserIP(); //Mediante $_SERVER[‘REMOTE_ADDR’] obtenemos la dirección IP desde la cual está viendo la página actual el usuario.

    echo "<br> IP Cliente  " . $ipcli;
    echo 'IP del servidor: ' . $_SERVER['SERVER_ADDR'] . "<br/>"; //Imprime la IP del servidor
    echo 'nombre del servidor: ' . $_SERVER['SERVER_NAME'] . "<br/>"; //Imprime el nombre del servidor
    echo 'S.O y navegador del cliente: ' . $_SERVER['HTTP_USER_AGENT'] . "<br/>"; /* Imprime la información de S.O y navegador del cliente */

    //dep($_SERVER);

    echo '$_COOKIE';
    //dep($_COOKIE);

    $meta = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . '66.249.64.184'));
    $latitud = $meta['geoplugin_latitude'];
    $longitud = $meta['geoplugin_longitude'];
    $ciudad = $meta['geoplugin_city'];
    //dep($meta);

    //Uso de getenv()
    $ip = getenv('REMOTE_ADDR');
    //dep($ip);

    phpinfo();
  }

  /* -------------------------------------------------------------------------------- */

  public function procesarfoto($param) {
    if (($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/gif")) {
      if (move_uploaded_file($_FILES["file"]["tmp_name"], "images/" . $_FILES['file']['name'])) {
        //more code here...
        echo "images/" . $_FILES['file']['name'];
      } else {
        echo 0;
      }
    } else {
      echo 0;
    }
  }

  /* ----------------------------------------------------------------- */
  /*   * *******************************************************************
    Purpose      : update image.
    Parameters       : null
    Returns      : integer
   * ********************************************************************* */

  public function changeAvatar() {
    global $current_user;
    $loggedInId = $current_user->ID;
    $post = isset($_POST) ? $_POST : array();
    $max_width = "500";
    $userId = isset($post['hdn-profile-id']) ? intval($post['hdn-profile-id']) : 0;
    $path = get_theme_root() . 'imagesuploadstmp';

    $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];
    if (strlen($name)) {
      list($txt, $ext) = explode(".", $name);
      if (in_array($ext, $valid_formats)) {
        if ($size < (1024 * 1024)) { // Image size max 1 MB
          $actual_image_name = 'avatar' . '_' . $userId . '.' . $ext;
          $filePath = $path . '/' . $actual_image_name;
          $tmp = $_FILES['photoimg']['tmp_name'];

          if (move_uploaded_file($tmp, $filePath)) {
            $width = $this->getWidth($filePath);
            $height = $this->getHeight($filePath);
            //Scale the image if it is greater than the width set above
            if ($width > $max_width) {
              $scale = $max_width / $width;
              $uploaded = $this->resizeImage($filePath, $width, $height, $scale);
            } else {
              $scale = 1;
              $uploaded = $this->resizeImage($filePath, $width, $height, $scale);
            }
            $res = $this->Profile->saveAvatar(array(
              'userId' => isset($userId) ? intval($userId) : 0,
              'avatar' => isset($actual_image_name) ? $actual_image_name : '',
            ));

            //mysql_query("UPDATE users SET profile_image='$actual_image_name' WHERE uid='$session_id'");
            echo "<img id='photo' class='' src='" . getCustomAvatar($userId, true) . '?' . time() . "' class='preview'/>";
          } else
            echo "failed";
        } else
          echo "Image file size max 1 MB";
      } else
        echo "Invalid file format..";
    } else
      echo "Please select image..!";
    exit;
  }

  /*   * ***************************************************************************** */

  public function img_area_select() {
    //dep($_POST);
    //dep($_GET);

    // Recuperamos las variables recibidas por post
    $x1 = $_POST["x1"];
    $y1 = $_POST["y1"];
    $x2 = $_POST["x2"];
    $y2 = $_POST["y2"];
    $anchura = $_POST["anchura"];
    $altura = $_POST["altura"];
    $imagen = $_POST["imagen"];

    $imagenDeOrigen = '../images/' . $imagen;
    $manejadorDeOrigen = imagecreatefromjpeg($imagenDeOrigen);
    $manejadorDeDestino = ImageCreateTrueColor($anchura, $altura);
    imagecopyresampled(
        $manejadorDeDestino,
        $manejadorDeOrigen,
        0,
        0,
        $x1,
        $y1,
        $anchura,
        $altura,
        $anchura,
        $altura
    );
    imagejpeg($manejadorDeDestino, "../images/prueba.jpg", 100);
  }

  /*   * ************************************************************************* */

  public function setCategoria() {


    if ($_POST) { //Validamos que no este vacio el post
      if (empty($_POST['txtNombre']) || empty($_POST['txtDescripcion'])) {  // validamos que los datos no esten vacios 
        $arrResponse = array("status" => false, "msg" => "Datos incompletos");  // si uno de los datos esta vacio. entonces se devuelve un mensaje 
      } else {
        //recibe los datos por medio de url y devuelve un mensaje json segun su resultado
        //los datos enviados los almacenamos en variables

        $intIdCat = intval($_POST['idCategoria']);
        $strCat = strClean($_POST['txtNombre']);
        $strDescripcion = strClean($_POST['txtDescripcion']);
        $listTpoCat = strClean($_POST['listTpoCat']); //
        $listPublicar = strClean($_POST['listPublicarCat']); //
        $strTags = strClean($_POST['txtTags']); //
        $intStatus = intval($_POST['listStatus']); //
        // pasamos a variables los datos de la foto 

        $foto_actual = $_POST['foto_actual'];
        $foto_remove = $_POST['foto_remove'];

        $foto = $_FILES['foto']; //
        $nombre_foto = $foto['name']; // pasamos a variable el nombre 
        $type = $foto['type']; //el tipo de archivo 
        $url_temp = $foto['tmp_name']; //la url temporal donde se sube la foto al server
        $img_portada = "portada_categoria.png"; //

        $ruta = strtolower(clear_cadena($strCat));
        $ruta = str_replace(" ", "-", $ruta);
        /* ESTADO DE FOTOS---------------------------------- */
        $estado_foto = '';

        if ($nombre_foto != '' && $nombre_foto != $foto_actual && $foto_remove == 1) {
          $estado_foto = 'actualizada';
        }
        if ($nombre_foto != '' && $foto_actual == '') {
          $estado_foto = 'nueva';
        }
        if ($foto_actual != 'portada_categoria.png' && $foto_remove == '') {
          $estado_foto = 'sin_mov';
        }
        if ($nombre_foto == '' && $foto_actual == 'portada_categoria.png' || $nombre_foto == '' && $foto_actual == '') {
          $estado_foto = 'sin_mov_def';
        }
        if ($nombre_foto == '' && $foto_actual != 'portada_categoria.png' && $foto_remove == 1) {
          $estado_foto = 'eliminada';
        }

        /* ESTADO DE IMG PORTADA ----------------------------- */
        if ($estado_foto == 'nueva') {
          $img_portada = 'img-' . $ruta . '-' . md5(date('d-m-y h:m:s')) . '.jpg'; // le generamos un nombre aleatorio a la imagen
        }
        if ($estado_foto == 'sin_mov') {
          $img_portada = $foto_actual; // le generamos un nombre aleatorio a la imagen
        }
        if ($estado_foto == 'sin_mov_def') {
          $img_portada = 'portada_categoria.png'; // le generamos un nombre aleatorio a la imagen
        }
        if ($estado_foto == 'actualizada') {
          $img_portada = 'img-' . $ruta . '-' . md5(date('d-m-y h:m:s')) . '.jpg'; // le generamos un nombre aleatorio a la imagen
        }
        if ($estado_foto == 'eliminada') {
          $img_portada = 'portada_categoria.png';
        }

        // nueva categoria 
        if ($intIdCat == 0) {//validamos por medio del id si es un nuevo Categoria o si se actualiza una Categoria.
          //creamos una una categoria, enviamos los datos al modelo
          $request_cat = $this->model->insertCategoria($strCat, $strDescripcion, $img_portada, $listTpoCat, $listPublicar, $strTags, $ruta, $intStatus);
          $option = 'new';
          //ACTUALIZAR CATEGORIA 
        } else { // si intIdCat es distinto de cero arctuelizamos un categoria
          //Actualiamos una categoria, enviam,os los datos al modelo
          $request_cat = $this->model->updateCategoria($intIdCat, $strCat, $strDescripcion, $img_portada, $listTpoCat, $listPublicar, $strTags, $ruta, $intStatus);
          $option = 'update';
        }
        // //depemdiendo de la respuesta enviamos un mensaje 
        if ($request_cat > 0) {
          if ($option == 'new') {
            if ($estado_foto == 'nueva') { // si esta variable foto es diferente de vacio
              uploadImage($foto, $img_portada); // movemos el archivo del temporal a la carpeta image/upload
            }
            $arrResponse = array('status' => true, 'msg' => 'Datos Guardados Correctamente');
          } elseif ($option == 'update') {
            if ($estado_foto == 'nueva' || $estado_foto == 'actualizada') { //movemos la imagen del 
              uploadImage($foto, $img_portada); // movemos el archivo del temporal a la carpeta image/upload
            }
            if ($estado_foto == 'eliminada' || $estado_foto == 'actualizada') { //indica que estamos subiendo una imagen que no es la imagen por defecto
              if ($foto_actual != 'portada_categoria.png') {
                deleteFile($foto_actual);
              }
            }
            $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados Correctamente');
          }
        } else if ($request_cat == 'exist') {
          $arrResponse = array('status' => false, 'msg' => 'Atencion la Categoria Ya Existe');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'No es posible Guardar la categoria');
        }
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    } exit();
  }

  public function cropper() {
    //dep($_GET);
    //dep($_REQUEST);
    //dep($_FILES);
    exit();
    if ($json = file_get_contents("php://input")) {
      //pasamos de json a array
      $post = json_decode($json, true);
      //en la key image llega un string de este estilo:
      // data:image/png;base64,iVBORw0KGgoAAAA....
      $parts = explode(";base64,", $post["image"]);
      //despues de decodificarlo vuelve a ser "blob" (mirar el codigo js que lo convierte de blob a base64)
      //$strblob guardaría algo como
      // �PNG  IHDR��󠒱 IDATx^���gv�U�˹_�42�@�Yaf�I�,{m�Ɩ���8h����H�h5Z�v5��+k�Y�$+L�H+�f
      $strblob = base64_decode($parts[1]);
      $uuid = uniqid();
      $pathfile = "./Views/Pruebas/$uuid.png";

      file_put_contents($pathfile, $strblob);
      $pathfile = "/tienda-virtual/Views/Pruebas/$uuid.png";
      echo json_encode([
        "message" => "image uploaded successfully.",
        "file" => $pathfile
      ]);
      exit;
    }
  }
/*
  private function getTPDetalles() {

    $TPDetalles = array(); // declaramos la variable conse almacenaran tipos de pago
    $arrTP = $this->model->selectTiposPagos(); // hacemos una consulta por los tipos de pago
    foreach ($arrTP as $TP) {  //recorremos el restultado de la consulta 
      $arrtpd = array(); // declaramos la variable para almacenar el detalle del tipo de pago 
      $detalled = $this->model->selectTiposPagoDetallesT($TP['idtipopago']);

      foreach ($detalled as $D) {

        $arrtpd = array_merge($arrtpd, array($D['tpopago_label'] => $D['tpopago_value']));
      }

      $tipopago[$TP ['tipopago']] = array(
        'tipopago' => $TP ['tipopago'],
        'idtipopago' => $TP ['idtipopago'],
        'nombre_tpago' => $TP ['nombre_tpago'],
        'status' => $TP ['status'],
        'detalle' => $arrtpd
      );

      $TPDetalles = $tipopago;
    }

    return $TPDetalles;
  }
*/
}
