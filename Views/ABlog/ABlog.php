<?=
headerAdmin($data);
?>

<div id="contentAjax"></div>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-user-circle"></i> <?= $data["page_title"] ?>
                
                <?php if ($_SESSION['userPermiso'][$data["modulo"]]['crear'] == 1) { ?>
                    <!--Boton Nuevo Rol-->
                    <button class="btn btn-primary" type="button" onclick="nvaEntrada()">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        <font style="vertical-align: inherit;">Nuevo</font> 
                    </button>
                <?php } ?>
                    
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url() . 'dashboard'?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item"><a href="<?= base_url() . $data['page_name'] ?>"><?= $data['page_name'] ?></a></li>
        </ul>
    </div>

    <!-- Data Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive ">
                        <table class="table table-hover table-bordered display" style="width:100%" height:100%"  id="tableEntradas">
                            <thead>
                                <tr>
                                    <th scope="col" >Portada</th>
                                    <th scope="col" >Titulo</th>
                                    <th scope="col" >Descripcion</th>
                                    <th scope="col" >Autor</th>
                                    <th scope="col" >Categoria</th>
                                    <th scope="col" >Acciones</th>
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
<?=
getModal('modalABlog', $data); //se llama a al modal
footerAdmin($data)
?>   