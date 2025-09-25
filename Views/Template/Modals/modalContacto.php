<!-- Modal Ver Contacto-->
<div class="modal fade-in" id="modalVerCto" tabindex="-1" role="dialog"  aria-hidden="true" aria-labelledby="Formulario de nuevo ">
  <div class="modal-dialog modal-dialog-centered modal-md modal-fullscreen-sm-down">
    <div class="modal-content">
      <div class="modal-header header-primary"> <!-- headerRegister   una segunda clase para agregar en los estilos ccs-->
        <h5 class="modal-title" id="titleModal">Datos de Contacto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >
        <div class="card"><!-- Card  -->
          <div class="p-2"> <!--div class="tile  container-fluid ">div class="tile-body">  <div class="tile-body"-->
            <!--Tabla ver datos -->
            <div class=" row">
              <div class="mb-3 col-md-6">
                <label class="form-control-plaintext" id="verNombre">Nombre</label>
              </div>
              <div class="mb-3 col-md-6">
                <label class="form-control-plaintext" id="verApellido">Apellido</label>
              </div>
            </div>
            <div class=" row">
              <div class="mb-3 col-md-6">
                <label class="form-control-plaintext" id="verTelefono">telefono</label>
              </div>
              <div class="mb-3 col-md-6">
                <label class="form-control-plaintext" id="verEmail">email</label>
              </div>
            </div>
            <div class=" row">

              <div class="mb-3 col-md-6">
                <label class="form-control-plaintext" id="verFecha">Fecha</label>
              </div>         
              <div class="mb-3 col-md-6">
                <label class="form-control-plaintext" id="verLocalidad">Localidad</label>
              </div>
            </div>
            <div class=" row">
              <div class="mb-3 col-md-12">
                <textarea id="verMensaje" rows="5" cols="99%" class="form-control-plaintext" disabled="">mensaje</textarea>
              </div>

            </div>


          </div>
        </div>
      </div>
      <div class="modal-footer ">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times-circle" aria-hidden="true"></i>&nbsp;Cerrar</button>
      </div>
    </div>
  </div>
</div>