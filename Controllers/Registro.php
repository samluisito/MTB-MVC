<?php

declare(strict_types=1);

//require_once("Models/TClientes.php");    // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 
//require_once("Models/TCategoria.php");    // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 
require_once("Models/LoginModel.php");  // incluimos el archivo con los metodos trait, asi podemos ejecutar mas de una erencia en el controlador 

class Registro extends Controllers {

//  use TCategoria,
//      TClientes; //llama al uso de metodos de trait

  public function __construct() {
    parent::__construct();
  }

  public function Registro() {
    
        /*     * ******************************************* */
    include __DIR__ . '/../Controllers/Notificacion.php';
    $notificacion = new Notificacion();
    $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
    /*     * ******************************************* */
    /*     * ******************************************* */
    include __DIR__ . '/../Controllers/Home.php';
    $this->data = new Home();
    $data['header'] = $this->data->data_header('Profile');
    $data['footer'] = $this->data->data_footer();
    /*     * ******************************************* */
    $empresa = $_SESSION['info_empresa'];
    $data["empresa"] = $empresa;


    $data['page_name'] = 'Registro';
    $data['page_title'] = $data['page_name'];
    $data['logo_desktop'] = $empresa['url_logoMenu'];
    $data['shortcut_icon'] = $empresa['url_shortcutIcon'];


    $data['empresa'] = $empresa;

    $meta = $empresa;
    $data['meta'] = array(
      'robots' => 'noindex, nofollow, noarchive',
      'title' => $meta['nombre_comercial'],
      'description' => 'Suscribete', /* substr(strClean(strip_tags($meta['descripcion'])), 0, 160), */
      'keywords' => $meta['tags'],
      'url' => base_url(),
      'image' => $meta['url_logoImpreso'],
      'image:type' => explode('.', $meta['logo_imp']),
      'og:type' => 'website'
    );


    $data["page_css"] = array("wizardBootstrap.min.css");
    $data["page_functions_js"] = array("wizardBootstrap.js");

    $this->views->getView("Registro", $data);
  }

  /* --------------------------------------------------------------------------------- */

  public function PoliticaDePrivacidad() {
    $empresa = $_SESSION['info_empresa'];
    $data["empresa"] = $empresa;
    if ($empresa['fecha_mantenimiento_hasta'] > date("Y-m-d H:i:s")) {
      header("Location:" . base_url() . 'enConstruccion');
      exit();
    }

    $data['page_name'] = 'Registro';
    $data['page_title'] = $data['page_name'];
    $data['logo_desktop'] = $empresa['url_logoMenu'];
    $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

    /*     * ******************************************* */
    include __DIR__ . '/../Controllers/Notificacion.php';
    $notificacion = new Notificacion();
    $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
    /*     * ******************************************* */

    $data['empresa'] = $empresa;

    $meta = $empresa;
    $data['meta'] = array(
      'robots' => 'noindex, nofollow, noarchive',
      'title' => $meta['nombre_comercial'],
      'description' => 'Suscribete', /* substr(strClean(strip_tags($meta['descripcion'])), 0, 160), */
      'keywords' => $meta['tags'],
      'url' => base_url(),
      'image' => $meta['url_logoImpreso'],
      'image:type' => explode('.', $meta['logo_imp']),
      'og:type' => 'website'
    );
    $data['menu_categorias'] = $this->model->getCategoriasMenuTienda();

    $data['footer_cat'] = $this->model->getCategoriasFooterT();

    $data['empresa'] = array(
      'direccion' => $empresa['direccion'],
      'telefono' => $empresa['telefono'],
      'email' => $empresa['email']
    );
    // las funciones de la pagina van de ultimo 
    $data["page_css"] = array("wizardBootstrap.min.css");
    $data["page_functions_js"] = array("wizardBootstrap.js");

    $this->views->getView("PoliticaDePrivacidad", $data);
  }

