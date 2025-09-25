<section>
  <div class='container p-tb-20 bg6'>
    <div class='row flex-c isotope-grid'>
      <!-- Block2 -->
      <?php
      $pos = 0;
      $productos = $data['productos'];

      if (is_array($productos)) {
        foreach ($productos as $producto) {
          $pos++;
          extract($producto);
          $oferta_activa = $producto['oferta_activa'];
          $porcentaje = $oferta_activa ? calcularPorciento($precio, $oferta) . ' OFF' : '';
          $ruta_producto = BASE_URL . '/tienda/producto/' . $ruta;
          $url_img = $images['url_img'];
          $url_img_back = $images['url_img_back'];
          $url = $images['url_img'];
          ?>
          <div class='col-6 col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item <?= num_par($pos) ? 'p-l-10-col-sm-6' : 'p-r-10-col-sm-6'; ?>'>
            <div class='block2 bg0'>
              <a href='<?= $ruta_producto ?>' class='block2-pic hov-img0 prod-pic-rel bg6 <?= $oferta_activa ? 'label-new' : ''; ?>' 
                 data-label='<?= $porcentaje ?>' 
                 style='background: url(<?= $url_img_back ?>)' loading='lazy'>
                <img src='<?= $url_img ?>' alt='<?= $nombre ?>' loading='lazy' class='prod-scale-img' style=''>
              </a>
              <div class='block2-txt flex-w flex-t p-t-5 p-b-5 p-lr-10'>
                <div class='block2-txt-child1 flex-col-l '>
                  <a href='<?= $ruta_producto ?>' class='stext-104 cl4 hov-cl1 trans-04  p-b-0 text-recort ' title='<?= $nombre ?>'> 
                    <h3 class='stext-104 dis-inline-block text-recort js-name-b2'><?= $nombre ?></h3>
                  </a>
                  <div class='row'>
                    <span class='p-l-15 <?= $oferta_activa ? 'del_precio_oferta mtext-107 cl12' : 'mtext-102 cl4'; ?>  ' ><?= formatMoney($precio); ?></span>
                    <span class='p-l-8 mtext-102 cl4 '><?= $oferta_activa ? formatMoney($oferta) : ''; ?></span>
                  </div>
                </div>
                <div class='block2-txt-child2 flex-r p-t-10'>
                  <a href='#' class='btn-addwish-b2 dis-block pos-relative js-addwish-b2  <?= $favorito ? 'js-addedwish-b2' : '' ?>' 
                     id='<?= 'fav-' . $idproducto ?>'>
                    <img class='icon-heart1 dis-block trans-04'  src='<?= DIR_MEDIA ?>images/icons/icon-heart-01.png' width="19" height="16" alt='ICON' loading='lazy' >
                    <img class='icon-heart2 dis-block trans-04 ab-t-l' src='<?= DIR_MEDIA ?>images/icons/icon-heart-02.png' width="19" height="16" alt='ICON'loading='lazy' >
                  </a>
                </div>
              </div>
            </div>
          </div>
          <?php
        } //end foreach
      }//end if
      else {
        echo $data['productos'];
      }
      ?>
    </div>
  </div>
</section>