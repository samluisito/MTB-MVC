<?php

declare(strict_types=1);

class Controllers
{
    protected ?object $model = null;
    protected Views $views;

    public function __construct()
    {
        $className = get_class($this);
        $this->views = new Views($className);
        $this->loadModel($className);
    }

    /**
     * Carga el modelo correspondiente para el controlador.
     * Por ejemplo, para el controlador 'Users', intenta cargar 'UsersModel'.
     * También desencadena tareas relacionadas con la sesión después de cargar el modelo.
     */
    private function loadModel(string $className): void
    {
        $modelClass = $className . 'Model';
        $modelFile = __DIR__ . '/../../Models/' . $modelClass . '.php';

        if (file_exists($modelFile)) {
            require_once $modelFile;
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

    public function getModel(): ?object
    {
        return $this->model;
    }
}