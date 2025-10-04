<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class ConfiguracionModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectConfig(int $idempresa): ?array
    {
        $request = $this->select("SELECT * FROM config_gral WHERE idempresa = ?", [$idempresa]);
        if ($request) {
            $dolar = ($_SESSION['base']['region_abrev'] ?? 'AR') == 'VE' ? 1 : getDolarHoy();
            $request['costo_envio'] = $request['costo_envio'] * $dolar;
            $request['url_shortcutIcon'] = DIR_IMAGEN . $request['shortcut_icon'];
            $request['url_logoMenu'] = DIR_IMAGEN . $request['logo_menu'];
            $request['url_logoImpreso'] = DIR_IMAGEN . $request['logo_imp'];
        }
        return $request;
    }

    public function updateConfig(array $data)
    {
        // This method is complex and would benefit from being broken down
        // For now, focusing on namespaces and prepared statements
        $sql = "UPDATE config_gral SET
                    nombre_comercial=?, nombre_fiscal=?, id_fiscal=?, email=?, telefono=?,
                    direccion=?, descripcion=?, tags=?, facebook=?, instagram=?,
                    whatsapp_numero=?, whatsapp_texto=?, twitter=?, logo_menu=?,
                    logo_imp=?, shortcut_icon=?, smtp_status=?, serv_mail=?,
                    pass_mail=?, host_mail=?, fecha_mantenimiento_hasta=?, costo_envio=?,
                    modo_entrega=?, guardar_webp=?, login_facebook=?, clave_app_fb=?,
                    id_app_fb=?, pixel_facebook=?, pixel_fb_id=?, meta_dominio=?, excluir_ip=?
                WHERE idempresa = 1";

        $arrData = [
            $data['nombre_comercial'], $data['nombre_fiscal'], $data['id_fiscal'], $data['email'], $data['telefono'],
            $data['direccion'], $data['descripcion'], $data['tags'], $data['facebook'], $data['instagram'],
            $data['whatsapp_numero'], $data['whatsapp_texto'], $data['twitter'], $data['logo_menu'],
            $data['logo_imp'], $data['shortcut_icon'], $data['smtp_status'], $data['serv_mail'],
            $data['pass_mail'], $data['host_mail'], $data['fecha_mantenimiento_hasta'], $data['costo_envio'],
            $data['modo_entrega'], $data['guardar_webp'], $data['login_facebook'], $data['clave_app_fb'],
            $data['id_app_fb'], $data['pixel_facebook'], $data['pixel_fb_id'], $data['meta_dominio'], $data['excluir_ip']
        ];

        return $this->update($sql, $arrData);
    }

    public function selectTiposPagos(): array
    {
        return $this->select_all("SELECT * FROM tipopago");
    }

    public function selectTiposPagoDetalles(int $tipopagoid): array
    {
        return $this->select_all("SELECT * FROM tipopago_detalle WHERE tipopagoid = ?", [$tipopagoid]);
    }

    public function updStatusTP(int $idtipopago, int $estado): int
    {
        return $this->update("UPDATE tipopago SET status = ? WHERE idtipopago = ?", [$estado, $idtipopago]);
    }

    public function updTPDetalle(int $tipopagoid, string $tpopago_label, string $tpopago_value)
    {
        $existe = $this->select("SELECT id FROM tipopago_detalle WHERE tipopagoid = ? AND tpopago_label = ?", [$tipopagoid, $tpopago_label]);
        if ($existe) {
            return $this->update("UPDATE tipopago_detalle SET tpopago_label = ?, tpopago_value = ? WHERE id = ?", [$tpopago_label, $tpopago_value, $existe['id']]);
        }
        return $this->insert("INSERT INTO tipopago_detalle (tipopagoid, tpopago_label, tpopago_value) VALUES (?,?,?)", [$tipopagoid, $tpopago_label, $tpopago_value]);
    }

    public function selectRegiones(): array
    {
        return $this->select_all("SELECT * FROM config_regional");
    }

    public function configRegionEnUso(int $idregion): ?array
    {
        return $this->select("SELECT idempresa FROM config_gral WHERE regionid = ?", [$idregion]);
    }

    public function selecRegionId(int $id): ?array
    {
        return $this->select("
            SELECT  r.*,
              IFNULL((SELECT idregion FROM config_regional WHERE idregion < r.idregion ORDER BY idregion DESC LIMIT 1), 0) AS prev,
              IFNULL((SELECT idregion FROM config_regional WHERE idregion > r.idregion ORDER BY idregion ASC LIMIT 1), 0) AS prox,
              CONCAT((SELECT COUNT(*) FROM config_regional WHERE idregion <= r.idregion), ' de ', (SELECT COUNT(*) FROM config_regional)) AS posicion
            FROM config_regional AS r
            WHERE r.idregion = ?", [$id]);
    }
}