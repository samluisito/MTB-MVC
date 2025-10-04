<?php

declare(strict_types=1);

namespace App\Librerias\Core;

/**
 * Clase base para todos los controladores de la aplicación.
 *
 * Se encarga de cargar automáticamente el modelo y la vista correspondientes
 * al controlador que se está ejecutando.
 *
 * @version 2.1.0
 * @author Jules
 */
class Controllers
{
    /** @var Mysql|null El modelo asociado al controlador. */
    protected ?Mysql $model = null;

    /** @var Views La vista asociada al controlador. */
    protected Views $views;

    /** @var array Datos que se pasarán a la vista. */
    protected array $data = [];

    public function __construct()
    {
        // Obtiene el nombre corto de la clase del controlador (ej. 'Home' de 'App\Controllers\Home').
        $fullClassName = get_class($this);
        $className = substr($fullClassName, strrpos($fullClassName, '\\') + 1);

        $this->views = new Views($className);
        $this->loadModel($className);
    }

    /**
     * Carga el modelo correspondiente para el controlador.
     * El modelo debe seguir la convención de nomenclatura 'NombreControladorModel'.
     *
     * @param string $controllerName El nombre corto del controlador.
     */
    private function loadModel(string $controllerName): void
    {
        // Construye el nombre completo de la clase del modelo con su namespace.
        $modelClass = "App\\Models\\" . $controllerName . 'Model';

        if (class_exists($modelClass)) {
            $this->model = new $modelClass();

            // Estos métodos solo pueden ejecutarse si se carga un modelo con éxito.
            $this->infoEmpresa();
            if (isset($_COOKIE['id_sesion']) && empty($_SESSION['login'])) {
                $this->restaurarSesion();
            }
        }
    }

    /**
     * Almacena en caché la información general de la empresa en la sesión para reducir las consultas a la base de datos.
     * La caché está configurada para expirar después de 60 minutos.
     */
    private function infoEmpresa(): void
    {
        $now = time();
        $vence = $_SESSION['info_empresa']['vence'] ?? '2000-01-01 00:00:00';

        if (strtotime($vence) > $now) {
            return; // La caché todavía es válida.
        }

        // Obtiene la última cotización de la moneda.
        $request = $this->model->select("SELECT oficial_venta, blue_venta, fecha FROM divisa WHERE idcotizacion = (SELECT MAX(idcotizacion) FROM divisa)");
        if ($request) {
            $cotizacion = ($_SESSION['base']['region_abrev'] === 'VE') ? $request['oficial_venta'] : $request['blue_venta'];
            $_SESSION['dolarhoy'] = ['precio' => $cotizacion, 'fecha' => $request['fecha']];
        }

        // Obtiene la configuración general de la empresa.
        $request = $this->model->select("SELECT * FROM config_gral WHERE idempresa = 1");
        $dolar = ($_SESSION['base']['region_abrev'] === 'VE') ? 1 : getDolarHoy();
        $request['costo_envio'] = redondear_decenas($request['costo_envio'] * $dolar);
        $request['url_shortcutIcon'] = DIR_IMAGEN . $request['shortcut_icon'];
        $request['url_logoMenu'] = DIR_IMAGEN . $request['logo_menu'];
        $request['url_logoImpreso'] = DIR_IMAGEN . $request['logo_imp'];

        // Establece una expiración de 60 minutos para los datos en caché.
        $request['vence'] = date("Y-m-d H:i:s", strtotime("+60 minutes"));
        $_SESSION['info_empresa'] = $request;
    }

    /**
     * Restaura la sesión de un usuario utilizando un ID de sesión de una cookie ("recuérdame").
     */
    private function restaurarSesion(): void
    {
        $idSesion = $_COOKIE['id_sesion'];
        $querySql = "SELECT * FROM `sesiones` WHERE `id_sesion`=? AND `estado_sesion`=1";
        $resultado = $this->model->select($querySql, [$idSesion]);

        if ($resultado) {
            // Verificación de seguridad básica: asegurar que el SO o el navegador coincidan con el almacenado en la sesión.
            if ($resultado['os'] === dispositivoOS() || $resultado['browser'] === getUserBrowser()) {
                sessionLogin($this->model, $resultado['id_persona']);
            }
        }
    }

    /**
     * Devuelve la instancia del modelo.
     *
     * @return Mysql|null
     */
    public function getModel(): ?Mysql
    {
        return $this->model;
    }
}