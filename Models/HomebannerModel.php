<?php

declare(strict_types=1);

class HomebannerModel extends Mysql {

  //CATEGORIAS
  public $intId;
  public $strNombre;
  public $strDescripcion;
  public $intStatus;
  public $strListTpo;
  public $intListItem;
  public $strTags;
  public $strRuta;
  public $strImg;

  public function __construct() {
    parent::__construct();
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function selectItem($tipo = null) {
    $array = array();
    if ($tipo == 'prod') {
      $sql = "SELECT idproducto, nombre FROM `producto` WHERE status = 1";
      $recuest = $this->select_all($sql);
      //  dep($recuest);
      for ($index = 0; $index < count($recuest); $index++) {
        array_push($array, array("id" => $recuest[$index]['idproducto'], "nombre" => $recuest[$index]['nombre']));
      }
    } else if ($tipo == 'categ') {
      $categorias = $this->select_all("SELECT idcategoria, nombre FROM producto_categoria WHERE padre_cat_id IS NULL AND status = 1 ");
      $countCategorias = count($categorias);
      for ($index = 0; $index < $countCategorias; $index++) {

        array_push($array, array("id" => $categorias[$index]['idcategoria'], "nombre" => strtoupper($categorias[$index]['nombre'])));
        $subCategorias = $this->select_all("SELECT idcategoria, nombre FROM producto_categoria WHERE padre_cat_id ={$categorias[$index]['idcategoria']} AND status = 1 ");
        $countSubCategorias = count($subCategorias);
        for ($i = 0; $i < $countSubCategorias; $i++) {
          array_push($array, array("id" => $subCategorias[$i]['idcategoria'], "nombre" => ucwords(strtolower($subCategorias[$i]['nombre']))));
        }
      }
    } else if ($tipo == 'blog') {
      $sql = "SELECT identrada, titulo FROM `blog_entrada` WHERE status = 1";
      $recuest = $this->select_all($sql);
      for ($index = 0; $index < count($recuest); $index++) {
        array_push($array, array("id" => $recuest[$index]['identrada'], "nombre" => $recuest[$index]['titulo']));
      }
    }

    //EXTRAE TODOS LOS DATOS DE CATEGORIAS
    //  dep($array);
    return $array;
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function selectUrlItem(string $tipo = null, int $id, $prefijo) {
    $ruta = false;
    if ($tipo == 'prod') {
      $sql = "SELECT ruta FROM `producto` WHERE idproducto = {$id}";
      $recuest = $this->select($sql);
      $prefijo = $prefijo == 'ruta' ? 'tienda/producto/' : '';
      $ruta = $prefijo . $recuest['ruta'];
    } else if ($tipo == 'categ') {
      $sql = "SELECT ruta FROM producto_categoria WHERE idcategoria = {$id}";
      $recuest = $this->select($sql);
      $prefijo = $prefijo == 'ruta' ? 'tienda/categoria/' : '';
      $ruta = $prefijo . $recuest['ruta'];
    } else if ($tipo == 'blog') {
      $sql = "SELECT url FROM blog_entrada WHERE identrada = {$id} ";
      $recuest = $this->select($sql);
      $prefijo = $prefijo == 'ruta' ? 'blog/entrada/' : '';
      $ruta = $prefijo . $recuest['url'];
    }
    return $ruta;
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function getRutaItem($tipo, $id) {
    $recuest1 = $this->select("SELECT `idbanner` FROM `home_banner` WHERE tipo = '{$tipo}' AND `itemid` = {$id}");

    if ($recuest1 > 0) {
      //dep($recuest1);
      if ($tipo == 'prod') {
        $sql = "SELECT rura FROM `producto` WHERE idproducto = {$id}";
        $recuest = $this->select($sql);
      } else if ($tipo == 'categ') {
        $sql = "SELECT ruta FROM producto_categoria WHERE idbanner = {$id}";
        $recuest = $this->select_all($sql);
      } else if ($tipo == 'blog') {
        $sql = "SELECT url FROM blog_entrada WHERE identrada = {$id}";
        $recuest = $this->select_all($sql);
      }
    } else {
      $recuest = 'existe';
    }
    return $recuest;
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

//   public function validarBannerExiste($url) {
//      
//   }
  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function insertBanner(string $Nombre, string $Descripcion, string $img_banner, string $strListTpo, int $intListItem, string $ruta, int $Status) {
    $return = "";
    $this->strNombre = $Nombre;
    $this->strDescripcion = $Descripcion;
    $this->strImg = $img_banner;
    $this->strListTpo = $strListTpo;
    $this->intListItem = $intListItem;
    // $this->strTags = $strTags;
    $this->strRuta = $ruta;
    $this->intStatus = $Status;

    //consultamos la existencia de categorias duplicadas
    $sql = "SELECT idbanner FROM home_banner WHERE tipo = '{$this->strListTpo}' and itemid = '{$this->intListItem}'";
    $recuest = $this->select($sql);

    if (empty($recuest)) {
      // si la consulta es nul  entonce insertamos la producto_categoria de lo contrarior devilvemos "exist"
      $query_insert = "INSERT INTO home_banner (nombre, descripcion, img, tipo, itemid,ruta, status) VALUES (?,?,?,?,?,?,?)";
      $arrData = array($this->strNombre, $this->strDescripcion, $this->strImg, $this->strListTpo, $this->intListItem, $this->strRuta, $this->intStatus);
      $request_insert = $this->insert($query_insert, $arrData);
      $return = $request_insert;
    } else {
      $return = "exist";
    }
    return $return;
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function updateBanner(int $idBanner, string $Nombre, string $Descripcion, string $img_banner, string $strListTpo, int $intListItem, string $ruta, int $Status) {
    $request_update = "";

    $this->intId = $idBanner;
    $this->strNombre = $Nombre;
    $this->strDescripcion = $Descripcion;
    $this->strImg = $img_banner;
    $this->strListTpo = $strListTpo;
    $this->intListItem = $intListItem;
    $this->strRuta = $ruta;
    $this->intStatus = $Status;

    //validamos que el producto_categoria no este duplcado 
    $sql = "SELECT idbanner FROM `home_banner` WHERE tipo =  '{$this->strListTpo}' AND itemid = {$this->intListItem} AND idbanner != {$this->intId}";
    $request = $this->select($sql);
    // si la consulta es trae in id no actualizamos y e informamos que el item ya existe
    if ($request > 0) {
      $request_update = 'exist';
    } else {
      $query_update = "UPDATE home_banner SET nombre = ?, descripcion = ?, img=?, tipo = ?,itemid = ?, ruta =?, status = ? WHERE idbanner =  '$this->intId'";
      $arrData = array(
        $this->strNombre,
        $this->strDescripcion,
        $this->strImg,
        $this->strListTpo,
        $this->intListItem,
        $this->strRuta,
        $this->intStatus);
      $request_update = $this->update($query_update, $arrData);
    }
    return $request_update;
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function listarImagenesBanners() {
    $sql = "SELECT * FROM home_banner WHERE img != 'portada_categoria.png'";
    $request = $this->select_all($sql);
    return $request;
  }

  public function actualizaImagenBanner($id, $img) {
    $this->intId = $id;
    $this->strImg = $img;
    $sql = "UPDATE home_banner SET img = ? WHERE idbanner = $this->intId";
    $arrData = array($this->strImg);
    $request = $this->update($sql, $arrData);
    return $request;
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function selectBanner(int $id) {
    //EXTRAE EXTRAE UN BANNER, PARAMETRO DE ENTRADA EL ID A BUSCAR, DEVUELVE UN ARRAY CON LOS DATOS DEL ROL
    $this->intId = $id;
    $sql = "SELECT * FROM home_banner WHERE idbanner = $this->intId ";

    $recuest = $this->select($sql);
    return $recuest;
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function selectBanners() {
    //EXTRAE EXTRAE UNA una Lista de banners

    $sql = "SELECT idbanner, nombre, img, tipo, status FROM home_banner WHERE status < 2";
    $recuest = $this->select_all($sql);
    return $recuest;
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  function editBannerStatus($id, $intStatus) {
    $request = 'error';
    if (is_numeric($id) && is_numeric($intStatus)) {
      $status = $intStatus == 1 ? 0 : 1;
      $request = $this->update("UPDATE home_banner SET status = ? WHERE idbanner = $id", array($status)) ? 'OK' : 'error';
    }
    return $request;
  }

  function deleteBanner($intId) {
    return (empty($this->delete("DELETE FROM home_banner WHERE idbanner =  $intId"))) ? 0 : 1;
  }

  function selectImgBanner($intId) {
    return $this->select_column("SELECT img FROM home_banner  WHERE idbanner = $intId");
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function bannerEnUso($id) {
    //$request = $this->select_column("SELECT idproducto FROM producto WHERE categoriaid = $id");
    return 0; //$request;
  }

  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */



  /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

  public function selectModulos($idbanner) {
    $this->intId = $idbanner;
    //EXTRAE modulos
    $sql = "SELECT idmodulo FROM modulo";  //contamos la cantidad modulos
    $recuest_modulo = $this->select_all($sql);

    for ($i = 0; $i < count($recuest_modulo); $i++) {  // Repasamos que haya un permiso por cada modulo para el Banner, (si hay 5 modulo, deben de haber 5 permisos por cada categoria)
      $this->intModuloId = $recuest_modulo[$i]['idmodulo'];
      $sql = "SELECT idmodulo FROM permisos WHERE moduloid = $this->intModuloId AND categoriaid = $this->intId ";
      $recuest_permiso = $this->select($sql);

      if (empty($recuest_permiso)) { //si no hay un permiso para el modulo y el categoria, se cre uno, con valores 0
        $query_insert = "INSERT INTO permisos (categoriaid, moduloid, ver, crear, actualizar, eliminar) VALUES (?,?, '0', '0', '0', '0');";
        $arrData = array($this->intId, $this->intModuloId);
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
                p.categoriaid = $this->intId ";  //contamos la cantidad modulos
    $recuest_permiso_modulo = $this->select_all($sql);

    return $recuest_permiso_modulo;
    die();
  }

}

//  dep($sql);