<?php
// Obtener la extensión del archivo CSS
$css = TPO_SERV_LOCAL ? '.min.css' : '.min.css';
$js = TPO_SERV_LOCAL ? '.min.js' : '.min.js';

$empresa = $data['empresa'];
?>

<!DOCTYPE html>
<html>
  <head>
    <!-- Meta Base-->
    <title><?= $data['page_title'] ?></title>
    <meta charset='utf-8'>
    <meta name='google' content='notranslate'/>
    <meta name='robots' content='noindex, nofollow, noarchive'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--------------------------->
    <!-- App favicon -->
    <link rel='shortcut icon' href='<?= $data['shortcut_icon'] ?>' type='image/<?= pathinfo($data['shortcut_icon'], PATHINFO_EXTENSION) ?>' >

    <!-- Bootstrap Css -->
    <link href="<?= DIR_MEDIA ?>vadmin/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- fontawesome -->
    <link href="<?= DIR_MEDIA ?>vadmin/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= DIR_MEDIA ?>vadmin/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= DIR_MEDIA ?>vadmin/libs/sweetalert2/sweetalert2.min.css"rel="stylesheet" type="text/css" />
    <?php
    if (isset($data['page_css']) && $data['page_css'] != '') {
      foreach ($data['page_css'] as $stilo) {
        echo "  <link rel='stylesheet' type='text/css' href='" . DIR_MEDIA . "{$stilo}' >";
      }
    }
    ?>

    <style>
      .auth-bg{
        background-image:url(<?= DIR_MEDIA ?>vadmin/images/pattern-bg.webp)
      }
    </style>

  </head>
  <body class="bg-white">

    <div class="auth-page d-flex align-items-center min-vh-100">
      <div class="container-fluid p-0">
        <div class="row g-0">
          <div class="col-xxl-3 col-xl-4 col-lg-5 col-md-5">
            <div class="d-flex flex-column h-100 py-5 px-4 ">
              <div class="text-center text-muted mb-2">
                <div class="pb-3">
                  <a href="<?= BASE_URL ?>">
                    <span class="logo-lg">
                      <img src="<?= $data['logo_desktop'] ?>" alt="" height="24"> <span class="logo-txt"><?= $empresa["nombre_comercial"] ?></span>
                    </span>
                  </a>
                  <p class="text-muted font-size-15 w-75 mx-auto mt-3 mb-0"><?= $empresa["descripcion"] ?></p>
                </div>
              </div>

              <div class="my-auto">
                <div class="p-3 text-center">
                  <img src="<?= DIR_MEDIA ?>vadmin/images/auth-img.webp" alt="" class="img-fluid">
                </div>
              </div>

              <div class="mt-4 mt-md-5 text-center">
                <p class="mb-0"> <!--© <script>document.write(new Date().getFullYear())</script>-->Hecho con <i class="mdi mdi-heart text-danger"></i> por <?= $empresa["nombre_comercial"] ?>.</p>
              </div>
            </div>

            <!-- end auth full page content -->
          </div><!-- end col -->

          <div class="col-xxl-9 col-xl-8 col-lg-7 col-md-7">
            <div class="auth-bg bg-light py-md-5 p-4 d-flex">
              <div class="bg-overlay-gradient"></div>
              <!-- end bubble effect -->
              <div class="row justify-content-center g-0 align-items-center w-100">
                <div class="col-xxl-4 col-xl-6 col-lg-8 col-md-10">


                  <div class="card" >
                    <div class="card-body d-none" id="card_reset_pass">
                      <div class="px-3 py-3">
                        <div class="text-center">
                          <h5 class="mb-0">Restablecer la contraseña</h5>
                          <a href="#"id="volver_login" class="text-muted text-decoration-underline font-size-14">Volver al Login</a>
                        </div>
                        <div class="alert font-size-14 alert-success text-center mb-3 mt-5" role="alert">
                          ¡Ingrese su correo electrónico y se le enviarán las instrucciones!
                        </div>
                        <form class="mt-3" id="formForgetPassword" >
                          <div class="form-floating form-floating-custom mb-3">
                            <input type="email" class="form-control" id="input-email" placeholder="Enter Email">
                            <label for="input-email">Email</label>
                            <div class="form-floating-icon"><i class="uil uil-envelope-alt"></i></div>
                          </div>
                          <div class="mt-4">
                            <a href="" class="btn btn-primary w-100">Enviar</a>
                          </div>
                        </form><!-- end form -->
                      </div>
                    </div>
                    <div class="card-body" id="card_login">
                      <div class="px-3 py-3">
                        <div class="text-center">
                          <h5 class="mb-0">Bienvenido !</h5>
                          <p class="text-muted mt-2">Inicia sesión para continuar.</p>
                        </div>
                        <form class="mt-4 pt-2" id="formLogin" >
                          <div class="form-floating form-floating-custom mb-3">
                            <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="Escribe tu Email" autofocus>
                            <label for="input-username">Username</label>
                            <div class="form-floating-icon"> <i class="uil uil-users-alt"></i></div>
                          </div>
                          <div class="form-floating form-floating-custom mb-3 auth-pass-inputgroup">
                            <input type="password" class="form-control" id="txtPassword" name="txtPassword" placeholder="Escribe el Password">
                            <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon"><i class="mdi mdi-eye-outline font-size-18 text-muted"></i></button>
                            <label for="password-input">Password</label>
                            <div class="form-floating-icon"><i class="uil uil-padlock"></i></div>
                          </div>
                          <div class="form-check form-check-primary font-size-16 py-1">
                            <input class="form-check-input" type="checkbox" id="recuerdameCheck" name="recuerdame">
                            <div class="float-end">
                              <a href="#" id="olvide_contraseña" class="text-muted text-decoration-underline font-size-14">Olvidate la Contraseña?</a>
                            </div>
                            <label class="form-check-label font-size-14" for="remember-check">Recordar login</label>
                          </div>
                        </form><!-- end form -->

                        <div class="mt-3">
                          <button class="btn btn-primary w-100" onClick="login()" >Iniciar Sesion</button>
                        </div>

                        <div class="mt-4 text-center">
                          <div class="signin-other-title">
                            <h5 class="font-size-15 mb-4 text-muted fw-medium">- O puedes loguearte con -</h5>
                          </div>

                          <div class="d-flex gap-2">
                            <?php if ($empresa['login_facebook']) { ?>
                              <button type="button" class="btn btn-soft-primary waves-effect waves-light w-100" onclick="loginFb()">
                                <i class="bx bxl-facebook font-size-16 align-middle"></i>
                              </button>
                            <?php } ?>

                            <!--<button type="button" class="btn btn-soft-info waves-effect waves-light w-100">
                                  <i class="bx bxl-linkedin font-size-16 align-middle"></i> 
                                </button>
                                <button type="button" class="btn btn-soft-danger waves-effect waves-light w-100">
                                  <i class="bx bxl-google font-size-16 align-middle"></i> 
                                </button>-->
                          </div>
                        </div>

                        <div class="mt-4 pt-3 text-center z">
                          <p class="text-muted mb-0">¿No tienes una cuenta? <a href="<?= base_url() . 'registro' ?>" class="fw-semibold text-decoration-underline"> Regístrate ahora</a> </p>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- end col -->
        </div>
        <!-- end row -->
      </div>
      <!-- end container fluid -->
    </div>
    <!-- end authentication section -->
    <script>
      document.getElementById('olvide_contraseña').onclick = () => {
        document.getElementById('card_reset_pass').classList.remove('d-none');
        document.getElementById('card_login').classList.add('d-none');
      };
      document.getElementById('volver_login').onclick = () => {
        document.getElementById('card_reset_pass').classList.add('d-none');
        document.getElementById('card_login').classList.remove('d-none');
      };

    </script>
    <!-- Essential javascripts for application to work-->
    <script src="<?= DIR_MEDIA ?>vadmin/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= DIR_MEDIA ?>vadmin/libs/metismenujs/metismenujs.min.js"></script>
    <script src="<?= DIR_MEDIA ?>vadmin/libs/simplebar/simplebar.min.js"></script>
    <script src="<?= DIR_MEDIA ?>vadmin/libs/feather-icons/feather.min.js"></script>
    <script src="<?= DIR_MEDIA ?>vadmin/libs/sweetalert2/sweetalert2.min.js"></script>

    <script >const base_url = "<?= base_url() ?>";</script>
    <script >const media = "<?= DIR_MEDIA ?>";</script>

    <?php if ($empresa['login_facebook'] === 1) { ?> 
      <script > const app_id = <?= $empresa['id_app_fb'] ?></script>
      <script async type='text/javascript' src="<?= DIR_MEDIA ?>js/functions_login_FB<?= $js ?>"></script>
    <?php } ?>
    <!--===============================================================================================-->
    <?php
    if (isset($data['page_functions_js']) && $data['page_functions_js'] != '') {
      foreach ($data['page_functions_js'] as $value) {
        ?>
        <script defer type='text/javascript' src='<?= DIR_MEDIA . $value . $js ?>'></script>
        <?php
      }
    }
    ?>
    <script src="<?= DIR_MEDIA ?>js/functions_login.js"></script>
  </body>
</html>
