<!-- Modal Nuevo Entrada - Edit Entrada-->
<div class="modal modal-fullscreen fade" id="modalFormEntrada" name="modalFormEntrada" tabindex="-1" role="dialog"  aria-hidden="true" aria-labelledby="Formulario de nuevo Entrada">
   <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header headerRegister"> <!-- headerRegister   una segunda clase para agregar en los estilos ccs-->
            <h5 class="modal-title" id="titleModal">Nuevo Entrada</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <!--Formulario Crear-Editar Entrada  -->
            <form id="formEntrada" name="formEntrada">

               <input type="hidden" id="idEntrada" name="idEntrada" value=""><!-- este elemento estara oculto y su funcion es setear el id del Entrada a actualizar -->
               <p class="text-primary">los campos con asterisco (<span class="required">*</span>)</p>

               <div class="row">
                  <div class="col-lg-10">

                     <div class="form-group">
                        <label class="control-label"for="txtNombre"><font style="vertical-align: inherit;">Titulo <span class="required">*</span></font></label>
                        <input class="form-control" id="txtTitulo"name="txtTitulo"type="text" placeholder="Ingrese el nombre de la Entrada" required="">
                     </div>

                     <div class="form-group">
                        <label class="control-label" for="txtDescripcion"><font style="vertical-align: inherit;">Descripcion <span class="required">*</span></font></label>
                        <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="2" ></textarea>
                     </div>
                     <div class="form-group">
                        <label class="control-label" for="txtTexto"><font style="vertical-align: inherit;">Texto <span class="required">*</span></font></label>
                        <textarea class="form-control" id="txtTexto" name="txtTexto" rows="16" ></textarea>
                     </div>
                     <div class="form-group">
                        <label class="control-label"for="txtTags">Etiquetas de Entrada</label>
                        <input class="form-control" type="text" name="txtTags" id="txtTags" placeholder="Separa las etiquetas con coma ( , )">

                     </div>
                     <div class="row">
                        <div class="form-group col-md-6">
                           <label for="listCategoria">Categoria<span class="required">*</span></label>
                           <select class="form-select" data-live-search="true" id="listCategoria" name="listCategoria" required=""></select>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="listStatus" id="listStatusLabel"><font style="vertical-align: inherit;">Estado <span class="required">*</span></font></label>
                           <select class="form-select" id="listStatus"name="listStatus">
                              <option value="1"> <font style="vertical-align: inherit;">Activo</font></option>
                              <option value="0" selected> <font style="vertical-align: inherit;">Inactivo</font></option>
                           </select>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-2">
                     <div class="form-group col-md-12">
                        <div id="containerGallery">
                           <span>Agregar foto (1200 x 1486 o -30%)</span>
                           <button class="btnAddImage btn btn-info btn-sm" type="button"> <i class="fa fa-plus"></i></button>
                        </div>
                        <hr>
                        <div id="containerImages">
                           <!-- div id="div24">
                               <div class="prevImage " >
                                   <img src="<?= DIR_MEDIA; ?>/images/uploads/producto1.jpg">
                               </div>
                               <input type="file" name="foto" id="img1" class="inputUploadfile">
                               <label for="img1" class="btnUploadfile"><i class="fa fa-upload "></i></label>
                               <button class="btnDeleteImage" type="button" onclick="fntDelItem('div24')"><i class="fa fa-trash"></i></button -->
                        </div>
                     </div>

                  </div>
               </div>
               <!-- Pie de formulario para imagenes -->
               <div class="row tile-footer">
                  <div class="form-group col-sm-8">
                  </div>
                  <div class="form-group col-md-2">
                     <button id="btnActionForm" type="submit" class="btn btn-primary btn-block" >   
                        <i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"> </i> 
                        <span id="btnText">Guardar</span> 
                     </button>&nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->
                  </div>
                  <div class="form-group col-md-2">
                     <button class="btn btn-danger btn-block"  data-dismiss="modal">   
                        <i class="fa fa-fw fa-lg fa-times-circle" aria-hidden="true"> </i> 
                        <span>Cerrar</span> 
                     </button>
                  </div>

               </div>

            </form>
            <div class="row" >
               <div class="col-5 " id="pagina_prev" >
                  <!--<button class="page-item page-link pull-left" >«</button>-->
               </div>
               <div class="col-2 align-items-center text-center" id="pagina_poss" >
                  <!--<span class="text-primary align-text-center" > 5 /10 </span>-->
               </div>
               <div class="col-5 " id="pagina_prox" > 
                  <!--<button class=" page-item page-link pull-right" >»</button>-->
               </div>
            </div>
         </div>
      </div>
   </div>
</div>



<!-- Modal Ver Entrada-->
<div class="modal fade-in" id="modalVerEntrada" tabindex="-1" role="dialog"  aria-hidden="true" aria-labelledby="Datos del Entrada">
   <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
         <div class="modal-header header-primary"> <!-- headerRegister   una segunda clase para agregar en los estilos ccs-->
            <h5 class="modal-title" id="titleModal">Datos del Entrada</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body " ><!--div class="tile  container-fluid ">div class="tile-body">  <div class="tile-body"-->

            <!--Tabla ver datos -->
            <table class="table table-bordered">

               <tbody>
                  <tr>
                     <td>Codigo: </td>
                     <td id="celCodigo">11222333</td>
                  </tr>
                  <tr>
                     <td>Nombre: </td>
                     <td id="celNombre">@fat</td>
                  </tr>
                  <tr>
                     <td>Precio: </td>
                     <td id="celPrecio">@fat</td>
                  </tr>
                  <tr>
                     <td>Stock: </td>
                     <td id="celStock">@fat</td>
                  </tr>
                  <tr>
                     <td>Categoria: </td>
                     <td id="celCategoria">xxxxxx</td>
                  </tr>
                  <tr>
                     <td>Estado: </td>
                     <td id="celEstado">xxxxxx</td>
                  </tr>
                  <tr>
                     <td>Descripcion: </td>
                     <td id="celDescripcion">xxxxxx</td>
                  </tr>
                  <tr>
                     <td>Foto de referencia: </td>
                     <td id="celFotos">xxxxxx</td>
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