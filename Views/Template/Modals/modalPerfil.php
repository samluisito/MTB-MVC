

<!-- Modal Edit Perfil-->
<div class="modal fade-in" id="modalFormPerfil" tabindex="-1" role="dialog"  aria-hidden="true" aria-labelledby="Formulario de nuevo Rol">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header headerUpdate"> <!-- headerRegister   una segunda clase para agregar en los estilos ccs-->
        <h5 class="modal-title" id="titleModal">Nuevo Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body container-fluid" >

        <!--Formulario -->
        <form class="form-horizontal" id="formPerfil" name="formPerfil">
          <input type="hidden" id="idUsuario" name="idUsuario" value=""><!-- este elemento estara oculto y su funcion es setear el id del rol a actualizar -->
          <p class="text-primary"> Los campos con asterisco (<span  class="required"> * </span>) son Obligatorios </p>

          <!--Identificacion  -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txtIdentificacion" class="control-label"><font style="vertical-align: inherit;">Identificacion</font></label>
              <input class="form-control" type="text" id="txtIdentificacion"name="txtIdentificacion" placeholder="Ingrese la identificacion" required="" value="<?= $_SESSION['userData']['identificacion'] ?>">
            </div>
          </div>

          <!--Nombres -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txtNombre" class="control-label"><font style="vertical-align: inherit;">Nombre<span  class="required"> * </span></font></label>
              <input class="form-control" type="text" id="txtNombre"name="txtNombre"  placeholder="Ingrese Los Nombres del Ussuario" required="" value="<?= $_SESSION['userData']['nombres'] ?>">
            </div>
            <!-- Apellido  -->
            <div class="form-group col-md-6">
              <label for="txtApellido" class="control-label"><font style="vertical-align: inherit;">Apellido<span  class="required"> * </span></font></label>
              <input class="form-control" type="text" id="txtApellido"name="txtApellido" placeholder="Ingrese Los Apellidos del Ususario" required="" value="<?= $_SESSION['userData']['apellidos'] ?>">
            </div>
          </div>
          <!--Telefono  -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txtTelefono" class="control-label"><font style="vertical-align: inherit;">Telefono<span  class="required"> * </span></font></label>
              <input class="form-control" type="tel" id="txtTelefono" name="txtTelefono"  placeholder="Ingrese el numero de Telefono" required=""  value="<?= $_SESSION['userData']['telefono'] ?>">
            </div>
            <!-- Email -->
            <div class="form-group col-md-6">
              <label for="txtEmail" class="control-label"><font style="vertical-align: inherit;">Email</font></label>
              <input class="form-control" type="email" id="txtEmail"name="txtEmail" placeholder="Ingrese el Email del usuario" required="" value="<?= $_SESSION['userData']['email_user'] ?>" readonly="true" disabled="true">
            </div>
          </div>
          <!-- Password  y PasswordConfirm -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="txtPassword" class="control-label"><font style="vertical-align: inherit;">Password</font></label>
              <input class="form-control" id="txtPassword"name="txtPassword" type="password" autocomplete="off" placeholder="Ingrese la Contraseña"
                     readonly="readonly" onfocus="this.removeAttribute('readonly');">
            </div>
            <div class="form-group col-md-6">
              <label for="txtPasswordConfirm" class="control-label"><font style="vertical-align: inherit;">Confirmar Password</font></label>
              <input  class="form-control" id="txtPasswordConfirm" name="txtPasswordConfirm" type="password"  placeholder="Ingrese la Contraseña"
                      readonly="readonly" onfocus="this.removeAttribute('readonly');" >
            </div>
          </div>


          <div class="tile-footer ">
            <button id="btnActionForm" type="submit" class="btn btn-info" >   
              <i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"> </i> 
              <span id="btnText">Actualizar</span> 
            </button>&nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->

            <a class="btn btn-danger" href="#" data-dismiss="modal">   
              <i class="fa fa-fw fa-lg fa-times-circle" aria-hidden="true"></i> 
              <span>Cancelar</span> 
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

