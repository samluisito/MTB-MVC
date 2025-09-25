document.addEventListener('DOMContentLoaded', function () {
  // Login Page Flipbox control
  if (document.querySelector(".login-content")) {
    $('.login-content [data-toggle="flip"]').click(function () {
      $('.login-box').toggleClass('flipped');
      return false;
    });
  }

  if (document.querySelector("#formRsetParword")) {
    restPass();
  }
  if (document.getElementById("password-addon")) {
    document.getElementById("password-addon").addEventListener("click", function () {
      let e = document.getElementById("txtPassword");
      e.type = "password" == e.type ? "text" : "password";
    });
  }

});

function login() {
  /*capturamos los valores formulario*/
  let strEmail = document.querySelector("#txtEmail").value;
  let strPassword = document.querySelector("#txtPassword").value;
  if (strEmail === "" || strPassword === "") {//valida que el formulario no este vacio
    swal("Por Favor", "Escribe Usuario y Contraseña", "error");
    return false;
  } else {
    let formLogin = document.querySelector("#formLogin");
    let formData = new FormData(formLogin);
    fetch(base_url + 'login/loginUser', {
      method: 'POST',
      body: formData
    }).then(response => response.json())
            .then(objData => {
              if (objData.status) {//si el estatus es true
                //limpiamos la url
                history.pushState(null, null, "?txtEmail=" + strEmail + "&txtPassword=" + strPassword + "#");
                history.replaceState({}, document.title, window.location.pathname);
                
                if (document.title === 'login') {// si estamos en la pagina de login redirigimos al dashboard
                  window.location.href = base_url + "dashboard";
                } else {// si no estamos en la pagina de loguin, significa que estamos en alguna otra pagina, entonces refrescamos la pagina
                  location.reload();
                }
              } else {// si el estatus es false, significa que los datos de loguin son ncorrectos 
                if (typeof swal === "function" && typeof swal.showLoading === "undefined") {//validamos que version de SweetAlert estamos usando, si es la version 1
                  swal("Atencion!", objData.msg, "error");
                  //location.reload();
                } else {//si es la version 2 de sweet alert
                  Swal.fire('Atencion!', objData.msg, 'error');
                }
              }
            })
            .catch(error => {
              console.error('Error:', error);// alert('Error:', error);
            });
  }
}



function restPass() {
  let formResetPass = document.querySelector("#formRsetParword");
  formResetPass.onsubmit = function (event) {
    event.preventDefault();
    //let indica que la letiable sera usada solo dentro de la funcion
    let strPassword = document.querySelector("#txtPassword").value;
    let strPasswordConfirm = document.querySelector("#txtPasswordConfirm").value;
    if (strPassword !== "" || strPasswordConfirm !== "") {
      if (strPassword !== strPasswordConfirm) {
        Swal.fire("Error", "Las Contraseñas no son iguales", "error");
        return false;
      }
      if (strPassword.length < 5) {
        Swal.fire("Error", "Las contraseña debe tener minimo 5 caracteres", "info");
        return false;
      }
    }
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + 'login/actualizarPassword';
    let formData = new FormData(formResetPass);
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
      if (request.readyState !== 4)
        return;
      if (request.status === 200) {
        let objData = JSON.parse(request.responseText);
        if (objData.status) {
          Swal.fire({
            title: "",
            text: objData.msg,
            type: "success",
            confirmButtonText: "Aceptar",
            closeOnConfirm: false
          }, function (isConfirm) {
            if (isConfirm) {
              window.location = base_url + 'dashboard';
            }
          });
        } else {
          Swal.fire("Atencion", objData.msg, "error");
          document.querySelector('#txtPassword').value = "";
          document.querySelector('#txtPasswordConfirm').value = "";
        }
      } else {
        Swal.fire("Error", "Error en el proceso", "error");
      }
      return false;
    }
    // console.log(request);
  }
}

function fntForgetPassword() {
  let formFP = document.querySelector("#formForgetPassword"); //let indica que la vaariable sera usada solo dentro de la funcion
  formFP.onsubmit = function (event) {
    event.preventDefault(); //evita que la pagina se recargue
    let strEmailFP = document.querySelector("#txtEmailFP").value;
    if (strEmailFP === "") {
      Swal.fire("Por Favor", "Escribe tu Usuario", "error");
      return false
    } else {
      let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
      let ajaxUrl = base_url + 'login/forgetPassword';
      let formData = new FormData(formFP);
      request.open("POST", ajaxUrl, true);
      request.send(formData);
      request.onreadystatechange = function () {
        if (request.readyState !== 4)
          return;
        if (request.status === 200) {
          console.log('1');
          let objData = JSON.parse(request.responseText);
          if (objData.status) {

            Swal.fire("Atencion", objData.msg, "success");
          } else {
            Swal.fire("Atencion", objData.msg, "error");
          }
        } else {
          Swal.fire("Error", "Error en el proceso", "error");
        }
        return false;
      }
      // console.log(request);
    }
  }
}
