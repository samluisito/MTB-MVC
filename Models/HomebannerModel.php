<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class HomebannerModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectItem(string $tipo = null): array
    {
        $array = [];
        if ($tipo == 'prod') {
            $sql = "SELECT idproducto as id, nombre FROM `producto` WHERE status = 1";
            $array = $this->select_all($sql);
        } elseif ($tipo == 'categ') {
            $sql = "SELECT idcategoria as id, nombre FROM producto_categoria WHERE status = 1";
            $array = $this->select_all($sql);
        } elseif ($tipo == 'blog') {
            $sql = "SELECT identrada as id, titulo as nombre FROM `blog_entrada` WHERE status = 1";
            $array = $this->select_all($sql);
        }
        return $array;
    }

    public function selectUrlItem(string $tipo = null, int $id, string $prefijo)
    {
        $ruta = false;
        if ($tipo == 'prod') {
            $sql = "SELECT ruta FROM `producto` WHERE idproducto = ?";
            $recuest = $this->select($sql, [$id]);
            $prefijo_ruta = $prefijo == 'ruta' ? 'tienda/producto/' : '';
            $ruta = $prefijo_ruta . ($recuest['ruta'] ?? '');
        } elseif ($tipo == 'categ') {
            $sql = "SELECT ruta FROM producto_categoria WHERE idcategoria = ?";
            $recuest = $this->select($sql, [$id]);
            $prefijo_ruta = $prefijo == 'ruta' ? 'tienda/categoria/' : '';
            $ruta = $prefijo_ruta . ($recuest['ruta'] ?? '');
        } elseif ($tipo == 'blog') {
            $sql = "SELECT url FROM blog_entrada WHERE identrada = ?";
            $recuest = $this->select($sql, [$id]);
            $prefijo_ruta = $prefijo == 'ruta' ? 'blog/entrada/' : '';
            $ruta = $prefijo_ruta . ($recuest['url'] ?? '');
        }
        return $ruta;
    }

    public function insertBanner(string $nombre, string $descripcion, string $img_banner, string $strListTpo, int $intListItem, string $ruta, int $status)
    {
        $sql = "SELECT idbanner FROM home_banner WHERE tipo = ? AND itemid = ?";
        $recuest = $this->select($sql, [$strListTpo, $intListItem]);

        if (empty($recuest)) {
            $query_insert = "INSERT INTO home_banner (nombre, descripcion, img, tipo, itemid, ruta, status) VALUES (?,?,?,?,?,?,?)";
            $arrData = [$nombre, $descripcion, $img_banner, $strListTpo, $intListItem, $ruta, $status];
            return $this->insert($query_insert, $arrData);
        }
        return "exist";
    }

    public function updateBanner(int $idBanner, string $nombre, string $descripcion, string $img_banner, string $strListTpo, int $intListItem, string $ruta, int $status)
    {
        $sql = "SELECT idbanner FROM `home_banner` WHERE tipo = ? AND itemid = ? AND idbanner != ?";
        $request = $this->select($sql, [$strListTpo, $intListItem, $idBanner]);

        if ($request) {
            return 'exist';
        }

        $query_update = "UPDATE home_banner SET nombre = ?, descripcion = ?, img=?, tipo = ?, itemid = ?, ruta =?, status = ? WHERE idbanner = ?";
        $arrData = [$nombre, $descripcion, $img_banner, $strListTpo, $intListItem, $ruta, $status, $idBanner];
        return $this->update($query_update, $arrData);
    }

    public function selectBanners(): array
    {
        return $this->select_all("SELECT idbanner, nombre, img, tipo, status FROM home_banner WHERE status < 2");
    }

    public function selectBanner(int $id): ?array
    {
        return $this->select("SELECT * FROM home_banner WHERE idbanner = ?", [$id]);
    }

    public function actualizaImagenBanner(int $id, string $img): int
    {
        return $this->update("UPDATE home_banner SET img = ? WHERE idbanner = ?", [$img, $id]);
    }
}