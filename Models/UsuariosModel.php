<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class UsuariosModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertUsuario(
        string|int $identificacion,
        string $nombre,
        string $apellido,
        int $telefono,
        string $email,
        string $password,
        int $idtporol,
        int $status
    ) {
        $sql = "SELECT idpersona FROM persona WHERE email_user = ? OR identificacion = ?";
        $request = $this->select($sql, [$email, $identificacion]);

        if (empty($request)) {
            $query_insert = "INSERT INTO persona (identificacion, nombres, apellidos, telefono, email_user, password, rolid, status) VALUES (?,?,?,?,?,?,?,?)";
            $arrData = [$identificacion, $nombre, $apellido, $telefono, $email, $password, $idtporol, $status];
            return $this->insert($query_insert, $arrData);
        }
        return "exist";
    }

    public function selectListUsuarios(): array
    {
        $sql = "SELECT p.idpersona, p.identificacion, p.nombres, p.apellidos, p.telefono, p.email_user, p.status, r.nombrerol
                FROM persona p
                INNER JOIN rol r ON p.rolid = r.idrol
                WHERE p.status < 2";
        return $this->select_all($sql);
    }

    public function selectUser(int $idUser): ?array
    {
        $sql = "SELECT p.idpersona, p.identificacion, p.nombres, p.apellidos, p.telefono, p.email_user,
                       p.nit, p.nombrefiscal, p.direccionfiscal, r.idrol, r.nombrerol,
                       p.status, DATE_FORMAT(p.datecreated, '%d-%m-%Y') as fechaRegistro
                FROM persona p
                INNER JOIN rol r ON p.rolid = r.idrol
                WHERE p.idpersona = ?";
        return $this->select($sql, [$idUser]);
    }

    public function updateUsuario(
        int $idUser,
        string|int $identificacion,
        string $nombre,
        string $apellido,
        int $telefono,
        string $email,
        string $password,
        int $idtporol,
        int $status
    ) {
        $sql = "SELECT idpersona FROM persona WHERE (email_user = ? OR identificacion = ?) AND idpersona != ?";
        $request = $this->select($sql, [$email, $identificacion, $idUser]);

        if (empty($request)) {
            $arrData = [$identificacion, $nombre, $apellido, $telefono, $email, $idtporol, $status];
            $sql_pass = "";
            if ($password != "") {
                $sql_pass = ", password = ?";
                $arrData[] = $password;
            }
            $arrData[] = $idUser;

            $sql_update = "UPDATE persona SET identificacion = ?, nombres = ?, apellidos = ?, telefono = ?, email_user = ?, rolid = ?, status = ? {$sql_pass} WHERE idpersona = ?";
            return $this->update($sql_update, $arrData);
        }
        return "exist";
    }

    public function deleteUser(int $idPersona): int
    {
        $sql = "UPDATE persona SET status = ? WHERE idpersona = ?";
        return $this->update($sql, [2, $idPersona]);
    }

    public function usuarioEnUso(int $idpersona): ?array
    {
        return $this->select("SELECT MAX(idpedido) as idpedido FROM pedido WHERE personaid = ?", [$idpersona]);
    }
}