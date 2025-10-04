<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

trait TClientes
{
    private $intIdUser;
    private $strIdentificacion;
    private $strNombre;
    private $strApellido;
    private $strEmail;
    private $intTelefono;
    private $intTipoRolId;
    private $intStatus;
    private $strPassword;
    private $strtoken;
    private $strNit;
    private $strNombreFiscal;
    private $strDireccionFiscal;
    private $strLocalidad;
    private $strCiudad;
    private $strPais;
    private $strSexo;
    private $oauth_provider;
    private $oauth_uid;
    private $img;
    private $con;

    public function insertCliente(
        string $nombre,
        string $apellido,
        int $telefono = null,
        string $email,
        string $sexo,
        string $direccion = null,
        string $localidad,
        string $ciudad,
        string $pais = null,
        string $password,
        int $idTpoRol,
        string $oauth_provider = null,
        string $oauth_uid = null,
        string $img = null
    ) {
        $this->con = new Mysql();

        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->intTelefono = $telefono;
        $this->strEmail = $email;
        $this->strSexo = $sexo;
        $this->strDireccionFiscal = $direccion;
        $this->strLocalidad = $localidad;
        $this->strCiudad = $ciudad;
        $this->strPais = $pais;
        $this->strPassword = $password;
        $this->intTipoRolId = $idTpoRol;
        $this->oauth_provider = $oauth_provider;
        $this->oauth_uid = $oauth_uid;
        $this->img = $img;

        $return = 0;

        $sql = "SELECT * FROM persona WHERE email_user = '{$this->strEmail}' ";
        $request = $this->con->select($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO persona (nombres, apellidos, telefono, email_user, sexo, direccionfiscal, localidad, ciudad, pais, password, rolid, oauth_provider, oauth_uid, img) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $arrData = array(
                $this->strNombre,
                $this->strApellido,
                $this->intTelefono,
                $this->strEmail,
                $this->strSexo,
                $this->strDireccionFiscal,
                $this->strLocalidad,
                $this->strCiudad,
                $this->strPais,
                $this->strPassword,
                $this->intTipoRolId,
                $this->oauth_provider,
                $this->oauth_uid,
                $this->img,
            );
            $request_insert = $this->con->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function selectMail(string $email)
    {
        $this->con = new Mysql();
        $this->strEmail = $email;
        $return = 0;

        $sql = "SELECT idpersona FROM persona WHERE email_user = '{$this->strEmail}' ";
        $request = $this->con->select($sql);

        if (empty($request)) {
            $return = "OK";
        } else {
            $return = "Exist";
        }
        return $return;
    }

    public function insertPedido(
        string $transaccionid = null,
        string $datajson = null,
        int $personaid,
        float $subtotal,
        float $costo_envio,
        float $monto,
        string $metodoEntrega,
        int $tipopagoid,
        string $direccionenvio,
        string $status
    ) {
        $this->con = new Mysql();

        $query_insert = "INSERT INTO pedido (transaccionid, datajson, personaid, subtotal, costo_envio, monto, metodo_entrega, tipopagoid, direccionenvio, status) VALUES  (?,?,?,?,?,?,?,?,?,?)";
        $arrData = array($transaccionid, $datajson, $personaid, $subtotal, $costo_envio, $monto, $metodoEntrega, $tipopagoid, $direccionenvio, $status);
        $request_insert = $this->con->insert($query_insert, $arrData);
        return $request_insert;
    }

    public function insertDetallePedido(int $pedidoid, int $productoid, float $precio, float $cantidad)
    {
        $this->con = new Mysql();
        $query_insert = "INSERT INTO pedido_detalle ( pedidoid,	productoid, precio, cantidad ) VALUES  (?,?,?,?)";
        $arrData = array($pedidoid, $productoid, $precio, $cantidad);
        $request_insert = $this->con->insert($query_insert, $arrData);
        return $request_insert;
    }

    public function getPedido($idpedido)
    {
        $this->con = new Mysql();
        $request = array();

        $sql_ped = "SELECT p.idpedido, p.referenciadecobro, p.transaccionid,
                 p.personaid, pe.nombres, pe.apellidos, pe.email_user, pe.telefono,
                 p.fecha, p.subtotal, p.costo_envio, p.monto, p.tipopagoid, t.tipopago, 
                 p.direccionenvio, p.status 
                 FROM pedido as p INNER JOIN tipopago t INNER JOIN persona pe 
                 ON p.tipopagoid = t.idtipopago and p.personaid = pe.idpersona 
                 WHERE p.idpedido = {$idpedido}";
        $request_ped = $this->con->select($sql_ped);

        if (count($request_ped) > 0) {
            $sql_deta_ped = "SELECT dp.pedidoid, dp.productoid, p.nombre, p.ruta, dp.precio, dp.cantidad
                                    FROM pedido_detalle as dp
                                    INNER JOIN producto p
                                    ON dp.productoid = p.idproducto
                                    WHERE dp.pedidoid = {$idpedido}";
            $request_deta_ped = $this->con->select_all($sql_deta_ped);
            $request = ['pedido' => $request_ped, 'detalle' => $request_deta_ped];
        }
        return $request;
    }

    public function checkUserFB($oauth_provider, $oauth_uid, $strEmail)
    {
        $this->con = new Mysql();
        $sql = "SELECT idpersona FROM persona WHERE oauth_provider = '{$oauth_provider}' AND oauth_uid = '{$oauth_uid}' OR email_user = '{$strEmail}'";
        $recuest = $this->con->select($sql);
        return $recuest;
    }

    public function actualuzarUserFB(string $oauth_provider, string $oauth_uid, string $img, int $idpersona)
    {
        $this->con = new Mysql();
        $datemodified = date("Y-m-d H:i:s");
        $sql = "UPDATE persona SET oauth_provider = ?,oauth_uid = ?, img= ?, datemodified = ? WHERE idpersona = {$idpersona}";
        $arrData = array($oauth_provider, $oauth_uid, $img, $datemodified);
        $recuest = $this->con->update($sql, $arrData);
        return $recuest;
    }
}