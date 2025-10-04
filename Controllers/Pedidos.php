<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Pedidos extends Controllers
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

    public function pedidos()
    {
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) != 1) {
            header('location:' . base_url() . 'dashboard');
            exit();
        }

        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'Pedidos';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data["page_css"] = ["plugins/datatables/css/datatables.min.css"];
        $data["page_functions_js"] = [
            "plugins/jquery/jquery-3.6.0.min.js",
            "plugins/datatables/js/datatables.min.js",
            "js/functions_pedidos.js"
        ];

        $this->views->getView("Pedidos", $data);
    }

    public function orden($idpedido)
    {
        $idpedido = intval($idpedido);
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) == 1 && $idpedido > 0) {
            $data['pedido'] = $this->model->selectPedidoId($idpedido);
            $empresa = $_SESSION['info_empresa'];
            $data["empresa"] = $empresa;
            $data['page_name'] = 'Orden';
            $data['page_title'] = $data['page_name'];
            $data['logo_desktop'] = $empresa['url_logoMenu'];
            $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

            $notificacion = new Notificacion();
            $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

            $data["page_css"] = [];
            $data["page_functions_js"] = ["functions_pedidos.js"];

            $this->views->getView("Orden", $data);
            $notificacion->updateNotificacionID('pedido', $data['pedido']['pedido']['idpedido'], 1);
        } else {
            header('location:' . base_url() . 'dashboard');
            exit();
        }
    }

    public function transaccion($idtransaccion)
    {
        $idtransaccion = strClean($idtransaccion);
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) == 1 && $idtransaccion != "") {
            $data['pedido'] = $this->model->selectPedidoId($idtransaccion, true);
            $data['transaccion'] = $this->model->selectTransaccionPaypal($idtransaccion);

            $empresa = $_SESSION['info_empresa'];
            $data["empresa"] = $empresa;
            $data['page_name'] = 'Transaccion';
            $data['page_title'] = $data['page_name'];
            $data['logo_desktop'] = $empresa['url_logoMenu'];
            $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

            $notificacion = new Notificacion();
            $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

            $data["page_css"] = [];
            $data["page_functions_js"] = ["functions_pedidos.js"];

            $this->views->getView("Transaccion", $data);
        } else {
            header('location:' . base_url() . 'dashboard');
            exit();
        }
    }

    public function getPedido($idpedido)
    {
        $idpedido = intval($idpedido);
        if ($idpedido > 0) {
            $request_pedido = $this->model->selectPedidoId($idpedido);
            if (is_array($request_pedido)) {
                $htmlModal = getFile('Template/Modals/modalPedido', $request_pedido);
                $notificacion = new Notificacion();
                $notificacion->updateNotificacionID('pedido', $request_pedido['pedido']['idpedido'], 1);
                $arrResponse = ["status" => true, 'html' => $htmlModal];
            } else {
                $arrResponse = ["status" => false, 'msg' => 'Este pedido pertenece a otro cliente'];
            }
        } else {
            $arrResponse = ["status" => false, 'msg' => 'ID de pedido inválido'];
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function setPedido()
    {
        if ($_POST && ($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) == 1) {
            $idpedido = !empty($_POST['idPedido']) ? intval($_POST['idPedido']) : 0;
            $estado = !empty($_POST['listEstado']) ? strClean($_POST['listEstado']) : "";
            $tipoPago = !empty($_POST['listTpoPago']) ? strClean($_POST['listTpoPago']) : "";
            $transaccion = !empty($_POST['txtTransaccion']) ? strClean($_POST['txtTransaccion']) : "";

            if ($idpedido == 0) {
                $arrResponse = ["status" => false, 'msg' => 'ID de pedido incorrecto'];
            } else {
                $requestPedido = $this->model->updatePeido($idpedido, $transaccion, $tipoPago, $estado);
                if ($requestPedido) {
                    $arrResponse = ["status" => true, 'msg' => 'Pedido Actualizado'];
                } else {
                    $arrResponse = ["status" => false, 'msg' => 'No se realizaron cambios'];
                }
            }
        } else {
            $arrResponse = ["status" => false, 'msg' => 'Acceso no permitido'];
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function getPedidos()
    {
        $idUser = ($_SESSION['userData']['rolid'] == 2) ? $_SESSION['userData']['idpersona'] : null;
        $data = $this->model->selectPedidos($idUser);
        $notificacion = new Notificacion();

        foreach ($data as &$item) {
            $id_not = $notificacion->getIdNotificacionesTipo('pedido', $item['idpedido']);
            $item['status'] = $id_not > 0 ? '<span class="badge bg-success font-size-14 me-1">' . $item['status'] . '</span>' : $item['status'];
            $item['transaccion'] = $item['transaccionid'] ?: $item['referenciadecobro'];
            $item['monto'] = formatMoney($item['monto']);

            $btnView = '<a href="' . base_url() . 'pedidos/orden/' . $item['idpedido'] . '" target="_blank" class="btn btn-info m-1" title="Ver Pedido"><i class="fa fa-eye"></i></a>';
            $btnPdf = '<a class="btn btn-danger m-1" href="' . base_url() . 'factura/generarFactura/' . $item['idpedido'] . '" target="_blank" title="Generar PDF"><i class="far fa-file-pdf"></i></a>';

            $btnMethodPay = match ($item['tipopago']) {
                'ce' => '<button class="btn btn-primary m-1" title="Editar transacción Efectivo" type="button" onClick="fntEditPedido(' . $item['idpedido'] . ')"><i class="far fa-money-bill-alt"></i></button>',
                'tb' => '<button class="btn btn-info m-1" title="Ver transacción Bancaria" type="button"><i class="fas fa-exchange-alt"></i></button>',
                'pp' => '<a title="Ver detalle de transacción con PayPal" href="' . base_url() . 'pedidos/transaccion/' . $item['transaccionid'] . '" target="_blank" class="btn btn-info m-1"><i class="fab fa-paypal"></i></a>',
                'mp' => '<button class="btn btn-info m-1" title="Ver transacción MercadoPago" type="button"><i class="fas fa-credit-card"></i></button>',
                default => ''
            };

            $item['options'] = '<div class="text-center">' . $btnView . $btnPdf . $btnMethodPay . '</div>';
        }
        exit(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}