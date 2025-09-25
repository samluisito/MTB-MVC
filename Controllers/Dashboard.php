<?php

declare(strict_types=1);

class Dashboard extends Controllers {



  function __construct() {

    parent::__construct();

    if (empty($_SESSION['login'])) {
      require_once "Login.php";
      $login = new Login();
      $login->Login();
      exit();         //header('location:' . base_url() . 'login');
    }
    if ($_SESSION['userData']['rolid'] == 2) {
      //echo $_SESSION['userData']['rolid'] == 2; exit();
      header('location:' . base_url() . 'usuarios/perfil');
      exit();
    }
  }

  function Dashboard($params) {


    $empresa = $_SESSION['info_empresa'];

    $data["empresa"] = $empresa;

    $data['page_name'] = 'Dashboard';
    $data['page_title'] = $data['page_name'];
    $data['logo_desktop'] = $empresa['url_logoMenu'];
    $data['shortcut_icon'] = $empresa['url_shortcutIcon'];
   
    include __DIR__ . '/../Controllers/Notificacion.php';
    $notificacion = new Notificacion();
    $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
    
    $data['visit_rango_f'] = $this->model->obtenerRangoFechasVisitas();
    $data['visit_pais'] = $this->model->obtenerVisitasEnPais();
    $data['wVisitas'] = $this->model->obtenerConteoVisitasEnRango(date("Y-m-01 00:00:00"), date("Y-m-d H:i:s"));
    //$data['wUsuarios'] = $this->model->countUsers();
    $data['wClientes'] = $this->model->countClientesD();
    $data['wProductos'] = $this->model->countProductosD();
    $data['wPedidos'] = $this->model->countPedidosD();
    $data['tbPedidos'] = $this->model->ultimosPedidosD();

    // las funciones de la pagina van de ultimo 
    $data["page_css"] = array();
    $data["page_functions_js"] = array(
      "vadmin/libs/apexcharts/apexcharts.min.js",
      "js/functions_dashboard.js"); //"plugins/datatables.min.js",

    $this->views->getView("Dashboard", $data);
  }

  /* ================================================================================================= */
  /* Cobros realizadoz agrupados por metodo de pago -------------------------------------------------- */

  function getPagosAnioMes($param) {
    $arrData = explode(",", $param);
    $anio = intval($arrData[0]);
    $mes = intval($arrData[1]);
    $arrData = $this->model->selectPagosMes($anio, $mes);
    exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
  }

  /* Ventas realizadas durante el mes/año ----------------------------------------------------------- */

  function getVentasAnioMes($param) {
    $arrData = explode(",", $param);
    $anio = intval($arrData[0]);
    $mes = intval($arrData[1]);

    $mesCon = date_format(date_create($anio . '-' . $mes), "Y-m");
    //Mes consultado
    $dias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio); // contamos la cantidad de dias que tiene un mes en el año seleccionado
    $total_ventas_mes = 0;
    $total_ventas_dia = array();

    for ($dia = 1; $dia < $dias + 1; $dia++) {
      $fecha = $mesCon . "-" . $dia;
      $ventadia = $this->model->selectVentasAnioMes($fecha);
      $ventadia['dia'] = $dia;
      $ventadia['total'] = intval($ventadia['total'] ?? 0);
      $total_ventas_mes += $ventadia['total'];
      array_push($total_ventas_dia, $ventadia);
    }
    //Mes anterior
    $fechaPrev = date("Y-m", strtotime($mesCon . "- 1 month"));
    $mesPrev = intval(date("m", strtotime($fechaPrev)));
    $diasPrev = cal_days_in_month(// contamos la cantidad de dias que tiene un mes en el año seleccionado
        CAL_GREGORIAN, (int) $mesPrev, (int) date("Y", strtotime($fechaPrev)));
    $total_ventas_mes_prev = 0;
    $total_ventas_dia_prev = array();

    for ($dia = 1; $dia < $diasPrev + 1; $dia++) {
      $ventadia = $this->model->selectVentasAnioMes($fechaPrev . "-" . $dia);
      $ventadia['dia'] = $dia;
      $ventadia['total'] = intval($ventadia['total'] ?? 0);
      $total_ventas_mes_prev += $ventadia['total'];
      array_push($total_ventas_dia_prev, $ventadia);
    }

