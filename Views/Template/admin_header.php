<?php
$extension = TPO_SERV_LOCAL ? '.css' : '.min.css';
$notificaciones = $data['notificaciones']
?>
<!DOCTYPE html>
<html lang='es' translate='no'>
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
    <!-- Nosmalize Css -->
    <link rel="preload stylesheet" as="style"  type="text/css" href="<?= DIR_MEDIA ?>css/normalize<?= $extension ?>"  >
    <!--Bootstrap Css--> 
    <link href="<?= DIR_MEDIA ?>vadmin/css/bootstrap<?= $extension ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- fontawesome -->
    <link href="<?= DIR_MEDIA ?>vadmin/css/icons<?= $extension ?>" rel="stylesheet" type="text/css" />
    <!-- Sweetalert-->
    <link href="<?= DIR_MEDIA ?>vadmin/libs/sweetalert2/sweetalert2<?= $extension ?>", id="app-style" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= DIR_MEDIA ?>vadmin/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    <!-- plugin css -->
    <?php
    if (isset($data['page_css']) && $data['page_css'] != '') {
      foreach ($data['page_css'] as $stilo) {
        echo "  <link rel='stylesheet' type='text/css' href='" . DIR_MEDIA . "{$stilo}' >";
      }
    }
    ?>
    <!-- admin Css-->  

    <link rel='stylesheet' type='text/css' href='<?= DIR_MEDIA ?>css/style-admin<?= $extension ?>'>
  </head>

  <body>

    <div id='divLoading' style="display: none" >  
      <img src='<?= DIR_MEDIA; ?>/images/loading.svg' alt='Loading'>     
    </div> 
    <!-- Begin page -->
    <div id="layout-wrapper">

      <header id="page-topbar">
        <div class="navbar-header">
          <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
              <a href="<?= base_url() ?>" class="logo logo-dark">
                <span class="logo-sm">
                  <img src="<?= $data['shortcut_icon'] ?>" alt="" height="26">
                </span>
                <span class="logo-lg">
                  <img src="<?= $data['shortcut_icon'] ?>" alt="" height="26"> <span class="logo-txt"><?= $data['empresa']["nombre_comercial"] ?></span>
                </span>
              </a>

              <a href="<?= base_url() ?>" class="logo logo-light">
                <span class="logo-sm">
                  <img src="<?= $data['shortcut_icon'] ?>" alt="" height="26">
                </span>
                <span class="logo-lg">
                  <img src="<?= $data['shortcut_icon'] ?>" alt="" height="26"> <span class="logo-txt"><?= $data['empresa']["nombre_comercial"] ?></span>
                </span>
              </a>
            </div>
            <!-- Buscador -->
            <button type="button" class="btn btn-sm px-3 header-item vertical-menu-btn noti-icon">
              <i class="fa fa-fw fa-bars font-size-16"></i>
            </button>

            <form class="app-search d-none d-lg-block">
              <div class="position-relative">
                <input type="text" class="form-control" placeholder="Search...">
                <span class="bx bx-search icon-sm"></span>
              </div>
            </form>
            <?php if (strrpos($_SERVER['HTTP_HOST'], 'localhost') !== false) { ?>
              <div class="topnav active">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu active">

                  <div class="collapse navbar-collapse active">

                    <div class="dropdown mt-4 nav-item">
                      <a href="#" class="nav-link dropdown-toggle " data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bxs-server icon"></i> Server <i class="mdi mdi-chevron-down"></i>
                      </a>
                      <div class="dropdown-menu scrollable" style="">
                        <div class="" style="max-height: 80vh;max-width: 60vh;"><?= dep($_SERVER) ?></div>
                      </div>
                    </div>
                    <div class="dropdown mt-4 nav-item">
                      <a href="#" class="nav-link dropdown-toggle " data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-user-circle icon"></i> Sesion <i class="mdi mdi-chevron-down"></i>
                      </a>
                      <div class="dropdown-menu scrollable" style="">
                        <div class="" style="max-height: 80vh;max-width: 60vh;"><?= dep($_SESSION) ?></div>
                      </div>
                    </div>
                    <div class="dropdown mt-4 nav-item">
                      <a href="#" class="nav-link dropdown-toggle " data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bxs-data icon"></i> Data <i class="mdi mdi-chevron-down"></i>
                      </a>
                      <div class="dropdown-menu scrollable" style="">
                        <div class="" style="max-height: 80vh;max-width: 60vh;"><p><?= dep($data) ?></p></div>
                      </div>
                    </div>
                  </div>
              </div>
            <?php } ?>
          </div>

          <div class="d-flex">
            <!--Busqueda tablet telefono-->
            <div class="dropdown d-inline-block d-block d-lg-none">
              <button type="button" class="btn header-item noti-icon"  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="bx bx-search icon-sm"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                <form class="p-2">
                  <div class="search-box">
                    <div class="position-relative">
                      <input type="text" class="form-control rounded bg-light border-0" placeholder="Search...">
                      <i class="bx bx-search search-icon"></i>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <!--Idioma-->
            <div class="dropdown d-inline-block language-switch">
              <button type="button" class="btn header-item noti-icon" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img id="header-lang-img" src="<?= DIR_MEDIA ?>vadmin/images/flags/spain.jpg" alt="Header Language" height="16">
              </button>
              <div class="dropdown-menu dropdown-menu-end">

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item language" data-lang="eng">
                  <img src="<?= DIR_MEDIA ?>vadmin/images/flags/us.jpg" alt="user-image" class="me-2" height="12"> <span class="align-middle">English</span>
                </a>
                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item language" data-lang="sp">
                  <img src="<?= DIR_MEDIA ?>vadmin/images/flags/spain.jpg" alt="user-image" class="me-2" height="12"> <span class="align-middle">Spanish</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item language" data-lang="gr">
                  <img src="<?= DIR_MEDIA ?>vadmin/images/flags/germany.jpg" alt="user-image" class="me-2" height="12"> <span class="align-middle">German</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item language" data-lang="it">
                  <img src="<?= DIR_MEDIA ?>vadmin/images/flags/italy.jpg" alt="user-image" class="me-2" height="12"> <span class="align-middle">Italian</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item language" data-lang="ru">
                  <img src="<?= DIR_MEDIA ?>vadmin/images/flags/russia.jpg" alt="user-image" class="me-2" height="12"> <span class="align-middle">Russian</span>
                </a>
              </div>
            </div>

            <!--Notificaciones-->
            <div class="dropdown d-inline-block">
              <button type="button" class="btn header-item noti-icon" id="page-header-notifications-dropdown"
                      data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="bx bx-bell icon-sm"></i>
                <?php if ($notificaciones['conteototal'] > 0) { ?> 
                  <span class="noti-dot bg-danger rounded-pill"><?= $notificaciones['conteototal'] ?></span>
                <?php } ?>
              </button>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                   aria-labelledby="page-header-notifications-dropdown">

                <!--marcar todas como leidas-->
                <div class="p-3">
                  <div class="row align-items-center">
                    <div class="col">
                      <h5 class="m-0 font-size-15"> Notifications </h5>
                    </div>
                    <div class="col-auto">
                      <!--<a href="javascript:void(0);" class="small"> Mark all as read</a>-->
                    </div>
                  </div>
                </div>

                <div data-simplebar style="max-height: 250px;">
                  <?php if ($notificaciones['conteototal'] > 0) {
                    ?>  <!--divisor nuevo-->
                    <h6 class="dropdown-header bg-light">Nuevo</h6>
                  <?php } ?>

                  <?php
                  if (isset($notificaciones['pedido'])) {
                    $notificacion = $notificaciones['pedido'];
                    if ($notificacion['cantidad'] > 1) {
                      $titulo = "Tienes {$notificacion['cantidad']} pedidos sin leer";
                      $icono = 'uil-shopping-cart-alt';
                    } else {
                      $titulo = "Tiene 1 pedido sin leer";
                      $icono = 'uil-shopping-basket';
                    }
                    ?>  
                    <!--item de notificacion nuevo pedido-->
                    <a href="<?= base_url() ?>pedidos" class="text-reset notification-item">
                      <div class="d-flex border-bottom align-items-start">
                        <div class="flex-shrink-0">
                          <div class="avatar-sm me-3">
                            <span class="avatar-title bg-primary rounded-circle font-size-16">
                              <i class="<?= $icono ?>"></i>
                            </span>
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-1"><?= $titulo ?></h6>
                          <div class="text-muted">
                            <!--<p class="mb-1 font-size-13">Abrir para confirmar pedidos.</p>-->
                            <p class="mb-0 font-size-10 text-uppercase fw-bold"><i class="mdi mdi-clock-outline"></i>Hace <?= $notificacion['fecha'] ?></p>
                          </div>
                        </div>
                      </div>
                    </a>
                  <?php } ?>
                  <?php
                  if (isset($notificaciones['contacto'])) {
                    $notificacion = $notificaciones['contacto'];
                    if ($notificacion['cantidad'] > 1) {
                      $titulo = "Tienes {$notificacion['cantidad']} nuevos contactos sin leer";
                      $icono = 'uil-users-alt';
                    } else {
                      $titulo = "Tiene 1 nuevo contacto sin leer";
                      $icono = 'uil-user';
                    }
                    ?>  
                    <a href="<?= base_url() ?>aContactos" class="text-reset notification-item">
                      <div class="d-flex border-bottom align-items-start">
                        <div class="flex-shrink-0">
                          <div class="avatar-sm me-3">
                            <span class="avatar-title bg-primary rounded-circle font-size-16">
                              <i class="<?= $icono ?>"></i>
                            </span>
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-1"><?= $titulo ?></h6>
                          <div class="text-muted">
                            <!--<p class="mb-1 font-size-13">Abrir contactos.</p>-->
                            <p class="mb-0 font-size-10 text-uppercase fw-bold"><i class="mdi mdi-clock-outline"></i>Hace <?= $notificacion['fecha'] ?></p>
                          </div>
                        </div>
                      </div>
                    </a>
                  <?php } ?>



                  <?php if (isset($notif_pedido['tipo']) && $notif_pedido['tipo'] === 'xxx') { ?>
                    <!--divisor-->
                    <h6 class="dropdown-header bg-light">Más temprano</h6>

                    <!--item de notificacion actualizacion de proceso-->
                    <a href="" class="text-reset notification-item">
                      <div class="d-flex border-bottom align-items-start">
                        <div class="flex-shrink-0">
                          <img src="<?= DIR_MEDIA ?>vadmin/images/users/avatar-3.jpg"
                               class="me-3 rounded-circle avatar-sm" alt="user-pic">
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-1">Justin Verduzco</h6>
                          <div class="text-muted">
                            <p class="mb-1 font-size-13">Su tarea cambió un problema de "En curso" a <span class="badge badge-soft-success">En Revision</span></p>
                            <p class="mb-0 font-size-10 text-uppercase fw-bold"><i class="mdi mdi-clock-outline"></i> 1 hours ago</p>
                          </div>
                        </div>
                      </div>
                    </a>
                    <!--item de notificacion pedido en evio-->
                    <a href="" class="text-reset notification-item">
                      <div class="d-flex border-bottom align-items-start">
                        <div class="flex-shrink-0">
                          <div class="avatar-sm me-3">
                            <span class="avatar-title bg-soft-success text-success rounded-circle font-size-16">
                              <i class="uil-truck"></i>
                            </span>
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-1">Your item is shipped</h6>
                          <div class="text-muted">
                            <p class="mb-1 font-size-13">Here is somthing that you might light like to know.</p>
                            <p class="mb-0 font-size-10 text-uppercase fw-bold"><i class="mdi mdi-clock-outline"></i> 1 day ago</p>
                          </div>
                        </div>
                      </div>
                    </a>

                    <!--item de notificacion pedido en evio-->
                    <a href="" class="text-reset notification-item">
                      <div class="d-flex border-bottom align-items-start">
                        <div class="flex-shrink-0">
                          <img src="<?= DIR_MEDIA ?>vadmin/images/users/avatar-4.jpg"
                               class="me-3 rounded-circle avatar-sm" alt="user-pic">
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-1">Salena Layfield</h6>
                          <div class="text-muted">
                            <p class="mb-1 font-size-13">Yay ! Everything worked!</p>
                            <p class="mb-0 font-size-10 text-uppercase fw-bold"><i class="mdi mdi-clock-outline"></i> 3 days ago</p>
                          </div>
                        </div>
                      </div>
                    </a>
                  <?php } ?>
                </div>
                <div class="p-2 border-top d-grid">
                  <a class="btn btn-sm btn-link font-size-14 btn-block text-center" href="javascript:void(0)">
                    <!--<i class="uil-arrow-circle-right me-1"></i> <span>Ver mas..</span>-->
                  </a>
                </div>
              </div>
            </div>

            <!--Usuario Logueado-->
            <div class="dropdown d-inline-block">
              <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown"
                      data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle header-profile-user" src="<?= DIR_MEDIA ?>vadmin/images/users/avatar-3.jpg"
                     alt="Header Avatar">
                <span class="ms-2 d-none d-xl-inline-block user-item-desc">
                  <span class="user-name"><?= $_SESSION['userData']['nombres'] ?>. <i class="mdi mdi-chevron-down"></i></span>
                </span>
              </button>
              <div class="dropdown-menu dropdown-menu-end pt-0">
                <h6 class="dropdown-header">Welcome <?= $_SESSION['userData']['nombres'] ?>!</h6>
                <a class="dropdown-item" href="<?= base_url() ?>usuarios/perfil"><i class="mdi mdi-account-circle text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
                <a class="dropdown-item" href="#"><i class="mdi mdi-message-text-outline text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Messages</span></a>
                <a class="dropdown-item" href="#"><i class="mdi mdi-calendar-check-outline text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Taskboard</span></a>
                <a class="dropdown-item" href="#"><i class="mdi mdi-lifebuoy text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Help</span></a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#"><i class="mdi mdi-wallet text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Balance : <b>$6951.02</b></span></a>
                <a class="dropdown-item d-flex align-items-center" href="<?= base_url() ?>configuracion"><i class="mdi mdi-cog-outline text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Settings</span><span class="badge badge-soft-success ms-auto">New</span></a>
                <a class="dropdown-item" href="#"><i class="mdi mdi-lock text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Lock screen</span></a>
                <a class="dropdown-item" href="<?= base_url() ?>logout"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Logout</span></a>
              </div>
            </div>

            <!--Seting Session template-->
            <div class="dropdown d-inline-block">
              <button type="button" class="btn header-item noti-icon right-bar-toggle" id="right-bar-toggle">
                <i class="bx bx-cog icon-sm"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- start dashtroggle -->
        <div class="collapse show verti-dash-content" id="dashtoggle">
          <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
              <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                  <h4 class="mb-0"><?= $data['page_name'] ?></h4>

                  <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                      <li class="breadcrumb-item"><a href="javascript: void(0);"><i class="bx bx-home-circle"></i></a></li>
                      <li class="breadcrumb-item active"><?= $data['page_name'] ?></li>
                    </ol>
                  </div>

                </div>
              </div>
            </div>
            <!-- end page title -->
            <?php if ($data['page_name'] === 'Dashboard') { ?>
              <!-- start dash info -->
              <div class="row">
                <div class="col-xl-12">
                  <div class="card dash-header-box shadow-none border-0">
                    <div class="card-body p-0">
                      <div class="row row-cols-xxl-6 row-cols-md-3 row-cols-1 g-0">
                        <div class="col">
                          <div class="mt-md-0 py-3 px-4 mx-2">
                            <p class="text-white-50 mb-2 text-truncate">Visitas </p>
                            <h3 class="text-white mb-0"><?= $data['wVisitas'] ?></h3>
                          </div>
                        </div><!-- end col -->

                        <div class="col">
                          <div class="mt-3 mt-md-0 py-3 px-4 mx-2">
                            <p class="text-white-50 mb-2 text-truncate">Clientes</p>
                            <h3 class="text-white mb-0"><?= $data['wClientes'] ?></h3>
                          </div>
                        </div><!-- end col -->

                        <div class="col">
                          <div class="mt-3 mt-md-0 py-3 px-4 mx-2">
                            <p class="text-white-50 mb-2 text-truncate">Productos</p>
                            <h3 class="text-white mb-0"><?= $data['wProductos'] ?></h3>
                          </div>
                        </div><!-- end col -->

                        <div class="col">
                          <div class="mt-3 mt-md-0 py-3 px-4 mx-2">
                            <p class="text-white-50 mb-2 text-truncate">Pedidos</p>
                            <h3 class="text-white mb-0"><?= $data['wPedidos'] ?></h3>
                          </div>
                        </div><!-- end col -->

                        <div class="col">
                          <div class="mt-3 mt-lg-0 py-3 px-4 mx-2">
                            <p class="text-white-50 mb-2 text-truncate">Dolar <?= $_SESSION['base']['region_abrev'] == 'VE' ? 'Oficial' : 'Blue' ?></p> 
                            <p class="text-white-50 mb-2 text-truncate"></p> 
                            <h3 class="text-white mb-0"> <?= SMONEY . $_SESSION['dolarhoy']['precio'] ?></h3>
                          </div>
                        </div><!-- end col -->

                        <div class="col">
                          <div class="mt-3 mt-lg-0 py-3 px-4 mx-2">
                            <p class="text-white-50 mb-2 text-truncate">Dolar al: </p>
                            <h3 class="text-white mb-0"><?= $_SESSION['dolarhoy']['fecha'] ?></h3>
                          </div>
                        </div>
                        <!-- end col -->

                      </div><!-- end row -->
                    </div><!-- end card body -->
                  </div><!-- end card -->
                </div><!-- end col -->
              </div>
              <!-- end dash info -->
            <?php } ?>
          </div>
        </div>
        <!-- end dashtroggle -->

        <!-- start dashtroggle-icon -->
        <div>
          <a class="dash-troggle-icon" id="dash-troggle-icon" data-bs-toggle="collapse" href="#dashtoggle" aria-expanded="true" aria-controls="dashtoggle" onclick="saveBarSession()">
            <i class="bx bx-up-arrow-alt"></i>
          </a>
        </div>
        <!-- end dashtroggle-icon -->

      </header>

      <!-- ========== Left Sidebar Start ========== -->
      <div class="vertical-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
          <a href="<?= base_url() ?>" class="logo logo-dark">
            <span class="logo-sm">
              <img src="<?= $data['shortcut_icon'] ?>" alt="" height="26">
            </span>
            <span class="logo-lg">
              <img src="<?= $data['shortcut_icon'] ?>" alt="" height="26"> <span class="logo-txt"><?= $data['empresa']['nombre_comercial'] ?></span>
            </span>
          </a>

          <a href="<?= base_url() ?>" class="logo logo-light">
            <span class="logo-sm">
              <img src="<?= $data['shortcut_icon'] ?>" alt="" height="26">
            </span>
            <span class="logo-lg">
              <img src="<?= $data['shortcut_icon'] ?>" alt="" height="26"> <span class="logo-txt"><?= $data['empresa']['nombre_comercial'] ?></span>
            </span>
          </a>
        </div>

        <button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn">
          <i class="fa fa-fw fa-bars"></i>
        </button>

        <div data-simplebar class="sidebar-menu-scroll">

          <!--- Sidemenu -->
          <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
              <li class="menu-title" data-key="t-menu">Menu</li>
              <?php if ($_SESSION['userData']['rolid'] != 2) { ?>
                <li>
                  <a href="<?= base_url() ?>Dashboard">
                    <i class="bx bx-home-circle nav-icon"></i>
                    <span class="menu-item" data-key="t-dashboard">Dashboard</span>
                  </a>
                </li>
              <?php } ?>
              <li class="menu-title" data-key="t-applications">Applications</li>

              <li>
                <a href="javascript: void(0);" class="has-arrow">
                  <i class="bx bx-shield-quarter nav-icon"></i>
                  <span class="menu-item" data-key="t-ecommerce">Ecommerce</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                  <li><a href="<?= base_url() ?>productos" data-key="t-products">Products</a></li>
                  <!--<li><a href="ecommerce-product-detail.html" data-key="t-product-detail">Product Detail</a></li>-->
                  <li><a href="<?= base_url() ?>pedidos" data-key="t-orders">Orders</a></li>
                  <li><a href="<?= base_url() ?>clientes" data-key="t-customers">Customers</a></li>
                  <li><a href="<?= base_url() ?>categorias" data-key="t-categorias">Categorias</a></li>
                  <li><a href="<?= base_url() ?>productos/proveedores" data-key="t-proveedor">Proveedores</a></li>
                  <!--<li><a href="ecommerce-checkout.html" data-key="t-checkout">Checkout</a></li>-->
                  <!--<li><a href="ecommerce-shops.html" data-key="t-shops">Shops</a></li>-->
                  <!--<li><a href="ecommerce-add-product.html" data-key="t-add-product">Add Product</a></li>-->
                </ul>
              </li>


              <li>
                <a href="javascript: void(0);" class="has-arrow">
                  <i class="bx bx-book nav-icon"></i>
                  <span class="menu-item" data-key="t-contacts">Contacts</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                  <!--<li><a href="contacts-grid.html" data-key="t-user-grid">User Grid</a></li>-->
                  <li><a href="<?= base_url() ?>aContactos" >Contactos</a></li>
                  <li><a href="<?= base_url() ?>usuarios" data-key="t-user-list">User List</a></li>
                  <li><a href="<?= base_url() ?>roles" data-key="t-user-roles">Roles</a></li>
                  <!--<li><a href="<?= base_url() ?>configuracion" data-key="t-user-settings">User Settings</a></li>-->
                </ul>
              </li>



              <li>
                <a href="javascript: void(0);" class="has-arrow">
                  <i class="mdi mdi-cog-outline nav-icon"></i>
                  <span class="menu-item" data-key="t-setting">Setting</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                  <li><a href="<?= base_url() ?>configuracion" data-key="t-setting">Configuracion</a></li>
                  <li><a href="<?= base_url() ?>homebanner" >Home Banner</a></li>
                  <li><a href="<?= base_url() ?>configuracion/tiposdepago" data-key="t-user-roles">Tipos de Pago</a></li>
                  <li><a href="<?= base_url() ?>modulos" data-key="t-modules">Modules</a></li>
                </ul>
              </li>



            </ul>
          </div>
          <!-- Sidebar -->
        </div>
      </div>
      <!-- Left Sidebar End -->

      <!-- ============================================================== -->
      <!-- Start right Content here -->
      <!-- ============================================================== -->
      <div class="main-content">

        <!-- Start Page-content -->
        <div class="page-content">