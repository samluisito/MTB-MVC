<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class NotificacionModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertNotificacion(string $tipo, $id_tipo, $leido = null)
    {
        $id_not_existente = $this->selectNotificacion($tipo, $id_tipo);

        if (empty($id_not_existente)) {
            $query_insert = "INSERT INTO `notificaciones` (`tipo`, `id_tipo`, `leido`) VALUES (?,?,?)";
            $arrData = [$tipo, $id_tipo, $leido];
            return $this->insert($query_insert, $arrData);
        }
        return 'existe';
    }

    public function updateNotificacion(string $tipo, $id_tipo, $leido = null)
    {
        $id_not = $this->selectNotificacion($tipo, $id_tipo);

        if ($id_not) {
            $leido = $leido ? 1 : null;
            $query_update = "UPDATE `notificaciones` SET `leido` = ? WHERE `id_not` = ?";
            $arrData = [$leido, $id_not];
            return $this->update($query_update, $arrData);
        }
        return 0;
    }

    public function selectNotificacion(string $tipo, int $id_tipo): ?int
    {
        $sql = "SELECT id_not FROM notificaciones WHERE tipo = ? AND id_tipo = ? AND leido IS NULL";
        $result = $this->select($sql, [$tipo, $id_tipo]);
        return $result['id_not'] ?? null;
    }

    public function selectNotificacionesNoLeidas(): array
    {
        $sql = "SELECT `tipo`, `datecreated`, COUNT(`id_not`) AS cantidad
                FROM notificaciones
                WHERE `leido` IS NULL
                GROUP BY `tipo`";
        return $this->select_all($sql);
    }
}