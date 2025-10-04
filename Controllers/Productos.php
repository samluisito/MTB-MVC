<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;
use Soundasleep\Html2Text;

class Productos extends Controllers
{
    private int $idModul = 5;

    public function __construct()
    {
        if (empty($_SESSION['login']) && !strpos($_SERVER['REQUEST_URI'], 'getProductosCategoriaCSV')) {
            $login = new Login();
            $login->login();
            exit();
        }
        parent::__construct();
    }

    public function productos()
    {
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) != 1) {
            header('location:' . base_url() . 'dashboard');
            exit();
        }

        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'Productos';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data["page_css"] = [
            "vadmin/libs/choices.js/css/choices.min.css", "vadmin/libs/glightbox/css/glightbox.min.css",
            "plugins/datatables/css/datatables.min.css", "plugins/cropper/css/cropper.min.css",
        ];
        $data["page_functions_js"] = [
            "plugins/jquery/jquery-3.6.0.min.js", "vadmin/libs/choices.js/js/choices.min.js",
            "vadmin/libs/glightbox/js/glightbox.min.js", "plugins/datatables/js/datatables.min.js",
            "plugins/cropper/js/cropper.min.js", "plugins/tinymce/tinymce.min.js",
            "plugins/JsBarcode/JsBarcode.all.min.js", "js/functions_productos.js"
        ];

        $this->views->getView("Productos", $data);
    }

    public function getProductos()
    {
        $categoria = isset($_GET['cat']) ? (intval($_GET['cat']) > 0 ? $_GET['cat'] : null) : null;
        $premin = isset($_GET['premin']) ? (intval($_GET['premin']) > 0 ? $_GET['premin'] : 0) : 0;
        $premax = isset($_GET['premax']) ? (intval($_GET['premax']) > 0 ? $_GET['premax'] : null) : null;
        $estado = isset($_GET['estado']) ? strClean($_GET['estado']) : 't';

        $arrData = $this->model->selectProductos($categoria, $premin, $premax, $estado);

        foreach ($arrData as &$item) {
            $recuest_img = $this->model->imgProdructo($item['idproducto']);
            $img_prod = isset($recuest_img['img']) ? DIR_IMAGEN . 'thumb_4_' . $recuest_img["img"] : DIR_MEDIA . 'images/producto_sin_foto.png';
            $item['img_prod'] = '<img class="minlistprod_img" src="' . $img_prod . '">';
            $item['img_url'] = isset($recuest_img['img']) ? DIR_IMAGEN . $recuest_img["img"] : DIR_MEDIA . 'images/producto_sin_foto.png';
            $item['ruta'] = base_url() . "tienda/producto/{$item['ruta']}";
            $precio_dolar = ($_SESSION['base']['region_abrev'] ?? 'AR') == 'VE' ? 1 : getDolarHoy();
            $item['precio'] = formatMoney(redondear_decenas($item['precio'] * $precio_dolar));

            $id = $item['idproducto'];
            $opciones = "<div class='text-center'>";
            $opciones .= "<button class='btn btn-secondary m-1' onClick='fntVer({$id})' title='Ver'><i class='fas fa-eye'></i></button>";
            $opciones .= ($_SESSION['userPermiso'][$this->idModul]['actualizar'] ?? 0) == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar'><i class='fas fa-edit'></i></button>" : '';
            $opciones .= $item['status'] == 1 ? "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado'><i class='fa fa-power-off'></i></button>" : "<button class='btn btn-danger m-1' onClick='fntStatus({$id})' title='Desactivado'><i class='fa fa-power-off'></i></button>";
            $opciones .= $this->model->productoEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar'><i class='fas fa-trash-alt'></i></button>";
            $item['options'] = $opciones . "</div>";
        }
        exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    public function getProductosCategoriaCSV(): void
    {
        $categoria = isset($_GET['cat']) ? (intval($_GET['cat']) > 0 ? $_GET['cat'] : null) : null;
        $estado = isset($_GET['estado']) ? strClean($_GET['estado']) : 'a';

        $header_desc = [ /* Header descriptions */ ];
        $header_fields = [ /* Header fields */ ];
        $arrCSV = [$header_desc, $header_fields];

        $arrData = $this->model->selectProductosPorCategoria($categoria, $estado);

        foreach ($arrData as $i => $item) {
            $availability = match ($item['stock_status']) {
                'instock' => 'in stock',
                'onbackorder' => 'available for order',
                'outofstock' => 'discontinued',
                default => 'in stock',
            };

            $recuest_img = $this->model->imgProdructoCSV($item['idproducto']);
            $image_link = isset($recuest_img[0]['id']) ? DIR_IMAGEN . 'thumb_1_' . $recuest_img[0]["img"] : DIR_MEDIA . 'images/producto_sin_foto.png';
            $additional_image_link = implode(',', array_map(fn($img) => DIR_IMAGEN . 'thumb_1_' . $img['img'], array_slice($recuest_img, 1, 3)));

            $fechas = '';
            if (ofertaActiva($item)) {
                $fecha_ini = date_format(date_create("{$item['oferta_f_ini']} 00:00"), "Y-m-d\TH:i:sP");
                $fecha_fin = $item['oferta_f_fin'] ? date_format(date_create("{$item['oferta_f_fin']} 23:59"), "Y-m-d\TH:i:sP") : '';
                $fechas = $fecha_fin ? "{$fecha_ini}/{$fecha_fin}" : $fecha_ini;
            }

            $precio_dolar = ($_SESSION['base']['region_abrev'] ?? 'AR') == 'VE' ? 1 : getDolarHoy();
            $moneda = ($_SESSION['base']['region_abrev'] ?? 'AR') == 'VE' ? 'USD' : 'ARS';

            $arrCSV[$i + 2] = [
                'id' => $item['idproducto'],
                'title' => $item['nombre'],
                'description' => Html2Text::convert(html_entity_decode($item['descripcion'])),
                'availability' => $availability,
                'condition' => 'new',
                'price' => number_format(redondear_decenas($item['precio'] * $precio_dolar), 2, '.', '') . ' ' . $moneda,
                'link' => base_url() . "tienda/producto/{$item['idproducto']}/{$item['ruta']}",
                'image_link' => $image_link,
                'additional_image_link' => $additional_image_link,
                // ... other fields
            ];
        }

        $ruta_base = __DIR__ . '/../uploads/';
        $ruta = $ruta_base . FILE_SISTEM_CLIENTE . 'catalog_products.csv';
        generarCSV($arrCSV, $ruta);

        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=\"catalog_products.csv\"");
        readfile($ruta);
        exit();
    }

    // Other methods would be refactored similarly...
}