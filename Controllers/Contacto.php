<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Contacto extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        if (($_SESSION['info_empresa']['fecha_mantenimiento_hasta'] ?? '') > date("Y-m-d H:i:s")) {
            header("Location:" . base_url() . 'enConstruccion');
            exit();
        }
    }

    public function contacto()
    {
        $homeController = new Home();
        $data['header'] = $homeController->data_header('Contacto');
        $data['footer'] = $homeController->data_footer();

        $empresa = $_SESSION['info_empresa'];
        $data['empresa'] = $empresa;

        $data['meta'] = [
            'robots' => 'index, follow, archive',
            'title' => $empresa['nombre_comercial'],
            'description' => substr(strClean(strip_tags($empresa['descripcion'])), 0, 160),
            'keywords' => $empresa['tags'],
            'url' => base_url(),
            'image' => $empresa['url_logoImpreso'],
            'image:type' => 'image/' . pathinfo($empresa['logo_imp'], PATHINFO_EXTENSION),
            'og:type' => 'website'
        ];

        $data["page_css"] = [];
        $data["page_functions_js"] = [];

        $this->views->getView("Contacto", $data);
    }

    public function formContacto()
    {
        $meta = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . getUserIP()));

        $request = $this->model->newContacto(
            'contacto',
            strClean($_POST['txtNombre']),
            strClean($_POST['txtApellido']),
            strClean($_POST['txtTelefono']),
            strClean($_POST['txtEmail']),
            strClean($_POST['mensaje']),
            $_SERVER['REMOTE_ADDR'],
            json_encode($meta, JSON_UNESCAPED_UNICODE),
            $meta['geoplugin_city'],
            $meta['geoplugin_regionName'],
            $meta['geoplugin_countryName'],
            detectar_dispositivo(),
            dispositivoOS(),
            $_SERVER['HTTP_USER_AGENT']
        );

        if ($request) {
            $empresa = $_SESSION['info_empresa'];
            $arrDataEmail = [
                'empresa' => $empresa,
                'nombreUsuario' => strClean($_POST['txtNombre']) . ' ' . strClean($_POST['txtApellido']),
                'email' => strClean($_POST['txtEmail']),
                'asunto' => 'Nuevo mensaje de contacto en ' . $empresa['nombre_comercial'],
            ];

            $bodyMail = getFile("Template/Email/email_contacto", $arrDataEmail);
            set_notificacion('contacto', $request);
            sendEmail($arrDataEmail, $bodyMail);

            $arrResponse = ['status' => true, 'msg' => 'Nos estaremos comunicando lo antes posible'];
        } else {
            $arrResponse = ['status' => false, 'msg' => 'No se pudo guardar el mensaje, intente más tarde'];
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
}