<?php

class CarritoModel extends Mysql {

  //put your code here
  function __construct() {
    parent::__construct();
  }

  /* Tipos de pago ------------------------------------------------------------------------- */

  function selectTransaccionId($id) {
    return $this->select("SELECT idpedido FROM pedido WHERE transaccionid = '{$id}' ");
  }

  function selectTiposPagosT() {
    return $this->select_all('SELECT * FROM tipopago WHERE  status = 1 ');
  }

  function selectTiposPagoDetallesT($tipopagoid) {
    return $this->select_all("SELECT * FROM tipopago_detalle WHERE tipopagoid = $tipopagoid ");
  }

  function idTipoPago($tipopago) {
    return $this->select("SELECT idtipopago FROM tipopago WHERE tipopago = '{$tipopago}'")['idtipopago'];
  }

  /* TClientes---------------------------------- */

  function insertPedido(string $transaccionid = NULL, string $datajson = NULL, int $personaid,
      float $subtotal, float $costo_envio, float $monto, string $metodoEntrega, int $tipopagoid, string $direccionenvio, string $status) {
    $query_insert = "INSERT INTO pedido (transaccionid, datajson, personaid, subtotal,
                                          costo_envio, monto, metodo_entrega, tipopagoid,
                                          direccionenvio, status) VALUES  (?,?,?,?,?,?,?,?,?,?)";
    $arrData = array(
      $transaccionid, $datajson,
      $personaid, $subtotal,
      $costo_envio, $monto,
      $metodoEntrega, $tipopagoid,
      $direccionenvio, $status);

    return $this->insert($query_insert, $arrData);
  }

  function insertDetallePedido($pedidoid, int $productoid, float $precio, int $cantidad) {
    $query_insert = "INSERT INTO pedido_detalle ( pedidoid,	productoid, precio, cantidad ) VALUES  (?,?,?,?)";
    $arrData = array($pedidoid, $productoid, $precio, $cantidad);
    return $this->insert($query_insert, $arrData);
  }

  function getPedido($idpedido) {
    $request = array();

    $sql_ped = "SELECT p.idpedido, p.referenciadecobro, p.transaccionid, 
                 p.personaid, pe.nombres, pe.apellidos, pe.email_user, pe.telefono,
                 p.fecha, p.subtotal, p.costo_envio, p.monto, p.tipopagoid, t.tipopago, 
                 p.direccionenvio, p.status 
                 FROM pedido as p INNER JOIN tipopago t INNER JOIN persona pe 
                 ON p.tipopagoid = t.idtipopago and p.personaid = pe.idpersona 
                 WHERE p.idpedido = {$idpedido}";

    $request_ped = $this->select($sql_ped);
    if (count($request_ped) > 0) {
      $sql_deta_ped = "SELECT dp.pedidoid, dp.productoid, p.nombre, p.ruta,
                              dp.precio, dp.cantidad
                              FROM pedido_detalle as dp
                              INNER JOIN producto p
                              ON dp.productoid = p.idproducto
                              WHERE dp.pedidoid = {$idpedido}";
      $request_deta_ped = $this->select_all($sql_deta_ped);

      $request = array('pedido' => $request_ped, 'detalle' => $request_deta_ped);
    }
    return $request;
  }

  function insertClienteCarr(string $nombre, string $apellido, int $telefono = null, string $email,
      string $sexo, string $direccion = null, string $localidad, string $ciudad, string $pais = null, string $password,
      int $idTpoRol, string $oauth_provider = null, string $oauth_uid = null, string $img = null) {

    //consultamos la existencia de una identificacion o imail duplicado    or identificacion ='{$this->strIdentificacion}'
    $idpersona = $this->select("SELECT idpersona FROM persona WHERE email_user = '{$email}' ");
    if (empty($idpersona)) {
      /* si la consulta es nul  entonce insertamos el Usuario */
      $query_insert = "INSERT INTO persona (nombres, apellidos, telefono, email_user, sexo,direccionfiscal, localidad,
                        ciudad,pais, password, rolid,oauth_provider,oauth_uid,img) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $arrData = array($nombre, $apellido, $telefono, $email, $sexo, $direccion, $localidad,
        $ciudad, $pais, $password, $idTpoRol, $oauth_provider, $oauth_uid, $img);
      $return = $this->insert($query_insert, $arrData);
    } else {
      $return = $idpersona;
    }

    return $return;
  }

}
