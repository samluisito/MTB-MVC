<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class ProfileModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Selecciona los últimos 12 pedidos del usuario actual.
     *
     * @return array Un array con los datos de los pedidos.
     */
    public function ultimosPedidos(): array
    {
        $idpersona = $_SESSION['userData']['idpersona'];

        $sql = "SELECT p.idpedido, CONCAT(pr.nombres,' ',pr.apellidos) AS nombre, p.monto, p.status
                FROM pedido p
                INNER JOIN persona pr ON p.personaid = pr.idpersona
                WHERE p.personaid = ?
                ORDER BY p.idpedido DESC LIMIT 12";

        return $this->select_all($sql, [$idpersona]);
    }
}