<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class ClientesModel extends Mysql
{
    private int $intIdUser;
    private string $strIdentificacion;
    private string $strNombre;
    private string $strApellido;
    private string $strEmail;
    private int $intTelefono;
    private int $intTipoRolId;
    private int $intStatus;
    private string $strPassword;
    private string $strtoken;
    private string $strNit;
    private string $strNombreFiscal;
    private string $strDireccionFiscal;

    public function __construct()
    {
        parent::__construct();
    }

    public function insertCliente(
        string $identificacion,
        string $nombre,
        string $apellido,
        int $telefono,
        string $email,
        string $password,
        int $idTpoRol,
        string $nit,
        string $nombreFiscal,
        string $dirFiscal
    ) {
        $this->strIdentificacion = $identificacion;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->intTelefono = $telefono;
        $this->strEmail = $email;
        $this->strPassword = $password;
        $this->intTipoRolId = $idTpoRol;
        $this->strNit = $nit;
        $this->strNombreFiscal = $nombreFiscal;
        $this->strDireccionFiscal = $dirFiscal;

        $sql = "SELECT idpersona FROM persona WHERE email_user = ? OR identificacion = ?";
        $request = $this->select($sql, [$this->strEmail, $this->strIdentificacion]);

        if (empty($request)) {
            $query_insert = "INSERT INTO persona (identificacion, nombres, apellidos, telefono, email_user, password, nit, nombrefiscal, direccionfiscal, rolid) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $arrData = [
                $this->strIdentificacion, $this->strNombre, $this->strApellido,
                $this->intTelefono, $this->strEmail, $this->strPassword,
                $this->strNit, $this->strNombreFiscal, $this->strDireccionFiscal,
                $this->intTipoRolId
            ];
            return $this->insert($query_insert, $arrData);
        }
        return "exist";
    }

    public function selectClientes(): array
    {
        $sql = "SELECT idpersona, identificacion, nombres, apellidos, telefono, email_user, status
                FROM persona
                WHERE rolid = 2 AND status < 2 AND idpersona IN
                (SELECT `personaid` FROM `pedido` WHERE status = 'Completo' GROUP BY `personaid`)";
        return $this->select_all($sql);
    }

    public function cteEnUso(int $idpersona): ?array
    {
        return $this->select("SELECT MAX(idpedido) as idpedido FROM pedido WHERE personaid = ?", [$idpersona]);
    }

    public function selectCliente(int $idpersona): ?array
    {
        $sql = "SELECT idpersona, identificacion, nombres, apellidos, telefono, email_user,
                       nit, nombrefiscal, direccionfiscal, status,
                       DATE_FORMAT(datecreated, '%d-%m-%Y') as fechaRegistro 
                FROM persona 
                WHERE idpersona = ?";
        return $this->select($sql, [$idpersona]);
    }

    public function updateCliente(
        int $idUser,
        string $identificacion,
        string $nombre,
        string $apellido,
        int $telefono,
        string $email,
        string $password,
        string $nit,
        string $nombreFiscal,
        string $dirFiscal
    ) {
        $this->intIdUser = $idUser;
        $this->strIdentificacion = $identificacion;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strEmail = $email;
        $this->intTelefono = $telefono;
        $this->strPassword = $password;
        $this->strNit = $nit;
        $this->strNombreFiscal = $nombreFiscal;
        $this->strDireccionFiscal = $dirFiscal;

        $sql = "SELECT idpersona FROM persona WHERE (email_user = ? OR identificacion = ?) AND idpersona != ?";
        $request = $this->select($sql, [$this->strEmail, $this->strIdentificacion, $this->intIdUser]);

        if (empty($request)) {
            if ($this->strPassword != "") {
                $sql_update = "UPDATE persona SET identificacion = ?, nombres = ?, apellidos = ?, telefono = ?, email_user = ?, password = ?, nit = ?, nombrefiscal = ?, direccionfiscal = ? WHERE idpersona = ?";
                $arrData = [$this->strIdentificacion, $this->strNombre, $this->strApellido, $this->intTelefono, $this->strEmail, $this->strPassword, $this->strNit, $this->strNombreFiscal, $this->strDireccionFiscal, $this->intIdUser];
            } else {
                $sql_update = "UPDATE persona SET identificacion = ?, nombres = ?, apellidos = ?, telefono = ?, email_user = ?, nit = ?, nombrefiscal = ?, direccionfiscal = ? WHERE idpersona = ?";
                $arrData = [$this->strIdentificacion, $this->strNombre, $this->strApellido, $this->intTelefono, $this->strEmail, $this->strNit, $this->strNombreFiscal, $this->strDireccionFiscal, $this->intIdUser];
            }
            return $this->update($sql_update, $arrData);
        }
        return "exist";
    }

    public function deleteCliente(int $idPersona): int
    {
        $sql = "UPDATE persona SET status = ? WHERE idpersona = ?";
        return $this->update($sql, [2, $idPersona]);
    }
}