<?php
headerAdmin($data);
$f_visit_desde = $data['visit_rango_f']['desde'];
$f_visit_hasta = $data['visit_rango_f']['hasta'];
?>
<script>const f_visit_desde = new Date("<?= $f_visit_desde ?>");</script>
<script>const f_visit_hasta = new Date("<?= $f_visit_hasta ?>");</script>

<div class="container-fluid">
  <!--Chart Invoice Overview-->
  <!--
    <div class="row">
      <div class="col-xl-8">
        <div class="card">
          <div class="card-body pb-2">
            <div class="d-flex align-items-start mb-4 mb-xl-0">
              <div class="flex-grow-1">
                <h5 class="card-title">Invoice Overview</h5>
              </div>
              <div class="flex-shrink-0">
                <div class="dropdown">
                  <a class="dropdown-toggle text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="fw-semibold">Sort By:</span> <span class="text-muted">Yearly<i class="mdi mdi-chevron-down ms-1"></i></span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#">Yearly</a>
                    <a class="dropdown-item" href="#">Monthly</a>
                    <a class="dropdown-item" href="#">Weekly</a>
                    <a class="dropdown-item" href="#">Today</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="row align-items-center">
              <div class="col-xl-4">
                <div class="card bg-light mb-0">
                  <div class="card-body">
                    <div class="py-2">
                      <h5>Total Revenue:</h5>
                      <h2 class="mt-4 pt-1 mb-1">$9,542,00</h2>
                      <p class="text-muted font-size-15 text-truncate">From Jan 20,2022 to July,2022</p>
                      <div class="d-flex mt-4 align-items-center">
                        <div id="mini-1" data-colors='["--bs-success"]' class="apex-charts"></div>
                        <div class="ms-3">
                          <span class="badge bg-danger"><i class="mdi mdi-arrow-down me-1"></i>16.3%</span>
                        </div>
                      </div>
                      <div class="row mt-4">
                        <div class="col">
                          <div class="d-flex mt-2">
                            <i class="mdi mdi-square-rounded font-size-10 text-success mt-1"></i>
                            <div class="flex-grow-1 ms-2 ps-1">
                              <h5 class="mb-1">3,526,56</h5>
                              <p class="text-muted text-truncate mb-0">Net Profit</p>
                            </div>
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex mt-2">
                            <i class="mdi mdi-square-rounded font-size-10 text-primary mt-1"></i>
                            <div class="flex-grow-1 ms-2 ps-1">
                              <h5 class="mb-1">5,324,85</h5>
                              <p class="text-muted text-truncate mb-0">Net Revenue</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-8">
                <div>
                  <div id="column_chart" data-colors='["--bs-primary", "--bs-primary-rgb, 0.2"]' class="apex-charts" dir="ltr"></div>  
                </div>
              </div>
            </div>
  
          </div>
  
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-start">
              <div class="flex-grow-1">
                <h5 class="card-title mb-2">Order Stats</h5>
              </div>
              <div class="flex-shrink-0">
                <div class="dropdown">
                  <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Monthly<i class="mdi mdi-chevron-down ms-1"></i>
                  </a>
  
                  <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#">Yearly</a>
                    <a class="dropdown-item" href="#">Monthly</a>
                    <a class="dropdown-item" href="#">Weekly</a>
                    <a class="dropdown-item" href="#">Today</a>
                  </div>
                </div>
              </div>
            </div>--> 
  <!--Chart--><!--
  <div id="chart-donut" data-colors='["--bs-primary", "--bs-success","--bs-danger"]' class="apex-charts" dir="ltr"></div>
  <div class="mt-1 px-2">
    <div class="order-wid-list d-flex justify-content-between border-bottom">
      <p class="mb-0"><i class="mdi mdi-square-rounded font-size-10 text-primary me-2"></i>Order Completed</p>
      <div>
        <span class="pe-5">56,236</span>
        <span class="badge bg-primary"> + 0.2% </span>
      </div>
    </div>
    <div class="order-wid-list d-flex justify-content-between border-bottom">
      <p class="mb-0"><i class="mdi mdi-square-rounded font-size-10 text-success me-2"></i>Order Processing</p>
      <div>
        <span class="pe-5">12,596</span>
        <span class="badge bg-success"> - 0.7% </span>
      </div>
    </div>
    <div class="order-wid-list d-flex justify-content-between">
      <p class="mb-0"><i class="mdi mdi-square-rounded font-size-10 text-danger me-2"></i>Order Cancel</p>
      <div>
        <span class="pe-5">1,568</span>
        <span class="badge bg-danger"> + 0.4% </span>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>  --> <!--end row-->

  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pb-2">
          <div class="row">

            <div class="col-md-3">
              <div class="row mb-3">
                <span class="fw-semibold" >Pais:</span> 
                <select class="form-select " id="selectVisitasPais"> <!--onchange=""-->
                  <?PHP
                  foreach ($data['visit_pais'] as $pais) {
                    $selected = $pais == $_SESSION['base']['region']?'selected':''; 
                    echo "<option value = '{$pais}' $selected > {$pais}</option>";
                  }
                  ?>

                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>   <!--end row-->

    <!--Chart Visitas - Visitantes-->
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body pb-2">
            <div class="d-flex align-items-start mb-4 mb-xl-0">
              <div class="flex-grow-1">
                <h5 class="card-title">Total Visitas y Visitantes</h5>
              </div>
              <div class="flex-shrink-0">
                <div class="row">


                  <div class="col-md-5">
                    <div class="row mb-3">
                      <span class="fw-semibold" >Mostrar por:</span> 
                      <select class="form-select " id="selectVisitasMostrarPor" onchange="visitMesMostrarpor()">
                        <option value="m">Mes</option>
                        <option value="s">Semana</option>
                        <!--<option value="p">Periodo</option>-->
                      </select>
                    </div>
                  </div>

                  <div class="col-md-5">
                    <div class="row mb-3">
                      <span class="fw-semibold" id="labelVisitas">Mes</span> 
                      <input class="form-control" id="dateVisitas"  name="dateVisitas" 
                             min="<?= date("Y-m", strtotime($f_visit_desde)) ?>" 
                             max="<?= date("Y-m", strtotime($f_visit_hasta)) ?>" 
                             type="month" onchange="btnSearchVisitasMesAnio()" value="<?php echo date("Y-m"); ?>">
                    </div>
                  </div>

                  <div class="col-md-1">
                    <div class="row mb-3">
                      <button type="button" class="btnVisitMes btn btn-info btn-sm mx-1 " onclick="btnSearchVisitasMesAnio()"><i class="fa fa-search"></i></button>
                    </div>
                  </div>

                </div>

              </div>
            </div>

            <div class="row align-items-center">
              <div class="col-xl-12 px-2">
                <div id="Chart-Line-Data-Labels-Visitantes-Visitas" data-colors='["--bs-primary", "--bs-success","--bs-danger"]' class="apex-charts" dir="ltr"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!--end row-->

    <!--Chart Visitas por pagina-->
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body pb-2">
            <div class="d-flex align-items-start mb-4 mb-xl-0">
              <div class="flex-grow-1">
                <h5 class="card-title">Paginas Visitadas</h5>
              </div>
              <div class="flex-shrink-0">
                <div class="row">

                  <div class="col-md-5">
                    <div class="row mb-3">
                      <span class="fw-semibold" >Mostrar por:</span> 
                      <select class="form-select " id="selectVisitPorPag" onchange="selectVisitPorPag()">
                        <option value="m">Mes</option>
                        <option value="s">Semana</option>
                        <option value="d">Dia</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-5">
                    <div class="row mb-3">
                      <span class="fw-semibold" id="labelVisitPorPag">Mes</span> 
                      <input class="form-control" id="dateVisitPorPag"  name="dateVisitPorPag" 
                             min="<?= date("Y-m", strtotime($f_visit_desde)) ?>" 
                             max="<?= date("Y-m", strtotime($f_visit_hasta)) ?>" 
                             type="month" onchange="btnSearchVisitPorPag()" value="<?php echo date("Y-m"); ?>">
                    </div>
                  </div>

                  <div class="col-md-1">
                    <div class="row mb-3">
                      <button type="button" class="btnVisitMes btn btn-info btn-sm mx-1 " onclick="btnSearchVisitPorPag()"><i class="fa fa-search"></i></button>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <div class="row align-items-center">
              <div class="col-xl-12">
                <div id="Chart-Visitas-por-pagina" data-colors='["--bs-primary", "--bs-success","--bs-danger"]' class="apex-charts" dir="ltr"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!--end row-->
    <!--Chart Visitas por Region-->
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body pb-2">
            <div class="d-flex align-items-start mb-4 mb-xl-0">
              <div class="flex-grow-1">
                <h5 class="card-title">Visitas por Region</h5>
              </div>
              <div class="flex-shrink-0">
                <div class="row">

                  <div class="col-md-5">
                    <div class="row mb-3">
                      <span class="fw-semibold" >Mostrar por:</span> 
                      <select class="form-select " id="selectVisitPorRegion" onchange="selectVisitPorRegion()">
                        <option value="m">Mes</option>
                        <option value="s">Semana</option>
                        <option value="d">Dia</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-5">
                    <div class="row mb-3">
                      <span class="fw-semibold" id="labelVisitPorRegion">Mes</span> 
                      <input class="form-control" id="dateVisitPorRegion"  name="dateVisitPorRegion" 
                             min="<?= date("Y-m", strtotime($f_visit_desde)) ?>" 
                             max="<?= date("Y-m", strtotime($f_visit_hasta)) ?>" 
                             type="month" onchange="btnSearchVisitPorRegion()" value="<?php echo date("Y-m"); ?>">
                    </div>
                  </div>

                  <div class="col-md-1">
                    <div class="row mb-3">
                      <button type="button" class="btnVisitMes btn btn-info btn-sm mx-1 " onclick="btnSearchVisitPorRegion()"><i class="fa fa-search"></i></button>
                    </div>
                  </div>

                </div>


              </div>
            </div>

            <div class="row align-items-center">
              <div class="col-xl-6">
                <div id="Chart-VisitPorRegion" data-colors='["--bs-primary", "--bs-success","--bs-danger"]' class="apex-charts" dir="ltr"></div>
              </div>
              <div class="col-xl-6">
                <div id="Chart-VisitPorLocalidad" data-colors='["--bs-primary", "--bs-success","--bs-danger"]' class="apex-charts" dir="ltr"></div>
              </div>
            </div>
            <div class="row align-items-center">

            </div>
          </div>
        </div>
      </div>
    </div><!--end row-->

    <!--Chart Dolar Peso-->
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body pb-2">
            <div class="d-flex align-items-start mb-4 mb-xl-0">
              <div class="flex-grow-1">
                <h5 class="card-title">Invoice Overview</h5>
              </div>
              <div class="flex-shrink-0">
                <div class="dropdown">
                  <a class="dropdown-toggle text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="fw-semibold">Sort By:</span> <span class="text-muted">Yearly<i class="mdi mdi-chevron-down ms-1"></i></span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#">Yearly</a>
                    <a class="dropdown-item" href="#">Monthly</a>
                    <a class="dropdown-item" href="#">Weekly</a>
                    <a class="dropdown-item" href="#">Today</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="row align-items-center">
              <div class="col-xl-3">
                <div class="card bg-light mb-0">
                  <div class="card-body">
                    <div class="py-2">
                      <h5>Maximo del periodo:</h5>
                      <!--<h2 class="mt-4 pt-1 mb-1">$9,542,00</h2>-->
                      <p id="fecha_maximo_dia_blue" class="text-muted font-size-15 text-truncate">From Jan 20,2022 to July,2022</p>
                      <div class="row mt-4">
                        <div class="col">
                          <div class="d-flex mt-2">
                            <i class="mdi mdi-square-rounded font-size-10 text-success mt-1"></i>
                            <div class="flex-grow-1 ms-2 ps-1">
                              <p class="text-muted text-truncate mb-0">Blue Compra</p>
                              <h5 id="data_compra_blue" class="mb-1">3,526,56</h5>
                            </div>
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex mt-2">
                            <i class="mdi mdi-square-rounded font-size-10 text-primary mt-1"></i>
                            <div class="flex-grow-1 ms-2 ps-1">
                              <p class="text-muted text-truncate mb-0">Blue Venta</p>                            
                              <h5 id="data_venta_blue"class="mb-1">5,324,85</h5>
                            </div>
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex mt-2">
                            <i class="mdi mdi-square-rounded font-size-10 text-success mt-1"></i>
                            <div class="flex-grow-1 ms-2 ps-1">
                              <p class="text-muted text-truncate mb-0">Oficial Compra</p>
                              <h5 id="data_compra_oficial"class="mb-1">5,324,85</h5>
                            </div>
                          </div>
                        </div>
                        <div class="col">
                          <div class="d-flex mt-2">
                            <i class="mdi mdi-square-rounded font-size-10 text-primary mt-1"></i>
                            <div class="flex-grow-1 ms-2 ps-1">
                              <p class="text-muted text-truncate mb-0">Oficial Venta</p>
                              <h5 id="data_venta_oficial"class="mb-1">5,324,85</h5>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-9">
                <div>
                  <div id="Chart-Line-Data-Labels-Dolar-Peso-Periodo" data-colors='["--bs-primary", "--bs-success","--bs-danger"]' class="apex-charts" dir="ltr"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> 

  </div>
  <!--End-container-fluid--> 
  <?= footerAdmin($data); ?>