  public function InstruccionesParaLaEliminacionDeDatosDeUsuario() {
    $empresa = $_SESSION['info_empresa'];
    $data["empresa"] = $empresa;
    if ($empresa['fecha_mantenimiento_hasta'] > date("Y-m-d H:i:s")) {
      header("Location:" . base_url() . 'enConstruccion');
      exit();
    }

    $data['page_name'] = 'Registro';
    $data['page_title'] = $data['page_name'];
    $data['logo_desktop'] = $empresa['url_logoMenu'];
    $data['shortcut_icon'] = $empresa['url_shortcutIcon'];
    /*     * ******************************************* */
    include __DIR__ . '/../Controllers/Notificacion.php';
    $notificacion = new Notificacion();
    $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
    /*     * ******************************************* */

    $data['empresa'] = $empresa;

//        $data["carrusel"] = $this->model->getCategoriasT()['carrusel'];
//      $data["banner"] = $this->model->getCategoriasT()['banner'];
//        $data["producto"] = $this->model->getProductosT();

    $meta = $empresa;
    $data['meta'] = array(
      'robots' => 'noindex, nofollow, noarchive',
      'title' => $meta['nombre_comercial'],
      'description' => 'Suscribete', /* substr(strClean(strip_tags($meta['descripcion'])), 0, 160), */
      'keywords' => $meta['tags'],
      'url' => base_url(),
      'image' => $meta['url_logoImpreso'],
      'image:type' => explode('.', $meta['logo_imp']),
      'og:type' => 'website'
    );
    $data['menu_categorias'] = $this->model->getCategoriasMenuTienda();

    $data['footer_cat'] = $this->model->getCategoriasFooterT();

    $data['empresa'] = array(
      'direccion' => $empresa['direccion'],
      'telefono' => $empresa['telefono'],
      'email' => $empresa['email']
    );
    // las funciones de la pagina van de ultimo 
    $data["page_css"] = array("wizardBootstrap.min.css");
    $data["page_functions_js"] = array("wizardBootstrap.js");

    $this->views->getView("InstruccionesEliminacionDatosUsuario", $data);
  }

  /* =============================================================================================== */

