<?php

declare(strict_types=1);

class VisitasModel extends Mysql {

  public function __construct() {
    parent::__construct();
  }

  function registrarVisita($ip, $pagina, $url, $dispositivo, $dispositivoOS, $pais, $ciudad, $localidad, $idnav, $idUser) {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $sql = 'INSERT INTO visitas(ip, pagina, url, dispositivo,os,pais,ciudad,localidad,navegador_id,user_id,user_agent) VALUES(?,?,?,?,?,?,?,?,?,?,?)';
    $arrData = array($ip, $pagina, $url, $dispositivo, $dispositivoOS, $pais, $ciudad, $localidad, $idnav, $idUser, $user_agent);
    $sentencia = $this->insert($sql, $arrData);
    return $sentencia > 0 ? true : false;
  }

  function getVisitasSinCity($cant) {
    return $this->select_all("SELECT `idvisita`,`ip`,`pais`,`ciudad`,`localidad` 
                              FROM `visitas` WHERE
                              `pais` = '' OR `pais` IS null OR
                              `ciudad` = '' OR `ciudad` IS null OR 
                              `localidad` ='' OR `localidad` IS null ORDER BY `ciudad` ASC LIMIT $cant ");

//    return $this->select_all("SELECT `idvisita`,`ip`,`pais`,`ciudad`,`localidad` FROM visitas WHERE `ciudad` = 'Buenos Aires F.D.' LIMIT $cant;");
  }

  function updateGeolocalizacionVisita($pais, $ciudad, $localidad, $id) {
    $sql = 'UPDATE `visitas` SET `pais` = ?, `ciudad` = ?, `localidad` = ? WHERE `visitas`.`idvisita` = ?';
    $arrData = array($pais, $ciudad, $localidad, $id);
    $sentencia = $this->update($sql, $arrData);

    return $sentencia > 0 ? true : false;
  }

}