    exit(json_encode(array('anio' => $anio,
      'mes' => mesNumLet()[intval($mes)], 'total_v' => $total_ventas_mes, 'ventas' => $total_ventas_dia,
      'mes_prev' => mesNumLet()[$mesPrev], 'total_v_prev' => $total_ventas_mes_prev, 'ventas_prev' => $total_ventas_dia_prev)
            , JSON_UNESCAPED_UNICODE));
  }

  /* ventas realizadas en el mes/año ---------------------------------------------------------------- */

  function getVentasMensuales($param) {
    $anio = intval($param);
    $ventas_anuales = array();
    for ($mes = 1; $mes < 13; $mes++) {
      $datames = $this->model->selectVentasTotalMes($anio, $mes);
      $arrData = array('mes' => mesNumLet()[$mes], 't_ventas' => ''); //'anio' => $anio, 'n_mes' => $mes,
      $arrData['t_ventas'] = empty($datames) ? 0 : intval($datames['ventas']);
      array_push($ventas_anuales, $arrData);
    }
    exit(json_encode($ventas_anuales, JSON_UNESCAPED_UNICODE));
  }

  /* Visitas por pagina  ---------------------------------------------------------------------------- */

  function getVisitPorPag($param) {
    $pais = (isset($_GET['pais']) && $_GET['pais'] != '') ? strClean($_GET['pais']) : 'Argentina';

    $data = $param ? $param : date("Y") . ',' . date("m");
    $arrData = explode(",", $data);
    if ($arrData[0] == 'm') {
      $anio = intval($arrData[1]);
      $mes = intval($arrData[2]);
      $fechaInicio = date_format(date_create($anio . '-' . $mes), "Y-m-d"); // creamos un objeto fecha mes y año con los datos recibidos
      $fechaFin = date("Y-m-t", strtotime($fechaInicio));
    } elseif ($arrData[0] == 's') {
      $anio = intval($arrData[1]); //convertimos en numero el valor pasado
      $semana = ($arrData[2]); //convertimos en numero el valor pasado
      $fechaInicio = date_format(date_create($anio . '-' . $semana), "Y-m-d"); // creamos un objeto fecha mes y año con los datos recibidos
      $fechaFin = date("Y-m-d", strtotime($fechaInicio . "+ 6 days"));
    } elseif ($arrData[0] == 'd') {

      $anio = intval($arrData[1]); //convertimos en numero el valor pasado
      $mes = ($arrData[2]); //convertimos en numero el valor pasado
      $dia = ($arrData[3]); //convertimos en numero el valor pasado

      $fechaInicio = date_format(date_create("$anio-$mes-$dia"), "Y-m-d"); // creamos un objeto fecha mes y año con los datos recibidos
      $fechaFin = $fechaInicio;
    }

    $response = $this->model->selectCountVisitPorPagRango($fechaInicio, $fechaFin, $pais);

    echo(json_encode($response, JSON_UNESCAPED_UNICODE));
    /*
      $pag = array();
      $cant = array();

      foreach ($paginas as $pagina) {
      array_push($pag, $pagina['pagina']);
      array_push($cant, ($pagina['cantidad']));
      }

      echo(json_encode($response = array('pagina' => $pag, 'cantidad' => $cant))); */
  }

  /* Visitas por pagina por region ------------------------------------------------------------------ */

  function getVisitPorRegion($param) {
    $pais = (isset($_GET['pais']) && $_GET['pais'] != '') ? strClean($_GET['pais']) : 'Argentina';

    $ciudad = (isset($_GET['ciudad']) && $_GET['ciudad'] != '') ? $_GET['ciudad'] : null;
    $data = $param ? $param : date("Y") . ',' . date("m");
    $arrData = explode(",", $data);
    if ($arrData[0] == 'm') {
      $anio = intval($arrData[1]);
      $mes = intval($arrData[2]);
      $fechaInicio = date_format(date_create($anio . '-' . $mes), "Y-m-d"); // creamos un objeto fecha mes y año con los datos recibidos
      $fechaFin = date("Y-m-t", strtotime($fechaInicio));
    } elseif ($arrData[0] == 's') {
      $anio = intval($arrData[1]); //convertimos en numero el valor pasado
      $semana = ($arrData[2]); //convertimos en numero el valor pasado
      $fechaInicio = date_format(date_create($anio . '-' . $semana), "Y-m-d"); // creamos un objeto fecha mes y año con los datos recibidos
      $fechaFin = date("Y-m-d", strtotime($fechaInicio . "+ 6 days"));
    } elseif ($arrData[0] == 'd') {

      $anio = intval($arrData[1]); //convertimos en numero el valor pasado
      $mes = ($arrData[2]); //convertimos en numero el valor pasado
      $dia = ($arrData[3]); //convertimos en numero el valor pasado

      $fechaInicio = date_format(date_create("$anio-$mes-$dia"), "Y-m-d"); // creamos un objeto fecha mes y año con los datos recibidos
      $fechaFin = $fechaInicio;
    }

    $response = $this->model->selectCountRegionPorPagRango($fechaInicio, $fechaFin, $ciudad, $pais);

    echo(json_encode($response, JSON_UNESCAPED_UNICODE));
    /*
      $pag = array();
      $cant = array();

      foreach ($paginas as $pagina) {
      array_push($pag, $pagina['pagina']);
      array_push($cant, ($pagina['cantidad']));
      }

      echo(json_encode($response = array('pagina' => $pag, 'cantidad' => $cant))); */
  }

  /* Visitas y visitantes mes/año ------------------------------------------------------------------- */

  function getVisitAnioMes($param) {
    $pais = (isset($_GET['pais']) && $_GET['pais'] != '') ? strClean($_GET['pais']) : 'Argentina';
    $data = $param ? $param : date("Y") . ',' . date("m");
    $arrData = explode(",", $data);
    if ($arrData[0] == 'm') {
      $anio = intval($arrData[1]); //convertimos en numero el valor pasado
      $mes = intval($arrData[2]); //convertimos en numero el valor pasado
      $mesCon = date_format(date_create($anio . '-' . $mes), "Y-m"); // creamos un objeto fecha mes y año con los datos recibidos
      $dias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio); // contamos la cantidad de dias que tiene un mes en el año seleccionado

      $total_visitas_mes = 0;
      $total_visitas_dia = array();

      $total_visitantes_mes = 0;
      $total_visitasntes_dia = array();

      for ($dia = 1; $dia < $dias + 1; $dia++) {
        $fecha = $mesCon . "-" . $dia;

        $visitadia = $this->model->obtenerVisitasEnDia($fecha, $pais);

        $visitadia['dia'] = $dia;
        $visitadia['total'] = $visitadia['total'] ?? 0;
        $total_visitas_mes += $visitadia['total'];
        array_push($total_visitas_dia, $visitadia);

        $visitantedia = $this->model->obtenerVisitantesEnDia($fecha, $pais);
        $visitantedia['dia'] = $dia;
        $visitantedia['total'] = $visitantedia['total'] ?? 0;
        $total_visitantes_mes += $visitantedia['total'];
        array_push($total_visitasntes_dia, $visitantedia);
        $arrDataReturn = array('anio' => $anio, 'mes' => mesNumLet()[$mes],
          'total_pagVisitadas' => $total_visitas_mes, 'pagVisitadas' => $total_visitas_dia,
          'total_visitantes' => $total_visitantes_mes, 'visitantes' => $total_visitasntes_dia);
      }
    } elseif ($arrData[0] == 's') {
      $anio = intval($arrData[1]); //convertimos en numero el valor pasado
      $semana = ($arrData[2]); //convertimos en numero el valor pasado
      $fecha_sem = date_format(date_create($anio . '-' . $semana), "Y-m-d"); // creamos un objeto fecha mes y año con los datos recibidos
      $mes = intval(date_format(date_create($fecha_sem), "m"));

      //$dias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio); // contamos la cantidad de dias que tiene un mes en el año seleccionado

      $total_visitas_semana = 0;
      $total_visitas_dia = array();

      $total_visitantes_semana = 0;
      $total_visitasntes_dia = array();

      for ($dia = 0; $dia < 7; $dia++) {
        $fecha_dia = date("Y-m-d", strtotime($fecha_sem . "+ $dia days"));

        $visitadia = $this->model->obtenerVisitasEnDia($fecha_dia, $pais);

        $visitadia['dia'] = date("D-j", strtotime($fecha_sem . "+ $dia days"));
        $visitadia['total'] = $visitadia['total'] ?? 0;
        $total_visitas_semana += $visitadia['total'];
        array_push($total_visitas_dia, $visitadia);

        $visitantedia = $this->model->obtenerVisitantesEnDia($fecha_dia, $pais);
        $visitantedia['dia'] = intval(date_format(date_create($fecha_sem), "d")) + $dia;
        $visitantedia['total'] = $visitantedia['total'] ?? 0;
        $total_visitantes_semana += $visitantedia['total'];
        array_push($total_visitasntes_dia, $visitantedia);

        $arrDataReturn = array(
          'anio' => $anio,
          'mes' => mesNumLet()[$mes],
          'total_pagVisitadas' => $total_visitas_semana, 'pagVisitadas' => $total_visitas_dia,
          'total_visitantes' => $total_visitantes_semana, 'visitantes' => $total_visitasntes_dia);
      }
    }

    exit(json_encode($arrDataReturn, JSON_UNESCAPED_UNICODE));
  }

  /* Dolar =============================================================================================== */

  function getDolarPesoAnioMes($param) {

    $data = $param ? $param : date("Y") . ',' . date("m");
    $arrData = explode(",", $data);

    $anio = intval($arrData[0]);    //convertimos en numero el valor pasado
    $mes = intval($arrData[1]); //convertimos en numero el valor pasado
    $mesCon = date_format(date_create($anio . '-' . $mes), "Y-m"); // creamos un objeto fecha mes y año con los datos recibidos
    $dias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio); // contamos la cantidad de dias que tiene un mes en el año seleccionado

    $cotizaciones_por_dia = array();

    for ($dia = 1; $dia < $dias + 1; $dia++) {
      $fecha = $mesCon . "-" . $dia;
      $dolar_al_dia = $this->model->obtenerDolarPesoEnDia($fecha);

      $dolar_al_dia['dia'] = $dia;
      $dolar_al_dia['oficial_compra'] = $dolar_al_dia['oficial_compra'] ?? 0;
      $dolar_al_dia['oficial_venta'] = $dolar_al_dia['oficial_venta'] ?? 0;
      $dolar_al_dia['blue_compra'] = $dolar_al_dia['blue_compra'] ?? 0;
      $dolar_al_dia['blue_venta'] = $dolar_al_dia['blue_venta'] ?? 0;

      array_push($cotizaciones_por_dia, $dolar_al_dia);
    }

