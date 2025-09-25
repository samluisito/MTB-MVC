<!-- Button trigger modal 
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
    Launch demo modal
</button>
-->

<!-- Modal Nuevo Rol - Edit Rol-->
<div class="modal fade" id="modalFormRol" name="modalFormRol" tabindex="-1" role="dialog"  aria-hidden="true" aria-labelledby="Formulario de nuevo Rol">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down" role="document">
    <div class="modal-content">
      <div class="modal-header headerRegister"> <!-- headerRegister   una segunda clase para agregar en los estilos ccs-->
        <h5 class="modal-title" id="titleModal">Nuevo Rol</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >
        <div class="card"><!-- Card  -->
          <div class="p-2">
            <!--Formulario -->
            <form id="formRol" name="formRol">
              <input type="hidden" id="idRol" name="idRol" value=""><!-- este elemento estara oculto y su funcion es setear el id del rol a actualizar -->
              <div class="mb-3">
                <label class="control-label">Nombre</label>
                <input class="form-control" id="txtNombre"name="txtNombre"type="text" placeholder="Ingrese el nombre del Rol" required="">
              </div>

              <div class="mb-3">
                <label class="control-label">Descripcion</label>
                <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="2" placeholder="Descripcion del Rol" required=""></textarea>
              </div>

              <div class="mb-3">
                <label for="exampleSelect1" id="listStatusLabel">Estado</label>
                <select class="form-select" id="listStatus"name="listStatus">
                  <option value="0"> Inactivo</option>
                  <option value="1"> Activo </option>
                </select>
              </div>

            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer ">
        <button id="btnActionForm" type="submit" form="formRol" class="btn btn-primary" ><i class="fa fa-check-circle" aria-hidden="true"></i>Guardar</button>
        &nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->
        <button class="btn btn-secondary"  data-bs-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i>Cancelar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal permisos-->
<div class="modal fade" id="modalPermisos" name="modalPermisos" tabindex="-1" role="dialog"  aria-hidden="true" aria-labelledby="Formulario de Permisos">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-lg-down" role="document">
    <div class="modal-content">
      <div class="modal-header headerRegister"> <!-- headerRegister   una segunda clase para agregar en los estilos ccs-->
        <h5 class="modal-title" id="titleModal">Propiedades del rol </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h3 class="">Permisos de Rol</h3>
        <div class="table-responsive">
          <table class="table table-hover table-bordered  display compact" style="width:100%; height:100%" id="tablePermisos">
            <thead>
              <tr>
                <th scope="col" >idmodulo</th>
                <th scope="col" >rolid</th>
                <th scope="col" >no visible</th>
                <th scope="col" >Modulo</th>
                <th scope="col" >Ver</th>
                <th scope="col" >Crear</th>
                <th scope="col" >Actualizar</th>
                <th scope="col" >Eliminar</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>     
      </div>
    </div>
  </div>
</div>