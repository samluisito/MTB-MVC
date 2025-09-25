<!-- Modal Seleccion y corete de imagen-->
<div class="modal fade" id="div-modal-recorte" tabindex="-1" role="dialog" aria-labelledby="modalRecoteFoto" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Recortar Imagen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="img-container">
          <div class="row">
            <div class="col-md-9">
              <!-- en este img se visualizará todo el archivo seleccionado-->
              <img id="img-original" class="img-fluid">
            </div>
            <div class="col-md-3">
              <!-- en este div se mostrará la zona seleccionada, lo que quedará despues de hacer click en el boton crop-->
              <div id="div-preview" class="preview img-fluid preview-lg" style="width: 256px; height: 144px;">

              </div>
            </div>              
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times-circle"></i> Cerrar</button>
          <button type="button" class="btn btn-primary" id="btn-crop"><i class="fas fa-cut"></i> Cortar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div><!-- /.modal -->