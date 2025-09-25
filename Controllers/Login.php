<?php

declare(strict_types=1);

class Login extends Controllers {

  public function __construct() {
    parent::__construct();
  }

  public function Login() {

    $reset = 0;
    if (isset($_GET['r'])) {
      $reset = $_GET['r'] == 'reset' ? 1 : 0;
    }
    $empresa = $_SESSION['info_empresa'];
    $data["empresa"] = $empresa;

    $data['page_name'] = 'login';
    $data['page_title'] = $data['page_name'];
    $data['logo_desktop'] = $empresa['url_logoMenu'];
    $data['shortcut_icon'] = $empresa['url_shortcutIcon'];
    $data['reset'] = $reset;
    // las funciones de la pagina van de ultimo 
    $data["page_css"] = array();
    $data["page_functions_js"] = array();

    $this->views->getView("Login", $data);
  }

  public function passwordReset() {

    if (count($_GET) > 1) { // la variable get inicia con url y luego el paramtro, si la cuenta es menor a 2, el parametro no esta en la url, si es igual o mayor, esta el seundo paramtro  token
      $token = $_GET['t']; // pasamos el token a una variable
      if ($token != "") {
        $token = strClean($token); //limpiamos el token 

        $request_user = $this->model->buscar_id_x_token($token);
        if ($request_user) {

          $data['idUser'] = $request_user['idpersona'];

          $data["empresa"] = $_SESSION['info_empresa'];

          $data["page_name"] = 'Password Reset';
          $data['page_title'] = 'Password Reset';
          $data['logo_desktop'] = $data["empresa"]['url_logoMenu'];
          $data['shortcut_icon'] = $data["empresa"]['url_shortcutIcon'];

          // las funciones de la pagina van de ultimo 
          $data["page_css"] = array();
          $data["page_functions_js"] = array("functions_login.js");

          $this->views->getView("PasswordReset", $data);
        }
      }
    } else {
      header('location:' . base_url() . 'login');
      exit();
    }exit();
  }

  /* ============================================================================================== */

