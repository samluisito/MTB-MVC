<?php

declare(strict_types=1);

class TiendaModel extends Mysql {

  public function __construct() {
    parent::__construct();
  }

  /* {TOOLS}*========================================================================================== */

  private function ordenarProductos($request_prod) {
    $dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();
    foreach ($request_prod as &$producto) {
      $producto['precio'] = redondear_decenas($producto['precio'] * $dolar);
      $producto['oferta'] = redondear_decenas($producto['oferta'] * $dolar);
      $producto['oferta_activa'] = ofertaActiva($producto);

      $producto['ruta'] = "{$producto['idproducto']}/{$producto['ruta']}";
      $imagenes = $producto['imagenes'] ? explode(',', $producto['imagenes']) : [];
      $producto['images'] = array(
        'url_img' => count($imagenes) > 0 ? DIR_IMAGEN . 'thumb_3_' . $imagenes[0] : DIR_MEDIA . 'images/producto_sin_foto.png',
        'url_img_back' => count($imagenes) > 0 ? DIR_IMAGEN . 'thumb_4_' . $imagenes[0] : DIR_MEDIA . 'images/producto_sin_foto.png'
      );
      unset($producto['imagenes']);

      $producto['favorito'] = $producto['favorito'] ?? 0;
    }
    unset($dolar, $producto);
    return $request_prod;
  }

  /* [TIENDA][CATEGORIAS]============================================================================================================================ */

  function getMetaCategoria($id_cat, $ruta_cat) {
    $sql_id_ruta = $id_cat > 0 ? "idcategoria = {$id_cat}" : "ruta = '{$ruta_cat}'";

    $request = $this->select("SELECT idcategoria, nombre, descripcion, img , ruta , tags FROM producto_categoria WHERE " . $sql_id_ruta);
    if ($request) {
      $request['url_img'] = $request['img'] == 'portada_categoria.png' ? DIR_MEDIA . $request['img'] : DIR_IMAGEN . $request['img'];
    }return $request;
  }

  /* Get productos paginado por categorias-------------------------------------------------------------------------------- */

  function getCategoriaIds(int $id_cat, string $ruta_cat) {
    $sql_id_ruta = $id_cat > 0 ? "idcategoria = {$id_cat}" : "ruta = '{$ruta_cat}'";
    $cat_id = $this->select_column("SELECT idcategoria FROM producto_categoria WHERE $sql_id_ruta");
    $cat_hijo = $this->select_all_column("SELECT idcategoria FROM producto_categoria WHERE padre_cat_id = $cat_id");
    if ($cat_hijo !== false && count($cat_hijo) > 0) {
      foreach ($cat_hijo as $cat) {
        $cat_id .= ",$cat";
      }
    }
    return !empty($cat_id) ? array('ids' => $cat_id, 'total' => $this->select("SELECT COUNT(idproducto) AS total_registros FROM producto WHERE categoriaid IN ({$cat_id}) AND status = 1 ")) : null;
  }

  function countProductos(int $id_cat = null, string $ruta_cat = null) {
    if ($id_cat !== null && $ruta_cat !== null) {
      $categoriaIds = $this->getCategoriaIds($id_cat, $ruta_cat);
      return $categoriaIds !== null ? $categoriaIds['total'] : 0;
    } else {
      return $this->select("SELECT COUNT(idproducto) AS total_registros FROM producto WHERE status = 1");
    }
  }

