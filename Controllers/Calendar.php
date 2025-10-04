<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Calendar extends Controllers
{
    public function __construct()
    {
        if (($_SESSION['info_empresa']['fecha_mantenimiento_hasta'] ?? '') > date("Y-m-d H:i:s")) {
            header("Location:" . base_url() . 'enConstruccion');
            exit();
        }
        if (empty($_SESSION['login'])) {
            $login = new Login();
            $login->login();
            exit();
        }
        parent::__construct();
    }

    public function calendar()
    {
        $empresa = $_SESSION['info_empresa'];

        $data['page_name'] = 'Calendar';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data["page_css"] = ["fullcalendar.css"];
        $data["page_functions_js"] = ["plugins/fullcalendar5.min.js", "plugins/moment-with-locales.min.js", "functions_categorias.js"];

        $this->views->getView("Calendar", $data);
    }
}