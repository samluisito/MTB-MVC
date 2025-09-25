<?=
headerAdmin($data);
; //se llama a al modal
?>

<div class="container-fluid" >
  <div class="row" >
    <div class="col-12" >
      <div class="card" >
        <div class="card-body" >

          <!-- Data Table -->
          <div class="table-responsive"> 
            <table class="table table-hover table-bordered table-striped border display" style="width:99%; height:99%" id="tableClientes">
              <thead>
                <tr>
                  <th class="" scope="col" type="hidden" >Id</th>
                  <th scope="col" >Identificacion</th>
                  <th class="all" scope="col" >Nombre</th>
                  <th class="all" scope="col" >Apellido</th>

                  <th scope="col" >Telefono</th>
                  <th scope="col" scope="col" >Email</th>

                  <th class="all" scope="col" >Status</th>
                  <th class="all" scope="col" class="text-center align-middle sorting">     
                    <?php if ($_SESSION['userPermiso'][$data["modulo"]]['crear'] == 1) { ?>
                      <!--Boton de nuevo Usuario-->
                      <button class="btn btn-success waves-effect waves-light m-auto" type="button" onclick="ftnNvoCte();"><i class="fa fa-plus-circle" aria-hidden="true"></i>Nuevo</button>
                    <?php } ?> 
                  </th>
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


</main>
<?= footerAdmin($data);
getModal('modalClientes', $data)
?>   