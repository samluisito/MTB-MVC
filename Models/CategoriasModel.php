<?php

declare(strict_types=1);

class CategoriasModel extends Mysql {

  //CATEGORIAS
  private $intIdCat;
  private $strNomCat;
  private $strDescripcion;
  private $intStatus;
  private $strTags;
  private $strRuta;
  private $strPortada;

  function __construct() {
    parent::__construct();
  }

  function insertCategoria(int $intIdCatPadre = null, string $Nombre, string $Descripcion, int $intCatFb, int $intCatGg, string $img, string $strTags, string $ruta, int $Status) {
    $return = "";
    //consultamos la existencia de categorias duplicadas
    $recuest = $this->select_all("SELECT * FROM producto_categoria WHERE nombre = '{$Nombre}'");

    if (empty($recuest)) {
      // si la consulta es nul entonce insertamos la producto_categoria de lo contrarior devilvemos "exist"
      $query_insert = "INSERT INTO producto_categoria (padre_cat_id, nombre, descripcion,cat_facebook_id, cat_google_id, img, tags, ruta, status) VALUES (?,?,?,?,?,?,?,?,?)";
      $arrData = array($intIdCatPadre, $Nombre, $Descripcion, $intCatFb, $intCatGg, $img, $strTags, $ruta, $Status);
      $request_insert = $this->insert($query_insert, $arrData);
      $return = $request_insert;
    } else {
      $return = 'e';
    }
    return $return;
  }

  function updateCategoria(int $idCategoria, int $intIdCatPadre = null, string $Nombre, string $Descripcion, int $intCatFb, int $intCatGg, string $img, string $strTags, string $ruta, int $Status) {
    //validamos que el producto_categoria no este duplcado 
    $sql = "SELECT * FROM producto_categoria WHERE nombre = '$Nombre' AND idcategoria != $idCategoria";
    $request = $this->select_all($sql);

    // si la consulta es nul entonce insertamos el categoria
    if (empty($request)) {
      $query_update = "UPDATE producto_categoria SET padre_cat_id = ?, nombre = ?, descripcion = ?, cat_facebook_id=?, cat_google_id=?, img=?, tags = ?, ruta =?, status = ? WHERE idcategoria = $idCategoria";
      $arrData = array($intIdCatPadre, $Nombre, $Descripcion, $intCatFb, $intCatGg, $img, $strTags, $ruta, $Status);
      $request_update = $this->update($query_update, $arrData);
    } else {
      $request_update = 'e';
    }
    return $request_update;
  }

  function listarImagenesCategorias() {
    $sql = "SELECT img FROM producto_categoria WHERE img != 'portada_categoria.png' AND status != 2";
    $request = $this->select_all($sql);
    return $request;
  }

//EXTRAE TODOS LOS DATOS DE CATEGORIAS
  function selectCategorias($id = 0) {
    $sql = $id === 0 ? 'IS NULL' : "= $id";
    return $this->select_all("SELECT * FROM producto_categoria WHERE status < 2 AND padre_cat_id $sql");
  }

  function selectCat(int $idCat) { //EXTRAE EXTRAE UNA CATEGORIA, PARAMETRO DE ENTRADA EL ID A BUSCAR, DEVUELVE UN ARRAY CON LOS DATOS DLA CATEGORIA
    return $this->select("SELECT * FROM producto_categoria WHERE idcategoria = $idCat ");
  }

  function selectAllSubCat(int $idCat) { //EXTRAE EXTRAE UNA CATEGORIA, PARAMETRO DE ENTRADA EL ID A BUSCAR, DEVUELVE UN ARRAY CON LOS DATOS DEL Categoria
    return $this->select_all("SELECT * FROM producto_categoria WHERE padre_cat_id = $idCat");
  }

  function selectCatPrevProx($posicion, $id) {
    // dep($posicion);
    if ($posicion == 'prev') {
      $posicion = "SELECT max(idcategoria) FROM producto_categoria WHERE padre_cat_id IS NULL AND idcategoria < {$id}";
    } else if ($posicion == 'prox') {
      $posicion = "SELECT min(idcategoria) FROM producto_categoria WHERE padre_cat_id IS NULL AND idcategoria > {$id}";
    }
    //EXTRAE TODOS LOS DATOS DE CATEGORIAS
    $recuest = $this->select("SELECT idcategoria FROM producto_categoria WHERE padre_cat_id IS NULL AND idcategoria = ($posicion)");
    $recuest == '' ? $recuest['idcategoria'] = 0 : $recuest;
    return $recuest;
  }

