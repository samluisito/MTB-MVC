<?php

class Confirmarpedido {
  //put your code here
}

headerTienda($data);
?>
<div class="jumbotron text-center">
  <h1 class="display-4">Tu compra fue aprobada</h1>
  <br>  
  <p class="lead"> Tu nomero de orden es <strong><?= $data['orden'] ?></strong></p>
  <?php if (!empty($data['transaccion'])) { ?>
    <p class="lead"> Transaccion: <strong><?= $data['transaccion'] ?></strong></p>
  <?php } ?>
  <hr class="my-4">
  <p>Pronto nos comunicaremos para coordinar la entrega.</p>
  <p>Puedes ver el estado de tu pedido en la seccion Pedidos de tu cuenta <?= NOMBRE_EMPRESA ?></p>

  <hr class="my-4">

  <a class="btn btn-primary btn-lg" href="<?= base_url() ?>" role="button">Continuar</a>
</div>


<?= footerTienda($data); ?>


