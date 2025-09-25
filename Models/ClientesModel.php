<?php

declare(strict_types=1);

class ClientesModel extends Mysql {

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
    //echo 'mensaje desde el modelo home';
    parent::__construct();
  }

  public function insertCliente(string $identificacion, string $nombre, string $apellido, int $telefono,
      string $email, string $password, int $idTpoRol, string $Nit, string $NombreFiscal,
      string $DirFiscal) {

    $this->strIdentificacion = $identificacion;
    $this->strNombre = $nombre;
    $this->strApellido = $apellido;
    $this->intTelefono = $telefono;
    $this->strEmail = $email;
    $this->strPassword = $password;
    $this->intTipoRolId = $idTpoRol;
    $this->strNit = $Nit;
    $this->strNombreFiscal = $NombreFiscal;
    $this->strDireccionFiscal = $DirFiscal;
    //$this->intStatus = $status;

    $return = 0;

    //consultamos la existencia de una identificacion o imail duplicado
    $sql = "SELECT * FROM persona WHERE email_user = '{$this->strEmail}' or identificacion ='{$this->strIdentificacion}'";

    $request = $this->select_all($sql);

    if (empty($request)) {
      // si la consulta es nul  entonce insertamos el Usuario
      $query_insert = "INSERT INTO persona (
                	identificacion,
                        nombres, apellidos,
                        telefono, email_user,
                        password, nit, nombrefiscal, direccionfiscal,rolid) VALUES (?,?,?,?,?,?,?,?,?,?)";

      $arrData = array(
        $this->strIdentificacion,
        $this->strNombre,
        $this->strApellido,
        $this->intTelefono,
        $this->strEmail,
        $this->strPassword,
        $this->strNit,
        $this->strNombreFiscal,
        $this->strDireccionFiscal,
        $this->intTipoRolId);

      $request_insert = $this->insert($query_insert, $arrData);
      $return = $request_insert;
    } else {
      $return = "exist";
    }
    return $return;
  }

  public function selectClientes() {
    //EXTRAE DATOS DE LA TABLA PERSONAS CON EL ROL DE CLIENTES
    return $this->select_all("SELECT idpersona, identificacion, nombres, apellidos, telefono, email_user, status 
          FROM persona WHERE rolid = 2 AND status < 2 AND idpersona IN 
          (SELECT `personaid` FROM `pedido` WHERE status = 'Completo' GROUP BY `personaid`)");
  }

  public function cteEnUso($idpersona) {
    $this->intIdUser = $idpersona;
    return $this->select("SELECT MAX(idpedido)as idpedido FROM pedido WHERE personaid = $this->intIdUser");
  }

  public function selectCliente(int $idpersona) { //EXTRAE EXTRAE UN ROL, PARAMETRO DE ENTRADA EL ID A BUSCAR, DEVUELVE UN ARRAY CON LOS DATOS DEL ROL
    $this->intIdUser = $idpersona;
    $sql = "SELECT idpersona, identificacion,
                       nombres, apellidos,
                       telefono, email_user,
                       nit, nombrefiscal,
                       direccionfiscal, status,
                       DATE_FORMAT(datecreated, '%d-%m-%Y') as fechaRegistro 
                FROM persona 
                WHERE idpersona = '{$this->intIdUser}' ";

    $request = $this->select($sql);
    return $request;
  }

  public function updateCliente(int $idUser, string $identificacion, string $nombre, string $Apellido,
      int $telefono, string $email, string $password, string $Nit, string $NombreFiscal,
      string $DirFiscal) {

    $this->intIdUser = $idUser;
    $this->strIdentificacion = $identificacion;
    $this->strNombre = $nombre;
    $this->strApellido = $Apellido;
    $this->strEmail = $email;
    $this->intTelefono = $telefono;
    $this->strPassword = $password;
    $this->strNit = $Nit;
    $this->strNombreFiscal = $NombreFiscal;
    $this->strDireccionFiscal = $DirFiscal;

    $return = 0;

    //consultamos la existencia de una identificacion o imail duplicado
    $sql = "SELECT idpersona FROM persona 
            WHERE  email_user = '{$this->strEmail}' AND idpersona != '{$this->intIdUser}'
            or identificacion ='{$this->strIdentificacion}'AND idpersona != '{$this->intIdUser}'";

    $request = $this->select_all($sql);

    if (empty($request)) {
      if ($this->strPassword != "") {
        $sql_update = "UPDATE persona SET 
                	identificacion = ?,
                        nombres = ?, apellidos = ?,
                        telefono = ?, email_user = ?,
                        password = ?, nit = ?, 
                        nombrefiscal = ?, direccionfiscal = ?
                        WHERE idpersona = '{$this->intIdUser}'";

        $arrData = array(
          $this->strIdentificacion,
          $this->strNombre,
          $this->strApellido,
          $this->intTelefono,
          $this->strEmail,
          $this->strPassword,
          $this->strNit,
          $this->strNombreFiscal,
          $this->strDireccionFiscal);
      } else {
        $sql_update = "UPDATE persona SET 
                	identificacion = ?,
                        nombres = ?, apellidos = ?,
                        telefono = ?, email_user = ?,
                        nit = ?, nombrefiscal = ?, direccionfiscal =?
                        WHERE idpersona = '{$this->intIdUser}'";

        $arrData = array(
          $this->strIdentificacion,
          $this->strNombre,
          $this->strApellido,
          $this->intTelefono,
          $this->strEmail,
          //$this->strPassword,
          $this->strNit,
          $this->strNombreFiscal,
          $this->strDireccionFiscal);
      }

      $request_update = $this->update($sql_update, $arrData);
      //$return = $request_insert;
    } else {
      return "exist";
    }
    return $request_update;
  }

  public function deleteCliente($idPersona) {

    $this->intIdUser = $idPersona;
    $sql = "UPDATE persona SET status = ? WHERE idpersona = $this->intIdUser";
    $arrData = array(2);
    $request = $this->update($sql, $arrData);
    return $request;
  }

}
