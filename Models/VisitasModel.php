<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class VisitasModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function registrarVisita(
        string $ip,
        string $pagina,
        string $url,
        string $dispositivo,
        string $dispositivoOS,
        ?string $pais,
        ?string $ciudad,
        ?string $localidad,
        string $idnav,
        ?int $idUser
    ): bool {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $sql = 'INSERT INTO visitas(ip, pagina, url, dispositivo, os, pais, ciudad, localidad, navegador_id, user_id, user_agent) VALUES(?,?,?,?,?,?,?,?,?,?,?)';
        $arrData = [$ip, $pagina, $url, $dispositivo, $dispositivoOS, $pais, $ciudad, $localidad, $idnav, $idUser, $user_agent];
        $sentencia = $this->insert($sql, $arrData);
        return $sentencia > 0;
    }

    public function getVisitasSinCity(int $cant): array
    {
        $sql = "SELECT `idvisita`, `ip`, `pais`, `ciudad`, `localidad`
                FROM `visitas`
                WHERE `pais` = '' OR `pais` IS NULL OR
                      `ciudad` = '' OR `ciudad` IS NULL OR
                      `localidad` = '' OR `localidad` IS NULL
                ORDER BY `ciudad` ASC LIMIT ?";
        return $this->select_all($sql, [$cant]);
    }

    public function updateGeolocalizacionVisita(?string $pais, ?string $ciudad, ?string $localidad, int $id): bool
    {
        $sql = 'UPDATE `visitas` SET `pais` = ?, `ciudad` = ?, `localidad` = ? WHERE `idvisita` = ?';
        $arrData = [$pais, $ciudad, $localidad, $id];
        $sentencia = $this->update($sql, $arrData);
        return $sentencia > 0;
    }
}