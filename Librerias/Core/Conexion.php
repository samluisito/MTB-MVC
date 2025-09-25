<?php

declare(strict_types=1);

class Conexion {

  public $connect;

  function __construct() {
    if (empty($_SESSION['base'])) {
      $this->conectar(array(
        'db_host' => '127.0.0.1:3306', //TPO_SERV_LOCAL ? '127.0.0.1:3306' : '127.0.0.1:3306',
        'db_user' => TPO_SERV_LOCAL ? 'root' : 'mitienda_prod',
        'db_password' => TPO_SERV_LOCAL ? '' : 'mitienda031282',
        'db_name' => 'mitienda_Control',
        'db_charset' => 'utf8mb4',
      ));
      $this->setear_bd($this->seleccionarBD());
    } else {
      $this->conectar($_SESSION['base']);
    }

    $this->setConfiguracionRegional($_SESSION['base']);

    require_once __DIR__ . '/../../Config/Propiedades.php';
  }

  private function conectar($credenciales) {
    $this->connect = new mysqli('p:' . $credenciales['db_host'], $credenciales['db_user'], $credenciales['db_password'], $credenciales['db_name']);
    $this->propiedadesConect();
    if ($this->connect->connect_error) {
      exit('Error conectando a la base de datos'); // Debe ser un mensaje que un usuario típico pueda entender en producción.
    }
    $this->connect->set_charset($credenciales['db_charset']);
  }

  private function setear_bd($nombre_bd) {
    if (!$this->connect->select_db($nombre_bd)) {
      exit('Error seleccionando la base de datos'); // Debe ser un mensaje que un usuario típico pueda entender en producción.
    }
  }

  private function propiedadesConect() {
    $this->connect->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);    // Configura algunas opciones de la conexión para mejorar el rendimiento.
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);    // Configura la conexión para que lance excepciones en caso de errores.
    $this->connect->set_charset("utf8mb4");    // Configura la conexión para usar el conjunto de caracteres UTF-8.
  }

  private function seleccionarBD() {
    // Utilizar una sentencia preparada para evitar inyecciones de SQL
    $sql = "SELECT a.idcte, a.db_host, a.db_name, a.db_user, a.db_password, a.db_charset,
        b.region, b.region_abrev, b.idioma, b.timezone, b.moneda, b.moneda_formato, b.moneda_simbolo, b.moneda_separador_miles, b.moneda_separador_decimales, b.zona_horaria, b.fecha_formato
            FROM clientes a
            INNER JOIN config_regional b ON a.regionid = b.idregion
            WHERE a.url_empresa = ?";

    $stmt = $this->connect->prepare($sql);
//    $stmt->bind_param('s', strval(BD_SELECT));
//    $stmt->execute();


    $stmt->execute([BD_SELECT]); // Usamos un array con la variable de enlace



    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
      $_SESSION['base'] = $row;
      return $row['db_name'];
    } else {
      exit(require_once('./Controllers/Error.php'));
      session_unset();
      session_destroy();
    }
  }

  public function getConexion() {
    return $this->conexion;
  }

  private function setConfiguracionRegional($arrData) {

    // Establecer la configuración regional
    setlocale(LC_ALL, $arrData['idioma'] . '_' . $arrData['region_abrev'] . '.UTF-8');
    date_default_timezone_set($arrData['timezone']);

    // Configuración específica de la moneda
    setlocale(LC_MONETARY, $arrData['idioma'] . '_' . $arrData['region_abrev'] . '.UTF-8');
    setlocale(LC_NUMERIC, $arrData['idioma'] . '_' . $arrData['region_abrev'] . '.UTF-8'); // Establecer punto decimal como separador para números

    if (!defined('SMONEY')) {
      define('SMONEY', $arrData['moneda_simbolo']);
      define('SPM', $arrData['moneda_separador_miles']);
      define('SPD', $arrData['moneda_separador_decimales']);
    }

    $zona_horaria_utc = $this->convertirZonaHoraria($arrData['zona_horaria']);

    $query = "SET time_zone = '$zona_horaria_utc'";
    $this->connect->query($query);
    return true;
  }

  private function convertirZonaHoraria($zona_horaria) {
    $signo = substr($zona_horaria, 3, 1); // Obtiene el signo (+ o -)
    $horas = substr($zona_horaria, 4); // Obtiene las horas y minutos (ejemplo: 3, 4:30, etc.)
    // Si hay minutos en la representación de la zona horaria, se utiliza la notación "X:30"
    if (strpos($horas, ':') !== false) {
      return $signo . $horas;
    } else {
      return $signo . $horas . ':00';
    }
  }
}

//dep($_SESSION);
//unset($_SESSION);
//session_destroy();
