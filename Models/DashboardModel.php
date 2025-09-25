<?php

declare(strict_types=1);

class DashboardModel extends Mysql {

  public function __construct() {
    parent::__construct();
  }

  public function countUsersD() {
    return $request = $this->select('SELECT COUNT(idpersona) as total FROM persona WHERE status = 1 AND idpersona > 2 AND rolid != 2')['total'];
  }

  public function countClientesD() {
    return $this->select('SELECT COUNT(idpersona) as total FROM persona WHERE status = 1 AND idpersona > 2 AND rolid = 2')['total'];
  }

  public function countProductosD() {
    return $this->select('SELECT COUNT(idproducto) as total FROM producto WHERE status = 1')['total'];
  }

  public function countPedidosD() {
    return $this->select('SELECT COUNT(idpedido) as total FROM pedido')['total'];
  }

  /* ============================================================================== */

  public function ultimosPedidosD() {
    return $this->select_all('SELECT p.idpedido, CONCAT(pr.nombres," ",pr.apellidos) AS nombre, p.monto, p.status
                 FROM pedido p
                 INNER JOIN persona pr
                 on p.personaid = pr.idpersona
                 ORDER BY p.idpedido  DESC LIMIT 12');
  }

  /* ============================================================================== */

  public function selectPagosMes(int $anio, int $mes) {
    return array('anio' => $anio, 'mes' => mesNumLet()[intval($mes)],
      'tipospago' => $this->select_all("SELECT p.tipopagoid, tp.nombre_tpago, COUNT(p.tipopagoid) as cantidad, SUM(p.monto) as total 
            FROM pedido p INNER JOIN tipopago tp ON p.tipopagoid = tp.idtipopago 
            WHERE MONTH(p.fecha) = {$mes} 
            AND YEAR(p.fecha) = {$anio}  
            GROUP BY p.tipopagoid")
    );
  }

  public function selectVentasAnioMes(string $fecha) {
    return $this->select("SELECT DAY (fecha) as dia, COUNT(idpedido) as cantidad, SUM(monto) as total FROM pedido  
           WHERE DATE(fecha) = '{$fecha}'  
           AND status IN ('Completo','Aprobado')  ");
  }

  public function selectVentasTotalMes(int $anio, int $mes) {
    return $this->select("SELECT   SUM(monto) AS ventas FROM pedido  
            WHERE YEAR(fecha) = '{$anio}'
            AND MONTH(fecha) = '{$mes}'
            AND status IN ('Completo','Aprobado')  
            GROUP BY MONTH(fecha) ");
  }

  /* VISITAS ============================================================================== */

  function obtenerRangoFechasVisitas() {// para el total en widget
    $f_desde = $this->select_column("SELECT `datecreated`  FROM `visitas` WHERE `idvisita` in (SELECT MIN(idvisita) FROM `visitas`)");
    $f_hasta = $this->select_column("SELECT `datecreated`  FROM `visitas` WHERE `idvisita` in (SELECT MAX(idvisita) FROM `visitas`)");
    return array(
      'desde' => date("Y-m-d", strtotime($f_desde)),
      'hasta' => date("Y-m-d", strtotime($f_hasta))
    );
  }

  function obtenerVisitasEnPais() {// para el total en widget
    return $this->select_all_column("SELECT `pais` FROM `visitas` WHERE pais IS NOT NULL GROUP BY pais ORDER BY pais ASC");
  }

  /* ------------------------------------------------------------------------------------------ */

  public function selectCountVisitPorPagRango($fechaInicio, $fechaFin,$pais) {
    $sql_fechas = $fechaInicio === $fechaFin ? "DATE(datecreated) = '{$fechaInicio}'" :
        "DATE(datecreated) >= '{$fechaInicio}' AND datecreated <= '{$fechaFin}'";
    $sql = "SELECT `pagina` , COUNT(`pagina`) AS cantidad FROM visitas 
           WHERE {$sql_fechas}  AND pais = '{$pais}'
            GROUP BY `pagina` ORDER by cantidad DESC ";
    return $this->select_all($sql);
  }

  /* ------------------------------------------------------------------------------------------ */

  public function selectCountRegionPorPagRango($fechaInicio, $fechaFin, $ciudad,$pais) {
    $sql_fechas = $fechaInicio === $fechaFin ? "DATE(datecreated) = '{$fechaInicio}'" :
        "DATE(datecreated) >= '{$fechaInicio}' AND datecreated <= '{$fechaFin}'";
    if ($ciudad) {
      $sql = "SELECT `localidad` , COUNT(`localidad`) AS cantidad FROM visitas 
           WHERE {$sql_fechas} 
            AND ciudad = '{$ciudad}' AND `localidad` IS NOT NULL AND `localidad` !=''
            GROUP BY `localidad` ORDER by cantidad DESC ";
    } else {
      $sql = "SELECT `ciudad` , COUNT(`ciudad`) AS cantidad FROM visitas 
           WHERE {$sql_fechas} AND pais = '{$pais}'
            AND `ciudad` IS NOT NULL AND `ciudad` !=''
            GROUP BY `ciudad` ORDER by cantidad DESC ";
    }

//dep($sql);
    return $this->select_all($sql);
  }

  /* ------------------------------------------------------------------------------------------ */

  function obtenerConteoVisitasEnRango($fechaInicio, $fechaFin) {// para el total en widget
    return $this->select("SELECT COUNT(idvisita) AS conteo FROM visitas 
      WHERE datecreated >= '{$fechaInicio}' AND datecreated <= '{$fechaFin}'")['conteo'];
  }

  function obtenerVisitasEnDia($fecha,$pais) {
    return $this->select("SELECT DAY (datecreated) as dia, COUNT(idvisita) AS total FROM visitas 
            WHERE DATE(datecreated) = '{$fecha}' AND pais = '{$pais}'");
  }

  function obtenerVisitantesEnDia($fecha,$pais) {
    return $this->select("SELECT DAY (datecreated) as dia, COUNT(DISTINCT ip) AS total FROM visitas 
            WHERE DATE(datecreated) = '{$fecha}' AND pais = '{$pais}'");
  }

  /* Dolar Peso ============================================================================== */

  function obtenerDolarPesoEnDia($fecha) {
    return $this->select("SELECT oficial_compra, oficial_venta, blue_compra, blue_venta FROM `divisa` WHERE `idcotizacion` = (
    SELECT MAX(`idcotizacion`) FROM `divisa` WHERE `idcotizacion` in ( 
        SELECT `idcotizacion` FROM `divisa` WHERE DATE(fecha) = '{$fecha}' ))");
  }

  function obtenerMaximoPrecioDolarPesoMes($anio, $mes) {
    return $this->select("SELECT * FROM `divisa` WHERE `idcotizacion` = ( 
      SELECT MAX(`idcotizacion`) FROM `divisa` WHERE YEAR(`fecha`) = '{$anio}' AND MONTH(`fecha`) = '{$mes}' AND `blue_compra` =( 
        SELECT MAX(`blue_compra`) FROM `divisa` WHERE YEAR(`fecha`) = '{$anio}' AND MONTH(`fecha`) = '{$mes}'))");
  }

}
