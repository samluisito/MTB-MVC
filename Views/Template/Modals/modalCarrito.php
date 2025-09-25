<?php $total = 0; ?>
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
    <!-- Contenido del carrito -->

    <div class="header-cart-content flex-w js-pscroll ps" style="position: relative; overflow: hidden;">
      <ul class="header-cart-wrapitem w-full" id="productosCarrito">
        <!-- Item carrito -->
        <?php
        if (isset($_SESSION['arrCarrito']) and count($_SESSION['arrCarrito']) > 0) {
          foreach ($_SESSION['arrCarrito'] as $producto) {
            $precio_producto = $producto['oferta'] != 0 ? $producto['oferta'] : $producto['precio'];

            $total += $producto['cantidad'] * $precio_producto; //a la variable total le sumamos el resultado de cantidad * precio
            echo html_producto_carrito($producto); // imprime el html item cart (esta en helper y es comun para todas las acciones)
          }
        }
        ?>
      </ul>

      <div class="w-full">
        <div class="header-cart-total w-full p-tb-40">
          Total: <?= formatMoney($total) ?>
        </div>

        <div class="header-cart-buttons flex-w w-full">
          <a href="<?= base_url() ?>carrito" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
            Ver Carrito
          </a>
          <a href="<?= base_url() ?>carrito/procesarpago" class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-b-10">
            Pagar
          </a>
        </div>
      </div>
      <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
  </div>
</div>
