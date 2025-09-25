<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php


headerTienda($data);
$empresa = $data['empresa'];

$Tpagos = $data['tipos_pagos'];


$subtotal = 0;

foreach ($_SESSION['arrCarrito'] as $producto) {
    $subtotal += $producto['precio'] * $producto['cantidad'];
}
$total = $subtotal + $empresa['costo_envio'];

$carrito = $_SESSION['arrCarrito'];
?>

<?php
/* ========================================================= */
// SDK de Mercado Pago
require __DIR__ . '/vendor/autoload.php';
// Agrega credenciales
MercadoPago\SDK::setAccessToken('APP_USR-7149247733431813-022300-4ff7ba03f63403adadb45392014368de-719053255');
// Crea un objeto de preferencia
$preference = new MercadoPago\Preference();

//URL de Retorno al terminar la oeracion
$preference->back_urls = array(
    "success" => base_url() . "carrito/procesarventamp",
    "failure" => base_url() . "carrito/procesarventamp",
    "pending" => base_url() . "carrito/procesarventamp"
);
$preference->auto_return = "all"; //approved
// productos del carrito 
$prod_carrito = array();
for ($i = 0; $i < count($carrito); $i++) {
// Crea un ítem en la preferencia
    $item = new MercadoPago\Item();

    $item->id = $carrito[$i]['idproducto'];
    $item->title = $carrito[$i]['producto'];
    $item->description = $carrito[$i]['producto'];
    $item->picture_url = $carrito[$i]['img'];
    $item->quantity = $carrito[$i]['cantidad'];
    $item->currency_id = "ARS";
    $item->unit_price = $carrito[$i]['precio'];

    $prod_carrito[$i] = $item;
}


$preference->items = $prod_carrito;

//dep(array($prod_carrito));

$preference->save();
?>
<html>

    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        
            <form action="procesar.php"method="POST">
    <script
        src="https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js"
        data-preference-id="<?php echo $preference->id; ?>">
    </script>
            
            
            </form>
        
        
        <?php
        foreach ($_SESSION['arrCarrito'] as $producto) {
            $totalProducto = $producto['precio'] * $producto['cantidad'];
            $subtotal += $totalProducto;
            $idProducto = encript($producto['idproducto']);
            ?>
        <tr class="table_row <?= $idProducto ?>">
            <td class="column-1">
                <div class="how-itemcart1" idpr="<?= $idProducto ?>" op="2" onclick="fntdelItem(this)">
                    <img src="<?= $producto['img'] ?>" alt="<?= $producto['producto'] ?>">
                </div>
            </td>
            <td class="column-2"><?= $producto['producto'] ?></td>
            <td class="column-3"><?= formatMoney($producto['precio']) ?></td>
            <td class="column-4">
                <div class="wrap-num-product flex-w m-l-auto m-r-0">
                    <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m" idpr ="<?= $idProducto ?>">
                        <i class="fs-16 zmdi zmdi-minus"></i>
                    </div>

                    <input class=" mtext-104 cl3 txt-center num-product" idpr ="<?= $idProducto ?> type="number" name="num-product1" value="<?= $producto['cantidad'] ?>">

                    <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m" idpr ="<?= $idProducto ?>">
                        <i class="fs-16 zmdi zmdi-plus"></i>
                    </div>
                </div>
            </td>
            <td class="column-5"><?= formatMoney($totalProducto) ?></td>
        </tr>
    <?php } ?>


</body>



</html>
