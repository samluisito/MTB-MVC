<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Dashboard extends Controllers
{
    public function __construct()
    {
        parent::__construct();

        if (empty($_SESSION['login'])) {
            $login = new Login();
            $login->login();
            exit();
        }
        if (($_SESSION['userData']['rolid'] ?? 0) == 2) {
            header('location:' . base_url() . 'usuarios/perfil');
            exit();
        }
    }

    public function dashboard($params)
    {
        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'Dashboard';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data['visit_rango_f'] = $this->model->obtenerRangoFechasVisitas();
        $data['visit_pais'] = $this->model->obtenerVisitasEnPais();
        $data['wVisitas'] = $this->model->obtenerConteoVisitasEnRango(date("Y-m-01 00:00:00"), date("Y-m-d H:i:s"));
        $data['wClientes'] = $this->model->countClientesD();
        $data['wProductos'] = $this->model->countProductosD();
        $data['wPedidos'] = $this->model->countPedidosD();
        $data['tbPedidos'] = $this->model->ultimosPedidosD();

        $data["page_css"] = [];
        $data["page_functions_js"] = [
            "vadmin/libs/apexcharts/apexcharts.min.js",
            "js/functions_dashboard.js"
        ];

        $this->views->getView("Dashboard", $data);
    }

    public function getPagosAnioMes($param)
    {
        $arrData = explode(",", $param);
        $anio = intval($arrData[0]);
        $mes = intval($arrData[1]);
        $arrData = $this->model->selectPagosMes($anio, $mes);
        exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    public function getVentasAnioMes($param)
    {
        $arrData = explode(",", $param);
        $anio = intval($arrData[0]);
        $mes = intval($arrData[1]);
        $mesCon = date_format(date_create($anio . '-' . $mes), "Y-m");
        $dias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

        $total_ventas_mes = 0;
        $total_ventas_dia = [];
        for ($dia = 1; $dia <= $dias; $dia++) {
            $fecha = $mesCon . "-" . str_pad((string)$dia, 2, '0', STR_PAD_LEFT);
            $ventadia = $this->model->selectVentasAnioMes($fecha);
            $ventadia['dia'] = $dia;
            $ventadia['total'] = intval($ventadia['total'] ?? 0);
            $total_ventas_mes += $ventadia['total'];
            $total_ventas_dia[] = $ventadia;
        }

        $fechaPrev = date("Y-m", strtotime($mesCon . "- 1 month"));
        $mesPrev = intval(date("m", strtotime($fechaPrev)));
        $anioPrev = intval(date("Y", strtotime($fechaPrev)));
        $diasPrev = cal_days_in_month(CAL_GREGORIAN, $mesPrev, $anioPrev);

        $total_ventas_mes_prev = 0;
        $total_ventas_dia_prev = [];
        for ($dia = 1; $dia <= $diasPrev; $dia++) {
            $fecha = $fechaPrev . "-" . str_pad((string)$dia, 2, '0', STR_PAD_LEFT);
            $ventadia = $this->model->selectVentasAnioMes($fecha);
            $ventadia['dia'] = $dia;
            $ventadia['total'] = intval($ventadia['total'] ?? 0);
            $total_ventas_mes_prev += $ventadia['total'];
            $total_ventas_dia_prev[] = $ventadia;
        }

        exit(json_encode([
            'anio' => $anio,
            'mes' => mesNumLet()[intval($mes)],
            'total_v' => $total_ventas_mes,
            'ventas' => $total_ventas_dia,
            'mes_prev' => mesNumLet()[$mesPrev],
            'total_v_prev' => $total_ventas_mes_prev,
            'ventas_prev' => $total_ventas_dia_prev
        ], JSON_UNESCAPED_UNICODE));
    }

    public function getVentasMensuales($param)
    {
        $anio = intval($param);
        $ventas_anuales = [];
        for ($mes = 1; $mes <= 12; $mes++) {
            $datames = $this->model->selectVentasTotalMes($anio, $mes);
            $arrData = ['mes' => mesNumLet()[$mes], 't_ventas' => 0];
            $arrData['t_ventas'] = empty($datames) ? 0 : intval($datames['ventas']);
            $ventas_anuales[] = $arrData;
        }
        exit(json_encode($ventas_anuales, JSON_UNESCAPED_UNICODE));
    }
}