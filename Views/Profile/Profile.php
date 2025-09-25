<!DOCTYPE html>
<?php
headerTienda($data);
?>

<!-- Title page -->
<!--<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('<?= DIR_MEDIA ?>images/bg-01.jpg');">
   <h2 class="ltext-105 cl0 txt-center">
      About
   </h2>
</section>	-->
<!-- Datos Personales -->
<section class="bg-transparent p-t-25 p-b-15 ">
  <div class="container">

    <div class="row bg0">
      <div class="order-md-1 col-3 col-md-3 col-lg-2 m-lr-auto p-b-30 p-t-15 p-lr-15 p-lr-5-lg">
        <div class="how-bor2">
          <div class="hov-img0 bor7">
            <img class="user-img" src="<?= DIR_MEDIA . $_SESSION['userData']['foto_user'] ?>">
          </div>
        </div>
      </div>
      <div class="order-md-2 col-md-9 col-lg-10 p-b-30">
        <div class="p-t-7 p-lr-25 p-lr-15-lg p-l-0-md">
          <h3 class="mtext-111 cl2 p-b-16">
            <?= $_SESSION['userData']['nombres'] . " " . $_SESSION['userData']['apellidos'] ?>
          </h3>

          <div class="content">
            <h5> Datos Personales <button class="btn btn-info btn-sm" type="button" onClick="mostrarModalFrofile()" title="Editar Usuario">
                <i class="fa fa-pencil"></i></button>
            </h5>
            <div class="row">
              <div class="col-md-6">
                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <td style="width:150px;">Identificacion:</td>
                      <td><?= $_SESSION['userData']['identificacion'] ?></td>
                    </tr>
                    <!--<tr>
                       <td>Nombre(s)</td>
                       <td ><?= $_SESSION['userData']['nombres'] ?></td>
                    </tr>
                    <tr>
                       <td>Apellido(s):</td>
                       <td><?= $_SESSION['userData']['apellidos'] ?></td>
                    </tr>-->
                    <tr>
                      <td>Telefono:</td>
                      <td><?= $_SESSION['userData']['telefono'] ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-md-6">
                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <td>Email:</td>
                      <td><?= $_SESSION['userData']['email_user'] ?></td>
                    </tr>
                    <!--<tr>
                       <td>Tipo de Usuario</td>
                       <td><?= $_SESSION['userData']['nombrerol'] ?></td>
                    </tr>-->
                    <tr>
                      <td>Miembro desde</td>
                      <td><?= $_SESSION['userData']['datecreated'] ?></td>
                    </tr>

                  </tbody>
                </table>
              </div>
            </div>

          </div>


        </div>
      </div>
    </div>
  </div>
</section>	

<!-- Pedidos -->
<section class="bg-transparent p-t-20 p-b-25">
  <div class="container">
    <div class="flex-w flex-tr">
      <div class="size-210 bg0 bor10 p-lr-35 p-t-55 p-b-35 p-lr-15-lg w-full-md col-md-3">
        <div class="tile p-0">
          <ul class="nav flex-column nav-tabs user-tabs">
            <li class="nav-item"><a class="nav-link active" href="#user-datospersonales" data-toggle="tab">Ultimos pedidos</a></li>
            <li class="nav-item"><a class="nav-link" href="#user-settings" data-toggle="tab">Datos Fiscales</a></li>
            <li class="nav-item"><a class="nav-link" href="#user-timeline" data-toggle="tab">Pagos</a></li>

          </ul>
        </div>
      </div>

      <div class="size-210 bg0 bor10 flex-w flex-col-m p-lr-50 p-tb-30 p-lr-15-lg w-full-md col-md-9">
        <div class="tab-content">
          <div class="tab-pane active" id="user-datospersonales">
            <div class="timeline-post">
              <div class="post-media">

                <div class="col-md-12">
                  <div class="tile">
                    <!--                              <h3 class="tile-title"> Ultimos pedidos</h3>-->

                    <table class="table-shopping-cart bor10">
                      <thead>
                        <tr class="table_head">
                          <th class="column-1">#</th>
                          <th class="column-2">Cliente</th>
                          <th class="column-3">Estado</th>
                          <th class="column-4 text-right">Monto</th>
                          <th class="column-5">Ver</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if (count($data['tbPedidos']) > 0) {
                          foreach ($data['tbPedidos'] as $pedido) {
                            ?>               
                            <tr>
                              <td class="text-center"><?= $pedido['idpedido'] ?></td>
                              <td><?= $pedido['nombre'] ?></td>
                              <td><?= $pedido['status'] ?></td>
                              <td class="text-right"><?= formatMoney($pedido['monto']) ?></td>
                              <td class="text-center"><a href="<?= base_url() . 'pedidos/orden/' . $pedido['idpedido'] ?>" target="blank"> <i class=" fa fa-eye" aria-hidden="true"></i> </a></td>
                            </tr>
                            <?php
                          }
                        }
                        ?>  
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="user-settings">
            <div class="tile user-settings">
              <h4 class="line-head">Datos Fiscales</h4>

              <form id="formDataFiscal" name="formDataFiscal">

                <div class="row mb-4">
                  <div class="col-md-4">
                    <label href="#txtNit" >Identificacion Tributaria</label>
                    <input class="form-control" type="text" id="txtNit" name="texNit" value="<?= $_SESSION['userData']['nit'] ?>">
                  </div>
                  <div class="col-md-4">
                    <label href="#txtNombreFiscal" >Nombre Fiscal</label>
                    <input class="form-control" type="text" id="txtNombreFiscal" name="txtNombreFiscal" value="<?= $_SESSION['userData']['nombrefiscal'] ?>">
                  </div>
                  <div class="col-md-4">
                    <label href="#txtDirFiscal" >Direccion Fiscal</label>
                    <input class="form-control" type="text" id="txtDirFiscal" name="txtDirFiscal" value="<?= $_SESSION['userData']['direccionfiscal'] ?>">
                  </div>
                </div>

                <div class="row mb-10">
                  <div class="col-md-12">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Save</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="tab-pane afade" id="user-timeline">
            <div class="timeline-post">
              <div class="post-media">
                <div class="content">
                  <h5><a href="#">John Doe</a></h5>
                  <p class="text-muted"><small>2 January at 9:30</small></p>
                </div>
              </div>
              <div class="post-content">
                <p>EN DESARROLLO</p>
              </div>

            </div>

          </div>
        </div>


      </div>
    </div>
  </div>
</section>	

<?= include_once 'modalPerfil.php' ?>
<?= footerTienda($data) ?>