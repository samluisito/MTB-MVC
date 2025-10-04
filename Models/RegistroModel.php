<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class RegistroModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectMail(string $email): string
    {
        $sql = "SELECT idpersona FROM persona WHERE email_user = ?";
        $request = $this->select($sql, [$email]);
        return empty($request) ? "OK" : "Exist";
    }

    public function insertCliente(
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
        $sql = "SELECT idpersona FROM persona WHERE email_user = ?";
        $request = $this->select($sql, [$email]);

        if (empty($request)) {
            $query_insert = "INSERT INTO persona (nombres, apellidos, telefono, email_user, sexo, direccionfiscal, localidad, ciudad, pais, password, rolid, oauth_provider, oauth_uid, img) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $arrData = [$nombre, $apellido, $telefono, $email, $sexo, $direccion, $localidad, $ciudad, $pais, $password, $idTpoRol, $oauth_provider, $oauth_uid, $img];
            return $this->insert($query_insert, $arrData);
        }
        return "exist";
    }
}