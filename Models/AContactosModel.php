<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class AContactosModel extends Mysql
{
    private int $idcontacto;
    private string $origen;
    private string $nombre;
    private string $apellido;
    private string $telefono;
    private string $email;
    private string $mensaje;
    private string $ip_contacto;
    private string $geoplugin;
    private string $localidad;
    private string $ciudad;
    private string $pais;
    private string $navegador;

    public function __construct()
    {
        parent::__construct();
    }

    public function selectContactos(): array
    {
        $sql = "SELECT idcontacto, origen, nombre, apellido, telefono, email, localidad,
                       DATE_FORMAT(datecreated, '%d-%m-%Y') as fechaRegistro
                FROM contacto 
                ORDER BY idcontacto DESC";
        return $this->select_all($sql);
    }

    public function selectContacto(int $id): ?array
    {
        $this->idcontacto = $id;
        $sql = "SELECT idcontacto, origen, nombre, apellido, telefono, email, mensaje,
                       localidad, ciudad, pais, navegador,
                       DATE_FORMAT(datecreated, '%d-%m-%Y') as fechaRegistro
                FROM contacto 
                WHERE idcontacto = ?";
        return $this->select($sql, [$this->idcontacto]);
    }
}