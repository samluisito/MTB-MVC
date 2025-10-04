<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class CalendarModel extends Mysql
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

    public function insertUsuario(
        string $identificacion,
        string $nombre,
        string $apellido,
        int $telefono,
        string $email,
        string $password,
        int $idtporol,
        int $status
    ) {
        $this->strIdentificacion = $identificacion;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strEmail = $email;
        $this->intTelefono = $telefono;
        $this->intTipoRolId = $idtporol;
        $this->intStatus = $status;
        $this->strPassword = $password;

        $sql = "SELECT * FROM persona WHERE email_user = ? OR identificacion = ?";
        $request = $this->select($sql, [$this->strEmail, $this->strIdentificacion]);

        if (empty($request)) {
            $query_insert = "INSERT INTO persona (identificacion, nombres, apellidos, telefono, email_user, password, rolid, status) VALUES (?,?,?,?,?,?,?,?)";
            $arrData = [$this->strIdentificacion, $this->strNombre, $this->strApellido, $this->intTelefono, $this->strEmail, $this->strPassword, $this->intTipoRolId, $this->intStatus];
            return $this->insert($query_insert, $arrData);
        }
        return "exist";
    }

    public function selectUsuarios(): array
    {
        $sql = "SELECT p.idpersona, p.identificacion, p.nombres, p.apellidos, p.telefono, p.email_user, p.status, r.nombrerol
                FROM persona p
                INNER JOIN rol r ON p.rolid = r.idrol
                WHERE p.status != 0";
        return $this->select_all($sql);
    }

    public function selectUser(int $idUser): ?array
    {
        $this->intIdUser = $idUser;
        $sql = "SELECT p.idpersona, p.identificacion, p.nombres, p.apellidos, p.telefono, p.email_user,
                       p.nit, p.nombrefiscal, p.direccionfiscal, r.idrol, r.nombrerol,
                       p.status, DATE_FORMAT(p.datecreated, '%d-%m-%Y') as fechaRegistro
                FROM persona p
                INNER JOIN rol r ON p.rolid = r.idrol
                WHERE p.idpersona = ?";
        return $this->select($sql, [$this->intIdUser]);
    }

    public function updateUsuario(
        int $idUser,
        string $identificacion,
        string $nombre,
        string $apellido,
        int $telefono,
        string $email,
        string $password,
        int $idtporol,
        int $status
    ) {
        $this->intIdUser = $idUser;
        $this->strIdentificacion = $identificacion;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->strEmail = $email;
        $this->intTelefono = $telefono;
        $this->intTipoRolId = $idtporol;
        $this->intStatus = $status;
        $this->strPassword = $password;

        $sql = "SELECT * FROM persona WHERE (email_user = ? OR identificacion = ?) AND idpersona != ?";
        $request = $this->select($sql, [$this->strEmail, $this->strIdentificacion, $this->intIdUser]);

        if (empty($request)) {
            if ($this->strPassword != "") {
                $sql_update = "UPDATE persona SET identificacion = ?, nombres = ?, apellidos = ?, telefono = ?, email_user = ?, password = ?, rolid = ?, status = ? WHERE idpersona = ?";
                $arrData = [$this->strIdentificacion, $this->strNombre, $this->strApellido, $this->intTelefono, $this->strEmail, $this->strPassword, $this->intTipoRolId, $this->intStatus, $this->intIdUser];
            } else {
                $sql_update = "UPDATE persona SET identificacion = ?, nombres = ?, apellidos = ?, telefono = ?, email_user = ?, rolid = ?, status = ? WHERE idpersona = ?";
                $arrData = [$this->strIdentificacion, $this->strNombre, $this->strApellido, $this->intTelefono, $this->strEmail, $this->intTipoRolId, $this->intStatus, $this->intIdUser];
            }
            return $this->update($sql_update, $arrData);
        }
        return "exist";
    }
}