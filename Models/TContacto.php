<?php

declare(strict_types=1);

//require_once './Librerias/Core/Mysql.php';

trait TContacto {

  private $origen;
  private $nombre;
  private $apellido;
  private $telefono;
  private $email;
  private $mensaje;
  private $ip_contacto;
  private $geoplugin;
  private $localidad;
  private $ciudad;
  private $pais;
  private $dispositivo;
  private $os;
  private $navegador;
  private $con;

  function newContacto(string $origen, string $nombre, string $apellido, string $telefono,
      string $email, string $mensaje, string $ip_contacto, string $geoplugin,
      $localidad = null, string $ciudad = null, string $pais = null, string $dispositivo, string $dispositivoOS, string $navegador) {

    $this->con = new Mysql();

    $this->origen = $origen;
    $this->nombre = $nombre;
    $this->apellido = $apellido;
    $this->telefono = $telefono;
    $this->email = $email;
    $this->mensaje = $mensaje;
    $this->ip_contacto = $ip_contacto;
    $this->geoplugin = $geoplugin;
    $this->localidad = $localidad;
    $this->ciudad = $ciudad;
    $this->pais = $pais;
    $this->dispositivo = $dispositivo;
    $this->os = $dispositivoOS;
    $this->navegador = $navegador;

    $sql = "INSERT INTO contacto (
            origen,
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
            navegador
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $arrData = array(
      $this->origen,
      $this->nombre,
      $this->apellido,
      $this->telefono,
      $this->email,
      $this->mensaje,
      $this->ip_contacto,
      $this->geoplugin,
      $this->localidad,
      $this->ciudad,
      $this->pais,
      $this->dispositivo,
      $this->os,
      $this->navegador);

    $request = $this->con->insert($sql, $arrData);

    return $request;
  }

}
