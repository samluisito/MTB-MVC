<?php headerAdmin($data) ?>
<div class="container-fluid" >
  <div class="row" >
    <div class="col-12" >
      <div class="card" >
        <div class="card-body" >
          <div class="table-responsive"> <!-- Data Table -->
            <table class="table table-hover table-bordered display" style="width:100%" height:100%"  id="tablePedidos">
              <thead>
                <tr>
                  <th scope="col" class="align-middle">Id</th>                                    
                  <th scope="col" class="align-middle">Fecha</th>
                  <th scope="col" class="align-middle">Ref. / transaccion</th>
                  <th scope="col" class="align-middle">Monto</th>
                  <th scope="col" class="align-middle">Tipo de Pago</th>
                  <th scope="col" class="align-middle" >Estado</th>
                  <th scope="col" class="text-center align-middle sorting sorting_asc" >                
                    <?php if ($_SESSION['userPermiso'][$data["modulo"]]['crear'] == 1) { ?>
                      <!--Boton Nuevo-->
                      <button class="btn btn-success waves-effect waves-light m-auto" type="button" onclick="nvoPedido()">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo</button>
                    <?php } ?></th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div><!-- End Data Table -->
        </div>
      </div>
    </div>
  </div>
</div>

<div id="divModal"></div>
<?= footerAdmin($data) ?>   