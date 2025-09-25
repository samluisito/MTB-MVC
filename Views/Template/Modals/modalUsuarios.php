

<!-- Modal Nuevo User - Edit User-->
<div class="modal fade " id="modalFormUsuario" tabindex="-1" role="dialog" aria-labelledby="Formulario Usuarario" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-xl-down ">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nuevo Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >
        <!--Formulario -->
        <form class="form-horizontal" id="formUsuario" name="formUsuario">
          <input type="hidden" id="idUsuario" name="idUsuario" value=""><!-- este elemento estara oculto y su funcion es setear el id del rol a actualizar -->
          <div class="card"><!-- Card  -->
            <div class="p-2">
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="txtIdentificacion" class="control-label">Identificacion</label>
                  <input class="form-control" type="text" id="txtIdentificacion"name="txtIdentificacion" placeholder="Ingrese la identificacion" required="" autocomplete="off" >
                </div>
              </div>

              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="txtNombre" class="control-label">Nombre</label>
                  <input class="form-control" type="text" id="txtNombre"name="txtNombre"  placeholder="Ingrese Los Nombres del Ussuario" required="" autocomplete="off" >
                </div>

                <div class="mb-3 col-md-6">
                  <label for="txtApellido" class="control-label">Apellido</label>
                  <input class="form-control" type="text" id="txtApellido"name="txtApellido" placeholder="Ingrese Los Apellidos del Ususario" required="" autocomplete="off" >
                </div>
              </div>

              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="txtTelefono" class="control-label">Telefono</label>
                  <input class="form-control" type="tel" id="txtTelefono" name="txtTelefono"  placeholder="Ingrese el numero de Telefono" required="" autocomplete="off" >
                </div>

                <div class="mb-3 col-md-6">
                  <label for="txtEmail" class="control-label">Email</label>
                  <input class="form-control" type="email" id="txtEmail"name="txtEmail" placeholder="Ingrese el Email del usuario" required="" autocomplete="off" >
                </div>
              </div>


              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="listRolid">Tipo de Usuario</label>
                  <select class="form-select" data-live-search="true" id="listRolid" name="listRolid">
                  </select>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="listStatus">Estado</label>
                  <select class="form-control selectpicker" id="listStatus"name="listStatus">
                    <option value="0"> Inactivo </option>
                    <option value="1"> Activo </option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="txtPassword" class="form-label">Password<span class="required">*</span></label>
                  <input class="form-control" id="txtPassword"name="txtPassword"type="password" placeholder="Ingrese la Contraseña" autocomplete="new-password">
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer ">
        <button id="btnActionForm" type="submit" class="btn btn-primary" form="formUsuario"><i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;Guardar</button>
        &nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times-circle" aria-hidden="true"></i>&nbsp;Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Ver User-->
<div class="modal fade" id="modalVerUsuario" tabindex="-1" role="dialog"  aria-hidden="true" aria-labelledby="Ver usuario">
  <div class="modal-dialog modal-dialog-centered modal-md modal-fullscreen-sm-down">
    <div class="modal-content">
      <div class="modal-header header-primary"> <!-- headerRegister   una segunda clase para agregar en los estilos ccs-->
        <h5 class="modal-title" id="titleModal">Datos del Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >
        <div class="card"><!-- Card  -->
          <div class="p-2">
            <!--Tabla ver datos -->
            <table class="table table-light table-striped">
              <tbody>
                <tr>
                  <td>Identificacion:</td>
                  <td id="verIdentificacion">11222333</td>
                </tr>
                <tr>
                  <td>Nombre(s)</td><td id="verNombre">@fat</td>
                </tr>
                <tr>
                  <td>Apellido(s):</td><td id="verApellido">xxxxxx</td>
                </tr>
                <tr>
                  <td>Telefono:</td><td id="verTelefono">1122334455</td>
                </tr>
                <tr>
                  <td>Email:</td><td id="verEmail"></td>
                </tr>
                <tr>
                  <td>Tipo de Usuario</td><td id="verTpoUser"></td>
                </tr>
                <tr>
                  <td>Estado (Usuario):</td><td id="verEstado"></td>
                </tr>
                <tr>
                  <td>Fecha de Registro</td><td id="verFechReg"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer ">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times-circle" aria-hidden="true"></i>&nbsp;Cerrar</button>
      </div>
    </div>
  </div>
</div>