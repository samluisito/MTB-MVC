<!-- Modal -->
<div class="modal fade" id="modalFormModulo" name="modalFormModulo" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="Formulario de nuevo Modulo">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header headerRegister"> <!-- headerRegister una segunda clase para agregar en los estilos ccs-->
        <h5 class="modal-title" id="titleModal">Nuevo Modulo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body bg-secondary bg-gradient"style="--bs-bg-opacity: 0.2;" >
        <!--Formulario -->
        <form id="formModulo" name="">
          <div class="card"><!-- Card -->
            <div class="p-2">
              <input type="hidden" id="idModulo" name="idModulo" value=""><!-- este elemento estara oculto y su funcion es setear el id del rol a actualizar -->
              <div class="form-group">
                <label class="control-label">Titulo</label>
                <input class="form-control" id="txtTitulo"name="txtTitulo"type="text" placeholder="Ingrese el nombre del Modulo" required="">
              </div>
              <div class="form-group">
                <label class="control-label">Descripcion</label>
                <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="3" placeholder="Descripcion del Modulo" required=""></textarea>
              </div>
              <div class="form-group">
                <label for="listStatus"id="listStatusLabel"hidden="" > Estado </label>
                <select class="form-select" id="listStatus"name="listStatus" hidden="">
                  <option value="0"> Inactivo </option>
                  <option value="1"> Activo</option>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer ">
        <button id="btnActionForm" type="submit" class="btn btn-primary" form="formModulo"><i class="fa fa-check-circle" aria-hidden="true"></i><&nbsp;Guardar</button>
        &nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times-circle" aria-hidden="true"></i>&nbsp;Cerrar</button>
      </div>
    </div>
  </div>
</div>