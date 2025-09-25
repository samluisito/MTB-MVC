<?php

declare(strict_types=1);

class ContactoModel extends Mysql {

  public function __construct() {
    parent::__construct();
  }


  /* --------------------------------------------------------------------------------------------------- */

  function newContacto(string $origen, string $nombre, string $apellido, string $telefono,
      string $email, string $mensaje, string $ip_contacto, string $geoplugin,
      string $localidad = null, string $ciudad = null, string $pais = null, string $dispositivo, string $dispositivoOS, string $navegador) {
    $sql = "INSERT INTO contacto (            origen,
            nombre,
            apellido,
            telefono,
            email,
            mensaje,
            ip_contacto,
            geoplugin,
            localidad,
            ciudad,
            pais,
            dispositivo,
            os,
            navegador            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $arrData = array($origen,
      $nombre,
      $apellido,
      $telefono,
      $email,
      $mensaje,
      $ip_contacto,
      $geoplugin,
      $localidad,
      $ciudad,
      $pais,
      $dispositivo,
      $dispositivoOS,
      $navegador);
    return $this->insert($sql, $arrData);
  }

}