  function getProductosPaginado(int $desde, int $limitProd, int $id_cat = null, string $ruta_cat = null) {
    $sql_cat = '';
    $categoriaIds = 1;
    $idUser = $_SESSION['idUser'] ?? 0;
    if ($id_cat !== null && $ruta_cat !== null) {
      $categoriaIds = $this->getCategoriaIds($id_cat, $ruta_cat);
      $sql_cat = "AND  p.categoriaid in ({$categoriaIds['ids']})";
    }
    if ($categoriaIds !== null) {
      $request_prod = $this->select_all("SELECT p.idproducto, p.codigo, p.nombre, p.etiquetas, p.categoriaid ,
              c.nombre as categoria, p.precio, p.oferta, p.oferta_f_ini, p.oferta_f_fin, p.stock, p.stock_status, p.ruta, p.status,
              GROUP_CONCAT(i.img) AS imagenes,
              IF(pf.productoid IS NULL, 0, 1) AS favorito
              FROM producto p
              INNER JOIN producto_categoria c on p.categoriaid = c.idcategoria
              LEFT JOIN imagen i ON p.idproducto = i.productoid AND i.posicion = 0
              LEFT JOIN producto_favorito pf ON p.idproducto = pf.productoid AND pf.personaid = {$idUser}
              WHERE p.status = 1  $sql_cat
              GROUP BY p.idproducto
              ORDER BY p.idproducto DESC LIMIT {$desde}, {$limitProd}");
      return count($request_prod) > 0 ? $this->ordenarProductos($request_prod) : "categoria no encontrada";
    }
    return "categoria no encontrada";
  }

  /* contador de productos por producto_categoria para el paginado por busqueda-------------------------------------------------------------------------------- */
  /* [BUSQUEDA]================================================================================ */

  function countProductosPorBusquedaT(string $search) {
    $search = str_ireplace(' ', '* ', $search);
    $sql_match = "MATCH(nombre, descripcion, detalle, etiquetas) AGAINST ('+$search' IN BOOLEAN MODE)";
    return $this->select("SELECT COUNT(idproducto) AS total_registros, $sql_match AS relevance FROM producto WHERE status = 1 AND $sql_match ORDER BY relevance DESC ");
  }

  /* -------------------------------------------------------------------------------- */

  function getProductosBusquedaPaginado(string $search, int $desde, int $limitProd) {
    $idUser = $_SESSION['idUser'] ?? 0;
    $search = str_ireplace(' ', '* ', $search);
    $sql_match = "MATCH(p.nombre, p.descripcion, p.detalle,p.etiquetas) AGAINST ('+$search' IN BOOLEAN MODE)";
    $sql = "SELECT p.idproducto, p.codigo,
                p.nombre, p.etiquetas, p.categoriaid, c.nombre as categoria,
                p.precio, p.oferta, p.oferta_f_ini, p.oferta_f_fin, p.stock, p.stock_status, p.ruta, p.status,
                $sql_match AS relevance, 
                img.img AS imagenes, 
                fav.personaid IS NOT NULL AS favorito
            FROM producto p 
            INNER JOIN producto_categoria c ON p.categoriaid = c.idcategoria 
            LEFT JOIN imagen img ON p.idproducto = img.productoid AND img.posicion = 0 
            LEFT JOIN producto_favorito fav ON p.idproducto = fav.productoid AND fav.personaid = {$idUser} 
            WHERE p.status = 1 AND $sql_match 
            ORDER BY relevance DESC 
            LIMIT {$desde}, {$limitProd}";

    $request_prod = $this->select_all($sql);
    return count($request_prod) > 0 ? $this->ordenarProductos($request_prod) : "categoria no encontrada";
  }

  /* [PRODUCTO]================================================================================ */

  /* Extraer datos de un producto  -------------------------------------------------------------------------------- */

  function selectCatPadre($id) {
    return $this->select("SELECT idcategoria, nombre, ruta FROM producto_categoria WHERE idcategoria = (
         SELECT padre_cat_id FROM producto_categoria WHERE idcategoria = $id)");
  }

  function getProductoId(int $id_prod, string $ruta_prod) {
    $idUser = $_SESSION['idUser'] ?? 0;

    $sql_id_ruta = $id_prod > 0 ? "p.idproducto = {$id_prod}" : "p.ruta = '{$ruta_prod}'";
    $sql_prod = "SELECT p.idproducto, p.codigo, p.nombre, p.descripcion, p.detalle, p.etiquetas, p.categoriaid,
                c.nombre as categoria, c.ruta as ruta_categoria, p.precio, p.oferta, p.oferta_f_ini, p.oferta_f_fin,
                p.stock, p.stock_status, p.ruta, p.color, p.size, p.material, p.pattern, p.style, p.status,
                COALESCE((SELECT 1 FROM producto_favorito WHERE productoid = p.idproducto AND personaid = {$idUser}), 0) AS favorito
                FROM producto p
                INNER JOIN producto_categoria c ON p.categoriaid = c.idcategoria
                WHERE {$sql_id_ruta}";

    $request_prod = $this->select($sql_prod);

    if (!empty($request_prod)) {
      $request_prod['descripcion'] = html_entity_decode($request_prod['descripcion']);
      $request_prod['detalle'] = html_entity_decode($request_prod['detalle']);
      $request_prod['color'] = $request_prod['color'] != '' ? explode(',', $request_prod['color']) : null;
      $request_prod['size'] = $request_prod['size'] != '' ? explode(',', $request_prod['size']) : null;
      $precio_dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();
      $request_prod['precio'] = redondear_decenas($request_prod['precio'] * $precio_dolar);
      $request_prod['oferta'] = redondear_decenas($request_prod['oferta'] * $precio_dolar);
      $request_prod['oferta_activa'] = ofertaActiva($request_prod);
      $request_prod['images'] = $this->ordenarImagenes($request_prod['idproducto']);
    }
    return $request_prod;
  }

  private function ordenarImagenes($idproducto) {
    $recuest_img = $this->select_all("SELECT img FROM imagen WHERE productoid = {$idproducto} ORDER BY posicion ASC");
    $num_imgs = count($recuest_img);
    for ($i = 0;
        $i < $num_imgs;
        $i++) { //iteramos por las imagenes recividad 
      $recuest_img[$i]['img'] = $recuest_img[$i]['img'];
      for ($j = 1;
          $j <= 4;
          $j++) { // creamos las urls para los distintos tamaños de imagenes
        $recuest_img[$i]['url_img_thumb_' . $j] = DIR_IMAGEN . 'thumb_' . $j . '_' . $recuest_img[$i]['img'];
      }
      $recuest_img[$i]['url_img_thumb_og'] = DIR_IMAGEN . 'thumb_og' . pathinfo($recuest_img[$i]['img'], PATHINFO_FILENAME) . '.jpg'; // creamos la url para la imagen de metadatos 
    }
    if ($num_imgs === 0) {
      $recuest_img[] = [
        'img' => 'producto_sin_foto.png',
        'url_img_thumb_1' => DIR_MEDIA . 'images/producto_sin_foto.png',
        'url_img_thumb_2' => DIR_MEDIA . 'images/producto_sin_foto.png',
        'url_img_thumb_3' => DIR_MEDIA . 'images/producto_sin_foto.png',
        'url_img_thumb_4' => DIR_MEDIA . 'images/producto_sin_foto.png',
        'url_img_thumb_og' => DIR_MEDIA . 'images/producto_sin_foto.png',
      ];
    }
    return $recuest_img;
  }

  /* -------------------------------------------------------------------------------- */

  public function getProductosRamdom(int $cantidad, string $orden, int $idProducto = null, string $search) {
    $idUser = $_SESSION['idUser'] ?? 0;
    $search = str_ireplace(',', ' ', $search);
    $search = str_ireplace('  ', ' ', $search);
    $search = str_ireplace(' ', '* ', $search);
    $sql_match = "MATCH(p.nombre, p.descripcion, p.detalle, p.etiquetas) AGAINST ('$search' IN BOOLEAN MODE)";
    $idPro = $idProducto ? " AND  p.idproducto != $idProducto " : '';

    switch ($orden) {
      case 'r':
        $orden = 'RAND()';
        break;
      case 'a':
        $orden = 'p.idproducto ASC';
        break;
      case 'd':
        $orden = 'p.idproducto DESC';
        break;
      default:
        $orden = '';
        break;
    }

    $sql = "SELECT p.idproducto, p.codigo,
                p.nombre, p.etiquetas, p.categoriaid, c.nombre as categoria,
                p.precio, p.oferta, p.oferta_f_ini, p.oferta_f_fin, p.stock, p.stock_status, p.ruta, p.status,
                $sql_match AS relevance, 
                img.img AS imagenes, 
                fav.personaid IS NOT NULL AS favorito
            FROM producto p 
            INNER JOIN producto_categoria c ON p.categoriaid = c.idcategoria 
            LEFT JOIN imagen img ON p.idproducto = img.productoid AND img.posicion = 0 
            LEFT JOIN producto_favorito fav ON p.idproducto = fav.productoid AND fav.personaid = {$idUser} 
            WHERE p.status = 1 AND $sql_match 
            ORDER BY $orden LIMIT $cantidad";

    $request_prod = $this->ordenarProductos($this->select_all($sql));

    return $request_prod;
  }

  /* -------------------------------------------------------------------------------- */

  function getProductoIdInfoCar(int $idProducto) {
    $idUser = $_SESSION['idUser'] ?? 0;
    $request_prod = $this->select("SELECT idproducto, nombre, precio, oferta, oferta_f_ini, oferta_f_fin, ruta, etiquetas FROM producto WHERE idproducto = '{$idProducto}' ");
    if (!empty($request_prod)) {
      $dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();
      $request_prod['precio'] = redondear_decenas($request_prod['precio'] * $dolar); //calculamos el precio del peso segun el dolar
      $request_prod['oferta'] = redondear_decenas($request_prod['oferta'] * $dolar); //calculamos el precio del peso segun el dolar
      $request_prod['oferta_activa'] = ofertaActiva($request_prod);

      $recuest_img = $this->select("SELECT id, img FROM imagen WHERE productoid = {$request_prod['idproducto']} AND posicion = 0");
      $request_prod['images'] = !empty($recuest_img) ?
          DIR_IMAGEN . 'thumb_4_' . $recuest_img['img'] :
          DIR_MEDIA . 'images/producto_sin_foto.png';
      /* favorito */
      $request_prod['favorito'] = isset($_SESSION['login']) && $_SESSION['login'] == 1 ?
          ($this->select("SELECT productoid FROM producto_favorito WHERE productoid = {$request_prod['idproducto']}  AND personaid = {$idUser}") ? 1 : 0) : 0;
    } else {
      $request_prod = "";
    }
    return $request_prod;
  }

  /* -------------------------------------------------------------------------------- */

//  public function getProducBlog(int $cantidad, string $orden) {
//    $cantidad = $cantidad;
//    switch ($orden) {
//      case 'r':$orden = 'RAND() ';
//        break;
//      case 'a':$orden = 'idproducto ASC ';
//        break;
//      case 'd':$orden = 'idproducto DESC ';
//        break;
//      default:$orden = "";
//        break;
//    }
//    
//    $request_prod = $this->select_all("SELECT idproducto, nombre, precio, ruta FROM producto WHERE status = 1 
//                ORDER BY {$orden}  LIMIT {$cantidad}");
//    return count($request_prod) > 0 ? $this->ordenarProductos($request_prod) : 0;
//  }

  /* -------------------------------------------------------------------------------- */

  public function addFav($idprod, $idpers) {
// $request = $this->select("SELECT personaid FROM producto_favorito WHERE productoid = $idprod AND personaid = $idpers");
    return !$this->select("SELECT personaid FROM producto_favorito WHERE productoid = $idprod AND personaid = $idpers") ?
        ($this->insert('INSERT INTO producto_favorito (productoid, personaid) VALUES  (?,?)', array($idprod, $idpers)) == 0 ? 'ok' : '') : 'existe';
  }

  public function delFav($idprod, $idpers) {
// $request = $this->select("SELECT personaid FROM producto_favorito WHERE productoid = $idprod AND personaid = $idpers");
    return $this->select("SELECT personaid FROM producto_favorito WHERE productoid = $idprod AND personaid = $idpers") ?
        ($this->delete("DELETE FROM producto_favorito WHERE productoid = $idprod AND personaid = $idpers ") == 1 ? 'ok' : '') : 'inexistente';
  }
}
