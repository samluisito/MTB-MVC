<?php
declare(strict_types=1);
extract($data);
// Definir la extensión del archivo JS según si está en un servidor local o en producción
$extension = TPO_SERV_LOCAL ? '.js' : '.min.js';

// Obtener el título de la página
$page_name = $header['page_title'];
?>
</section>
<!-- Footer -->
<footer id='section_footer' class='bg3 p-t-30 p-b-20'>
  <?php
  ?>
  <div class='container'>
    <div class='row'>
      <div class='col-sm-5  p-b-10'>
        <h4 class='stext-301 cl0 p-b-30'>Categorías</h4>
        <ul>
          <?php
          // Mostrar las categorías del footer
          foreach ((array) $footer['footer_cat'] as $footerCat) {
            ?>
            <li class='p-b-10'>
              <a href='<?= base_url() ?>tienda/categoria/<?= $footerCat['ruta'] ?>' class='stext-107 cl7 hov-cl1 trans-04'>
                <?= ucwords(strtolower($footerCat['nombre'])) ?>
              </a>
            </li>
            <?php
          } // Fin del foreach de categorías
          ?>
        </ul>
      </div>
      <div class='col-sm-7  p-b-10'>
        <h4 class='stext-301 cl0 p-b-30'>Contacto</h4>
        <p class='stext-107 cl7 size-201'>Dirección: <?= $empresa['direccion'] ?></p>
        <p class='stext-107 cl7 size-201'>Teléfono: <a href='tel:<?= $empresa['telefono'] ?>'><?= $empresa['telefono'] ?></a></p>
        <p class='stext-107 cl7 size-201'>Email: <a target='_blank' href='mailto:<?= $empresa['email'] ?>'><?= $empresa['email'] ?></a></p>
        <div class='p-t-27'>
          <?php
          // Mostrar los enlaces de redes sociales si están definidos en la información de la empresa
          if ($empresa['facebook'] != '') {
            ?>
            <a title='facebook.com/' href='<?= $empresa['facebook'] ?>' target='_blank' class='fs-18 cl7 hov-cl1 trans-04 m-r-16'>
              <i class='fa fa-facebook'></i>
            </a>
            <?php
          } // Fin del if de Facebook
          if ($empresa['instagram'] != '') {
            ?>
            <a title='instagram/' href='<?= $empresa['instagram'] ?>' target='_blank' class='fs-18 cl7 hov-cl1 trans-04 m-r-16'>
              <i class='fa fa-instagram'></i>
            </a>
            <?php
          } // Fin del if de Instagram
          ?>
        </div>
      </div>
    </div>
    <div class='p-t-10'>

      <div class='flex-c-m flex-w p-b-10'>
        <a href='<?= base_url() ?>tienda/#' class='m-all-1'><img loading="lazy" style ="width: 35; height: 23" src='<?= DIR_MEDIA ?>images/icons/icon-pay-01.png' alt='ICON-PAY'></a>
        <a href='<?= base_url() ?>tienda/#' class='m-all-1'><img loading='lazy' style ="width: 35; height: 23" src='<?= DIR_MEDIA ?>images/icons/icon-pay-02.png' alt='ICON-PAY'></a>
        <a href='<?= base_url() ?>tienda/#' class='m-all-1'><img loading='lazy' style ="width: 35; height: 23" src='<?= DIR_MEDIA ?>images/icons/icon-pay-03.png' alt='ICON-PAY'></a>
        <a href='<?= base_url() ?>tienda/#' class='m-all-1'><img loading='lazy' style ="width: 35; height: 23" src='<?= DIR_MEDIA ?>images/icons/icon-pay-04.png' alt='ICON-PAY'></a>
        <a href='<?= base_url() ?>tienda/#' class='m-all-1'><img loading='lazy' style ="width: 35; height: 23" src='<?= DIR_MEDIA ?>images/icons/icon-pay-05.png' alt='ICON-PAY'></a>
      </div>
    </div>
  </div>
</footer>

<?php
if (strlen($empresa['whatsapp_numero']) > 10) {
  // Botón de WhatsApp
  echo "<div class='whatsapp'>
    <a href='https://wa.me/" . $empresa['whatsapp_numero'] . "' id='WS_contact'
    onclick=\"window.open('https://wa.me/" . $empresa['whatsapp_numero'] . "?text=" . url_ws($empresa['whatsapp_texto']) . "', 'WhatsApp-dialog'); return false;\"
    target='_blank'>
    <i class='fa fa-whatsapp whatsapp-icon'></i></a>
    </div>";
}
?>
<!-- Botón de volver arriba -->
<div class='btn-back-to-top' id='myBtn'><span class='symbol-btn-back-to-top'><i class='fa fa-chevron-up'></i></span></div>
    <?= empty($_SESSION['login']) ? getModal('modalTienda', $data) : '' ?>

