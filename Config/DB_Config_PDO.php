<?php

declare(strict_types=1);

class SelectBase {

  private $conect;
  private $query;
  private $name;
  private $DB_HOST;
  private $DB_NAME;
  private $DB_USER;
  private $DB_PASSWORD;
  private $DB_CHARSET;

  function __construct() {
    
  }

  function consulTab($bdcon, $serv_local) {
    if ($serv_local) {//determinamos si estamos en localhost 1 o en el servidor 0
      $this->DB_HOST = 'localhost:3306';
      $this->DB_NAME = 'mitienda_Control';
      $this->DB_USER = 'root';
      $this->DB_PASSWORD = '';
      $this->DB_CHARSET = ';charset=utf8mb4';
    } else {
      $this->DB_HOST = 'localhost:3306';
      $this->DB_NAME = 'mitienda_Control';
      $this->DB_USER = 'mitienda_prod';
      $this->DB_PASSWORD = 'mitienda031282';
      $this->DB_CHARSET = ';charset=utf8mb4';
    }
    $this->name = strtolower(clear_cadena(strClean($bdcon)));

// Consulta Con PDO

    try {
      $this->conect = new PDO('mysql:host=' . $this->DB_HOST . ';dbname=' . $this->DB_NAME . $this->DB_CHARSET, $this->DB_USER, $this->DB_PASSWORD);
      $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // echo 'conexion abierta <br>';
    } catch (Exception $e) {
      print 'ERROR : ' . $e->getMessage() . '<br>'; //$this-> conect = 'Error de conexion';
    }
    if (!($sentencia = $this->conect->prepare("SELECT * FROM clientes WHERE url_empresa = '{$this->name}'"))) {
      echo "Falló la preparación: (" . $this->conect->errno . ") " . $this->conect->error;
    }
    $result = $this->conect->prepare("SELECT * FROM clientes WHERE url_empresa = '{$this->name}'");
    $result->execute();
    $data = $result->fetch(PDO::FETCH_ASSOC);

    if (!empty($data)) {
      $_SESSION['base'] = array('id_emp' => $data['idcte'], 'nombre' => $this->name, 'db_host' => $data['db_host'], 'db_name' => $data['db_name'], 'db_user' => $data['db_user'], 'db_password' => $data['db_password'], 'db_charset' => $data['db_charset']);
      $resp = $data['idcte'];
    } else {
      $resp = 0;
    }

    unset($data, $result, $stmt, $mysqli, $this->DB_HOST, $this->DB_USER, $this->DB_PASSWORD, $this->DB_NAME);
    return $resp;
  }

}
