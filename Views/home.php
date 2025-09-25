
<?php headerTienda($data); ?>
<?php if ($data['carrusel'] !== '') {//$ruta_categoria = $slide['ruta'];           ?>  
  <!-- Slider -->
  <section class="section-slide">
    <div class="wrap-slick1">
      <div class="slick1">
        <?php foreach ($data['carrusel'] as $index => $slide) {//$ruta_categoria = $slide['ruta'];        ?>  
          <div class="item-slick1" style="background-image: url(<?= $slide['url_img'] ?>)" loading="lazy" >
            <div class="container h-full">
              <div class="flex-col-l-m col-sm-8 h-full p-t-100 p-b-30">

                <div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
                  <h2 class="ltext-104 cl2 p-t-10 p-b-10 respon1"><?= $slide['nombre'] ?></h2>
                </div>
                <div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
    <!--                <text class="ltext-202 cl2 respon2">
                  </text>-->
                  <pre class="ltext-202 cl2 respon2"><?= $slide['descripcion'] ?></pre>
                </div>
                <div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
                  <a href=" <?= base_url() . $slide['ruta'] ?>" class="flex-c-m stext-101 cl5 size-101 bg0 bor1 hov-btn1 p-lr-15 trans-04 w-25"> ver</a>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>
<?php } ?>
<!-- Seccion de Productos -->
<section class="bg-none p-t-23 p-b-20">
  <div class="container">

    <!-- busqueda y filtros -->
    <div class="flex-w flex-sb-m p-lr-20 m-b-20 bg0">

      <!-- Buscador display none-->
      <div class="dis-none panel-search w-full p-t-10 p-b-15" style="display: block;"> 
        <div class="bor8 dis-flex p-l-1">
          <button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04" aria-label="buscar"><i class="fa fa-search"></i></button>
          <input class="mtext-107 cl2 size-114 plh2 p-r-15" type="search" name="searchW" id="searchW" onkeypress="btnBuscarEnter(event)" placeholder="Buscar" value="<?= isset($data['busqueda']) ? $data['busqueda'] : '' ?>">
        </div>	
      </div>
    </div>
    <div class="p-b-32">
      <h3 class="ltext-105 cl5 txt-center respon1">
        Ultimos Articulos
      </h3>
    </div>
  </div>
  <!-- seccion display productos -->
  <?php getDisplay(__DIR__, 'Tienda/displayProducto', $data); ?>

  <!-- Load more -->
  <div class="flex-c-m flex-w w-full p-t-45">
    <a href="<?= base_url() ?>tienda/" class="flex-c-m stext-101 cl5 size-103 bor1 hov-btn1 p-lr-15 trans-04 bg0 ">Ver mas</a>
  </div>

</section>
<?= footerTienda($data) ?>
