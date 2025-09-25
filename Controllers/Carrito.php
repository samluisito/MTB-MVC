<?php

declare(strict_types=1);

class Carrito extends Controllers {

  public function __construct() {

    if ($_SESSION['info_empresa']['fecha_mantenimiento_hasta'] > date("Y-m-d H:i:s")) {
      header("Location:" . base_url() . 'enConstruccion');
    }
    parent::__construct();
  }

  /* vistas ------------------------------------------------------------------------------------------------------------------------------------ */
  /*  ------------------------------------------------------------------------------------------------------------------------------------ */

  public function Carrito() {
    /*     * ******************************************* */
    include __DIR__ . '/../Controllers/Home.php';
    $this->data = new Home();
    $data['header'] = $this->data->data_header('Carrito');
    $data['footer'] = $this->data->data_footer();
    /*     * ******************************************* */
    $empresa = $_SESSION['info_empresa'];
    $data['empresa'] = $empresa;

    $data['tpos_pago'] = $this->getTPDetalles();
    $meta = $empresa;
    $data['meta'] = array(
      'robots' => 'noindex, nofollow, noarchive',
      'title' => $meta['nombre_comercial'],
      'description' => substr(strClean(strip_tags($meta['descripcion'])), 0, 160),
      'keywords' => $meta['tags'],
      'url' => base_url(),
      'image' => $meta['url_logoImpreso'],
      'image:type' => explode('.', $meta['logo_imp'])[1],
      'og:type' => 'website'
    );
    $data["page_css"] = array();
    $data["page_functions_js"] = array('js/functions_admin');

    $this->views->getView("Carrito", $data);
  }

  /* ------------------------------------------------------------------------- */

  public function ProcesarPago() {
    if (!isset($_SESSION['arrCarrito']) || empty($_SESSION['arrCarrito'])) {
      header("Location:" . base_url());
    } else {
      /*       * ******************************************* */
      include __DIR__ . '/../Controllers/Home.php';
      $this->data = new Home();
      $data['header'] = $this->data->data_header('Procesar Pago');
      $data['footer'] = $this->data->data_footer();
      /*       * ******************************************* */
      $empresa = $_SESSION['info_empresa'];
      $data['empresa'] = $empresa;

      $data['tipos_pagos'] = $this->getTPDetalles();

      $meta = $empresa;
      $data['meta'] = array(
        'robots' => 'noindex, nofollow, noarchive',
        'title' => $meta['nombre_comercial'],
        'description' => substr(strClean(strip_tags($meta['descripcion'])), 0, 160),
        'keywords' => $meta['tags'],
        'url' => base_url(),
        'image' => $meta['url_logoImpreso'],
        'image:type' => explode('.', $meta['logo_imp'])[1],
        'og:type' => 'website'
      );

// las funciones de la pagina van de ultimo 
      $data["page_css"] = array();
      $data["page_functions_js"] = array('js/functions_admin','js/functions_carrito_procesar_pago');

      $this->views->getView("Procesarpago", $data);
    }
  }

  /* ----------------------------------------------------------------------------- */

  public function confirmarpedido() {
    if (empty($_SESSION['dataorden'])) {
      header("Location:" . base_url());
    } else {
      /*       * ******************************************* */
      include __DIR__ . '/../Controllers/Home.php';
      $this->data = new Home();
      $data['header'] = $this->data->data_header('Pedido Confirmado');
      $data['footer'] = $this->data->data_footer();
      /*       * ******************************************* */
      $empresa = $_SESSION['info_empresa'];
      $data['empresa'] = $empresa;

      $data['orden'] = $_SESSION['dataorden']['orden'];
      $data['transaccion'] = $_SESSION['dataorden']['transaccion'];

      $meta = $empresa;
      $data['meta'] = array(
        'robots' => 'noindex, nofollow, noarchive',
        'title' => $meta['nombre_comercial'],
        'description' => substr(strClean(strip_tags($meta['descripcion'])), 0, 160),
        'keywords' => $meta['tags'],
        'url' => base_url(),
        'image' => $meta['url_logoImpreso'],
        'image:type' => explode('.', $meta['logo_imp'])[1],
        'og:type' => 'website'
      );

      set_notificacion('pedido', $_SESSION['dataorden']['transaccion']);

      $this->views->getView("Confirmarpedido", $data);
// limpiamoa las variables de sesion para que esten disponibles para un nuevo pedido
      if (!empty('arrCarrito')) {
        unset($_SESSION['arrCarrito']);
      }
      if (!empty('dataorden')) {
        unset($_SESSION['dataorden']);
      }
      if (!empty('procesar_pago')) {
        unset($_SESSION['data_pedido_mp']);
      }
      if (!empty('arrCarrito')) {
        unset($_SESSION['arrCarrito']);
      }

      $idUser = intval($_SESSION['idUser']);

      session_regenerate_id(false);
      //sleep('2');
      sessionUser($idUser);
    }
  }

