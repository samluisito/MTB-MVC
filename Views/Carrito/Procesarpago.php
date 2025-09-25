
<?php
headerTienda($data);
$empresa = $data['empresa'];
$tPagos = $data['tipos_pagos'];

$subtotal = 0;

foreach ($_SESSION['arrCarrito'] as $producto) {
  $precio_producto = ($producto['oferta'] != 0 ? $producto['oferta'] : $producto['precio']);
  $totalProducto = $producto['cantidad'] * $precio_producto;
  $subtotal += $totalProducto;
}

$total = $subtotal + $empresa['costo_envio'];
$carrito = $_SESSION['arrCarrito'];

/* ========================================================= */
$modoEnvio = $data['empresa']['modo_entrega'];

$retiroEnTienda = $modoEnvio == 'r' || $modoEnvio == 'rd' ? true : false;
?>

<!-- breadcrumb -->
<div class="container">
  <div class="bread-crumb flex-w p-l-25 p-r-15 p-tb-15 p-lr-0-lg bg0">
    <a href="<?= base_url(); ?>" class="stext-109 cl8 hov-cl1 trans-04">
      Inicio
      <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
    </a>
    <a href="<?= base_url() . 'carrito'; ?>" class="stext-109 cl8 hov-cl1 trans-04">
      Carrito
      <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
    </a>
    <span class="stext-109 cl4"><?= $data['header']['page_title']; ?></span>
  </div>
</div>
<!-- Title page -->
<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('<?= DIR_MEDIA ?>images/bg-03.webp');">
  <h2 class="ltext-105 cl0 txt-center">

  </h2>
</section>	




