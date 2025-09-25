<?php

declare(strict_types=1);

//require_once('Models/TContacto.php');   // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 
//require_once("Models/TCategoria.php");    // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 

class Contacto extends Controllers {

  public function __construct() {
    parent::__construct();
    $empresa = $_SESSION['info_empresa'];
    if ($empresa['fecha_mantenimiento_hasta'] > date("Y-m-d H:i:s")) {
      header("Location:" . base_url() . 'enConstruccion');
      exit();
    }
  }

  public function Contacto() {

    /*     * ******************************************* */
    include __DIR__ . '/../Controllers/Home.php';
    $this->data = new Home();
    $data['header'] = $this->data->data_header('Pedido Confirmado');
    $data['footer'] = $this->data->data_footer();
    /*     * ******************************************* */
    $empresa = $_SESSION['info_empresa'];
    $data['empresa'] = $empresa;
   

    $data['meta'] = array(
      'robots' => 'noindex, nofollow, noarchive',
      'title' => $empresa['nombre_comercial'],
      'description' => substr(strClean(strip_tags($empresa['descripcion'])), 0, 160),
      'keywords' => $empresa['tags'],
      'url' => base_url(),
      'image' => $empresa['url_logoImpreso'],
      'image:type' => explode('.', $empresa['logo_imp'])[1],
      'og:type' => 'website'
    );

    $data["page_css"] = array();
    $data["page_functions_js"] = array();

    $this->views->getView("Contacto", $data);
  }

  public function formContacto() {
    //dep($_POST);
//contacto de prueba
    $meta = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . getUserIP()));

    $origen = 'contacto';
    $nombre = strClean($_POST['txtNombre']);
    $apellido = strClean($_POST['txtApellido']);
    $telefono = strClean($_POST['txtTelefono']);
    $email = strClean($_POST['txtEmail']);
    $mensaje = strClean($_POST['mensaje']);
    $ip_contacto = $_SERVER['REMOTE_ADDR'];
    $geoplugin = json_encode($meta, JSON_UNESCAPED_UNICODE);
    $localidad = $meta['geoplugin_city'];
    $ciudad = $meta['geoplugin_regionName'];
    $pais = $meta['geoplugin_countryName'];
    $dispositivo = detectar_dispositivo();//dispositivoTipo();
    $dispositivoOS = dispositivoOS();
    $navegador = $_SERVER['HTTP_USER_AGENT'];

    $request = $this->model->newContacto(
        $origen,
        $nombre,
        $apellido,
        $telefono,
        $email,
        $mensaje,
        $ip_contacto,
        $geoplugin,
        $localidad,
        $ciudad,
        $pais,
        $dispositivo,
        $dispositivoOS,
        $navegador);
    if ($request) {
      $empresa = $_SESSION['info_empresa'];
      $arrDataEmail = array(// preparamos el array con los datos requeridos
        'empresa' => $empresa, // un array con los datos de la empresa, y configuracion
        'nombreUsuario' => $nombre . ' ' . $apellido, //nombre del usuario
        'email' => $email, //email del usuario (email destino)
        'asunto' => $empresa['nombre_comercial'],
      );
      $bodyMail = getFile("Template/Email/email_contacto", $arrDataEmail);
      set_notificacion('contacto', $request);
      sendEMail($arrDataEmail, $bodyMail);

      $arrResponse = array('status' => true, 'msg' => 'Nos estaremos comunicando lo antes posible');
    } else {
      $arrResponse = array('status' => false, 'msg' => 'No se pudo guargar el usuario, intente mas tarde');
    }

    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

}
