<?php
headerTienda($data);
$producto = $data['producto'];
$ruta_producto = base_url() . 'tienda/producto' . '/' . $producto['ruta'];
$porcentaje = $producto['oferta_activa'] ? calcularPorciento($producto['precio'], $producto['oferta']) . ' OFF' : '';
?>
<!-- breadcrumb -->
<div class='container p-all-0'>
  <div class='bread-crumb bg0 flex-w p-l-25 p-r-15 p-tb-15 p-lr-7-lg'>
    <a href='<?= base_url(); ?>' class='stext-109 cl8 hov-cl1 trans-04'>Inicio<i class='fa fa-angle-right m-l-9 m-r-10' aria-hidden='true'></i></a>
    <br>
    <?php if (isset($data['cat_padre']['nombre'])) { ?>
      <a href='<?= base_url() . 'tienda/categoria/' . $data['cat_padre']['ruta'] ?>' class='stext-109 cl8 hov-cl1 trans-04'><?= $data['cat_padre']['nombre'] ?><i class='fa fa-angle-right m-l-9 m-r-10' aria-hidden='true'></i></a>
      <br>
    <?php } ?>
    <a href='<?= base_url() . 'tienda/categoria/' . $producto['ruta_categoria']; ?>' class='stext-109 cl8 hov-cl1 trans-04'><?= $producto['categoria'] ?><i class='fa fa-angle-right m-l-9 m-r-10' aria-hidden='true'></i></a>
    <span class='stext-109 cl4'><?= $producto['nombre'] ?></span>
  </div>
</div>

