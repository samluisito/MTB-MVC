<?= headerAdmin($data) ?>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-dashboard"></i> <?= $data["page_title"] ?></h1>

    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url() . 'dashboard' ?>"><i class="fa fa-home fa-lg"></i></a></li>
      <li class="breadcrumb-item"><a href="<?= base_url() . $data['page_name'] ?>"><?= $data['page_name'] ?></a></li>
    </ul>
  </div>
  <div class="row">
    <?php if ($_SESSION['userPermiso'][1]['ver'] == 1) { ?>
      <div class="col-md-6 col-lg-3">
        <a href="<?= base_url() . 'Usuarios' ?>" class="linkWind">
          <div class="widget-small primary coloured-icon"><i class="icon fa fa-line-chart fa-3x"></i>
            <div class="info">
              <h4>Visitas</h4>
              <p><b><?= $data['wVisitas'] ?></b></p>
            </div>
          </div>
        </a>
      </div>
    <?php } ?>  
    <?php if ($_SESSION['userPermiso'][3]['ver'] == 1) { ?>
      <div class="col-md-6 col-lg-3">
        <a href="<?= base_url() . 'clientes' ?>" class="linkWind">
          <div class="widget-small info coloured-icon"><i class="icon fa fa-user fa-3x"></i>
            <div class="info">
              <h4>Clientes</h4>
              <p><b><?= $data['wClientes'] ?></b></p>
            </div>
          </div>
        </a>
      </div>
    <?php } ?>  
    <?php if ($_SESSION['userPermiso'][4]['ver'] == 1) { ?>
      <div class="col-md-6 col-lg-3">
        <a href="<?= base_url() . 'productos' ?>" class="linkWind">
          <div class="widget-small warning coloured-icon"><i class="icon fa fa-archive fa-3x"></i>
            <div class="info">
              <h4>Productos</h4>
              <p><b><?= $data['wProductos'] ?></b></p>
            </div>
          </div>
        </a>
      </div>
    <?php } ?>  
    <?php if ($_SESSION['userPermiso'][5]['ver'] == 1) { ?>
      <div class="col-md-6 col-lg-3">
        <a href="<?= base_url() . 'pedidos' ?>" class="linkWind">
          <div class="widget-small danger coloured-icon"><i class="icon fa fa-shopping-cart fa-3x"></i>
            <div class="info">
              <h4>Pedidos</h4>
              <p><b><?= $data['wPedidos'] ?></b></p>
            </div>
          </div>
        </a>
      </div>
    <?php } ?>  

  </div>
  <div class="row">
    <?php if ($_SESSION['userPermiso'][5]['ver'] == 1) { ?>
      <div class="col-md-6">
        <div class="tile">
          <h3 class="tile-title"> Ultimos pedidos</h3>

          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th class="text-right">Monto</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php
              if (count($data['tbPedidos']) > 0) {
                foreach ($data['tbPedidos'] as $pedido) {
                  ?>               
                  <tr>
                    <td><?= $pedido['idpedido'] ?></td>
                    <td><?= $pedido['nombre'] ?></td>
                    <td><?= $pedido['status'] ?></td>
                    <td class="text-right"><?= formatMoney($pedido['monto']) ?></td>
                    <td><a href="<?= base_url() . 'pedidos/orden/' . $pedido['idpedido'] ?>" target="blank"> <i class=" fa fa-eye" aria-hidden="true"></i> </a></td>
                  </tr>
                  <?php
                }
              }
              ?>  
            </tbody>
          </table>
        </div>
      </div>
    <?php } ?>  
    <div class="col-md-6">
      <div class="tile">
        <div class="container-title">
          <h3 class="tile-title"> Tipo de Pago mes año</h3>
          <div class="dflex">
            <input  class="date-picker pagoMes" name="pagoMes" placeholder="Mes y año" minlength="4" maxlength="7"  value="<?php echo date("m-Y"); ?>" >
            <button type="button" class="btnTipoPagoMes btn btn-info btn-sm" onclick="btnSearchPagoMesAnio()"><i class="fa fa-search"  ></i></button>
          </div>
        </div>
        <figure class="highcharts-figure">
          <div id="pagoMesAnio"></div>
        </figure>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="tile">
        <div class="container-title">
          <h3 class="tile-title"> Ventas por mes </h3>
          <div class="dflex">
            <input  class="date-picker ventasMes" name="ventasMes" placeholder="Mes y año" minlength="4" maxlength="7" value="<?php echo date("m-Y"); ?>" >
            <button type="button" class="btnVentasMes btn btn-info btn-sm" onclick="btnSearchVentasMesAnio()"><i class="fa fa-search"></i></button>
          </div>
        </div>
        <figure class="highcharts-figure">
          <div id="ventasPorMes"></div>
        </figure>
      </div>
    </div>
    <div class="col-md-6">
      <div class="tile">
        <div class="container-title">
          <h3 class="tile-title"> Ventas por año </h3>
          <div class="dflex">
            <input  type="year" class="ventasAnualPorMes" name="ventasAnualPorMes" placeholder="año" minlength="4" maxlength="4" onkeypress=" enterSearchVentasAnualPorMes(event); return controlTag(event)"  value="<?= date("Y") ?>" >
            <button type="button" class="btnVentasAnualPorMes btn btn-info btn-sm" onclick="btnSearchVentasAnualPorMes()"> <i class="fa fa-search"></i></button>
          </div>
        </div>
        <figure class="highcharts-figure">
          <div id="ventasPorAnio"></div>
        </figure>
      </div>
    </div>
  </div>
  <div class="row">    
    <div class="col-md-6">
      <div class="tile">
        <div class="container-title">
          <h3 class="tile-title"> Visitantes y Visitas por mes </h3>
          <div class="dflex">
            <input  class="date-picker visitMes" name="visitMes" placeholder="Mes y año" minlength="4" maxlength="7" value="<?php echo date("m-Y"); ?>" >
            <button type="button" class="btnVisitMes btn btn-info btn-sm" onclick="btnSearchVisitasMesAnio()"><i class="fa fa-search"></i></button>
          </div>
        </div>
        <figure class="highcharts-figure">
          <div id="chartVisitaVisitante"></div>
        </figure>
      </div>
    </div>
  </div>

</main>
<?= footerAdmin($data) ?>   

