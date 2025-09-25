<?php
headerAdmin($data);
$empresa = $data['empresa'];
?>
<div class="container-fluid" >
  <div class="row" >
    <div class="col-12" >
      <div class="card" >
        <div class="card-body" >
          <?php
          if (!empty($data['pedido']['pedido'])) {
            $orden = $data['pedido']['pedido']; //                    dep($orden);
            $usuario = $data['pedido']['usuario']; //                    dep($usuario);
            $detalle = $data['pedido']['detalle']; //
            ?>
            <section id="sPedido" class="invoice">
              <div class="row mb-4">
                <div class="col-6">
                  <h2 class="page-header"><img src="<?= $data['logo_desktop'] ?>" width="30%" height="30%" alt="alt"/></h2>
                </div>
                <div class="col-6">
                  <h5 class="text-right">Fecha: <?= $orden['fecha'] ?></h5>
                </div>
              </div>
              <!-- Datos de la empresa -->
              <div class="row invoice-info">
                <div class="col-4">
                  <address><strong><?= $empresa['nombre_fiscal'] ?>.</strong>
                    <br>Dir: <?= $empresa['direccion'] ?>
                    <br> Telefono: <?= $empresa['telefono'] ?>
                    <br>Email: <?= $empresa['email'] ?></address>
                </div>
                <div class="col-4">
                  <address><strong><?= $usuario['nombres'] . ' ' . $usuario['apellidos'] ?></strong>
                    <br>Envio: <?= $orden['direccionenvio'] ?>
                    <br>Tlefono: <?= $usuario['telefono'] ?>
                    <br>Email: <?= $usuario['email_user'] ?></address>
                </div>
                <div class="col-4"><b> Orden: <?= rellena($orden['idpedido']) ?></b>
                  <br>Estado: <b><?= $orden['status'] ?></b>
                  <br>Medio de Pago: <?= $orden['tipopago'] ?>
                  <br><?php if ($orden['tipopagoid'] != 2) { ?>
                    Transaccion: <?= $orden['transaccionid']; ?>
                  <?php } ?> 
                  <br>Monto: <?= formatMoney($orden['monto']) ?></div>
              </div>
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th class="text-left">Descripcion</th>
                        <th class="text-center">Precio</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Importe</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php foreach ($detalle as $item) { ?>

                        <tr>
                          <td> <a target="_blank" href="<?= base_url() . 'tienda/producto/' . $item['productoid'] . '/' . $item['ruta']; ?>"><?= $item['nombre']; ?></a>  </td>                    
                          <td class="text-center"> <?= formatMoney($item['precio']); ?> </td>                    
                          <td class="text-center"> <?= $item['cantidad']; ?> </td>                    
                          <td class="text-center">  <?= formatMoney($item['precio'] * $item['cantidad']); ?></td>                    
                        </tr>  
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="3" class="text-right "> SubTotal: </th>                    
                        <td class="text-center"> <?= formatMoney($orden['subtotal']); ?></td>                    
                      </tr>
                      <tr>
                        <th colspan="3" class="text-right "> Envio: </th>                    
                        <td class="text-center"> <?= formatMoney($orden['costo_envio']); ?></td>                    
                      </tr>
                      <tr>
                        <th colspan="3" class="text-right "> Total: </th>                    
                        <td class="text-center"> <?= formatMoney($orden['monto']); ?></td>                    
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
              <div class="row d-print-none mt-2">
                <div class="col-12 text-right">
                  <a class="btn btn-danger " href="<?= base_url() . 'factura/generarFactura/' . $orden['idpedido'] ?>" 
                     target="_blanck" title="Generar PDF"><i class="fa fa-file-pdf-o"></i>Imprimir</a>
                  <a class="btn btn-info" href="" onclick="reenviarEmail(<?= $orden['idpedido'] ?>)" ">
                    <i class="fa fa-envelope"></i> Reenviar Mail</a>

                  <a class="btn btn-primary" href="javascript:window.print('#sPedido')" >
                    <!--                                <a class="btn btn-primary" href="#" onclick="printPantalla('#sPedido')">-->
                    <i class="fa fa-print"></i> Imprimir</a>
                </div>
              </div>
            </section>
            <?php
          } else {
            echo 'Orden no Encontrada';
            ;
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php footerAdmin($data) ?>   