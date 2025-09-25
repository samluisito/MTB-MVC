<?php
declare(strict_types=1);
$page_name = $data['header']['page_title'];
$page_name === "ERROR 404" ? header("HTTP/1.0 404 Not Found") : '';
$es_local = strrpos($_SERVER['HTTP_HOST'], 'localhost');
$data_min = '';
if ($es_local !== false) {
  $data_min = $data;
  unset($data_min['empresa']);
}
$empresa = $data['empresa'];

$metodo = $data['path'][0] ?? '';
$url = $data['path'][1] ?? '';
$cantCarrito = $data['cantCarrito'] ?? 0;

$extension = TPO_SERV_LOCAL ? '.css' : '.min.css';
$excluir_ip = $empresa['excluir_ip'] != '' ?
    is_numeric(array_search(getUserIP(), explode(',', $empresa['excluir_ip']))) : null;
   

?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title><?=$page_name ?></title>
    <link rel="icon" type="image/<?= pathinfo($data['empresa']['shortcut_icon'], PATHINFO_EXTENSION) ?>" href="<?= $data['empresa']['url_shortcutIcon'] ?>">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"><!-- Ensures optimal rendering on mobile devices. -->
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> <!-- Optimal Internet -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">


    <?php if (isset($_SESSION['base']['nombre']) && $_SESSION['base']['nombre'] === 'mitiendabit' && $page_name === 'Home') { ?>
      <!-- verificacion de FB suite manager para mitiendabit-->  
      <meta name="facebook-domain-verification" content="trmzlumtx5cdsd86e0xjtl6dnmyxva" />

    <?php } if (isset($data['meta'])) { ?>
      <!-- Buscdores -->
      <meta name="robots" content="<?= $data['meta']['robots'] ?>">
      <meta name="title" content="<?= $data['meta']['title'] ?>">
      <meta name="description" content="<?= $data['meta']['description'] ?>">
      <meta name="keywords" content=<?= $data['meta']['keywords'] ?> >
      <meta name="language" content="Spanish">
      <!--<meta name="revisit-after" content="7 days">--> 
      <meta name="author" content="mitiendabit.com">
      <!-- Open Graph Meta general -->
      <meta property="og:locale" content="es_ES" >
      <meta property="og:type" content="<?= $data['meta']['og:type'] ?>" >
      <meta property="og:title" content="<?= $data['meta']['title'] ?>" >
      <meta property="og:description" content="<?= $data['meta']['description'] ?>" >
      <meta property="og:url" content="<?= $data['meta']['url'] ?>" > 
      <meta property="og:site_name" content="<?= $page_name ?>" >
      <!-- Para twitter -->
      <meta name="twitter:card" content="summary" >
      <meta name="twitter:title" content="<?= $data['meta']['title'] ?>" >
      <meta name="twitter:description" content="<?= $data['meta']['description'] ?>" >
      <meta name="twitter:image" content="<?= $data['meta']['image'] ?>" >

    <?php } ?>
    <script> const sessionlogin = "<?= isset($_SESSION['login']) ? $_SESSION['login'] : 0 ?>"</script> 
    <!--===============================================================================================-->
        <!--<link rel="stylesheet" type="text/css" href="<?= DIR_MEDIA ?>tienda/vendor/animsition/css/animsition.min<?= $extension ?>"  >-->
    <!--===============================================================================================-->
    <link rel="preload stylesheet" as="style"  type="text/css" href="<?= DIR_MEDIA ?>css/normalize<?= $extension ?>"  >
    <link rel="preload stylesheet" as="style"  type="text/css" href="<?= DIR_MEDIA ?>tienda/vendor/bootstrap/css/bootstrap-reboot<?= $extension ?>" >
    <link rel="preload stylesheet" as="style"  type="text/css" href="<?= DIR_MEDIA ?>tienda/vendor/bootstrap/css/bootstrap-grid<?= $extension ?>" >
    <link rel="preload stylesheet" as="style"  type="text/css" href="<?= DIR_MEDIA ?>tienda/css/bootstrap-tienda<?= $extension ?>" >
    <!--<link rel="preload stylesheet" as="style"  type="text/css" href="<?= DIR_MEDIA ?>tienda/vendor/bootstrap/css/bootstrap<?= $extension ?>" >-->
    <!-- ===============================================================================================-->
    <link rel="preload stylesheet" as="style"  type="text/css" href="<?= DIR_MEDIA ?>tienda/fonts/font-awesome-4.7.0/css/font-awesome<?= $extension ?>" >
    <!--===============================================================================================-->	
    <link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA ?>tienda/vendor/css-hamburgers/hamburgers<?= $extension ?>" >
    <!--===============================================================================================-->
    <link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA ?>tienda/vendor/slick/slick<?= $extension ?>" >
    <!--===============================================================================================-->
    <?php if ($page_name == "Home") { ?>
      <link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA ?>tienda/vendor/animate/animate<?= $extension ?>" >
    <?php } if ($page_name == "Carrito" || $page_name == "Procesar Pago") { ?>
      <link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA ?>css/wizardBootstrap<?= $extension ?>">
    <?php } if ($page_name == "Producto") { ?>
      <link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA ?>tienda/vendor/MagnificPopup/magnific-popup<?= $extension ?>" >
      <?php
    } if (isset($data['page_css']) && $data['page_css'] != "") {
      foreach ((array) $data['page_css'] as $stilo) {
        echo '<link rel="preload stylesheet" as="style" type="text/css" href="' . DIR_MEDIA . 'css/' . $stilo . '" >';
      }
    }
    ?>
    <!--===============================================================================================-->
    <link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA ?>tienda/vendor/perfect-scrollbar/perfect-scrollbar<?= $extension ?>" >
    <!--===============================================================================================-->
    <link rel="preload stylesheet" as="style"  type="text/css" href="<?= DIR_MEDIA ?>tienda/css/util<?= $extension ?>" >
    <link rel="preload stylesheet" as="style"  type="text/css" href="<?= DIR_MEDIA ?>tienda/css/main<?= $extension ?>" >
    <!--===============================================================================================-->
    <link rel="preload stylesheet" as="style"  type="text/css" href="<?= DIR_MEDIA ?>css/style-tienda<?= $extension ?>" >
    <!--===============================================================================================-->
    <?php if ($page_name == "enConstruccion") { ?> 
      <!--===============================================================================================-->
      <link rel="preload stylesheet" as="style"  type="text/css" href="<?= DIR_MEDIA ?>css/simplyCountdown<?= $extension ?>" >
    <?php } ?>


    <?php
    if ($empresa['meta_dominio']) {
      echo "<!-- verificacion de FB suite manager-->";
      echo $empresa['meta_dominio'];
    }
    if ($empresa['pixel_facebook'] == 1 && $empresa['pixel_fb_id'] != '' && $excluir_ip != 1) {/* Meta Pixel Code */
      ?> 
      <!-- Meta Pixel Code -->
      <script>
        !function (f, b, e, v, n, t, s) {
          if (f.fbq)
            return;
          n = f.fbq = function () {
            n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments)
          };
          if (!f._fbq)
            f._fbq = n;
          n.push = n;
          n.loaded = !0;
          n.version = '2.0';
          n.queue = [];
          t = b.createElement(e);
          t.async = !0;
          t.src = v;
          s = b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t, s)
        }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '<?= intval($empresa['pixel_fb_id']) ?>');
        fbq('track', 'PageView');
      </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?= intval($empresa['pixel_fb_id']) ?>&ev=PageView&noscript=1" /></noscript>
  <?php } /* End Meta Pixel Code */ ?>
  <?php if ($page_name === 'Home') { ?> 
    <meta name="google-site-verification" content="ePCRDzNrQth3X7D1Pw83vT2Faqw_1f-GjvTt0slxofk" />
  <?php } ?>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-161546351-1">
  </script>
  <script>
  window.dataLayer = window.dataLayer || [];
  function gtag() {
  dataLayer.push(arguments);
  }
  gtag('js', new Date());
  gtag('config', 'UA-161546351-1');
  </script>-->