  function selectCatPosicion($id) {
    $sql = "SELECT idcategoria FROM producto_categoria WHERE padre_cat_id IS NULL";
    $request = $this->select_all($sql);

    $count = count($request);
    $posicion = 1;
    foreach ($request as $value) {
      $value['idcategoria'] < $id ? $posicion = $posicion + 1 : '';
    }
    $response = $posicion . '/' . $count;
    return $response;
  }

  /* ------------------------------------------------------------------------------ */

  function deleteCategoria($intId) {
    return (!empty($this->delete("DELETE FROM producto_categoria WHERE idcategoria = $intId"))) ? 1 : 0;
  }

  function selectImgCategoria($intId) {
    return $this->select_column("SELECT img FROM producto_categoria WHERE idcategoria = $intId");
  }

  function categoriaEnUso($intId) {

    $con1 = $this->select_column("SELECT MAX(idproducto) FROM producto WHERE categoriaid = $intId");
    $con2 = $this->select_column("SELECT MAX(itemid) FROM home_banner WHERE tipo = 'categ' AND itemid = $intId");
    return 0 + $con1 + $con2;
  }

  function selectModulos($idcategoria) {
    $this->intIdCategoria = $idcategoria;
    //EXTRAE modulos
    $sql = "SELECT idmodulo FROM modulo"; //contamos la cantidad modulos
    $recuest_modulo = $this->select_all($sql);

    for ($i = 0; $i < count($recuest_modulo); $i++) { // Repasamos que haya un permiso por cada modulo para el Categoria, (si hay 5 modulo, deben de haber 5 permisos por cada categoria)
      $this->intModuloId = $recuest_modulo[$i]['idmodulo'];
      $sql = "SELECT idmodulo FROM permisos WHERE moduloid = $this->intModuloId AND categoriaid = $this->intIdCategoria ";
      $recuest_permiso = $this->select($sql);

      if (empty($recuest_permiso)) { //si no hay un permiso para el modulo y el categoria, se cre uno, con valores 0
        $query_insert = "INSERT INTO permisos (categoriaid, moduloid, ver, crear, actualizar, eliminar) VALUES (?,?, '0', '0', '0', '0');";
        $arrData = array($this->intIdCategoria, $this->intModuloId);
        $request_insert = $this->insert($query_insert, $arrData);
      }
    }

    //devolvemos una consulta con los valores 
    $sql = "SELECT
 p.idmodulo,
 p.categoriaid,
 p.moduloid,
 m.titulo,
 p.ver,
 p.crear,
 p.actualizar,
 p.eliminar
 FROM
 permisos p
 INNER JOIN modulo m
 ON p.moduloid = m.idmodulo
 WHERE
 p.categoriaid = $this->intIdCategoria "; //contamos la cantidad modulos
    $recuest_permiso_modulo = $this->select_all($sql);

    return $recuest_permiso_modulo;
    die();
  }

  function editPermiso($intIdPerm, $strTpoPerm, $valorTpoPerm) {

    $this->intModuloId = $intIdPerm;
    $this->strTpoPerm = $strTpoPerm;
    $this->intValPerm = $valorTpoPerm;

    $sql = "UPDATE permisos SET {$this->strTpoPerm} = ? WHERE idmodulo = {$this->intModuloId}";
    $arrData = array($this->intValPerm);
    $request = $this->update($sql, $arrData);

    $sql = "SELECT categoriaid FROM permisos WHERE idmodulo = {$this->intModuloId}";
    $request_categoria = $this->select($sql);

    if ($request_categoria['categoriaid'] == $_SESSION['userData']['categoriaid']) {
      sessionUser($_SESSION['idUser']);
    }

    return $request;
  }

  function editCategoriaStatus($id, $intStatus) {
    $request = 'error';
    if (is_numeric($id) && is_numeric($intStatus)) {
      $status = $intStatus == 1 ? 0 : 1;
      $request = $this->update("UPDATE producto_categoria SET status = ? WHERE idcategoria = $id", array($status)) ? 'OK' : 'error';
    }
    return $request;
  }

