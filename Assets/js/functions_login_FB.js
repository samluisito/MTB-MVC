let div_fbsession = '<div class="col-md-4" id="facebook-session"><a href="#" id="fblogout"class="btn btn-danger">Cerrar SesionFB</a></div>';
let scopes = 'public_profile,email,user_online_presence';//,user_friends
let fields = '?fields=id,first_name,last_name,email,birthday,gender,picture.height(400).width(400){url},location';//name,picture.type(large){url}


// Inicializa el SDK de Facebook
window.fbAsyncInit = function () {
  FB.init({
    appId: app_id,
    cookie: true,
    xfbml: true,
    version: 'v11.0'
  });

  // Registra un evento de página
  FB.AppEvents.logPageView();
};

// Inserta el SDK de Facebook en el DOM
(function (d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {
    return;
  }
  js = d.createElement(s);
  js.id = id;
  js.src = "https://connect.facebook.net/es_LA/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// Función de inicio de sesión
function loginFb() {
  FB.getLoginStatus(function (response) { // Llamado después de que se haya inicializado JS SDK. y verificamos el estado del login
    //console.log(response);
if (response.status === 'conectado') { // Inicie sesión en su página web y Facebook. si ya se logueado anteriormente, pasamos los datos para el inicio de sesion
       iniciar_sesion(response);//testAPI(); // Devuelve el estado de inicio de sesión.
     } else {
       FB.login(function (response) {// handle the response . si no se encontró logueado en FB o no había iniciado sesion en la appfb. se ejecuta FB.login para solicitar el inicio de sesion en la apk
         if (response.status === 'conectado') { // Inicie sesión en su página web y Facebook. si el estado está conectado se envían los datos para el inicio de sesión
           iniciar_sesion(response);//testAPI(); // Devuelve el estado de inicio de sesión.//funcion de envio de datos al servidor
         } else { // La persona no ha iniciado sesión en su página web o no podemos saberlo.
           //posible swal debe aceptar las condiciones de la app
         }
       }, {scope: scopes}); //'perfil_publico,email'
    }
  });
}

// Función de inicio de sesión
function iniciar_sesion(response) {
  FB.api('/me' + fields, function (response) {
    //console.log(response);
    //funcion de insersion de datos en el servidor.
    let formData = new FormData();
    formData.append("oauth_uid", response.id);
    formData.append("nombre", response.first_name);
    formData.append("apellido", response.last_name);
    formData.append("email", response.email);
    formData.append("img", response.picture.data.url);

    response.birthday ? formData.append("birthday", response.birthday) : '';
    response.gender ? formData.append("gender", response.gender) : '';
    response.location ? formData.append("location", response.location.name) : '';

    let ajaxUrl = base_url + 'registro/regConFb';
    fetch(ajaxUrl, {
      method: 'POST',
      body: formData
    }).then(response => response.json())
            .then(objData => {
              if (objData.status) {
                Swal.fire({
                  title: objData.title,
                  text: objData.msg,
                  icon: "success",
                  buttons: 'OK',
                  dangerMode: true
                }).then((value) => {
                  window.location.reload();
                });
                document.querySelector('.swal-text').innerHTML = "<img src='" + objData.msg + "' width='70px' height='70px' />";
              } else {
                Swal.fire('Error', objData.msg, "error");
                return;
              }
            });


  }, {scope: 'email'});
}

// Función de cierre de sesión
function logoutFb() {
  FB.logout(function (response) {// Person is now logged out
    console.log(response);
  });
}