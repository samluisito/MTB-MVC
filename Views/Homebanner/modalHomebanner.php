<!-- Modal Nuevo Banner - Edit Banner-->
<div class="modal fade" id="modalFormBanner" name="modalFormBanner" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="Formulario de nueva Banner">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-xl-down ">
    <div class="modal-content">
      <div class="modal-header headerRegister"> <!-- headerRegister una segunda clase para agregar en los estilos ccs-->
        <h5 class="modal-title" id="titleModal">Nuevo Banner</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >
        <div class="card"><!-- Card -->
          <div class="p-2">
            <!--Formulario Crear-Editar Banner -->
            <form id="formBanner" name="formBanner">
              <input type="hidden" id="idBanner" name="idBanner" value=""><!-- este elemento estara oculto y su funcion es setear el id del Banner a actualizar -->
              <p class="text-primary">los campos con asterisco (<span class="required">*</span>)</p>
              <div class="row">
                <div class="col-lg-6">
                  <div class="mb-3">
                    <label class="control-label">Nombre <span class="required">*</span></label>
                    <input class="form-control" id="txtNombre"name="txtNombre"type="text" placeholder="Ingrese el nombre de la Banner" required="">
                  </div>
                  <div class="mb-3">
                    <label class="control-label">Descripcion <span class="required">*</span></label>
                    <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="4,5" placeholder="Descripcion de la Banner" required=""></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="listTpo" id="selectVerCat" >tipo <span class="required">*</span></label>
                    <select class="form-select" id="listTpo" name="listTpo" onchange="selectItem()" onload="selectItem()">
                      <option value="categ"> Categoria </option>
                      <option value="prod"> Producto </option>
                      <option value="blog"> Blog</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="listItem">Item<span class="required">*</span></label>
                    <select class="form-select" data-live-search="true" id="listItem" name="listItem" required="" onchange="selectUrlItem()">
                      
                    </select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <input type="hidden" id="foto_actual" name="foto_actual" value=""><!-- -->
                  <input type="hidden" id="foto_remove" name="foto_remove" value=""><!-- -->
                  <!--<input type="" id="foto_json" name="foto_json" value=""> -->
                  <input type="hidden" id="foto_blob_name" name="foto_blob_name" value=""><!-- -->
                  <input type="hidden" id="foto_blob_type" name="foto_blob_type" value=""><!-- -->
                  <div class="photo"> <!-- Estilos de la imagen -->
                    <label id="prevPhotoLabel"for="foto"></label>
                    <div class="prevPhoto prevPhoto-Carrusel"> <!-- Donde se mostrara la vistaa previa de la imagen-->
                      <span class="delPhoto notBlock">X</span> <!-- no estara visible y se le aplicaran algunos estilos -->
                      <label for="foto"></label> <!-- ocupara el ancho para poder seleccionar la foto -->
                      <div>
                        <img id="imgminiat" src="<?= DIR_MEDIA; ?>images/portada_categoria.png"> <!-- imagen previa -->
                      </div>
                    </div>
                    <div class="upimg"> <!-- junto al imput tipo file serviran para cargar la foto -->
                      <input type="file" accept="image/jpg , image/jpeg , image/png , image/webp" name="foto" id="foto" >
                    </div>
                    <div id="form_alert"></div> <!-- aca se mostrata un texto -->
                  </div>
                  <div class="mb-3">
                    <label class="control-label">Enlace: </label>
                    <a class="" target="_blank" id="txtUrl"name="txtUrl" href=""> </a>
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
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer ">
        <button id="btnActionForm" type="submit" class="btn btn-primary" form="formBanner"><i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;Guardar</button>
        &nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times-circle" aria-hidden="true"></i>&nbsp;Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Ver Banner-->
<div class="modal fade-in" id="modalVerBanner" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="Datos de la Banner">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header header-primary"> <!-- headerRegister una segunda clase para agregar en los estilos ccs-->
        <h5 class="modal-title" id="titleModal">Datos de la Banner</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body " ><!--div class="tile container-fluid ">div class="tile-body"> <div class="tile-body"-->

        <!--Tabla ver datos -->
        <table class="table table-bordered">
          <!--thead>
          <tr>
          <th scope="col">#</th>
          <th scope="col">First</th>
          <th scope="col">Last</th>
          <th scope="col">Handle</th>
          </tr>
          </thead-->
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
              <td id="celImgBanner">xxxxxx</td>
            </tr>

          </tbody>
        </table>
        <div class="tile-footer ">
          <button class="btn btn-secondary" href="#" data-dismiss="modal"> 
            <i class="fa fa-fw fa-lg fa-times-circle" aria-hidden="true"></i> 
            <span>Cerrar</span> 
          </button>
        </div>

      </div>
    </div>
  </div>
</div>