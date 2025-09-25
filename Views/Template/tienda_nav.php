<?php
$cantCarrito = 0;

if (isset($_SESSION['arrCarrito'])and count($_SESSION['arrCarrito']) > 0) {
    foreach ($_SESSION['arrCarrito'] as $producto) {
        $cantCarrito += $producto['cantidad']; // sumamos las cantidades por item 
    }
}
?>


<!-- Header desktop -->
<div class="container-menu-desktop">
    <!-- Topbar -->
    <div class="top-bar">
        <div class="content-topbar flex-sb-m h-full container">
            <div class="left-top-bar">
                <?php
                if (!empty($_SESSION['userData'])) {
                    echo 'Hola  ' . $_SESSION['userData']['nombres'];
                }
                ?>
            </div>

            <div class="right-top-bar flex-w h-full">
                <a href="<?= base_url() ?>tienda/#" class="flex-c-m trans-04 p-lr-25">
                    Ayuda y Preguntas Frecuentes
                </a>

                <?php if (!empty($_SESSION['userData'])) { ?>
                    <a href="<?= base_url() ?>login" class="flex-c-m trans-04 p-lr-25">  MI CUENTA  </a>
                    <a href="<?= base_url() ?>logout" class="flex-c-m trans-04 p-lr-25">   SALIR    </a>
                <?php } else { ?>
                    <a href="<?= base_url() ?>login" class="flex-c-m trans-04 p-lr-25">  LOGIN  </a>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="wrap-menu-desktop">
        <nav class="limiter-menu-desktop container">

            <!-- Logo desktop -->		
            <a href="<?= base_url() ?>Home" class="logo">
                <img src="<?= $data['logo_desktop']; ?>" alt="tienda-virtual">
            </a>

            <!-- Menu desktop -->
            <div class="menu-desktop">
                <ul class="main-menu">
                    <li class="active-menu">
                        <a href="<?= base_url() ?>">Inicio</a>

                        <!--ul class="sub-menu">
                            <li><a href="<?= media() ?>tienda/index.html">Homepage 1</a></li>
                            <li><a href="<?= media() ?>tienda/home-02.html">Homepage 2</a></li>
                            <li><a href="<?= media() ?>tienda/home-03.html">Homepage 3</a></li>
                        </ul-->

                    </li>

                    <li> <a  href="<?= base_url() ?>tienda">Tienda</a> </li>
                    <!--li> <a class="label1" data-label1="hot" href="<?= base_url() ?>tienda">Tienda</a> </li-->

                    <li> <a href="<?= base_url() ?>carrito">Carrito</a>           </li>
                            <!--li> <a href="<?= base_url() ?>tienda/blog.html">Blog</a> </li>
                    <li> <a href="<?= base_url() ?>tienda/nosotros">Nosotros</a> </li-->
                    <li>  <a href="<?= base_url() ?>contacto">Contacto</a> </li>
                </ul>
            </div>	

            <!-- Icon header -->
            <div class="wrap-icon-header flex-w flex-r-m">
                <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
                    <i class="zmdi zmdi-search"></i>
                </div>
                <?php if ($data['page_name'] != "Carrito" && $data['page_name'] != "Procesar Pago") { ?>
                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart cantCarrito" data-notify="<?= $cantCarrito; ?>">
                        <i class="zmdi zmdi-shopping-cart"></i>
                    </div>
                <?php } ?>
<!--a href="<?= base_url() ?>tienda/#" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti" data-notify="0">
<i class="zmdi zmdi-favorite-outline"></i>
</a-->
            </div>
        </nav>
    </div>	
</div>

<!-- Header Mobile -->
<div class="wrap-header-mobile">
    <!-- Logo moblie -->		
    <div class="logo-mobile">
        <a href="<?= base_url() ?>home">
            <img src="<?= $data['logo_desktop']; ?>" alt="tienda-virtual">
        </a>
    </div>

    <!-- Icon header -->
    <div class="wrap-icon-header flex-w flex-r-m m-r-15">
        <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
            <i class="zmdi zmdi-search"></i>
        </div>
        <?php if ($data['page_name'] != "Carrito" && $data['page_name'] != "Procesar Pago") { ?>
            <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart cantCarrito" data-notify="<?= $cantCarrito; ?>">
                <i class="zmdi zmdi-shopping-cart"></i>
            </div>
        <?php } ?>
<!--a href="<?= base_url() ?>tienda/#" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti" data-notify="0">
<i class="zmdi zmdi-favorite-outline"></i>
</a-->
    </div>

    <!-- Button show menu -->
    <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
        <span class="hamburger-box">
            <span class="hamburger-inner"></span>
        </span>
    </div>
</div>

<!-- Menu Mobile -->
<div class="menu-mobile">
    <ul class="topbar-mobile">
        <li>
            <div class="left-top-bar">
                <?php
                if (isset($_SESSION['userData'])) {
                    echo 'Hola : ' . $_SESSION['userData']['nombres'];
                }
                ?>
            </div>
        </li>

        <li>
            <div class="right-top-bar flex-w h-full">
                <a href="<?= base_url() ?>tienda/#" class="flex-c-m p-lr-10 trans-04">
                    Ayuda y Preguntas frecuentes
                </a>
                <?php if (isset($_SESSION['userData'])) { ?>
                    <a href="<?= base_url() ?>login" class="flex-c-m trans-04 p-lr-25">  Mi Cuenta  </a>
                    <a href="<?= base_url() ?>logout" class="flex-c-m p-lr-10 trans-04"> Salir </a>
                <?php } else { ?>
                    <a href="<?= base_url() ?>login" class="flex-c-m p-lr-10 trans-04"> Ingresar </a>
                <?php } ?>
            </div>
        </li>
    </ul>

    <ul class="main-menu-m">
        <li><a href="<?= base_url() ?>">Inicio</a> </li>
        <!--li> <a href="<?= base_url() ?>tienda/shoping-cart.html" class="label1 rs1" data-label1="hot">Features</a>  </li-->
        <li> <a href="<?= base_url() ?>tienda">Tienda</a> </li>
        <li> <a href="<?= base_url() ?>carrito">Carrito</a> </li>
        <!--li> <a href="<?= base_url() ?>tienda/nosotros">Nosotros</a> </li-->
        <li> <a href="<?= base_url() ?>contacto">Contacto</a> </li>
        </li>
    </ul>
</div>

<!-- Modal Search -->
<div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
    <div class="container-search-header">
        <button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
            <img src="<?= media() ?>tienda/images/icons/icon-close2.png" alt="CLOSE">
        </button>

        <form class="wrap-search-header flex-w p-l-15">
            <button class="flex-c-m trans-04">
                <i class="zmdi zmdi-search"></i>
            </button>
            <input class="plh3" type="text" name="search" placeholder="Search...">
        </form>
    </div>
</div>

</header>

<!-- Cart -->
<div class="wrap-header-cart js-panel-cart">
    <div class="s-full js-hide-cart"></div>

    <div class="header-cart flex-col-l p-l-65 p-r-25">
        <div class="header-cart-title flex-w flex-sb-m p-b-8">
            <span class="mtext-103 cl2">
                Tu carrito
            </span>

            <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                <i class="zmdi zmdi-close"></i>
            </div>
        </div>

        <div id="productosCarrito" class="header-cart-content flex-w js-pscroll">
            <!-- Contenido del carrito -->
            <?= getModal('modalCarrito', $data) ?>
        </div>
    </div>
</div>
