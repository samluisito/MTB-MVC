<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class RolesModel extends Mysql
{
    public int $intIdRol;
    public string $strRol;
    public string $strDescripcion;
    public int $intStatus;

    public function __construct()
    {
        parent::__construct();
    }

    public function selectRoles(): array
    {
        $sql = "SELECT * FROM rol WHERE status < 2";
        return $this->select_all($sql);
    }

    public function selectRol(int $idRol): ?array
    {
        $sql = "SELECT * FROM rol WHERE idrol = ?";
        return $this->select($sql, [$idRol]);
    }

    public function insertRol(string $rol, string $descripcion, int $status)
    {
        $sql = "SELECT idrol FROM rol WHERE nombrerol = ?";
        $request = $this->select($sql, [$rol]);

        if (empty($request)) {
            $query_insert = "INSERT INTO rol (nombrerol, descripcion, status) VALUES (?,?,?)";
            $arrData = [$rol, $descripcion, $status];
            return $this->insert($query_insert, $arrData);
        }
        return "exist";
    }

    public function updateRol(int $idRol, string $rol, string $descripcion, int $status)
    {
        $sql = "SELECT idrol FROM rol WHERE nombrerol = ? AND idrol != ?";
        $request = $this->select($sql, [$rol, $idRol]);

        if (empty($request)) {
            $query_update = "UPDATE rol SET nombrerol = ?, descripcion = ?, status = ? WHERE idrol = ?";
            $arrData = [$rol, $descripcion, $status, $idRol];
            return $this->update($query_update, $arrData);
        }
        return 'exist';
    }

    public function deleteRol(int $idRol): string
    {
        $sql = "SELECT * FROM persona WHERE rolid = ?";
        $request = $this->select($sql, [$idRol]);

        if (empty($request)) {
            $sql = "UPDATE rol SET status = ? WHERE idrol = ?";
            $request_update = $this->update($sql, [2, $idRol]);
            return $request_update ? 'OK' : 'error';
        }
        return 'exist';
    }

    public function rolEnUso(int $idRol): ?array
    {
        return $this->select("SELECT rolid FROM persona WHERE rolid = ?", [$idRol]);
    }

    public function editStatus(int $id, int $intStatus): string
    {
        $status = $intStatus == 1 ? 0 : 1;
        $request = $this->update("UPDATE rol SET status = ? WHERE idrol = ?", [$status, $id]);
        return $request ? 'OK' : 'error';
    }

    public function selectModulos(int $idrol): array
    {
        $sql_modulos = "SELECT idmodulo FROM modulo";
        $request_modulos = $this->select_all($sql_modulos);

        foreach ($request_modulos as $modulo) {
            $idmodulo = $modulo['idmodulo'];
            $sql_permiso = "SELECT idmodulo FROM permisos WHERE moduloid = ? AND rolid = ?";
            $request_permiso = $this->select($sql_permiso, [$idmodulo, $idrol]);

            if (empty($request_permiso)) {
                $query_insert = "INSERT INTO permisos (rolid, moduloid, ver, crear, actualizar, eliminar) VALUES (?,?, '0', '0', '0', '0')";
                $this->insert($query_insert, [$idrol, $idmodulo]);
            }
        }

        $sql_final = "SELECT p.idmodulo, p.rolid, p.moduloid, m.titulo, p.ver, p.crear, p.actualizar, p.eliminar
                      FROM permisos p
                      INNER JOIN modulo m ON p.moduloid = m.idmodulo
                      WHERE p.rolid = ?";
        return $this->select_all($sql_final, [$idrol]);
    }

    public function editPermiso(int $intIdPerm, string $strTpoPerm, int $valorTpoPerm): bool
    {
        $sql = "UPDATE permisos SET {$strTpoPerm} = ? WHERE idmodulo = ?";
        $request = $this->update($sql, [$valorTpoPerm, $intIdPerm]);

        if ($request) {
            $sql_rol = "SELECT rolid FROM permisos WHERE idmodulo = ?";
            $request_rol = $this->select($sql_rol, [$intIdPerm]);
            if (($request_rol['rolid'] ?? 0) == ($_SESSION['userData']['rolid'] ?? -1)) {
                sessionUser($_SESSION['idUser']); // Assuming sessionUser is a global helper
            }
        }
        return (bool)$request;
    }
}