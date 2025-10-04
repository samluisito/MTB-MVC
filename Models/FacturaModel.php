<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class FacturaModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectPedidoId($idpedido)
    {
        $idpedido_original = $idpedido;
        $request_idPer = null;

        if (is_numeric($idpedido)) {
            $request_idPer = $this->select("SELECT personaid FROM pedido WHERE idpedido = ?", [$idpedido]);
        } else {
            $request_idPer = $this->select("SELECT personaid, idpedido FROM pedido WHERE transaccionid = ?", [$idpedido]);
            if ($request_idPer) {
                $idpedido = $request_idPer['idpedido'];
            }
        }

        if (empty($request_idPer)) {
            return "pedido_no_existe";
        }

        $personaid_pedido = $request_idPer['personaid'];

        if (($_SESSION['userData']['rolid'] ?? 0) == 2) {
            if ($_SESSION['userData']['idpersona'] != $personaid_pedido) {
                return "Pedido_de_otro_cliente";
            }
        }

        $sql_ped = "SELECT p.idpedido, p.referenciadecobro, p.transaccionid, p.personaid, p.fecha,
                           p.subtotal, p.costo_envio, p.monto, p.tipopagoid, t.tipopago,
                           t.nombre_tpago, p.direccionenvio, p.status
                    FROM pedido as p
                    INNER JOIN tipopago t ON p.tipopagoid = t.idtipopago
                    WHERE p.idpedido = ?";
        $recuest_pedido = $this->select($sql_ped, [$idpedido]);

        if (!$recuest_pedido) {
            return "pedido_no_existe";
        }

        $sql_per = "SELECT idpersona, identificacion, nombres, apellidos, telefono, email_user,
                           nit, nombrefiscal, direccionfiscal
                    FROM persona WHERE idpersona = ?";
        $recuest_persona = $this->select($sql_per, [$recuest_pedido['personaid']]);

        $sql_det = "SELECT dp.pedidoid, dp.productoid, p.nombre, p.ruta, dp.precio, dp.cantidad
                    FROM pedido_detalle as dp
                    INNER JOIN producto p ON dp.productoid = p.idproducto
                    WHERE dp.pedidoid = ?";
        $recuest_detalle = $this->select_all($sql_det, [$idpedido]);

        return [
            'usuario' => $recuest_persona,
            'orden' => $recuest_pedido,
            'detalle' => $recuest_detalle
        ];
    }
}