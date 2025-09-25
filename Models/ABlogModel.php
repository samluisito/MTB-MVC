<?php
declare(strict_types=1);

class ABlogModel extends Mysql {

   //Entradas
   public $intIdEntrada;
   public $intAutorid;
   public $strTitulo;
   public $strUrl;
   public $strDescripcion;
   public $strTxt_entrada;
   public $strTags;
   public $intCategoriaId;
   public $DateUpdate;
   public $intStatus;
   public $strImagen;

   public function __construct() {
      parent::__construct();
   }

   public function selectEntradas() {
      //EXTRAE PRODUCTOS    p.descripcion,
      $sql = "SELECT be.identrada,
                CONCAT(pe.nombres, ' ', pe.apellidos ) as autor,
                be.titulo,
                be.descripcion,
                c.nombre as categoria,
                be.status
                FROM blog_entrada be
                INNER JOIN producto_categoria c 
                INNER JOIN persona pe 
                on be.categoriaid = c.idcategoria
                and be.autorid = pe.idpersona
                WHERE be.status < 2";
      $recuest = $this->select_all($sql);

      return $recuest;
   }

   public function imgEntrada($identrada) {
      $intIdEntrada = $identrada;

      $sql_img = $sql = "SELECT min(id), entradaid, img FROM imagen WHERE entradaid = $intIdEntrada";

      $recuest_img = $this->select($sql_img);

      return $recuest_img;
   }

   public function entradaEnUso($idEntrada) {
      $this->intIdEntrada = $idEntrada;
      $sql = "SELECT * FROM pedido_detalle WHERE entradaid = $this->intIdEntrada";
      $request = $this->select($sql);
      return $request;
   }

   public function insertEntrada(int $autorid, string $Titulo, string $url, string $Descripcion, string $txt_texto, string $Tags, int $CategoriaId, int $Status) {
      $return = "";

      $this->intAutorid = $autorid;
      $this->strTitulo = $Titulo;
      $this->strUrl = $url;
      $this->strDescripcion = $Descripcion;
      $this->strTxt_entrada = $txt_texto;
      $this->strTags = $Tags;
      $this->intCategoriaId = $CategoriaId;
      $this->intStatus = $Status;

      //consultamos la existencia de un Entrada duplicado
      $sql = "SELECT identrada FROM blog_entrada WHERE url = '{$this->strUrl}'";
      $recuest = $this->select_all($sql);

      if (empty($recuest)) { // si la consulta es nul  entonce insertamos el Entrada
         $query_insert = "INSERT INTO blog_entrada (autorid, titulo, url, descripcion, txt_entrada, tags, categoriaid, status) VALUES (?,?,?,?,?,?,?,?)";

         $arrData = array($this->intAutorid, $this->strTitulo, $this->strUrl, $this->strDescripcion,
           $this->strTxt_entrada, $this->strTags, $this->intCategoriaId, $this->intStatus);

         $request_insert = $this->insert($query_insert, $arrData);

         $return = $request_insert;
      } else {
         $return = "exist";
      }
      return $return;
   }

   public function updateEntrada(int $idEntrada, string $Titulo, string $url, string $Descripcion, string $txt_texto, string $Tags, int $CategoriaId, int $Status) {
      $return = "";

      $this->intIdEntrada = $idEntrada;
      $this->strTitulo = $Titulo;
      $this->strUrl = $url;
      $this->strDescripcion = $Descripcion;
      $this->strTxt_entrada = $txt_texto;
      $this->strTags = $Tags;
      $this->intCategoriaId = $CategoriaId;
      $this->intStatus = $Status;

      //validamos que el Entrada no este duplcado
      $sql = "SELECT identrada FROM blog_entrada WHERE identrada != {$this->intIdEntrada} AND url = '{$this->strUrl} '";
      $recuest = $this->select_all($sql);
      // si la consulta es nul  entonce insertamos el entrada
      if (empty($request)) {
         $sql = "UPDATE blog_entrada SET                 
                                titulo = ?,
                                url = ?,
                                descripcion = ?, 
                                txt_entrada = ?, 
                                tags = ?,
                                categoriaid = ?,
                                dateupdate = NOW(),
                                status = ?
                            WHERE identrada =  '$this->intIdEntrada'";

         $arrData = array(
           $this->strTitulo, $this->strUrl, $this->strDescripcion,
           $this->strTxt_entrada, $this->strTags, $this->intCategoriaId, $this->intStatus);

         $request_update = $this->update($sql, $arrData);
      } else {
         $request_update = 'exist';
      }
      return $request_update;
   }

