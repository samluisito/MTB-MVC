<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class CarritoModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    /* Tipos de pago ------------------------------------------------------------------------- */

    public function selectTransaccionId($id)
    {
        return $this->select("SELECT idpedido FROM pedido WHERE transaccionid = ?", [$id]);
    }

    public function selectTiposPagosT()
    {
        return $this->select_all('SELECT * FROM tipopago WHERE status = 1');
    }

    public function selectTiposPagoDetallesT($tipopagoid)
    {
        return $this->select_all("SELECT * FROM tipopago_detalle WHERE tipopagoid = ?", [$tipopagoid]);
    }

    public function idTipoPago($tipopago)
    {
        $result = $this->select("SELECT idtipopago FROM tipopago WHERE tipopago = ?", [$tipopago]);
        return $result['idtipopago'] ?? null;
    }

    /* Pedidos y Clientes ---------------------------------- */

    public function insertPedido(
        ?string $transaccionid,
        ?string $datajson,
        int $personaid,
        float $subtotal,
        float $costo_envio,
        float $monto,
        string $metodoEntrega,
        int $tipopagoid,
        string $direccionenvio,
        string $status
    ) {
        $query_insert = "INSERT INTO pedido (transaccionid, datajson, personaid, subtotal, costo_envio, monto, metodo_entrega, tipopagoid, direccionenvio, status) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $arrData = [$transaccionid, $datajson, $personaid, $subtotal, $costo_envio, $monto, $metodoEntrega, $tipopagoid, $direccionenvio, $status];
        return $this->insert($query_insert, $arrData);
    }

    public function insertDetallePedido(int $pedidoid, int $productoid, float $precio, int $cantidad)
    {
        $query_insert = "INSERT INTO pedido_detalle (pedidoid, productoid, precio, cantidad) VALUES (?,?,?,?)";
        $arrData = [$pedidoid, $productoid, $precio, $cantidad];
        return $this->insert($query_insert, $arrData);
    }

    public function getPedido(int $idpedido): array
    {
        $request = [];
        $sql_ped = "SELECT p.idpedido, p.referenciadecobro, p.transaccionid, p.personaid, pe.nombres, pe.apellidos, pe.email_user, pe.telefono, p.fecha, p.subtotal, p.costo_envio, p.monto, p.tipopagoid, t.tipopago, p.direccionenvio, p.status
                    FROM pedido as p
                    INNER JOIN tipopago t ON p.tipopagoid = t.idtipopago
                    INNER JOIN persona pe ON p.personaid = pe.idpersona
                    WHERE p.idpedido = ?";
        $request_ped = $this->select($sql_ped, [$idpedido]);

        if (!empty($request_ped)) {
            $sql_deta_ped = "SELECT dp.pedidoid, dp.productoid, p.nombre, p.ruta, dp.precio, dp.cantidad
                             FROM pedido_detalle as dp
                             INNER JOIN producto p ON dp.productoid = p.idproducto
                             WHERE dp.pedidoid = ?";
            $request_deta_ped = $this->select_all($sql_deta_ped, [$idpedido]);
            $request = ['pedido' => $request_ped, 'detalle' => $request_deta_ped];
        }
        return $request;
    }

    public function insertClienteCarr(
        string $nombre,
        string $apellido,
        ?int $telefono,
        string $email,
        string $sexo,
        ?string $direccion,
        string $localidad,
        string $ciudad,
        ?string $pais,
        string $password,
        int $idTpoRol,
        ?string $oauth_provider = null,
        ?string $oauth_uid = null,
        ?string $img = null
    ) {
        $idpersona = $this->select("SELECT idpersona FROM persona WHERE email_user = ?", [$email]);
        if (empty($idpersona)) {
            $query_insert = "INSERT INTO persona (nombres, apellidos, telefono, email_user, sexo, direccionfiscal, localidad, ciudad, pais, password, rolid, oauth_provider, oauth_uid, img) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $arrData = [$nombre, $apellido, $telefono, $email, $sexo, $direccion, $localidad, $ciudad, $pais, $password, $idTpoRol, $oauth_provider, $oauth_uid, $img];
            return $this->insert($query_insert, $arrData);
        }
        return $idpersona['idpersona'];
    }
}