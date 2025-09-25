<?php

declare(strict_types=1);

class Pedidos extends Controllers {

  private $idModul = 5;

  public function __construct() {
    if (empty($_SESSION['login'])) {
      require_once "Login.php";
      $login = new Login();
      $login->Login();
      exit();         //header('location:' . base_url() . 'login');
    }
    parent::__construct();
  }

  public function Pedidos() {
    //ejecuta el contenido del archivo home
    //echo 'Mensaje desde el controlador home';

    $data["modulo"] = $this->idModul;
    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1) {

      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'Pedidos';
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
        "js/functions_pedidos.js");

      $this->views->getView("Pedidos", $data);
    } else {
      header('location:' . base_url() . 'dashboard');
      exit();
    }
  }

  /* ======================================================================================================================================== */

  public function orden($idpedido) {
//    if (!is_numeric($idpedido)) {
//      header("Location:" . base_url() . 'pedidos');
//      exit();
//    }
    $idpedido = intval($idpedido);

    $data["modulo"] = $this->idModul;

    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1 && $idpedido > 0) {

      $data['pedido'] = $this->model->selectPedidoId($idpedido); //consultamos la tabla y traemos todos los registros 



      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'Orden';
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
      $data["page_functions_js"] = array("functions_pedidos.js");

      $this->views->getView("Orden", $data);


      $notificacion->updateNotificacionID('pedido', $data['pedido']['pedido']['idpedido'], 1); //$_SESSION['userData'];    
    } else {
      header('location:' . base_url() . 'dashboard');
      exit();
    }
  }

  /* ======================================================================================================================================== */

  public function transaccion($idpedido) {

    $data["modulo"] = $this->idModul;
    $idtransaccion = intval($idpedido);

    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1 && $idpedido != "") {

      $data['pedido'] = $this->model->selectPedidoId($idtransaccion); //consultamos la tabla y traemos todos los registros 
      $data['transaccion'] = $this->model->selectTransaccionPaypal($idtransaccion); //consultamos la api de paypal  y traemos los datos de la transaccion 

      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'Transaccion';
      $data['page_title'] = $data['page_name'];
      $data['logo_desktop'] = $empresa['url_logoMenu'];
      $data['shortcut_icon'] = $empresa['url_shortcutIcon'];
      /*       * ******************************************* */
      include __DIR__ . '/../Controllers/Notificacion.php';
      $notificacion = new Notificacion();
      $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
      /*       * ******************************************* */

      // las funciones de la pagina van de ultimo 
      $data["page_css"] = array("");
      $data["page_functions_js"] = array("functions_pedidos.js");

      $this->views->getView("Transaccion", $data);
    } else {
      header('location:' . base_url() . 'dashboard');
      exit();
    }
  }

  /* ======================================================================================================================================== */

  public function getPedido($idpedido) {

    $idpedido = intval($idpedido);
    if ($idpedido > 0) {// si el dato recicbido no es un numero, de devuelve un error 'id invalido 
      $request_pedido = $this->model->selectPedidoId($idpedido);
      if (is_array($request_pedido)) { // el modal devuelve un array scon los datos del pedido , en caso de que el usuario sea cliente y el pedido no sea suyo devolvera un string 'Pedido_de_otro_cliente' 
        $Modal = 'Template/Modals/modalPedido';
        $htmlModal = getFile($Modal, $request_pedido);

        include __DIR__ . '/../Controllers/Notificacion.php';
        $notificacion = new Notificacion();
        $notificacion->updateNotificacionID('pedido', $request_pedido['pedido']['idpedido'], 1); //$_SESSION['userData'];    

        $arrResponse = array("status" => true, 'html' => $htmlModal);
      } else {
        $arrResponse = array("status" => false, 'msg' => 'Este pedido pertenece a otro cliente');
      }
    } else {
      $arrResponse = array("status" => false, 'msg' => 'Transaccion invalida');
    }



    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    exit();
  }

  /* ======================================================================================================================================== */

  public function setPedido() {
    if ($_POST) {
      if ($_SESSION['userPermiso'][$this->idModul]['ver'] == 1 && $_SESSION['userData']['idrol'] != 2) {
        $idpedido = !empty($_POST['idPedido']) ? intval($_POST['idPedido']) : "";
        $estado = !empty($_POST['listEstado']) ? strClean($_POST['listEstado']) : "";
        $tipoPago = !empty($_POST['listTpoPago']) ? strClean($_POST['listTpoPago']) : "";
        $transaccion = !empty($_POST['txtTransaccion']) ? strClean($_POST['txtTransaccion']) : "";

        if ($idpedido == "") {
          $arrResponse = array("status" => false, 'msg' => 'idtransaccion incorrecta');
        } else {
          if ($tipoPago == "") {
            if ($estado == "") {
              $arrResponse = array("status" => false, 'msg' => 'estado incorrecto');
            } else {
              $requestPedido = $this->model->updatePeido($idpedido, '', '', $estado);
              if ($requestPedido) {
                $arrResponse = array("status" => true, 'msg' => 'Pedido Actualizado');
              } else {
                $arrResponse = array("status" => false, 'msg' => 'Pedido no actualizado');
              }
            }
          } else {
            if ($transaccion == "" or $estado == '') {
              $arrResponse = array("status" => false, 'msg' => 'trans,estado incorrecto');
            } else {
              $requestPedido = $this->model->updatePeido($idpedido, $transaccion, $tipoPago, $estado);
              if ($requestPedido) {
                $arrResponse = array("status" => true, 'msg' => 'Pedido Actualizado');
              } else {
                $arrResponse = array("status" => false, 'msg' => 'Pedido no actualizado');
              }
            }
          }
        }
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    exit();
  }

  /* ======================================================================================================================================== */

  //DEVUELVE UN ARRAY CON LOS DATOS DE ROLES Y BOTONES DE OPCION BOOSTRAP PARA INSERTAR EN DATATABLE
  public function getPedidos() {
    $idUser = "";
    if ($_SESSION['userData']['rolid'] == 2) { // si el id de la sesion es == a 2 que pertenece a cliente
      $idUser = $_SESSION['userData']['idpersona'];
    }

    include __DIR__ . '/../Controllers/Notificacion.php';
    $notificacion = new Notificacion();

    $data = $this->model->selectPedidos($idUser); //consultamos la tabla y traemos todos los registros 
    for ($i = 0; $i < count($data); $i++) {

      $id_not = $notificacion->getIdNotificacionesTipo('pedido', $data[$i]['idpedido']); //$_SESSION['userData'];    

      $data[$i]['status'] = $id_not > 0 ? '<span class="badge bg-success font-size-14 me-1">' . $data[$i]['status'] . '</span>' : $data[$i]['status'];

      $data[$i]['transaccion'] = $data[$i]['referenciadecobro']; // unificamos los campos de identificacion de cobro,
      if ($data[$i]['transaccionid'] != "") {
        $data[$i]['transaccion'] = $data[$i]['transaccionid'];
      }

      $data[$i]['monto'] = formatMoney($data[$i]['monto']);

      $btnView = '<a href="' . base_url() . 'pedidos/orden/' . $data[$i]['idpedido'] . '" target="_blank" class="btn btn-info m-1 " title="Ver Pedido" > <i class="fa fa-eye"></i></a>';
      $btnPdf = '<a class="btn btn-danger m-1 " href="' . base_url() . 'factura/generarFactura/' . $data[$i]['idpedido'] . '" target="_blanck" title="Generar PDF" ><i class="far fa-file-pdf"></i></a>';
      switch ($data[$i]['tipopago']) {
        case 'ce':$btnMethodPay = '<button class="btn btn-primary m-1 " title="editar transaccion Efectivo" type="button" onClick ="fntEditPedido(' . $data[$i]['idpedido'] . ')" > <i class="far fa-money-bill-alt"></i></button> ';
          break;
        case 'tb':$btnMethodPay = '<button class="btn btn-info m-1 "  title="Ver transaccion PayPal" type="button" ><i class="fas fa-exchange-alt"></i></button>';
          break;
        case 'pp':$btnMethodPay = '<a title="Ver detalle de transaccion con PayPal"  href="' . base_url() . 'pedidos/transaccion/' . $data[$i]['transaccionid'] . '"target="_blank" class="btn btn-info m-1" ><i class="fab fa-paypal"></i></button>';
          break;
        case 'mp':$btnMethodPay = '<button class="btn btn-info m-1 " title="Ver transaccion Mercadopago" type="button" ><i class="fas fa-credit-card"></i></button>';
          break;
      }
      $data[$i]['options'] = '<div class= "text-center">' . $btnView . ' ' . $btnPdf . ' ' . $btnMethodPay . ' </div>';
    }
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));
  }

  /* ======================================================================================================================================== */

  public function getTransaccion(string $transaccion) {

    if ($_SESSION['userData']['rolid'] != 2) { // si el idrol de la sesion es == a 2 que pertenece a cliente
      if ($transaccion == '') {
        $arrResponse = array("status" => false, 'msg' => 'Transaccion Incorrecta');
      } else {
        $trans = strClean($transaccion);
        $transPaypal = $this->model->selectTransaccionPaypal($trans);
        if (empty($transPaypal)) {
          $arrResponse = array("status" => false, 'msg' => 'Transaccion no disponible');
        } else {
          $modal = 'Template/Modals/modalReembolso';
          $htmlModal = getFile($modal, $transPaypal);
          $arrResponse = array("status" => true, 'html' => $htmlModal);
        }
      }
    }
    echo json_encode($arrResponse);
    exit();
  }

  /*  ======================================================================================================================================== */

  public function reenviarEmail($idpedido) {

    $idpedido = intval($idpedido);

    $data["modulo"] = $this->idModul;

    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1 && $idpedido > 0) {

      $infoOrden = $this->model->selectPedidoId($idpedido);

      $empresa = $_SESSION['info_empresa'];

      $infoOrden['empresa'] = $empresa;
      $bodyMail = getFile("Template/Email/Confirmar_orden", $infoOrden);

      $nombreUsuario = $_SESSION['userData']['nombres'] . ' ' . $_SESSION['userData']['apellidos'];
      $UserMail = $_SESSION['userData']['email_user'];
      $arrDataEmail = array(// preparamos el array con los datos requeridos
        'empresa' => $empresa, // un array con los datos de la empresa, y configuracion
        'nombreUsuario' => $nombreUsuario, //nombre del usuario
        'email' => $UserMail, //email del usuario (email destino)
        'asunto' => 'Se ha creado la orden Nro:' . str_pad($idpedido, 5, "0", STR_PAD_LEFT),
      );

      $send_mail = sendEMail($arrDataEmail, $bodyMail);
      if ($send_mail == 1) {
        $arrResponse = array("status" => true, 'msg' => 'Mail reenviado');
      } else {
        $arrResponse = array("status" => valse, 'msg' => 'mail no enviado' . $send_mail);
      }
    }

    echo json_encode($arrResponse);
    exit();
  }

  /* paypal ======================================================================================================================================== */

  public function setReembolso() {

    if ($_POST) {
      if ($_SESSION['userData']['rolid'] != 2) {// si el idrol de la sesion es == a 2 que pertenece a cliente
        $transaccion = strClean($_POST['idtransaccion']);
        $observacion = strClean($_POST['observacion']);
        $reembolsoPaypal = $this->model->reembolsoPaypal($transaccion, $observacion);

        if ($reembolsoPaypal) {
          $arrResponse = array("status" => true, 'msg' => 'Reembolso procesado');
        } else {
          $arrResponse = array("status" => false, 'msg' => 'No es posible realizar el reembolso');
        }
      } else {
        $arrResponse = array("status" => false, 'msg' => 'No es posible completar su solicitud, comuniquese con el administrador');
      }
    }
    echo json_encode($arrResponse);
    exit();
  }

  /* ======================================================================================================================================== */
}