  /* consulta por costo de envio ============================================================================================================================ */

  public function getShipping() {
    $arrResponse = array(
      'shipping' => formatMoney($_SESSION['info_empresa']['costo_envio']),
    );
    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  /* procesar venta conatra entrega============================================================================================================================ */

  public function procesarVentaCE() {
//    
    $arrResponse = '';
    if ($_POST) {
      $datosPOST = $_POST;
      if ($datosPOST['tpopago'] != 'ce') {// valida que el metodo de pago sea el corecto
        echo json_encode(array('status' => false, 'msg' => 'no es posible validar el metodo de pago'), JSON_UNESCAPED_UNICODE);
      }
      $datosPOST['datajson'] = null;
      $datosPOST['transaccionid'] = empty($_POST['transaccionid']) ? null : $_POST['transaccionid'];
      $datosPOST['status'] = 'Pendiente';
      isset($_SESSION['idUser']) && $_SESSION['idUser'] > 0 ?
              $_SESSION['idUser'] :
              $this->insertClienteCarrito($_POST);
      $request_pedido = $this->procesoInsertarPedido($datosPOST); // en procesoInsertarPedido insertamos el pedido y retorna el id des pedido recien insertado

      $transaccionid = $request_pedido['idpedido'];
      $request_idpedido = $request_pedido['transaccionid'];
      /* =========================================== */
      $_SESSION['dataorden'] = array(// encriptamos el id de orden y de transaccion, para enviarlo en formato json,
        'orden' => $request_idpedido,
        'transaccion' => $transaccionid,
      );
      $arrResponse = array('status' => true);
      unset($_SESSION['arrCarrito']);
    }
    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  /* procesar venta transferencia bancaria============================================================================================================================ */

  public function validaridtransferencia($idtransferencia) {
    $request = $this->model->selectTransaccionId($idtransferencia);
    $retornar = $request != 0 ? false : true;
    echo json_encode(array('status' => $retornar), JSON_UNESCAPED_UNICODE);
  }

  public function procesarVentatb() {
    $arrResponse = '';
    if ($_POST) {
      $datosPOST = $_POST;
      if ($datosPOST['tpopago'] != 'tb') {// valida aprobacion de pago paypal y declaramos las variables a tratar en esta parte
        echo json_encode(array('status' => false, 'msg' => 'no es posible validar el metodo de pago'), JSON_UNESCAPED_UNICODE);
      }
      $datosPOST['datajson'] = null;
      $datosPOST['transaccionid'] = $_POST['transaccionid'];
      $datosPOST['status'] = 'Pendiente';

      $request_idpedido = $this->procesoInsertarPedido($datosPOST); // en procesoInsertarPedido insertamos el pedido y retorna el id des pedido recien insertado
      $transaccionid = $request_idpedido['idpedido'];
      $request_idpedido = $request_idpedido['transaccionid'];

      /* =========================================== */
      $_SESSION['dataorden'] = array(// encriptamos el id de orden y de transaccion, para enviarlo en formato json,
        'orden' => encript($request_idpedido),
        'transaccion' => encript($transaccionid),
      );
      $arrResponse = array('status' => true);
      unset($_SESSION['arrCarrito']);
    }
    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  /* ============================================================================================================================ */

  public function procesarVentaPP() {

    $arrResponse = '';
    if ($_POST) {
      $datosPOST = $_POST;
      if ($datosPOST['tpopago'] != 'pp') {// valida aprobacion de pago paypal y declaramos las variables a tratar en esta parte
        echo json_encode(array('status' => false, 'msg' => 'no es posible validar el metodo de pago'), JSON_UNESCAPED_UNICODE);
      }
      $datosPOST['datajson'] = null;
      $datosPOST['transaccionid'] = null;
      $datosPOST['status'] = 'Pendiente';

      $objPaypal = json_decode($datosPOST['datapay']); //decodificamos el objeto json y lo pasamos a una variable para su analisis 
      if (is_object($objPaypal)) {  // validamos que datapay sea un objeto.
        $datosPOST['datajson'] = $datosPOST['datapay']; // pasamos el objeto JSON a una variable para ser almacenados en la base de datos
        unset($datosPOST['datapay']);
        $datosPOST['transaccionid'] = $objPaypal->purchase_units[0]->payments->captures[0]->id; //captura el id de la transaccion para ser almacenados en la base de datos

        if ($objPaypal->status == "COMPLETED") {  // validamos el estado de la transaccion,
          $datosPOST['status'] = $objPaypal->purchase_units[0]->amount->value == number_format($datosPOST['total_monto'], 2, ".", "") ? 'Aprobado' : 'Pendiente';
        } else {
          $arrResponse = array('status' => false, 'msg' => 'El el pago no ha sido completado');
        }
      } else {
        $arrResponse = array('status' => false, 'msg' => 'Error en el procesamiento del pago');
      }

      $request_pedido = $this->procesoInsertarPedido($datosPOST); // en procesoInsertarPedido insertamos el pedido y retrona el id des pedido recien insertado
      /* =========================================== */
      $transaccionid = $request_pedido['transaccionid'];
      $request_idpedido = $request_pedido['idpedido'];

      $_SESSION['dataorden'] = array(// encriptamos el id de orden y de transaccion, para enviarlo en formato json,
        'orden' => encript($request_idpedido),
        'transaccion' => encript($transaccionid),
      );
      $arrResponse = array('status' => true);
      unset($_SESSION['arrCarrito']);
    } else {
      $arrResponse = array('status' => false, 'msg' => 'Datos no recibidos, no es posible procear el la solicirud');
    }

    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  /* MercadoPago ============================================================================================================================ */

  public function prefereciaMP() {
    //header('Content-Type: application/json; charset=utf-8');
    if ($_POST) {

      $_SESSION['data_pedido_mp'] = $_POST;
      $_SESSION['data_pedido_mp']['tpopago'] = 'mp';
      $carrito = $_SESSION['arrCarrito'];
      $data_mp = $this->getTPDetalles()['mp']['detalle'];

// SDK de Mercado Pago
      require_once("Librerias/vendor/autoload.php"); //require_once __DIR__ . '/vendor/autoload.php';
      MercadoPago\SDK::setAccessToken($data_mp['mpAccesTocken']); // Agrega credenciales
      $preference = new MercadoPago\Preference(); // Crea un objeto de preferencia

      $prod_carrito = array(); // productos del carrito creamos un array vacio para agregar los productos 
      for ($i = 0; $i < count($carrito); $i++) {
        $item = new MercadoPago\Item(); // generamos un nuevo objeto Item, por cada producto a cobrar, y le asignamos las propiedades correspondientes
        $item->id = $carrito[$i]['idproducto'];
        $item->title = $carrito[$i]['nombre'];
        $item->description = substr(strClean(strip_tags($carrito[$i]['nombre'])), 0, 100);
        $item->picture_url = $carrito[$i]['img'];
        $item->quantity = $carrito[$i]['cantidad'];
        $item->currency_id = $data_mp['mpCurrency'];
        $item->unit_price = $carrito[$i]['precio'];

        array_push($prod_carrito, $item); //este objeto Item, lo agregamos en el array
      }

      $preference->items = $prod_carrito; // agregamos el array con los Items a cobrar a la preferencia

      $preference->back_urls = array(//URL de Retorno al terminar la oeracion
        "success" => base_url() . "carrito/procesarventamp",
        "failure" => base_url() . "carrito/procesarventamp",
        "pending" => base_url() . "carrito/procesarventamp"
      );
      $preference->auto_return = "approved"; //approved


      if (isset($_POST['metodoEntrega']) && $_POST['metodoEntrega'] === 'entrega') {
        $costo = floatval($_SESSION['info_empresa']['costo_envio']); //floatval($_POST['shipping_monto']); // antes de enviar el monto del envio 

        $ship = new MercadoPago\Shipments(); // Generamos un Shipments para agregar el costo de envio
        $ship->mode = 'not_specified'; // en este caso declaramos el modo de envio como libre
        $ship->cost = $costo; // declaras el valor de envio a cobrar
        $preference->shipments = $ship; //agegamos a la preferencia el objeto Shipments con las propiedades de cobro de envio
        //var_dump($ship);
      }
      $preference->save();

      $response = array(
        'id' => $preference->id,
      );
      echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
  }

  /* ------------------------------------------------------------------------------------------------------------------------------- */

  public function procesarVentaMP() {
    $datavent = $_SESSION['data_pedido_mp'];
    $datosMP = $_GET;
    $datajson = json_encode($datosMP);

    $datosPOST['transaccionid'] = $datosMP['id'];
    $datosPOST['datajson'] = json_encode($_GET);

    $datosPOST['subtotal_monto'] = $datavent['subtotal'];
    $datosPOST['shipping_monto'] = $datavent['envio'];
    $datosPOST['total_monto'] = $datavent['subtotal'] + $datavent['envio'];

    $datosPOST['metodoEntrega'] = $datavent['metodoEntrega'];
    $datosPOST['tpopago'] = $datavent['tpopago'];

    $datosPOST['direccion'] = $datavent['direccion'];
    $datosPOST['ciudad'] = $datavent['ciudad'];
    $datosPOST['status'] = 'Pendiente';

    if ($datosMP['status'] == 'COMPLETED') {// valida aprobacion de pago paypal 
      $datosPOST['status'] = 'Aprobado';
    }

    $request_idpedido = $this->model->insertPedido(
        $transaccionid,
        $datajson,
        $personaid,
        $subtotal,
        $costo_envio,
        $monto,
        $tipopagoid,
        $direccionenvio,
        $status);

    if ($request_idpedido > 0) {
      foreach ($_SESSION['arrCarrito'] as $producto) { // una ves aprobada la transacion , repasamos el arrCarrito e insertamos cada uno de los productos en la tabla detalle_pedido
        $pedidoid = $request_idpedido;
        $productoid = $producto['idproducto'];
        $precio = $producto['precio'];
        $cantidad = $producto['cantidad'];

        $this->model->insertPedido($pedidoid, $productoid, $precio, $cantidad);
      }

      /* enviamos el mail al cliente =============================== */
      $infoOrden = $this->model->getPedido($request_idpedido);
      $empresa = $_SESSION['info_empresa'];

      $infoOrden['empresa'] = $empresa;
      $bodyMail = getFile("Template/Email/Confirmar_orden", $infoOrden);

      $nombreUsuario = $_SESSION['userData']['nombres'] . ' ' . $_SESSION['userData']['apellidos'];
      $UserMail = $_SESSION['userData']['email_user'];
      $arrDataEmail = array(// preparamos el array con los datos requeridos
        'empresa' => $empresa, // un array con los datos de la empresa, y configuracion
        'nombreUsuario' => $nombreUsuario, //nombre del usuario
        'email' => $UserMail, //email del usuario (email destino)
        'asunto' => 'Se ha creado la orden Nro:' . str_pad($request_idpedido, 5, "0", STR_PAD_LEFT),
      );

      sendEMail($arrDataEmail, $bodyMail);
      /* =========================================== */
      $_SESSION['dataorden'] = array(// encriptamos el id de orden y de transaccion, para enviarlo en formato json,
        'orden' => $request_idpedido,
        'transaccion' => $transaccionid,
      );
      $arrResponse = array('status' => true);
      unset($_SESSION['arrCarrito']);
      header("Location:" . base_url() . 'carrito/confirmarpedido');
    } else {
      $arrResponse = array('status' => false, 'msg' => 'El pedido no fue procesado 1');
    }
    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  /* ============================================================================================================================ */

  /* ============================================================================================================================ */
  /* Funciones============================================================================================================================ */

  private function getTPDetalles() {
    $TPDetalles = array(); // declaramos la variable conse almacenaran tipos de pago
    $arrTP = $this->model->selectTiposPagosT(); // hacemos una consulta por los tipos de pago
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

  /* Insertar cliente cuando no esta registrado ============================================================================================================================ */

  private function insertClienteCarrito($data) {
    $meta = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR']));

    $strNombre = ucwords(strClean($data['nombre']));
    $strApellido = ucwords(strClean($data['apellido']));

    $intTelefono = intval(strClean($data['telefono']));
    $strEmail = strtolower(strClean($data['email']));
    $strSexo = 'I';
    $strDireccion = ucwords(strClean($data['direccion']));
    $strLocalidad = ucwords(strClean($data['ciudad']));
    $strCiudad = ucwords(strClean($data['ciudad']));
    $pais = $meta['geoplugin_countryName'];

    $idTpoRol = 2; // en los clientes el rol por defecto es 2

    $strPasswordEncript = hash("SHA256", passGenerator());

    $idUser = $this->model->insertClienteCarr($strNombre, $strApellido, $intTelefono, $strEmail, $strSexo,
        $strDireccion, $strLocalidad, $strCiudad, $pais, $strPasswordEncript, $idTpoRol);
    $idUser = intval($idUser);
    $_SESSION['idUser'] = $idUser;

    return $idUser;
  }

  /* Insertar pedido------------------------------------------------------------------------------ */

  private function procesoInsertarPedido($datosPOST) {
    try {
      /* debido a que el proceso de insertar un pedido es practicamente el mismo en todos los metodos de entrega, 
       * se creo un preceso de insertar pedido general adaptable a los distintos metodos de pago, antes de insertar el pedido los daos seran tratados por el metodo que lo invoca */
      $transaccionid = $datosPOST['transaccionid'];
      $datajson = isset($datosPOST['datajson']) && $datosPOST['datajson'] != '' ? $datosPOST['datajson'] : null;

      $personaid = intval($_SESSION['idUser']);
      $nombreUsuario = $datosPOST['nombre'] . ' ' . $datosPOST['apellido'];
      $UserMail = $datosPOST['email'];

      $subtotal = $datosPOST['subtotal_monto'];
      $costo_envio = floatval($datosPOST['shipping_monto']);
      $monto = $datosPOST['total_monto'];

      $metodoEntrega = $datosPOST['metodoEntrega'];
      $tipopagoid = intval($this->model->idTipoPago($datosPOST['tpopago']));
      $direccionenvio = $metodoEntrega == 'retiro' ? '' : strClean($datosPOST['direccion']) . ', ' . strClean($datosPOST['ciudad']);
      $status = $datosPOST['status'];

      if (!empty($_SESSION['arrCarrito'])) { // si arrCarrito existe en sesion , calculamos el subtotal
        foreach ($_SESSION['arrCarrito'] as $prod) {
          $subtotal += $prod['cantidad'] * $prod['precio'];
        }
        $monto = $subtotal + $costo_envio;     // sumamos el costo envio para tener un precio final          
// crear pedido 

        $request_idpedido = $this->model->insertPedido(
            $transaccionid,
            $datajson,
            $personaid,
            $subtotal,
            $costo_envio,
            $monto,
            $metodoEntrega,
            $tipopagoid,
            $direccionenvio,
            $status);

        if ($request_idpedido > 0) {// repasamos el arrCarrito e insertamos cada uno de los productos en la tabla detalle_pedido
          foreach ($_SESSION['arrCarrito'] as $producto) {
            $productoid = $producto['idproducto'];
            $precio = ($producto['precio']);
            $cantidad = intval($producto['cantidad']);

            $this->model->insertDetallePedido($request_idpedido, $productoid, $precio, $cantidad);
          }
          /* enviamos el mail al cliente =============================== */

          $infoOrden = $this->model->getPedido($request_idpedido);
          $empresa = $_SESSION['info_empresa'];

          $infoOrden['empresa'] = $empresa;
          $bodyMail = getFile("Template/Email/Confirmar_orden", $infoOrden);

          $arrDataEmail = array(// preparamos el array con los datos requeridos
            'empresa' => $empresa, // un array con los datos de la empresa, y configuracion
            'nombreUsuario' => $nombreUsuario, //nombre del usuario
            'email' => $UserMail, //email del usuario (email destino)
            'asunto' => 'Se ha creado la orden Nro:' . str_pad("$request_idpedido", 5, "0", STR_PAD_LEFT),
          );

          sendEMail($arrDataEmail, $bodyMail);
          return array('idpedido' => $request_idpedido, 'transaccionid' => $transaccionid);
          if (isset($_SESSION['data_pedido_mp'])) {
            unset($_SESSION['data_pedido_mp']);
          }
        }
      } else {
        $arrResponse = array('status' => false, 'msg' => 'No hay productos en el Carrito, no es posible procear el la solicirud');
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
      }
    } catch (Exception $e) {
      // echo 'Excepción capturada: ', $e->getMessage(), "\n";
      $arrResponse = array('status' => false, 'msg' => $e->getMessage());
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
  }

}
