<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class DashboardModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function countUsersD(): int
    {
        return $this->select('SELECT COUNT(idpersona) as total FROM persona WHERE status = 1 AND idpersona > 2 AND rolid != 2')['total'] ?? 0;
    }

    public function countClientesD(): int
    {
        return $this->select('SELECT COUNT(idpersona) as total FROM persona WHERE status = 1 AND idpersona > 2 AND rolid = 2')['total'] ?? 0;
    }

    public function countProductosD(): int
    {
        return $this->select('SELECT COUNT(idproducto) as total FROM producto WHERE status = 1')['total'] ?? 0;
    }

    public function countPedidosD(): int
    {
        return $this->select('SELECT COUNT(idpedido) as total FROM pedido')['total'] ?? 0;
    }

    public function ultimosPedidosD(): array
    {
        return $this->select_all('SELECT p.idpedido, CONCAT(pr.nombres," ",pr.apellidos) AS nombre, p.monto, p.status
                 FROM pedido p
                 INNER JOIN persona pr ON p.personaid = pr.idpersona
                 ORDER BY p.idpedido DESC LIMIT 12');
    }

    public function selectPagosMes(int $anio, int $mes): array
    {
        $sql = "SELECT p.tipopagoid, tp.nombre_tpago, COUNT(p.tipopagoid) as cantidad, SUM(p.monto) as total
                FROM pedido p INNER JOIN tipopago tp ON p.tipopagoid = tp.idtipopago
                WHERE MONTH(p.fecha) = ? AND YEAR(p.fecha) = ?
                GROUP BY p.tipopagoid";
        return [
            'anio' => $anio,
            'mes' => mesNumLet()[intval($mes)],
            'tipospago' => $this->select_all($sql, [$mes, $anio])
        ];
    }

    public function selectVentasAnioMes(string $fecha): ?array
    {
        $sql = "SELECT DAY(fecha) as dia, COUNT(idpedido) as cantidad, SUM(monto) as total
                FROM pedido
                WHERE DATE(fecha) = ? AND status IN ('Completo','Aprobado')";
        return $this->select($sql, [$fecha]);
    }

    public function selectVentasTotalMes(int $anio, int $mes): ?array
    {
        $sql = "SELECT SUM(monto) AS ventas
                FROM pedido
                WHERE YEAR(fecha) = ? AND MONTH(fecha) = ? AND status IN ('Completo','Aprobado')
                GROUP BY MONTH(fecha)";
        return $this->select($sql, [$anio, $mes]);
    }

    public function obtenerRangoFechasVisitas(): array
    {
        $f_desde = $this->select_column("SELECT `datecreated` FROM `visitas` ORDER BY idvisita ASC LIMIT 1");
        $f_hasta = $this->select_column("SELECT `datecreated` FROM `visitas` ORDER BY idvisita DESC LIMIT 1");
        return [
            'desde' => $f_desde ? date("Y-m-d", strtotime($f_desde)) : date('Y-m-d'),
            'hasta' => $f_hasta ? date("Y-m-d", strtotime($f_hasta)) : date('Y-m-d')
        ];
    }

    public function obtenerVisitasEnPais(): array
    {
        return $this->select_all_column("SELECT `pais` FROM `visitas` WHERE pais IS NOT NULL GROUP BY pais ORDER BY pais ASC");
    }

    public function obtenerConteoVisitasEnRango(string $fechaInicio, string $fechaFin): int
    {
        return $this->select("SELECT COUNT(idvisita) AS conteo FROM visitas WHERE datecreated >= ? AND datecreated <= ?", [$fechaInicio, $fechaFin])['conteo'] ?? 0;
    }

    public function obtenerVisitasEnDia(string $fecha, string $pais): ?array
    {
        return $this->select("SELECT DAY(datecreated) as dia, COUNT(idvisita) AS total FROM visitas WHERE DATE(datecreated) = ? AND pais = ?", [$fecha, $pais]);
    }

    public function obtenerVisitantesEnDia(string $fecha, string $pais): ?array
    {
        return $this->select("SELECT DAY(datecreated) as dia, COUNT(DISTINCT ip) AS total FROM visitas WHERE DATE(datecreated) = ? AND pais = ?", [$fecha, $pais]);
    }
}