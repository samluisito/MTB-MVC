<!-- Modal Nuevo Categoria - Edit Categoria-->
<div class="modal fade" id="modalFormCategoria" name="modalFormCategoria" tabindex="-1" role="dialog"  aria-hidden="true" aria-labelledby="Formulario de nueva Categoria">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-xl-down ">
    <div class="modal-content">
      <div class="modal-header headerRegister"> <!-- headerRegister   una segunda clase para agregar en los estilos ccs-->
        <h5 class="modal-title" id="titleModal">Nueva Categoria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >
        <!--Formulario Crear-Editar Categoria  -->
        <div class="card"><!-- Card  -->
          <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-justified" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#navtabs2-categoria" role="tab" aria-selected="true">
                  <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                  <span class="d-none d-sm-block">Categoria</span>    
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-subCategoria" role="tab" aria-selected="false">
                  <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                  <span class="d-none d-sm-block">SubCategorias</span>    
                </a>
              </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content p-3 text-muted">

              <div class="tab-pane active" id="navtabs2-categoria" role="tabpanel">
                <form id="formCategoria" name="formCategoria">
                  <input type="hidden" id="idCategoria" name="idCategoria" value=""><!-- este elemento estara oculto y su funcion es setear el id del Categoria a actualizar -->   
                  <input type="hidden" id="idCatPadre" name="idCatPadre" value=""><!-- este elemento estara oculto y su funcion es setear el id del Categoria a actualizar -->   

                  <div class="p-2">            
                    <p class="p-2 text-primary">los campos con asterisco (<span class="required">*</span>)</p>

                    <div class="row">
                      <div class="col-lg-6">
                        <div class="mb-3">
                          <label class="control-label">Nombre <span class="required">*</span></label>
                          <input class="form-control" id="txtNombre"name="txtNombre"type="text" placeholder="Ingrese el nombre de la Categoria" required="">
                        </div>
                        <div class="mb-3">
                          <label class="control-label">Descripcion <span class="required">*</span></label>
                          <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="4,5" placeholder="Descripcion de la Categoria" required=""></textarea>
                        </div>
                      </div>

                      <div class="col-lg-6">
                        <input type="hidden" id="foto_actual" name="foto_actual" value=""><!-- -->
                        <input type="hidden" id="foto_remove" name="foto_remove" value=""><!-- -->
                        <input type="hidden" id="foto_blob_name" name="foto_blob_name" value=""><!-- -->
                        <input type="hidden" id="foto_blob_type" name="foto_blob_type" value=""><!-- -->

                        <div class="photo"> <!-- Estilos de la imagen -->
                          <label id="prevPhotoLabel"for="foto">Resolucion Minima 500x320)</label>
                          <div id="prevPhoto" class="prevPhoto prevPhoto-Banner"> <!-- Donde se mostrara la vistaa previa de la imagen-->
                            <span class="delPhoto notBlock">X</span> <!-- no estara visible y se le aplicaran algunos estilos -->
                            <label for="foto"></label> <!-- ocupara el ancho para poder seleccionar la foto -->
                            <div>
                              <img id="imgminiat" src="<?= DIR_MEDIA; ?>images/portada_categoria.png"> <!-- imagen previa -->
                            </div>
                          </div>
                          <div class="upimg"> <!-- junto al imput tipo file serviran para cargar la foto -->
                            <input type="file" accept="image/jpg , image/jpeg , image/png" name="foto" id="foto">
                          </div>
                          <div id="form_alert"></div>  <!-- aca se mostrata un texto  -->
                        </div>
                      </div>                      
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="listStatus" id="listCatFBLabel">Categoria Facebook <span class="required">*</span></label>
                          <select class="form-select" id="listCatFB"name="listCatFB">
                            <?php foreach ($data['categorias_facebook_n1'] as $cat_fb) { ?>
                              <option value="<?= $cat_fb['id_fb'] ?>"> <?= $cat_fb['nombre'] ?> </option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-6">

                        <div class="mb-3">
                          <label for="listStatus" id="listCatGoogleLabel">Categoria Google <span class="required">*</span></label>
                          <select class="form-select" id="listCatGoogle"name="listCatGoogle">
                            <?php foreach ($data['categorias_google_n1'] as $cat_gg) { ?>
                              <option value="<?= $cat_gg['id_cat_gg'] ?>"> <?= $cat_gg['nombre'] ?> </option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>


                    <div class="row">
                      <div class="col-md-9">
                        <div class="mb-3">
                          <label class="control-label">Etiquetas </label>
                          <input class="form-control" id="txtTags"name="txtTags"type="text" placeholder="Separe las etiquetas por coma ( , )">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="mb-3">
                          <label for="listStatus" id="listStatusLabel">Estado <span class="required">*</span></label>
                          <select class="form-select" id="listStatus"name="listStatus">
                            <option value="0"> Inactivo</option>
                            <option value="1"> Activo</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </form> 
              </div><!-- end tab pane Categoria -->

              <div class="tab-pane" id="navtabs2-subCategoria" role="tabpanel">
                <div class="row ">
                  <div class=" align-middle pb-3">
                    <button class="btn btn-success waves-effect waves-light m-auto" type="button" onclick="nvaSubCategoria();"><i class="fa fa-plus-circle" aria-hidden="true"></i>Nueva Subcategoria</button>
                  </div>
                </div>
                <div class="accordion" id="accordionCategorias"> <!--contenedor de acrodeones-->
                  <div class="accordion-item">

                    <h2 class="accordion-header" id="headingOne">
                      <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Nueva subCategoria
                      </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                      <div class="accordion-body">
                        <form id="formCategoria" name="formCategoria">
                          <input type="hidden" id="idCategoria" name="idCategoria" value=""><!-- este elemento estara oculto y su funcion es setear el id del Categoria a actualizar -->   
                          <div class="p-2">            
                            <div class="row">
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label class="control-label">Nombre <span class="required">*</span></label>
                                  <input class="form-control" id="txtNombre"name="txtNombre"type="text" placeholder="Ingrese el nombre de la Categoria" required="">
                                </div>
                                <div class="mb-3">
                                  <label class="control-label">Descripcion <span class="required">*</span></label>
                                  <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="4,5" placeholder="Descripcion de la Categoria" required=""></textarea>
                                </div>
                              </div>
                              <div class="col-lg-6 ">
                                <div class="row">
                                  <div class="d-flex justify-content-center align-middle p-3">
                                    <input type="hidden" id="foto_actual" name="foto_actual" value=""><!-- -->
                                    <input type="hidden" id="foto_remove" name="foto_remove" value=""><!-- -->
                                    <input type="hidden" id="foto_blob_name" name="foto_blob_name" value=""><!-- -->
                                    <input type="hidden" id="foto_blob_type" name="foto_blob_type" value=""><!-- -->
                                    <div class="photo"> <!-- Estilos de la imagen -->
                                      <label id="prevPhotoLabel"for="foto">Resolucion Minima 500x320)</label>
                                      <div class="prevPhoto prevPhoto-subCategoria"> <!-- Donde se mostrara la vistaa previa de la imagen-->
                                        <span id="delPhoto" class="delPhoto notBlock">X</span> <!-- no estara visible y se le aplicaran algunos estilos -->
                                        <label for="foto"></label> <!-- ocupara el ancho para poder seleccionar la foto -->
                                        <div>
                                          <img id="imgminiat" src="<?= DIR_MEDIA; ?>images/portada_categoria.png"> <!-- imagen previa -->
                                        </div>
                                      </div>
                                      <div class="upimg"> <!-- junto al imput tipo file serviran para cargar la foto -->
                                        <input type="file" accept="image/jpg , image/jpeg , image/png" name="foto" id="foto">
                                      </div>
                                      <div id="form_alert"></div>  <!-- aca se mostrata un texto  -->
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div><!--end row-->
                            <div class="row">
                              <div class="col-md-6">
                                <div class="mb-3">
                                  <label for="listStatus" id="listCatFBLabel">Categoria Facebook <span class="required">*</span></label>
                                  <select class="form-select" id="listCatFB"name="listCatFB">
                                    <?php foreach ($data['categorias_facebook_n1'] as $cat_fb) { ?>
                                      <option value="<?= $cat_fb['id_fb'] ?>"> <?= $cat_fb['nombre'] ?> </option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="mb-3">
                                  <label for="listStatus" id="listCatGoogleLabel">Categoria Google <span class="required">*</span></label>
                                  <select class="form-select" id="listCatGoogle"name="listCatGoogle">
                                    <?php foreach ($data['categorias_google_n1'] as $cat_gg) { ?>
                                      <option value="<?= $cat_gg['id_cat_gg'] ?>"> <?= $cat_gg['nombre'] ?> </option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                            </div> <!--end row-->
                            <div class="row">
                              <!--                              <div class="mt-2 d-flex align-middle">-->
                              <div class="col-sm-6 mb-3">
                                <div class="mb-3">
                                  <label class="control-label">Etiquetas </label>
                                  <input class="form-control" id="txtTags"name="txtTags"type="text" placeholder="Separe las etiquetas por coma ( , )">
                                </div>
                              </div>
                              <div class="col-sm-4 mb-3">
                                <label class="control-label">Esatdo </label>
                                <select class="form-select pr-5" id="listStatus"name="listStatus">
                                  <option value="0"> Inactivo</option>
                                  <option value="1"> Activo</option>
                                </select>
                              </div>
                              <div class="col-sm-2 mb-3">
                                <label class="control-label">...................</label>
                                <button id="btnActionForm" type="submit" class="btn btn-primary" form="">
                                  <i class="fa fa-check-circle" aria-hidden="true"></i><span id="btnText">&nbsp;Guardar</span></button>
                              </div> 
                              <!--</div>--> 
                            </div>
                          </div>
                        </form> 
                      </div>
                    </div><!--end collapse-->
                    
                  </div><!--end accordion-item-->

                  <!--                  <div class="accordion-item">
                                      <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                          Accordion Item #2
                                        </button>
                                      </h2>
                                      <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                          <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                                        </div>
                                      </div>
                                    </div>-->
                </div>
              </div><!-- end tab pane -->
            </div><!-- end tab content -->
          </div><!-- end crad body -->
        </div>
      </div>
      <div class="modal-footer ">
        <button id="btnActionForm" type="submit" class="btn btn-primary" form="formCategoria"><i class="fa fa-check-circle" aria-hidden="true"></i><span id="btnText">&nbsp;Guardar</span></button>
        &nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times-circle" aria-hidden="true"></i>&nbsp;Cerrar</button>
      </div>
      <div class="row" >
        <div class="col-5 " id="pagina_prev" ></div>
        <div class="col-2 align-items-center text-center" id="pagina_poss" ></div>
        <div class="col-5 align-items-center text-center" id="pagina_prox" ></div>
      </div>
    </div>
  </div>
</div>


<!-- Modal Ver Categoria-->
<div class="modal fade-in" id="modalVerCategoria" tabindex="-1" role="dialog"  aria-hidden="true" aria-labelledby="Datos de la Categoria">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen-sml-down ">
    <div class="modal-content">
      <div class="modal-header header-primary"> <!-- headerRegister   una segunda clase para agregar en los estilos ccs-->
        <h5 class="modal-title" id="titleModal">Datos de la Categoria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >
        <div class="card"><!-- Card  -->
          <div class="p-2">  
            <!--Tabla ver datos -->
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td>ID: </td>
                  <td id="celID">11222333</td>
                </tr>
                <tr>
                  <td>Nombre: </td>
                  <td id="celNombre">@fat</td>
                </tr>
                <tr>
                  <td>Descripcion: </td>
                  <td id="celDescripcion">xxxxxx</td>
                </tr>
                <tr>
                  <td>Estado: </td>
                  <td id="celEstado">xxxxxx</td>
                </tr>
                <tr>
                  <td>Foto: </td>
                  <td id="celImgCategoria">xxxxxx</td>
                </tr>
              </tbody>
            </table>
          </div>     
        </div>     
      </div>     
      <div class="modal-footer ">
        &nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times-circle" aria-hidden="true"></i>&nbsp;Cerrar</button>
      </div>
    </div>
  </div>
</div>