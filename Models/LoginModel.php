<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class LoginModel extends Mysql
{
    private int $intIdUser;
    private string $strUsuario;
    private string $strPassword;
    private string $strToken;
    private int $intRolId;

    public function __construct()
    {
        parent::__construct();
    }

    public function validarUserLogin(string $strUsuario, string $strPassword): ?array
    {
        $sql = "SELECT idpersona, status FROM persona WHERE email_user = ? AND password = ?";
        $arrData = [$strUsuario, $strPassword];
        return $this->select($sql, $arrData);
    }

    public function consultar_session(string $idSesion, int $idPersona, string $OS, string $browser)
    {
        $querySql = "SELECT id FROM `sesiones` WHERE id_sesion = ? AND id_persona = ? AND os = ? AND browser = ? AND estado_sesion = 1";
        return $this->select_column($querySql, [$idSesion, $idPersona, $OS, $browser]);
    }

    public function insertar_session(string $idSesion, string $fecha, int $estadoSesion, int $idPersona, string $OS, string $browser)
    {
        $querySql = "INSERT INTO `sesiones` (id_sesion, fecha, estado_sesion, id_persona, os, browser) VALUES (?,?,?,?,?,?)";
        $this->insert($querySql, [$idSesion, $fecha, $estadoSesion, $idPersona, $OS, $browser]);
    }

    public function forgetPass($strUser): ?array
    {
        $this->strUsuario = $strUser;
        $sql = "SELECT idpersona FROM persona WHERE email_user = '{$this->strUsuario}' AND status = 1";
        $recuest = $this->select($sql);

        if (!empty($recuest)) {
            $this->intIdUser = $recuest['idpersona'];
            $this->strToken = token();
            $query_update = "UPDATE persona SET token=? WHERE idpersona = '{$this->intIdUser}'";
            $arrData = [$this->strToken];
            $update_request = $this->update($query_update, $arrData);

            if ($update_request) {
                $sql = "SELECT idpersona, nombres, apellidos, email_user, token, status FROM persona WHERE idpersona = '{$this->intIdUser}'";
                return $this->select($sql);
            }
        }
        return null;
    }

    public function buscar_id_x_token($token): ?array
    {
        $this->strToken = $token;
        $sql = "SELECT idpersona, status FROM persona WHERE token = '{$this->strToken}'";
        return $this->select($sql);
    }

    public function updatePasword(int $idUser, string $password): int
    {
        $this->intIdUser = $idUser;
        $this->strPassword = $password;
        $query_update = "UPDATE persona SET password=? WHERE idpersona = '{$this->intIdUser}'";
        $arrData = [$this->strPassword];
        return $this->update($query_update, $arrData);
    }

    public function cerrar_session(string $idSesion)
    {
        $sql = "UPDATE sesiones SET estado_sesion = ? WHERE id_sesion = ?";
        return $this->update($sql, [0, $idSesion]);
    }
}