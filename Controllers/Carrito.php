<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;
use MercadoPago\Shipments;

class Carrito extends Controllers
{
    public function __construct()
    {
        if (($_SESSION['info_empresa']['fecha_mantenimiento_hasta'] ?? '') > date("Y-m-d H:i:s")) {
            header("Location:" . base_url() . 'enConstruccion');
            exit();
        }
        parent::__construct();
    }

    public function carrito()
    {
        $homeController = new Home();
        $data['header'] = $homeController->data_header('Carrito');
        $data['footer'] = $homeController->data_footer();

        $empresa = $_SESSION['info_empresa'];
        $data['empresa'] = $empresa;
        $data['tpos_pago'] = $this->getTPDetalles();

        $data['meta'] = [
            'robots' => 'noindex, nofollow, noarchive',
            'title' => $empresa['nombre_comercial'],
            'description' => substr(strClean(strip_tags($empresa['descripcion'])), 0, 160),
            'keywords' => $empresa['tags'],
            'url' => base_url(),
            'image' => $empresa['url_logoImpreso'],
            'image:type' => 'image/' . pathinfo($empresa['logo_imp'], PATHINFO_EXTENSION),
            'og:type' => 'website'
        ];
        $data["page_css"] = [];
        $data["page_functions_js"] = ['js/functions_admin'];

        $this->views->getView("Carrito", $data);
    }

    public function procesarPago()
    {
        if (!isset($_SESSION['arrCarrito']) || empty($_SESSION['arrCarrito'])) {
            header("Location:" . base_url());
            exit();
        }

        $homeController = new Home();
        $data['header'] = $homeController->data_header('Procesar Pago');
        $data['footer'] = $homeController->data_footer();

        $empresa = $_SESSION['info_empresa'];
        $data['empresa'] = $empresa;
        $data['tipos_pagos'] = $this->getTPDetalles();

        $data['meta'] = [
            'robots' => 'noindex, nofollow, noarchive',
            'title' => $empresa['nombre_comercial'],
            'description' => substr(strClean(strip_tags($empresa['descripcion'])), 0, 160),
            'keywords' => $empresa['tags'],
            'url' => base_url(),
            'image' => $empresa['url_logoImpreso'],
            'image:type' => 'image/' . pathinfo($empresa['logo_imp'], PATHINFO_EXTENSION),
            'og:type' => 'website'
        ];

        $data["page_css"] = [];
        $data["page_functions_js"] = ['js/functions_admin', 'js/functions_carrito_procesar_pago'];

        $this->views->getView("Procesarpago", $data);
    }

    public function confirmarpedido()
    {
        if (empty($_SESSION['dataorden'])) {
            header("Location:" . base_url());
            exit();
        }

        $homeController = new Home();
        $data['header'] = $homeController->data_header('Pedido Confirmado');
        $data['footer'] = $homeController->data_footer();

        $empresa = $_SESSION['info_empresa'];
        $data['empresa'] = $empresa;
        $data['orden'] = $_SESSION['dataorden']['orden'];
        $data['transaccion'] = $_SESSION['dataorden']['transaccion'];

        $data['meta'] = [
            'robots' => 'noindex, nofollow, noarchive',
            'title' => $empresa['nombre_comercial'],
            'description' => substr(strClean(strip_tags($empresa['descripcion'])), 0, 160),
            'keywords' => $empresa['tags'],
            'url' => base_url(),
            'image' => $empresa['url_logoImpreso'],
            'image:type' => 'image/' . pathinfo($empresa['logo_imp'], PATHINFO_EXTENSION),
            'og:type' => 'website'
        ];

        set_notificacion('pedido', $_SESSION['dataorden']['transaccion']);
        $this->views->getView("Confirmarpedido", $data);

        // Limpiar variables de sesión
        unset($_SESSION['arrCarrito'], $_SESSION['dataorden'], $_SESSION['data_pedido_mp']);

        $idUser = intval($_SESSION['idUser']);
        session_regenerate_id(false);
        sessionUser($idUser);
    }

