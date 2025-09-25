<?= headerTienda($data) ?>

<!-- Title page -->
<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('<?= DIR_MEDIA ?>images/bg-error-2.webp');">
  <h2 class="ltext-105 cl0 txt-center">
    Lo sentimos
  </h2>
</section>	
<!-- Content page -->
<section class="bg-transparent p-t-20 p-b-25">
  <div class="container bg0 p-b-20" >
    <div class="flex-w flex-tr">
      <div class="size-210 bg0  p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full-md">
        <div class="flex-w w-full p-b-42 p-t-100">
          <a href="<?= BASE_URL ?>" class="m-lr-auto btn btn-sm flex-c-m stext-101 cl0 size-101 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
            Regresar al inicio
          </a>         
        </div>
        <div class="flex-w w-full p-b-42">     
          <h2 class="mtext-111 cl2 m-lr-auto">
            <?= isset($data['busqueda']) ? "Lo siento no encontramos: {$data['busqueda']}" : 'La pagina no exite' ?> 
          </h2>
        </div>
        <div class="flex-w w-full p-b-42">
          <div class="dis-none panel-search w-full p-lr-30" style="display: block;"> 
            <div class="bor8 dis-flex p-l-1">
              <button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04"><i class="fa fa-search"></i></button>
              <input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="searchW" id="searchW" onkeypress="btnBuscarEnter(event)" placeholder="Buscar" value="<?= isset($data['busqueda']) ? $data['busqueda'] : '' ?>">
            </div>	
          </div>
        </div>
      </div>
      <div class="size-210 bg0  flex-w flex-col-m p-lr-93 p-tb-30 p-lr-15-lg w-full-md">
        <div class="row">
          <div class="how-bor1 ">
            <div class="hov-img0">
              <img src="<?= DIR_MEDIA ?>images/about-01.jpg" alt="IMG">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>	
<!-- Map -->


<?= footerTienda($data) ?>