<?= headerAdmin($data); ?>
<!--<div id="contentAjax"></div>-->
<!-- Data Table -->
<div class="row">
  <div class="col-lg-12">
    <div id="addproduct-accordion" class="custom-accordion">
      <div class="card">
        <a href="#addproduct-billinginfo-collapse" class="text-dark" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-billinginfo-collapse"></a>
        <div class="p-4">
          <form id="formFiltroTableProductos" >
            <div class="row">
              <div class="col-xl-4">
                <div class="mb-3">
                  <label for="filtro_categoria">Categoria</label>
                  <select class="form-control" id="filtro_categoria" data-trigger></select>
                </div>
              </div>
              <div class="col-xl-4">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="control-label" for="filtro_montoMinimo">Monto minimo</label>
                      <div class="mb-3">
                        <label class="sr-only" for="filtro_montoMinimo">Monto mini (in dollars)</label>
                        <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                          <input class="form-control" id="filtro_montoMinimo" type="text" placeholder="Monto Minimo">
                          <div class="input-group-append"><span class="input-group-text">.00</span></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="control-label"for="filtro_montoMaximo">Input addons</label>
                      <div class="mb-3">
                        <label class="sr-only" for="filtro_montoMaximo">Amount (in dollars)</label>
                        <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                          <input class="form-control" id="filtro_montoMaximo" type="text" placeholder="Monto Maximo">
                          <div class="input-group-append"><span class="input-group-text">.00</span></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="filtro_estado" for="filtro_estado">Estado</label>
                      <select class="form-select" id="filtro_estado">
                        <option value='t'>Todos</option>
                        <option value='a'selected="selected">Activo</option>
                        <option value='i'>Inactivo</option>
                      </select>
                    </div>    
                  </div>    
                  <div class="col-md-6">
                    <div class="mt-4">
                      <button class="btn btn-primary m-1" type="submit"><i class="fa fa-filter" aria-hidden="true"></i> Filtrar</button>
                      <button  class="btn btn-primary m-1" type="" onclick="csvFB()"><i class="fas fa-file-csv" aria-hidden="true"></i> CSV FB</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div id="addproduct-accordion" class="custom-accordion">
      <div class="card">
        <a href="#addproduct-billinginfo-collapse" class="text-dark" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-billinginfo-collapse"></a>
        <div class="p-4">
          <div class="table-responsive">
            <table class="table display" style="width:100%"  id="tableProductos">
              <thead>
                <tr>
                  <th scope="col" ></th>
                  <th scope="col" >
                    <?php if ($_SESSION['userPermiso'][$data["modulo"]]['crear'] == 1) { ?>
                      <!--Boton Nuevo-->
                      <button class="btn btn-success waves-effect waves-light mb-2 me-2" type="button" onclick="nvoProducto()">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo</button>
                      <?php } ?>
                  </th>
                  <th scope="col" >Codigo</th>
                  <th scope="col" >Nombre</th>
                  <th scope="col" >Categoria</th>
                  <th scope="col" >Precio</th>
                  <th scope="col" >Stock</th>
                  <th scope="col" >Acciones</th>
                  <th scope="col" >descripcion</th>
                  <th scope="col" >url_img</th>
                  <th scope="col" >ruta</th>
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
</div>


</div>
</main>
<?=
require __DIR__ . '/modalProductos.php'; //se llama a al modal
require __DIR__ . '/modalProveedor.php'; //se llama a al modal
getModal('modalCropper', $data); //se llama a al modal
footerAdmin($data)
?>   