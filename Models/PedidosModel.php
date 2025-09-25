<?php

declare(strict_types=1);

class PedidosModel extends Mysql {

  //Pedidos
  public $intIdProducto;
  public $strNomProducto;
  public $strDescripcion;
  public $intCodigo;
  public $intCategoriaId;
  public $intPrecio;
  public $intStock;
  public $strRuta;
  public $intStatus;
  public $strImagen;

  public function __construct() {
    parent::__construct();
  }

  /* =============================================================================================================================================== */

  public function selectPedidos($idUser) {
    $where = $idUser != "" ? "WHERE personaid = {$idUser}" : "";
    //EXTRAE PRODUCTOS    p.descripcion,personaid
    return $this->select_all("SELECT  p.idpedido,
                        p.referenciadecobro,
                        p.transaccionid,
                        DATE_FORMAT(p.fecha,'%d/%m/%y') as fecha,
                        p.monto,
                        p.tipopagoid,
                        t.tipopago,
                        t.nombre_tpago,
                        p.status
                FROM pedido p INNER JOIN tipopago t
                on p.tipopagoid = t.idtipopago {$where} ORDER BY p.idpedido DESC ");
  }

  /* =============================================================================================================================================== */

  public function selectPedidoId($idpedido) {
    if (is_numeric($idpedido)) {
      //echo "La cadena $idpedido consiste completamente de dígitos.\n";
      $recuest_idPer = $this->select("SELECT personaid FROM pedido WHERE idpedido = {$idpedido}"); // validamos que el id pedido exista, y extraemos el idpersona
    } else {
      //echo "La cadena $idpedido no consiste completamente de dígitos.\n";
      $recuest_idPer = $this->select("SELECT personaid, idpedido  FROM pedido WHERE transaccionid = '{$idpedido}'"); // validamos que el id pedido exista, y extraemos el idpersona

      $idpedido = $recuest_idPer ['idpedido'];
    }
    if (empty($recuest_idPer)) {     //si el resulatado de la consulta es null retornamos pedido inexistente, de lo contrario continuamos           
      $recuest = "pedido_no_existe";
    } else {
      $recuest_idPer = $recuest_idPer ['personaid'];
      if ($_SESSION['userData']['rolid'] == 2) {      // si el rol del usuario logueado es de cliente, validamos que idpersona del pedido corespoda con el idpersona de la sesion
        if ($_SESSION['userData']['idpersona'] != $recuest_idPer) {     // si no corresponde retornamos Pedido_de_otro_cliente
          return "Pedido_de_otro_cliente";
          exit();
        }
      }
      /* extraemos el pedido--------------------------- */
//            $sql_ped = "SELECT p.idpedido, 
//                p.referenciadecobro, 
//                p.transaccionid, 
//                p.personaid, 
//                DATE_FORMAT(p.fecha, '%d/%m/%y') as fecha, 
//                p.subtotal, 
//                p.costo_envio, 
//                p.monto, 
//                p.tipopagoid, 
//                t.tipopago, 
//                p.direccionenvio, 
//                p.status 
//                 
//                 FROM pedido as p INNER JOIN tipopago t 
//                 ON p.tipopagoid = t.idtipopago
//                 WHERE p.idpedido = {$idpedido}";


      $sql_ped = "SELECT p.idpedido, 
                    p.referenciadecobro, 
                    p.transaccionid, 
                    p.personaid, 
                    pe.nombres, pe.apellidos, pe.email_user, pe.telefono,
                    p.fecha, 
                    p.subtotal, 
                    p.costo_envio, 
                    p.monto, 
                    p.tipopagoid, 
                    t.tipopago, 
                    p.direccionenvio, 
                    p.status 
                    FROM pedido as p INNER JOIN tipopago t INNER JOIN persona pe 
                    ON p.tipopagoid = t.idtipopago 
                    and p.personaid = pe.idpersona 
                    WHERE p.idpedido = {$idpedido}";
      $recuest_pedido = $this->select($sql_ped);

      /* extraemos los datos del usuario que realizo el pedido--------------------------- */
      $sql_per = "SELECT  idpersona,
                            identificacion,
                            nombres,
                            apellidos,
                            telefono,
                            email_user,
                            nit,
                            nombrefiscal,
                            direccionfiscal
                    FROM persona WHERE idpersona = {$recuest_pedido['personaid']}";
      $recuest_persona = $this->select($sql_per);
      /* extraemos el detalle del pedido--------------------------- */

      $sql_det = "SELECT  dp.pedidoid,
                            dp.productoid,
                            p.nombre,
                            p.ruta,
                            dp.precio,
                            dp.cantidad
                            FROM pedido_detalle as dp
                            INNER JOIN producto p
                            ON dp.productoid = p.idproducto
                            WHERE dp.pedidoid = {$recuest_pedido['idpedido']}";

      $recuest_detalle = $this->select_all($sql_det);

      $tipospago = $this->select_all('SELECT * FROM tipopago');
      $recuest = array(
        'usuario' => $recuest_persona,
        'pedido' => $recuest_pedido,
        'detalle' => $recuest_detalle,
        'tiposdepago' => $tipospago
      );
    }

    return $recuest;
  }

  /* =============================================================================================================================================== */

  public function selectTransaccionPaypal(string $transaccionid) {
    $sql_usuario = '';
    if ($_SESSION['userData']['rolid'] == '2') {
      $personaid = $_SESSION['userData']['idpersona'];
      $sql_usuario = "AND personaid = '{$personaid}'";
    }

    $objData = array();
    $sql = "SELECT `datajson`,personaid FROM `pedido` WHERE `transaccionid`= '{$transaccionid}' $sql_usuario";

    $request = $this->select($sql);

    if (!empty($request)) {

      $objData = json_decode($request['datajson']);

      //$urlTansaccion = $objData->purchase_units[0]->payments->captures[0]->links[0]->href;
      $urlTansaccion = $objData->links[0]->href;

      //$urlOrden = $objData->purchase_units[0]->payments->captures[0]->links[2]->href;
      $urlOrden = $urlTansaccion;
      $objTransaccion = curlConectionGet($urlOrden, "application/json", getTokenPayPal());

      return $objTransaccion;
    } else {
      return 0;
    }
  }

  /* =============================================================================================================================================== */

  public function reembolsoPaypal(string $transaccion, string $observacion) {
    $response = false;
    if ($_SESSION['userData']['rolid'] != '2') {
      $sql = "SELECT idpedido, datajson FROM pedido WHERE transaccionid = '{$transaccion}'";
      $requestPedido = $this->select($sql);

      if (!empty($requestPedido)) {

        $objData = json_decode($requestPedido['datajson']);

        $url_reembolso = $objData->purchase_units[0]->payments->captures[0]->links[1]->href;

        $obj_reembolso = curlConectionPost($url_reembolso, "application/json", getTokenPayPal());

        if (isset($obj_reembolso->status) && $obj_reembolso->status == 'COMPLETED') {

          $idpedido = $requestPedido['idpedido'];
          $itransaccion = $obj_reembolso->id;
          $jsonData = json_encode($obj_reembolso);
          $txtobservacion = $observacion;
          $status = $obj_reembolso->status;

          $sql_insert = "INSERT INTO pedido_reembolso (pedidoid, idtransaccion, datos_reembolso, observacion, estado) VALUES (?,?,?,?,?)";
          $arrData_insert = array($idpedido, $itransaccion, $jsonData, $txtobservacion, $status);

          $request_insert = $this->insert($sql_insert, $arrData_insert);

          if ($request_insert > 0) {

            $sql_update = "UPDATE pedido SET status = ? WHERE idpedido = $idpedido";

            $arrData_update = array('Reembolsado');
            $request_update = $this->update($sql_update, $arrData_update);
            $response = true;
          }
        }
      }
      return $response;
      exit();
    }
  }

  /* =============================================================================================================================================== */

  public function updatePeido(int $idpedido, $transaccion = null, $tipoPago = null, string $estado) {
    if ($transaccion == null) {
      $sql = "UPDATE pedido SET status = ? WHERE idpedido = $idpedido ";
      $arrData = array($estado);
    } else {
      $tipopago = $this->select("SELECT idtipopago FROM tipopago WHERE tipopago = '{$tipoPago}'");
      $tipopagoid = $tipopago['idtipopago'];
      $sql = "UPDATE pedido SET referenciadecobro = ?, tipopagoid =?, status = ? WHERE idpedido = $idpedido ";
      $arrData = array($transaccion, $tipopagoid, $estado);
    }

    $requestUpdate = $this->update($sql, $arrData);
    return $requestUpdate;
  }

  /* =============================================================================================================================================== */
}
