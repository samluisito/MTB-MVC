<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class CategoriasModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertCategoria(?int $intIdCatPadre, string $nombre, string $descripcion, int $intCatFb, int $intCatGg, string $img, string $strTags, string $ruta, int $status)
    {
        $sql = "SELECT idcategoria FROM producto_categoria WHERE nombre = ?";
        $request = $this->select($sql, [$nombre]);

        if (empty($request)) {
            $query_insert = "INSERT INTO producto_categoria (padre_cat_id, nombre, descripcion, cat_facebook_id, cat_google_id, img, tags, ruta, status) VALUES (?,?,?,?,?,?,?,?,?)";
            $arrData = [$intIdCatPadre, $nombre, $descripcion, $intCatFb, $intCatGg, $img, $strTags, $ruta, $status];
            return $this->insert($query_insert, $arrData);
        }
        return 'e'; // 'e' for exists
    }

    public function updateCategoria(int $idCategoria, ?int $intIdCatPadre, string $nombre, string $descripcion, int $intCatFb, int $intCatGg, string $img, string $strTags, string $ruta, int $status)
    {
        $sql = "SELECT idcategoria FROM producto_categoria WHERE nombre = ? AND idcategoria != ?";
        $request = $this->select($sql, [$nombre, $idCategoria]);

        if (empty($request)) {
            $query_update = "UPDATE producto_categoria SET padre_cat_id = ?, nombre = ?, descripcion = ?, cat_facebook_id=?, cat_google_id=?, img=?, tags = ?, ruta =?, status = ? WHERE idcategoria = ?";
            $arrData = [$intIdCatPadre, $nombre, $descripcion, $intCatFb, $intCatGg, $img, $strTags, $ruta, $status, $idCategoria];
            return $this->update($query_update, $arrData);
        }
        return 'e'; // 'e' for exists
    }

    public function selectCategorias(int $id = 0): array
    {
        $sql = ($id === 0)
            ? "SELECT * FROM producto_categoria WHERE status < 2 AND padre_cat_id IS NULL"
            : "SELECT * FROM producto_categoria WHERE status < 2 AND padre_cat_id = " . $id;
        return $this->select_all($sql);
    }

    public function selectCat(int $idCat): ?array
    {
        return $this->select("SELECT * FROM producto_categoria WHERE idcategoria = ?", [$idCat]);
    }

    public function selectImgCategoria(int $intId): ?string
    {
        return $this->select_column("SELECT img FROM producto_categoria WHERE idcategoria = ?", [$intId]);
    }

    public function categoriaEnUso(int $intId): bool
    {
        $con1 = $this->select_column("SELECT MAX(idproducto) FROM producto WHERE categoriaid = ?", [$intId]);
        $con2 = $this->select_column("SELECT MAX(itemid) FROM home_banner WHERE tipo = 'categ' AND itemid = ?", [$intId]);
        return ($con1 + $con2) > 0;
    }

    public function editCategoriaStatus(int $id, int $intStatus): string
    {
        $status = $intStatus == 1 ? 0 : 1;
        $request = $this->update("UPDATE producto_categoria SET status = ? WHERE idcategoria = ?", [$status, $id]);
        return $request ? 'OK' : 'error';
    }

    // ... Other methods would be refactored similarly, using prepared statements.
}