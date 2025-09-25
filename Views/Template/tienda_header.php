<?php
declare(strict_types=1);
extract($data);

// Obtener el nombre de la página
$page_name = $header['page_title']; // Obtener el nombre de la página
// Si el nombre de la página es ERROR 404, enviar encabezado 404
$page_name === "ERROR 404" ? header("HTTP/1.0 404 Not Found") : '';

// Verificar si la página se está ejecutando localmente
$es_local = strrpos($_SERVER['HTTP_HOST'], 'localhost');

// Si la página se está ejecutando localmente, omitir la empresa
$data_min = '';
if ($es_local !== false) {
  $data_min = $data;
  unset($data_min['empresa']);
}

// Obtener la información de la empresa
$empresa = $data['empresa'];

// Obtener el método y la URL pra mostrar en el menu qu eesta activo
$metodo = $data['path'][0] ?? '';
$url = $data['path'][1] ?? '';

// Obtener la cantidad de productos en el carrito
$cantCarrito = $header['cantCarrito'] ?? 0;

// Obtener la extensión del archivo CSS
$extension = TPO_SERV_LOCAL ? '.min.css' : '.min.css';

// Verificar si la dirección IP debe ser excluida
$excluir_ip = $empresa['excluir_ip'] != '' ?
    is_numeric(array_search(getUserIP(), explode(',', $empresa['excluir_ip']))) : null;

