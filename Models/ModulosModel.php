<?php

declare(strict_types=1);

class modulosModel extends Mysql {

  public $intIdModulo;
  public $strTiulo;
  public $strDescripcion;
  public $intStatus;

  public function __construct() {
    //echo 'mensaje desde el modelo home';
    parent::__construct();
  }

  public function selectModulos() {
    //EXTRAE ROLES
    $sql = "SELECT * FROM modulo WHERE status < 2";
    $recuest = $this->select_all($sql);
    return $recuest;
  }

  public function insertModulo(string $Titulo, string $Descripcion, int $Status) {
    $return = "";
    $this->strTiulo = $Titulo;
    $this->strDescripcion = $Descripcion;
    $this->intStatus = $Status;

    //consultamos la existencia de un rol duplicado
    $sql = "SELECT * FROM modulo WHERE titulo = '{$this->strTiulo}'";
    $recuest = $this->select_all($sql);

    if (empty($recuest)) {
      // si la consulta es nul entonce insertamos el rol
      $query_insert = "INSERT INTO modulo (titulo, descripcion, status) VALUES (?,?,?)";
      $arrData = array($this->strTiulo, $this->strDescripcion, $this->intStatus);
      $request_insert = $this->insert($query_insert, $arrData);
      $return = $request_insert;
    } else {
      $return = "exist";
    }
    return $return;
  }

  public function updateModulo(int $idModulo, string $Titulo, string $Descripcion, int $Status) {
    $return = "";

    $this->intIdModulo = $idModulo;

    $this->strTiulo = $Titulo;
    $this->strDescripcion = $Descripcion;
    $this->intStatus = $Status;

    //validamos que el modulo no este duplcado 
    $sql = "SELECT * FROM modulo WHERE titulo = '$this->strTiulo' AND idmodulo != $this->intIdModulo";
    $request = $this->select_all($sql);

    // si la consulta es nul entonce insertamos el rol
    if (empty($request)) {
      $query_update = "UPDATE modulo SET titulo = ?, descripcion = ?, status = ? WHERE idmodulo = '$this->intIdModulo'";
      $arrData = array($this->strTiulo, $this->strDescripcion, $this->intStatus);
      $request_update = $this->update($query_update, $arrData);
    } else {
      $request_update = 'exist';
    }

    return $request_update;
  }

  public function selectModulo(int $idModulo) {
    //EXTRAE EXTRAE UN ROL, PARAMETRO DE ENTRADA EL ID A BUSCAR, DEVUELVE UN ARRAY CON LOS DATOS DEL ROL
    $this->intIdModulo = $idModulo;
    $sql = "SELECT * FROM modulo WHERE idmodulo = $this->intIdModulo ";
    $recuest = $this->select($sql);
    return $recuest;
  }

  public function moduloEnUso($idmodulo) {
    $this->intIdModulo = $idmodulo;
    return $this->select_all("SELECT * FROM permisos WHERE idmodulo = $this->intIdModulo");
  }

  public function deleteModulo($idModulo) {
    $this->intIdModulo = $idModulo;
    $sql = "SELECT * FROM permisos WHERE moduloid = $this->intIdModulo";
    $request = $this->select_all($sql);

    if (empty($request)) {
      $sql = "UPDATE modulo SET status = ? WHERE idmodulo = $this->intIdModulo";
      $arrData = array(2);
      $request = $this->update($sql, $arrData);
      if ($request) {
        $request = 'OK';
      } else {
        $request = 'error';
      }
    } else {
      $request = 'exist';
    }
    return $request;
  }

  public function statusModulo($idModulo, $intStatus) {
    $this->intIdModulo = $idModulo;

    if ($intStatus == 1) {
      $this->intStatus = 0;
    } elseif ($intStatus == 0) {
      $this->intStatus = 1;
    }

    $sql = "UPDATE modulo SET status = ? WHERE idmodulo = $this->intIdModulo";
    $arrData = array($this->intStatus);
    $request = $this->update($sql, $arrData);
    if ($request) {
      $request = 'OK';
    } else {
      $request = 'error';
    }


    return $request;
  }

}

// dep($sql);