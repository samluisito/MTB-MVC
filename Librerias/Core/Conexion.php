<?php

declare(strict_types=1);

require_once __DIR__ . '/RedisCache.php';

class Conexion {

  private static $connect = null;

  public function __construct() {
    // Solo ejecutar la lógica de conexión si la conexión aún no se ha establecido.
    if (self::$connect === null) {
      if (empty($_SESSION['base'])) {
        // La configuración del cliente ahora se obtiene desde la caché o la base de datos.
        $_SESSION['base'] = $this->getTenantConfig();
      }

      $this->conectar($_SESSION['base']);
      $this->setConfiguracionRegional($_SESSION['base']);
      require_once __DIR__ . '/../../Config/Propiedades.php';
    }
  }

  private function getTenantConfig(): array
  {
      $cache = RedisCache::getInstance();
      $cacheKey = 'db_config:' . BD_SELECT;

      if ($cache->isConnected()) {
          $cachedConfig = $cache->get($cacheKey);
          if ($cachedConfig) {
              return $cachedConfig;
          }
      }

      // Si no está en caché o Redis no funciona, se obtiene de la BD y se guarda en caché.
      return $this->fetchConfigFromControlDB($cache, $cacheKey);
  }

  private function fetchConfigFromControlDB(RedisCache $cache, string $cacheKey): array
  {
      // Credenciales para la base de datos central que contiene la información del inquilino.
      $controlDbCreds = [
          'db_host' => '127.0.0.1:3306',
          'db_user' => TPO_SERV_LOCAL ? 'root' : 'mitienda_prod',
          'db_password' => TPO_SERV_LOCAL ? '' : 'mitienda031282',
          'db_name' => 'mitienda_Control',
      ];

      // Establece una conexión temporal y no persistente a la BD de control.
      $controlDb = new mysqli(...array_values($controlDbCreds));

      if ($controlDb->connect_error) {
          error_log("Error de conexión a la BD de control: " . $controlDb->connect_error);
          // Si no podemos conectarnos a la BD de control, no podemos continuar.
          require_once(__DIR__ . '/../../Controllers/Error.php');
          exit();
      }

      $controlDb->set_charset('utf8mb4');

      // Se espera que las funciones de Helpers.php estén cargadas.
      $tenantIdentifier = strtolower(clear_cadena(strClean(BD_SELECT)));

      $sql = "SELECT a.*, b.*
              FROM clientes a
              INNER JOIN config_regional b ON a.regionid = b.idregion
              WHERE a.url_empresa = ?";

      $stmt = $controlDb->prepare($sql);
      $stmt->bind_param('s', $tenantIdentifier);
      $stmt->execute();
      $clientConfig = $stmt->get_result()->fetch_assoc();

      $stmt->close();
      $controlDb->close();

      if ($clientConfig) {
          // Si se encuentra la configuración, se guarda en caché por 24 horas si Redis está disponible.
          if ($cache->isConnected()) {
              $cache->set($cacheKey, $clientConfig, 86400);
          }
          return $clientConfig;
      }

      // Si no se encuentra la configuración del cliente para el subdominio, se activa un 404.
      require_once(__DIR__ . '/../../Controllers/Error.php');
      exit();
  }

  private function conectar($credenciales) {
    self::$connect = new mysqli('p:' . $credenciales['db_host'], $credenciales['db_user'], $credenciales['db_password'], $credenciales['db_name']);
    $this->propiedadesConect();
    if (self::$connect->connect_error) {
      exit('Error conectando a la base de datos');
    }
    self::$connect->set_charset($credenciales['db_charset']);
  }

  private function propiedadesConect() {
    self::$connect->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    self::$connect->set_charset("utf8mb4");
  }

  public function getConexion() {
    return self::$connect;
  }

  private function setConfiguracionRegional($arrData) {
    setlocale(LC_ALL, $arrData['idioma'] . '_' . $arrData['region_abrev'] . '.UTF-8');
    date_default_timezone_set($arrData['timezone']);
    setlocale(LC_MONETARY, $arrData['idioma'] . '_' . $arrData['region_abrev'] . '.UTF-8');
    setlocale(LC_NUMERIC, $arrData['idioma'] . '_' . $arrData['region_abrev'] . '.UTF-8');

    if (!defined('SMONEY')) {
      define('SMONEY', $arrData['moneda_simbolo']);
      define('SPM', $arrData['moneda_separador_miles']);
      define('SPD', $arrData['moneda_separador_decimales']);
    }

    $zona_horaria_utc = $this->convertirZonaHoraria($arrData['zona_horaria']);
    $query = "SET time_zone = '$zona_horaria_utc'";
    self::$connect->query($query);
    return true;
  }

  private function convertirZonaHoraria($zona_horaria) {
    $signo = substr($zona_horaria, 3, 1);
    $horas = substr($zona_horaria, 4);
    if (strpos($horas, ':') !== false) {
      return $signo . $horas;
    } else {
      return $signo . $horas . ':00';
    }
  }
}