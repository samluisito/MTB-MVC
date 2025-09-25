<?php

declare(strict_types=1);

class PruebasModel extends Mysql {

  public function __construct() {
    parent::__construct();
  }

  public function selectTransaccionId($id) {

    return $this->select("SELECT idpedido FROM pedido WHERE transaccionid = '{$id}' ");
  }

  public function selectTiposPagosT() {

    return $this->select_all('SELECT * FROM tipopago WHERE  status = 1 ');
  }

  public function selectTiposPagoDetallesT($tipopagoid) {

    $sql = "SELECT * FROM tipopago_detalle WHERE tipopagoid = $tipopagoid ";
    return $this->select_all($sql);
  }

  public function idTipoPago($tipopago) {

    return $this->select("SELECT idtipopago FROM tipopago WHERE tipopago = '{$tipopago}'")['idtipopago'];
  }

}
