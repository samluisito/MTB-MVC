<?php
headerTienda($data);
?>


<section class="bg-transparent p-tb-50 p-b-25">
  <div class="container" bis_skin_checked="1">
    <div class="flex-w flex-tr" bis_skin_checked="1">
      <div class="size-210 bg0 bor10 m-lr-auto p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full-md" bis_skin_checked="1">

        <div class="jumbotron text-center">
          <h1 class="display-4">Tu compra fue aprobada</h1>
          <br>  
          <p class="lead"> Su numero de orden es <strong><?= $data['orden'] ?></strong></p>
          <?php if (!empty($data['transaccion'])) { ?>
            <p class="lead"> Transaccion: <strong><?= $data['transaccion'] ?></strong></p>
          <?php } ?>
          <hr class="my-4">
          <p>Pronto nos comunicaremos para coordinar la entrega.</p>
          <p>Puedes ver el estado de tu pedido en la seccion <a href="<?= base_url() ?>pedidos">Pedidos</a> de tu cuenta</p>
          <hr class="my-4">
          <a href="<?= base_url() ?>" class="flex-c-m stext-101 cl5 size-103 bor1 hov-btn1 p-lr-15 trans-04 bg2 " bis_skin_checked="1">Continuar</a>
        </div>
      </div>

    </div>
  </div>
</section>





<?= footerTienda($data); ?>


