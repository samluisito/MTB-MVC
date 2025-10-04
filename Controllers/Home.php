<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;
use SitemapPHP\Sitemap;

class Home extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function home()
    {
        $empresa = $_SESSION['info_empresa'];
        $data['empresa'] = $empresa;
        $data['header'] = $this->data_header('Home');
        $data['footer'] = $this->data_footer();

        $data['meta'] = [
            'robots' => 'index, follow, archive',
            'title' => $empresa['nombre_comercial'],
            'description' => substr(strip_tags($empresa['descripcion']), 0, 160),
            'keywords' => $empresa['tags'],
            'url' => base_url(),
            'image' => $empresa['url_logoImpreso'],
            'image:type' => explode('.', $empresa['logo_imp'])[1],
            'og:type' => 'website'
        ];
        unset($empresa);

        $data["page_css"] = [];
        $data["page_functions_js"] = [];

        $data["carrusel"] = $this->model->getBanner(detectar_dispositivo());
        $data["productos"] = $this->model->getProductosH(8);

        $this->views->getView("home", $data);
    }

    /* =============================================================================================== */

    public function data_header(string $page_title): array
    {
        return [
            'page_title' => $page_title,
            'path' => path(),
            'dispositivo' => detectar_dispositivo(),
            'cantCarrito' => cantCarrito(),
            'menu_categorias' => $this->model->getCategoriasMenuTienda()
        ];
    }

    public function data_footer(): array
    {
        return ['footer_cat' => $this->model->getCategoriasFooter()];
    }

    /* setea una cotizacion dolar blue --------------------------------------------------------------- */

    public function liveSesion()
    {
        $session_status = session_status();
        $arrResponse = [
            'time' => date("H:i:s"),
            'status' => $session_status == 2 ? true : false,
            'msg' => session_cache_expire()
        ];
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    /* ----------------------------------------------------------------------------------------------- */

    public function setNewDolar()
    {
        $request = $this->model->getUltimoDolar();

        if (empty($request) || ($request['id'] > 0 && (strtotime(date("Y-m-d H:i:s")) > strtotime($request['fecha'] . "+12 hour")))) {
            if ($_SESSION['base']['region_abrev'] == 'AR') {
                $meta = json_decode(file_get_contents('https://api.bluelytics.com.ar/v2/latest'));
                $oficial_compra = floatval($meta->oficial->value_buy);
                $oficial_venta = floatval($meta->oficial->value_sell);
                $paralelo_compra = floatval($meta->blue->value_buy);
                $paralelo_venta = floatval($meta->blue->value_sell);
            } elseif ($_SESSION['base']['region_abrev'] == 'VE') {
                $precio = $this->dolarBolivasBCV();
                $oficial_compra = $precio;
                $oficial_venta = $precio;
                $paralelo_compra = 0;
                $paralelo_venta = 0;
            }
            $this->model->setUltimoDolar($oficial_compra, $oficial_venta, $paralelo_compra, $paralelo_venta);
            dep(['oficial' => floatval($oficial_venta), 'paralelo' => floatval($paralelo_venta), 'vence' => date("Y-m-d H:i:s", strtotime("+24 hour"))]);
            unset($meta, $request);
        } else {
            dep(['Oficial' => $request['oficial_venta'], 'Paralelo' => $request['blue_venta'], 'vence' => $request['fecha']]);
        }
    }

    private function dolarBolivasBCV()
    {
        $url = "https://www.bcv.org.ve/terminos-condiciones";
        $opts = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
        $context = stream_context_create($opts);
        $content = file_get_contents($url, false, $context);
        preg_match_all("/([0-9]+,[0-9]+)/", $content, $matches);
        $numString = $matches[1][4];
        $numString = str_replace(',', '.', $numString);
        return floatval(number_format(floatval($numString), 2, '.', ''));
    }

    /* ------------------------------------------------------------------------------------------------- */

    public function sitemapxml()
    {
        $url_base = base_url();
        $sitemap = new Sitemap($url_base);
        $ruta = __DIR__ . '/../uploads/' . FILE_SISTEM_CLIENTE;
        $sitemap->setPath($ruta);

        $productos = $this->model->getProductoSiteMap();
        foreach ($productos as $producto) {
            $url = "tienda/producto/{$producto['idproducto']}/{$producto['ruta']}";
            $dateupdate = $producto['dateupdate'];
            $changeFreq = 'weekly';
            $priority = '0.5';
            $sitemap->addItem($url, $priority, $changeFreq, $dateupdate);
        }

        $sitemap->createSitemapIndex(DIR_IMAGEN, 'Today');
        $file_name = 'sitemap-index.xml';
        readfile($ruta . $file_name);
    }
}