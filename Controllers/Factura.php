<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;
use Spipu\Html2Pdf\Html2Pdf;

class Factura extends Controllers
{
    private int $idModul = 5;

    public function __construct()
    {
        if (empty($_SESSION['login'])) {
            $login = new Login();
            $login->login();
            exit();
        }
        parent::__construct();
    }

    public function generarFactura($id)
    {
        $idpedido = intval(strClean($id));
        if ($idpedido > 0) {
            $empresa = $_SESSION['info_empresa'];
            $data = $this->model->selectPedidoId($idpedido);

            if (!empty($data)) {
                $rutaDirVista = 'Template/Modals/comptobantePDF';
                $data['infoEmpresa'] = $empresa;

                ob_end_clean();
                $html = getFile($rutaDirVista, $data);
                $html2pdf = new Html2Pdf();
                $html2pdf->writeHTML($html);
                $html2pdf->output();
                exit();
            }
        }

        // Redirect or show error if pedido not found
        header('Location: ' . base_url() . 'pedidos');
        exit();
    }
}