<?php

declare(strict_types=1);

class rolesModel extends Mysql {

  //roles
  public $intIdRol;
  public $strRol;
  public $strDescripcion;
  public $intStatus;
  //permisos
  public $intIdModulo;
  public $intRolId;
  public $intModuloId;
  public $intVer;
  public $intCrear;
  public $intActualizar;
  public $intEliminar;
  public $strTpoPerm;
  public $intValPerm;

  public function __construct() {
    //echo 'mensaje desde el modelo home';
    parent::__construct();
  }

  public function selectRoles() {
    //EXTRAE ROLES
    $sql = "SELECT * FROM rol WHERE status < 2";
    $recuest = $this->select_all($sql);
    return $recuest;
  }

  public function selectRol(int $idRol) {
    //EXTRAE EXTRAE UN ROL, PARAMETRO DE ENTRADA EL ID A BUSCAR, DEVUELVE UN ARRAY CON LOS DATOS DEL ROL
    $this->intIdRol = $idRol;
    $sql = "SELECT * FROM rol WHERE idrol = $this->intIdRol ";
    $recuest = $this->select($sql);
    return $recuest;
  }

  public function insertRol(string $Rol, string $Descripcion, int $Status) {
    $return = "";
    $this->strRol = $Rol;
    $this->strDescripcion = $Descripcion;
    $this->intStatus = $Status;

    //consultamos la existencia de un rol duplicado
    $sql = "SELECT * FROM rol WHERE nombrerol = '{$this->strRol}'";
    $recuest = $this->select_all($sql);

    if (empty($recuest)) {
      // si la consulta es nul  entonce insertamos el rol
      $query_insert = "INSERT INTO rol (nombrerol, descripcion, status) VALUES (?,?,?)";
      $arrData = array($this->strRol, $this->strDescripcion, $this->intStatus);
      $request_insert = $this->insert($query_insert, $arrData);
      $return = $request_insert;
    } else {
      $return = "exist";
    }
    return $return;
  }

  public function updateRol(int $idRol, string $Rol, string $Descripcion, int $Status) {
    $return = "";

    $this->intIdRol = $idRol;

    $this->strRol = $Rol;
    $this->strDescripcion = $Descripcion;
    $this->intStatus = $Status;

    //validamos que el rol no este duplcado 
    $sql = "SELECT * FROM rol WHERE nombrerol = '$this->strRol' AND idrol != $this->intIdRol";
    $request = $this->select_all($sql);

    // si la consulta es nul  entonce insertamos el rol
    if (empty($request)) {
      $query_update = "UPDATE rol SET nombrerol = ?, descripcion = ?, status = ? WHERE idrol =  '$this->intIdRol'";
      $arrData = array($this->strRol, $this->strDescripcion, $this->intStatus);
      $request_update = $this->update($query_update, $arrData);
    } else {
      $request_update = 'exist';
    }

    return $request_update;
  }

  public function deleteRol($idRol) {
    $this->intIdRol = $idRol;
    $sql = "SELECT * FROM persona WHERE rolid = $this->intIdRol";
    $request = $this->select_all($sql);

    if (empty($request)) {
      $sql = "UPDATE rol SET status = ? WHERE idrol = $this->intIdRol";
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

  public function rolEnUso($idRol) {
    $this->intIdRol = $idRol;
    return $this->select("SELECT rolid FROM persona WHERE rolid = $this->intIdRol");
  }

  public function editStatus($id, $intStatus) {
    $request = 'error';
    if (is_numeric($id) && is_numeric($intStatus)) {
      $status = $intStatus == 1 ? 0 : 1;
      $r = $this->update("UPDATE rol SET status = ? WHERE idrol = $id", array($status));
      $request = $r ? 'OK' : 'error';
    }
    return $request;
  }

  public function selectModulos($idrol) {
    $this->intIdRol = $idrol;
    //EXTRAE modulos
    $sql = "SELECT idmodulo FROM modulo";  //contamos la cantidad modulos
    $recuest_modulo = $this->select_all($sql);

    for ($i = 0; $i < count($recuest_modulo); $i++) {  // Repasamos que haya un permiso por cada modulo para el Rol, (si hay 5 modulo, deben de haber 5 permisos por cada rol)
      $this->intModuloId = $recuest_modulo[$i]['idmodulo'];
      $sql = "SELECT idmodulo FROM permisos WHERE moduloid = $this->intModuloId AND rolid = $this->intIdRol ";
      $recuest_permiso = $this->select($sql);

      if (empty($recuest_permiso)) { //si no hay un permiso para el modulo y el rol, se cre uno, con valores 0
        $query_insert = "INSERT INTO permisos (rolid, moduloid, ver, crear, actualizar, eliminar) VALUES (?,?, '0', '0', '0', '0');";
        $arrData = array($this->intIdRol, $this->intModuloId);
        $request_insert = $this->insert($query_insert, $arrData);
      }
    }

    //devolvemos una consulta con los valores 
    $sql = "SELECT
                p.idmodulo,
                p.rolid,
                p.moduloid,
                m.titulo,
                p.ver,
                p.crear,
                p.actualizar,
                p.eliminar
            FROM
                permisos p
            INNER JOIN modulo m
            ON p.moduloid = m.idmodulo
            WHERE
                p.rolid = $this->intIdRol ";  //contamos la cantidad modulos
    $recuest_permiso_modulo = $this->select_all($sql);

    return $recuest_permiso_modulo;
    die();
  }

  public function editPermiso($intIdPerm, $strTpoPerm, $valorTpoPerm) {

    $this->intModuloId = $intIdPerm;
    $this->strTpoPerm = $strTpoPerm;
    $this->intValPerm = $valorTpoPerm;

    $sql = "UPDATE permisos SET {$this->strTpoPerm} = ? WHERE idmodulo = {$this->intModuloId}";
    $arrData = array($this->intValPerm);
    $request = $this->update($sql, $arrData);

    $sql = "SELECT rolid FROM permisos WHERE  idmodulo = {$this->intModuloId}";
    $request_rol = $this->select($sql);

    if ($request_rol['rolid'] == $_SESSION['userData']['rolid']) {
      sessionUser($_SESSION['idUser']);
    }

    return $request;
  }

}
