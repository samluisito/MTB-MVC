<?= headerTienda($data) ?>
<?php
//dep($data['empresa']);
$empresa = $data['empresa'];

?>
<!-- Title page -->
<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('<?= DIR_MEDIA ?>images/bg-01.jpg');">
    <h2 class="ltext-105 cl0 txt-center">
        Contact
    </h2>
</section>	


<!-- Content page -->
<section class="bg-transparent p-t-20 p-b-25">
    <div class="container">
        <div class="flex-w flex-tr">
            <div class="size-210 bg0 bor10 p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full-md">
                <form id="formContacto">
                    <h4 class="mtext-105 cl2 txt-center p-b-30">
                        Envianos un mensaje
                    </h4>

                    <div class="bor8 m-b-20 how-pos4-parent">
                        <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="text"id='txtNombre' name="txtNombre" placeholder="Nombre" required="">
                        <i class="fa fa-user  how-pos4 pointer-none" aria-hidden="true"></i>
                        <!--img class="how-pos4 pointer-none" src="<?= DIR_MEDIA ?>images/icons/icon-email.png" alt="ICON"-->
                    </div>
                    <div class="bor8 m-b-20 how-pos4-parent">
                        <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="text" id="txtApellido" name="txtApellido" placeholder="Apellido"required="">
                        <!--img class="how-pos4 pointer-none" src="<?= DIR_MEDIA ?>images/icons/icon-email.png" alt="ICON"-->
                    </div>
                    <div class="bor8 m-b-20 how-pos4-parent">
                        <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="tel"id="txtTelefono" name="txtTelefono" placeholder="Telefono"required="">
                        <i class="fa fa-phone-square  how-pos4 pointer-none" aria-hidden="true"></i>
                        <!--img class="how-pos4 pointer-none" src="<?= DIR_MEDIA ?>images/icons/icon-email.png" alt="ICON"-->
                    </div>
                    <div class="bor8 m-b-20 how-pos4-parent">
                        <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="email" id="txtEmail" name="txtEmail" placeholder="Email"required="">
                        <i class="fa fa-envelope  how-pos4 pointer-none" aria-hidden="true"></i>
                        <!--img class="how-pos4 pointer-none" src="<?= DIR_MEDIA ?>images/icons/icon-email.png" alt="ICON"-->        
                    </div>

                    <div class="bor8 m-b-30">
                        <textarea class="stext-111 cl2 plh3 size-120 p-lr-28 p-tb-25" id="txtMensaje" name="mensaje" placeholder="Como podemos ayudarte?"></textarea>
                    </div>

                    <button type="submit" class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer">
                        Enviar
                    </button>
                </form>
            </div>

            <div class="size-210 bg0 bor10 flex-w flex-col-m p-lr-93 p-tb-30 p-lr-15-lg w-full-md">
                <div class="flex-w w-full p-b-42">
                    <span class="fs-18 cl5 txt-center size-211">
                        <i class="fa fa-map-marker"></i>
                    </span>

                    <div class="size-212 p-t-2">
                        <span class="mtext-110 cl2">
                            Direccion
                        </span>

                        <p class="stext-115 cl6 size-213 p-t-18">
                            <?= $empresa['direccion'] ?>
                        </p>
                    </div>
                </div>

                <div class="flex-w w-full p-b-42">
                    <span class="fs-18 cl5 txt-center size-211">
                         <i class="fa fa-phone"></i>
                    </span>

                    <div class="size-212 p-t-2">
                        <span class="mtext-110 cl2">
                            Llamanos
                        </span>

                        <p class="stext-115 cl1 size-213 p-t-18">
                           <a href="tel:<?= $empresa['telefono'] ?>"><?= $empresa['telefono'] ?></a>             
                        </p>
                    </div>
                </div>

                <div class="flex-w w-full">
                    <span class="fs-18 cl5 txt-center size-211">
                         <i class="fa fa-envelope"></i>
                    </span>

                    <div class="size-212 p-t-2">
                        <span class="mtext-110 cl2">
                            Escribenos
                        </span>

                        <p class="stext-115 cl1 size-213 p-t-18">
                           <a target="_blank" href="mailto:<?= $empresa['email'] ?>  "> <?= $empresa['email'] ?>  </a>                 
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>	


<!-- Map -->
<!--div class="map">
    <div class="size-303" id="google_map" data-map-x="40.691446" data-map-y="-73.886787" data-pin="<?= DIR_MEDIA ?>images/icons/pin.png" data-scrollwhell="0" data-draggable="1" data-zoom="11"></div>
</div-->



<?= footerTienda($data); ?>