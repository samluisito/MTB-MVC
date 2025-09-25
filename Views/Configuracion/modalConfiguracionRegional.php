<!-- Modal Nuevo Region - Edit Region - Extra Large modal-->
<div class="modal fade " id="modalFormRegion" tabindex="-1" role="dialog" aria-labelledby="modalRegion" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable modal-fullscreen-xl-down ">
    <div class="modal-content">

      <div class="modal-header headerRegister d-flex justify-content-between">
        <div class=" px-2"><h5 class="modal-title" >Region</h5></div>
        <div><button type="button" class="btn btn-warning btnDuplicar" onclick="fntDuplicar()"><i class="fas fa-copy"></i>Duplicar</button></div>
        <div class="px-2"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
      </div><!-- /.modal-header -->

      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >

        <form id="formRegion" name="formRegion">

          <div class="row">
            <div class="col-12"><!-- col-xxl-10 col-xl-9 col-lg-8 -->

              <div class="card">
                <div class="card-header justify-content-between align-items-center"> <!-- d-flex-->
                  <div class="row"> 

                    <div class="mb-3 col-md-4">
                      <input type="hidden" id="idRegion" name="idRegion" value=""><!-- este elemento estara oculto y su funcion es setear el id del Region a actualizar -->
                      <label class="form-label" for="txtNombre">Nombre Region <span class="required">*</span></label>
                      <input id="txtNombre" name="txtNombre" placeholder="" type="text" class="form-control" autocomplete="off" required="" >
                    </div>

                    <div class="col-md-2">
                      <div class="mb-2">
                        <label for="txtAbrev" class="form-label">Abreveviatura</label>
                        <input id="txtAbrev" name="txtAbrev" placeholder="" type="text" class="form-control" autocomplete="off" required="" >
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="mb-2">
                        <label for="txtIdioma" class="form-label">Idioma</label>
                        <input id="txtIdioma" name="txtIdioma" placeholder="" type="text" class="form-control" autocomplete="off" required="" >
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="mb-2">
                        <label for="txtTimeZone" class="form-label">Time Zone</label>
                        <input id="txtTimeZone" name="txtTimeZone" placeholder="" type="text" class="form-control" autocomplete="off" required="" >
                      </div>
                    </div>
                  </div> <!--end row-->

                  <div class="row"> 
                    <div class="col-md-4">
                      <div class="mb-2">
                        <label for="txtMoneda" class="form-label">Moneda</label>
                        <input id="txtMoneda" name="txtMoneda" placeholder="" type="text" class="form-control" autocomplete="off" required="" >
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="mb-2">
                        <label for="txtFormatoMoneda" class="form-label">Formato</label>
                        <input id="txtFormatoMoneda" name="txtFormatoMoneda" placeholder="" type="text" class="form-control" autocomplete="off" required="" >
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="mb-2">
                        <label for="txtSimboloMoneda" class="form-label">Simbolo</label>
                        <input id="txtSimboloMoneda" name="txtSimboloMoneda" placeholder="" type="text" class="form-control" autocomplete="off" required="" >
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="mb-2">
                        <label for="txtSPM" class="form-label">SPM</label>
                        <input id="txtSPM" name="txtSPM" placeholder="" type="text" class="form-control" autocomplete="off" required="" >
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="mb-2">
                        <label for="txtSPD" class="form-label">SPD</label>
                        <input id="txtSPD" name="txtSPD" placeholder="" type="text" class="form-control" autocomplete="off" required="" >
                      </div>
                    </div>
                  </div><!--end row-->

                  <div class="row"> 
                    <div class="col-md-4">
                      <div class="mb-2">
                        <label for="txtUTC" class="form-label">Zona Horaria UTC</label>
                        <input id="txtUTC" name="txtUTC" placeholder="" type="text" class="form-control" autocomplete="off" required="" >
                      </div>
                    </div>  
                    <div class="col-md-4">
                      <div class="mb-2">
                        <label for="txtFormatoFecha" class="form-label">Formato de Fecha</label>
                        <input id="txtFormatoFecha" name="region_abrev" placeholder="" type="text" class="form-control" autocomplete="off" required="" >
                      </div>
                    </div>  
                  </div><!--end row-->

                </div><!-- end card header -->
              </div><!-- end card -->

            </div><!-- end col -->
            <!-- <div class="col-xxl-10 col-xl-9 col-lg-8"> </div> -->
          </div><!-- end row -->
        </form>



      </div><!-- /.modal-body -->

      <div class="row" >
        <div class="col-3 m-auto align-items-right text-right" id="pagina_prev" ><button class="page-item page-link pull-right" >«</button></div>
        <div class="col-2 m-auto align-items-center text-center" id="pagina_poss" ><span class="text-primary align-text-center" > 0 / 0 </span> </div>
        <div class="col-3 m-auto " id="pagina_prox" ><button class=" page-item page-link pull-right" >»</button></div>
        <div class="col-4 modal-footer pe-4">
          <button id="btnActionForm" form="formRegion" type="submit" class="btn btn-primary btn-block" ><i class="fa fa-check-circle" aria-hidden="true"></i><span id="btnText">Guardar</span></button>
          &nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->
          <button class="btn btn-danger btn-block" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i>Cerrar</button>
        </div><!-- /.modal-footer -->
      </div>
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->


