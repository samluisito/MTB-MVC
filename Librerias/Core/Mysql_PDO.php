<?php

declare(strict_types=1);

class Mysql extends Conexion {

  protected $conexion;
  protected $strquery;
  protected $arrayValues;

  function __construct() {
    $this->conexion = new Conexion(); //solicitamos la conexion a la base de datos. la cual ase auto ejecura en el constructor.
    $this->conexion = $this->conexion->connect();
  }

  public function select(string $query) {
    $this->strquery = $query;
    $result = $this->conexion->query($this->strquery); // ejecuta la consula simple
//    if (CONTROLADOR_DB === 'PDO') {
//      return $result->fetch(PDO::FETCH_ASSOC);
//    } else {
    $result = $result->num_rows === 0 ?
        null : // exit('No rows');
        $result->fetch_assoc();

    return $result;
//    }
  }

  public function select_column(string $query) {
    $this->strquery = $query;
    $result = $this->conexion->query($this->strquery);
//    if (CONTROLADOR_DB === 'PDO') {
//      return $result->fetch(PDO::FETCH_COLUMN);
//    } else {
    $result = $result->num_rows === 0 ? null : // exit('No rows');
        $result->fetch_column();
//    }
    return $result;
  }

  public function select_all(string $query) {
    $this->strquery = $query;
    $result = $this->conexion->query($this->strquery);
//    if (CONTROLADOR_DB === 'PDO') {
//      return $result->fetchAll(PDO::FETCH_ASSOC);
//    } else {
    $result = $result->num_rows === 0 ?
        array() : // exit('No rows');
        (array) $result->fetch_all(MYSQLI_ASSOC);
    return $result;
//    }
  }

  public function select_all_column(string $query) {
    $this->strquery = $query;
    $result = $this->conexion->query($this->strquery);
//    if (CONTROLADOR_DB === 'PDO') {
//      return $result->fetchAll(PDO::FETCH_COLUMN);
//    } else {
      $result->num_rows === 0 ?
              $result = false : // exit('No rows');
              $result = $this->mysqli_fetch_all_column($result);
      return $result;
//    }
  }

//inserta un registro 
  public function insert(string $query, array $arrayvalues) {
    $this->strquery = $query;
    $this->arrayValues = $arrayvalues;
    $result = $this->conexion->prepare($this->strquery);
//    if (CONTROLADOR_DB === 'PDO') {
//      $resinsert = $result->execute($this->arrayValues); //verificamos que se inserto el registro, si se inserto devolvemos el numero de id, sino devolvemos 0
//      return $resinsert ? $this->conexion->lastinsertid() : 0;
//    } else {
      //$this->strTypes = $this->data_types($arrayvalues);
      /* Metodo con php >8.1 */
      $result->execute($this->arrayValues);
      /* Metodo con php <8.1 */
//            $result = $this->conexion->prepare($this->strquery); // prepare
//            $result->bind_param(str_repeat('s', count($arrayvalues)), ...$arrayvalues); // bind array at once
//            $result->execute();
      $result = $result->insert_id;

      return $result;
//    }
  }

  public function update(string $query, array $arrayvalues) {
    $this->strquery = $query;
    $this->arrayValues = $arrayvalues;
    $result = $this->conexion->prepare($this->strquery);
//    if (CONTROLADOR_DB === 'PDO') {
//      return $result->execute($this->arrayValues);
//    } else {
      /* Metodo con php >8.1 */
      $result->execute($this->arrayValues);
      /* Metodo con php <8.1 */
//            $this->strTypes = $this->data_types($arrayvalues);
//            $result = $this->conexion->prepare($this->strquery); // prepare
//            $result->bind_param($this->strTypes, str_repeat(count($arrayvalues)), ...$arrayvalues); // bind array at once
//            $result->execute();
      return !empty($result->error) ? $result->error : 1;
//    }
  }

  public function delete(string $query) {
    $this->strquery = $query;
    $result = $this->conexion->prepare($this->strquery);
//    if (CONTROLADOR_DB === 'PDO') {
//      return $result->execute();
//    } else {
      /* Metodo con php >8.1 */
      $result->execute();
      $result = $result->affected_rows;
      return $result;
//    }
  }

  function mysqli_fetch_all_column($data) {
    $data = $data->fetch_all(MYSQLI_NUM);
    $valor = array();
    foreach ($data as $value) {
      array_push($valor, $value[0]);
    }
    return $valor;
  }

}
