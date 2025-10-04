<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Login extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
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

        $data["page_css"] = [];
        $data["page_functions_js"] = [];

        $this->views->getView("Login", $data);
    }

    public function passwordReset()
    {
        if (count($_GET) > 1) {
            $token = $_GET['t'];
            if ($token != "") {
                $token = strClean($token);
                $request_user = $this->model->buscar_id_x_token($token);
                if ($request_user) {
                    $data['idUser'] = $request_user['idpersona'];
                    $data["empresa"] = $_SESSION['info_empresa'];
                    $data["page_name"] = 'Password Reset';
                    $data['page_title'] = 'Password Reset';
                    $data['logo_desktop'] = $data["empresa"]['url_logoMenu'];
                    $data['shortcut_icon'] = $data["empresa"]['url_shortcutIcon'];
                    $data["page_css"] = [];
                    $data["page_functions_js"] = ["functions_login.js"];
                    $this->views->getView("PasswordReset", $data);
                }
            }
        } else {
            header('location:' . base_url() . 'login');
            exit();
        }
        exit();
    }

    public function loginUser()
    {
        if ($_POST) {
            if (empty($_POST['txtEmail']) || empty($_POST['txtPassword'])) {
                $arrResponse = array('status' => false, 'msg' => 'Necesito el Email y Passwor para poderte dar acceso');
            } else {
                $strUsuario = strtolower(strClean($_POST['txtEmail']));
                $strPassword = hash("SHA256", (strClean($_POST['txtPassword'])));
                $mantenerSesionActiva = (isset($_POST['recuerdame'])) && ($_POST['recuerdame'] == 'on') ? 1 : 0;

                $request_user = $this->model->validarUserLogin($strUsuario, $strPassword);

                if ((isset($request_user['idpersona'])) && ($request_user['status'] == 1)) {
                    $idPersona = intval($request_user['idpersona']);
                    sessionLogin($this->model, $idPersona);

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
                        if ($resultado == null) {
                            $idSesion = strClean(uniqid() . passGenerator());
                            $expiracion = time() + (60 * 60 * 24 * 90);
                            setcookie('id_sesion', $idSesion, $expiracion, '/');
                        }
                    }
                    $arrResponse = array('status' => true, 'msg' => 'Ok');
                } elseif (((isset($request_user['idpersona'])) && ($request_user['status'] == 0))) {
                    $arrResponse = array('status' => false, 'msg' => 'El Ususario se encuentra inactivo');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'El Ususario o la Contraseña es Incorecto');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    public function forgetPassword()
    {
        if ($_POST) {
            if (empty($_POST['txtEmailFP'])) {
                $arrResponse = array('status' => false, 'msg' => 'Error de Datos');
            } else {
                $UserMail = strtolower(strClean($_POST['txtEmailFP']));
                $request_user = $this->model->forgetPass($UserMail);

                if (empty($request_user)) {
                    $arrResponse = array('status' => false, 'msg' => 'El Ususario es Incorecto');
                } else {
                    $dataUser = $request_user;
                    $empresa = $_SESSION['info_empresa'];
                    $arrDataEmail = [
                        'empresa' => $empresa,
                        'nombreUsuario' => $dataUser['nombres'] . ' ' . $dataUser['apellidos'],
                        'email' => $UserMail,
                        'asunto' => 'Recuperar cuenta en ' . $empresa['nombre_comercial'],
                        'url_recovery' => base_url() . 'login/passwordReset&?t=' . $dataUser['token'],
                    ];

                    $bodyMail = getFile("Template/Email/email_cambioPassword", $arrDataEmail);
                    $send_mail = sendEmail($arrDataEmail, $bodyMail);

                    if ($send_mail == 1) {
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

    public function actualizarPassword()
    {
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