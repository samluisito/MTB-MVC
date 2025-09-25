<?php headerTienda($data); ?>
<link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,600,700,900&display=swap" rel="stylesheet">
<style type="text/css">
    body {
        background-image: url("<?= DIR_MEDIA . 'images/1920x1080_construction_background_514172.webp' ?>");
        background-repeat: no-repeat;
        background-position:right center;
        background-size: cover;
    } 
    .portada{
        background: #ffffff00;
    }
</style>

<div class="portada" id="portada">
    <div class="header">
        <h1 class="logotipo"><?= $data['empresa']['nombre_comercial'] ?></h1>
        <p class="mensaje">sitio web en construcción</p>
    </div>

    <div id="cuenta"class="col-md-12"></div>

    <div class="redes-sociales col-md-12">
        <?php if ($data['empresa']['facebook'] != '') { ?>
            <a href="<?= $data['empresa']['facebook'] ?>" class="btn-red-social"><i class="fab fa-facebook-f"></i></a>
        <?php } ?>
        <?php if ($data['empresa']['twitter'] != '') { ?>
            <a href="<?= $data['empresa']['twitter'] ?>" class="btn-red-social"><i class="fab fa-twitter"></i></a>
        <?php } ?>
        <?php if ($data['empresa']['instagram'] != '') { ?>
            <a href="<?= $data['empresa']['instagram'] ?>" class="btn-red-social"><i class="fab fa-instagram"></i></a>
            <?php } ?>

    </div>
</div>


<script>const mantenimiento_hasta = "<?= $data['empresa']['fecha_mantenimiento_hasta'] ?>"</script>
<script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>



<?= footerTienda($data); ?>
