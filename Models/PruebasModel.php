<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class PruebasModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectTransaccionId(string $id): ?array
    {
        return $this->select("SELECT idpedido FROM pedido WHERE transaccionid = ?", [$id]);
    }

    public function selectTiposPagosT(): array
    {
        return $this->select_all('SELECT * FROM tipopago WHERE status = 1');
    }

    public function selectTiposPagoDetallesT(int $tipopagoid): array
    {
        $sql = "SELECT * FROM tipopago_detalle WHERE tipopagoid = ?";
        return $this->select_all($sql, [$tipopagoid]);
    }

    public function idTipoPago(string $tipopago): ?int
    {
        $result = $this->select("SELECT idtipopago FROM tipopago WHERE tipopago = ?", [$tipopago]);
        return $result['idtipopago'] ?? null;
    }
}