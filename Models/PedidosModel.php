<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class PedidosModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectPedidos(?int $idUser): array
    {
        $sql = "SELECT p.idpedido, p.referenciadecobro, p.transaccionid,
                       DATE_FORMAT(p.fecha,'%d/%m/%y') as fecha,
                       p.monto, p.tipopagoid, t.tipopago, t.nombre_tpago, p.status
                FROM pedido p
                INNER JOIN tipopago t ON p.tipopagoid = t.idtipopago";
        $params = [];
        if ($idUser !== null) {
            $sql .= " WHERE p.personaid = ?";
            $params[] = $idUser;
        }
        $sql .= " ORDER BY p.idpedido DESC";
        return $this->select_all($sql, $params);
    }

    public function selectPedidoId($idpedido)
    {
        $params = [is_numeric($idpedido) ? $idpedido : null, !is_numeric($idpedido) ? $idpedido : null];
        $sql = "SELECT personaid, idpedido FROM pedido WHERE (idpedido = ? AND ? IS NOT NULL) OR (transaccionid = ? AND ? IS NULL)";
        $request_idPer = $this->select($sql, [$idpedido, $idpedido, $idpedido, $idpedido]);

        if (empty($request_idPer)) {
            return "pedido_no_existe";
        }

        $idpedido = $request_idPer['idpedido'];
        $personaid_pedido = $request_idPer['personaid'];

        if (($_SESSION['userData']['rolid'] ?? 0) == 2 && $_SESSION['userData']['idpersona'] != $personaid_pedido) {
            return "Pedido_de_otro_cliente";
        }

        $sql_ped = "SELECT p.idpedido, p.referenciadecobro, p.transaccionid, p.personaid, pe.nombres, pe.apellidos, pe.email_user, pe.telefono, p.fecha, p.subtotal, p.costo_envio, p.monto, p.tipopagoid, t.tipopago, p.direccionenvio, p.status
                    FROM pedido as p
                    INNER JOIN tipopago t ON p.tipopagoid = t.idtipopago
                    INNER JOIN persona pe ON p.personaid = pe.idpersona
                    WHERE p.idpedido = ?";
        $recuest_pedido = $this->select($sql_ped, [$idpedido]);

        $sql_per = "SELECT idpersona, identificacion, nombres, apellidos, telefono, email_user, nit, nombrefiscal, direccionfiscal FROM persona WHERE idpersona = ?";
        $recuest_persona = $this->select($sql_per, [$recuest_pedido['personaid']]);

        $sql_det = "SELECT dp.pedidoid, dp.productoid, p.nombre, p.ruta, dp.precio, dp.cantidad FROM pedido_detalle as dp INNER JOIN producto p ON dp.productoid = p.idproducto WHERE dp.pedidoid = ?";
        $recuest_detalle = $this->select_all($sql_det, [$idpedido]);

        return [
            'usuario' => $recuest_persona,
            'pedido' => $recuest_pedido,
            'detalle' => $recuest_detalle,
            'tiposdepago' => $this->select_all('SELECT * FROM tipopago')
        ];
    }

    public function selectTransaccionPaypal(string $transaccionid)
    {
        $sql_usuario = '';
        $params = [$transaccionid];
        if (($_SESSION['userData']['rolid'] ?? 0) == 2) {
            $sql_usuario = " AND personaid = ?";
            $params[] = $_SESSION['userData']['idpersona'];
        }

        $sql = "SELECT `datajson`, personaid FROM `pedido` WHERE `transaccionid`= ? {$sql_usuario}";
        $request = $this->select($sql, $params);

        if (!empty($request)) {
            $objData = json_decode($request['datajson']);
            $urlTansaccion = $objData->links[0]->href;
            return curlConectionGet($urlTansaccion, "application/json", getTokenPayPal());
        }
        return null;
    }

    public function updatePeido(int $idpedido, ?string $transaccion, ?string $tipoPago, string $estado): int
    {
        if ($transaccion === null) {
            $sql = "UPDATE pedido SET status = ? WHERE idpedido = ?";
            $arrData = [$estado, $idpedido];
        } else {
            $tipopago = $this->select("SELECT idtipopago FROM tipopago WHERE tipopago = ?", [$tipoPago]);
            $tipopagoid = $tipopago['idtipopago'];
            $sql = "UPDATE pedido SET referenciadecobro = ?, tipopagoid = ?, status = ? WHERE idpedido = ?";
            $arrData = [$transaccion, $tipopagoid, $estado, $idpedido];
        }
        return $this->update($sql, $arrData);
    }
}