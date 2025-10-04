<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class ModulosModel extends Mysql
{
    public int $intIdModulo;
    public string $strTiulo;
    public string $strDescripcion;
    public int $intStatus;

    public function __construct()
    {
        parent::__construct();
    }

    public function selectModulos(): array
    {
        $sql = "SELECT * FROM modulo WHERE status < 2";
        return $this->select_all($sql);
    }

    public function insertModulo(string $titulo, string $descripcion, int $status)
    {
        $this->strTiulo = $titulo;
        $this->strDescripcion = $descripcion;
        $this->intStatus = $status;

        $sql = "SELECT * FROM modulo WHERE titulo = ?";
        $request = $this->select($sql, [$this->strTiulo]);

        if (empty($request)) {
            $query_insert = "INSERT INTO modulo (titulo, descripcion, status) VALUES (?,?,?)";
            $arrData = [$this->strTiulo, $this->strDescripcion, $this->intStatus];
            return $this->insert($query_insert, $arrData);
        }
        return "exist";
    }

    public function updateModulo(int $idModulo, string $titulo, string $descripcion, int $status)
    {
        $this->intIdModulo = $idModulo;
        $this->strTiulo = $titulo;
        $this->strDescripcion = $descripcion;
        $this->intStatus = $status;

        $sql = "SELECT * FROM modulo WHERE titulo = ? AND idmodulo != ?";
        $request = $this->select($sql, [$this->strTiulo, $this->intIdModulo]);

        if (empty($request)) {
            $query_update = "UPDATE modulo SET titulo = ?, descripcion = ?, status = ? WHERE idmodulo = ?";
            $arrData = [$this->strTiulo, $this->strDescripcion, $this->intStatus, $this->intIdModulo];
            return $this->update($query_update, $arrData);
        }
        return 'exist';
    }

    public function selectModulo(int $idModulo): ?array
    {
        $this->intIdModulo = $idModulo;
        $sql = "SELECT * FROM modulo WHERE idmodulo = ?";
        return $this->select($sql, [$this->intIdModulo]);
    }

    public function moduloEnUso(int $idmodulo): ?array
    {
        $this->intIdModulo = $idmodulo;
        return $this->select("SELECT * FROM permisos WHERE moduloid = ?", [$this->intIdModulo]);
    }

    public function deleteModulo(int $idModulo)
    {
        $this->intIdModulo = $idModulo;
        $sql = "SELECT * FROM permisos WHERE moduloid = ?";
        $request = $this->select($sql, [$this->intIdModulo]);

        if (empty($request)) {
            $sql = "UPDATE modulo SET status = ? WHERE idmodulo = ?";
            $request = $this->update($sql, [2, $this->intIdModulo]);
            return $request ? 'OK' : 'error';
        }
        return 'exist';
    }

    public function statusModulo(int $idModulo, int $intStatus)
    {
        $this->intIdModulo = $idModulo;
        $this->intStatus = $intStatus == 1 ? 0 : 1;

        $sql = "UPDATE modulo SET status = ? WHERE idmodulo = ?";
        $request = $this->update($sql, [$this->intStatus, $this->intIdModulo]);

        return $request ? 'OK' : 'error';
    }
}