//    $oficial_compra_por_dia = array();
//    $oficial_venta_por_dia = array();
//    $blue_compra_por_dia = array();
//    $blue_venta_por_dia = array();
//
//    for ($dia = 1; $dia < $dias + 1; $dia++) {
//      $fecha = $mesCon . "-" . $dia;
//      $dolar_al_dia = $this->model->obtenerDolarPesoEnDia($fecha);
//      $dolar_al_dia['dia'] = $dia;
//      array_push($oficial_compra_por_dia, $dolar_al_dia['oficial_compra'] ?? 0);
//      array_push($oficial_venta_por_dia, $dolar_al_dia['oficial_venta'] ?? 0);
//      array_push($blue_compra_por_dia, $dolar_al_dia['blue_compra'] ?? 0);
//      array_push($blue_venta_por_dia, $dolar_al_dia['blue_venta'] ?? 0);
//    }
    $maximo_dolar_periodo = $this->model->obtenerMaximoPrecioDolarPesoMes($anio, $mes);

    exit(json_encode(array(
      'anio' => $anio,
      'mes' => mesNumLet()[$mes],
      'dolar_maximo_periodo' => $maximo_dolar_periodo,
      'dolar_por_dia' => $cotizaciones_por_dia), JSON_UNESCAPED_UNICODE));
  }

}