   function insertImage(int $idEntrada, string $imgTitulo) {

      $this->intIdEntrada = $idEntrada;
      $this->strImagen = $imgTitulo;

      $sql = 'INSERT INTO imagen(entradaid,img) VALUES (?,?)';
      $arrData = array($this->intIdEntrada, $this->strImagen);
      $request_insert = $this->insert($sql, $arrData);
      return $request_insert;
   }
   /* -------------------------------------------------------------------------- */

   public function selectEntrada($idEntrada) {
      //EXTRAE EXTRAE UN PRODUCTO, PARAMETRO DE ENTRADA EL ID A BUSCAR, DEVUELVE UN ARRAY CON LOS DATOS DEL PRODUCTO
      $this->intIdEntrada = $idEntrada;
      $sql = $sql = "
                SELECT be.identrada,
                CONCAT(pe.nombres, ' ', pe.apellidos ) as autor,
                be.titulo,
                be.descripcion,
                be.txt_entrada,
                be.tags,
                be.categoriaid,
                be.datecreated,
                be.dateupdate,
                be.status
                FROM blog_entrada be
                INNER JOIN persona pe 
                ON be.autorid = pe.idpersona
                WHERE identrada = $this->intIdEntrada ";
      $recuest = $this->select($sql);
      return $recuest;
   }
   public function selectEntradaPrevProx($posicion, $id) {
       if ($posicion == 'prev') {
         $posicion = "select max(identrada) from blog_entrada where identrada < {$id}";
      } else if ($posicion == 'prox') {
         $posicion = "select min(identrada) from blog_entrada where identrada > {$id}";
      }
      //EXTRAE TODOS LOS DATOS DE blog_entrada
      $sql = "select identrada from blog_entrada where identrada = ($posicion)";
      $recuest = $this->select($sql);
      $recuest == '' ? $recuest['identrada'] = 0 : $recuest;
      return $recuest;
   }

   public function selectEntradaPosicion($id) {
      $sql = "select identrada from blog_entrada";
      $request = $this->select_all($sql);

      $count = count($request);
      $posicion = 1;
      foreach ($request as $value) {
         $value['identrada'] < $id ? $posicion = $posicion + 1 : '';
      }
      $response = $posicion . '/' . $count;
      return $response;
   }

   /* -------------------------------------------------------------------------- */

   public function selectImages($intId) {
      $this->intIdEntrada = $intId;
      $sql = $sql = "SELECT entradaid, img FROM imagen
                    WHERE entradaid = $this->intIdEntrada";
      $recuest = $this->select_all($sql);
      return $recuest;
   }

   public function deleteImage(int $idEntrada, string $imgTitulo) {
      $this->intIdEntrada = $idEntrada;
      $this->strImagen = $imgTitulo;

      $sql = "DELETE FROM imagen WHERE entradaid = $this->intIdEntrada AND img = '{$this->strImagen}'";

      $request = $this->delete($sql);

      return $request;
   }

   public function deleteEntrada($idEntrada) {
      $this->intIdEntrada = $idEntrada;
      $sql = "SELECT * FROM pedido_detalle WHERE entradaid = $this->intIdEntrada";
      $request = $this->select_all($sql);

      if (empty($request)) {
         $sql = "UPDATE entrada SET status = ? WHERE identrada = $this->intIdEntrada";
         $arrData = array(2);
         $request = $this->update($sql, $arrData);
         if ($request) {
            $request = 'OK';
         } else {
            $request = 'error';
         }
      } else {
         $request = 'exist';
      }
      return $request;
   }

}

//  dep($sql);