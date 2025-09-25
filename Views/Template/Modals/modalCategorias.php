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
        <form id="formCategoria" name="formCategoria">
          <input type="hidden" id="idCategoria" name="idCategoria" value=""><!-- este elemento estara oculto y su funcion es setear el id del Categoria a actualizar -->
          <div class="card"><!-- Card  -->
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
                  <div class="mb-3">
                    <label for="listTpoCat" id="selectVerCat">Categoria para <span class="required">*</span></label>
                    <select class="form-select" id="listTpoCat"name="listTpoCat">
                      <option value="prod"> Producto </option>
                      <option value="blog"> Blog Entrada </option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="listPublicarCat" id="selectVerCat">Mostrar en <span class="required">*</span></label>
                    <select class="form-select" id="listPublicarCat"name="listPublicarCat">
                      <option value="mn"> Solo Menu </option>
                      <option value="sl"> Slide - Carrusel </option>
                      <option value="bn"> Banner Inferior</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <input type="hidden" id="foto_actual" name="foto_actual" value=""><!-- -->
                  <input type="hidden" id="foto_remove" name="foto_remove" value=""><!-- -->
                  <input type="hidden" id="foto_blob_name" name="foto_blob_name" value=""><!-- -->
                  <input type="hidden" id="foto_blob_type" name="foto_blob_type" value=""><!-- -->

                  <div class="photo"> <!-- Estilos de la imagen -->
                    <label id="prevPhotoLabel"for="foto"></label>
                    <div class="prevPhoto"> <!-- Donde se mostrara la vistaa previa de la imagen-->
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

                  <div class="mb-3">
                    <label class="control-label">Etiquetas </label>
                    <input class="form-control" id="txtTags"name="txtTags"type="text" placeholder="Separe las etiquetas por coma ( , )">
                  </div>
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
          </div>
        </form>
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