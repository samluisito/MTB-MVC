<?php

declare(strict_types=1);

class HomeModel extends Mysql {

  public function __construct() {

    parent::__construct();
  }

  private function ordenarImagenesBanner($imagenes, $dispositivo) {
    $tamanos = [
      'mobile' => 'thumb_3_',
      'tablet' => 'thumb_2_',
      'desktop' => 'thumb_1_',
    ];

    foreach ($imagenes as $i => $imagen) {
      $img = $imagen['img'];
      $url_img = $img === 'banner.png' ? DIR_MEDIA . 'images/' . $img : DIR_IMAGEN . $tamanos[$dispositivo] . $img;
      $imagenes[$i]['url_img'] = $url_img;
    }
    return $imagenes;
  }

  /* Home --------------------------------------------------------------------------------------------------- */

  function getCategoriasMenuTienda() {
    $arrData = array();

    $query = "SELECT idcategoria, nombre, ruta, padre_cat_id FROM producto_categoria WHERE status = 1 ORDER BY padre_cat_id ASC, nombre ASC";
    $categorias = $this->select_all($query);

    $padres = array();
    foreach ($categorias as $categoria) {
      if (!$categoria['padre_cat_id']) {
        $categoria['nombre'] = strtoupper($categoria['nombre']);
        $padres[$categoria['idcategoria']] = $categoria;
      } else {
        $padre = $padres[$categoria['padre_cat_id']];
        $categoria['nombre'] = '· ' . ucwords(strtolower($categoria['nombre']));
        if (!isset($padre['subcategorias'])) {
          $padre['subcategorias'] = array();
        }
        $padre['subcategorias'][] = $categoria;
        $padres[$categoria['padre_cat_id']] = $padre;
      }
    }

    foreach ($padres as $padre) {
      $arrData[] = $padre;
      if (isset($padre['subcategorias'])) {
        foreach ($padre['subcategorias'] as $subcategoria) {
          $arrData[] = $subcategoria;
        }
      }
    }

    return $arrData;
  }

  function getBanner($dispositivo) {
    $request = $this->select_all("SELECT nombre, descripcion, img, ruta FROM home_banner WHERE status = 1 AND img != 'banner.png'");
    return count($request) > 0 ? $this->ordenarImagenesBanner($request, $dispositivo) : '';
  }

  function getProductosH($limitProd) {
    $dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();
    $likes = isset($_SESSION['login']) && $_SESSION['login'] == 1 ? $this->select_all_column("SELECT productoid FROM producto_favorito WHERE personaid = {$_SESSION['idUser']}") : array();
    $arrayData = $this->select_all(
        "SELECT p.idproducto, p.nombre, p.descripcion, p.precio, p.oferta, p.oferta_f_ini, p.oferta_f_fin, p.ruta, i.img
         FROM producto p LEFT JOIN imagen i ON p.idproducto = i.productoid AND i.posicion = 0
         WHERE p.status = 1
         ORDER BY p.idproducto DESC LIMIT " . $limitProd
    );
    $productos = array();
    foreach ($arrayData as $producto) {
      $producto['precio'] = redondear_decenas($producto['precio'] * $dolar);
      $producto['oferta'] = redondear_decenas($producto['oferta'] * $dolar);
      $producto['oferta_activa'] = ofertaActiva($producto);
      $producto['ruta'] = "{$producto['idproducto']}/{$producto['ruta']}";
      $producto['images'] = array(
        'url_img' => empty($producto['img']) ? DIR_MEDIA . 'images/producto_sin_foto.png' : DIR_IMAGEN . 'thumb_3_' . $producto['img'],
        'url_img_back' => empty($producto['img']) ? DIR_MEDIA . 'images/producto_sin_foto.png' : DIR_IMAGEN . 'thumb_4_' . $producto['img']
      );
      $producto['favorito'] = isset($_SESSION['login']) && $_SESSION['login'] == 1 && is_array($likes) ? (in_array($producto['idproducto'], $likes) ? 1 : 0) : 0;
      $productos[] = $producto;
    }
    unset($dolar, $likes, $producto);
    return $productos;
  }

  function getCategoriasFooter() {
    return $this->select_all("SELECT nombre, ruta FROM producto_categoria WHERE status = 1 LIMIT 4");
  }

  /* Get - Set dolar ================================================================================ */

  function getUltimoDolar() {
    /* devuelve la la ultima cotizacion insertada */
    return $this->select("SELECT idcotizacion as id, oficial_venta ,blue_venta,fecha FROM divisa WHERE `idcotizacion` = (SELECT MAX(idcotizacion) FROM divisa)");
  }

  function setUltimoDolar($venta_oficial, $compra_oficial, $venta_blue, $compra_blue) {
    return $this->insert("INSERT INTO divisa (oficial_compra, oficial_venta, blue_compra, blue_venta) VALUES (?,?,?,?)",
            array($venta_oficial, $compra_oficial, $venta_blue, $compra_blue));
  }

  /* SITE MAP xml========================================================================================= */

  function getProductoSiteMap() {
    return $this->select_all("SELECT `idproducto`,`ruta`,`dateupdate` FROM `producto` WHERE status = 1;");
  }

  /* Get - Set dolar--------------------------------------------------------------------------------- */
  /*  function getCotizaciones() {
    return $this->select_all("SELECT * FROM divisa");
    } */
  /* Correccion de carga en cotizacion inicial--------------------------------------------------------------------------------- */
  /*
    function updateCotizacion($oficial_compra, $oficial_venta, $blue_compra, $blue_venta, $idcotizacion) {
    return $this->update("UPDATE divisa SET oficial_compra = ?, oficial_venta = ?, blue_compra=?, blue_venta = ? WHERE idcotizacion = ?",
    array($oficial_compra, $oficial_venta, $blue_compra, $blue_venta, $idcotizacion));
    }

    function getDolarID($id) {
    return $this->select("SELECT * FROM divisa WHERE idcotizacion = $id");
    } */
}

/*

ALTER TABLE `producto` ADD `dateupdate` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `datecreated`;
*/