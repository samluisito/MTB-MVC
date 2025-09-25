<?php

declare(strict_types=1);

class Controllers {

  protected $model;
  protected $views;
  protected $data;

  public function __construct() {
    $class = get_class($this);
//    

    $this->loadModel($class);
//        
    $this->views = new Views($class);
  }

  function loadModel($class): void {
    $model = $class . 'Model';
    $modelPath = __DIR__ . '/../../Models/';
    $modelFile = $modelPath . $model . '.php';
    if (file_exists($modelFile)) {
      require_once $modelFile;
      $this->model = new $model();
      $this->infoEmpresa();
      if (isset($_COOKIE['id_sesion']) && empty($_SESSION['login'])) {
        $this->restaurarSesion();
      }
    }
  }

  function infoEmpresa(): void {
    $vence = $_SESSION['info_empresa']['vence'] ?? '2000-01-01 00:00:00';
    $now = time();
    if (strtotime($vence) <= $now) {
      $request = $this->model->select("SELECT oficial_venta, blue_venta,fecha FROM divisa WHERE idcotizacion = (SELECT MAX(idcotizacion) FROM divisa)");      // Consulta el valor de la última cotización de la divisa
      if ($request) {
        $cotizacion = $_SESSION['base']['region_abrev']=='VE'? $request['oficial_venta']: $request['blue_venta'];
        $_SESSION['dolarhoy'] = array('precio' => $cotizacion, 'fecha' => $request['fecha']); // Si la consulta encontró un valor pasa un array a la variable de sesion con los datos de la nueva fecha.
        strtotime($request['fecha']) <= $now ?? setDolarHoy(); // Si la fecha obtenida es menor a hoy entonces ejecutar setDolarHoy()
      }
      // Consulta la información de la empresa
      $request = $this->model->select("SELECT * FROM config_gral WHERE idempresa = 1");
      // Redondea el costo de envío y lo multiplica por la cotización del dólar actual
      $dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();
      $request['costo_envio'] = redondear_decenas($request['costo_envio'] * $dolar);
      // Agrega la ruta completa de la imagen al array
      $request['url_shortcutIcon'] = sprintf('%s%s', DIR_IMAGEN, $request['shortcut_icon']);
      $request['url_logoMenu'] = sprintf('%s%s', DIR_IMAGEN, $request['logo_menu']);
      $request['url_logoImpreso'] = sprintf('%s%s', DIR_IMAGEN, $request['logo_imp']);
      // Establece la fecha de vencimiento de la sesión a 60 minutos a partir de ahora
      $request['vence'] = date("Y-m-d H:i:s", strtotime("+60 minutes"));
      // Establece la información de la empresa en la variable de sesión
      $_SESSION['info_empresa'] = $request;
    }
  }

  private function restaurarSesion() {
    // Verifica si la cookie de ID de sesión está establecida
    // Obtiene el ID de sesión de la cookie
    $idSesion = $_COOKIE['id_sesion'];

    // Busca el registro de la sesión en la base de datos
    $querySql = "SELECT * FROM `sesiones` WHERE `id_sesion`=? AND `estado_sesion`=1"; // Solo busca sesiones activas
    $resultado = $this->model->select($querySql, array($idSesion));

    if ($resultado) {    // Si se encuentra la sesión en la base de datos, restaura los datos de la sesión en $_SESSION
      $browser = getUserBrowser();
      $OS = dispositivoOS();
      if ($resultado['os'] == $OS || $resultado['browser'] == $browser) {
        sessionLogin($this->model, $resultado['id_persona']);
      }

//      $arrDataSesion = json_decode($resultado[0]['arr_data_sesion'], true); // Convierte la cadena JSON en un array
//      $_SESSION = $arrDataSesion;
      //Actualiza la fecha de la sesión en la base de datos
//      $fecha = date('Y-m-d H:i:s');
//      $querySql = "UPDATE `sesiones` SET `fecha`='$fecha' WHERE `id_sesion`='$idSesion'";
//      $this->modal->update($querySql);
    }
  }

  public function getModel() {
    return $this->model;
  }

}
