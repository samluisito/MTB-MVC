<?php header("HTTP/1.0 500 INTERNAL SERVER ERROR")?>
<!doctype html>
<html lang="es">

  <head>

    <meta charset="utf-8" />
    <title>500 Error Basic | Mi Tienda Bit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Bootstrap Css -->
    <link href="<?= DIR_MEDIA ?>vadmin/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= DIR_MEDIA ?>vadmin/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= DIR_MEDIA ?>vadmin/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <style>
      .auth-bg-basic{
        background-image:url(<?= DIR_MEDIA ?>vadmin/images/pattern-bg.webp);
      }
    </style>
  </head>

  <body>
    <div class="auth-bg-basic d-flex align-items-center min-vh-100">
      <div class="bg-overlay bg-light"></div>
      <div class="container">
        <div class="d-flex flex-column min-vh-100 py-5 px-3">
          <div class="row justify-content-center">
            <div class="col-xl-5">
              <div class="text-center text-muted mb-2">
                <div class="pb-3">
                  <a href="index.html">
                    <span class="logo-lg">
                      <img src="<?= DIR_MEDIA ?>images/upss-error.png" alt="" > <span class="logo-txt"></span>
                    </span>
                  </a>
                  <p class="text-muted font-size-15 w-75 mx-auto mt-3 mb-0"></p>
                </div>
              </div>
            </div>
          </div>

          <div class="row justify-content-center my-auto">
            <div class="col-md-8 col-lg-6 col-xl-7">
              <div class="card bg-transparent shadow-none border-0">
                <div class="card-body">
                  <div class="px-3 py-3 text-center">
                    <h1 class="error-title"><span class="blink-infinite">500</span></h1>
                    <h4 class="text-uppercase">Internal Server Error</h4>
                    <p class="font-size-15 mx-auto text-muted w-75 mt-4">ha ocurrido un error</p>
                    <div class="mt-5 text-center">
                      <a class="btn btn-primary waves-effect waves-light" href="javascript:history.back()">Volver</a>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div><!-- end row -->

          <div class="row">
            <div class="col-xl-12">
              <div class="mt-4 mt-md-5 text-center">
                <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> MiTiendaBit. Hecho con <i class="mdi mdi-heart text-danger"></i> </p>
              </div>
            </div>
          </div> <!-- end row -->
        </div>
      </div>
      <!-- end container fluid -->
    </div>
    <!-- end authentication section -->

    <!-- JAVASCRIPT -->
    <script src="<?= DIR_MEDIA ?>vadmin/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= DIR_MEDIA ?>vadmin/libs/metismenujs/metismenujs.min.js"></script>
    <script src="<?= DIR_MEDIA ?>vadmin/libs/simplebar/simplebar.min.js"></script>
    <script src="<?= DIR_MEDIA ?>vadmin/libs/feather-icons/feather.min.js"></script>

    <!-- JAVASCRIPT -->
    <script src="<?= DIR_MEDIA ?>vadmin/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= DIR_MEDIA ?>vadmin/libs/metismenujs/metismenujs.min.js"></script>
    <script src="<?= DIR_MEDIA ?>vadmin/libs/simplebar/simplebar.min.js"></script>
    <script src="<?= DIR_MEDIA ?>vadmin/libs/feather-icons/feather.min.js"></script>

  </body>

</html>