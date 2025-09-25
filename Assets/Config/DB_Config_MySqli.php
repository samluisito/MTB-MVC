<?php

declare(strict_types=1);

class SelectBase {

  private $name;
  private $mysqli;

  function __construct($serv_local) {
    // Implementar patrón Singleton para asegurarse de que solo haya una instancia de la clase mysqli en su aplicación
    if (isset($this->mysqli)) {
      return $this->mysqli;
    }
    // Establecer la conexión con la base de datos MySQL
    if ($serv_local) {
      $this->mysqli = new mysqli('127.0.0.1:3306', 'root', '', 'mitienda_Control');
    } else {
      $this->mysqli = new mysqli('127.0.0.1:3306', 'mitienda_prod', 'mitienda031282', 'mitienda_Control');
    }
    if ($this->mysqli->connect_error) {
      exit('Error connecting to database'); // Debe ser un mensaje que un usuario típico pueda entender en producción
    }
    $this->mysqli->set_charset("utf8mb4");
  }

  function consulTab($bdcon) {
    $this->name = strtolower($bdcon);

    // Utilizar una sentencia preparada para evitar inyecciones de SQL
    $stmt = $this->mysqli->prepare("SELECT idcte,db_host, db_name, db_user, db_password, db_charset FROM clientes WHERE url_empresa = ?");
    $stmt->bind_param("s", $this->name);
    $stmt->execute();
    $stmt->bind_result($idcte, $db_host, $db_name, $db_user, $db_password, $db_charset);
    $stmt->fetch();
    $stmt->close();

    // Cerrar explícitamente la conexión mysqli cuando haya terminado de usarla
    $this->mysqli->close();

    if (!empty($idcte)) {
      $_SESSION['base'] = array(
        'id_emp' => $idcte,
        'nombre' => $this->name,
        'db_host' => $db_host,
        'db_name' => $db_name,
        'db_user' => $db_user,
        'db_password' => $db_password,
        'db_charset' => $db_charset
      );
      return $idcte;
    } else {
      return 0;
    }
  }

}
