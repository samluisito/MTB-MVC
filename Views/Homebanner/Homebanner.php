<?=
headerAdmin($data);
?>
<div class="container-fluid" >
  <div class="row" >
    <div class="col-12" >
      <div class="card" >
        <div class="card-body" >
          <!-- Data Table -->
          <div class="table-responsive"> 
            <table class="table table-hover table-bordered table-striped border display" style="width:99%; height:99%" id="tableBanners">
              <thead>
                <tr>
                  <th scope="col" >img</th>
                  <th scope="col" >Nombre</th>
                 <!-- <th scope="col" >Descripcion</th>-->
                  <th scope="col" >Tipo</th>
                 <!-- <th scope="col" >Estado</th>-->
                  <th class="all" scope="col" class="text-center align-middle sorting"> 
                    <?php if ($_SESSION['userPermiso'][$data["modulo"]]['crear'] == 1) { ?>
                      <!--Boton de nuevo Usuario-->
                      <button class="btn btn-success waves-effect waves-light m-auto" type="button" onclick="nvoBanner();"><i class="fa fa-plus-circle" aria-hidden="true"></i>Nuevo</button>
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



<?=
include_once 'modalHomebanner.php';
getModal('modalCropper', $data); //se llama a al modal
footerAdmin($data)
?> 