</head>
<body class="animsition">
  <div id="divLoading" style="display: none;"> 
    <div>
      <img src="<?= DIR_MEDIA; ?>images/loading.svg" alt="Loading">
    </div>
  </div>
  <!-- Header -->
  <header <?= $page_name == 'Home' ? '' : 'class="header-v4"' ?>>
    <!-- Header y Menu Desktop -->
    <div class="container-menu-desktop">
      <!-- Topbar -->
      <div class="top-bar">
        <div class="content-topbar flex-sb-m h-full container">
          <div class="right-top-bar flex-w h-full">
            <?php if (isset($_SESSION['userData'])) { ?>
              <a href="#" class="flex-c-m trans-04 p-lr-25"> <?= $_SESSION['userData']['nombres'] ?> </a>
            <?php } ?>
          </div>
          <div class="right-top-bar flex-w h-full">
            <?php if (isset($_SESSION['userData'])) { ?>
              <?= $_SESSION['userData']['rolid'] = 1 ? ' <a href="' . base_url() . 'dashboard" class="flex-c-m trans-04 p-lr-25"> Administrar </a> ' : ''; ?>
              <a href="<?= base_url() ?>profile" class="flex-c-m trans-04 p-lr-25"> MI CUENTA </a>
              <a href="<?= base_url() ?>logout" class="flex-c-m trans-04 p-lr-25"> SALIR </a>
            <?php } else if ((empty($_SESSION['userData']) && $page_name !== 'Procesar Pago' && $page_name !== 'Registro')) {
              ?>
              <a href="#" type="button" class="flex-c-m trans-04 p-lr-25 js-show-modal1" > LOGIN </a>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="wrap-menu-desktop">
        <nav class="limiter-menu-desktop container">
          <!-- Logo desktop -->		
          <a href="<?= base_url() ?>Home" class="logo">
            <img  src="<?= $empresa['url_logoMenu']; ?>" alt="tienda-virtual"> 
          </a>

          <!-- Menu desktop -->
          <div class="menu-desktop">
            <ul class="main-menu">
              <li class="<?= $page_name == 'home' ? 'active-menu' : '' ?>">
                <a href="<?= base_url() ?>">Inicio</a>
              </li>
              <li class="<?= $page_name == 'tienda' ? 'active-menu' : '' ?>" >
                <a  href="<?= base_url() ?>tienda">Tienda</a> 
                <ul class="sub-menu">
                  <?php foreach ((array) $data['header']['menu_categorias'] as $menuTCat) {
                    ?>
                    <li class="<?= ($url == $menuTCat['ruta']) ? 'active-menu' : '' ?>"> 
                      <a href="<?= base_url() ?>tienda/categoria/<?= $menuTCat['ruta'] ?>">
                        <?= $menuTCat['nombre'] //. ' (' . $menuTCat['cantidad'] . ')' ?> 
                      </a>
                    </li>
                  <?php } ?>
                </ul>
              </li>
              <!--<li class="<?= $page_name == 'carrito' ? 'active-menu' : '' ?>"><a href="<?= base_url() ?>carrito">Carrito</a></li>-->
              <?php if ($es_local !== false) { ?>
                <li class="<?= $page_name == 'blog' ? 'active-menu' : '' ?>"><a href="<?= base_url() ?>blog">Blog</a></li>
              <?php } ?>
              <li class="<?= $page_name == 'contacto' ? 'active-menu' : '' ?>"><a href="<?= base_url() ?>contacto">Contacto</a> </li>
              <?php if ($es_local !== false) { ?>
                  <!--<li  ><a href="<?= base_url() ?>blog">Blog</a></li>-->
                <li  ><a href="#">Sesion</a> <ul class="sub-menu sub-menu-dataserv"><?= dep($_SESSION) ?></ul> </li>
                <li  ><a href="#">Server</a> <ul class="sub-menu sub-menu-dataserv"><?= dep($_SERVER) ?></ul> </li>
                <li  ><a href="#">Data</a> <ul class="sub-menu sub-menu-dataserv"><?= dep($data_min) ?></ul> </li>
              <?php } ?>
            </ul>
          </div>	
          <!-- Icon header -->
          <div class="wrap-icon-header flex-w flex-r-m"></div>
          <?php if ($page_name != "carrito") { ?>
            <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-cart cantCarrito icon-header-noti" data-notify="<?= $cantCarrito > 0 ? $cantCarrito : 0 ?>">
              <i class="fa fa-shopping-cart"></i>
            </div>
          <?php } ?>
        </nav>
      </div>
    </div>	

    <!-- Header Mobile -->
    <div class="wrap-header-mobile">
      <!-- Logo moblie -->		
      <div class="logo-mobile">
        <a href="<?= base_url() ?>home">
          <img  src="<?= $empresa['url_logoMenu']; ?>" alt="tienda-virtual">
        </a>
      </div>

      <!-- Icon header Mobile -->
      <div  class="wrap-icon-header flex-w flex-r-m m-r-15">
        <?php if ($page_name != "Carrito") { ?>
          <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 js-show-cart cantCarrito icon-header-noti" data-notify="<?= $cantCarrito > 0 ? $cantCarrito : 0 ?>">
            <i class="fa fa-shopping-cart"></i>
          </div>
        <?php } ?>
      </div>
      <!-- Button show menu -->
      <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
        <span class="hamburger-box"><span class="hamburger-inner"></span></span>
      </div>
    </div>
    <!-- Menu Mobile -->
    <div  class="menu-mobile">
      <ul class="topbar-mobile">
        <li>
          <div class="right-top-bar flex-w h-full">
            <?php if (isset($_SESSION['userData'])) { ?>
              <a href="#" class="flex-c-m trans-04 p-lr-25">  <?= $_SESSION['userData']['nombres'] ?>  </a>
            <?php } ?>
            <?php if (isset($_SESSION['userData'])) { ?>
              <?= $_SESSION['userData']['rolid'] = 1 ? ' <a href="' . base_url() . 'dashboard" class="flex-c-m trans-04 p-lr-25">  Administrar  </a> ' : ''; ?>
              <a href="<?= base_url() ?>login" class="flex-c-m trans-04 p-lr-25">  Mi Cuenta  </a>
              <a href="<?= base_url() ?>logout" class="flex-c-m trans-04 p-lr-10 "> Salir </a>
            <?php } else { ?>
              <!-- Button trigger modal -->
              <a href="#" type="button" class="flex-c-m trans-04 p-lr-25 js-show-modal1" >   LOGIN   </a> <!-- data-toggle="modal" data-target="#modalLogin"-->
            <?php } ?>
          </div>
        </li>
      </ul>

      <ul class="main-menu-m">
        <!-- Searchs Mobile -->
        <div class="bor17 of-hidden pos-relative ">
          <input class="stext-103 cl2 plh4 size-116 p-l-28 p-r-55" type="search" name="searchM" id="searchM" onkeypress="btnBuscarEnter(event)" placeholder="Buscar" value="<?= isset($data['busqueda']) ? $data['busqueda'] : '' ?>">

          <button class="flex-c-m size-122 ab-t-r fs-18 cl4 hov-cl1 trans-04" onclick="btnBuscar()">
            <i class="fa fa-search"></i>
          </button>
        </div>
        <li class="<?= $page_name == 'home' ? 'm-active-menu' : '' ?>">    <a href="<?= base_url() ?>">Inicio</a> </li>
      <!--li> <a href="<?= base_url() ?>tienda/shoping-cart.html" class="label1 rs1" data-label1="hot">Features</a>  </li-->
        <li class="<?= $page_name == 'tienda' ? 'm-active-menu' : '' ?>">    <a href="<?= base_url() ?>tienda">Tienda</a> 
          <ul class="sub-menu-m">
            <?php foreach ((array) $data['header']['menu_categorias'] as $menuTCat) { ?>
              <li class="<?= $metodo == $menuTCat['ruta'] ? 'm-active-menu' : '' ?>"><a href="<?= base_url() ?>tienda/categoria/<?= $menuTCat['ruta'] ?>"><?= $menuTCat['nombre'] ?> </a></li>
            <?php } ?>
          </ul>
          <span class="arrow-main-menu-m">
            <i class="fa fa-angle-right" aria-hidden="true"></i>
          </span>
        </li>
<!--          <li class="<?= $page_name == 'carrito' ? 'm-active-menu' : '' ?>"><a href="<?= base_url() ?>carrito">Carrito</a> </li>-->
        <?php if ($es_local !== false) { ?>
          <li  class="<?= $page_name == 'blog' ? 'm-active-menu' : '' ?>">     <a href="<?= base_url() ?>blog">Blog</a> </li>
        <?php } ?>
<!--li  class="<?= $page_name == 'nosotros' ? 'm-active-menu' : '' ?>"> <a href="<?= base_url() ?>tienda/nosotros">Nosotros</a> </li-->
        <li class="<?= $page_name == 'contacto' ? 'm-active-menu' : '' ?>">     <a href="<?= base_url() ?>contacto">Contacto</a> </li>
      </ul>
    </div>
    <?php $page_name != 'enConstruccion' ? '</header>' : '' ?>
    <!-- Cart -->

    <?= getModal('modalCarrito', $data) ?>

    <div class="espacio_superior"></div>
  </header>




