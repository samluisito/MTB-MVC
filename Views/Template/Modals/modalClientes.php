<!-- Modal Nuevo - Edit User-->
<div class="modal fade " id="modalFormCliente" tabindex="-1" role="dialog" aria-labelledby="Formulario Usuarario" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-xl-down ">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nuevo Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >

        <!--Formulario -->
        <form class="form-" id="formCliente" name="formCliente">
          <input type="hidden" id="idUsuario" name="idUsuario" value=""><!-- este elemento estara oculto y su funcion es setear el id del rol a actualizar -->

          <div class="card"><!-- Card  -->
            <div class="p-2">
              <h4 class="">Datos Personales</h4>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="txtIdentificacion" class="form-label">Identificacion <span class="required">*</span></label>
                  <input class="form-control" type="text" id="txtIdentificacion"name="txtIdentificacion" placeholder="Ingrese la identificacion" autocomplete="off" required="">
                </div>
                <div class="col-md-4 mb-3">
                  <label for="txtNombre" class="form-label">Nombre<span class="required">*</span></label>
                  <input class="form-control" type="text" id="txtNombre"name="txtNombre"  placeholder="Ingrese Los Nombres del Ussuario" autocomplete="off" required="">
                </div>
                <div class="col-md-4 mb-3">
                  <label for="txtApellido" class="form-label">Apellido<span class="required">*</span></label>
                  <input class="form-control" type="text" id="txtApellido"name="txtApellido" placeholder="Ingrese Los Apellidos del Ususario" autocomplete="off" required="">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="txtTelefono" class="form-label">Telefono<span class="required">*</span></label>
                  <input class="form-control" type="tel" id="txtTelefono" name="txtTelefono"  placeholder="Ingrese el numero de Telefono"autocomplete="off" required="">
                </div>
                <div class="col-md-4 mb-3">
                  <label for="txtEmail" class="form-label">Email<span class="required">*</span></label>
                  <input class="form-control" type="email" id="txtEmail"name="txtEmail" placeholder="Ingrese el Email del usuario" autocomplete="off" required="">
                </div>
                <div class="col-md-4 mb-3">
                  <label for="txtPassword" class="form-label">Password<span class="required">*</span></label>
                  <input class="form-control" id="txtPassword"name="txtPassword"type="password" placeholder="Ingrese la Contraseña" autocomplete="new-password">
                </div>
              </div>
            </div>
          </div>
          <div class="card"><!-- Card  -->
            <div class="p-2">
              <h4 class="">Datos Fiscales</h4>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label href="#txtNit" >Identificacion Tributaria</label>
                  <input class="form-control" type="text" id="txtNit" name="texNit" value="" autocomplete="off">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="txtNombreFiscal" href="#txtNombreFiscal" >Nombre Fiscal</label>
                  <input class="form-control" type="text" id="txtNombreFiscal" name="txtNombreFiscal" value=""autocomplete="off">
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="txtDirFiscal" href="#txtDirFiscal" >Direccion Fiscal</label>
                  <input class="form-control" type="text" id="txtDirFiscal" name="txtDirFiscal" value=""autocomplete="off">
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer ">
        <button id="btnActionForm" type="submit" class="btn btn-primary" form="formCliente"><i class="fa fa-check-circle" aria-hidden="true"></i><&nbsp;Guardar</button>
        &nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times-circle" aria-hidden="true"></i>&nbsp;Cerrar</button>
      </div>
    </div>
  </div>
</div>




<!-- Modal Ver Cliente-->
<div class="modal fade-in" id="modalVerCte" tabindex="-1" role="dialog"  aria-hidden="true" aria-labelledby="Formulario de nuevo Rol">
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
                  <td>Nombre(s)</td>
                  <td id="verNombre">@fat</td>
                </tr>
                <tr>
                  <td>Apellido(s):</td>
                  <td id="verApellido">xxxxxx</td>
                </tr>
                <tr>
                  <td>Telefono:</td>
                  <td id="verTelefono">1122334455</td>
                </tr>
                <tr>
                  <td>Email:</td>
                  <td id="verEmail">email@email.com</td>
                </tr>
                <tr>
                  <td>Identificacion Fiscal:</td>
                  <td id="verNit">Numero de id tributario</td>
                </tr>
                <tr>
                  <td>Nombre Fiscal:</td>
                  <td id="verNombreFiscal">Nombre F</td>
                </tr>
                <tr>
                  <td>Direccion Fiscal</td>
                  <td id="verDirFiscal">direccion</td>
                </tr>
                <tr>
                  <td>Estado</td>
                  <td id="verEstado"></td>
                </tr>
                <tr>
                  <td>Fecha de Registro</td>
                  <td id="verFechReg"></td>
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