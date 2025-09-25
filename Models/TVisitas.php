<?php
declare(strict_types=1);

trait TVisitas {
    private $con;
    function registrarVisita($ip, $pagina, $url, $dispositivo, $dispositivoOS, $pais, $ciudad, $localidad, $idnav, $idUser) {
        $this->con = new Mysql();
        $sql = 'INSERT INTO visitas(ip, pagina, url, dispositivo,os,pais,ciudad,localidad,navegador_id,user_id) VALUES(?,?,?,?,?,?,?,?,?,?)';
        $arrData = array($ip, $pagina, $url, $dispositivo, $dispositivoOS, $pais, $ciudad, $localidad,$idnav, $idUser);
        $sentencia = $this->con->insert($sql, $arrData);
        return $sentencia >0? true: false;
       
    }

}
