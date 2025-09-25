<?=
headerAdmin($data);
//getModal('modalClientes', $data); //se llama a al modal
?>

<div class="container-fluid" >
  <div class="row" >
    <div class="col-12" >
      <div class="card" >
        <div class="card-body" >
          <!-- Data Table -->
          <div class="table-responsive"> 
            <table class="table table-hover table-bordered table-striped border display" style="width:99%; height:99%"id="tableContactos">
              <thead>
                <tr>
                  <th class="" scope="col" type="hidden" >id</th>
                  <th class="all" scope="col"  >Origen</th>
                  <th class="all" scope="col" >Nombre</th>
                  <th class="all" scope="col" >Apellido</th>
                  <th scope="col" >Telefono</th>
                  <th scope="col" scope="col" >Email</th>
                  <th class="all" scope="col" >localidad</th>
                  <th class="all" scope="col" >Fecha</th>
                  <th class="all" scope="col" >Acciones</th>
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
getModal('modalContacto', $data);
footerAdmin($data);
?>   