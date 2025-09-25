<?php
headerAdmin($data);

$empresa = $data['empresa'];

if ($data['transaccion']) {
  $trs = $data['transaccion']->purchase_units[0];
  $cte = $data['transaccion']->payer;

  $idtransaccion = $trs->payments->captures[0]->id;
  $fecha = $trs->payments->captures[0]->create_time;
  $estado = $trs->payments->captures[0]->status;
  $monto = $trs->payments->captures[0]->amount->value;
  $moneda = $trs->payments->captures[0]->amount->currency_code;

  /* datos clientes */
  $nombreCte = $cte->name->given_name . ' ' . $cte->name->surname;
  $emailCte = $cte->email_address;
  if (!empty($cte->phone->phone_number->national_number)) {
    $telfCte = $cte->phone->phone_number->national_number;
  }
  $cod_cdad = $cte->address->country_code;
  $direccion1 = $trs->shipping->address->address_line_1;
  $direccion2 = $trs->shipping->address->admin_area_2;
  $direccion3 = $trs->shipping->address->admin_area_1;
  $cod_postal = $trs->shipping->address->postal_code;

//email comercio
  $email_comercio = $trs->payee->email_address;

//descripcion
  $descripcion = $trs->description;
  $montoDetalle = $trs->amount->value;

//detalle de montos
  $importe_bruto = $trs->payments->captures[0]->seller_receivable_breakdown->gross_amount->value;
  $comision = $trs->payments->captures[0]->seller_receivable_breakdown->paypal_fee->value;
  $importe_neto = $trs->payments->captures[0]->seller_receivable_breakdown->net_amount->value;

//REEMBOLSO
  $reembolso = false;
  if (isset($trs->payments->refunds)) {
    $reembolso = true;

    $fecha_reembolso = $trs->payments->captures[0]->update_time;
    $importe_bruto_reembolso = $trs->payments->refunds[0]->seller_payable_breakdown->gross_amount->value;
    $comision_reembolso = $trs->payments->refunds[0]->seller_payable_breakdown->paypal_fee->value;
    $importe_neto_reembolso = $trs->payments->refunds[0]->seller_payable_breakdown->net_amount->value;
  }
}
?>
<div id="divModalTrans"></div>
<div class="container-fluid" >
  <div class="row" >
    <div class="col-12" >
      <div class="card" >
        <div class="card-body" >
          <?php
          if ($data['transaccion']) {
            ?>               
            <section id="sTransaccion" class="invoice">
              <div class="row mb-4">

                <div class="col-6">
                  <h2 class="page-header"><img src="<?= DIR_MEDIA ?>images/img-ico-pp.png"  alt="alt"/></h2>
                </div>
                <div class="col-6 text-right">
                  <?php
                  if (!$reembolso) {
                    if ($_SESSION['userData']['rolid'] != '2') {
                      ?>
                      <button class="btn btn-outline-primary" onclick="fntTransaccion('<?= $idtransaccion ?>')"><i class="fa fa-reply-all"> Hacer Reembolso</i> </button>
                      <?php
                    }
                  }
                  ?>
                </div>
              </div>
              <!-- Datos de la empresa -->
              <div class="row invoice-info">
                <div class="col-4">
                  <address>
                    <strong><?= $idtransaccion ?></strong><br>
                    <br>Fecha: <?= $fecha ?>
                    <br> Estado: <strong> <?= $estado ?> </strong>
                    <br>Importe Bruto: <?= $monto ?>
                  </address>
                </div>
                <div class="col-4">
                  <strong>Enviado por:</strong><br>
                  <br>Nombre: <?= $nombreCte ?>
                  <br>Email: <?= $emailCte ?>
                  <?php if (!empty($cte->phone->phone_number->national_number)) { ?>
                    <br>Email: <?= $telfCte ?>
                  <?php } ?>
                  <br>Direccion: <?= $direccion1 ?>
                  <?= $direccion2 . ' ' . $direccion3 . ' ' . $cod_postal ?>
                  <?= $cod_cdad ?>
                </div>
                <div class="col-4"> 
                  <strong>Enviado a:</strong> <br>
                  <br>Email: <?= $email_comercio ?>                                    
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-sm">
                    <thead class="thead-light">
                      <tr>
                        <th class="text-left">Detalle pedido</th>
                        <th class="text-right">Cantidad</th>
                        <th class="text-right">Precio</th>
                        <th class="text-right">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-left"> <?= $descripcion ?>  </td>                    
                        <td class="text-right"> 1 </td>                    
                        <td class="text-right"> <?= $monto . ' ' . $moneda ?> </td>                    
                        <td class="text-right">  <?= $monto . ' ' . $moneda ?></td>                    
                      </tr>  
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="3" class="text-right "> Total de la compra </th>                    
                        <td class="text-right"> <?= $monto . ' ' . $moneda ?></td>  <hr>                  
                    </tr>
                    </tfoot>
                  </table>

                  <?php
                  if ($reembolso) {
                    if ($_SESSION['userData']['rolid'] != '2') {
                      ?>

                      <table class="table table-sm">
                        <thead class="thead-light">
                          <tr>
                            <th class="text-left">Movimiento de Reembolso</th>
                            <th class="text-right">Importe Bruto</th>
                            <th class="text-right">Comision</th>
                            <th class="text-right">Importe Neto</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="text-left"> <?= $fecha_reembolso . ' Reembolso para ' . $nombreCte ?> </td>                    
                            <td class="text-right"> <?= $importe_bruto_reembolso . ' ' . $moneda ?> </td>                    
                            <td class="text-right"> <?= $comision_reembolso . ' ' . $moneda ?> </td>                    
                            <td class="text-right">  <?= $importe_neto_reembolso . ' ' . $moneda ?></td> <hr>                 
                        </tr>  

                        </tbody>    
                      </table>
                      <table class="table table-sm">
                        <thead>
                          <tr>
                            <th colspan="2">Detalle del Pago</th>
                          </tr>                                                                       
                        </thead>
                        <tbody>
                          <tr>
                            <td width="250"><strong>Total de la Compra</strong></td>
                            <td><?= $importe_bruto . ' ' . $moneda ?></td>
                          </tr>  
                          <tr>
                            <td width="250"><strong>Comision de Paypal</strong></td>
                            <td><?= $comision . ' ' . $moneda ?></td>
                          </tr>  
                          <tr>
                            <td width="250"><strong>Importe Neto</strong></td>
                            <td><?= $importe_neto . ' ' . $moneda ?></td>
                          </tr>  
                        </tbody>
                      </table>

                      <?php
                    }
                  }
                  ?>
                </div>
              </div>
              <div class="row d-print-none mt-2">
                <div class="col-12 text-right"><a class="btn btn-primary" href="javascript:window.print('#sPedido');" ">
                    <i class="fa fa-print"></i> Imprimir</a></div>
              </div>
            </section>
            <?php
          } else {
            echo 'Datos no encontrados';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php footerAdmin($data)
?>   