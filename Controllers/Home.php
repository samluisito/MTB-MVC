<?php

declare(strict_types=1);

class Home extends Controllers {

  function __construct() {
    parent::__construct();
  }

  function Home() {


//    if (empty($_POST['b'])) {
//      $data['b'] = 0;
//    } else if (isset($_POST['b']) && $_POST['b'] = 1) {
//      
//    }
//            $data['b'] = 1;
    $empresa = $_SESSION['info_empresa'];
    $data['empresa'] = $empresa;
    $data['header'] = $this->data_header('Home');

    $data['footer'] = $this->data_footer();

    $data['meta'] = array(
      'robots' => 'index, follow, archive',
      'title' => $empresa['nombre_comercial'],
      'description' => substr(strip_tags($empresa['descripcion']), 0, 160),
      'keywords' => $empresa['tags'],
      'url' => base_url(),
      'image' => $empresa['url_logoImpreso'],
      'image:type' => explode('.', $empresa['logo_imp'])[1],
      'og:type' => 'website'
    );
    unset($empresa);

    // las funciones de la pagina van de ultimo 
    $data["page_css"] = array();
    $data["page_functions_js"] = array();

    $data["carrusel"] = $this->model->getBanner(detectar_dispositivo()); //categoria de productos en el banner    //$data["banner"] = $this->getCategoriasT()['banner']; // producto_categoria de productos en banner



    $data["productos"] = $this->model->getProductosH(8); // ingresar limite de productos por pag


    $this->views->getView("home", $data);
  }

  /* =============================================================================================== */

  function data_header(string $page_title): array {
    return [
      'page_title' => $page_title,
      'path' => path(),
      'dispositivo' => detectar_dispositivo(),
      'cantCarrito' => cantCarrito(),
      'menu_categorias' => $this->model->getCategoriasMenuTienda()
    ];
  }

  function data_footer(): array {
    return ['footer_cat' => $this->model->getCategoriasFooter()];
  }

  /* setea una cotizacion dolar blue --------------------------------------------------------------- */

  function liveSesion() {
    $session_status = session_status();
    $arrResponse = array(
      'time' => date("H:i:s"),
      'status' => $session_status == 2 ? true : false,
      'msg' => session_cache_expire()
    );
    json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  /* ----------------------------------------------------------------------------------------------- */

  function setNewDolar() {
    $request = $this->model->getUltimoDolar();

    if (empty($request) || ($request['id'] > 0 && (strtotime(date("Y-m-d H:i:s")) > strtotime($request['fecha'] . "+12 hour")))) {
      if ($_SESSION['base']['region_abrev'] == 'AR') {
        $meta = json_decode(file_get_contents('https://api.bluelytics.com.ar/v2/latest'));
        $oficial_compra = floatval($meta->oficial->value_buy);
        $oficial_venta = floatval($meta->oficial->value_sell);
        $paralelo_compra = floatval($meta->blue->value_buy);
        $paralelo_venta = floatval($meta->blue->value_sell);
      } else if ($_SESSION['base']['region_abrev'] == 'VE') {
        $precio = $this->dolarBolivasBCV();
        $oficial_compra = $precio;
        $oficial_venta = $precio;
        $paralelo_compra = 0;
        $paralelo_venta = 0;
      }
      $this->model->setUltimoDolar($oficial_compra, $oficial_venta, $paralelo_compra, $paralelo_venta);
      dep(array('oficial' => floatval($oficial_venta), 'paralelo' => floatval($paralelo_venta), 'vence' => date("Y-m-d H:i:s", strtotime("+24 hour")))); // pasa un array a la variable de sesion con los datos de la nueva fecha.

      unset($meta, $request);
    } else {
      dep(array('Oficial' => $request['oficial_venta'], 'Paralelo' => $request['blue_venta'], 'vence' => $request['fecha'])); // pasa un array a la variable de sesion con los datos de la nueva fecha.
    }
  }

  private function dolarBolivasBCV() {
// retorna el valor del dolar BVC
    $url = "https://www.bcv.org.ve/terminos-condiciones"; // URL del banco central
    $opts = array(
      'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
      )
    );

    $context = stream_context_create($opts);
    $content = file_get_contents($url, false, $context);
    preg_match_all("/([0-9]+,[0-9]+)/", $content, $matches); // Buscamos varios números con una expresión regular guardamos los resultados en $matches
    $numString = $matches[1][4]; // Almacenamos el cuarto valor del segundo array
    $numString = str_replace(',', '.', $numString);

    $floatValue = floatval($numString);
    return floatval(number_format($floatValue, 2, '.', ''));
  }

  /* ------------------------------------------------------------------------------------------------- */

  function sitemapxml() {
    /* entrega un documento xml con la data para url site map de gogle https://www.sitemaps.org/protocol.html
      https://www.sitemaps.org/protocol.html */
    require_once ('Librerias/vendor/evert/sitemap-php/src/SitemapPHP/Sitemap.php'); //include __DIR__ . '/.././Librerias/ 
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

    //$sitemap->setDomain($url_base);
    $sitemap->createSitemapIndex(DIR_IMAGEN, 'Today');

    $file_name = 'sitemap-index.xml';

//    header("Content-Description: File Transfer");
//    header("Content-Type: application/octet-stream");
//    header("Content-Disposition: attachment; filename=\"" . $file_name . "\"");
//
    readfile($ruta . $file_name);
//    header("Location:". DIR_IMAGEN . $file_name);
  }

  /* correcion al dolar mal seteado */
//   function corrigeDolarDolar() {
//    $cotizaciones = $this->model->getCotizaciones();
//    foreach ($cotizaciones as $value) {
//     // dep('------------------------------------');
//      dep($value);
////      $this->model->updateCotizacion($value['oficial_venta'], $value['oficial_compra'], $value['blue_venta'], $value['blue_compra'], $value['idcotizacion']);
////      dep($this->model->getDolarID($value['idcotizacion']));
//    }
//  }
}
