<!DOCTYPE html>
<html>
    <head>
        <!-- Meta Base-->
        <meta name="google" content="notranslate"/>
        <meta charset="utf-8">
        <meta name="robots" content="noindex, nofollow, noarchive">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="theme-color" content="#000000">
        <title><?= $data["page_name"] ?></title>

        <link rel = "shortcut icon" href="<?= $data['shortcut_icon'] ?>">

        <!-- Main CSS-->
        <link rel="stylesheet" type="text/css" href="<?= DIR_MEDIA ?>css/main.min.css">
        <link rel="stylesheet" type="text/css" href="<?= DIR_MEDIA ?>css/style.min.css">

        <!-- https://developer.snapappointments.com/bootstrap-select/ -->
        <link rel="stylesheet" type="text/css" href="<?= DIR_MEDIA ?>css/bootstrap-select.min.css">

        <!-- Font-icon css-->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


    </head>
    <body>
        <section class="material-half-bg">
            <div class="cover"></div>
        </section>
        <section class="login-content">
            <div class="logo">
                <h1><?= $data["page_title"] ?></h1>
            </div>

            <div class="login-box">
                <!--Formulario Login-->
                <form class="login-form" name="formRsetParword" id="formRsetParword" action="">
                    <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>Restablecer Contraseña</h3>
                    <input type="hidden" id="idUsuario" name="idUsuario" value="<?= $data["idUser"] ?>"><!-- este elemento estara oculto y su funcion es setear el id del rol a actualizar -->

                    <div class="form-group col-md">
                        <label for="txtPassword" class="control-label"><font style="vertical-align: inherit;">Password</font></label>
                        <input class="form-control" id="txtPassword"name="txtPassword" type="password" autocomplete="off" placeholder="Ingrese la Contraseña"
                               readonly="readonly" onfocus="this.removeAttribute('readonly');">
                    </div>
                    <div class="form-group col-md">
                        <label for="txtPasswordConfirm" class="control-label"><font style="vertical-align: inherit;">Confirmar Password</font></label>
                        <input  class="form-control" id="txtPasswordConfirm" name="txtPasswordConfirm" type="password"  placeholder="Ingrese la Contraseña"
                                readonly="readonly" onfocus="this.removeAttribute('readonly');" >
                    </div>

                    <div class="form-group btn-container">
                        <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>Guardar</button>
                    </div>
                </form>


            </div>
        </section>
        <!-- Essential javascripts for application to work-->
        <script> const base_url = "<?= base_url(); ?>"</script> 

        <script src="<?= DIR_MEDIA ?>js/jquery-3.3.1.min.js"></script>
        <script src="<?= DIR_MEDIA ?>js/popper.min.js"></script>
        <script src="<?= DIR_MEDIA ?>js/bootstrap.min.js"></script>
        <script src="<?= DIR_MEDIA ?>js/main.js"></script>

        <!-- The javascript plugin to display page loading on top-->
        <script src="<?= DIR_MEDIA ?>js/plugins/pace.min.js"></script>
        <!-- SwetAlert Plugins-->
        <script type="text/javascript" src="<?= DIR_MEDIA ?>js/plugins/sweetalert.min.js"></script>

        <!--Ppoyecto -->
        <script src="<?= DIR_MEDIA ?>js/functions_admin.js"></script>
        <?php
        if (isset($data['page_functions_js']) && $data['page_functions_js'] != "") {
            foreach ($data["page_functions_js"] as $value) {
                ?>
                <script type="text/javascript" src="<?= DIR_MEDIA ?>js/<?= $value ?>"></script>
                <?php
            }
        }
        ?>



    </body>
</html>