$session = $_SESSION;
$rolid = $session['userData']['rolid'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Metadatos -->
    <meta charset="UTF-8">
    <title><?= $page_name ?></title>
    <!-- Icono de la página -->
    <link rel="icon" type="image/<?= pathinfo($data['empresa']['shortcut_icon'], PATHINFO_EXTENSION) ?>" href="<?= $data['empresa']['url_shortcutIcon'] ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- Ensures optimal rendering on mobile devices. -->
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> <!-- Optimal Internet -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <?php
// Verificar si la sesión está iniciada y si el nombre de la base es 'mitiendabit'
    if (isset($session['base']['nombre']) && $session['base']['nombre'] === 'mitiendabit' && $page_name === 'Home') {
      ?>
      <!-- Verificación de Google y Facebook suite manager -->
      <meta name="google-site-verification" content="ePCRDzNrQth3X7D1Pw83vT2Faqw_1f-GjvTt0slxofk" />
      <meta name="facebook-domain-verification" content="trmzlumtx5cdsd86e0xjtl6dnmyxva" />
      <!-- Etiquetas adicionales para buscadores 
      <meta name="msvalidate.01" content="código de verificación de Bing" />
      <meta name="yandex-verification" content="código de verificación de Yandex" /> -->
    <?php } ?>

    <?php if (isset($data['meta'])) { ?>
      <!-- Etiquetas para buscadores -->
      <meta name="robots" content="<?= $data['meta']['robots'] ?>">
      <meta name="title" content="<?= $data['meta']['title'] ?>">
      <meta name="description" content="<?= $data['meta']['description'] ?>">
      <meta name="keywords" content="<?= $data['meta']['keywords'] ?>" >
      <meta name="language" content="Spanish">
      <meta name="revisit-after" content="7 days"> 
      <meta name="author" content="mitiendabit.com">

      <!-- Etiquetas adicionales para mejorar el posicionamiento -->
      <meta name="googlebot" content="index,follow">
      <meta name="bingbot" content="index,follow">
      <meta name="robots" content="index,follow">
      <meta name="referrer" content="no-referrer-when-downgrade">
      <!--<link rel="canonical" href="<?= 'canonical'// $data['meta']['canonical']                     ?>" />-->

      <!-- Etiquetas para Open Graph (Facebook) -->
      <meta property="og:locale" content="es_AR" >
      <meta property="og:type" content="<?= $data['meta']['og:type'] ?>" >
      <meta property="og:title" content="<?= $data['meta']['title'] ?>" >
      <meta property="og:description" content="<?= $data['meta']['description'] ?>" >
      <meta property="og:url" content="<?= $data['meta']['url'] ?>" > 
      <meta property="og:site_name" content="<?= $page_name ?>" >
      <meta property="og:image" content="<?= $data['meta']['image'] ?>" >

      <!-- Etiquetas para Twitter -->
      <meta name="twitter:card" content="summary" >
      <meta name="twitter:title" content="<?= $data['meta']['title'] ?>" >
      <meta name="twitter:description" content="<?= $data['meta']['description'] ?>" >
      <meta name="twitter:image" content="<?= $data['meta']['image'] ?>" >
    <?php }//endif; ?>




  <!--<link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA . 'css/normalize' . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">-->
  <!--<link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA . 'tienda/vendor/bootstrap/css/bootstrap-reboot' . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">-->
  <!--<link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA . 'tienda/vendor/bootstrap/css/bootstrap-grid' . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">-->

    <link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA . 'tienda/vendor/bootstrap/css/normalize-bootstrap-reboot-grid' . $extension ?>"  onload="this.onload = null;
        this.rel = 'preload stylesheet'">

    <script> const sessionlogin = "<?= isset($session['login']) ? $session['login'] : 0 ?>"</script> 

    <link rel="preload " as="style" type="text/css" href="<?= DIR_MEDIA . 'tienda/css/bootstrap-tienda' . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">
    <link rel="preload " as="style" type="text/css" href="<?= DIR_MEDIA . 'tienda/fonts/font-awesome-4.7.0/css/font-awesome' . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">
    <link rel="preload " as="style" type="text/css" href="<?= DIR_MEDIA . 'tienda/vendor/css-hamburgers/hamburgers' . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">
    <link rel="preload " as="style" type="text/css" href="<?= DIR_MEDIA . 'tienda/vendor/slick/slick' . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">
    <link rel="preload " as="style" type="text/css" href="<?= DIR_MEDIA . 'tienda/vendor/animate/animate' . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">
    <link rel="preload " as="style" type="text/css" href="<?= DIR_MEDIA . 'tienda/vendor/perfect-scrollbar/perfect-scrollbar' . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">
    <?php
    if (isset($data['page_css']) && $data['page_css'] != "") {
      foreach ((array) $data['page_css'] as $stilo) {
        ?>
        <link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA . $stilo . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">
        <?php
      }
    }
    ?>
    <link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA . 'tienda/css/util-main' . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">
    <!--<link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA . 'tienda/css/util' . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">-->
    <!--<link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA . 'tienda/css/main' . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">-->
    <link rel="preload stylesheet" as="style" type="text/css" href="<?= DIR_MEDIA . 'css/style-tienda' . $extension ?>"  onload="this.onload = null; this.rel = 'preload stylesheet'">

    <link rel="preload stylesheet" type="text/css" href="<?= DIR_MEDIA ?>tienda/vendor/animsition/css/animsition<?= $extension ?>"  >


    <?php
    if ($empresa['pixel_facebook'] == 1 && $empresa['pixel_fb_id'] != '' && $excluir_ip != 1) {
    // Verificar si la empresa tiene configurado el Pixel de Facebook y si no se excluye la dirección IP 
    // para agregar el código del Pixel
      ?> 
      <!-- Meta Pixel Code -->
      <script>
        window.addEventListener('load', function () {
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
        });
      </script>
      <noscript>
    <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?= intval($empresa['pixel_fb_id']) ?>&ev=PageView&noscript=1" />
    </noscript>

  <?php } /* End Meta Pixel Code */ ?>

</head>
<body class="animsition">


  <!-- Header -->
  <header <?= $page_name == 'Home' ? '' : 'class="header-v4"' ?>>
    <?php if ($header['dispositivo'] !== 'mobile') { ?>
      <!-- Header y Menu Desktop -->
      <div class="container-menu-desktop">
        <!-- Topbar -->
        <div class="top-bar">
          <div class="content-topbar flex-sb-m h-full container">
            <div class="right-top-bar flex-w h-full">
              <?php if (!empty($session['userData'])): ?>
                <!-- Si hay información de usuario, mostrar el nombre -->
                <a href="#" class="flex-c-m trans-04 p-lr-25"><?= $session['userData']['nombres'] ?></a>
              <?php endif ?>
            </div>
            <div class="right-top-bar flex-w h-full">
              <?php if (!empty($session['userData'])): ?>
                <!-- Si hay información de usuario, mostrar opciones de cuenta -->
                <?= ($rolid === 1) ? '<a href="' . base_url() . 'dashboard" class="flex-c-m trans-04 p-lr-25">Administrar</a>' : ''; ?>
                <a href="<?= base_url() ?>profile" class="flex-c-m trans-04 p-lr-25">MI CUENTA</a>
                <a href="<?= base_url() ?>logout" class="flex-c-m trans-04 p-lr-25">SALIR</a>
              <?php elseif (!in_array($page_name, ['Procesar Pago', 'Registro'])): ?>
                <!-- Si no hay información de usuario, mostrar opción de inicio de sesión -->
                <a href="#" type="button" class="flex-c-m trans-04 p-lr-25 " onClick="mostrarModalLogin()">LOGIN</a><!--js-show-modal1-->
              <?php endif ?>
            </div>
          </div>
        </div>
        <div class="wrap-menu-desktop">
          <nav class="limiter-menu-desktop container">
            <!-- Logo desktop -->		
            <a href="<?= base_url() ?>Home" class="logo">
              <img src="<?= $empresa['url_logoMenu'] ?>" alt="tienda-virtual"> 
            </a>
            <!-- Menu desktop -->
            <div class="menu-desktop">
              <ul class="main-menu">
                <li class="<?= $page_name == 'inicio' ? 'active-menu' : '' ?>"><a href="<?= base_url() ?>">Inicio</a></li>
                <li class="<?= $page_name == 'tienda' ? 'active-menu' : '' ?>"><a href="<?= base_url() ?>tienda">Tienda</a>
                  <ul class="sub-menu">
                    <?php foreach ((array) $header['menu_categorias'] as $menuTCat) { ?>
                      <li class="<?= ($url == $menuTCat['ruta']) ? 'active-menu' : '' ?>"> 
                        <a href="<?= base_url() ?>tienda/categoria/<?= $menuTCat['ruta'] ?>">
                          <?= $menuTCat['nombre'] //. ' (' . $menuTCat['cantidad'] . ')'         ?> 
                        </a>
                      </li>
                    <?php } ?>
                  </ul>
                </li>
                <!--<li class="<?= $page_name == 'carrito' ? 'active-menu' : '' ?>"><a href="<?= base_url() ?>carrito">Carrito</a></li>-->
                <?php if ($es_local !== false) { ?>
                  <li class="<?= $page_name == 'blog' ? 'active-menu' : '' ?>"><a href="<?= base_url() ?>blog">Blog</a></li>
                <?php } ?>
                <li class="<?= $page_name == 'contacto' ? 'active-menu' : '' ?>"><a href="<?= base_url() ?>contacto">Contacto</a></li>
                <?php if ($es_local !== false) { ?>
                                                                          <!--<li  ><a href="<?= base_url() ?>blog">Blog</a></li>-->
                  <li  ><a href="#">Sesion</a> <ul class="sub-menu sub-menu-dataserv"><?= dep($session) ?></ul> </li>
                  <li  ><a href="#">Server</a> <ul class="sub-menu sub-menu-dataserv"><?= dep($_SERVER) ?></ul> </li>
                  <li  ><a href="#">Data</a> <ul class="sub-menu sub-menu-dataserv"><?= dep($data_min) ?></ul> </li>
                <?php } ?>
              </ul>
            </div>	
            <!-- Icon header -->
            <div class="wrap-icon-header flex-w flex-r-m"></div>
            <?php if ($page_name != "carrito") { ?>
              <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-cart cantCarrito icon-header-noti" data-notify="<?= $cantCarrito ?>">
                <i class="fa fa-shopping-cart"></i>
              </div>
            <?php } ?>
          </nav>
        </div>
      </div>	
    <?php } ?>

    <!-- Header Mobile -->
    <div class="wrap-header-mobile">
      <!-- Logo móvil -->		
      <div class="logo-mobile">
        <a href="<?= base_url() ?>home">
          <img src="<?= $empresa['url_logoMenu']; ?>" alt="tienda-virtual">
        </a>
      </div>

      <!-- Icono de carrito de compras para móvil -->
      <?php if ($page_name != "Carrito") { ?>
        <div class="wrap-icon-header flex-w flex-r-m m-r-15">
          <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 js-show-cart cantCarrito icon-header-noti" data-notify="<?= $cantCarrito ?>">
            <i class="fa fa-shopping-cart"></i>
          </div>
        </div>
      <?php } ?>

      <!-- Botón para mostrar menú en dispositivos móviles -->
      <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
        <span class="hamburger-box"><span class="hamburger-inner"></span></span>
      </div>
    </div>
    <!-- Menu Mobile -->
    <div class="menu-mobile">
      <ul class="topbar-mobile">
        <li>
          <div class="right-top-bar flex-w h-full">
            <?php if (isset($session['userData'])) { ?>
              <a href="#" class="flex-c-m trans-04 p-lr-25"><?= $session['userData']['nombres'] ?></a>
            <?php } ?>
            <?php if (isset($session['userData'])) { ?>
              <?= $rolid === 1 ? '<a href="' . base_url() . 'dashboard" class="flex-c-m trans-04 p-lr-25">Administrar</a>' : ''; ?>
              <a href="<?= base_url() ?>login" class="flex-c-m trans-04 p-lr-25">Mi Cuenta</a>
              <a href="<?= base_url() ?>logout" class="flex-c-m trans-04 p-lr-10">Salir</a>
            <?php } else { ?>
              <!-- Button trigger modal -->
              <a href="#" type="button" class="flex-c-m trans-04 p-lr-25 " js-show-modal1 onClick="mostrarModalLogin()">LOGIN</a> <!-- data-toggle="modal" data-target="#modalLogin"-->
            <?php } ?>
          </div>
        </li>
      </ul>

      <ul class="main-menu-m">
        <!-- Searchs Mobile -->
        <div class="bor17 of-hidden pos-relative">
          <input class="stext-103 cl2 plh4 size-116 p-l-28 p-r-55" type="search" name="searchM" id="searchM" onkeypress="btnBuscarEnter(event)" placeholder="Buscar" value="<?= isset($data['busqueda']) ? $data['busqueda'] : '' ?>">
          <button class="flex-c-m size-122 ab-t-r fs-18 cl4 hov-cl1 trans-04" onclick="btnBuscar()">
            <i class="fa fa-search"></i>
          </button>
        </div>
        <li class="<?= $page_name == 'home' ? 'm-active-menu' : '' ?>"><a href="<?= base_url() ?>">Inicio</a></li>
        <li class="<?= $page_name == 'tienda' ? 'm-active-menu' : '' ?>"><a href="<?= base_url() ?>tienda">Tienda</a>
          <ul class="sub-menu-m">
            <?php
// Recorrer el arreglo de categorías y mostrarlas en el menú
            foreach ((array) $header['menu_categorias'] as $menuTCat) {
              ?>
              <li class="<?= $metodo == $menuTCat['ruta'] ? 'm-active-menu' : '' ?>"><a href="<?= base_url() ?>tienda/categoria/<?= $menuTCat['ruta'] ?>"><?= $menuTCat['nombre'] ?></a></li>
            <?php } ?>
          </ul>
          <span class="arrow-main-menu-m">
            <i class="fa fa-angle-right" aria-hidden="true"></i>
          </span>
        </li>
        <?php if ($es_local !== false) { ?>
          <li  class="<?= $page_name == 'blog' ? 'm-active-menu' : '' ?>">     <a href="<?= base_url() ?>blog">Blog</a> </li>
        <?php } ?>
        <li class="<?= $page_name == 'contacto' ? 'm-active-menu' : '' ?>">     <a href="<?= base_url() ?>contacto">Contacto</a> </li>
      </ul>
    </div>
    <!-- Cart -->
    <?= getModal('modalCarrito', $data) ?>

    <div class="espacio_superior"></div>
  </header>
  <section id="cuerpo">