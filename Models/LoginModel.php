<?php

declare(strict_types=1);

class loginModel extends Mysql {

  private $intIdUser;
  private $strUsuario;
  private $strPassword;
  private $strToken;
  private $intRolId;

  public function __construct() {
    //echo 'mensaje desde el modelo home';
    parent::__construct();
  }

  public function validarUserLogin(string $strUsuario, string $strPassword) {
    $sql = "SELECT idpersona, status FROM persona
                WHERE email_user = ? AND password = ?";
    $arrData = array($strUsuario, $strPassword);
    $request = $this->select($sql, $arrData);
    return $request;
  }

//
//  public function sessionUserData(int $idpersona) {
//    $idpersona = intval($idpersona);
//
//    $request['user_data'] = $this->select("SELECT p.idpersona, p.identificacion, p.nombres, p.apellidos,
//            p.telefono, p.email_user, p.nit, p.nombrefiscal, p.direccionfiscal, p.rolid, p.datecreated,
//            p.status, r.nombrerol, r.idrol 
//            FROM persona p  INNER JOIN rol r 
//            ON p.rolid = r.idrol where p.idpersona =?", array($idpersona));
//
//    $request['permisos'] = $this->select_all("SELECT p.idmodulo, p.rolid, p.moduloid, m.titulo, p.ver, p.crear, p.actualizar, p.eliminar 
//                FROM permisos p  INNER JOIN modulo m ON p.moduloid = m.idmodulo 
//                where p.rolid in (SELECT rolid FROM persona where idpersona =?)", array($idpersona));
//
//    return $request;
//  }

  function consultar_session(string $idSesion, int $idPersona, string $OS, string $browser) {
    $querySql = "SELECT id FROM `sesiones` WHERE id_sesion = ? AND id_persona = ? AND os = ? AND browser = ? AND estado_sesion = 1"; // Solo busca sesiones activas
    return $this->select_column($querySql, array($idSesion, $idPersona, $OS, $browser));
  }

  function insertar_session(string $idSesion, string $fecha, int $estadoSesion, int $idPersona, string $OS, string $browser) {
    $querySql = "INSERT INTO `sesiones` (id_sesion, fecha, estado_sesion, id_persona, os, browser) VALUES (?,?,?,?,?,?)";
    $this->insert($querySql, array($idSesion, $fecha, $estadoSesion, $idPersona, $OS, $browser));
  }

  /* ------------------------------------------------------------------------------ */

  public function forgetPass($strUser) { //recibe un mail de usuario, genera un token y devuelve : idusuario, nombre, apellido, mail, token, status, para enviar un mail de restablecimiento de contraseña
    $this->strUsuario = $strUser; // realizamos una primera consulta para validar que el usuario exite y esta activo. 

    $sql = "SELECT idpersona FROM persona
                WHERE email_user = '{$this->strUsuario}'
                AND status = 1";

    $recuest = $this->select($sql);

    if (!empty($recuest)) { // si el usuario existe y esta activo, generamos un token y lo actualizamos en el usuario
      $this->intIdUser = $recuest['idpersona'];
      $this->strToken = token();

      $query_update = "UPDATE persona SET token=? WHERE idpersona = '{$this->intIdUser}'";
      $arrData = array($this->strToken);

      $recuest = $this->update($query_update, $arrData);

      if (!empty($recuest)) { // generamos una consulta con los datos necesarios para enviar el mail de restablecimiento de contraseña
        $sql = "SELECT idpersona, nombres, apellidos, email_user, token, status FROM persona
                WHERE idpersona = '{$this->intIdUser}'";

        $recuest = $this->select($sql);
      }
    }
    return $recuest;
  }

  public function buscar_id_x_token($token) {
    $this->strToken = $token;

    $sql = "SELECT idpersona, status FROM persona
                WHERE token = '{$this->strToken}'";

    $recuest = $this->select($sql);
    return $recuest;
  }

  public function updatePasword(int $idUser, string $password) {

    $this->intIdUser = $idUser;
    $this->strPassword = $password;

    $query_update = "UPDATE persona SET password=? WHERE idpersona = '{$this->intIdUser}'";
    $arrData = array($this->strPassword);

    $recuest = $this->update($query_update, $arrData);

    return $recuest;
  }

}

/*
ALTER TABLE `sesiones` 
ADD `os` VARCHAR(20) NULL AFTER `id_persona`, 
ADD `browser` VARCHAR(20) NULL AFTER `os`;


*/