<!-- Product Detail -->
<section class='sec-product-detail bg-none p-t-40 p-b-40'>
  <div class='container bg0 p-t-40 p-b-40'>
    <div class='row'>
      <div class='col-md-6 col-lg-7 p-b-30'>
        <div class='p-lr-12 p-r-15 p-lr-0-lg '>
          <div class='wrap-slick3 flex-sa flex-w '>
            <div class='wrap-slick3-dots p-r-0 flex-w js-pscroll'></div>
            <div class='wrap-slick3-arrows flex-sb-m flex-w'></div>
            <div class='slick3 gallery-lb'>
              <?php
              if (!empty($producto['images'])) {
                foreach ($producto['images'] as $imagen) {
                  ?>
                  <div class='item-slick3 ' data-thumb='<?= $imagen['url_img_thumb_4']; ?>'><!--thumblr-->
                    <div class='wrap-pic-w pos-relative  prod-pic-rel' style='background: url(<?= $imagen['url_img_thumb_4']; ?>)' loading='lazy'>
                      <img  loading='lazy' src='<?= $imagen['url_img_thumb_2']; ?>' class='prod-scale-img' alt='<?= $producto['nombre']; ?>' >
                      <!--Imagen en Zoom -->
                      <a class='flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04'  href='<?= $imagen['url_img_thumb_1']; ?>'><i class='fa fa-expand'></i></a>
                    </div>
                  </div>
                  <?php
                }
              }
              ?>
            </div>
          </div>
        </div>
      </div>
      <div class='col-md-6 col-lg-5 p-b-30'>
        <div class='p-r-50 p-t-5 p-lr-0-lg'>
          <h1 class='mtext-105 cl2 js-name-detail p-b-14'><?= $producto['nombre']; ?> </h1>
          <span class='mtext-106 d-block' ><?= $producto['oferta_activa'] ? 'Oferta ' . $porcentaje : ''; ?>  </span>
          <span class='mtext-106 <?= $producto['oferta_activa'] ? 'del_precio_oferta' : 'cl2'; ?>  ' ><?= formatMoney($producto['precio']); ?></span>
          <span class='mtext-106 cl2 p-l-5 ' > <?= $producto['oferta_activa'] ? formatMoney($producto['oferta']) : ''; ?></span>
          <div class='stext-102 cl3 p-t-23 container-tinymce mce-content-body'><?= $producto['descripcion'] ?></div> 
          <br>
          <span class='stext-109 cl4 d-block' ><?=
            $producto['oferta_f_fin'] != null && $producto['oferta_activa'] == 1 ?
                'Oferta validad hasta el ' . date('d/m/Y', strtotime($producto['oferta_f_fin'])) :
                ($producto['oferta_f_fin'] == null && $producto['oferta_activa'] == 1 ?
                    'Promoción válida hasta agotar existencias' : '');
            ?>  </span>

          <!--Botonera Agregar al carrito-->

          <div class='p-t-33'> 
            <?php if ($producto['size']) { ?>
              <div class="flex-w flex-r-m p-b-10">
                <div class="size-203 flex-c-m respon6">
                  Talle
                </div>
                <div class="size-204 respon6-next">
                  <div class="rs1-select2 bor8 bg0">
                    <select class="js-select2" id='talle-product' name="time">
                      <?php
                      foreach ($producto['size'] as $key => $size) {
                        echo $key === 0 ?
                            "<option value ='' >Selecciona una Opcion</option>" : '';
                        echo '<option value =' . trim($size) . ' > Talle ' . trim($size) . '</option>';
                      }
                      ?>
                    </select>
                    <div class="dropDownSelect2"></div>
                  </div>
                </div>
              </div>
            <?php } ?>
            <?php if ($producto['color']) { ?>
              <div class="flex-w flex-r-m p-b-10">
                <div class="size-203 flex-c-m respon6">
                  Color
                </div>
                <div class="size-204 respon6-next">
                  <div class="rs1-select2 bor8 bg0">
                    <select class="js-select2" id='color-product' name="time">

                      <?php
                      foreach ($producto['color'] as $key => $color) {
                        echo $key === 0 ?
                            "<option value ='' >Selecciona una Opcion</option>" : '';
                        echo '<option value = ' . trim($color) . ' >' . trim($color) . '</option>';
                      }
                      ?>
                    </select>
                    <div class="dropDownSelect2"></div>
                  </div>
                </div>
              </div>
            <?php } ?>


            <div class='flex-w flex-r-m p-b-10'>
              <?php if ($producto['stock_status'] != 'outofstock') { ?>
                <?php if ($producto['stock_status'] == 'onbackorder') { ?>
                  <p class='available-on-backorder'>Disponible para reserva</p>
                <?php } ?>
                <div class='size-204 flex-w flex-m respon6-next'>
                  <div class='wrap-num-product flex-w m-r-20 m-tb-10'>
                    <div class='btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m'><i class='fs-16 fa fa-minus'></i></div>
                    <input id='cant-product' class='mtext-104 cl3 txt-center num-product' type='number' name='num-product' value='1' min='1'>
                    <div class='btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m'><i class='fs-16 fa fa-plus'></i></div>
                  </div>
                  <button id='<?= $producto['idproducto'] ?>' class='flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail'>
                    Agregar al carrito
                  </button>
                </div>
              <?php } else { ?>
                <p class='out-of-stock'><i class='fa fa-frown-o' aria-hidden='true'></i> Oops! Temporalmente Agotado</p>
              <?php } ?>

            </div>	
          </div>
          <!--Botonera Compartir en -->
          <div class='flex-w flex-c-m p-l-10 p-t-15 respon7'>
            <div class='flex-c-m bor9 p-r-10 m-r-11'>
              <a href='#' class=' <?= $producto['favorito'] ? 'js-addedwish-detail' : '' ?> fs-25 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100' id='<?= 'fav-' . $producto['idproducto'] ?>' data-tooltip='Add to Wishlist'>  <i class='fa fa-heart'></i> </a>
            </div>
            <a href='#' onclick='window.open('http://www.facebook.com/sharer/sharer.php?display=popup&u=<?= $ruta_producto ?>', 'facebook-share-dialog', 'toolbar=0, status=0, width = 400, height = 550'); return false;' class='fs-25 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100' data-tooltip='Facebook'><i class='fa fa-facebook'></i></a>
            <!--a href='https://twitter.com/share?url=<?= $ruta_producto ?>&text=<?= $producto['nombre']; ?>&hashtags=<?= str_replace(' ', '', $producto['nombre']); ?>' target='_blank' data-lang='es'  data-via='samluisito'-->
            <a href='https://twitter.com/intent/tweet?url=<?= $ruta_producto ?>&text=%0A<?= $producto['nombre']; ?>&hashtags=<?= str_replace(' ', '', $producto['nombre']); ?>' target='_blank' data-lang='es' data-url='<?= base_url() . 'tienda/producto' . '/' . $producto['ruta'] ?>' class='fs-25 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100' data-tooltip='Twitter'><i class='fa fa-twitter'></i> </a>
            <a href='https://wa.me/<?= '?text=' . $ruta_producto ?>' target='_blank' class='fs-25 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100' data-tooltip='WhatsApp'><i class='fa fa-whatsapp' aria-hidden='true'></i></a> 
            <a href='https://t.me/share/url?url=<?= $ruta_producto ?>&text=<?= $producto['nombre']; ?>&to=' target='_blank' class='fs-25 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100' data-tooltip='Telegram'><i class='fa fa-telegram' aria-hidden='true'></i></a>
          </div>
        </div>
      </div>
    </div>
    <?php
    if (isset($producto['detalle']) && $producto['detalle'] != '' ||
        isset($producto['inf_adic']) && $producto['inf_adic'] != '' ||
        isset($producto['review']) && $producto['review'] != '') {
      ?>
      <div class='bor10 m-t-50 p-t-43 p-b-40'>
        <!-- Tab01 -->
        <div class='tab01'>
          <!-- Nav tabs -->
          <ul class='nav nav-tabs' role='tablist'>
            <?php if (isset($producto['detalle']) && $producto['detalle'] != '') { ?>
              <li class='nav-item p-b-10'>
                <a class='nav-link active' data-toggle='tab' href='#description' role='tab'>Descripcion</a>
              </li>
            <?php } if (isset($producto['inf_adic']) && $producto['inf_adic'] != '') { ?>
              <li class='nav-item p-b-10'>
                <a class='nav-link' data-toggle='tab' href='#information' role='tab'>Additional information</a>
              </li>
            <?php } if (isset($producto['review']) && $producto['review'] != '') { ?>
              <li class='nav-item p-b-10'>
                <a class='nav-link' data-toggle='tab' href='#reviews' role='tab'>Reviews (1)</a>
              </li>
            <?php } ?>
          </ul>

          <!-- Tab panes -->
          <div class='tab-content p-t-43'>
            <?php if (isset($producto['detalle']) && $producto['detalle'] != '') { ?>
              <!-- - -->
              <div class='tab-pane fade show active' id='description' role='tabpanel'>
                <div class='how-pos2 p-lr-15-md'>
                  <p class='stext-102 cl6'>
                    <?= $producto['detalle'] ?>
                  </p>
                </div>
              </div>
            <?php } if (isset($producto['inf_adic']) && $producto['inf_adic'] != '') { ?>
              <!-- - -->
              <div class='tab-pane fade' id='information' role='tabpanel'>
                <div class='row'>
                  <div class='col-sm-10 col-md-8 col-lg-6 m-lr-auto'>
                  </div>
                </div>
              </div>
            <?php } if (isset($producto['review']) && $producto['review'] != '') { ?>
              <!-- - -->
              <div class='tab-pane fade' id='reviews' role='tabpanel'>
                <div class='row'>
                  <div class='col-sm-10 col-md-8 col-lg-6 m-lr-auto'>
                    <div class='p-b-30 m-lr-15-sm'>
                      <!-- Review -->
                      <div class='flex-w flex-t p-b-68'>
                        <div class='wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6'>
                          <img src='images/avatar-01.jpg' alt='AVATAR'>
                        </div>

                        <div class='size-207'>
                          <div class='flex-w flex-sb-m p-b-17'>
                            <span class='mtext-107 cl2 p-r-20'>
                              Ariana Grande
                            </span>

                            <span class='fs-18 cl11'>
                              <i class='zmdi zmdi-star'></i>
                              <i class='zmdi zmdi-star'></i>
                              <i class='zmdi zmdi-star'></i>
                              <i class='zmdi zmdi-star'></i>
                              <i class='zmdi zmdi-star-half'></i>
                            </span>
                          </div>

                          <p class='stext-102 cl6'>
                            Quod autem in homine praestantissimum atque optimum est, id deseruit. Apud ceteros autem philosophos
                          </p>
                        </div>
                      </div>

                      <!-- Add review -->
                      <form class='w-full'>
                        <h5 class='mtext-108 cl2 p-b-7'>
                          Add a review
                        </h5>

                        <p class='stext-102 cl6'>
                          Your email address will not be published. Required fields are marked *
                        </p>

                        <div class='flex-w flex-m p-t-50 p-b-23'>
                          <span class='stext-102 cl3 m-r-16'>
                            Your Rating
                          </span>

                          <span class='wrap-rating fs-18 cl11 pointer'>
                            <i class='item-rating pointer zmdi zmdi-star-outline'></i>
                            <i class='item-rating pointer zmdi zmdi-star-outline'></i>
                            <i class='item-rating pointer zmdi zmdi-star-outline'></i>
                            <i class='item-rating pointer zmdi zmdi-star-outline'></i>
                            <i class='item-rating pointer zmdi zmdi-star-outline'></i>
                            <input class='dis-none' type='number' name='rating'>
                          </span>
                        </div>

                        <div class='row p-b-25'>
                          <div class='col-12 p-b-5'>
                            <label class='stext-102 cl3' for='review'>Your review</label>
                            <textarea class='size-110 bor8 stext-102 cl2 p-lr-20 p-tb-10' id='review' name='review'></textarea>
                          </div>

                          <div class='col-sm-6 p-b-5'>
                            <label class='stext-102 cl3' for='name'>Name</label>
                            <input class='size-111 bor8 stext-102 cl2 p-lr-20' id='name' type='text' name='name'>
                          </div>

                          <div class='col-sm-6 p-b-5'>
                            <label class='stext-102 cl3' for='email'>Email</label>
                            <input class='size-111 bor8 stext-102 cl2 p-lr-20' id='email' type='text' name='email'>
                          </div>
                        </div>

                        <button class='flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10'>
                          Submit
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</section>
<!-- Related Products -->
<div class='bg-none flex-c-m flex-w size-302 m-t-0 p-tb-15'><h3>Productos Relacionados</h3></div>
<section class='sec-relate-product bg-none p-t-0 p-b-60'>
  <div class='container bg0'>
    <!-- Slide2 -->
    <div class='wrap-slick2'>

      <div class='slick2'> 
        <!-- Block2 -->
        <?php
        $pos = 0;
        if (!empty($prod_relac = $data['prod_relac'])) {
          foreach ((array) $prod_relac as $producto) {
            $pos = $pos + 1;
            $oferta_activa = $producto['oferta_activa'];
            $porcentaje = $oferta_activa ? calcularPorciento($producto['precio'], $producto['oferta']) . ' OFF' : '';
            $ruta_producto = base_url() . 'tienda/producto/' . $producto['ruta'];
            $url_img = $producto['images']['url_img'];
            $url_img_back = $producto['images']['url_img_back'];
            $url = $producto['images']['url_img'];
            $nombre = $producto['nombre'];
            ?>

            <div class='item-slick2 p-tb-20 p-lr-20 <?= num_par($pos) ? 'p-l-10-col-6' : 'p-r-10-col-6'; ?>' >
              <div class='block2 bg0 ' > 
                <a href='<?= $ruta_producto ?>' class='block2-pic hov-img0 prod-pic-rel bg6 <?= $oferta_activa ? 'label-new' : ''; ?>' 
                   data-label='<?= $porcentaje ?>' style='background: url(<?= $url_img_back ?>)' loading='lazy'>
                  <img   src='<?= $url_img ?>'  alt='<?= $nombre ?>' class='prod-scale-img' <?= $data['dispositivo'] === 'mobile' ? 'loading="lazy"' : '' ?>> 
                  <!--<a href='#' class='block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04'>Ver producto</a>-->
                </a>
                <div class='block2-txt flex-w flex-t p-t-5 p-b-5 p-lr-10'>
                  <div class='block2-txt-child1 flex-col-l '>
                    <a href='<?= $ruta_producto ?>' class='stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-0 text-recort ' title='<?= $nombre ?>'> 
                      <h3 class='stext-104 dis-inline-block text-recort'><?= $nombre ?></h3></a>
                    <div class='row'>
                      <span class='p-l-15 <?= $oferta_activa ? 'del_precio_oferta mtext-107 cl12' : 'mtext-106 cl4'; ?>  ' ><?= formatMoney($producto['precio']); ?></span>
                      <span class='p-l-15 mtext-106 cl4 '><?= $oferta_activa ? formatMoney($producto['oferta']) : ''; ?></span>
                    </div>
                  </div>
                  <div class='block2-txt-child2 flex-r p-t-10'>
                    <a href='#' class='btn-addwish-b2 dis-block pos-relative js-addwish-b2  <?= $producto['favorito'] ? 'js-addedwish-b2' : '' ?>' 
                       id='<?= 'fav-' . $producto['idproducto'] ?>'>
                      <img class='icon-heart1 dis-block trans-04'  src='<?= DIR_MEDIA ?>images/icons/icon-heart-01.png'  alt='ICON' loading='lazy' style ='width: 100%; height: 100%'>
                      <img class='icon-heart2 dis-block trans-04 ab-t-l' src='<?= DIR_MEDIA ?>images/icons/icon-heart-02.png'  alt='ICON'loading='lazy' style ='width: 100%; height: 100%'>
                    </a>
                  </div>
                </div>
              </div>
            </div>


            <?php
          }
        }
        ?>

      </div>
    </div>
  </div>
</section>
<?= footerTienda($data); ?>
