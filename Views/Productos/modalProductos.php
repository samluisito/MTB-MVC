<!-- Modal Nuevo Producto - Edit Producto - Extra Large modal-->
<div class="modal fade " id="modalFormProducto" tabindex="-1" role="dialog" aria-labelledby="modalProducto" aria-hidden="true">
  <div class="modal-dialog modal-xxl modal-dialog-centered modal-dialog-scrollable modal-fullscreen-xl-down ">
    <div class="modal-content">

      <div class="modal-header headerRegister d-flex justify-content-between">
        <div class=" px-2"><h5 class="modal-title" >Producto</h5></div>
        <div><button type="button" class="btn btn-warning btnDuplicar" onclick="fntDuplicar()"><i class="fas fa-copy"></i>Duplicar</button></div>
        <div class="px-2"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
      </div><!-- /.modal-header -->

      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >

        <form id="formProducto" name="formProducto">

          <div class="row">
            <div class="col-12"><!-- col-xxl-10 col-xl-9 col-lg-8 -->

              <div class="card">
                <div class="card-header justify-content-between align-items-center"> <!-- d-flex-->
                  <div class="row"> 
                    <div class="mb-3 col-md-7">
                      <input type="hidden" id="idProducto" name="idProducto" value=""><!-- este elemento estara oculto y su funcion es setear el id del Producto a actualizar -->
                      <label class="form-label" for="txtNombre">Nombre Producto <span class="required">*</span></label>
                      <input id="txtNombre" name="txtNombre" placeholder="Introduzca el nombre del producto" type="text" class="form-control" autocomplete="off" required="" >
                    </div>
                    <div class="col-md-2">
                      <div class="mb-2">
                        <label for="listStatus" class="form-label">Estado</label>
                        <select class="form-select" data-trigger name="listStatus" id="listStatus">
                          <option value="1"> Activo</option>
                          <option value="0"> Inactivo</option>
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
                </div><!-- end card header -->
              </div><!-- end card -->

              <div class="card notBlock">
                <!--<div class="card-header justify-content-between d-flex align-items-center"> <h4 class="card-title">Accordion Example</h4></div> 
                Card oculto , la finalidad es usar los atributos end card header -->
                <div class="card-body">
                  <div class="accordion" id="accordionDescripcion">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                          Descripcion</button>
                      </h2>
                      <div id="collapseOne" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionDescripcion" style="">

                        <div class="accordion-body">
                          <div class="row">
                            <div class="col-md-2">
                              <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link mb-2" id="v-pills-detalle-tab" data-bs-toggle="pill" href="#v-pills-detalle" role="tab" aria-controls="v-pills-detalle" aria-selected="false">Detalle</a>
                              </div>
                            </div><!-- end col -->
                            <div class="col-md-10">

                              <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">



                                <div class="tab-pane " id="v-pills-detalle" role="tabpanel" aria-labelledby="v-pills-detalle-tab">
                                  <textarea class="form-control" id="txtDetalle" name="txtDetalle" rows="8"></textarea>
                                </div><!-- end col content -->

                              </div><!-- end tab-content -->
                            </div><!-- end col -->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div><!-- end card body -->
              </div>


              <div class="card">
                <!--<div class="card-header justify-content-between d-flex align-items-center">
                <h4 class="card-title">Vertical Nav Left Tabs</h4>
                </div> 
                end card header -->
                <div class="card-body bor1">

                  <div class="row">
                    <div class="col-md-2">
                      <div class="nav flex-column nav-pills" id="v-pills-tab-datos" role="tablist" aria-orientation="vertical">
                        <a class="nav-link mb-2 active" id="v-pills-descripcion-tab" data-bs-toggle="pill" href="#v-pills-descripcion" role="tab" aria-controls="v-pills-descripcion" aria-selected="true">Descripcion</a> 
                        <a class="nav-link mb-2" id="v-pills-categoria-tab" data-bs-toggle="pill" href="#v-pills-categoria" role="tab" aria-controls="v-pills-categoria" aria-selected="false">Categoria</a>
                        <a class="nav-link mb-2" id="v-pills-Precio-tab" data-bs-toggle="pill" href="#v-pills-Precio" role="tab" aria-controls="v-pills-Precio" aria-selected="false">Precio</a>
                        <a class="nav-link mb-2" id="v-pills-stock-tab" data-bs-toggle="pill" href="#v-pills-stock" role="tab" aria-controls="v-pills-stock" aria-selected="false">Stock</a>
                        <a class="nav-link mb-2" id="v-pills-origen-tab" data-bs-toggle="pill" href="#v-pills-origen" role="tab" aria-controls="v-pills-origen" aria-selected="false">Origen</a>
                        <a class="nav-link mb-2" id="v-pills-atributos-tab" data-bs-toggle="pill" href="#v-pills-atributos" role="tab" aria-controls="v-pills-atributos" aria-selected="false">Atributos</a>
                        <a class="nav-link mb-2" id="v-pills-tags-tab" data-bs-toggle="pill" href="#v-pills-tags" role="tab" aria-controls="v-pills-tags" aria-selected="false">Tags</a>
                        <!--<a class="nav-link mb-2" id="v-pills-envio-tab" data-bs-toggle="pill" href="#v-pills-envio" role="tab" aria-controls="v-pills-envio" aria-selected="false">Envio</a>-->
                      </div>
                    </div>
                    <div class="col-md-10">
                      <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tab-datosContent">

                        <div class="tab-pane fade active show" id="v-pills-descripcion" role="tabpanel" aria-labelledby="v-pills-descripcion-tab">
                          <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="6" maxlength="400"> </textarea>
                        </div><!-- end col content -->


                        <div class="tab-pane fade border p-2" id="v-pills-categoria" role="tabpanel" aria-labelledby="v-pills-categoria-tab">

                          <div class="row">
                            <div class="form-group">
                              <div class="mb-4">
                                <label for="listCategoria" class="form-label">Categoria</label>
                                <select class="form-select" data-trigger name="listCategoria" id="listCategoria" required=""> </select><!--data-live-search="true"-->
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-6">
                              <div class="mb-3">
                                <label for="listStatus" id="listCatFBLabel">Categoria Facebook <span class="required">*</span></label>
                                <select class="form-select" id="listCatFB"name="listCatFB" required>

                                  <option value="">  </option>

                                </select>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="mb-3">
                                <label for="listStatus" id="listCatGoogleLabel">Categoria Google <span class="required">*</span></label>
                                <select class="form-select" id="listCatGoogle"name="listCatGoogle" required>
                                  <option value="">  </option>

                                </select>
                              </div>
                            </div>
                          </div>

                        </div><!--end colapse vertical Categoria-->
                        <div class="tab-pane fade border p-2" id="v-pills-Precio" role="tabpanel" aria-labelledby="v-pills-Precio-tab">

                          <div class="row">

                          </div>

                          <div class="row bor1">
                            <div class="col-md-6">
                              <div class="row">
                                <div class="col-6">
                                  <div class="mb-2">
                                    <label class="form-label" for="costoPeso">Costo <?= SMONEY ?></label>
                                    <input id="costoPeso" name="costoPeso" step="0" type="number" class="form-control" autocomplete="off">
                                  </div>
                                </div>
                                <div class="col-6">
                                  <div class="mb-2">
                                    <label class="form-label" for="costoUSD">Costo USD <span class="required">*</span></label>
                                    <input id="costoUSD" name="costoUSD" step="0.01" type="number" class="form-control" autocomplete="off">
                                  </div>
                                </div> 
                              </div> 
                            </div> 
                            <div class="col-md-6">
                              <div class="row">
                                <div class="col-md-6 ">
                                  <div class="mb-2">
                                    <label class="form-label" for="dolarHoy">Dolar Hoy</label>
                                    <input id="dolarHoy" name="manufacturerbrand" step="0.01" type="number" class="form-control" disabled value="<?= getDolarHoy() ?>">
                                  </div>
                                </div> 
                              </div> 
                            </div> 
                          </div><!--end fila costo-->

                          <div class="row bor1">
                            <div class="col-md-6">
                              <div class="row">
                                <div class="col-6">
                                  <div class="mb-2">
                                    <label class="form-label" for="diferenciaPeso">Diferencia Peso <span class="required">*</span></label>
                                    <input id="diferenciaPeso" step="0.01" type="number" class="form-control" autocomplete="off">
                                  </div>
                                </div> 
                                <div class="col-6">
                                  <div class="mb-2">
                                    <label class="form-label" for="diferenciaDolar">Diferencia USD</label>
                                    <input id="diferenciaDolar" step="0.01" type="number" class="form-control" autocomplete="off">
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-6">
                              <div class="row">
                                <div class="col-6 col-md-6">
                                  <div class="mb-2">
                                    <label class="form-label" for="porcentaje">% sobre el Costo</label>
                                    <input id="porcentaje" step="0.01" type="number" class="form-control" autocomplete="off">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div><!--end fila ganancia-->


                          <div class="row">
                            <div class="col-md-6">
                              <div class="row">
                                <div class="col-6">
                                  <div class="mb-2">
                                    <label class="form-label" for="precioPeso">Precios <?= SMONEY ?></label>
                                    <input id="precioPeso" name="precioPeso" step="0" type="number" class="form-control" autocomplete="off">
                                  </div>
                                </div>
                                <div class="col-6">
                                  <div class="mb-2">
                                    <label class="form-label" for="precioUSD">Precio USD <span class="required">*</span></label>
                                    <input id="precioUSD" name="precioUSD" step="0.01" type="number" class="form-control" autocomplete="off">
                                  </div>
                                </div> 
                              </div> 
                            </div> 
                          </div><!--end fila precio venta-->

                          <div class="row">
                            <div class="col-md-6">
                              <div class="row">
                                <div class="col-6">
                                  <div class="mb-2">
                                    <label class="form-label" for="porcentaje">Oferta <?= SMONEY ?></label>
                                    <input id="ofertaPesos" step="0" type="number" class="form-control" autocomplete="off">
                                  </div>
                                </div>
                                <div class="col-6">
                                  <div class="mb-2">
                                    <label class="form-label" for="porcentaje">Oferta USD</label>
                                    <input id="ofertaDolar" name="ofertaDolar" step="0.01" type="number" class="form-control" autocomplete="off">
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="row">
                                <div class="col-6">
                                  <div class="mb-2">
                                    <label class="form-label" for="porcentaje">Fecha Inicio</label>
                                    <input class="form-control" type="date" value="" id="ofertaFechaInicio" name="oferta_f_ini"> </div>
                                </div>
                                <div class="col-6">
                                  <div class="mb-2">
                                    <label class="form-label" for="porcentaje">Fecha Fin</label><!-- 2019-08-19 -->
                                    <input class="form-control" type="date" value="" id="ofertaFechaFin" name="oferta_f_fin"> </div>
                                </div>
                              </div>
                            </div>
                          </div><!--end row Oferta-->

                        </div><!--end colapse vertical PRECIO-->


                        <div class="tab-pane fade border p-2" id="v-pills-stock" role="tabpanel" aria-labelledby="v-pills-stock-tab">
                          <div class="row"> 
                            <div class="col-md-2">
                              <div class="mb-2">
                                <label class="form-label" for="txtStock">Stock Inicial</label>
                                <input id="txtStock" name="txtStock" step="0" type="number" autocomplete="off" required="" class="form-control">
                              </div>
                            </div>
                          </div> 
                          <div class="row mb-2">
                            <label class="col-form-label" for="list_stock_status" >Estado de Stock</label>
                            <div class="col-md-3">
                              <select id="list_stock_status" name="stock_status" class="form-select">
                                <option value="instock" selected="selected">Hay stock</option>
                                <option value="onbackorder">Se puede reservar</option>	
                                <option value="outofstock">Agotado</option>
                              </select>
                            </div>
                          </div>

                        </div> <!--end colapse vertical Stock-->
                        <div class="tab-pane fade border p-2" id="v-pills-origen" role="tabpanel" aria-labelledby="v-pills-origen-tab">
                          <div class="row"> 

                            <div class="col-md-4">
                              <div class="mb-2">
                                <label class="form-label" for="txtMarca">Marca</label>
                                <input id="txtMarca" name="txtMarca" type="text" class="form-control">
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="mb-4">
                                <label for="listProveedor" class="form-label">Proveedor</label>
                                <select class="form-select" data-trigger name="listProveedor" id="listProveedor" required=""> </select><!--data-live-search="true"-->
                                <button type="button" class="btn btn-outline-success" onclick="fntVerProvee()"><i class="far fa-eye"></i></button>

                              </div>
                            </div>
                          </div>
                        </div> <!--end colapse vertical 0rigen-->

                        <div class="tab-pane fade border p-2" id="v-pills-atributos" role="tabpanel" aria-labelledby="v-pills-atributos-tab">
                          <div class="row">
                            <div class="form-group">
                              <div class="row">
                                <div class="form-group">
                                  <div class="row">
                                    <div class="col-md-4">
                                      <div class="mb-4">
                                        <label class="control-label" for="txtCodigo">Codigo de Barras </label>
                                        <input class="form-control" id="txtCodigo" name="txtCodigo" type="number" placeholder="Codigo de Barras">
                                        <div id="divBarCode" class="notBlock text-center">
                                          <div id="printCode"> <img id="codigo" alt="Text margin" ></img></div>
                                          <button class="btn btn-success btn-sm" type="button" onclick="fntPrintBarCode('#printCode')"><i class="fas fa-print"></i> Imprimir </button>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="mb-4">
                                        <label for="listGrupoEtario" class="form-label">Grupo Etario</label>
                                        <select class="form-select" data-trigger name="listGrupoEtario" id="listGrupoEtario" required=""> 
                                          <option value="T" selected="selected">Todas las Edades</option>
                                          <option value="A" >Adulto</option>	
                                          <option value="AD">Adolescente</option>	
                                          <option value="N" >Niños</option>	
                                          <option value="RN">Recién Nacido</option>	
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="mb-4">
                                        <label for="listGenero" class="form-label">Genero</label>
                                        <select class="form-select" data-trigger name="listGenero" id="listGenero" required=""> 
                                          <option value="U" selected="selected">Unisex</option>
                                          <option value="F">Femenino</option>	
                                          <option value="M">Masculino</option>
                                        </select>
                                      </div>
                                    </div>
                                  </div><!--end row-->
                                  <div class="row">
                                    <div class="col-md-3">
                                      <div class="mb-2">
                                        <label class="form-label" for="txtTalla">Talla</label>
                                        <input id="txtTalla" name="txtTalla" type="text" maxlength="100" class="form-control">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="mb-2">
                                        <label class="form-label" for="txtColor">Color</label>
                                        <input id="txtColor" name="txtColor" type="text" maxlength="200" class="form-control">
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="mb-2">
                                        <label class="form-label" for="txtMaterial">Material</label>
                                        <input id="txtMaterial" name="txtMaterial" type="text" maxlength="100" class="form-control">
                                      </div>
                                    </div>
                                  </div><!--end row-->
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="mb-2">
                                        <label class="form-label" for="txtEstilo">Estilo</label>
                                        <input id="txtEstilo" name="txtEstilo" type="text" maxlength="100" class="form-control">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="mb-2">
                                        <label class="form-label" for="txtEstampado">Estampado</label>
                                        <input id="txtEstampado" name="txtEstampado" type="text" maxlength="100" class="form-control">
                                      </div>
                                    </div>
                                  </div><!--end row-->

                                </div><!--end form-group-->
                              </div><!--end row-->
                            </div>
                          </div>
                        </div><!--end colapse vertical Barcode-->

                        <div class="tab-pane fade border p-2" id="v-pills-tags" role="tabpanel" aria-labelledby="v-pills-tags-tab">
                          <div class="row"> 
                            <div class="col-md-12">
                              <div class="mb-2">
                                <label class="form-label" for="txtEtiquetas">Palabras Clave <span class="required">*</span></label>
                                <input id="txtEtiquetas" name="txtEtiquetas" placeholder="Separa las etiquetas con coma ( , ) type="text" class="form-control" >
                              </div> 
                            </div>
                          </div>
                        </div><!--end colapse vertical Tags-->
                        <!-- <div class="tab-pane fade border p-2" id="v-pills-envio" role="tabpanel" aria-labelledby="v-pills-envio-tab">
                        <div class="row"> 
                        <div class="col-md-12">
                        <div id="shipping_product_data" class="panel woocommerce_options_panel hidden" style="display: block;">
                        <div class="options_group">
                        <p class="form-field _weight_field ">
                        <label for="_weight">Peso (kg)</label><span class="woocommerce-help-tip"></span><input type="text" class="short wc_input_decimal" style="" name="_weight" id="_weight" value="" placeholder="0"> </p>			<p class="form-field dimensions_field">
                        <label for="product_length">Dimensiones (cm)</label>
                        <span class="wrap">
                        <input id="product_length" placeholder="Longitud" class="input-text wc_input_decimal" size="6" type="text" name="_length" value="">
                        <input id="product_width" placeholder="Anchura" class="input-text wc_input_decimal" size="6" type="text" name="_width" value="">
                        <input id="product_height" placeholder="Altura" class="input-text wc_input_decimal last" size="6" type="text" name="_height" value="">
                        </span>
                        <span class="woocommerce-help-tip"></span>			</p>
                        </div>
                        
                        <div class="options_group">
                        <p class="form-field shipping_class_field">
                        <label for="product_shipping_class">Clase de envío</label>
                        <select name="product_shipping_class" id="product_shipping_class" class="select short">
                        <option value="-1" selected="selected">Ninguna clase de envío</option>
                        </select>
                        <span class="woocommerce-help-tip"></span>		</p>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>--> <!--end colapse vertical Tags-->
                      </div><!--end tab content-->
                    </div><!-- end col -->
                  </div><!-- end row -->
                </div><!-- end card body -->
              </div><!-- end card -->                   



              <!--<div class="card">
              <div class="card-body">
              <div class="accordion " id="accordionCategorias">
              <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingThree">
              <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
              Categoria
              </button>
              </h2>
              <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionCategorias" style="">
              <div class="accordion-body">
              <div class="col-lg-4">
              <div class="mb-4">
              <label for="listCategoria" class="form-label">Categoria</label>
              <select class="form-select" data-trigger name="listCategoria" id="listCategoria" required=""> </select>data-live-search="true"
              </div>
              </div> 
              </div>
              </div>
              </div>
              </div> end card body 
              </div> end card body 
              </div> end card -->

            </div><!-- end col -->
            <!-- <div class="col-xxl-10 col-xl-9 col-lg-8"> </div> -->
          </div><!-- end row -->
        </form>

        <div class="card"><!-- Card IMG del producto -->
          <div class="card-body">
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
        </div><!-- end de Card para imagenes -->

      </div><!-- /.modal-body -->

      <div class="row " >
        <div class="col-4 m-auto" id="pagina_prev" ><button class="page-item page-link pull-left" >«</button></div>
        <div class="col-2 m-auto align-items-center text-center" id="pagina_poss" ><span class="text-primary align-text-center" > 0 / 0 </span> </div>
        <div class="col-3 m-auto " id="pagina_prox" ><button class=" page-item page-link pull-right" >»</button></div>
        <div class="col-md-3 modal-footer pe-4">
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