  public function regNvoUsuario() {
    if ($_POST) {  // validamos que post sea true 
      $empresa = $_SESSION['info_empresa'];
      if (//validamos que los datos no esten vacios 
          empty($_POST['txtNombre']) || empty($_POST['txtApellido']) ||
          empty($_POST['txtEmailReg']) || empty($_POST['txtTelefono']) ||
          empty($_POST['txtPassword']) || empty($_POST['txtRePassword'] ||
              ($_POST['txtPassword'] != $_POST['txtRePassword'])
          )
      ) {// si estan vacios 
        $arrResponse = array("status" => false, "msg" => 'Revisa el formulario, faltan datos o estan incorrectos');
      } else {// si los datos estan ok pasamos los datos de post a variables
        $strNombre = ucwords(strClean($_POST['txtNombre']));
        $strApellido = ucwords(strClean($_POST['txtApellido']));
        $strSexo = strClean($_POST['sexo']);
        $intTelefono = intval(strClean($_POST['txtTelefono']));
        $strEmail = strtolower(strClean($_POST['txtEmailReg']));
        $strPass = strClean($_POST['txtPassword']);
        $strRePass = strClean($_POST['txtRePassword']);

        $meta = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR']));
        $pais = $meta['geoplugin_countryName'];
        $strCiudad = isset($meta['geoplugin_regionName']) ?? ucwords($meta['geoplugin_regionName']);
        $strLocalidad = isset($meta['geoplugin_city']) ?? ucwords($meta['geoplugin_city']);
        $strDireccion = isset($_POST['Direccion']) ?? ucwords(strClean($_POST['Direccion']));

        $idTpoRol = 2; // en los clientes el rol por defecto es 2

        if ($strPass === $strRePass) {
          $strPasswordEncript = hash("SHA256", $strPass);
        }

        $request_user = $this->model->insertCliente($strNombre, $strApellido,
            $intTelefono, $strEmail, $strSexo,
            $strDireccion, $strLocalidad, $strCiudad, $pais,
            $strPasswordEncript, $idTpoRol);

        if ($request_user != 'exist') {// si se ejecuto el elvio de datos a la base de datos se enviara un mail, respuesta al cliente
          $login = new LoginModel();
          $login->sessionLogin($request_user);
          $_SESSION['idUser'] = $request_user;
          $_SESSION['login'] = true;

          $arrDataEmail = array(// preparamos el array con los datos requeridos
            'empresa' => $empresa, // un array con los datos de la empresa, y configuracion
            'nombreUsuario' => $strNombre . ' ' . $strApellido, //nombre del usuario
            'email' => $strEmail, //email del usuario (email destino)
            'asunto' => 'Bienvenido a ' . $empresa['nombre_comercial'],
            //CUERMO DEL MAIL 
            'password' => $strPass,
            'url_recovery' => /* base_url() . 'login/passwordReset&?t=' . $token */'',
          );
          $bodyMail = getFile("Template/Email/email_bienvenida", $arrDataEmail);
          sendEMail($arrDataEmail, $bodyMail); // ENVIAMOS EL MAIL / recibimos un 1 si es OK o un 0 se hay un error
          $arrResponse = array('status' => true, 'msg' => 'Enviamos a tu email la contraseña de accesso, para futuros ingresos');
        } elseif ($request_user === 'exist') {
          $arrResponse = array('status' => false, 'msg' => '!ATENCION¡ este Email ya se encuentra registrado');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'No se pudo guargar el usuario, intente mas tarde');
        }
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE); //IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON 
    }exit();
  }

  public function validarMail() {
    $mail = strClean($_POST['mail']);
    $resp = filter_var($mail, FILTER_VALIDATE_EMAIL) != false ? $this->model->selectMail($mail) : 'ivalid_format';
    exit(json_encode($resp, JSON_UNESCAPED_UNICODE));
  }

  /* ============================================================================================== */

  public function regConFb() {

    $arrResponse = array('status' => false, 'msg' => 'No se Cargar el Usuario intente mas tarde');

    $strNombre = ucwords(strClean($_POST['nombre']));
    $strApellido = ucwords(strClean($_POST['apellido']));
    $intTelefono = null;
    $strEmail = strtolower(strClean($_POST['email']));
    $strSexo = strClean($_POST['gender'] ?? 'I');

    $strPassword = passGenerator();
    $strPasswordEncript = hash("SHA256", $strPassword);

    $ip = getUserIP() ?? "";
    $ipLoc = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip));
    $strDireccion = null; //ucwords(strClean($_POST['Direccion']));
    $strLocalidad = ucwords(strClean($ipLoc['geoplugin_city']));
    $strCiudad = ucwords(strClean($ipLoc['geoplugin_regionName']));
    $pais = ucwords(strClean($ipLoc['geoplugin_countryName']));

    $strPassword = passGenerator();
    $strPasswordEncript = hash("SHA256", $strPassword);
    $idTpoRol = 2; // en los clientes el rol por defecto es 2

    $oauth_provider = 'facebook';
    $oauth_uid = $_POST['oauth_uid'];
    $strImg = $_POST['img'];

    $userID = $this->model->checkUserFB($oauth_provider, $oauth_uid, $strEmail); // Revisar si la información de usuario ya existe
    $userID = $userID['idpersona'] ?? '';
    if ($userID) { // actualizar información si es que existe
      $request_user = $this->model->actualuzarUserFB($oauth_provider, $oauth_uid, $strImg, $userID);
      if ($request_user) {// si se ejecuto el elvio de datos a la base de datos se enviara un mail, respuesta al cliente
        $_SESSION['idUser'] = $userID;
        $_SESSION['login'] = true;
        $login = new LoginModel();
        $login->sessionLogin($userID);

        $title = "Bienvedo $strNombre ' ";
        $msj = "<img src='$strImg' width='50px' height='50px' alt='$strNombre'/>";
        $arrResponse = array('status' => true, 'title' => $title, 'msg' => $strImg);
      }
    } else { // Insertar información del usuario
      $request_user = $this->model->insertCliente(
          $strNombre,
          $strApellido,
          $intTelefono,
          $strEmail,
          $strSexo,
          $strDireccion,
          $strLocalidad,
          $strCiudad,
          $pais,
          $strPasswordEncript,
          $idTpoRol,
          $oauth_provider,
          $oauth_uid,
          $strImg);

      if ($request_user != 'exist') {// si se ejecuto el elvio de datos a la base de datos se enviara un mail, respuesta al cliente
        $_SESSION['idUser'] = $request_user;
        $_SESSION['login'] = true;
        $login = new LoginModel();
        $login->sessionLogin($request_user);

        $title = "Registro Completado $strNombre";
        $msj = "<img src='$strImg' width='50px' height='50px' alt='$strNombre'/>";
        $arrResponse = array('status' => true, 'title' => $title, 'msg' => $strImg);
      }
      //IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON 
    }
    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    exit();
  }

}
