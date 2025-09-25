<?php
$usuario = $data['usuario'];
$orden = $data['pedido'];
$tiposdepago = $data['tiposdepago'];
$estados = ESTADOS_PEDIDOS;
?>
<!-- Modal Nuevo Pedido - Edit Pedido-->
<div class="modal fade " id="modalFormPedido" tabindex="-1" role="dialog" aria-labelledby="Formulario de nuevo Pedido" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down ">
    <div class="modal-content">

      <div class="modal-header headerUpdate">
        <h5 class="modal-title" >Actualizar Estado de Pedido</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"  aria-label="Close"></button>
      </div><!-- /.modal-header -->

      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >

        <div class="card">
          <!--Formulario Crear-Editar Pedido  -->
          <form id="formUpdatePedido" name="formUpdatePedido">
            <input type="hidden" id="idPedido" name="idPedido" value="<?= $orden['idpedido'] ?>"><!-- este elemento estara oculto y su funcion es setear el id del Pedido a actualizar -->
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td width="210"> N°. Pedido : </td>
                  <td>10</td>
                </tr>
                <tr>
                  <td>Cliente : </td>
                  <td><?= $usuario['nombres'] . ' ' . $usuario['apellidos'] ?></td>
                </tr>
                <tr>
                  <td>Importe :</td>
                  <td><?= formatMoney($orden['monto']) ?></td>
                </tr>

                <tr>
                  <td>tipo de Pago</td>
                  <td> 
                    <?php
                    if ($orden['tipopago'] == 'pp') {
                      $orden['transaccionid'];
                    } else {
                      ?>                              
                      <select id="listTpoPago" name="listTpoPago" class="form-select selectpicker" data-live-search='true' required="">
                        <?php
                        foreach ($tiposdepago as $tpoPag) {
                          $selectec = '';
                          if ($tpoPag['tipopago'] == $orden['tipopago']) { // seleccionamos el estado 
                            $selectec = 'selected';
                          }
                          ?>
                          <option value="<?= $tpoPag['tipopago'] ?>"<?= $selectec ?> >
                            <?= $tpoPag['nombre_tpago']; ?></option>
                        <?php } ?>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Transaccion : </td>
                  <td> <?php
                    if ($orden['tipopago'] == 'pp' |
                        $orden['tipopago'] == 'mp') {
                      $orden['transaccionid'];
                    } else {
                      ?>  <input type="text" id="txtTransaccion" name="txtTransaccion" class="form-control" value=""> 
                    <?php } ?>
                  </td>
                </tr>
                <tr>
                  <td>Estado</td>
                  <td> 
                    <select id="listEstado" name="listEstado" class="form-select selectpicker" data-live-search='true' required="">
                      <?php
                      foreach (ESTADOS_PEDIDOS as $estado_pedido) {
                        $selectec = $estado_pedido === $orden['status'] ? $selectec = 'selected' : '';
                        ?>
                        <option value="<?= $estado_pedido ?>" <?= $selectec ?> >   <?= $estado_pedido; ?></option>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>      
      </div>
      <div class="modal-footer">
        <button id="btnActionForm" class="btn btn-info" type="submit" > 
          <i class="fa fa-fw fa-lg fa-check-circle"></i> <span> Actualizar </span> </button> &nbsp;&nbsp;&nbsp;

        <button  class="btn btn-danger" type="button" data-bs-dismiss="modal" >
          <i class="fa fa-fw fa-lg fa-times-circle"></i> Cerrar </button>
      </div>
    </div>
  </div>
</div>

