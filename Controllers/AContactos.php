<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class AContactos extends Controllers
{
    private int $idModul = 9;

    public function __construct()
    {
        if (empty($_SESSION['login'])) {
            $login = new Login();
            $login->login();
            exit();
        }
        parent::__construct();
    }

    public function aContactos()
    {
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) == 0) {
            header('location:' . base_url() . 'dashboard');
            exit();
        }

        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'Contactos';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data["page_css"] = ["plugins/datatables/css/datatables.min.css"];
        $data["page_functions_js"] = [
            "plugins/jquery/jquery-3.6.0.min.js",
            "plugins/datatables/js/datatables.min.js",
            "js/functions_aContactos.js"
        ];

        $this->views->getView("AContactos", $data);
    }

    public function getContactos()
    {
        $arrData = $this->model->selectContactos();
        $notificacion = new Notificacion();

        for ($i = 0; $i < count($arrData); $i++) {
            $id_not = $notificacion->getIdNotificacionesTipo('contacto', $arrData[$i]['idcontacto']);
            $arrData[$i]['origen'] = $id_not > 0 ? '<span class="badge bg-success font-size-14 me-1">' . $arrData[$i]['origen'] . '</span>' : $arrData[$i]['origen'];

            $ver = '<button class="btn btn-secondary btn-sm btnViewUser" onClick="fntVerContact(' . $arrData[$i]['idcontacto'] . ')" title="Ver contacto" type="button"><i class="fa fa-eye"></i></button>';
            $arrData[$i]['options'] = '<div class="text-center">' . $ver . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function getCto(int $id)
    {
        if ($id > 0) {
            $arrData = $this->model->selectContacto($id);

            $notificacion = new Notificacion();
            $notificacion->updateNotificacionID('contacto', $arrData['idcontacto'], 1);

            $direc = ($arrData['localidad'] ? $arrData['localidad'] . ', ' : '')
                   . ($arrData['ciudad'] ? $arrData['ciudad'] . ', ' : '')
                   . ($arrData['pais'] ?? '');

            $arrData['localidad'] = $direc;
            unset($arrData['ciudad'], $arrData['pais']);

            if (empty($arrData)) {
                $arrResponse = ['status' => false, 'msg' => 'Datos no encontrados'];
            } else {
                $arrResponse = ['status' => true, 'data' => $arrData];
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }
}