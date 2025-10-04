<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class LogoutModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Marca una sesión como inactiva en la base de datos.
     *
     * @param string $idSesion El ID de la sesión a cerrar.
     * @return int El número de filas afectadas.
     */
    public function cerrar_session(string $idSesion): int
    {
        $sql = "UPDATE `sesiones` SET `estado_sesion` = ? WHERE `id_sesion` = ?";
        return $this->update($sql, [0, $idSesion]);
    }
}