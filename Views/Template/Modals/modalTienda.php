<!-- Modal  Login data-backdrop="static" -->
<div class="wrap-modal1 js-modal1 p-t-60 p-b-20 notBlock" id="modalLogin" >
  <div class="overlay-modal1 "></div> 
  <!--js-hide-modal1-->
  <div class="container-login container">
    <div class="bg0 p-t-10 p-b-5 p-lr-15-lg how-pos3-parent">
      <button class="how-pos3 hov3 trans-04 js-hide-modal1">
        <img src="<?= DIR_MEDIA ?>tienda/images/icons/icon-close.png" loading="lazy" style ="width: 20; height: 20" alt="CLOSE">
      </button>
      <div class="row p-t-15 p-b-15 p-lr-10">
        <div class="container">
          <div class="text-center p-t-15 p-b-15 p-lr-10">
            <?php if ($data['empresa']['login_facebook']) { ?> 
              <a href="#" class="btn-login-with bgfb m-b-10" onclick="loginFb()">
                <i class="fa fa-facebook-official"></i> Login with Facebook  </a>
            <?php } ?> 
<!--a href="#" class="btn-login-with bgtw"><i class="fa fa-twitter"></i>Login with Twitter</a-->
          </div>
        </div>
        <!--Formulario Login-->
        <div class="container">
          <div class="text-center p-t-5 p-b-0">
            <span class="txt1">
              Login con email
            </span>
          </div>
          <form class="px-2 py-3 " name="formLogin" id="formLogin" action="">
            <div class="form-group ">
              <label for="txtEmail" class="control-label">USUARIO</label>
              <input type="email" class="form-control" id="txtEmail" name="txtEmail"  placeholder="email@example.com" autofocus autocomplete="on">
            </div>
            <div class="form-group">
              <label for="txtPassword" class="control-label">PASSWORD</label>
              <input type="password" class="form-control" id="txtPassword" name="txtPassword"  placeholder="Password"autocomplete="on">
            </div>

            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="recuerdameCheck" name="recuerdame">
              <label class="form-check-label" for="recuerdameCheck">
                Recuerdame
              </label>
            </div>
          </form>
          <div class="form-group">
            <div class="form-group btn-container">
              <button class="btn btn-primary btn-block btn-login-with" onClick="login()" ><i class="fa fa-sign-in fa-lg fa-fw"></i>Ingresar</button>
            </div>      
          </div>
        </div>
        <div class="container pb-sm-3 pt-sm-0 text-center">
          <a class="dropdown-item p-t-10 p-b-5" href="<?= base_url() . 'registro' ?>"> No tienes cuenta? Registrate</a>
          <a class="dropdown-item p-t-5 p-b-15" href="<?= base_url() . 'login?r=reset' ?>">Olvide mi contraseña?</a>
        </div>
      </div>
    </div>
  </div>
</div>