    public function getShipping()
    {
        $arrResponse = ['shipping' => formatMoney($_SESSION['info_empresa']['costo_envio'])];
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function procesarVentaCE()
    {
        if (!$_POST) {
            return;
        }

        if ($_POST['tpopago'] != 'ce') {
            echo json_encode(['status' => false, 'msg' => 'Método de pago no válido.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $datosPOST = $_POST;
        $datosPOST['datajson'] = null;
        $datosPOST['transaccionid'] = empty($_POST['transaccionid']) ? null : $_POST['transaccionid'];
        $datosPOST['status'] = 'Pendiente';

        if (!isset($_SESSION['idUser']) || $_SESSION['idUser'] <= 0) {
            $_SESSION['idUser'] = $this->insertClienteCarrito($_POST);
        }

        $request_pedido = $this->procesoInsertarPedido($datosPOST);

        $_SESSION['dataorden'] = [
            'orden' => encript($request_pedido['idpedido']),
            'transaccion' => encript($request_pedido['transaccionid']),
        ];

        $arrResponse = ['status' => true];
        unset($_SESSION['arrCarrito']);

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function validaridtransferencia($idtransferencia)
    {
        $request = $this->model->selectTransaccionId($idtransferencia);
        echo json_encode(['status' => $request == 0], JSON_UNESCAPED_UNICODE);
    }

    public function procesarVentatb()
    {
        if (!$_POST) {
            return;
        }

        if ($_POST['tpopago'] != 'tb') {
            echo json_encode(['status' => false, 'msg' => 'Método de pago no válido.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $datosPOST = $_POST;
        $datosPOST['datajson'] = null;
        $datosPOST['transaccionid'] = $_POST['transaccionid'];
        $datosPOST['status'] = 'Pendiente';

        $request_pedido = $this->procesoInsertarPedido($datosPOST);

        $_SESSION['dataorden'] = [
            'orden' => encript($request_pedido['idpedido']),
            'transaccion' => encript($request_pedido['transaccionid']),
        ];

        $arrResponse = ['status' => true];
        unset($_SESSION['arrCarrito']);

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function procesarVentaPP()
    {
        if (!$_POST) {
            echo json_encode(['status' => false, 'msg' => 'Datos no recibidos.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        if ($_POST['tpopago'] != 'pp') {
            echo json_encode(['status' => false, 'msg' => 'Método de pago no válido.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $datosPOST = $_POST;
        $objPaypal = json_decode($datosPOST['datapay']);

        if (!is_object($objPaypal)) {
            echo json_encode(['status' => false, 'msg' => 'Error en los datos de pago.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $datosPOST['datajson'] = $datosPOST['datapay'];
        $datosPOST['transaccionid'] = $objPaypal->purchase_units[0]->payments->captures[0]->id;
        $datosPOST['status'] = ($objPaypal->status == "COMPLETED" && $objPaypal->purchase_units[0]->amount->value == number_format($datosPOST['total_monto'], 2, ".", ""))
            ? 'Aprobado'
            : 'Pendiente';

        $request_pedido = $this->procesoInsertarPedido($datosPOST);

        $_SESSION['dataorden'] = [
            'orden' => encript($request_pedido['idpedido']),
            'transaccion' => encript($request_pedido['transaccionid']),
        ];

        $arrResponse = ['status' => true];
        unset($_SESSION['arrCarrito']);

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    public function prefereciaMP()
    {
        if (!$_POST) {
            return;
        }

        $_SESSION['data_pedido_mp'] = $_POST;
        $_SESSION['data_pedido_mp']['tpopago'] = 'mp';

        $carrito = $_SESSION['arrCarrito'];
        $data_mp = $this->getTPDetalles()['mp']['detalle'];

        SDK::setAccessToken($data_mp['mpAccesTocken']);
        $preference = new Preference();
        $prod_carrito = [];

        foreach ($carrito as $producto) {
            $item = new Item();
            $item->id = $producto['idproducto'];
            $item->title = $producto['nombre'];
            $item->description = substr(strClean(strip_tags($producto['nombre'])), 0, 100);
            $item->picture_url = $producto['img'];
            $item->quantity = $producto['cantidad'];
            $item->currency_id = $data_mp['mpCurrency'];
            $item->unit_price = $producto['precio'];
            $prod_carrito[] = $item;
        }

        $preference->items = $prod_carrito;
        $preference->back_urls = [
            "success" => base_url() . "carrito/procesarventamp",
            "failure" => base_url() . "carrito/procesarventamp",
            "pending" => base_url() . "carrito/procesarventamp"
        ];
        $preference->auto_return = "approved";

        if (isset($_POST['metodoEntrega']) && $_POST['metodoEntrega'] === 'entrega') {
            $ship = new Shipments();
            $ship->mode = 'not_specified';
            $ship->cost = floatval($_SESSION['info_empresa']['costo_envio']);
            $preference->shipments = $ship;
        }

        $preference->save();

        echo json_encode(['id' => $preference->id], JSON_UNESCAPED_UNICODE);
    }

    public function procesarVentaMP()
    {
        $datavent = $_SESSION['data_pedido_mp'];
        $datosMP = $_GET;

        $datosPOST = [
            'transaccionid' => $datosMP['id'],
            'datajson' => json_encode($datosMP),
            'subtotal_monto' => $datavent['subtotal'],
            'shipping_monto' => $datavent['envio'],
            'total_monto' => $datavent['subtotal'] + $datavent['envio'],
            'metodoEntrega' => $datavent['metodoEntrega'],
            'tpopago' => $datavent['tpopago'],
            'direccion' => $datavent['direccion'],
            'ciudad' => $datavent['ciudad'],
            'status' => ($datosMP['status'] == 'approved') ? 'Aprobado' : 'Pendiente',
        ];

        $request_idpedido = $this->procesoInsertarPedido($datosPOST);

        $_SESSION['dataorden'] = [
            'orden' => encript($request_idpedido['idpedido']),
            'transaccion' => encript($request_idpedido['transaccionid']),
        ];

        unset($_SESSION['arrCarrito'], $_SESSION['data_pedido_mp']);
        header("Location:" . base_url() . 'carrito/confirmarpedido');
    }

    private function getTPDetalles()
    {
        $TPDetalles = [];
        $arrTP = $this->model->selectTiposPagosT();
        foreach ($arrTP as $TP) {
            $arrtpd = [];
            $detalles = $this->model->selectTiposPagoDetallesT($TP['idtipopago']);
            foreach ($detalles as $D) {
                $arrtpd[$D['tpopago_label']] = $D['tpopago_value'];
            }
            $TPDetalles[$TP['tipopago']] = [
                'tipopago' => $TP['tipopago'],
                'idtipopago' => $TP['idtipopago'],
                'nombre_tpago' => $TP['nombre_tpago'],
                'status' => $TP['status'],
                'detalle' => $arrtpd
            ];
        }
        return $TPDetalles;
    }

    private function insertClienteCarrito($data)
    {
        $meta = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $_SERVER['REMOTE_ADDR']));

        $strNombre = ucwords(strClean($data['nombre']));
        $strApellido = ucwords(strClean($data['apellido']));
        $intTelefono = intval(strClean($data['telefono']));
        $strEmail = strtolower(strClean($data['email']));

        $idUser = $this->model->insertClienteCarr(
            $strNombre,
            $strApellido,
            $intTelefono,
            $strEmail,
            'I',
            ucwords(strClean($data['direccion'])),
            ucwords(strClean($data['ciudad'])),
            ucwords(strClean($data['ciudad'])),
            $meta['geoplugin_countryName'],
            hash("SHA256", passGenerator()),
            2
        );

        $_SESSION['idUser'] = intval($idUser);
        return intval($idUser);
    }

    private function procesoInsertarPedido($datosPOST)
    {
        try {
            if (empty($_SESSION['arrCarrito'])) {
                throw new \Exception('No hay productos en el Carrito.');
            }

            $subtotal = 0;
            foreach ($_SESSION['arrCarrito'] as $prod) {
                $subtotal += $prod['cantidad'] * $prod['precio'];
            }

            $costo_envio = floatval($datosPOST['shipping_monto'] ?? 0);
            $monto = $subtotal + $costo_envio;

            $request_idpedido = $this->model->insertPedido(
                $datosPOST['transaccionid'],
                $datosPOST['datajson'],
                intval($_SESSION['idUser']),
                $subtotal,
                $costo_envio,
                $monto,
                $datosPOST['metodoEntrega'],
                intval($this->model->idTipoPago($datosPOST['tpopago'])),
                ($datosPOST['metodoEntrega'] == 'retiro' ? '' : strClean($datosPOST['direccion']) . ', ' . strClean($datosPOST['ciudad'])),
                $datosPOST['status']
            );

            if ($request_idpedido > 0) {
                foreach ($_SESSION['arrCarrito'] as $producto) {
                    $this->model->insertDetallePedido(
                        $request_idpedido,
                        $producto['idproducto'],
                        $producto['precio'],
                        intval($producto['cantidad'])
                    );
                }

                $infoOrden = $this->model->getPedido($request_idpedido);
                $empresa = $_SESSION['info_empresa'];
                $infoOrden['empresa'] = $empresa;
                $bodyMail = getFile("Template/Email/Confirmar_orden", $infoOrden);

                $arrDataEmail = [
                    'empresa' => $empresa,
                    'nombreUsuario' => $datosPOST['nombre'] . ' ' . $datosPOST['apellido'],
                    'email' => $datosPOST['email'],
                    'asunto' => 'Se ha creado la orden Nro:' . str_pad("$request_idpedido", 5, "0", STR_PAD_LEFT),
                ];
                sendEmail($arrDataEmail, $bodyMail);

                return ['idpedido' => $request_idpedido, 'transaccionid' => $datosPOST['transaccionid']];
            }
        } catch (\Exception $e) {
            error_log('Error en procesoInsertarPedido: ' . $e->getMessage());
            echo json_encode(['status' => false, 'msg' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
            exit();
        }
    }
}