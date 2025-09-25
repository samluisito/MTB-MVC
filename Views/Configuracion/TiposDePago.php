<?= headerAdmin($data) ?>
<?php
$empresa = $data['empresa'];

$ce = $data['tpos_pago']['ce'];
$tb = $data['tpos_pago']['tb'];
$pp = $data['tpos_pago']['pp'];
$mp = $data['tpos_pago']['mp'];
?>
<div class="container-fluid" >
  <div class="row" >
    <div class="col-12" >
      <div class="card" >
        <div class="card-header justify-content-between d-flex align-items-center">
          <h4 class="card-title">Configuracion</h4>
        </div>

        <div class="card-body"> 
          <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
            <li class="nav-item "><a class="nav-link container-title active" data-bs-toggle="tab" aria-selected="true" href="#tpoContraEntrega"><i class="fas fa-money" aria-hidden="true"></i> &nbsp;<?= $ce['nombre_tpago'] ?></a></li>
            <li class="nav-item "><a class="nav-link container-title " data-bs-toggle="tab" aria-selected="false" href="#tpoTransferencia"><i class="fas fa-exchange-alt" aria-hidden="true"></i> &nbsp;<?= $tb['nombre_tpago'] ?></a></li>
            <li class="nav-item "><a class="nav-link container-title " data-bs-toggle="tab" aria-selected="false" href="#tpoPayPal"> <i class="fab fa-paypal" aria-hidden="true"> &nbsp;<?= $pp['nombre_tpago'] ?></i></a></li>
            <li class="nav-item "><a class="nav-link container-title " data-bs-toggle="tab" aria-selected="false" href="#tpoMercadoPago"><img src="<?= DIR_MEDIA ?>/images/mercadopago-icon-1.png" width="16" height="16" alt="mercadopago"/> &nbsp;&nbsp;<?= $mp['nombre_tpago'] ?></a></li>
          </ul>
          <form method="POST" id="formTiposDePago" name="formTiposDePago">
            <div class="tab-content p-3 text-muted">
              <div class="tab-pane active" role="tabpanel" id="tpoContraEntrega">
                <input type="hidden" id="ceContraEntregaId" name="ceContraEntregaId" value="<?= $ce['idtipopago'] ?>"><!-- este elemento estara oculto y su funcion es setear el id del Producto a actualizar -->
                <p class="text-primary">los campos con asterisco (<span class="required">*</span>)</p>
                <div class="row">
                  <div class="col-md">
                    <div class="mb-3">
                      <div class=" toggle-flip">
                        <label> <input type="checkbox" id="ceCheck" name="ceCheck" <?= $ce['status'] ? 'value="1" checked="checked"' : 'value="0"'; ?> />
                          <span onclick="fntCheckTP('ceCheck')" class="flip-indecator" data-toggle-on="Activo" data-toggle-off="Inactivo"></span></label>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label class="control-label" ><font style="vertical-align: inherit;">Descripcion <span class="required">*</span></font></label>
                      <input type="text" class="form-control" id="ceDescripcion"name="ceDescripcion"type="text" value="<?= $ce['detalle']['ceDescripcion'] ?>" >
                    </div>
                    <div class="mb-3">
                      <label class="control-label"><font style="vertical-align: inherit;">Detalle de Pagos <?= $ce['nombre_tpago'] ?> <span class="required">*</span></font></label>
                      <textarea class="form-control form-text" id="ceDetalle" name="ceDetalle" rows="5" ><?= $ce['detalle']['ceDetalle'] ?></textarea>
                    </div>
                  </div>
                </div> 
              </div>
              <div class="tab-pane" role="tabpanel" id="tpoTransferencia">
                <input type="hidden" id="tbTransferenciaId" name="tbTransferenciaId" value="<?= $tb['idtipopago'] ?>"><!-- este elemento estara oculto y su funcion es setear el id del Producto a actualizar -->
                <p class="text-primary">los campos con asterisco (<span class="required">*</span>)</p>

                <div class="row">
                  <div class="col-md">
                    <div class="mb-3">
                      <div class=" toggle-flip">
                        <label> <input type="checkbox" id="tbCheck" name="tbCheck" <?= $tb['status'] ? 'value="1" checked="checked"' : 'value="0"'; ?> />
                          <span onclick="fntCheckTP('tbCheck')" class="flip-indecator" data-toggle-on="Activo" data-toggle-off="Inactivo"></span></label>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label class="control-label" ><font style="vertical-align: inherit;">Descripcion del Banco <span class="required">*</span></font></label>
                      <input type="text" class="form-control" id="tbDescripcion"name="tbDescripcion"type="text" value="<?= $tb['detalle']['tbDescripcion'] ?>" >
                    </div>
                    <div class="mb-3">
                      <label class="control-label"><font style="vertical-align: inherit;">Detalle de la cuenta bancaria <span class="required">*</span></font></label>
                      <textarea class="form-control form-text" id="tbDetalle" name="tbDetalle" rows="5" ><?= $tb['detalle']['tbDetalle'] ?></textarea>
                    </div>
                  </div>
                </div> 
              </div> 
              <div class="tab-pane" role="tabpanel" id="tpoPayPal">
                <div class="row">
                  <div class="col-md">
                    <input type="hidden" id="ppPayPalId" name="ppPayPalId" value="<?= $pp['idtipopago'] ?>"><!-- este elemento estara oculto y su funcion es setear el id del Producto a actualizar -->
                    <p class="text-primary">los campos con asterisco (<span class="required">*</span>)</p>
                    <div class="mb-3">
                      <div class="toggle-flip">
                        <label> <input type="checkbox" id="ppCheck" name="ppCheck" <?= $pp['status'] ? 'value="1" checked="checked"' : 'value="0"' ?> />
                          <span onclick="fntCheckTP('ppCheck')" class="flip-indecator" data-toggle-on="Activo" data-toggle-off="Inactivo"></span></label>
                      </div>
                    </div>

                    <div class="mb-3">
                      <label class="control-label"><font style="vertical-align: inherit;">Cleinte ID <span class="required"></span></font></label>
                      <input type="text" class="form-control" id="ppClienteID"name="ppClienteID"type="text" value="<?= $pp['detalle']['ppClienteID'] ?>" >
                    </div>

                    <div class="mb-3">
                      <label class="control-label"><font style="vertical-align: inherit;">Secret <span class="required"></span></font></label>
                      <input type="text" class="form-control" id="ppSecret" name="ppSecret" value="<?= $pp['detalle']['ppSecret'] ?>" >
                    </div>
                    <div class="row">
                      <div class="col-md-3">

                        <div class="mb-3">
                          <label class="control-label"><font style="vertical-align: inherit;">Currency <span class="required"></span></font></label>
                          <input type="tel" class="form-control" id="ppCurrency" name="ppCurrency" value="<?= $pp['detalle']['ppCurrency'] ?>" >
                        </div>
                      </div>
                      <div class="col-md-9">
                        <div class="mb-3">

                          <label class="control-label"><font style="vertical-align: inherit;">Entorno <span class="required"></span></font></label>
                          <select class="form-select"id="ppEntorno" name="ppEntorno" value="" > >
                            <option <?= $mp['detalle']['mpEntorno'] == 'https://api-m.sandbox.paypal.com' ? 'selected=""' : '' ?> value="https://api-m.sandbox.paypal.com">Sandbox</option>
                            <option <?= $mp['detalle']['mpEntorno'] == 'https://api-m.paypal.com' ? 'selected=""' : '' ?> value="https://api-m.paypal.com">Produccion</option>
                          </select>

                        </div>
                      </div>
                    </div>
                  </div>


                </div> 
              </div>
              <div class="tab-pane" role="tabpanel" id="tpoMercadoPago">
                <div class="row">
                  <div class="col-md">
                    <input type="hidden" id="mpMercadoPagoId" name="mpMercadoPagoId" value="<?= $mp['idtipopago'] ?>"><!-- este elemento estara oculto y su funcion es setear el id del Producto a actualizar -->
                    <p class="text-primary">los campos con asterisco (<span class="required">*</span>)</p>
                    <div class="mb-3">
                      <div class="toggle-flip">

                        <label> <input type="checkbox" id="mpCheck" name="mpCheck" <?= $mp['status'] ? 'value="1" checked="checked"' : 'value="0"'; ?>/>
                          <span onclick="fntCheckTP('mpCheck')" class="flip-indecator" data-toggle-on="Activo" data-toggle-off="Inactivo"></span></label>
                      </div>
                    </div>

                    <div class="mb-3">
                      <label class="control-label"><font style="vertical-align: inherit;">Aplicacion <span class="required"></span></font></label>
                      <input type="text" class="form-control" id="mpAplicacion"name="mpAplicacion"type="text" value="<?= $mp['detalle']['mpAplicacion'] ?>" >
                    </div>
                    <div class="mb-3">
                      <label class="control-label"><font style="vertical-align: inherit;">Publick Key <span class="required"></span></font></label>
                      <input type="text" class="form-control" id="mpPublickKey"name="mpPublickKey"type="text" value="<?= $mp['detalle']['mpPublickKey'] ?>" >
                    </div>

                    <div class="mb-3">
                      <label class="control-label"><font style="vertical-align: inherit;">Acces Tocken <span class="required"></span></font></label>
                      <input type="text" class="form-control" id="mpAccesTocken" name="mpAccesTocken" rows="6" value="<?= $mp['detalle']['mpAccesTocken'] ?>" >
                    </div>
                    <div class="mb-3">
                      <label class="control-label"><font style="vertical-align: inherit;">Cleinte ID <span class="required"></span></font></label>
                      <input type="text" class="form-control" id="mpClienteID"name="mpClienteID"type="text" value="<?= $mp['detalle']['mpClienteID'] ?>" >
                    </div>

                    <div class="mb-3">
                      <label class="control-label"><font style="vertical-align: inherit;">Cleinte Secret <span class="required"></span></font></label>
                      <input type="text" class="form-control" id="mpCleinteSecret" name="mpCleinteSecret" value="<?= $mp['detalle']['mpCleinteSecret'] ?>" >
                    </div>
                    <div class="row">
                      <div class="col-md-3">

                        <div class="mb-3">
                          <label class="control-label"><font style="vertical-align: inherit;">Currency <span class="required"></span></font></label>
                          <input type="tel" class="form-control" id="mpCurrency" name="mpCurrency" value="<?= $mp['detalle']['mpCurrency'] ?>">
                        </div>
                      </div>
                      <div class="col-md-9">
                        <div class="mb-3">

                          <label class="control-label"><font style="vertical-align: inherit;">Entorno <span class="required"></span></font></label>
                          <select class="form-select"id="mpEntorno" name="mpEntorno" value="" > >
                            <option <?= $mp['detalle']['mpEntorno'] == 'https://api-m.sandbox.paypal.com' ? 'selected=""' : '' ?>value="https://api-m.sandbox.paypal.com">Sandbox</option>
                            <option <?= $mp['detalle']['mpEntorno'] == 'https://api-m.paypal.com' ? 'selected=""' : '' ?>value="https://api-m.paypal.com">Produccion</option>
                          </select>

                        </div>
                      </div>
                    </div>
                  </div>


                </div> 
              </div>
            </div>
          </form>

        </div>
        <div class="card-footer">
          <button id="btnActionForm" type="submit" form="formTiposDePago" class="btn btn-primary btn-block" ><i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;Guardar</button>
          &nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->
          <button class="btn btn-danger btn-block" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cerrar</button>
        </div>

      </div>
    </div>
  </div>
</div>

<?= footerAdmin($data) ?> 