<script>const base_url = '<?= base_url(); ?>';</script>
<script>const media = '<?= DIR_MEDIA; ?>';</script>

<script defer type='text/javascript' src='<?= DIR_MEDIA ?>tienda/vendor/jquery/jquery-3.6.4.min.js'></script>
<script defer type='text/javascript' src='<?= DIR_MEDIA ?>tienda/vendor/animsition/js/animsition.js'></script>
<script defer type='text/javascript' src='<?= DIR_MEDIA ?>tienda/js/animsition-custom.js'></script>

<?php if ($page_name == 'scripts') { ?>
  <!--===============================================================================================-->	
  <!--<script defer type='text/javascript' src='//<?= DIR_MEDIA ?>tienda/vendor/jquery/jquery-3.6.4<?= $extension ?>'></script>-->
  <!--===============================================================================================-->
  <!--<script defer type='text/javascript' src='<?= DIR_MEDIA ?>tienda/vendor/isotope/isotope.pkgd<?= $extension ?>'></script>-->
  <!--===============================================================================================-->
  <!--  <script defer type='text/javascript' src='<?= DIR_MEDIA ?>tienda/vendor/parallax100/parallax100<?= $extension ?>'></script>
  <script type="text/javascript">        $('.parallax100').parallax100();</script>-->
<?php } ?>
<script>let cuerpo = document.querySelector("#cuerpo");
  if (cuerpo.innerHTML == '') {
    console.log(cuerpo.innerHTML);


    let URLactual = window.location;
    let formData = new FormData();
    formData.append('b', 1);

    fetch(URLactual, {method: 'POST', body: formData}).then(response => response.text())

            .then(data => {
              cuerpo.innerHTML = data;
              //console.log(data);
            })
            .catch(error => console.error(error));

  }</script>

<!--<script defer type='text/javascript' src='<?= DIR_MEDIA ?>tienda/vendor/bootstrap/js/bootstrap.bundle<?= $extension ?>'></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>-->
<!--<script defer type='text/javascript' src='<?= DIR_MEDIA ?>tienda/vendor/bootstrap/js/bootstrap<?= $extension ?>'></script>-->
<!--===============================================================================================-->
<script defer type='text/javascript' src='<?= DIR_MEDIA ?>tienda/vendor/slick/slick<?= $extension ?>'></script>
<script defer type='text/javascript' src='<?= DIR_MEDIA ?>tienda/js/slick-custom<?= $extension ?>'></script>
<!--===============================================================================================-->
<script defer type='text/javascript' src='<?= DIR_MEDIA ?>tienda/vendor/sweetalert/sweetalert.min.js'></script>
<!--===============================================================================================-->
<script defer type='text/javascript' src='<?= DIR_MEDIA ?>tienda/vendor/perfect-scrollbar/v1.1.0/perfect-scrollbar<?= $extension ?>'></script>
<?php if ($page_name == 'Contactos') { ?>
  <!--===============================================================================================-->
  <script defer type='text/javascript' src='https://maps.googleapis.com/maps/api/js?key=AIzaSyAKFWBqlKAGCeS1rMVoaNlwyayu0e0YRes'></script>
  <script defer type='text/javascript' src='<?= DIR_MEDIA ?>tienda/js/map-custom<?= $extension ?>'></script>
  <?php
}
if (isset($data['page_functions_js']) && $data['page_functions_js'] != '') {
  foreach ($data['page_functions_js'] as $value) {
    ?>
    <!--===============================================================================================-->
    <script defer type='text/javascript' src='<?= DIR_MEDIA . $value . $extension ?>'></script>
    <?php
  }
}
?>

<!--===============================================================================================-->
<script defer type='text/javascript' src='<?= DIR_MEDIA ?>tienda/js/main<?= $extension ?>'></script>
<!--===============================================================================================-->
<!--Ppoyecto -->
<?php if ($empresa['login_facebook'] === 1) { ?> 
  <script > const app_id = <?= $empresa['id_app_fb'] ?></script>
  <script async type='text/javascript' src="<?= DIR_MEDIA ?>js/functions_login_FB<?= $extension ?>"></script>
<?php } ?>
<script async type='text/javascript' src='<?= DIR_MEDIA ?>js/functions_login<?= $extension ?>'></script>

<!--<script defer type='text/javascript' src='<?= DIR_MEDIA ?>js/functions_admin<?= $extension ?>'></script>-->
<script defer type='text/javascript' src='<?= DIR_MEDIA ?>js/functions<?= $extension ?>'></script>

<?php
$excluir_ip = $empresa['excluir_ip'] != '' ? is_numeric(array_search(getUserIP(), explode(';', $empresa['excluir_ip']))) : null;
if ($excluir_ip != 1) {
  ?>
  <script defer type='text/javascript' src='<?= DIR_MEDIA ?>js/visit_counter<?= $extension ?>'></script>
<?php } ?>

</body>
</html>