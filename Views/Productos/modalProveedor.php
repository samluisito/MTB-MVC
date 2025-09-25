<!-- Modal Nuevo Proveedor - Edit Proveedor-->
<div class="modal fade" id="modalFormProveedor" name="modalFormProveedor" tabindex="-1" role="dialog"  aria-hidden="true" aria-labelledby="Formulario de nueva Proveedor">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-md-down ">
    <div class="modal-content">
      <div class="modal-header headerRegister"> <!-- headerRegister   una segunda clase para agregar en los estilos ccs-->
        <h5 class="modal-title" id="titleModal">Nueva Proveedor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >
        <!--Formulario Crear-Editar Proveedor  -->
        <form id="formProveedor" name="formProveedor">
          <input type="hidden" id="idProveedor" name="idProveedor" value=""><!-- este elemento estara oculto y su funcion es setear el id del Proveedor a actualizar -->
          <div class="card"><!-- Card  -->
            <div class="p-2">            
              <p class="p-2 text-primary">los campos con asterisco (<span class="required">*</span>)</p>
              <div class="row">
                <div class="col-md-7 col-lg-9">
                  <div class="mb-3">
                    <label class="control-label" for="txtNombre">Nombre <span class="required">*</span></label>
                    <input class="form-control" autocomplete="off" id="txtNombre"name="txtNombre"type="text" placeholder="Ingrese el nombre de la Proveedor" >
                  </div>
                  <div class="mb-3">
                    <label class="control-label" for="txtDescripcion">Descripcion <span class="required">*</span></label>
                    <textarea maxlength="255" class="form-control" autocomplete="off" id="txtDescripcion" name="txtDescripcion" rows="2" placeholder="Descripcion de la Proveedor" ></textarea>
                  </div>
                </div>
                <div class="col-md-5 col-lg-3">
                  <input type="hidden" id="foto_actual" name="foto_actual" value=""><!-- -->
                  <input type="hidden" id="foto_remove" name="foto_remove" value=""><!-- -->
                  <input type="hidden" id="foto_blob_name" name="foto_blob_name" value=""><!-- -->
                  <input type="hidden" id="foto_blob_type" name="foto_blob_type" value=""><!-- -->

                  <div class="photo"> <!-- Estilos de la imagen -->
                    <label id="prevPhotoLabel"for="foto"></label>
                    <div class="prevPhoto prevPhoto-logoProveedor"> <!-- Donde se mostrara la vistaa previa de la imagen-->
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
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="control-label" for="txtDireccion">Direccion <span class="required">*</span></label>
                    <textarea maxlength="255" class="form-control" autocomplete="off" id="txtDireccion" name="txtDireccion" rows="2" placeholder="Direccion del Proveedor" ></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-3">
                    <label class="control-label" for="txtWeb">Link Web </label>
                    <input class="form-control" autocomplete="off" id="txtWeb"name="txtWeb"type="text" type="text" placeholder="Pagina web">
                  </div>
                  <div class="mb-3">
                    <label class="control-label" for="txtFacebook">Link Facebook </label>
                    <input class="form-control" autocomplete="off" id="txtFacebook"name="txtFacebook"type="text" placeholder="Link, de pagina de Facebook">
                  </div>
                  <div class="mb-3">
                    <label class="control-label" for="txtInstagram">Link Instagram </label>
                    <input class="form-control" autocomplete="off" id="txtInstagram"name="txtInstagram"type="text" placeholder="Link, de pagina de Instagram">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="control-label" for="txtTelefono">Telefono Local </label>
                    <input class="form-control" autocomplete="off" id="txtTelefono"name="txtTelefono"type="text" placeholder="Incluir codigo de pais">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label class="control-label" for="txtMobil">Celular (WS) </label>
                    <input class="form-control" autocomplete="off" id="txtMobil" name="txtMobil" type="text" placeholder="Incuir codigo de pais">
                  </div>
                </div>
                <div class="col-md-4">
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
        <button id="btnActionForm" type="submit" class="btn btn-primary" form="formProveedor"><i class="fa fa-check-circle" aria-hidden="true"></i><span id="btnText">&nbsp;Guardar</span></button>
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


<!-- Modal Ver Proveedor-->
<div class="modal fade-in" id="modalVerProveedor" tabindex="-1" role="dialog"  aria-hidden="true" aria-labelledby="Datos de la Proveedor">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen-sml-down ">
    <div class="modal-content">
      <div class="modal-header header-primary"> <!-- headerRegister   una segunda clase para agregar en los estilos ccs-->
        <h5 class="modal-title" id="titleModal">Datos de la Proveedor</h5>
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
                  <td id="verProvID">11222333</td>
                </tr>
                <tr>
                  <td>Nombre: </td>
                  <td id="verProvNombre">@fat</td>
                </tr>
                <tr>
                  <td>Descripcion: </td>
                  <td id="verProvDescripcion">xxxxxx</td>
                </tr>
                <tr>
                  <td>Direccion: </td>
                  <td id="verProvDireccion">xxxxxx</td>
                </tr>
                <tr>
                  <td>Telefono local: </td>
                  <td id="verProvTelefono">xxxxxx</td>
                </tr>
                <tr>
                  <td>Direccion: </td>
                  <td id="verProvCelular">xxxxxx</td>
                </tr>
                <tr>
                  <td>Links: </td>
                  <td id="verProvLinks"></td>
                </tr>
                <tr>
                  <td>Estado: </td>
                  <td id="verProvEstado">xxxxxx</td>
                </tr>
                <tr>
                  <td>Fecha de Creacion: </td>
                  <td id="verProvFecha">xxxxxx</td>
                </tr>
                <tr>
                  <td>Foto: </td>
                  <td id="verProvImgProveedor">xxxxxx</td>
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
      <div class="row" >
        <div class="col-5 " id="pagina_prev" ></div>
        <div class="col-2 align-items-center text-center" id="pagina_poss" ></div>
        <div class="col-5 align-items-center text-center" id="pagina_prox" ></div>
      </div>
    </div>
  </div>
</div>