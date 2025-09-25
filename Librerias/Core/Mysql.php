<?php

declare(strict_types=1);
/* Metodo con php >8.1 */

class Mysql extends Conexion {

  protected $conexion;

  function __construct() {
    // Call the parent constructor to ensure the static connection is established.
    parent::__construct();
    // Assign the static connection object to the local property for use in this class's methods.
    $this->conexion = $this->getConexion();
  }

  /* Metodo con php >8.1 */

  public function select(string $query, null|array $arrayvalues = null) {
    if ($arrayvalues == null) {
      $result = $this->conexion->query($query);
      return $result->num_rows === 0 ? null : $result->fetch_assoc();
    } else {
      $result = $this->conexion->prepare($query);
      $result->execute($arrayvalues);
      $response = $result->get_result();
      return $response->num_rows === 0 ? null : $response->fetch_assoc();
    }
  }

  public function select_column(string $query, null|array $arrayvalues = null) {
    if ($arrayvalues == null) {
      $result = $this->conexion->query($query);
      return $result->num_rows === 0 ? null : $result->fetch_column();
    } else {
      $result = $this->conexion->prepare($query);
      $result->execute($arrayvalues);
      $response = $result->get_result();
      return $response->num_rows === 0 ? null : $response->fetch_column();
    }
  }

  public function select_all(string $query, null|array $arrayvalues = null) {
    if ($arrayvalues == null) {
      $result = $this->conexion->query($query);
      return $result->num_rows === 0 ? array() : (array) $result->fetch_all(MYSQLI_ASSOC);
    } else {
      $result = $this->conexion->prepare($query);
      $result->execute($arrayvalues);
      $response = $result->get_result();
      return $response->num_rows === 0 ? array() : (array) $response->fetch_all(MYSQLI_ASSOC);
    }
    // Si el número de filas devueltas es 0, devuelve un array vacío, de lo contrario devuelve un array asociativo
  }

  public function select_all_column(string $query, null|array $arrayvalues = null) {
    if ($arrayvalues == null) {
       $result = $this->conexion->query($query);
      return $result->num_rows === 0 ? array() : (array) $this->fetch_column($result);
    } else {
      $result = $this->conexion->prepare($query);
      $result->execute($arrayvalues);
      $response = $result->get_result();
      return $response->num_rows === 0 ? array() : (array) $this->fetch_column($response);
    }
    // Si el número de filas devueltas es 0, devuelve un array vacío, de lo contrario devuelve un array asociativo
  }

  function fetch_column($data) {
    $data = $data->fetch_all(MYSQLI_NUM);    // Devuelve un array con la primera columna de cada fila de la consulta
    $valor = array();
    foreach ($data as $value) {
      array_push($valor, $value[0]);
    }
    return $valor;
  }

  public function insert(string $query, array $arrayvalues): int {
    $result = $this->conexion->prepare($query);
    $result->execute($arrayvalues);
    return (int) $result->insert_id;
  }

  public function update(string $query, array $arrayvalues): int {
    $result = $this->conexion->prepare($query);
    $result->execute($arrayvalues);
    return $result->affected_rows;
  }

  public function delete(string $query): int {
    $result = $this->conexion->prepare($query);
    $result->execute();
    return $result->affected_rows;
  }

}