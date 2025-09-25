<?php

declare (strict_types=1);

class ProductosModel extends Mysql {

  //Productos
  private $intIdProducto;
  private $strNomProducto;
  private $strDescripcion;
  private $strDetalle;
  private $strMarca;
  private $strEtiquetas;
  private $intCodigo;
  private $intCategoriaId;
  private $intPrecio;
  private $intStock;
  private $strRuta;
  private $intStatus;
  private $strImagen;

  function __construct() {
    parent::__construct();
  }

  function imgProdructo($id) {
    return $this->select("SELECT img img FROM imagen WHERE productoid = {$id} AND posicion = 0");
  }

  function imgProdructoCSV($id) {
    return $this->select_all("SELECT id, img FROM imagen WHERE productoid = {$id} ORDER BY id ASC LIMIT 4");
  }

  function productoEnUso($id) {
    return $this->select("SELECT MAX(id) AS id FROM pedido_detalle WHERE productoid = $id")['id'] ? 1 :
        ($this->select("SELECT MAX(idbanner) AS id FROM home_banner WHERE tipo='prod' AND itemid= $id")['id'] ? 1 : 0);
  }

  function insertProducto(string $strNombre, string $strDescripcion, string $strDetalle, string $strMarca, string $strEtiquetas,
      int $strCodigo, int $intCategoriaId, int $intCatFbId, int $intCatGgId, int $intProveedorId, float $floCosto,
      float $floPrecio, float $floOferta, string $strOferta_f_ini = null, string $strOferta_f_fin = null, int $intStock, string $stock_status, string $ruta,
      string $grupoEtario, string $genero, string $talla, string $olor, string $material, string $estilo, string $stampado, int $intStatus) {
//    consultamos la existencia de un Producto duplicado
    $recuest = $this->select_all("SELECT idproducto FROM producto WHERE ruta = '{$ruta}'");

    if (empty($recuest)) { // si la consulta es nul  entonce insertamos el Producto
      $return = $this->insert("INSERT INTO producto (nombre, descripcion, detalle, marca, etiquetas, codigo, categoriaid,cat_facebook_id, "
          . "cat_google_id,proveedorId, costo, precio, oferta, oferta_f_ini, oferta_f_fin, stock, stock_status, ruta, "
          . "age_group, gender, size, color, material, style, pattern, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
          array($strNombre, $strDescripcion, $strDetalle, $strMarca, $strEtiquetas, $strCodigo, $intCategoriaId, $intCatFbId, $intCatGgId,
            $intProveedorId, $floCosto, $floPrecio, $floOferta, $strOferta_f_ini, $strOferta_f_fin, $intStock, $stock_status, $ruta,
            $grupoEtario, $genero, $talla, $olor, $material, $estilo, $stampado, $intStatus));
    } else {
      $return = "exist";
    }
    return $return;
  }

  function updateProducto(int $intIdProducto, string $strNombre, string $strDescripcion, string $strDetalle, string $strMarca, string $strEtiquetas,
      int $strCodigo, int $intCategoriaId, int $intCatFbId, int $intCatGgId, int $intProveedorId, float $floCosto,
      float $floPrecio, float $floOferta, string $strOferta_f_ini = null, string $strOferta_f_fin = null, int $intStock, string $stock_status, string $ruta,
      string $grupoEtario, string $genero, string $talla, string $olor, string $material, string $estilo, string $estampado, int $intStatus) {

//    validamos que el Producto no este duplcado   
    $request = $this->select("SELECT idproducto FROM producto WHERE idproducto != {$intIdProducto} AND  ruta = '{$ruta}'");

    return (!empty($request)) ? 'exist' : //     si la consulta es nul  entonce insertamos el producto
        $this->update("UPDATE producto SET nombre=?, descripcion=?, detalle=?, marca=?, etiquetas=?, codigo=?, "
            . "categoriaid=?,cat_facebook_id =?, cat_google_id =?, proveedorId=?, "
            . "costo=?, precio=?, oferta=?, oferta_f_ini=?, oferta_f_fin=?, stock=?,stock_status=?, ruta=?, "
            . "age_group=?, gender=?, size=?, color=?, material=?, style=?, pattern=?, status=? WHERE idproducto = ?",
            array($strNombre, $strDescripcion, $strDetalle, $strMarca, $strEtiquetas, $strCodigo,
              $intCategoriaId, $intCatFbId, $intCatGgId, $intProveedorId,
              $floCosto, $floPrecio, $floOferta, $strOferta_f_ini, $strOferta_f_fin, $intStock, $stock_status, $ruta,
              $grupoEtario, $genero, $talla, $olor, $material, $estilo, $estampado, $intStatus, $intIdProducto));
  }

