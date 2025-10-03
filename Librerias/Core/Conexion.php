<?php

declare(strict_types=1);

require_once __DIR__ . '/RedisCache.php';

/**
 * Gestiona la conexión a la base de datos para un entorno multi-inquilino.
 *
 * Esta clase implementa un patrón Singleton para garantizar una única conexión
 * a la base de datos por solicitud. Primero se conecta a una base de datos
 * de "control" para validar el subdominio (inquilino) y obtener sus
 * credenciales de base de datos, que luego se utilizan para establecer la
 * conexión principal de la aplicación.
 *
 * La configuración del inquilino se almacena en caché (Redis) para mejorar
 * el rendimiento en solicitudes posteriores.
 *
 * @version 2.0.1
 * @author Jules
 */
class Conexion
{
    /** @var mysqli|null La instancia única de la conexión mysqli. */
    protected static ?mysqli $connect = null;

    /**
     * El constructor es privado para evitar la instanciación directa.
     * Inicia el proceso de conexión si aún no se ha establecido.
     */
    public function __construct()
    {
        if (self::$connect === null) {
            $this->initializeConnection();
        }
    }

    /**
     * Inicializa la conexión completa: obtiene la configuración del inquilino,
     * se conecta a su base de datos y establece la configuración regional.
     */
    private function initializeConnection(): void
    {
        // La configuración del inquilino se carga una vez y se guarda en la sesión.
        $_SESSION['base'] ??= $this->getTenantConfig();
        $credentials = $_SESSION['base'];

        $this->connectToTenantDatabase($credentials);
        $this->applyRegionalSettings($credentials);

        // Carga propiedades adicionales de configuración.
        require_once __DIR__ . '/../../Config/Propiedades.php';
    }

    /**
     * Obtiene la configuración del inquilino, ya sea desde la caché de Redis o
     * desde la base de datos de control como fallback.
     *
     * @return array La configuración del inquilino.
     */
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

        // Si no está en caché o Redis no está disponible, se obtiene de la BD de control.
        return $this->fetchConfigFromControlDB($cache, $cacheKey);
    }

    /**
     * Se conecta a la base de datos de control para obtener la configuración
     * de un inquilino específico basado en su identificador (subdominio).
     *
     * @param RedisCache $cache      Instancia del manejador de caché.
     * @param string     $cacheKey   La clave para almacenar la configuración en caché.
     *
     * @return array La configuración del inquilino.
     */
    private function fetchConfigFromControlDB(RedisCache $cache, string $cacheKey): array
    {
        // NOTA: Las credenciales están hardcodeadas. En un entorno ideal,
        // esto debería manejarse a través de variables de entorno.
        $controlDbCreds = [
            'host' => '127.0.0.1:3306',
            'user' => TPO_SERV_LOCAL ? 'root' : 'mitienda_prod',
            'password' => TPO_SERV_LOCAL ? '' : 'mitienda031282',
            'dbname' => 'mitienda_Control',
        ];

        // Se utiliza una conexión temporal y no persistente.
        $controlDb = new mysqli(...array_values($controlDbCreds));

        if ($controlDb->connect_error) {
            error_log("Error de conexión a la BD de control: " . $controlDb->connect_error);
            require_once __DIR__ . '/../../Controllers/Error.php';
            exit();
        }

        $controlDb->set_charset('utf8mb4');

        // Se sanitiza el identificador del inquilino antes de la consulta.
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
            if ($cache->isConnected()) {
                // Guarda la configuración en caché por 24 horas.
                $cache->set($cacheKey, $clientConfig, 86400);
            }
            return $clientConfig;
        }

        // Si no se encuentra el inquilino, se muestra una página de error.
        require_once __DIR__ . '/../../Controllers/Error.php';
        exit();
    }

    /**
     * Establece la conexión principal a la base de datos del inquilino.
     *
     * @param array $credentials Las credenciales de la BD del inquilino.
     */
    private function connectToTenantDatabase(array $credentials): void
    {
        // Se antepone 'p:' para una conexión persistente.
        self::$connect = new mysqli(
            'p:' . $credentials['db_host'],
            $credentials['db_user'],
            $credentials['db_password'],
            $credentials['db_name']
        );

        if (self::$connect->connect_error) {
            // En caso de fallo, se detiene la ejecución para evitar errores posteriores.
            error_log("Error conectando a la base de datos del inquilino: " . self::$connect->connect_error);
            exit('Error de conexión con la base de datos.');
        }

        $this->setDefaultConnectionProperties();
    }

    /**
     * Configura las propiedades por defecto para la conexión mysqli.
     */
    private function setDefaultConnectionProperties(): void
    {
        // Convierte los tipos INT y FLOAT de SQL a tipos nativos de PHP.
        self::$connect->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
        // Lanza excepciones en caso de errores de mysqli.
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        // Establece el conjunto de caracteres.
        self::$connect->set_charset("utf8mb4");
    }

    /**
     * Devuelve la instancia de la conexión mysqli.
     *
     * @return mysqli|null
     */
    public function getConexion(): ?mysqli
    {
        return self::$connect;
    }

    /**
     * Configura los ajustes regionales y de zona horaria para la sesión actual.
     *
     * @param array $config La configuración regional del inquilino.
     */
    private function applyRegionalSettings(array $config): void
    {
        $locale = "{$config['idioma']}_{$config['region_abrev']}.UTF-8";

        setlocale(LC_ALL, $locale);
        setlocale(LC_MONETARY, $locale);
        setlocale(LC_NUMERIC, $locale);
        date_default_timezone_set($config['timezone']);

        // Define constantes monetarias si aún no existen.
        if (!defined('SMONEY')) {
            define('SMONEY', $config['moneda_simbolo']);
            define('SPM', $config['moneda_separador_miles']);
            define('SPD', $config['moneda_separador_decimales']);
        }

        // Sincroniza la zona horaria de la base de datos con la de la aplicación.
        $dbTimeZone = $this->convertTimeZoneToSqlFormat($config['zona_horaria']);
        self::$connect->query("SET time_zone = '{$dbTimeZone}'");
    }

    /**
     * Convierte un formato de zona horaria (ej. 'GMT-4:30') a un formato
     * compatible con SQL (ej. '-04:30').
     *
     * @param string $timeZoneString La zona horaria a convertir.
     * @return string El formato de zona horaria para SQL.
     */
    private function convertTimeZoneToSqlFormat(string $timeZoneString): string
    {
        $sign = substr($timeZoneString, 3, 1);
        $hours = substr($timeZoneString, 4);

        return str_contains($hours, ':') ? "{$sign}{$hours}" : "{$sign}{$hours}:00";
    }
}