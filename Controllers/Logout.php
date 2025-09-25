<?php

declare(strict_types=1);

class Logout extends Controllers {

  public function __construct() {
    parent::__construct();
  }

  public function Logout() {
    // Destruir todas las variables de sesión.
    $_SESSION = array();
// Si se desea destruir la sesión completamente, borre también la cookie de sesión.
// Nota: ¡Esto destruirá la sesión, y no la información de la sesión!
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
      );
    }
    if (isset($_COOKIE['id_sesion'])) {

      $idSesion = strClean($_COOKIE['id_sesion']);
//      $idPersona = $_SESSION['idUser'];
//      $estadoSesion = 0; // 1 para sesiones activas, 0 para sesiones inactivas
      setcookie('id_sesion', '', time() - 3600, '/'); //borramos la sesion del lado cliente
      // Actualiza el estado de la sesión en la base de datos a inactivo
      $this->model->cerrar_session($idSesion);
    }
// Finalmente, destruir la sesión.
    session_unset();
    session_destroy();

    $this->model->getConexion()->close();
    header('location:' . base_url());
  }

}
