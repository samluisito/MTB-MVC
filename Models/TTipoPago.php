<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

trait TTipoPago
{
    private $con;

    public function selectTransaccionId($id)
    {
        $this->con = new Mysql();
        return $this->con->select("SELECT idpedido FROM pedido WHERE transaccionid = '{$id}' ");
    }

    public function selectTiposPagosT()
    {
        $this->con = new Mysql();
        return $this->con->select_all('SELECT * FROM tipopago WHERE  status = 1 ');
    }

    public function selectTiposPagoDetallesT($tipopagoid)
    {
        $this->con = new Mysql();
        $sql = "SELECT * FROM tipopago_detalle WHERE tipopagoid = $tipopagoid ";
        return $this->con->select_all($sql);
    }

    public function idTipoPago($tipopago)
    {
        $this->con = new Mysql();
        return $this->con->select("SELECT idtipopago FROM tipopago WHERE tipopago = '{$tipopago}'")['idtipopago'];
    }
}