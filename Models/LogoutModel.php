<?php

declare(strict_types=1);

class logoutModel extends Mysql {

  public function __construct() {
    parent::__construct();
  }

  function cerrar_session($idSesion) {
    $querySql = "UPDATE `sesiones` SET `estado_sesion`= 0 WHERE `id_sesion`= ?"; //AND `id_persona`='$idPersona'
    $this->update($querySql, array($idSesion));
  }

}
