<?php

declare(strict_types=1);

class Conexion {

  private $conect;
  private $DB_HOST;
  private $DB_NAME;
  private $DB_USER;
  private $DB_PASSWORD;

  //private $query;

  public function __construct() {

    $this->DB_HOST = $_SESSION['base']['db_host'];
    $this->DB_NAME = $_SESSION['base']['db_name'];
    $this->DB_USER = $_SESSION['base']['db_user'];
    $this->DB_PASSWORD = $_SESSION['base']['db_password'];

//    if (CONTROLADOR_DB === 'PDO') {// usamos el controlador PDO
//      try {
//
//        $this->conect = new PDO("mysql:host=" . $this->DB_HOST . ";dbname=" . $this->DB_NAME . ";charset=utf8", $this->DB_USER, $this->DB_PASSWORD);
//        $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//      } catch (Exception $e) {
//
//        echo "ERROR : " . $e->getMessage() . "<br>"; //$this-> conect = "Error de conexion";
//      }
//    } else {//Usamos el Controlador MySQLi
    $this->conect = new mysqli('127.0.0.1', $this->DB_USER, $this->DB_PASSWORD, $this->DB_NAME);

    $this->conect->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $this->conect->set_charset("utf8mb4");
    if ($this->conect->connect_error) {
      exit('Error connecting to database'); //Should be a message a typical user could understand in production
//      }
//      mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
//      $this->conect = mysqli_connect($this->DB_HOST, $this->DB_USER, $this->DB_PASSWORD, $this->DB_NAME);
//      mysqli_set_charset($this->conect, 'utf8mb4'); /* Set the desired charset after establishing a connection */
//      printf("Success... %s\n", mysqli_get_host_info($this->conect));
    }
  }

  public function connect() {
    return $this->conect;
  }

}
