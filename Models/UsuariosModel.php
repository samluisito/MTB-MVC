<?php

declare(strict_types=1);

class UsuariosModel extends Mysql {

  private $intIdUser;
  private $strIdentificacion;
  private $strNombre;
  private $strApellido;
  private $strEmail;
  private $intTelefono;
  private $intTipoRolId;
  private $intStatus;
  private $strPassword;
  private $strtoken;
  private $strNit;
  private $strNombreFiscal;
  private $strDireccionFiscal;

  public function __construct() {
    parent::__construct();
  }

  public function insertUsuario(string|int $identificacion, string $nombre, string $Apellido, int $telefono,
      string $email, string $password, int $idtporol, int $status) {

    $this->strIdentificacion = $identificacion;
    $this->strNombre = $nombre;
    $this->strApellido = $Apellido;
    $this->strEmail = $email;
    $this->intTelefono = $telefono;
    $this->intTipoRolId = $idtporol;
    $this->intStatus = $status;
    $this->strPassword = $password;

    //consultamos la existencia de una identificacion o imail duplicado
    $request = $this->select_all("SELECT * FROM persona WHERE email_user = '{$this->strEmail}' or identificacion ='{$this->strIdentificacion}'");

    if (empty($request)) {
      // si la consulta es nul  entonce insertamos el Usuario
      $query_insert = "INSERT INTO persona (identificacion, nombres, apellidos, telefono, email_user, password, rolid, status) VALUES (?,?,?,?,?,?,?,?)";
      $arrData = array(
        $this->strIdentificacion,
        $this->strNombre,
        $this->strApellido,
        $this->intTelefono,
        $this->strEmail,
        $this->strPassword,
        $this->intTipoRolId,
        $this->intStatus);

      return $this->insert($query_insert, $arrData);
    } else {
      return "exist";
    }
    return 0;
  }

  public function selectListUsuarios() {
//EXTRAE ROLES
    return $this->select_all(
            "SELECT a.idpersona, a.identificacion, a.nombres, a.apellidos, a.telefono, a.email_user, a.status, b.nombrerol
                FROM persona a INNER JOIN rol b
                ON a.rolid = b.idrol
                WHERE  a.status < 2
                ");//AND a.rolid !=2
  }

  public function selectUser(int $idUser) {
//EXTRAE EXTRAE UN ROL, PARAMETRO DE ENTRADA EL ID A BUSCAR, DEVUELVE UN ARRAY CON LOS DATOS DEL ROL
    $this->intIdUser = $idUser;
    return $this->select(
            "SELECT a.idpersona, a.identificacion, a.nombres, a.apellidos,
            a.telefono, a.email_user, a.nit, a.nombrefiscal,
            a.direccionfiscal, b.idrol, b.nombrerol,
            a.status, DATE_FORMAT(a.datecreated, '%d-%m-%Y') as fechaRegistro 
                FROM persona a INNER JOIN rol b
                ON a.rolid = b.idrol
                WHERE a.idpersona = '{$this->intIdUser}'");
  }

  public function updateUsuario(int $idUser, string|int $identificacion, string $nombre, string $Apellido, int $telefono, string $email, string $password, int $idtporol, int $status) {
    $this->intIdUser = $idUser;
    $this->strIdentificacion = $identificacion;
    $this->strNombre = $nombre;
    $this->strApellido = $Apellido;
    $this->strEmail = $email;
    $this->intTelefono = $telefono;
    $this->intTipoRolId = $idtporol;
    $this->intStatus = $status;
    $this->strPassword = $password;
    //consultamos la existencia de una identificacion o imail duplicado
    $request = $this->select_all("SELECT idpersona FROM persona  WHERE  email_user = '{$this->strEmail}' AND idpersona != '{$this->intIdUser}' or identificacion ='{$this->strIdentificacion}'AND idpersona != '{$this->intIdUser}'");
    if (empty($request)) {
      $arrData = array($this->strIdentificacion, $this->strNombre, $this->strApellido, $this->intTelefono, $this->strEmail, $this->intTipoRolId, $this->intStatus);
      if ($this->strPassword != "") {
        array_push($arrData, $this->strPassword);
        $pass = ',password = ?';
      }
      return $this->update("UPDATE persona SET identificacion = ?, nombres = ?, apellidos = ?, telefono = ?, email_user = ?, rolid = ?, status = ? {$pass} WHERE idpersona = '{$this->intIdUser}'", $arrData);
    } else {
      return "exist";
    }
    return 0;
  }

  public function deleteUser($idPersona) {

    $this->intIdUser = $idPersona;
    $sql = "UPDATE persona SET status = ? WHERE idpersona = $this->intIdUser";
    $arrData = array(2);
    $request = $this->update($sql, $arrData);
    return $request;
  }

  public function updatePerfil(int $idUser, string|int $identificacion,
      string $nombre, string $Apellido, int $telefono, string $password) {

    $this->intIdUser = $idUser;
    $this->strIdentificacion = $identificacion;
    $this->strNombre = $nombre;
    $this->strApellido = $Apellido;
    $this->intTelefono = $telefono;
    $this->strPassword = $password;

    $arrData = array(
      $this->strIdentificacion,
      $this->strNombre,
      $this->strApellido,
      $this->intTelefono,
    );
    if ($this->strPassword != "") {
      array_push($arrData, $this->strPassword);
      $pass = ',password = ?';
    }

    return $this->update(
            "UPDATE persona SET identificacion = ?, nombres = ?, apellidos = ?, telefono = ? 
            {$pass} WHERE idpersona = '{$this->intIdUser}'",
            $arrData);
  }

  public function updateDataFiscal(int $intIdUser, string $strNit,
      string $strNombreFiscal, string $strDireccionFiscal) {

    $this->intIdUser = $intIdUser;
    $this->strNit = $strNit;
    $this->strNombreFiscal = $strNombreFiscal;
    $this->strDireccionFiscal = $strDireccionFiscal;

    return $this->update(
            "UPDATE persona SET nit = ?, nombrefiscal = ?, direccionfiscal = ? WHERE idpersona = '{$this->intIdUser}'",
            array($this->strNit, $this->strNombreFiscal, $this->strDireccionFiscal));
  }

    public function usuarioEnUso($idpersona) {
    $this->intIdUser = $idpersona;
    return $this->select("SELECT MAX(idpedido)as idpedido FROM pedido WHERE personaid = $this->intIdUser");
  }
  
}
