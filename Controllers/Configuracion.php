<?php

declare(strict_types=1);

class Configuracion extends Controllers {

  private $idModul = 7;

  public function __construct() {
    if (empty($_SESSION['login'])) {
      require_once "Login.php";
      $login = new Login();
      $login->Login();
      exit();         //header('location:' . base_url() . 'login');
    }
    parent::__construct();
  }

  /* VISTAS ====================================================================== */

  public function Configuracion() {
    $data["modulo"] = $this->idModul;
    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1) {
      $empresa = $_SESSION['info_empresa'];
      $data['empresa'] = $empresa;
      $data['page_name'] = 'Configuracion';
      $data['page_title'] = $data['page_name'];
      $data['logo_desktop'] = $empresa['url_logoMenu'];
      $data['shortcut_icon'] = $empresa['url_shortcutIcon'];
      /*       * ******************************************* */
      include __DIR__ . '/../Controllers/Notificacion.php';
      $notificacion = new Notificacion();
      $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
      /*       * ******************************************* */

      // las funciones de la pagina van de ultimo 
      $data["page_css"] = array();
      $data["page_functions_js"] = array("js/functions_configuracion.js");
      $this->views->getView("Configuracion", $data);
    } else {
      header('location:' . base_url() . 'dashboard');
      exit();
    }
  }

  /* ------------------------------------------------------------------------- */

  public function tiposDePago() {
    $data["modulo"] = $this->idModul;
    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1) {
      $empresa = $_SESSION['info_empresa'];
      $data['tpos_pago'] = $this->getTPDetalles();
      $data['empresa'] = $empresa;
      $data['page_name'] = 'Configuracion';
      $data['page_title'] = $data['page_name'];
      $data['logo_desktop'] = $empresa['url_logoMenu'];
      $data['shortcut_icon'] = $empresa['url_shortcutIcon'];
      /*       * ******************************************* */
      include __DIR__ . '/../Controllers/Notificacion.php';
      $notificacion = new Notificacion();
      $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
      /*       * ******************************************* */


      $data["page_css"] = array(
        "vadmin/libs/sweetalert2/sweetalert2.min.css",
        "plugins/datatables/css/datatables.min.css");

      $data["page_functions_js"] = array(
        "plugins/jquery/jquery-3.6.0.min.js",
        "vadmin/libs/sweetalert2/sweetalert2.min.js",
        "plugins/datatables/js/datatables.min.js",
        "js/functions_configuracion.js");

      $this->views->getView("TiposDePago", $data);
    } else {
      header('location:' . base_url() . 'dashboard');
      exit();
    }
  }

  /* ----------------------------------------------------------------------------- */

  public function ConfigRegion() {
    $data["modulo"] = $this->idModul;
    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1) {

      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'Configuracion Regional';
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
        "vadmin/libs/glightbox/css/glightbox.min.css",
        "plugins/datatables/css/datatables.min.css",
        "plugins/cropper/css/cropper.min.css",
      );
      $data["page_functions_js"] = array(
        "plugins/jquery/jquery-3.6.0.min.js",
        "vadmin/libs/choices.js/js/choices.min.js",
        "vadmin/libs/glightbox/js/glightbox.min.js",
        "plugins/datatables/js/datatables.min.js",
        "plugins/cropper/js/cropper.min.js",
        "plugins/tinymce/tinymce.min.js",
        "js/functions_configuracion.js");

      $this->views->getView("ConfiguracionRegional", $data);
    } else {
      // header('location:' . base_url() . 'dashboard');exit();
    }
  }

  /* METODOS CONFIGURACION ==================================================== */

  public function getConfiguracion() {
    $intId = 1;
    $this->model->selectConfig($intId); //buscamos los datos que correspondan a este id
  }

  /* ------------------------------------------------------------------------ */

  public function setConfiguracion() {

    !empty($_POST) ? '' : exit(array("status" => false, "msg" => "Datos incompletos"));  //Validamos que no este vacio el post
    $formData = $_POST;
    if (//empty($formData['idEmpresa']) ||
        empty($formData['txtNombreComercial']) || empty($formData['txtEmail']) || empty($formData['txtTelefono']) ||
        empty($formData['txtDireccion']) || empty($formData['txtServHost']) || empty($formData['txtServEmail']) || empty($formData['txtServPassword']) ||
        (isset($formData['login_facebook']) && $formData['txtIdAppFb'] === '') ||
        (isset($formData['login_facebook']) && $formData['txtClaveAppFb'] === '')
    ) {  // validamos que los datos no esten vacios 
      $arrResponse = array("status" => false, "msg" => "Datos incompletos");  // si uno de los datos esta vacio. entonces se devuelve un mensaje 
    } else {
//Datos Empresa
      $intIdEmpresa = 1;
      $strNombrecomercial = strClean($formData ['txtNombreComercial']);
      $strEmail = strClean($formData ['txtEmail']);
      $strTelefono = strClean($formData ['txtTelefono']);
      $strNombreFiscal = strClean($formData ['txtNombreFiscal']);
      $strIdFiscal = strClean($formData ['txtIdFiscal']);
      $strDireccion = strClean($formData ['txtDireccion']);
      $strDescripcion = strClean($formData ['txtDescripcion']);
      $strTags = strClean($formData ['txtEtiquetas']);
//RS
      $strLinkFacebook = strClean($formData ['txtLinkFacebook']);
      $strLinkInstagram = strClean($formData ['txtLinkInstagram']);
      $intTelfWhatsApp = intClean($formData ['intTelfWhatsApp']);
      $strTextoWhatsApp = strClean($formData ['txtTextoWhatsApp']);
      $strLinkTwitter = strClean($formData ['txtLinkTwitter']);
//Mail
      $int_smtp_status = isset($formData ['smtp_status']) ? 1 : 0;
      $strServHost = strClean($formData ['txtServHost']);
      $istrServMail = strClean($formData ['txtServEmail']);
      $strServPassword = strClean($formData ['txtServPassword']);
//Operacional
      $intGuardar_webp = (isset($formData ['guardar_webp']) && $formData ['guardar_webp'] == 'on') ? 1 : 0;
      $intCostoEnvio = floatval($formData ['intCostoEnvio']);
      $strModoEntrega = strClean($formData ['modoEntrega']);
      $strFechaMantenimientoHasta = strClean($formData ['fecha_mantenimiento_hasta']);
      $strHoraMantenimientoHasta = strClean($formData ['hora_mantenimiento_hasta']) ?? '00:00';
      $mantenimientoHasta = $strFechaMantenimientoHasta . ' ' . $strHoraMantenimientoHasta . ':00';
//Login
      $intLoginFacebook = empty($formData['login_facebook']) ? 0 : 1;
      $txtIdAppFb = strClean($formData['txtIdAppFb']) ?: null;
      $txtClaveAppFb = strClean($formData['txtClaveAppFb']) ?: null;
//Facebok Business-Pixel    
      $intPixelFacebook = empty($formData['pixel_facebook']) ? 0 : 1;
      $txtIdPixelFb = intval($formData['txtIdPixelFb']) ?: null;
      $txtMetaDominioFb = strClean($formData['txtMetaDominio']) ?: null;
      $txtExcluirIP = str_ireplace(' ', '', strClean($formData['txtExcuirIP'])) ?: null;
      $txtExcluirIP = str_ireplace(',', ';', $txtExcluirIP) ?: null;

//      dep($_POST); exit;
      // Imagenes
      $nombre_shrotcutIcon = $this->actualizar_img_config(// Actualiza la imagen de ser necesario y devuelve su nombre
          $strNombrecomercial, //nombre comercial formara parate de la url 
          'shrotcutIcon', //tipo de imagen 
          $_FILES['shrotcutIcon'], // datos de la imagen 
          $intGuardar_webp, //el formato en que sera guardada
          $formData['foto_actual_shrotcutIcon'], //nombre atual de la imagen, servira para validar si la imagen se actualizo o no 
          intval($formData['foto_remove_shrotcutIcon'])//ultimo estado de la imagen 
      );

      $nombre_logoMenu = $this->actualizar_img_config($strNombrecomercial, 'logoMenu', $_FILES['logoMenu'], $intGuardar_webp, $formData['foto_actual_logoMenu'], intval($formData['foto_remove_logoMenu']));
      $nombre_logoImpreso = $this->actualizar_img_config($strNombrecomercial, 'logoImpreso', $_FILES['logoImpreso'], $intGuardar_webp, $formData['foto_actual_logoImpreso'], intval($formData['foto_remove_logoImpreso']));

      $request = $this->model->updateConfig(//Enviamos los datos al modelo para actualizar la configuracion
          $intIdEmpresa,
          $strNombrecomercial,
          $strNombreFiscal,
          $strIdFiscal,
          $strEmail,
          $strTelefono,
          $strDireccion,
          $strDescripcion,
          $strTags,
          $strLinkFacebook,
          $strLinkInstagram,
          $intTelfWhatsApp,
          $strTextoWhatsApp,
          $strLinkTwitter,
          $nombre_logoMenu,
          $nombre_logoImpreso,
          $nombre_shrotcutIcon,
          $int_smtp_status,
          $istrServMail,
          $strServPassword,
          $strServHost,
          $mantenimientoHasta,
          $intCostoEnvio,
          $strModoEntrega,
          $intGuardar_webp,
          $intLoginFacebook,
          $txtClaveAppFb,
          $txtIdAppFb,
          $intPixelFacebook,
          $txtIdPixelFb,
          $txtMetaDominioFb,
          $txtExcluirIP
      );
      /** @var type $request */
      if ($request > 0) {
        $arrResponse = array('status' => true, 'msg' => 'Se ha actualizaco satisfactoriamente');
        unset($_SESSION['info_empresa']);
        $_SESSION['info_empresa'] = $this->InfoEmpresa();
      } else {
        $arrResponse = array('status' => false, 'msg' => 'No es posible Guardar la categoria');
      }
    }
    exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
  }

  private function actualizar_img_config($strNombrecomercial, $tipo, $obj_file, $intGuardar_webp, $img_actual, $img_remove) {
    $file_name = $obj_file['name']; // pasamos a variable el nombre 
    $estado = $file_name != '' && $img_remove === 1 ? 'actualizado' : 'sin_cambios';
    $nombre_img = $img_actual;
    if ($estado === 'actualizado') { // si la variable es diferente de vacio, quiere decir que se esta enviando una imagen     
      $nombre_ruta = str_replace(" ", "-", strtolower(clear_cadena($strNombrecomercial)));
      $extension_shrotcutIcon_a = explode('/', $obj_file['type'])[1];
      $extension_shrotcutIcon_b = $intGuardar_webp == 1 ? 'webp' : $extension_shrotcutIcon_a;
      $nombre_img = "img-$tipo-$nombre_ruta-" . uniqid() . ".$extension_shrotcutIcon_b"; // le generamos un nombre aleatorio a la imagenmd5(date('d-m-y h:m:s'))
      if ($intGuardar_webp) {
        $img_temp = pathinfo($nombre_img, PATHINFO_FILENAME) . '.' . $extension_shrotcutIcon_a;
        uploadImage($obj_file, $img_temp); // movemos el archivo del temporal a la carpeta image/upload
        $dir_img = 'uploads/' . FILE_SISTEM_CLIENTE . '/' . $img_temp;
        convertImageToWebP($dir_img);
        deleteFile($img_temp);
      } else {
        uploadImage($obj_file, $nombre_img); // movemos el archivo del temporal a la carpeta image/upload
      }
      deleteFile($img_actual);
    }
    return $nombre_img;
  }

  /* METODOS TIPOS DE PAGO ======================================================== */

  public function setTiposDePago() {
    $formData = $_POST;
    // dep($formData);    exit();
    $ceContraEntregaId = intval($formData['ceContraEntregaId']);
    $ceCheck = !empty($formData['ceCheck']) ? 1 : 0;

    $ceDescripcion = strClean($formData['ceDescripcion']);
    $ceDetalle = strClean($formData['ceDetalle']);

    $this->model->updStatusTP($ceContraEntregaId, $ceCheck);
    $ceOK = 0;
    if ($ceCheck != 0) {
      $a = $this->model->updTPDetalle($ceContraEntregaId, 'ceDescripcion', $ceDescripcion);
      $b = $this->model->updTPDetalle($ceContraEntregaId, 'ceDetalle', $ceDetalle);
      $ceOK = $a !== 0 && $a !== 0 ? $ceOK = 1 : 0;
    } else {
      $ceOK = 1;
    }
    //TRANSFERENCIA
    $tbTransferenciaId = intval($formData['tbTransferenciaId']);
    $tbCheck = !empty($formData['tbCheck']) ? 1 : 0;
    $tbDescripcion = $formData['tbDescripcion'];
    $tbDetalle = $formData['tbDetalle'];
    $tbOK = 0;
    $this->model->updStatusTP($tbTransferenciaId, $tbCheck);
    if ($tbCheck != 0) {
      $a = $this->model->updTPDetalle($tbTransferenciaId, 'tbDescripcion', $tbDescripcion);
      $b = $this->model->updTPDetalle($tbTransferenciaId, 'tbDetalle', $tbDetalle);

      $tbOK = $a != 0 && $a != 0 ? 1 : 0;
    } else {
      $tbOK = 1;
    }
    //PAYPAL
    $ppPayPalId = intval($formData['ppPayPalId']);
    $ppCheck = !empty($formData['ppCheck']) ? 1 : 0;

    $ppClienteID = $formData['ppClienteID'];
    $ppSecret = $formData['ppSecret'];
    $ppCurrency = $formData['ppCurrency'];
    $ppEntorno = $formData['ppEntorno'];
    $ppOK = 0;
    $this->model->updStatusTP($ppPayPalId, $ppCheck);
    if ($ppCheck != 0) {
      $a = $this->model->updTPDetalle($ppPayPalId, 'ppClienteID', $ppClienteID);
      $b = $this->model->updTPDetalle($ppPayPalId, 'ppSecret', $ppSecret);
      $c = $this->model->updTPDetalle($ppPayPalId, 'ppCurrency', $ppCurrency);
      $d = $this->model->updTPDetalle($ppPayPalId, 'ppEntorno', $ppEntorno);
      $ppOK = ($a != 0 && $a != 0 && $c != 0 && $d != 0) ? 1 : 0;
    } else {
      $ppOK = 1;
    }

    //MERCADOPAGO
    $mpMercadoPagoId = intval($formData['mpMercadoPagoId']);
    $mpCheck = isset($formData['mpCheck']) ? 1 : 0;

    $mpAplicacion = $formData['mpAplicacion'];
    $mpPublickKey = $formData['mpPublickKey'];
    $mpAccesTocken = $formData['mpAccesTocken'];
    $mpClienteID = $formData['mpClienteID'];
    $mpCleinteSecret = $formData['mpCleinteSecret'];
    $mpCurrency = $formData['mpCurrency'];
    $mpEntorno = $formData['mpEntorno'];

    $mpOK = 0;
    $this->model->updStatusTP($mpMercadoPagoId, $mpCheck);
    if ($mpCheck != 0) {
      $a = $this->model->updTPDetalle($mpMercadoPagoId, 'mpAplicacion', $mpAplicacion);
      $b = $this->model->updTPDetalle($mpMercadoPagoId, 'mpPublickKey', $mpPublickKey);
      $c = $this->model->updTPDetalle($mpMercadoPagoId, 'mpAccesTocken', $mpAccesTocken);
      $d = $this->model->updTPDetalle($mpMercadoPagoId, 'mpClienteID', $mpClienteID);
      $e = $this->model->updTPDetalle($mpMercadoPagoId, 'mpCleinteSecret', $mpCleinteSecret);
      $f = $this->model->updTPDetalle($mpMercadoPagoId, 'mpCurrency', $mpCurrency);
      $this->model->updTPDetalle($mpMercadoPagoId, 'mpEntorno', $mpEntorno);
      $mpOK = ($a != 0 && $a != 0 && $c != 0 && $d != 0 && $e != 0 && $f != 0) ? 1 : 0;
    } else {
      $mpOK = 1;
    }

    exit(json_encode($ceOK == 1 && $tbOK == 1 && $ppOK == 1 && $mpOK == 1 ?
                array('status' => true, 'msg' => 'Guardado Satisfactoriamente') :
                array('status' => false, 'msg' => 'No es posible guardar los tipos de pago')
            , JSON_UNESCAPED_UNICODE));
  }

  /* ------------------------------------------------------------------------ */

  private function getTPDetalles() {

    $TPDetalles = array(); // declaramos la variable conse almacenaran tipos de pago
    $arrTP = $this->model->selectTiposPagos(); // hacemos una consulta por los tipos de pago
    foreach ($arrTP as $TP) {  //recorremos el restultado de la consulta 
      $arrtpd = array(); // declaramos la variable para almacenar el detalle del tipo de pago 
      $detalled = $this->model->selectTiposPagoDetalles($TP['idtipopago']);
      foreach ($detalled as $D) {
        $arrtpd = array_merge($arrtpd, array($D['tpopago_label'] => $D['tpopago_value']));
      }
      $tipopago[$TP ['tipopago']] = array(
        'idtipopago' => $TP ['idtipopago'],
        'nombre_tpago' => $TP ['nombre_tpago'],
        'status' => $TP ['status'],
        'detalle' => $arrtpd
      );
      $TPDetalles = $tipopago;
    }
    return $TPDetalles;
  }

  /* METODOS CONFIGURACION REGIONAL ===========================================*/

  public function getRegionesList() {
    $arrData = $this->model->selectRegiones();

    foreach ($arrData as $i => $item) {
      $id = $item['idregion'];
      // BOTONES DE OPCIONES
      $opciones = "<div class= 'text-center'>";
      $opciones .= "<button class='btn btn-secondary m-1' onClick='fntPermisos({$id})' title='Ver' type='button' ><i class='fas fa-eye'></i></button>";
      $opciones .= $_SESSION['userPermiso'][$this->idModul]['actualizar'] == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar' type='button'><i class='fas fa-edit'></i></button>" : '';
      /* $opciones .= $item['status'] == 1 ?
        "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado'    type='button' id='btnStatus{$id}' value='1'><i class='fa fa-power-off' aria-hidden='true'></i></button>" :
        "<button class='btn btn-danger m-1 ' onClick='fntStatus({$id})' title='Desactivado' type='button' id='btnStatus{$id}' value='0'><i class='fa fa-power-off' aria-hidden='true'></i></button>"; */
//      if ($_SESSION['userPermiso'][$this->idModul]['eliminar'] == 1) { // si el rol esta en uso solo podra ser activado o desactivado
//      $opciones .= $this->model->configRegionEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar' type='button'><i class='fas fa-trash-alt'></i></button>";
//      }
      $arrData[$i]['options'] = $opciones . "</div>";
      // INDICADOR DE ESTADO reemplaza los valores 0 y 1 por inactivo - Activo 
//      $arrData[$i]['status'] = $item['status'] == 1 ? "<span class='badge bg-success'> Activo </span>" : "<span class='badge bg-danger'>Inactivo</span>";
    }
    exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
  }

  public function getRegion(int $idget) {
    $id = intval($idget);  //limpiamos los datos que vienen dentro de la variable $idRol
    //si el contenido de la variable es mayor a 0 significa que hay un id a buscar
    if ($id > 0) {
      $arrData = $this->model->selecRegionId($id); //buscamos los datos que correspondan a este id
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
}
