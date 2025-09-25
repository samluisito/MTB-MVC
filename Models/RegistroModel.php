<?php

declare(strict_types=1);

class RegistroModel extends Mysql {

  public function __construct() {
    parent::__construct();
  }


 
  /* -------------------------------------------------------------------------------------------------------- */

  public function selectMail(string $email) {
    $this->con = new Mysql();
    $this->strEmail = $email;
    $return = 0;
    //consultamos la existencia del mail '
    $sql = "SELECT idpersona FROM persona WHERE email_user = '{$this->strEmail}' ";

    $request = $this->con->select($sql);

    if (empty($request)) {
      $return = "OK";
    } else {
      $return = "Exist";
    }

    return $return;
  }

  /* -------------------------------------------------------------------------------------------------------- */

  public function insertCliente(string $nombre, string $apellido, int $telefono = null, string $email,
      string $sexo, string $direccion = null, string $localidad, string $ciudad, string $pais = null,
      string $password, int $idTpoRol, string $oauth_provider = null, string $oauth_uid = null, string $img = null) {
    $return = 0;

    //consultamos la existencia de una identificacion o imail duplicado    or identificacion ='{$this->strIdentificacion}'
    $request = $this->select("SELECT * FROM persona WHERE email_user = '{$email}' ");
    if (empty($request)) {/* si la consulta es nul  entonce insertamos el Usuario */
      $query_insert = "INSERT INTO persona (nombres, apellidos, telefono, email_user, sexo,direccionfiscal,localidad,ciudad,
                        pais, password, rolid,oauth_provider,oauth_uid,img) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
      $arrData = array($nombre, $apellido, $telefono, $email, $sexo, $direccion, $localidad,
        $ciudad, $pais, $password, $idTpoRol, $oauth_provider, $oauth_uid, $img,);

      $return = $this->insert($query_insert, $arrData);
    } else {
      $return = "exist";
    }

    return $return;
  }

  /* -------------------------------------------------------------------------------------------------------- */
}
