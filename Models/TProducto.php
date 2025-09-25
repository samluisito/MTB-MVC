<?php

declare(strict_types=1);

//require_once './Librerias/Core/Mysql.php';

trait TProducto {

  private $con;
  private $srtCategoria;
  private $Cat;
  private $intIdCategoria;
  private $strProducto;
  private $intCant;
  private $strRuta;
  private $strOrden;
  private $intIdProducto;
  private $intPersonaid;

  private function ordenarProductos($request_prod) {
    foreach ($request_prod as $index => $producto) {
      $request_prod[$index]['precio'] = $producto['precio'] * getDolarHoy(); //calculamos el precio del peso segun el dolar
      $recuest_img = $this->con->select("SELECT MIN(productoid), img FROM imagen WHERE productoid = {$producto['idproducto']} LIMIT 1");
      $recuest_img['url_img'] = ($recuest_img['img']) ? DIR_IMAGEN . 'thumb_2_' . $recuest_img['img'] : DIR_MEDIA . 'images/producto_sin_foto.png';
      $request_prod[$index]['images'] = $recuest_img;
      /* favorito */
      $request_prod[$index]['favorito'] = isset($_SESSION['login']) && $_SESSION['login'] == 1 ?
          ($this->con->select("SELECT productoid FROM producto_favorito WHERE productoid = {$producto['idproducto']}  AND personaid = {$_SESSION['idUser']}") ? 1 : 0) : 0;
    }
    return $request_prod;
  }

  private function ordenarImagenes($recuest_img) {
    if (count($recuest_img) > 0) {
      foreach ($recuest_img as $i => $img) {
        $recuest_img[$i]['img'] = $img['img'];
        $recuest_img[$i]['url_img'] = DIR_IMAGEN . $img['img'];
        $recuest_img[$i]['url_img_thumb_1'] = DIR_IMAGEN . 'thumb_1_' . $img['img'];
        $recuest_img[$i]['url_img_thumb_2'] = DIR_IMAGEN . 'thumb_2_' . $img['img'];
        $recuest_img[$i]['url_img_thumb_3'] = DIR_IMAGEN . 'thumb_3_' . $img['img'];
        $recuest_img[$i]['url_img_thumb_og'] = DIR_IMAGEN . 'thumb_og_' . pathinfo($img['img'], PATHINFO_FILENAME) . '.jpg';
      }
    } else {
      $recuest_img[0]['img'] = 'producto_sin_foto.png';
      $recuest_img[0]['url_img'] = DIR_MEDIA . 'images/producto_sin_foto.png';
      $recuest_img[0]['url_img_thumb_1'] = DIR_MEDIA . 'images/producto_sin_foto.png';
      $recuest_img[0]['url_img_thumb_2'] = DIR_MEDIA . 'images/producto_sin_foto.png';
      $recuest_img[0]['url_img_thumb_3'] = DIR_MEDIA . 'images/producto_sin_foto.png';
      $recuest_img[0]['url_img_thumb_og'] = DIR_MEDIA . 'images/producto_sin_foto.png';
    }
    return $recuest_img;
  }

  /* ----------------------------------------------------------------------------- */

