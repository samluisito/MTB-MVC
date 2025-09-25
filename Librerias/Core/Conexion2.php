<?php

class Conexion {

    private $conect;
    private $DB_HOST = 'localhost';
    private $DB_NAME= 'id15433932_db_tiendavirtual_karina';
    private $DB_USER = 'id15433932_roo';
    private $DB_PASSWORD = 'VirtualStore-123';
    
    public function __construct() {

        try {
            $conectionString = "mysql:host=" . $this->DB_HOST . ";dbname=" . $this->DB_NAME . ";charset=utf8";

            $this->conect = new PDO($conectionString, $this->DB_USER, $this->DB_PASSWORD);

            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // echo "conexion abierta <br>";
        } catch (Exception $e) {
            //$this-> conect = "Error de conexion";
            print "ERROR : " . $e->getMessage() . "<br>";
        }
    }

    public function connect() {
        return $this->conect;
    }

}