  public function loginUser() {

    if ($_POST) {//validamos que el metodo POST este habilitado
      //valida que los datos no vengan vacios
      if (empty($_POST['txtEmail']) || empty($_POST['txtPassword'])) {
        $arrResponse = array('status' => false, 'msg' => 'Necesito el Email y Passwor para poderte dar acceso');
      } else {//si los tados no vienen vacios, se limpian de carateres
        $strUsuario = strtolower(strClean($_POST['txtEmail']));
        $strPassword = hash("SHA256", (strClean($_POST['txtPassword'])));
        $mantenerSesionActiva = (isset($_POST['recuerdame'])) && ($_POST['recuerdame'] == 'on') ? 1 : 0;

        // con los caracteres limpios se hace la consulta a la base
        $request_user = $this->model->validarUserLogin($strUsuario, $strPassword);

        if ((isset($request_user['idpersona'])) && ($request_user['status'] == 1)) { // si la consulta viene vacia, quiere decir que: 1- usuario no existe,2 usuario correcto contraseña erronea, 3 usuario borrado,  
          $idPersona = intval($request_user['idpersona']);
          // variables de sesion    
          sessionLogin($this->model, $idPersona);

          //SESSION ACTIVA
          if ($mantenerSesionActiva) {
            $dispositivo = getUserBrowser();
            $browser = $dispositivo['browser'];
            $version = $dispositivo['version'];

            $OS = dispositivoOS();

            $resultado = null;
            if (isset($_COOKIE['id_sesion'])) {
              $idSesion = $_COOKIE['id_sesion'];
              $resultado = $this->model->consultar_session($idSesion, $idPersona, $OS, $browser, $version);
            }
            /*
              ALTER TABLE `sesiones` ADD `version` VARCHAR(20) NOT NULL AFTER `browser`;

             */




            if ($resultado == null) {
              // Genera un ID de sesión único
              $idSesion = strClean(uniqid() . passGenerator());
              // Establece una cookie con el ID de sesión y la fecha de expiración
              $expiracion = time() + (60 * 60 * 24 * 90); // 90 días de duración de la cookie si se mantiene la sesión activa
              setcookie('id_sesion', $idSesion, $expiracion, '/');
              // Inserta los datos de la sesión en la tabla de sesiones en la base de datos
              $fecha = date('Y-m-d H:i:s');
              $estadoSesion = 1; // 1 para sesiones activas, 0 para sesiones inactivas
              //$arrDataSesion = json_encode($_SESSION); // Convierte el array $_SESSION en una cadena JSON para guardarla en la base de datos
//              dep($_SERVER['HTTP_USER_AGENT']);
//              dep($dispositivo);
              //$this->model->insertar_session($idSesion, $fecha, $estadoSesion, $idPersona, $OS, $browser,$version,$userAgent);
            }
          }
          $arrResponse = array('status' => true, 'msg' => 'Ok');
        } elseif (((isset($request_user['idpersona'])) && ($request_user['status'] == 0))) {
          $arrResponse = array('status' => false, 'msg' => 'El Ususario se encuentra inactivo');
        } else {
          $arrResponse = array('status' => false, 'msg' => 'El Ususario o la Contraseña es Incorecto');
        }
      }
    }
    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  /* ============================================================================================== */

  public function forgetPassword() {

    if ($_POST) {//validamos que el metodo POST este habilitado
      if (empty($_POST['txtEmailFP'])) {            //valida que el datos no venga vacio
        $arrResponse = array('status' => false, 'msg' => 'Error de Datos');
      } else {//si los tados no vienen vacios, se limpia el mail de carateres
        $UserMail = strtolower(strClean($_POST['txtEmailFP']));

        // con los caracteres limpios se hace la consulta a la base
        $request_user = $this->model->forgetPass($UserMail); //se consulta si el usuario exite y si su estatus es activo

        if (empty($request_user)) { // si la consulta viene vacia, quiere decir que: 1- usuario no existe,2 usuario borrado,  
          $arrResponse = array('status' => false, 'msg' => 'El Ususario es Incorecto');
        } else {
          $dataUser = $request_user;
          $nombres = $dataUser['nombres'];
          $apellidos = $dataUser['apellidos'];
          $token = $dataUser['token'];
          $status = $dataUser['status'];

          $empresa = $_SESSION['info_empresa'];

          $arrDataEmail = array(// preparamos el array con los datos requeridos
            'empresa' => $empresa, // un array con los datos de la empresa, y configuracion
            'nombreUsuario' => $nombres . ' ' . $apellidos, //nombre del usuario
            'email' => $UserMail, //email del usuario (email destino)
            'asunto' => 'Recuperar cuenta en ' . $empresa['nombre_comercial'],
            //CUERMO DEL MAIL
            'url_recovery' => base_url() . 'login/passwordReset&?t=' . $token,
          );

          $bodyMail = getFile("Template/Email/email_cambioPassword", $arrDataEmail);

          $send_mail = sendEMail($arrDataEmail, $bodyMail);

          if ($send_mail == 1) { //si el elvio del mail es OK envia un arrasy para ejecurar Swet alert 
            $arrResponse = array('status' => true, 'msg' => 'En tu bandeja de entrada encontraras el coreo para restablecer tu contraseña');
          } else {
            $arrResponse = array('status' => false, 'msg' => $send_mail);
          }
        }
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    die();
  }

  /* ============================================================================================== */

  public function actualizarPassword() {
    if (!empty($_POST)) {
      $strPassword = hash("SHA256", ($_POST['txtPassword']));
      $intUsuario = intval($_POST['idUsuario']);

      $request_pass = $this->model->updatePasword($intUsuario, $strPassword);

      if ($request_pass) {
        $arrResponse = array('status' => true, 'msg' => 'Se a actualizado la ocntraseña correctamente, ahora podras ingresar');
      } else {
        $arrResponse = array('status' => false, 'msg' => 'Ocurrio un problema al actualiza el passwor');
      }
    }
    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

}