<!-- Modal Ver Region-->
<div class="modal fade " id="modalVerRegion" tabindex="-1" role="dialog" aria-labelledby="modalVerRegion" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-fullscreen-md-down modal-dialog-scrollable ">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" >Region</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div><!-- /.modal-header -->

      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >
        <div class="card">
          <div class="p-3">
            <!--Tabla ver datos -->
            <table class="table table-bordered">
              <tbody>
                <tr> <td>ID Region: </td> <td id="verID">11222333</td> </tr>
                <tr> <td>Nombre Region: </td> <td id="verRegion">@fat</td> </tr>
                <tr> <td>Abreveviatura: </td> <td id="verAbvrev">@fat</td> </tr>
                <tr> <td>Idioma: </td> <td id="verIdioma">@fat</td> </tr>
                <tr> <td>Time Zone: </td> <td id="verTimeZone">xxxxxx</td> </tr>
                <tr> <td>Moneda: </td> <td id="verMoneda">xxxxxx</td> </tr>
                <tr> <td>Formato: </td> <td id="verMonedaFormato">xxxxxx</td> </tr>
                <tr> <td>Simbolo: </td> <td id="verMonedaSimbolo">xxxxxx</td> </tr>
                <tr> <td>SPM: </td> <td id="verMonedaSPM">xxxxxx</td> </tr>
                <tr> <td>SPD: </td> <td id="verMonedaSPD">xxxxxx</td> </tr>
                <tr> <td>Zona Horaria UTC: </td> <td id="verUTC">xxxxxx</td> </tr>
                <tr> <td>Formato de Fecha: </td> <td id="verFechaFormato">xxxxxx</td> </tr>
              </tbody>
            </table>

            <div class="tab-content p-3 text-muted">
              <div class="tab-pane active" id="popularity" role="tabpanel">
                <div id="celFotos" class="dis-flex flex-wrap" >
                </div>
                <!-- end row -->
              </div>

            </div>

          </div>
        </div>
      </div>

      <div class="row " >
        <div class="col-4 m-auto" id="pagina_prev2" ><button class="page-item page-link pull-left" >«</button></div>
        <div class="col-2 m-auto align-items-center text-center" id="pagina_poss2" ><span class="text-primary align-text-center" > 5 /10 </span> </div>
        <div class="col-4 m-auto" id="pagina_prox2" ><button class=" page-item page-link pull-right" >»</button></div>
        <div class="col-2 modal-footer pe-3">
          &nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->
          <button class="btn btn-danger btn-block" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i>Cerrar</button>
        </div><!-- /.modal-footer -->
      </div>
    </div>
  </div>
</div>