  /* categorias google */

  function getCatGoogleNivel($padre_id = NULL) {
    $padre_id = $padre_id == NULL ? " is NULL" : " = $padre_id";
    return $this->select_all("SELECT id_cat_gg, nombre, id_cat_gg FROM producto_categoria_google WHERE padre_id_gg $padre_id ORDER BY nombre ASC");
  }

  function getIdCategoriaGooglePorNombre(string $nombre_padre, $nombre_abue) {
    if ($nombre_abue) {
      $sql = "SELECT id_cat_gg FROM producto_categoria_google 
              WHERE padre_id_gg in (SELECT id_cat_gg FROM producto_categoria_google WHERE nombre = '$nombre_abue')
              AND nombre = '$nombre_padre'";
    } else {
      $sql = "SELECT id_cat_gg FROM producto_categoria_google WHERE nombre = '$nombre_padre'";
    }

    $ret = $this->select_column($sql);

    if (!$ret && ($nombre_abue != null)) {
      dep("No insertado ID_abuelo= '$nombre_abue' /  ID_padre = '$nombre_padre'");
    }
    return $ret;
  }

  function getIdCategoriaGooglePorId(int $id) {
    return $this->select_column("SELECT id_cat_gg FROM producto_categoria_google WHERE id_cat_gg = $id");
  }

  function insertar_categoria_google(int $id_google, string $nombre, int $id_padre = null) {
    return $this->insert("INSERT INTO `producto_categoria_google` (id_cat_gg, nombre, padre_id_gg) VALUES (?,?,?)",
            array($id_google, $nombre, $id_padre));
  }

  /* categorias Facebook */

  function getCatFacebookNivel($padre_id = NULL) {
    $padre_id = $padre_id == NULL ? " is NULL" : " = $padre_id";
    return $this->select_all("SELECT id_fb, nombre, padre_id_fb FROM producto_categoria_fb WHERE padre_id_fb $padre_id ORDER BY nombre ASC");
  }

  function getIdCategoriaFacebookPorNombre(string $nombre_padre, $nombre_abue) {   // dep("SELECT id_fb FROM producto_categoria_fb WHERE nombre = '$nombre'");
    $sql = $nombre_abue ? "SELECT id_fb FROM producto_categoria_fb 
              WHERE padre_id_fb in (SELECT id_fb FROM producto_categoria_fb WHERE nombre = '$nombre_abue')
              AND nombre = '$nombre_padre'" :
        "SELECT id_fb FROM producto_categoria_fb WHERE nombre = '$nombre_padre'";
    $request = $this->select_column($sql);
    !$request ? dep("No insertado ID_abuelo= '$nombre_abue' /  ID_padre = '$nombre_padre'") : '';
    return $request;
  }

  function getIdCategoriaFacebookPorId(int $id) {
    return $this->select_column("SELECT id_fb FROM producto_categoria_fb WHERE id_fb = $id");
  }

  function insertar_categoria_facebook(int $id_google, string $nombre, int $id_padre = null) {
    return $this->insert("INSERT INTO `producto_categoria_fb` (id_fb, nombre, padre_id_fb) VALUES (?,?,?)",
            array($id_google, $nombre, $id_padre));
  }

  function getCatFacebookNivelpp($tipo, $padre_id = NULL) {
    $padre_id = $padre_id == NULL ? " is NULL" : " = $padre_id";
    $tipo = $tipo === 'google' ? 'producto_categoria_google WHERE padre_id_gg' : 'producto_categoria_fb WHERE padre_id_fb';

//dep("SELECT * FROM $tipo $padre_id ORDER BY nombre ASC  ");
    return $this->select_all("SELECT * FROM $tipo $padre_id ORDER BY nombre ASC  "); //LIMIT 3 
  }

  function getCatFbGgID(string $tipo, int $id) {
    $sql = $tipo == 'facebook' ?
        "SELECT id_fb, nombre, padre_id_fb FROM producto_categoria_fb WHERE id_fb = $id" :
        "SELECT id_cat_gg, nombre, padre_id_gg FROM producto_categoria_google WHERE id_cat_gg = $id";
    return $this->select($sql);
  }

}