  /* --------------------------------------------------------------------------- */

  function getOrdenImage(int $idProducto) {
    return $this->select("SELECT posicion FROM imagen WHERE productoid = $idProducto AND posicion = (SELECT MAX(posicion)as posicion FROM imagen WHERE productoid = $idProducto);");
  }

  function insertImage(int $idProducto, string $imgNombre, int $posicion) {
    return $this->insert('INSERT INTO imagen(productoid,img,posicion) VALUES (?,?,?)', array($idProducto, $imgNombre, $posicion));
  }

  public function getImagenInsertadaIdPos($idprod) {
    return $this->select("SELECT id,posicion FROM imagen WHERE id = {$idprod}");
  }

  /* ----------------------------------------------------------------------------- */

  function selectProducto($idProducto) {
    //EXTRAE EXTRAE UN PRODUCTO, PARAMETRO DE ENTRADA EL ID A BUSCAR, DEVUELVE UN ARRAY CON LOS DATOS DEL PRODUCTO
    $this->intIdProducto = $idProducto;
    return $this->select("SELECT p.idproducto, p.codigo, p.nombre, p.descripcion, p.detalle, p.marca, p.etiquetas, 
                              p.categoriaid, c.nombre as categoria, p.cat_facebook_id, p.cat_google_id,
                              p.proveedorid , pr.nombre as proveedor, p.costo, p.precio, p.oferta, p.oferta_f_ini, p.oferta_f_fin, 
                              p.stock,p.stock_status, p.ruta, 
                              p.age_group, p.gender, p.size, p.color, p.material, p.style, p.pattern, p.status
                                              FROM producto p 
                                              INNER JOIN producto_categoria c ON p.categoriaid = c.idcategoria
                                              INNER JOIN producto_proveedor pr ON p.proveedorid = pr.idproveedor
                                              WHERE idproducto = $this->intIdProducto");
  }

  function selectProductos($categoria, $premin, $premax, $estado) {
    $sqlQuery = "SELECT p.idproducto, p.codigo, p.nombre, p.descripcion, c.nombre as categoria, p.precio, p.stock, p.stock_status, p.ruta,
                p.status FROM producto p INNER JOIN producto_categoria c on p.categoriaid = c.idcategoria WHERE  ";
    if ($estado != null) {
      switch (strtolower($estado)) {
        case 'a': $sqlQuery .= " 
          p.status = 1 ";
          break;
        case 'i': $sqlQuery .= " 
          p.status = 0 ";
          break;
        default : $sqlQuery .= " 
          p.status < 2 ";
          break;
      }
    }
    if ($categoria != null) {
      $id = $this->select_column("SELECT idcategoria AS id FROM producto_categoria WHERE idcategoria = {$categoria} "); //UPPER(nombre) = UPPER('{$categoria}')");
      $sqlQuery .= $id ? "AND c.idcategoria = {$id} OR c.padre_cat_id = {$id} " : '';
    }
    if ($premax != null) {
      $dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();
      $premin = round($premin / $dolar, 2);
      $premax = round($premax / $dolar, 2);
      $sqlQuery .= "AND precio BETWEEN '{$premin}' AND '{$premax} '";
    }
    $sqlQuery .= "ORDER By p.idproducto DESC";
    //EXTRAE PRODUCTOS    p.descripcion, p.categoriaid,

    return $this->select_all($sqlQuery);
  }

  function selectProductosPorCategoria($categoria, $estado) {
    $sqlQuery = "SELECT p.idproducto, p.codigo, p.nombre, p.descripcion,p.marca, p.categoriaid, c.nombre as categoria, p.cat_facebook_id, p.cat_google_id,
                p.precio, p.oferta, p.oferta_f_ini, p.oferta_f_fin, p.stock, p.stock_status, p.ruta,
                p.age_group, p.gender, p.size, p.color, p.material, p.style, p.pattern,p.status 
                FROM producto p INNER JOIN producto_categoria c on p.categoriaid = c.idcategoria WHERE  ";
    if ($estado != null) {
      switch (strtolower($estado)) {
        case 'a': $sqlQuery .= " 
          p.status = 1 ";
          break;
        case 'i': $sqlQuery .= " 
          p.status = 0 ";
          break;
        default : $sqlQuery .= " 
          p.status < 2 ";
          break;
      }
    }
    if ($categoria != null) {
      $id = $this->select("SELECT idcategoria AS id FROM producto_categoria WHERE idcategoria = {$categoria} "); //UPPER(nombre) = UPPER('{$categoria}')");
      $sqlQuery .= $id ? "AND c.idcategoria = {$id['id']} " : '';
    }
    $sqlQuery .= " 
      ORDER By p.idproducto DESC";
    return $this->select_all($sqlQuery);
  }

  /* ---------------------------------------------------------------------------------------------- */

  function selectProdPrevProx($posicion, $idProd, $categoria, $premin, $premax, $estado) {
    $sqlQuery = $posicion == 'prev' ?
        "SELECT MAX(idproducto) as idproducto FROM producto WHERE " :
        "SELECT MIN(idproducto) as idproducto FROM producto WHERE ";

    if ($estado != null) {
      switch (strtolower($estado)) {
        case 'a': $sqlQuery .= "status = 1 ";
          break;
        case 'i': $sqlQuery .= "status = 0 ";
          break;
        default : $sqlQuery .= "status < 2 ";
          break;
      }
    }
    $sqlQuery .= $categoria != null ? "AND categoriaid = {$categoria} " : '';
    if ($premax != null) {
      $dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();
      $premin = round($premin / $dolar, 2);
      $premax = round($premax / $dolar, 2);
      $sqlQuery .= "AND precio BETWEEN '{$premin}' AND '{$premax} '";
    }
    $sqlQuery .= $posicion == 'prev' ? "AND idproducto < {$idProd} " : "AND idproducto > {$idProd} ";
    $sqlQuery .= " ORDER By idproducto DESC";
    $recuest = $this->select($sqlQuery)['idproducto'];
    return $recuest ? $recuest : 0;
  }

  function selectProdPosicion($id, $categoria, $premin, $premax, $estado) {
    $sqlQuery = "WHERE ";
    if ($estado != null) {
      switch (strtolower($estado)) {
        case 'a': $sqlQuery .= "status = 1 ";
          break;
        case 'i': $sqlQuery .= "status = 0 ";
          break;
        default : $sqlQuery .= "status < 2 ";
          break;
      }
    }
    $sqlQuery .= $categoria != null ? "AND categoriaid = {$categoria} " : '';
    if ($premax != null) {
      $dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();
      $premin = round($premin / $dolar, 2);
      $premax = round($premax / $dolar, 2);
      $sqlQuery .= "AND precio BETWEEN '{$premin}' AND '{$premax} '";
    }
    $sqlQuery .= " ORDER By idproducto DESC";
    $request = $this->select_all("select idproducto from producto {$sqlQuery}");
    $posicion = 0;
    foreach ($request as $value) {
      $value['idproducto'] < $id ? $posicion = $posicion + 1 : '';
    }
    // ASC return $posicion +1 . '/' . count($request);
    return count($request) - $posicion . '/' . count($request);
  }

  /* Selecciona una imagen------------------------------------------------------------------------------------------ */

  function selectImages($intId) {
    return $this->select_all("SELECT id,posicion, img FROM imagen  WHERE productoid = $intId ORDER BY posicion ASC");
  }

  /* borrar una imagen------------------------------------------------------------------------------------------ */

  function deleteImage(int $idProducto, string $imgNombre) {
    return $this->delete("DELETE FROM imagen WHERE productoid = $idProducto AND img = '{$imgNombre}'");
  }

  public function getImagenProdPosicion($idprod) {
    return $this->select_all("SELECT id,posicion FROM imagen WHERE productoid = {$idprod} ORDER BY posicion ASC ");
  }

  public function updateOrdenImgId(int $posicion, int $idimg) {
    return $this->update("UPDATE imagen SET posicion = ? WHERE id =  ?", array($posicion, $idimg));
  }

  /* Selecciona una imagen------------------------------------------------------------------------------------------ */

  function listarImagenesProd($id = null) {
    $sql = $id != null ? "SELECT id, img FROM imagen WHERE productoid = $id" :
        'SELECT productoid FROM imagen WHERE productoid >0 and productoid is not null GROUP BY productoid';

    //dep("SELECT id, img FROM imagen WHERE  $sql");
    return $this->select_all($sql);
  }

  function actualizaImagenesProd($id, $img) {
    $this->intIdProducto = $id;
    $this->strImagen = $img;
    return $this->update("UPDATE imagen SET img = ? WHERE id = $this->intIdProducto", array($this->strImagen));
  }

  function deleteProducto($idProducto) {
    return (empty($this->delete("DELETE FROM producto WHERE idproducto =  $idProducto"))) ? 0 : 1;
  }

  function getCategoriasCountProducto($id = 0) {
    $sql = $id === 0 ? 'IS NULL' : "= $id";
    $categorias = $this->select_all("SELECT idcategoria, nombre FROM producto_categoria WHERE status=1 AND padre_cat_id $sql");
    foreach ($categorias as $key => $categoria) {
      $categorias[$key]['count'] = $this->select_column("SELECT COUNT(idproducto)as count FROM producto WHERE categoriaid={$categoria['idcategoria']}");
    }

    array_sort_by($categorias, 'count', $order = SORT_DESC); //SORT_ASC SORT_DESC
    return $categorias;
  }

  function editStatus($id, $intStatus) {
    $request = 'error';
    if (is_numeric($id) && is_numeric($intStatus)) {
      $status = $intStatus == 1 ? 0 : 1;
      $r = $this->update("UPDATE producto SET status = ? WHERE idproducto = $id", array($status));
      $request = $r ? 'OK' : 'error';
    }
    return $request;
  }

  /* PROVEEDOR ------------------------------------------------------------------------------------------------------------------------ */

  function insertProveedor(string $strNombre, string $strDescripcion, string $strDireccion, string $img, string $strWeb, string $strFacebook, string $strInstagram, int $intTelefono, int $intMobil = null, int $listStatus) {
    $return = "";

    //consultamos la existencia del proveedor
    $recuest = $this->select_column("SELECT idproveedor FROM producto_proveedor WHERE nombre = '{$strNombre}' ");

    if (empty($recuest)) {// si la consulta es nul  entonce insertamos la producto_categoria de lo contrarior devilvemos "exist"
      $query_insert = "INSERT INTO producto_proveedor (nombre, descripcion, direccion, img, web, fb, ig, telf_local, telf_mobil, status) VALUES (?,?,?,?,?,?,?,?,?,?)";
      $arrData = array($strNombre, $strDescripcion, $strDireccion, $img, $strWeb, $strFacebook, $strInstagram, $intTelefono, $intMobil, $listStatus);
      $return = $this->insert($query_insert, $arrData);
    } else {
      $return = "exist";
    }
    return $return;
  }

  function updateProveedor(int $idProveedor, string $strNombre, string $strDescripcion, string $strDireccion, string $img, string $strWeb, string $strFacebook, string $strInstagram, int $intTelefono = null, int $intMobil = null, int $listStatus) {
    //validamos que el producto_categoria no este duplcado 
    $request = $this->select_column("SELECT idproveedor FROM producto_proveedor WHERE nombre = '$strNombre' AND idproveedor != $idProveedor");
    // si la consulta es nul  entonce insertamos el categoria
    if (empty($request)) {
      $request_update = $this->update(
          "UPDATE producto_proveedor SET nombre = ?, descripcion = ?, direccion = ?, img = ?, web = ?, fb = ?, ig = ?, telf_local = ?, telf_mobil = ?, status = ? WHERE idproveedor = $idProveedor",
          array($strNombre, $strDescripcion, $strDireccion, $img, $strWeb, $strFacebook, $strInstagram, $intTelefono, $intMobil, $listStatus)
      );
    } else {
      $request_update = 'exist';
    }
    return $request_update;
  }

  function selectProveedores($estado = null) {
    if ($estado != null) {
      $query_estado = '';
      switch (strtolower($estado)) {
        case 'a': $query_estado .= " 
          status = 1 ";
          break;
        case 'i': $query_estado .= " 
          status = 0 ";
          break;
        default : $query_estado .= " 
          status < 2 ";
          break;
      }
    }
    //CONSULTA DATOS DE PROVEEDOR
    return $this->select_all("SELECT idproveedor, img, nombre, descripcion, status FROM `producto_proveedor` WHERE $query_estado");
  }

  function proveedorEnUso($idProducto) {
    $this->intIdProducto = $idProducto;
    return $this->select_column("SELECT MAX(idproducto) FROM producto WHERE proveedorid = $this->intIdProducto") ? 1 : 0;
  }

  function selectProveedor($id) {
    //EXTRAE EXTRAE UN PRODUCTO, PARAMETRO DE ENTRADA EL ID A BUSCAR, DEVUELVE UN ARRAY CON LOS DATOS DEL PRODUCTO
    return $this->select("SELECT * FROM `producto_proveedor` WHERE idproveedor = $id");
  }

  function selectProvPrevProx($direccion, $idProd, $estado) {
    $sqlQuery = $direccion == 'prev' ?
        "SELECT MAX(idproveedor) as idproveedor FROM producto_proveedor WHERE " :
        "SELECT MIN(idproveedor) as idproveedor FROM producto_proveedor WHERE ";

    if ($estado != null) {
      switch (strtolower($estado)) {
        case 'a': $sqlQuery .= "status = 1 ";
          break;
        case 'i': $sqlQuery .= "status = 0 ";
          break;
        default : $sqlQuery .= "status < 2 ";
          break;
      }
    }

    $sqlQuery .= $direccion == 'prev' ? "AND idproveedor < {$idProd} " : "AND idproveedor > {$idProd} ";
    $sqlQuery .= " ORDER By idproveedor DESC";
    $recuest = $this->select_column($sqlQuery);
    return $recuest ? $recuest : 0;
  }

  function selectProvPosicion($id, $estado) {
    $sqlQuery = "WHERE ";
    if ($estado != null) {
      switch (strtolower($estado)) {
        case 'a': $sqlQuery .= "status = 1 ";
          break;
        case 'i': $sqlQuery .= "status = 0 ";
          break;
        default : $sqlQuery .= "status < 2 ";
          break;
      }
    }

    $sqlQuery .= " ORDER By idproveedor DESC";
    $request = $this->select_all("SELECT idproveedor FROM producto_proveedor {$sqlQuery}");
    $posicion = 1;
    foreach ($request as $value) {
      $value['idproveedor'] < $id ? $posicion = $posicion + 1 : '';
    }
    return $posicion . '/' . count($request);
  }

  function editProveedorStatus($id, $intStatus) {
    $request = 'error';
    if (is_numeric($id) && is_numeric($intStatus)) {
      $status = $intStatus == 1 ? 0 : 1;
      $request = $this->update("UPDATE producto_proveedor SET status = ? WHERE idproveedor = $id", array($status)) ? 'OK' : 'error';
    }
    return $request;
  }

  function deleteProveedor($idproveedor) {
    return (!empty($this->delete("DELETE FROM producto_proveedor WHERE idproveedor =  $idproveedor"))) ? 1 : 0;
  }

  function selectImgProv($intId) {
    return $this->select_column("SELECT img FROM producto_proveedor  WHERE idproveedor = $intId");
  }

  public function selectProveedoresChoise($tipo = null) {

    //EXTRAE TODOS LOS DATOS DE CATEGORIAS

    $recuest = $this->select_all("SELECT idproveedor, nombre, status FROM producto_proveedor WHERE status < 2 ");
    return $recuest;
  }

  /* ---------------------------------------------------------------------- */

  public function getProductosid() {
    return $this->select_all_column("SELECT idproducto FROM producto ");
  }

  public function getImagenProdId($idprod) {
    return $this->select_all_column("SELECT id FROM imagen WHERE productoid = {$idprod} ORDER BY id ASC ");
  }

  public function getImagenOrdenProdId($idprod) {
    return $this->select_all("SELECT id, posicion FROM imagen WHERE productoid = {$idprod} ORDER BY id ASC ");
  }

  public function setOrdenImgId($posicion, $idimg) {
    return $this->update("UPDATE `imagen` SET `posicion` = ? WHERE `id` =  ?", array($posicion, $idimg));
  }
}

/*

ALTER TABLE producto 
ADD item_group_id INT(10) AFTER ruta,
ADD gender VARCHAR(20) AFTER item_group_id,
ADD color VARCHAR(200) AFTER gender,
ADD size VARCHAR(100) AFTER color,
ADD age_group VARCHAR(20) AFTER size,
ADD material VARCHAR(200) AFTER age_group,
ADD pattern VARCHAR(100) AFTER material,
ADD style VARCHAR(100) AFTER pattern;
  
UPDATE `producto` SET 
`gender` = 'F', 
`color` = 'blanco,negro', 
`size` = 'S,M,L', 
`age_group` = 'T', 
`material` = '', 
`pattern` = '', 
`style` = 'urbano' 
WHERE `idproducto` > 0
UPDATE `producto` SET `style` = 'urbano' WHERE `idproducto` > 0;
UPDATE `producto` SET `gender` = 'M' WHERE `categoriaid` IN (15,16);


 * 
*/