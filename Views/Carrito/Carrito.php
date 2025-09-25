<?php
headerTienda($data);
$subtotal = 0;
$costo_envio = $data['empresa']['costo_envio'];
$total = 0;
if (isset($_SESSION['arrCarrito']) and count($_SESSION['arrCarrito']) > 0) {
  ?>
  <!-- breadcrumb -->
  <div class="container">
    <div class="bread-crumb flex-w p-l-25 p-r-15 p-tb-10 p-lr-0-lg">
      <a href="<?= base_url(); ?>" class="stext-109 cl8 hov-cl1 trans-04">
        Inicio
        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
      </a>

      <span class="stext-109 cl4">
        <?= $data['header']['page_title']; ?>
      </span>
    </div>
  </div>
  <!-- Title page -->
  <section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('<?= DIR_MEDIA ?>images/bg-03.webp');">
    <h2 class="ltext-105 cl0 txt-center">

    </h2>
  </section>	
  <!-- Shoping Cart -->
  <form class="bg-transparent p-t-75 p-b-85" >
    <div class="container">
      <div class="row">

        <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50 p-all-10 bg0">
          <div class="m-l-25 m-r--38 m-lr-0-xl">
            <div class="wrap-table-shopping-cart">
              <table id="tblCarrito" class="table-shopping-cart bor10">
                <tbody>
                  <tr class="table_head">
                    <th class="column-1">Product</th>
                    <th class="column-2"></th>
                    <th class="column-3">Precio</th>
                    <th class="column-4">Cantidad</th>
                    <th class="column-5">Total</th>
                  </tr>
                  <?php
                  foreach ($_SESSION['arrCarrito'] as $producto) {
                    $totalProducto = $producto['cantidad'] * ($producto['oferta'] != 0 ? $producto['oferta'] : $producto['precio']);
                    $subtotal += $totalProducto;
                    $idProducto = $producto['idproducto'];
                    $html_oferta = $producto['oferta'] != 0 ?
                        "<span class='header-cart-item-info p-l-15 p-r-10'> " . formatMoney($producto['oferta']) . "</span>" : '';
                    $html_precio = $producto['oferta'] !== 0 ?
                        "<span class='header-cart-item-info del_precio_oferta stext-107 cl12 p-l-35 '>" . formatMoney($producto['precio']) . "</span>" :
                        "<span class='header-cart-item-info p-l-15 p-r-10'>" . formatMoney($producto['precio']) . "</span>";
                    ?>
                    <tr class="table_row <?= $idProducto ?>">
                      <td class="column-1">
                        <div loading='lazy' class="how-itemcart1 prod-pic-rel" style='background: url(<?= $producto['img'] ?>)' idpr="<?= $idProducto ?>" op="2" onclick="fntdelItem(this)">
                          <img loading='lazy' class='prod-scale-img' src="<?= $producto['img'] ?>" alt="<?= $producto['nombre'] ?>">
                        </div>
                      </td>
                      <td class="column-2"><?= $producto['nombre'] ?></td>
                      <td class="column-3"><?= $html_oferta . $html_precio ?> </td>
                      <td class="column-4">
                        <div class="wrap-num-product flex-w m-l-auto m-r-0">
                          <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m" idpr ="<?= $idProducto ?>">
                            <i class="fs-16 fa fa-minus"></i>
                          </div>

                          <input class=" mtext-104 cl3 txt-center num-product" idpr ="<?= $idProducto ?> type="number" name="num-product1" value="<?= $producto['cantidad'] ?>">

                          <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m" idpr ="<?= $idProducto ?>">
                            <i class="fs-16 fa fa-plus"></i>
                          </div>
                        </div>
                      </td>
                      <td class="column-5"><?= formatMoney($totalProducto) ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>

            <!-- div class="flex-w flex-sb-m bor15 p-t-18 p-b-15 p-lr-40 p-lr-15-sm">
                <div class="flex-w flex-m m-r-20 m-tb-5">
                    <input class="stext-104 cl2 plh4 size-117 bor13 p-lr-20 m-r-10 m-tb-5" type="text" name="coupon" placeholder="Coupon Code">

                    <div class="flex-c-m stext-101 cl2 size-118 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-5">
                        Apply coupon
                    </div>
                </div>
                      </div -->
          </div>
        </div>




        <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50 p-all-10 bg0">
          <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
            <h4 class="mtext-109 cl2 p-b-30">
              Total Carrito
            </h4>

            <div class="flex-w flex-t bor12 p-b-13">
              <div class="size-208">
                <span  class="stext-110 cl2">
                  Subtotal:
                </span>
              </div>

              <div class="size-209">
                <span id="subTotalCompra" class="mtext-110 cl2">
                  <?= formatMoney($subtotal) ?>
                </span>
              </div>
            </div>

            <div class="flex-w flex-t bor12 p-t-15 p-b-20">
              <div class="size-208 w-full-ssm">
                <span class="stext-110 cl2">
                  Envio:
                </span>
              </div>
              <div class="size-209">
                <span class="mtext-110 cl2">
                  <?= formatMoney($costo_envio) ?>
                </span>
              </div>
            </div>
            <div class="flex-w flex-t p-t-27 p-b-33">
              <div class="size-208">
                <span class="mtext-101 cl2">
                  Total:
                </span>
              </div>
              <div class="size-209 p-t-1">
                <span id="totalCompra" class="mtext-110 cl2">
                  <?= formatMoney($subtotal + $costo_envio) ?>
                </span>
              </div>
            </div>
            <a href="<?= base_url() ?>carrito/procesarpago" id="btnComprar" 
               class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
              PROCESAR PAGO
            </a>
          </div>
        </div>
      </div>
    </div>
  </form>

<?php } else { ?>
  <div class="container-fluid">
    <br>
    <br>
    <p class="text-center">No hay Productos en el carrito <a href="<?= base_url() ?>tienda">Ver Articulos</a></p>
    <br>
    <br>
  </div>
  <br>
<?php } ?>
<?= footerTienda($data); ?>
