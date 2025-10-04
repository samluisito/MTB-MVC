<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Logout extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function logout()
    {
        // Destruir todas las variables de sesión.
        $_SESSION = [];

        // Borrar la cookie de sesión del navegador.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Actualizar el estado de la sesión "recuérdame" en la base de datos si existe.
        if (isset($_COOKIE['id_sesion'])) {
            $idSesion = strClean($_COOKIE['id_sesion']);
            setcookie('id_sesion', '', time() - 3600, '/'); // Borrar la cookie del cliente.
            $this->model->cerrar_session($idSesion);
        }

        // Finalmente, destruir la sesión del servidor.
        session_unset();
        session_destroy();

        // La conexión a la base de datos se cierra globalmente en index.php,
        // por lo que no es necesario cerrarla aquí.
        header('location:' . base_url());
        exit();
    }
}