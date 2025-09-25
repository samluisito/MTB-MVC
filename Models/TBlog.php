<?php

declare(strict_types=1);

//require_once './Librerias/Core/Mysql.php';

trait TBlog {

  private $identrada;
  private $autorid;
  private $titulo;
  private $url;
  private $descripcion;
  private $txt_entrada;
  private $categoriaid;
  private $datecreated;
  private $dateupdate;
  private $tags;
  private $status;
  private $con;

  function getEntradas() {

    $this->con = new Mysql();

    $sql = "SELECT be.identrada, 
                CONCAT(p.nombres, ' ', p.apellidos) as 'autor', 
                be.titulo, 
                be.url, 
                be.descripcion, 
                be.tags, 
                be.datecreated 
                FROM blog_entrada be INNER JOIN persona p 
                ON be.autorid = p.idpersona 
                WHERE be.status = 1 order by be.dateupdate DESC";

    $request = $this->con->select_all($sql);

    if (count($request) > 0) { // si la consulta es mayo a 0, actualiza la url y la imagen de portada 
      
      for ($index = 0; $index < count($request); $index++) {
        $request[$index]['url'] = base_url() . 'blog/entrada/' . $request[$index]['url'];

        $request[$index]['img'] = DIR_MEDIA . 'images/entrada_sin_foto.png';

        $intIdEntrada = $request[$index]['identrada'];
        $sql_img = "SELECT min(id) as id, img FROM imagen WHERE entradaid = $intIdEntrada";

        $recuest_img = $this->con->select($sql_img);
        if ($recuest_img['img'] != '') {
          $request[$index]['img'] = DIR_IMAGEN . $recuest_img['img'];
        }
      }
    }
    return $request;
  }

  function getEntrada($url) {
    $this->url = $url;
    $this->con = new Mysql();

    $sql = "SELECT be.identrada, 
                CONCAT(p.nombres, ' ', p.apellidos) as 'autor', 
                be.titulo, 
                be.url, 
                be.descripcion, 
                be.txt_entrada,
                c.nombre,
                c.ruta as url_cat,
                be.tags, 
                be.datecreated,
                be.dateupdate,
                be.status
                FROM blog_entrada be INNER JOIN persona p INNER JOIN producto_categoria c 
                ON be.autorid = p.idpersona AND be.categoriaid = c.idcategoria
                WHERE be.status < 2 AND be.url = '{$this->url}'";

    $request = $this->con->select($sql);

    if (count($request) > 0) { // si la consulta es mayo a 0, actualiza la url y la imagen de portada 
      if ($request['status'] == 1) {

        $request['url'] = base_url() . 'blog/entrada/' . $request['url'];
        $request['url_cat'] = base_url() . 'blog/categoria/' . $request['url_cat'];
        $request['img'] = DIR_MEDIA . 'entrada_sin_foto.png';
        $request['img_url'] = DIR_MEDIA . 'images/entrada_sin_foto.png';

        $intIdEntrada = $request['identrada'];
        $sql_img = "SELECT min(id) as id, img FROM imagen WHERE entradaid = $intIdEntrada";

        $recuest_img = $this->con->select($sql_img);
        if ($recuest_img['img'] != '') {
          $request['img'] = $recuest_img['img'];
          $request['img_url'] = DIR_IMAGEN . $recuest_img['img'];
        }
      } else {
        $request = 'Entrada se encuentra temporalmente desahbilitada';
      }
    } else {
      $request = 'No se encuentra la entrada en el blog';
    }
    return $request;
  }

}
