<?php

declare(strict_types=1);

class ErrorModel extends Mysql {

  public function __construct() {
    parent::__construct();
  }

  private function ordenarProductosv1($request_prod) {
    $session = isset($_SESSION['login']) && $_SESSION['login'] == 1;
    $favoritos = $session ? $this->select_all_column("SELECT productoid FROM producto_favorito WHERE personaid = {$_SESSION['idUser']}") : null;
    $dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();

    foreach ($request_prod as $index => $producto) {
      $request_prod[$index]['precio'] = $producto['precio'] * $dolar; //calculamos el precio del peso segun el dolar
      $recuest_img = $this->select_column("SELECT img FROM imagen WHERE id = (SELECT MIN(id)as id FROM imagen WHERE productoid = {$producto['idproducto']} )");
      $img['url_img'] = $recuest_img ?
          DIR_IMAGEN . 'thumb_2_' . $recuest_img :
          DIR_MEDIA . 'images/producto_sin_foto.png';
      unset($recuest_img);
      $request_prod[$index]['images'] = $img;
      /* favorito */
      $request_prod[$index]['favorito'] = $session ?
          ((array_search($producto['idproducto'], $favoritos) === 0 ||
          array_search($producto['idproducto'], $favoritos) > 0) ? 1 : 0) : 0;
    }
    unset($session, $favoritos, $dolar);
    return $request_prod;
  }

  private function ordenarProductos($request_prod) {// toma el array con datos de productos y agrega irmagenes y estado de me gusta , segun sea el caso 
    $session = isset($_SESSION['login']) && $_SESSION['login'] == 1;
    $dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();
    reset($request_prod); //reseteo el puntero interno
    $arrCount = count(array_keys($request_prod));
    for ($index = 0; $index < $arrCount; $index++) {
      $request_prod[$index]['precio'] = $request_prod[$index]['precio'] * $dolar; //calculamos el precio del peso segun el dolar
      $recuest_img = $this->select_column("SELECT img FROM imagen WHERE id = (SELECT MIN(id)as id FROM imagen WHERE productoid = {$request_prod[$index]['idproducto']} )");
      $img['url_img'] = $recuest_img ?
          DIR_IMAGEN . 'thumb_2_' . $recuest_img :
          DIR_MEDIA . 'images/producto_sin_foto.png';
      $request_prod[$index]['images'] = $img;
      unset($recuest_img);
      /* favorito */
      $favoritos = $session ? $this->select_all_column("SELECT productoid FROM producto_favorito WHERE personaid = {$_SESSION['idUser']}") : null;
      $request_prod[$index]['favorito'] = $session ?
          ((array_search($request_prod[$index]['idproducto'], $favoritos) === 0 ||
          array_search($request_prod[$index]['idproducto'], $favoritos) > 0) ? 1 : 0) : 0;
      unset($favoritos);
    }
    unset($session, $dolar);
    return $request_prod;
  }

  private function ordanarImagenesBanner($imagenes, $dispositivo) {// funcion interna para ordenar imagenes
    foreach ($imagenes as $i => $imagen) {
      if ($imagenes[$i]['img'] === 'banner.png') {
        $imagenes[$i]['url_img'] = DIR_MEDIA . 'images/' . $imagen['img']; //url Img portada
      } else {
        $imagenes[$i]['url_img'] = $dispositivo === 'mobile' ? DIR_IMAGEN . 'thumb_2_' . $imagen['img'] :
            ($dispositivo === 'tablet' ? DIR_IMAGEN . 'thumb_1_' . $imagen['img'] : DIR_IMAGEN . $imagen['img']);
      }
    }
    return $imagenes;
  }

  /* --------------------------------------------------------------------------------------------------- */

  function getCategoriasMenuTiendaH() {
    $arrData = array();
    $cats = $this->select_all("SELECT c.idcategoria, c.nombre, c.ruta , COUNT(p.categoriaid) AS cantidad 
      FROM producto p INNER JOIN producto_categoria c ON p.categoriaid = c.idcategoria 
      WHERE padre_cat_id IS NULL AND c.status = 1 GROUP BY c.nombre");
    foreach ($cats as $cat) {
      $cat['nombre'] = strtoupper($cat['nombre']);
      array_push($arrData, $cat);
      $subCats = $this->select_all("SELECT c.nombre, c.ruta , COUNT(p.categoriaid) AS cantidad 
        FROM producto p INNER JOIN producto_categoria c ON p.categoriaid = c.idcategoria 
        WHERE padre_cat_id = {$cat['idcategoria']} AND c.status = 1 GROUP BY c.nombre");
      if (count($subCats) > 0) {
        foreach ($subCats as $subCat) {
          $subCat['nombre'] = '· ' . ucwords(strtolower($subCat['nombre']));
          array_push($arrData, $subCat);
        }
      }
    }
    return $arrData;
  }

  function getBanner($dispositivo) {
    $request = $this->select_all("SELECT nombre, descripcion, img, ruta FROM home_banner WHERE status = 1 AND img != 'banner.png'");
    return count($request) > 0 ? $this->ordanarImagenesBanner($request, $dispositivo) : '';
  }

  function getProductos($limitProd) {
    return $this->ordenarProductos($this->select_all(
                "SELECT idproducto, nombre,descripcion, precio, ruta
                  FROM producto p WHERE status = 1 ORDER BY idproducto DESC LIMIT {$limitProd}"));
  }

  function getCategoriasFooter() {
    return $this->select_all("SELECT nombre, ruta FROM producto_categoria WHERE status = 1 LIMIT 4");
  }

//  /* set dolar--------------------------------------------------------------------------------- */
//
//  function getUltimoDolar() {
//    /* devuelve la la ultima cotizacion insertada */
//    return $this->select("SELECT idcotizacion as id ,blue_venta,fecha FROM divisa WHERE `idcotizacion` = (SELECT MAX(idcotizacion) FROM divisa)");
//  }
//
//  function setUltimoDolar($venta_oficial, $compra_oficial, $venta_blue, $compra_blue) {
//    return $this->insert("INSERT INTO divisa (oficial_compra, oficial_venta, blue_compra, blue_venta) VALUES (?,?,?,?)",
//            array($venta_oficial, $compra_oficial, $venta_blue, $compra_blue));
//  }
}
