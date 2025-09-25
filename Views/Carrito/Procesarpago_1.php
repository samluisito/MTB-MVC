
<?php
headerTienda($data);
$empresa = $data['empresa'];
$tPagos = $data['tipos_pagos'];

$subtotal = 0;

foreach ($_SESSION['arrCarrito'] as $producto) {
   $totalProducto = $producto['precio'] * $producto['cantidad'];
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
   <div class="bread-crumb flex-w p-l-25 p-r-15 p-tb-15 p-lr-0-lg">
      <a href="<?= base_url(); ?>" class="stext-109 cl8 hov-cl1 trans-04">
         Inicio
         <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
      </a>
      <a href="<?= base_url() . 'carrito'; ?>" class="stext-109 cl8 hov-cl1 trans-04">
         Carrito
         <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
      </a>
      <span class="stext-109 cl4"><?= $data['page_title']; ?></span>
   </div>
</div>
<!-- Title page -->
<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('<?= DIR_MEDIA ?>images/bg-03.webp');">
   <h2 class="ltext-105 cl0 txt-center">

   </h2>
</section>	


<div class="container">
   <div class="row">
      <div class="col-lg-10 col-xl-8 m-lr-auto m-tb-20 p-30">
         <div class="bor10 p-tb-25 p-lr-15 p-lr-15-sm m-lr-35 m-l-25 m-lr-0-xl  m-r--38 bg0">
            <br>
            <!-- Asistente de Pago -->
            <div class="container">
               <div class="row d-flex justify-content-center">
                  <div class="col-md-12">
                     <div class="wizard">
                        <div class="wizard-inner">
                           <div class="connecting-line connecting-line3 "></div>
                           <ul class="nav nav-tabs" role="tablist">
                              <li role="presentation" class="wizard-li3 active">
                                 <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" aria-expanded="true"><span class="round-tab">1 </span> <i>Paso 1</i></a>
                              </li>
                              <li role="presentation" class="wizard-li3 disabled">
                                 <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" aria-expanded="false"><span class="round-tab">2</span> <i>Paso 2</i></a>
                              </li>
                              <li role="presentation" class="wizard-li3 disabled">
                                 <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" aria-expanded="false"><span class="round-tab">3</span> <i>Paso 3</i></a>
                              </li>
                           </ul>
                        </div>

                        <div class="tab-content" id="main_form">
                           <div class="tab-pane active" role="tabpanel" id="step1">
                              <h4 class="text-center p-b-30 "> Envio a domicilio <?= $retiroEnTienda ? '/ Retiro en tienda' : '' ?> </h4>
                              <br>
                              <input type="hidden" id="metodoEntregaSelect" name="metodoEntrega" value="entrega">
                              <form id="formEenvioRetiro">
                                 <div class="row">
                                    <div class="col-sm-6 divRetiroEnvio">
                                       <label><input type="radio" id="envio"  class="selctMetodoEntrega" name="metodoEntrega" value="entrega" checked> Envio a Domicilio </label>
                                    </div>
                                    <?php if ($retiroEnTienda) { ?>
                                       <div class="col-sm-6 divRetiroEnvio" >
                                          <label ><input type="radio" id="retiro" class="selctMetodoEntrega" name="metodoEntrega" value="retiro" > Retiro en Tienda </label>
                                       </div><?php }; ?>
                                 </div>
                                 <div class="row" >
                                    <div class="col-md-12">
                                       <div class="bor8 bg0 m-b-22"></div>
                                    </div>
                                 </div>
                                 <div class="row metodoEntregaDiv" id="entregaDiv" >
                                    <div class="col-md-6">
                                       <label for="txtDireccion"> Direccion de envio *</label>
                                       <div class="form-group bor8 bg0 m-b-12">
                                          <input id="txtDireccion" class="stext-111 cl8 plh3 size-111 p-lr-15 paso-1 campo-requerido form-control" type="text" name="direccion" placeholder="Direccion de envio" value="<?= $_SESSION ['userData']['direccionfiscal'] ?>">
                                       </div>
                                       <div id="feedback-direccion" class="m-b-20 notBlock ">Direccion imvalida</div>
                                    </div>

                                    <div class="col-md-6"> 
                                       <label>Ciudad *</label> 
                                       <div class="form-group bor8 bg0 m-b-22">
                                          <input id="txtCiudad" class="stext-111 cl8 plh3 size-111 p-lr-15 paso-1 campo-requerido form-control" type="text" name="ciudad" placeholder="Ciudad">
                                       </div>
                                       <div id="feedback-ciudad" class="m-b-20 notBlock ">Ciudad Invalida</div>
                                    </div>
                                 </div>
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
                                 <ul class="list-inline pull-right">
                                    <li><button type="button" class="default-btn next-step"onclick="btnPasoSiguiente('1')">Continue</button></li>
                                 </ul>
                              </form>
                           </div>
                           <div class="tab-pane" role="tabpanel" id="step2">
                              <h4 class="text-center p-b-30">Resumen</h4>
                              <br>
                              <div class="row">
                                 <table class="table">
                                    <thead class="">
                                       <tr>
                                          <th scope="col" class="column-3 text-left">Producto</th>
                                          <th scope="col" class="column-6 text-center">Cantidad</th>
                                          <th scope="col" class="column-6 text-right">Total</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                       foreach ($_SESSION['arrCarrito'] as $producto) {
                                          $totalProducto = $producto['precio'] * $producto['cantidad'];
                                          ?>
                                          <tr >
                                             <td scope ="row" class="column-3 text-left" ><?= $producto['nombre'] ?></td>
                                             <td class=" text-center"><?= $producto['cantidad'] ?> </td>
                                             <td class="text-right"><?= formatMoney($totalProducto) ?></td>
                                          </tr>
                                       <?php } ?>
                                    <tbody>
                                    <tfoot >
                                       <tr>
                                          <th colspan="2" class="text-right "> SubTotal: </th>                    
                                          <td class="text-right subtotal_monto"> <?= formatMoney($subtotal); ?></td>                    
                                       </tr>
                                       <tr>
                                          <th colspan="2" class="text-right "> Envio: </th>                    
                                          <td class="text-right shipping_monto"> </td>                    
                                       </tr>
                                       <tr>
                                          <th colspan="2" class="text-right "> Total: </th>                    
                                          <td class="text-right total_monto"> </td>                    
                                       </tr>
                                    </tfoot>

                                 </table>
                              </div>
                              <ul class="list-inline pull-right">
                                 <li><button type="button" class="default-btn prev-step">Back</button></li>
                                 <!--                                                            <li><button type="button" class="default-btn next-step skip-btn">Skip</button></li>-->
                                 <li><button type="button" class="default-btn next-step" onclick="btnPasoSiguiente('1')">Continue</button></li>
                              </ul>
                           </div>
                           <div class="tab-pane" role="tabpanel" id="step3">
                              <h4 class="p-b-30 text-center">   Método de pago     </h4>
                              <div class="row">
                                 <div class="col-sm-5 m-lr-auto m-b-25">
                                    <div class="bor10 p-lr-15 p-t-15 p-b-20 m-l-15 m-r-15 m-lr-0-xl p-lr-15-sm">
                                       <h4 class="mtext-109 cl2 p-b-30">
                                          Resumen
                                       </h4>
                                       <div class="flex-w flex-t bor12 p-b-10">
                                          <div class="size-208">
                                             <span  class="stext-110 cl2">Subtotal:</span>
                                          </div>

                                          <div class="size-209">
                                             <span class="mtext-110 cl2"><?= formatMoney($subtotal) ?></span>
                                          </div>
                                       </div>
                                       <div class="flex-w flex-t bor12 bor12 p-b-10 p-t-20">
                                          <div class="size-208">
                                             <span class="stext-110 cl2">  Envio:  </span>
                                          </div>
                                          <div class="size-209">
                                             <span class="mtext-110 cl2 shipping_monto"> </span>
                                          </div>
                                       </div>
                                       <div class="flex-w flex-t p-b-10 p-t-20">
                                          <div class="size-208">
                                             <span class="mtext-101 cl2">Total:</span>
                                          </div>

                                          <div class="size-209 p-t-1">
                                             <span id="totalCompra" class="mtext-110 cl2 total_monto"></span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-sm-7 m-lr-auto m-b-25">
                                    <div class="bor10 p-lr-15 p-t-15 p-b-20 m-l-15 m-r-15 m-lr-0-xl p-lr-15-sm">
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
                                                   <p class="container p-t-10 p-b-10"   ><?= $tPagos['ce']['detalle']['ceDetalle'] ?></p>
                                                </div>
                                                <button type="" id="ceButton" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">Pagar</button>
                                             </div>
                                          <?php } if (!empty($tPagos['tb'])) { ?>
                                             <div id="tbDiv" class="metodopagodiv notBlock" >
                                                <div class="">
                                                   <h5><?= $tPagos['tb']['nombre_tpago'] ?></h5>

                                                   <p><?= $tPagos['tb']['detalle']['tbDescripcion'] ?></p>
                                                   <p class="container p-t-10 p-b-10"  ><?= $tPagos['tb']['detalle']['tbDetalle'] ?></p>
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
                                                   <p>Para completar la transacción, te enviaremos a un espacio seguro desde los servidores seguros de PayPal.</p>
                                                </div>
                                                <hr>
                                                <!--contenedor de botones paypal-->
                                                <div id="paypal-button-container"></div>
                                             </div>
                                          <?php } if (!empty($tPagos['mp'])) { ?>
                                             <div id="mpDiv"class="metodopagodiv notBlock" >
                                                <div><p>Para completar la transacción, te enviaremos a los servidores seguros de MercadoPago.</p></div>
                                                <hr>
                  <!-- <input type="hidden" id="data-preference-id" name="data-preference-id" value="<?php //$preference->id;                                                              ?>"><!-- este elemento estara oculto y su funcion es setear el id del rol a actualizar -->
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
                              <ul class="list-inline pull-right">
                                 <li><button type="button" class="default-btn prev-step">Back</button></li>
                                 <!-- <li><button type="button" class="default-btn next-step skip-btn">Skip</button></li>
                                 <li><button type="button" class="default-btn next-step">Fin</button></li> -->
                              </ul>
                           </div>
                           <div class="clearfix"></div>
                        </div>
                     </div>
                  </div>
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
               <?php //print TERMINOS_Y_CONDICIONES; ?>  
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