<div class="container">
  <div class="row">
    <div class="col-lg-8 p-all-10 ">
      <div class=" p-all-10 bg0">      
        <div class="p-all-10 bor4">      

          <form id="formEenvioRetiro">
            <!--block datos del comprador-->
            <div class="row">
              <div class="col-md-6">
                <label for="txtNombre"> Nombre *</label>
                <div class="form-group bor8 bg0 m-b-12">
                  <input id="txtNombre" class="stext-111 cl8 plh3 size-111 p-lr-15 paso-1 campo-requerido form-control" 
                         type="text" name="nombre" placeholder="" value="">
                </div>
                <div id="feedback-nombre" class="m-b-20 notBlock ">Nombre no valido</div>
              </div>
              <div class="col-md-6">
                <label for="txtApellido"> Apellido *</label>
                <div class="form-group bor8 bg0 m-b-12">
                  <input id="txtApellido" class="stext-111 cl8 plh3 size-111 p-lr-15 paso-1 campo-requerido form-control" 
                         type="text" name="apellido" placeholder="" value="">
                </div>
                <div id="feedback-apellido" class="m-b-20 notBlock ">Apellido no valido</div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <label for="txtEmail"> Email *</label>
                <div class="form-group bor8 bg0 m-b-12">
                  <input id="txtEmail" class="stext-111 cl8 plh3 size-111 p-lr-15 paso-1 campo-requerido form-control" 
                         type="text" name="email" placeholder="" value="">
                </div>
                <div id="feedback-email" class="m-b-20 notBlock ">Email invalido</div>
              </div>
              <div class="col-md-6">
                <label for="txtTelefono"> Telefono *</label>
                <div class="form-group bor8 bg0 m-b-12">
                  <input id="txtTelefono" class="stext-111 cl8 plh3 size-111 p-lr-15 paso-1 campo-requerido form-control" 
                         type="tel" name="telefono" placeholder="" value="">
                </div>
                <div id="feedback-telefono" class="m-b-20 notBlock ">Telefono invalido</div>
              </div>
            </div>


            <input type="hidden" id="metodoEntregaSelect" name="metodoEntrega" value="entrega">

            <!--block selector de envio y retiro-->
            <div class="row ">
              <div class="col-md-12"><div class=" bg0 m-b-22"></div></div>
              <div class="col-sm-6 divRetiroEnvio ">
                <label><input type="radio" id="envio"  class="selctMetodoEntrega" name="metodoEntrega" value="entrega" checked> Envio a Domicilio </label>
              </div>
              <?php if ($retiroEnTienda) { ?>
                <div class="col-sm-6 divRetiroEnvio" >
                  <label ><input type="radio" id="retiro" class="selctMetodoEntrega" name="metodoEntrega" value="retiro" > Retiro en Tienda </label>
                </div><?php }; ?>
            </div>
            <div class="row" ><div class="col-md-12"><div class="bor8 bg0 m-b-22"></div></div></div>

            <!--block datos de envio -->
            <div class="row metodoEntregaDiv" id="entregaDiv" >
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12">
                    <label for="txtDireccion"> Direccion de envio *</label>
                    <div class="form-group bor8 bg0 m-b-12">
                      <input id="txtDireccion" class="stext-111 cl8 plh3 size-111 p-lr-15 paso-1 campo-requerido form-control" 
                             type="text" name="direccion" placeholder="" value="">
                    </div>
                    <div id="feedback-direccion" class="m-b-20 notBlock ">Direccion imvalida</div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6"> 
                    <label>Ciudad *</label> 
                    <div class="form-group bor8 bg0 m-b-22">
                      <input id="txtCiudad" class="stext-111 cl8 plh3 size-111 p-lr-15 paso-1 campo-requerido form-control" 
                             type="text" name="ciudad" placeholder="Ciudad">
                    </div>
                    <div id="feedback-ciudad" class="m-b-20 notBlock ">Ciudad Invalida</div>
                  </div>
                  <div class="col-md-6">
                    <label for="txtPostal"> Codigo Postal *</label>
                    <div class="form-group bor8 bg0 m-b-12">
                      <input id="txtCodPostal" class="stext-111 cl8 plh3 size-111 p-lr-15 paso-1 campo-requerido form-control" 
                             type="text" name="codpostal" placeholder="" value="">
                    </div>
                    <div id="feedback-codpostal" class="m-b-20 notBlock ">Codigo invalido</div>
                  </div>
                </div>
              </div>
            </div>

            <!--block datos de retiro -->
            <div class="row metodoEntregaDiv notBlock" id="retiroDiv" > 
              <div class=" col-md-6 flex-w w-full">
                <span class="fs-18 cl5 txt-center size-211"><i class="fa fa-map-marker"></i> </span>
                <div class="size-212 p-t-2">
                  <span class="mtext-110 cl2">  Direccion </span>
                  <p class="stext-115 cl6 size-213 p-t-18"> Buenos Aires Argentina </p>
                </div>
              </div>
              <div class=" col-md-6 flex-w w-full">
                <span class="fs-18 cl5 txt-center size-211"><i class="fa fa-calendar"></i></span>
                <div class="size-212 p-t-2">
                  <span class="mtext-110 cl2">Nuestros Horarios de Atencion:</span>
                  <p class="stext-115 cl6 size-213 p-t-18">De Lunes a Viernes de 9:00 am a 6:00pm</p>
                </div>
              </div>
            </div>
          </form>
        </div>
        <!--Block resumen de carrito-->
        <div class="row p-t-10">
          <div class="col-12">
            <div class="wrap-table-shopping-cart">
              <table class="table-shopping-cart">
                <tbody>
                  <tr class="table_head">
                    <th class="column-start">Descripcion</th>
                    <th class="column-left"></th>
                    <th class="column-right">Precio</th>
                    <th class="column-center">Cantidad</th>
                    <th class="column-end">Total</th>
                  </tr>
                  <?php
                  foreach ($_SESSION['arrCarrito'] as $producto) {
                  
                    $totalProducto = $producto['cantidad'] * ($producto['oferta'] != 0 ? $producto['oferta'] : $producto['precio']);

                    $html_oferta = $producto['oferta'] != 0 ?
                        "<span class='header-cart-item-info p-l-15 p-r-10'> " . formatMoney($producto['oferta']) . "</span>" : '';
                    $html_precio = $producto['oferta'] !== 0 ?
                        "<span class='header-cart-item-info del_precio_oferta stext-107 cl12 p-l-35 '>" . formatMoney($producto['precio']) . "</span>" :
                        "<span class='header-cart-item-info p-l-15 p-r-10'>" . formatMoney($producto['precio']) . "</span>";
                    ?>
                    <tr class="table_row_checkout">
                      <td class="column-start ">
                        <div class="how-itemcart2 prod-pic-rel"><img class="prod-scale-img" src="<?= $producto['img'] ?>" alt="IMG">
                        </div>
                      </td>
                      <td class="column-left"><?= $producto['nombre'] ?></td>
                      <td class="column-right"><?= $html_oferta . $html_precio ?></td>
                      <td class="column-center"><?= $producto['cantidad'] ?></td>
                      <td class="column-end"><?= formatMoney($totalProducto) ?></td>
                    </tr>
                  <?php } ?>

                </tbody></table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4  p-all-10 ">
      <div class="p-all-10 bg0">   
        <div class="bor10 p-lr-40 p-t-30 p-b-40  m-lr-0-xl ">
          <h4 class="mtext-109 cl2 p-b-30">
            Cart Totals
          </h4>

          <div class="flex-w flex-t bor12 p-b-13">
            <div class="size-208">
              <span class="stext-110 cl2">
                Subtotal:
              </span>
            </div>

            <div class="size-209">
              <span class="mtext-110 cl2 subtotal_monto"> <?= formatMoney($subtotal); ?></span>
            </div>
          </div>

          <div class="flex-w flex-t bor12 p-t-15 p-b-30">
            <div class="size-208 w-full-ssm">
              <span class="stext-110 cl2">
                Shipping:
              </span>
            </div>

            <div class="size-209 p-r-18 p-r-0-sm w-full-ssm">
              <div class="size-209">
                <span class="mtext-110 cl2  shipping_monto"> $9.65</span>
              </div>
            </div>
          </div>

          <div class="flex-w flex-t p-t-27 p-b-33">
            <div class="size-208">
              <span class="mtext-101 cl2">
                Total:
              </span>
            </div>

            <div class="size-209 p-t-1">
              <span class="mtext-110 cl2 total_monto">$79.65</span>
            </div>
          </div>
        </div>




        <div class="bor10 p-lr-40 p-t-30 p-b-40  m-lr-0-xl ">
          <div id="divMetodoPago"class="">
            <div class="divMetodPago">
              <input type="hidden" id="idTPseleccionado"  value="">
              <?php foreach ($tPagos as $tipopago) { ?>
                <label for="<?= $tipopago['tipopago'] ?>">
                  <input type="radio" id="<?= $tipopago['tipopago'] ?>" class=" selctmethodpago" name="payment-method" value="<?= $tipopago['tipopago'] ?>"> <?= $tipopago['nombre_tpago'] ?>  
                  &nbsp;<img src="<?= DIR_MEDIA ?>images/img-ico-<?= $tipopago['tipopago'] ?>.png" alt="Icono de <?= $tipopago['nombre_tpago'] ?>" class="ml-space-sm" width="74" height="20">
                </label>
              <?php } ?>
            </div>  
            <hr>
            <?php if (!empty($tPagos['ce'])) { ?>
              <div id="ceDiv" class="metodopagodiv notBlock" >
                <div>
                  <h5><?= $tPagos['ce']['nombre_tpago'] ?></h5>
                  <p><?= $tPagos['ce']['detalle']['ceDescripcion'] ?></p>
                  <textarea class="container p-t-10 p-b-10 desc-mtpg"><?= $tPagos['ce']['detalle']['ceDetalle'] ?></textarea>
                </div>
                <button type="" id="ceButton" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">Pagar</button>
              </div>
            <?php } if (!empty($tPagos['tb'])) { ?>
              <div id="tbDiv" class="metodopagodiv notBlock" >
                <div class="">
                  <h5><?= $tPagos['tb']['nombre_tpago'] ?></h5>

                  <p><?= $tPagos['tb']['detalle']['tbDescripcion'] ?></p>
                  <textarea class="container p-t-10 p-b-10 desc-mtpg"><?= $tPagos['tb']['detalle']['tbDetalle'] ?></textarea>
                </div>
                <hr>
                <div>
                  <div class="col form-group">
                    <h6>Indique el numero de transferencia / deposito</h6>
                    <input class="campo-requerido form-control" type="text" id="idTranfer" name="idTranfer">
                  </div>
                  <div id="feedback-idTranfer" class="m-b-20 notBlock ">Nuero de transaccion invalido</div>

                  <button type="" id="tbButton" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">Pagar</button>
                </div>
              </div>
            <?php } if (!empty($tPagos['pp'])) { ?>
              <div id="ppDiv" class="metodopagodiv notBlock" >
                <div>
                  <textarea class="container p-t-10 p-b-10 desc-mtpg">Para completar la transacción, te enviaremos a un espacio seguro desde los servidores seguros de PayPal.</textarea>
                </div>
                <hr>
                <!--contenedor de botones paypal-->
                <div id="paypal-button-container"></div>
              </div>
            <?php } if (!empty($tPagos['mp'])) { ?>
              <div id="mpDiv"class="metodopagodiv notBlock" >
                <div>
                  <textarea class="container p-t-10 p-b-10 desc-mtpg">Para completar la transacción, te enviaremos a los servidores seguros de MercadoPago.</textarea>
                </div>
                <hr>
  <!-- <input type="hidden" id="data-preference-id" name="data-preference-id" value="<?php //$preference->id;                                                                                                               ?>"><!-- este elemento estara oculto y su funcion es setear el id del rol a actualizar -->
                <input type="hidden" id="data-preference-monto" name="monto" value="<?php echo $total; ?>"><!-- este elemento estara oculto y su funcion es setear el id del rol a actualizar -->
                <input type="hidden" id="data-preference-subtotal" name="subtotal" value="<?php echo $subtotal; ?>"><!-- este elemento estara oculto y su funcion es setear el id del rol a actualizar -->
                <input type="hidden" id="data-preference-envio" name="subtotal" value="<?php echo $empresa['costo_envio']; ?>"><!-- este elemento estara oculto y su funcion es setear el id del rol a actualizar -->

                <div class="container" id="mpButtonContainer"></div>

              </div>
            <?php } ?>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>





<!-- Button trigger modal 
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>-->
<!-- Modal Terminos y Condiciones -->
<div class="modal fade" id="terminiosycondiciones" tabindex="-1" aria-labelledby="terminiosycondiciones" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Terminos y Condiciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>
          <?php //print TERMINOS_Y_CONDICIONES;  ?>  
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php if (!empty($tPagos['pp'])) { ?>
  <script
    src="https://www.paypal.com/sdk/js?client-id=<?= $data['tipos_pagos']['pp']['detalle']['ppClienteID'] ?>&currency=<?= $data['tipos_pagos']['pp']['detalle']['ppCurrency'] ?>"> // Required. Replace SB_CLIENT_ID with your sandbox client ID.
  </script>
  <!--script>  paypal.Buttons().render('#paypal-button-container');  // This function displays Smart Payment Buttons on your web page.</script-->
  <?php
};
if (!empty($tPagos['mp'])) {
  ?>
  <script src="https://sdk.mercadopago.com/js/v2"></script>
  <script> const mp = new MercadoPago('<?= $data['tipos_pagos']['mp']['detalle']['mpPublickKey'] ?>')</script><?php } ?>
<?= footerTienda($data); ?>

