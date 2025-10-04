<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Tienda extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function tienda()
    {
        $homeController = new Home();
        $data['header'] = $homeController->data_header('Tienda');
        $data['footer'] = $homeController->data_footer();

        $empresa = $_SESSION['info_empresa'];
        $data['empresa'] = $empresa;
        $data['dispositivo'] = detectar_dispositivo();

        $data['meta'] = [
            'robots' => 'index, follow, archive',
            'title' => $empresa['nombre_comercial'],
            'description' => substr(strClean(strip_tags($empresa['descripcion'])), 0, 160),
            'keywords' => $empresa['tags'],
            'url' => base_url(),
            'image' => $empresa['url_logoImpreso'],
            'image:type' => 'image/' . pathinfo($empresa['logo_imp'], PATHINFO_EXTENSION),
            'og:type' => 'website'
        ];

        $pagina = isset($_GET['page']) ? (is_numeric($_GET['page']) ? intval($_GET['page']) : 1) : 1;
        $data += $this->paginar(24, $pagina);

        $data['page_css'] = [];
        $data['page_functions_js'] = [];

        $this->views->getView('Tienda', $data);
    }

    private function paginar(int $limit_productos, int $pagina, int $id_cat = null, string $ruta_cat = null): array
    {
        $total_registro = $this->model->countProductos($id_cat, $ruta_cat)['total_registros'];
        $desde = ($pagina - 1) * $limit_productos;
        $total_paginas = ceil($total_registro / $limit_productos);
        $productos = $this->model->getProductosPaginado($desde, $limit_productos, $id_cat, $ruta_cat);
        return [
            'pagina' => $pagina,
            'total_pagina' => $total_paginas,
            'productos' => $productos
        ];
    }

    public function categoria($param)
    {
        if (empty($param)) {
            $this->tienda();
            return;
        }

        $homeController = new Home();
        $data['header'] = $homeController->data_header('Categorias');
        $data['footer'] = $homeController->data_footer();

        $empresa = $_SESSION['info_empresa'];
        $data['empresa'] = $empresa;
        $data['dispositivo'] = detectar_dispositivo();

        $arrParam = explode(',', $param);
        $id_cat = is_numeric($arrParam[0]) ? intval($arrParam[0]) : 0;
        $ruta_cat = strClean($arrParam[count($arrParam) - 1]);

        $meta = $this->model->getMetaCategoria($id_cat, $ruta_cat);

        if ($meta) {
            $data['meta'] = [
                'robots' => 'index, follow, archive',
                'title' => 'Categoria: ' . $meta['nombre'],
                'description' => substr(strClean(strip_tags($meta['descripcion'])), 0, 160),
                'keywords' => $meta['tags'],
                'url' => base_url() . 'tienda/categoria/' . $meta['idcategoria'] . '/' . $meta['ruta'],
                'image' => $meta['url_img'],
                'image:type' => 'image/' . pathinfo($meta['img'], PATHINFO_EXTENSION),
                'og:type' => 'product'
            ];
            $data['categoria'] = ['nombre' => $meta['nombre'], 'ruta' => $meta['ruta']];
            $pagina = isset($_GET['page']) ? (is_numeric($_GET['page']) ? intval($_GET['page']) : 1) : 1;
            $data += $this->paginar(24, $pagina, $meta['idcategoria'], $meta['ruta']);
        }

        $data['page_css'] = [];
        $data['page_functions_js'] = [];

        $this->views->getView(empty($data['meta']) ? 'ErrorTienda' : 'Tienda', $data);
    }

    public function addCarrito()
    {
        if (!$_POST) {
            exit();
        }

        $idProducto = intval($_POST['id']);
        $cantidad = intval($_POST['cant']);

        if ($idProducto > 0 && $cantidad > 0) {
            $arrDataProducto = $this->model->getProductoIdInfoCar($idProducto);
            if (empty($arrDataProducto)) {
                $arrResponse = ['status' => false, 'msg' => 'Producto no existe'];
            } else {
                $arrProducto = [
                    'idproducto' => $idProducto,
                    'nombre' => $arrDataProducto['nombre'],
                    'cantidad' => $cantidad,
                    'precio' => $arrDataProducto['precio'],
                    'oferta' => ($arrDataProducto['oferta_activa'] ?? 0) === 1 ? $arrDataProducto['oferta'] : 0,
                    'talle' => strClean($_POST['talle']),
                    'color' => strClean($_POST['color']),
                    'ruta' => $arrDataProducto['ruta'],
                    'img' => $arrDataProducto['images']
                ];

                $arrCarrito = $_SESSION['arrCarrito'] ?? [];
                $productExists = false;
                foreach ($arrCarrito as &$item) {
                    if ($item['idproducto'] === $idProducto) {
                        $item['cantidad'] += $cantidad;
                        $productExists = true;
                        break;
                    }
                }
                if (!$productExists) {
                    $arrCarrito[] = $arrProducto;
                }
                $_SESSION['arrCarrito'] = $arrCarrito;

                $totalCarrito = 0;
                $cantCarrito = 0;
                $htmlCarrito = '';
                foreach ($_SESSION['arrCarrito'] as $pro) {
                    $totalCarrito += $pro['cantidad'] * ($pro['oferta'] > 0 ? $pro['oferta'] : $pro['precio']);
                    $cantCarrito += $pro['cantidad'];
                    $htmlCarrito .= html_producto_carrito($pro);
                }

                $arrResponse = [
                    'status' => true,
                    'msg' => "Agregaste {$arrDataProducto['nombre']} al carrito",
                    'cantCarrito' => $cantCarrito,
                    'htmlCarrito' => $htmlCarrito,
                    'totalCarrito' => formatMoney($totalCarrito)
                ];
            }
        } else {
            $arrResponse = ['status' => false, 'msg' => 'Dato incorrecto'];
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }
}