  function getProductosT($limitProd) {
    $this->con = new Mysql();
    return $this->ordenarProductos($this->con->select_all(
                "SELECT p.idproducto, p.nombre,p.descripcion, p.precio, p.stock, p.ruta
                  FROM producto p INNER JOIN producto_categoria c
                  on p.categoriaid = c.idcategoria
                  WHERE p.status = 1  ORDER BY p.idproducto DESC LIMIT {$limitProd}"));
  }

  /* -------------------------------------------------------------------------------- */

  function getProductosPaginadoT($desde, $limitProd) {
    $this->con = new Mysql();
    //limit con 2 propiedades idica desde que numer contar registros y luego cuantos contara 
    $request_prod = $this->con->select_all("SELECT p.idproducto, p.codigo, p.nombre, p.descripcion, p.etiquetas, p.categoriaid ,
                c.nombre as categoria, p.precio, p.stock, p.ruta, p.status
                FROM producto p INNER JOIN producto_categoria c
                on p.categoriaid = c.idcategoria
                WHERE p.status = 1  ORDER BY p.idproducto DESC LIMIT {$desde}, {$limitProd}");
    if (count($request_prod) > 0) {
      $request_prod = $this->ordenarProductos($request_prod);
    }
    return $request_prod;
  }

  /* contador de productos por producto_categoria para el paginado -------------------------------------------------------------------------------- */

  function countProductosPorCategoria(string $ruta) {
    $this->con = new Mysql();
    $this->strRuta = $ruta;
    $this->Cat = $this->con->select("SELECT idcategoria FROM producto_categoria WHERE ruta = '{$this->strRuta}'")['idcategoria'];
    return !empty($this->Cat) ? $this->con->select("SELECT COUNT(idproducto) AS total_registros FROM producto WHERE categoriaid = '{$this->Cat}' and status = 1 ") : 0;
  }

  /* Get productos paginado por categorias-------------------------------------------------------------------------------- */

  function getProductosCategoriaPaginadoT(string $ruta, $desde, $limitProd) {
    $this->con = new Mysql();
    $this->strRuta = $ruta;
    $this->Cat = $this->con->select("SELECT idcategoria FROM producto_categoria WHERE ruta = '{$this->strRuta}'")['idcategoria'];
    if (!empty($this->Cat)) {
      $request_prod = $this->con->select_all("SELECT p.idproducto, p.codigo, p.nombre, p.descripcion, p.etiquetas, p.categoriaid ,
                c.nombre as categoria, p.precio, p.stock, p.ruta, p.status
                FROM producto p INNER JOIN producto_categoria c on p.categoriaid = c.idcategoria
                WHERE p.status = 1  and  p.categoriaid = {$this->Cat} ORDER BY p.idproducto DESC LIMIT {$desde}, {$limitProd}");
      return count($request_prod) > 0 ? $this->ordenarProductos($request_prod) : "categoria no encontrada";
    }
    return "categoria no encontrada";
  }

  /* contador de productos para el paginado  -------------------------------------------------------------------------------- */

  function countProductos() {
    $this->con = new Mysql();
    return$this->con->select("SELECT COUNT(idproducto) AS total_productos FROM producto WHERE status = 1");
  }

  /* contador de productos por producto_categoria para el paginado por busqueda-------------------------------------------------------------------------------- */

  function countProductosPorBusquedaT(string $search) {
    $this->con = new Mysql();
    return $this->con->select("SELECT COUNT(idproducto) AS total_registros 
      FROM producto WHERE status = 1 AND MATCH(nombre, descripcion, detalle, etiquetas) AGAINST ('{$search}')");
  }

  /* -------------------------------------------------------------------------------- */

  function getProductosBusquedaPaginadoT(string $search, $desde, $limitProd) {
    $this->con = new Mysql();

    $request_prod = $this->con->select_all("SELECT p.idproducto, p.codigo,
                p.nombre, p.descripcion, p.etiquetas, p.categoriaid, c.nombre as categoria,
                p.precio, p.stock, p.ruta, p.status,
                MATCH(p.nombre, p.descripcion, p.detalle,p.etiquetas) AGAINST ('{$search}' ) AS relevance
                FROM producto p INNER JOIN producto_categoria c on p.categoriaid = c.idcategoria              
                WHERE p.status = 1 AND MATCH(p.nombre, p.descripcion, p.detalle, p.etiquetas) AGAINST ('{$search}')                
                 ORDER BY relevance DESC LIMIT {$desde}, {$limitProd}");
    return count($request_prod) > 0 ? $this->ordenarProductos($request_prod) : '';
  }

  /* Extraer datos de un producto  -------------------------------------------------------------------------------- */

  function getProductoId(string $ruta) {
    $this->con = new Mysql();
    $this->strRuta = $ruta;
    $request_prod = $this->con->select("SELECT p.idproducto,
                p.codigo, p.nombre, p.descripcion,p.detalle, p.etiquetas, p.categoriaid ,
                c.nombre as categoria, c.ruta as ruta_categoria, p.precio, p.stock, p.ruta, p.status
                FROM producto p INNER JOIN producto_categoria c on p.categoriaid = c.idcategoria
                WHERE p.ruta = '{$this->strRuta}' ");
    if (!empty($request_prod)) {
      $request_prod['precio'] = $request_prod['precio'] * getDolarHoy(); //calculamos el precio del peso segun el dolar
      $request_prod['images'] = $this->ordenarImagenes($this->con->select_all("SELECT productoid, img FROM imagen WHERE productoid = {$request_prod['idproducto']}"));
      /* favorito */
      $request_prod['favorito'] = isset($_SESSION['login']) && $_SESSION['login'] == 1 ?
          ($this->con->select("SELECT productoid FROM producto_favorito 
            WHERE productoid = {$request_prod['idproducto']}  AND personaid = {$_SESSION['idUser']}") ? 1 : 0) : 0;
    } else {
      $request_prod = "";
    }
    return $request_prod;
  }

  /* -------------------------------------------------------------------------------- */

  public function getProductosRamdom(int $cantidad, string $orden, int $idCategoria = null, int $idProducto = null) {
    $this->intIdCategoria = $idCategoria ? "and  p.categoriaid != $idCategoria " : ' ';
    $this->intIdProducto = $idProducto ? " and  p.idproducto != $idProducto " : ' ';
    $this->intCant = $cantidad;
    switch ($orden) {
      case 'r':$this->strOrden = 'RAND() ';
        break;
      case 'a':$this->strOrden = 'idproducto ASC ';
        break;
      case 'd':$this->strOrden = 'idproducto DESC ';
        break;
      default:$this->strOrden = "";
        break;
    }

    $this->con = new Mysql;
    $request_prod = $this->con->select_all("SELECT p.idproducto, p.codigo, p.nombre, p.categoriaid ,
                c.nombre as categoria, p.precio, p.stock, p.ruta, p.status
                FROM producto p INNER JOIN producto_categoria c on p.categoriaid = c.idcategoria
                WHERE p.status = 1 $this->intIdProducto  $this->intIdCategoria 
                ORDER BY {$this->strOrden}  LIMIT {$this->intCant}");
    return count($request_prod) > 0 ? $this->ordenarProductos($request_prod) : 0;
  }

  /* -------------------------------------------------------------------------------- */

  function getProductoIdInfoCarT(int $idProducto) {
    $this->con = new Mysql();
    $this->intIdProducto = $idProducto;
    $request_prod = $this->con->select("SELECT idproducto, nombre, precio, ruta FROM producto WHERE idproducto = '{$this->intIdProducto}' ");
    if (!empty($request_prod)) {
      $request_prod['precio'] = $request_prod['precio'] * getDolarHoy(); //calculamos el precio del peso segun el dolar
      $recuest_img = $this->con->select("SELECT MIN(id) as id, img FROM imagen WHERE productoid = {$request_prod['idproducto']}");
      $request_prod['images'] = !empty($recuest_img) ?
          DIR_IMAGEN . 'thumb_3_' . $recuest_img['img'] :
          DIR_MEDIA . 'images/producto_sin_foto.png';
      /* favorito */
      $request_prod['favorito'] = isset($_SESSION['login']) && $_SESSION['login'] == 1 ?
          ($this->con->select("SELECT productoid FROM producto_favorito 
            WHERE productoid = {$request_prod['idproducto']}  AND personaid = {$_SESSION['idUser']}") ? 1 : 0) : 0;
    } else {
      $request_prod = "";
    }
    return $request_prod;
  }

  /* -------------------------------------------------------------------------------- */

  public function getProducBlog(int $cantidad, string $orden) {
    $this->intCant = $cantidad;
    switch ($orden) {
      case 'r':$this->strOrden = 'RAND() ';
        break;
      case 'a':$this->strOrden = 'idproducto ASC ';
        break;
      case 'd':$this->strOrden = 'idproducto DESC ';
        break;
      default:$this->strOrden = "";
        break;
    }
    $this->con = new Mysql;
    $request_prod = $this->con->select_all("SELECT idproducto, nombre, precio, ruta FROM producto WHERE status = 1 
                ORDER BY {$this->strOrden}  LIMIT {$this->intCant}");
    return count($request_prod) > 0 ? $this->ordenarProductos($request_prod) : 0;
  }

  /* -------------------------------------------------------------------------------- */

  public function addFav($idprod, $idpers) {
    $this->con = new Mysql;
    $this->intIdProducto = $idprod;
    $this->intPersonaid = $idpers;
    $request = $this->con->select("SELECT personaid FROM producto_favorito WHERE productoid = $this->intIdProducto AND personaid = $this->intPersonaid");
    return !$request ?
        ($this->con->insert(
            'INSERT INTO producto_favorito (productoid, personaid) VALUES  (?,?)',
            array($this->intIdProducto, $this->intPersonaid)) == 0 ? 'ok' : '') : 'existe';
  }

  public function delFav($idprod, $idpers) {
    $this->con = new Mysql;
    $this->intIdProducto = $idprod;
    $this->intPersonaid = $idpers;
    $request = $this->con->select("SELECT personaid FROM producto_favorito WHERE productoid = $this->intIdProducto AND personaid = $this->intPersonaid");
    return $request ?
        ($request_del = $this->con->delete("DELETE FROM producto_favorito 
          WHERE productoid = $this->intIdProducto AND personaid = $this->intPersonaid ") == 1 ? 'ok' : '') : 'inexistente';
  }

}
