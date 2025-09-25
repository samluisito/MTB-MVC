<?php

declare(strict_types=1);

class AContactosModel extends Mysql {

  private $idcontacto;
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
  private $navegador;

  public function __construct() {
    //echo 'mensaje desde el modelo home';
    parent::__construct();
  }

  public function selectContactos() {

    //EXTRAE DATOS DE LA TABLA PERSONAS CON EL ROL DE CLIENTES
    $sql = "SELECT  idcontacto,
                        origen,
                        nombre,
                        apellido,
                        telefono,
                        email,
                        localidad,
                        DATE_FORMAT(datecreated, '%d-%m-%Y') as fechaRegistro
                FROM contacto 
                ORDER BY idcontacto DESC                ";
    $request = $this->select_all($sql);
    return $request;
  }

//EXTRAE EXTRAE UN ROL, PARAMETRO DE ENTRADA EL ID A BUSCAR, DEVUELVE UN ARRAY CON LOS DATOS DEL ROL
  public function selectContacto(int $id) {
    $this->idcontacto = $id;
    $sql = "SELECT  idcontacto,
                        origen,
                        nombre,
                        apellido,
                        telefono,
                        email,
                        mensaje,
                        localidad,
                        ciudad,
                        pais,
                        navegador,
                        DATE_FORMAT(datecreated, '%d-%m-%Y') as fechaRegistro
                FROM contacto 
                WHERE idcontacto = '{$this->idcontacto}' ";
    $request = $this->select($sql);
    return $request;
  }


}
