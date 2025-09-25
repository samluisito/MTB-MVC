<?= headerTienda($data) ?>
<?php
//dep($data['empresa']);
$empresa = $data['empresa']
?>
<!-- Title page -->
<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('<?= DIR_MEDIA ?>images/bg-01.jpg');">
  <h2 class="ltext-105 cl0 txt-center">
    Registro
  </h2>
</section>	



<!-- Content page -->
<section class="bg0 p-t-20 p-b-25">
  <div class="container">

    <div class="flex-w flex-tr signup-step-container">

      <div class="container">
        <div class="bor10 p-lr-70 p-t-55 p-b-70 p-lr-15-lg">
          <form role="form" id="nvoUsusario">


            <h1 class="text-center">Crear Cuenta</h1>
            <br>
            <p class="text-center mb-4 px-4"> Comprá más rápido y llevá el control de tus pedidos, ¡en un solo lugar! </p>
            <br>


            <div class="row">
              <div class="col-md-6 bg0">
                <div class="bor8 how-pos4-parent form-group">
                  <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30 form-control campo-requerido" 
                         type="text" id='txtNombre' name="txtNombre" placeholder="Nombre" autocomplete="given-name">
                  <i class="fa fa-user how-pos4 pointer-none" aria-hidden="true"></i>
                </div>
                <div id="feedback-txtNombre" class="m-b-20 notBlock ">Nombre imvalido</div>
              </div>
              <div class="col-md-6">
                <div class="bor8 how-pos4-parent form-group">
                  <input class="stext-111 cl2 plh3 size-116 p-l-20 p-r-30 form-control campo-requerido" 
                         type="text" id="txtApellido" name="txtApellido" placeholder="Apellido" autocomplete="family-name">
                </div>
                <div id="feedback-txtApellido" class="m-b-20 notBlock ">Apellido invalido </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="bor8 how-pos4-parent form-group ">
                  <div class="stext-111 cl2 plh3 size-116 p-l-10 p-r-30 form-control">
                    <div class="row">
                      <div class="col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="sexo" id="sexoF" value="F" checked>
                          <label class="form-check-label stext-111 cl2 plh3 " for="sexoF">
                            Femenino
                          </label>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="sexo" id="SexoM" value="M">
                          <label class="form-check-label stext-111 cl2 plh3 " for="SexoM">
                            Masculino
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="feedback-Sexo" class="m-b-20 notBlock ">Seleccione un Sexo</div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="bor8 how-pos4-parent form-group">
                  <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30 form-control campo-requerido" 
                         type="tel"id="txtTelefono" name="txtTelefono" placeholder="Telefono" autocomplete="tel-national">
                  <i class="fa fa-phone-square how-pos4 pointer-none" aria-hidden="true"></i>
                </div> 
                <div id="feedback-txtTelefono" class="m-b-20 notBlock "> Telefono invalido</div>
              </div>
              <div class="col-md-6">
                <div class="bor8 how-pos4-parent form-group">
                  <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30 form-control " onblur="validarMailExist('txtEmailReg')" 
                         type="email" id="txtEmailReg" name="txtEmailReg" placeholder="Email" autocomplete="email">
                  <i class="fa fa-envelope how-pos4 pointer-none" aria-hidden="true"></i>
                </div>
                <div id="feedback-txtEmailReg" class="m-b-20 notBlock "></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="bor8 how-pos4-parent form-group">
                  <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30 form-control " type="password" id="txtPassword1" name="txtPassword" placeholder="Password" autocomplete="new-password">
                  <i class="fa fa-key how-pos4 pointer-none" aria-hidden="true"></i>
                </div> 
              </div>
              <div class="col-md-6">
                <div class="bor8 how-pos4-parent form-group">
                  <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30 form-control " type="password" id="txtRePassword1" name="txtRePassword" placeholder="Repite Password" autocomplete="new-password">
                  <i class="fa fa-key how-pos4 pointer-none" aria-hidden="true"></i>
                </div> 
              </div>
            </div>

            <br>
            <button type="submit" class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer">Registrar</button>
          </form>



          <div class="clearfix"></div>

          <!--          <div class="container">
                      <div class="row">
                        <div class="col-4">
                        </div>
                        <div class="col-4" id="facebook-session">
                          <a href="#" id="fblogin"class="btn btn-primary" onclick="loginFb()">Iniciar SesionFB</a>
          
                        </div>
                        <div class="col-4" id="test_status">
          
                        </div>
                      </div>
                    </div>-->


        </div>
      </div>
    </div>


  </div>
</section>	

<script>



</script>
<!-- Map -->
<!--div class="map">
 <div class="size-303" id="google_map" data-map-x="40.691446" data-map-y="-73.886787" data-pin="<?= DIR_MEDIA ?>images/icons/pin.png" data-scrollwhell="0" data-draggable="1" data-zoom="11"></div>
</div-->


<?= footerTienda($data) ?>