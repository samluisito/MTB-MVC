<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class ContactoModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function newContacto(
        string $origen,
        string $nombre,
        string $apellido,
        string $telefono,
        string $email,
        string $mensaje,
        string $ip_contacto,
        string $geoplugin,
        ?string $localidad,
        ?string $ciudad,
        ?string $pais,
        string $dispositivo,
        string $dispositivoOS,
        string $navegador
    ): int {
        $sql = "INSERT INTO contacto (
                    origen, nombre, apellido, telefono, email, mensaje,
                    ip_contacto, geoplugin, localidad, ciudad, pais,
                    dispositivo, os, navegador
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $arrData = [
            $origen, $nombre, $apellido, $telefono, $email, $mensaje,
            $ip_contacto, $geoplugin, $localidad, $ciudad, $pais,
            $dispositivo, $dispositivoOS, $navegador
        ];

        return $this->insert($sql, $arrData);
    }
}