<!-- Modal Nuevo Producto - Edit Producto - Extra Large modal-->
<div class="modal fade " id="modalFormProducto" tabindex="-1" role="dialog" aria-labelledby="modalProducto" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen modal-fullscreen-xxl-down ">
    <div class="modal-content">

      <div class="modal-header headerRegister">
        <h5 class="modal-title" >Producto</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div><!-- /.modal-header -->

      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >

        <div class="row">
          <div class="col-md-12">
            <div id="addproduct-accordion" class="custom-accordion">
              <form id="formProducto" name="formProducto">

                <div class="card"><!-- Card Detalle del producto -->
                  <a href="#addproduct-producto-collapse" class="text-dark" data-bs-toggle="collapse" aria-expanded="false" aria-haspopup="true" aria-controls="addproduct-producto-collapse"> 
                    <div class="p-2">
                      <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar">
                            <div class="avatar-title rounded-circle bg-soft-primary text-primary">01</div>
                          </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                          <h5 class="font-size-16 mb-0">Información del Producto</h5>
                          <p class="text-muted text-truncate mb-0">Complete toda la información a continuación, los campos con asterisco (<span class="required">*</span>)</p>
                        </div>
                        <div class="flex-shrink-0"><i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i></div>
                      </div>
                    </div>
                  </a>

                  <div id="addproduct-producto-collapse" class="collapse "show data-bs-parent="#addproduct-accordion" style="">
                    <div class="p-2 border-top">

                      <div class="row"> 
                        <input type="hidden" id="idProducto" name="idProducto" value=""><!-- este elemento estara oculto y su funcion es setear el id del Producto a actualizar -->
                        <div class="col-xl-10">
                          <div class="row"> 
                            <div class="mb-3">
                              <label class="form-label" for="txtNombre">Nombre Producto <span class="required">*</span></label>
                              <input id="txtNombre" name="txtNombre" placeholder="Introduzca el nombre del producto" type="text" class="form-control" autocomplete="off" required="" >
                            </div>
                          </div> 
                          <div class="row"> 
                            <div class="col-md-2">
                              <div class="mb-2">
                                <label class="form-label" for="txtStock">Stock Inicial</label>
                                <input id="txtStock" name="txtStock" step="0" type="number" autocomplete="off" required="" class="form-control">
                              </div>
                            </div>             
                            <div class="col-md-4">
                              <div class="mb-3">
                                <label class="form-label" for="txtMarca">Marca</label>
                                <input id="txtMarca" name="txtMarca" type="text" class="form-control">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-3">
                              <div class="mb-4">
                                <label for="listProveedor" class="form-label">Proveedor</label>
                                <select class="form-select" data-trigger name="listProveedor" id="listProveedor" required=""> </select><!--data-live-search="true"-->
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="mb-4">
                                <label for="listCategoria" class="form-label">Categoria</label>
                                <select class="form-select" data-trigger name="listCategoria" id="listCategoria" required=""> </select><!--data-live-search="true"-->
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="mb-2">
                                <label for="listStatus" class="form-label">Estado</label>
                                <select class="form-select" data-trigger name="listStatus" id="listStatus">
                                  <option value="0"> Inactivo</option>
                                  <option value="1"> Activo</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="mb-2">
                                <label class="form-label">Compartir</label>
                                <div class="form-control" data-trigger id="opcionesCompartir" >
                                </div>
                              </div>
                            </div>
                          </div>
                        </div> 
                        <div class="col-xl-2">
                          <div class="row">
                            <div class="form-group">
                              <label class="control-label">Codigo </label>
                              <input class="form-control" id="txtCodigo" name="txtCodigo" type="number" placeholder="Codigo de Barras">
                              <div id="divBarCode" class="notBlock text-center">
                                <div id="printCode"> <img id="barcode" alt="Text margin" ></img></div>
                                <button class="btn btn-success btn-sm" type="button" onclick="fntPrintBarCode('#printCode')"><i class="fas fa-print"></i> Imprimir </button>
                              </div>
                            </div>
                          </div>
                        </div> 
                      </div>
                      <div class="row"> 
                        <div class="col-md-12">
                          <div class="mb-0 ">
                            <label class="form-label" >Descripcion <span class="required">*</span></label>
                            <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="6" maxlength="300"> </textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row"> 
                        <div class="col-md-12">
                          <div class="mb-3">
                            <label class="form-label" for="txtEtiquetas">Palabras Clave <span class="required">*</span></label>
                            <input id="txtEtiquetas" name="txtEtiquetas" placeholder="Separa las etiquetas con coma ( , ) type="text" class="form-control" >
                          </div> 
                        </div>
                      </div>
                    </div>
                  </div>
                </div> <!--End Card Producto-->

                <div class="card"><!-- Card Precio -->
                  <a href="#addproduct-precio-collapse" class="text-dark collapsed" data-bs-toggle="collapse" aria-expanded="false" aria-haspopup="true" aria-controls="addproduct-precio-collapse">
                    <div class="p-2">
                      <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar">
                            <div class="avatar-title rounded-circle bg-soft-primary text-primary">02</div>
                          </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                          <h5 class="font-size-16 mb-0">Precio</h5>
                          <p class="text-muted text-truncate mb-0">Precio Costo y Precio Venta </p>
                        </div>
                        <div class="flex-shrink-0">
                          <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                        </div>
                      </div>
                    </div>
                  </a>
                  <div id="addproduct-precio-collapse" class="collapse" data-bs-parent="#addproduct-accordion">
                    <div class="p-2 border-top">

                      <div class="row">
                        <div class="col-md-2">
                          <div class="mb-3">
                            <label class="form-label" >Dolar Hoy</label>
                            <input id="dolarHoy" name="manufacturerbrand" step="0.01" type="number" class="form-control" disabled value="<?= $_SESSION['dolarhoy']['precio'] ?>">
                          </div>
                        </div>
                        <div class="col-sm-2">
                          <div class="mb-3">
                            <label class="form-label" for="porcentaje">% sobre el Costo</label>
                            <input id="porcentaje" step="0.01" type="number" class="form-control" autocomplete="off">
                          </div>
                        </div>
                      </div>

                      <div class="row">

                        <div class="col-md-3 ">
                          <div class="col-10">
                            <div class="mb-3">
                              <label class="form-label" for="CostoPeso">Costo Pesos</label>
                              <input id="costoPeso" name="costoPeso" step="0" type="number" class="form-control" autocomplete="off">
                            </div>
                          </div>
                          <div class="col-sm-10">
                            <div class="mb-2">
                              <label class="form-label" for="costoUSD">Costo USD$ <span class="required">*</span></label>
                              <input id="costoUSD" name="costoUSD" step="0.01" type="number" class="form-control" autocomplete="off">
                            </div>
                          </div>  
                        </div>

                    <div class="col-md-3 ">
                          <div class="col-10">
                            <div class="mb-2">
                              <label class="form-label" for="diferenciaPeso">Diferencia Peso <span class="required">*</span></label>
                              <input id="diferenciaPeso" step="0.01" type="number" class="form-control" autocomplete="off">
                            </div>
                          </div>  
                          <div class="col-sm-10">
                            <div class="mb-3">
                              <label class="form-label" for="diferenciaDolar">Diferencia USD</label>
                              <input id="diferenciaDolar" step="0.01" type="number" class="form-control" autocomplete="off">
                            </div>
                          </div>
                        </div>

                        <div class="col-md-3 ">
                          <div class="col-10">
                            <div class="mb-3">
                              <label class="form-label" for="precioPeso">Precios Pesos</label>
                              <input id="precioPeso" name="precioPeso" step="0" type="number" class="form-control" autocomplete="off">
                            </div>
                          </div>
                          <div class="col-sm-10">
                            <div class="mb-2">
                              <label class="form-label" for="precioUSD">Precio USD$ <span class="required">*</span></label>
                              <input id="precioUSD" name="precioUSD" step="0.01" type="number" class="form-control" autocomplete="off">
                            </div>
                          </div>  
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card"><!-- Card Detalle del producto -->
                  <a href="#addproduct-detalle-collapse" class="text-dark collapsed" data-bs-toggle="collapse" aria-expanded="false" aria-haspopup="true" aria-controls="addproduct-detalle-collapse">
                    <div class="p-2">
                      <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar">
                            <div class="avatar-title rounded-circle bg-soft-primary text-primary">02</div>
                          </div>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                          <h5 class="font-size-16 mb-0">Informacion Detallada del Producto</h5>
                          <p class="text-muted text-truncate mb-0">Agrega todo el detalle e informacion del producto </p>
                        </div>
                        <div class="flex-shrink-0">
                          <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                        </div>
                      </div>
                    </div>
                  </a>
                  <div id="addproduct-detalle-collapse" class="collapse" data-bs-parent="#addproduct-accordion">
                    <div class="p-2 border-top">
                      <div class="mb-0">
                        <label class="form-label" >Detalle del Producto</label>
                        <textarea class="form-control" id="txtDetalle" name="txtDetalle" placeholder="Descipcion detallada del producto" rows="10"></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </form>


              <div class="card"><!-- Card IMG del producto -->
                <div class="p-3"><!-- Card IMG del producto -->
                  <div class="form-group col-md-12">
                    <div id="containerGallery">
                      <span>Agregar foto (1200 x 1486 o -30%)</span>
                      <button class="btnAddImage btn btn-info btn-sm" type="button"> <i class="fa fa-plus"></i></button>
                    </div>
                    <hr>
                    <div id="containerImages">
                    </div>
                  </div>
                  <div class="row"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Pie de formulario para imagenes -->

      </div><!-- /.modal-body -->

      <div class="row" >
        <div class="col-4 m-auto" id="pagina_prev" ><button class="page-item page-link pull-left" >«</button></div>
        <div class="col-2 m-auto align-items-center text-center" id="pagina_poss" ><span class="text-primary align-text-center" > 5 /10 </span> </div>
        <div class="col-3 m-auto " id="pagina_prox" ><button class=" page-item page-link pull-right" >»</button></div>
        <div class="col-3 modal-footer pe-4">
          <button id="btnActionForm" form="formProducto" type="submit" class="btn btn-primary btn-block" ><i class="fa fa-check-circle" aria-hidden="true"></i><span id="btnText">Guardar</span></button>
          &nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->
          <button class="btn btn-danger btn-block" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i>Cerrar</button>
        </div><!-- /.modal-footer -->
      </div>
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->






