<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;
use App\Models\LoginModel;

class Registro extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function registro()
    {
        $homeController = new Home();
        $data['header'] = $homeController->data_header('Registro');
        $data['footer'] = $homeController->data_footer();

        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'Registro';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $meta = $empresa;
        $data['meta'] = [
            'robots' => 'noindex, nofollow, noarchive',
            'title' => $meta['nombre_comercial'],
            'description' => 'Suscríbete',
            'keywords' => $meta['tags'],
            'url' => base_url(),
            'image' => $meta['url_logoImpreso'],
            'image:type' => 'image/' . pathinfo($meta['logo_imp'], PATHINFO_EXTENSION),
            'og:type' => 'website'
        ];

        $data["page_css"] = ["wizardBootstrap.min.css"];
        $data["page_functions_js"] = ["wizardBootstrap.js"];

        $this->views->getView("Registro", $data);
    }

    public function regNvoUsuario()
    {
        if (!$_POST) {
            exit();
        }

        if (
            empty($_POST['txtNombre']) || empty($_POST['txtApellido']) ||
            empty($_POST['txtEmailReg']) || empty($_POST['txtTelefono']) ||
            empty($_POST['txtPassword']) || ($_POST['txtPassword'] != $_POST['txtRePassword'])
        ) {
            $arrResponse = ["status" => false, "msg" => 'Revisa el formulario, faltan datos o están incorrectos'];
        } else {
            $strNombre = ucwords(strClean($_POST['txtNombre']));
            $strApellido = ucwords(strClean($_POST['txtApellido']));
            $strSexo = strClean($_POST['sexo']);
            $intTelefono = intval(strClean($_POST['txtTelefono']));
            $strEmail = strtolower(strClean($_POST['txtEmailReg']));
            $strPass = strClean($_POST['txtPassword']);
            $strPasswordEncript = hash("SHA256", $strPass);

            $meta = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR']));

            $request_user = $this->model->insertCliente(
                $strNombre,
                $strApellido,
                $intTelefono,
                $strEmail,
                $strSexo,
                $_POST['Direccion'] ?? null,
                $meta['geoplugin_city'] ?? null,
                $meta['geoplugin_regionName'] ?? null,
                $meta['geoplugin_countryName'] ?? null,
                $strPasswordEncript,
                2 // Rol Cliente
            );

            if ($request_user > 0 && $request_user !== 'exist') {
                $login = new LoginModel();
                sessionLogin($login, $request_user); // Using global helper

                $empresa = $_SESSION['info_empresa'];
                $arrDataEmail = [
                    'empresa' => $empresa,
                    'nombreUsuario' => "$strNombre $strApellido",
                    'email' => $strEmail,
                    'asunto' => 'Bienvenido a ' . $empresa['nombre_comercial'],
                    'password' => $strPass,
                ];
                $bodyMail = getFile("Template/Email/email_bienvenida", $arrDataEmail);
                sendEmail($arrDataEmail, $bodyMail);
                $arrResponse = ['status' => true, 'msg' => 'Te hemos enviado la contraseña de acceso a tu email.'];
            } elseif ($request_user === 'exist') {
                $arrResponse = ['status' => false, 'msg' => '!ATENCIÓN! Este Email ya se encuentra registrado.'];
            } else {
                $arrResponse = ['status' => false, 'msg' => 'No se pudo guardar el usuario, intente más tarde.'];
            }
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function validarMail()
    {
        $mail = strClean($_POST['mail']);
        $resp = filter_var($mail, FILTER_VALIDATE_EMAIL) ? $this->model->selectMail($mail) : 'invalid_format';
        exit(json_encode($resp, JSON_UNESCAPED_UNICODE));
    }
}