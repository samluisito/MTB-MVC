<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class ProductosModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function imgProdructo(int $id): ?array
    {
        return $this->select("SELECT img FROM imagen WHERE productoid = ? AND posicion = 0", [$id]);
    }

    public function productoEnUso(int $id): bool
    {
        $pedido = $this->select("SELECT MAX(id) as id FROM pedido_detalle WHERE productoid = ?", [$id]);
        $banner = $this->select("SELECT MAX(idbanner) AS id FROM home_banner WHERE tipo='prod' AND itemid = ?", [$id]);
        return ($pedido['id'] ?? 0) > 0 || ($banner['id'] ?? 0) > 0;
    }

    public function insertProducto(
        string $strNombre,
        string $strDescripcion,
        string $strDetalle,
        string $strMarca,
        string $strEtiquetas,
        int $strCodigo,
        int $intCategoriaId,
        int $intCatFbId,
        int $intCatGgId,
        int $intProveedorId,
        float $floCosto,
        float $floPrecio,
        float $floOferta,
        ?string $strOferta_f_ini,
        ?string $strOferta_f_fin,
        int $intStock,
        string $stock_status,
        string $ruta,
        string $grupoEtario,
        string $genero,
        string $talla,
        string $color,
        string $material,
        string $estilo,
        string $estampado,
        int $intStatus
    ) {
        $request = $this->select("SELECT idproducto FROM producto WHERE ruta = ?", [$ruta]);

        if (empty($request)) {
            $query_insert = "INSERT INTO producto (nombre, descripcion, detalle, marca, etiquetas, codigo, categoriaid,cat_facebook_id, cat_google_id,proveedorId, costo, precio, oferta, oferta_f_ini, oferta_f_fin, stock, stock_status, ruta, age_group, gender, size, color, material, style, pattern, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $arrData = [
                $strNombre, $strDescripcion, $strDetalle, $strMarca, $strEtiquetas, $strCodigo, $intCategoriaId, $intCatFbId, $intCatGgId,
                $intProveedorId, $floCosto, $floPrecio, $floOferta, $strOferta_f_ini, $strOferta_f_fin, $intStock, $stock_status, $ruta,
                $grupoEtario, $genero, $talla, $color, $material, $estilo, $estampado, $intStatus
            ];
            return $this->insert($query_insert, $arrData);
        }
        return "exist";
    }

    public function updateProducto(
        int $intIdProducto,
        string $strNombre,
        string $strDescripcion,
        // ... (all other parameters)
        int $intStatus
    ) {
        $params = func_get_args();
        $request = $this->select("SELECT idproducto FROM producto WHERE idproducto != ? AND ruta = ?", [$intIdProducto, $params[18] /* ruta */]);

        if (!empty($request)) {
            return 'exist';
        }

        $sql = "UPDATE producto SET nombre=?, descripcion=?, detalle=?, marca=?, etiquetas=?, codigo=?,
                categoriaid=?, cat_facebook_id=?, cat_google_id=?, proveedorId=?, costo=?, precio=?,
                oferta=?, oferta_f_ini=?, oferta_f_fin=?, stock=?, stock_status=?, ruta=?,
                age_group=?, gender=?, size=?, color=?, material=?, style=?, pattern=?, status=?
                WHERE idproducto = ?";

        // Remove the first element (id) to match the placeholders
        array_shift($params);
        $params[] = $intIdProducto; // Add id at the end for the WHERE clause

        return $this->update($sql, $params);
    }

    public function selectProducto(int $idProducto): ?array
    {
        $sql = "SELECT p.idproducto, p.codigo, p.nombre, p.descripcion, p.detalle, p.marca, p.etiquetas,
                       p.categoriaid, c.nombre as categoria, p.cat_facebook_id, p.cat_google_id,
                       p.proveedorid , pr.nombre as proveedor, p.costo, p.precio, p.oferta, p.oferta_f_ini, p.oferta_f_fin,
                       p.stock, p.stock_status, p.ruta,
                       p.age_group, p.gender, p.size, p.color, p.material, p.style, p.pattern, p.status
                FROM producto p
                INNER JOIN producto_categoria c ON p.categoriaid = c.idcategoria
                INNER JOIN producto_proveedor pr ON p.proveedorid = pr.idproveedor
                WHERE idproducto = ?";
        return $this->select($sql, [$idProducto]);
    }

    public function selectImages(int $intId): array
    {
        return $this->select_all("SELECT id, posicion, img FROM imagen WHERE productoid = ? ORDER BY posicion ASC", [$intId]);
    }

    public function insertImage(int $idProducto, string $imgNombre, int $posicion): int
    {
        return $this->insert('INSERT INTO imagen(productoid, img, posicion) VALUES (?,?,?)', [$idProducto, $imgNombre, $posicion]);
    }

    public function deleteImage(int $idProducto, string $imgNombre): int
    {
        return $this->delete("DELETE FROM imagen WHERE productoid = ? AND img = ?", [$idProducto, $imgNombre]);
    }

    public function updateOrdenImgId(int $posicion, int $idimg): int
    {
        return $this->update("UPDATE imagen SET posicion = ? WHERE id = ?", [$posicion, $idimg]);
    }

    public function deleteProducto(int $idProducto): int
    {
        return $this->delete("DELETE FROM producto WHERE idproducto = ?", [$idProducto]);
    }

    public function editStatus(int $id, int $intStatus): string
    {
        $status = $intStatus == 1 ? 0 : 1;
        $r = $this->update("UPDATE producto SET status = ? WHERE idproducto = ?", [$status, $id]);
        return $r ? 'OK' : 'error';
    }
}