<!-- Modal Ver Producto-->
<div class="modal fade " id="modalVerProducto" tabindex="-1" role="dialog" aria-labelledby="modalVerProducto" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-fullscreen-md-down modal-dialog-scrollable ">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" >Producto</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div><!-- /.modal-header -->

      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >
        <div class="card">
          <div class="p-3">
            <!--Tabla ver datos -->
            <table class="table table-bordered">
              <tbody>
                <tr> <td>Codigo: </td> <td id="celCodigo">11222333</td> </tr>
                <tr> <td>Nombre: </td> <td id="celNombre">@fat</td> </tr>
                <tr> <td>Precio: </td> <td id="celPrecio">@fat</td> </tr>
                <tr> <td>Stock: </td> <td id="celStock">@fat</td> </tr>
                <tr> <td>Categoria: </td> <td id="celCategoria">xxxxxx</td> </tr>
                <tr> <td>Proveedor: </td> <td id="celProveedor">xxxxxx</td> </tr>
                <tr> <td>Compartir: </td> <td id="opcionesCompartir2">xxxxxx</td> </tr>
                <tr> <td>Descripcion: </td> <td id="celDescripcion">xxxxxx</td> </tr>
                <tr> <td>Foto de referencia: </td> <td id="celFotos2" class="dis-flex flex-wrap">xxxxxx</td> </tr>
              </tbody>
            </table>

            <div class="tab-content p-3 text-muted">
              <div class="tab-pane active" id="popularity" role="tabpanel">
                <div  id="celFotos" class="dis-flex flex-wrap" >
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