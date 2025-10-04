<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class TiendaModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    private function ordenarProductos(array $request_prod): array
    {
        $dolar = ($_SESSION['base']['region_abrev'] ?? 'AR') == 'VE' ? 1 : getDolarHoy();
        foreach ($request_prod as &$producto) {
            $producto['precio'] = redondear_decenas($producto['precio'] * $dolar);
            $producto['oferta'] = redondear_decenas($producto['oferta'] * $dolar);
            $producto['oferta_activa'] = ofertaActiva($producto);
            $producto['ruta'] = "{$producto['idproducto']}/{$producto['ruta']}";

            $imagenes = !empty($producto['imagenes']) ? explode(',', $producto['imagenes']) : [];
            $producto['images'] = [
                'url_img' => !empty($imagenes) ? DIR_IMAGEN . 'thumb_3_' . $imagenes[0] : DIR_MEDIA . 'images/producto_sin_foto.png',
                'url_img_back' => !empty($imagenes) ? DIR_IMAGEN . 'thumb_4_' . $imagenes[0] : DIR_MEDIA . 'images/producto_sin_foto.png'
            ];
            unset($producto['imagenes']);
            $producto['favorito'] = $producto['favorito'] ?? 0;
        }
        return $request_prod;
    }

    public function getMetaCategoria(int $id_cat, string $ruta_cat): ?array
    {
        $sql = "SELECT idcategoria, nombre, descripcion, img, ruta, tags FROM producto_categoria WHERE ";
        $params = [];
        if ($id_cat > 0) {
            $sql .= "idcategoria = ?";
            $params[] = $id_cat;
        } else {
            $sql .= "ruta = ?";
            $params[] = $ruta_cat;
        }
        $request = $this->select($sql, $params);

        if ($request) {
            $request['url_img'] = $request['img'] == 'portada_categoria.png' ? DIR_MEDIA . 'images/' . $request['img'] : DIR_IMAGEN . $request['img'];
        }
        return $request;
    }

    public function getProductosPaginado(int $desde, int $limitProd, ?int $id_cat, ?string $ruta_cat): array
    {
        $idUser = $_SESSION['idUser'] ?? 0;
        $sql_cat = "";
        $params = [$idUser];

        if ($id_cat !== null || $ruta_cat !== null) {
            $sql_id_ruta = $id_cat > 0 ? "idcategoria = ?" : "ruta = ?";
            $param_cat = $id_cat > 0 ? $id_cat : $ruta_cat;
            $cat_id = $this->select_column("SELECT idcategoria FROM producto_categoria WHERE $sql_id_ruta", [$param_cat]);
            if($cat_id){
                $sql_cat = "AND p.categoriaid = ?";
                $params[] = $cat_id;
            }
        }

        $params[] = $desde;
        $params[] = $limitProd;

        $sql = "SELECT p.idproducto, p.nombre, p.descripcion, p.precio, p.oferta, p.oferta_f_ini, p.oferta_f_fin, p.ruta, p.status,
                       GROUP_CONCAT(i.img) AS imagenes,
                       IF(pf.productoid IS NULL, 0, 1) AS favorito
                FROM producto p
                LEFT JOIN producto_categoria c ON p.categoriaid = c.idcategoria
                LEFT JOIN imagen i ON p.idproducto = i.productoid AND i.posicion = 0
                LEFT JOIN producto_favorito pf ON p.idproducto = pf.productoid AND pf.personaid = ?
                WHERE p.status = 1 {$sql_cat}
                GROUP BY p.idproducto
                ORDER BY p.idproducto DESC LIMIT ?, ?";

        $request_prod = $this->select_all($sql, $params);
        return !empty($request_prod) ? $this->ordenarProductos($request_prod) : [];
    }

    public function getProductoId(int $id_prod, string $ruta_prod): ?array
    {
        $idUser = $_SESSION['idUser'] ?? 0;
        $sql_id_ruta = $id_prod > 0 ? "p.idproducto = ?" : "p.ruta = ?";
        $param = $id_prod > 0 ? $id_prod : $ruta_prod;

        $sql_prod = "SELECT p.*, c.nombre as categoria, c.ruta as ruta_categoria,
                            COALESCE((SELECT 1 FROM producto_favorito WHERE productoid = p.idproducto AND personaid = ?), 0) AS favorito
                     FROM producto p
                     INNER JOIN producto_categoria c ON p.categoriaid = c.idcategoria
                     WHERE {$sql_id_ruta}";

        $request_prod = $this->select($sql_prod, [$idUser, $param]);

        if (!empty($request_prod)) {
            // Processing logic from controller would go here...
        }
        return $request_prod;
    }
}