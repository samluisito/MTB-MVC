let divLoading = document.querySelector("#divLoading");


window.addEventListener('beforeunload', function (event) {
//  //divLoading.style.backgroundColor = "rgb(9 9 9 / 99%)";
//  divLoading.style.transition = "opacity 0.2s ease-in 0s";
//  divLoading.style.display = 'flex';
//  divLoading.style.opacity = 0.5;
//  setTimeout(() => {
//    divLoading.style.opacity = 0.9;
//  }, 200); // oculta imagen de carga 
});
document.addEventListener('DOMContentLoaded', function () {
  window.scrollTo(0, 0);
//  divLoading.style.opacity = 0;
//  setTimeout(() => {
//    divLoading.style.display = 'none';
//  }, 500); // oculta imagen de carga 

});

/*FUNCIONES EXISTENTES EN EL TEMPLATE ------------------------------------------*/

/*select2----------------------------------------------------------------------*/
$(".js-select2").each(function () {
  $(this).select2({
    minimumResultsForSearch: 20,
    dropdownParent: $(this).next('.dropDownSelect2')
  });
})

/*parallax100
 $('.parallax100').parallax100();*/

/*MagnificPopup------------------------------------------------------------*/
$('.gallery-lb').each(function () { // the containers for all your galleries
  $(this).magnificPopup({
    delegate: 'a', // the selector for gallery item
    type: 'image',
    gallery: {
      enabled: true
    },
    mainClass: 'mfp-fade'
  });
});
/*favotitos ----------------------------------------------------------------*/


function favoritoAddDel(accion, idprod, nameProduct) {
  let ajaxUrl = accion === 'add' ? `${base_url}tienda/addFavorito` : `${base_url}tienda/delFavorito`;
  let formData = new FormData();
  formData.append('id', idprod);

  fetch(ajaxUrl, {method: 'POST', body: formData}).then(response => response.json())
          .then(data => {
            if (data.status) {
              if (accion === 'add') {
//                swal(nameProduct, "is added to wishlist !", "success");
              } else {
//                swal(nameProduct, "is removed to wishlist !", "success");
              }
            } else {
              swal('Error', data.msg, "error");
            }
          })
          .catch(error => {
            console.log(error.message);
          });
}


document.querySelectorAll('.js-addwish-b2').forEach(wishButton => {
  let nameProduct = wishButton.parentNode.parentNode.querySelector('.js-name-b2').innerHTML;
  wishButton.addEventListener('click', function (e) {
    e.preventDefault();
    if (sessionlogin == 0) {
      mostrarModalLogin();
    } else {
      let idprod = this.id.split('-')[1];
      if (this.classList.contains('js-addedwish-b2')) {
        document.querySelector(`#fav-${idprod}`).classList.remove('js-addedwish-b2');
        favoritoAddDel('del', idprod, nameProduct);
      } else {
        document.querySelector(`#fav-${idprod}`).classList.add('js-addedwish-b2');
        favoritoAddDel('add', idprod, nameProduct);
      }
    }
  });
});


document.querySelectorAll('.js-addwish-detail').forEach(wishButton => {

  let nameProduct = wishButton.parentNode.parentNode.parentNode.querySelector('.js-name-detail').innerHTML;

  wishButton.addEventListener('click', function (e) {

    e.preventDefault();
    if (sessionlogin == 0) {
      mostrarModalLogin();
    } else {
      let idprod = this.id.split('-')[1];
      if (this.classList.contains('js-addedwish-detail')) {
        console.log('del')
        document.querySelector(`#fav-${idprod}`).classList.remove('js-addedwish-detail');
        favoritoAddDel('del', idprod, nameProduct);
      } else {
        console.log('add')
        document.querySelector(`#fav-${idprod}`).classList.add('js-addedwish-detail');

        favoritoAddDel('add', idprod, nameProduct);
      }
    }
  });
});

/* Lista blanca de producto ---------------------------------------------*/

//$('.js-addwish-detail').each(function () {
//  //let nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();
//  $(this).on('click', function (e) {
//    e.preventDefault();
//    //swal(nameProduct, "is added to wishlist !", "success");
////    if (sessionlogin == 0) {// VALIDAMOS QUE LA SESSION ESTE INICIADA, SI ELUSUARIO ESTA LOGUEADO PODRA AGRAGAR EL PROCTO AL CARRITO, DE LO CONTRARO SOLICITA QUE SE LOGUEE
////      mostrarModalLogin();//MODAL DE LOGUEO
////    } else {
//    let idprod = this.id.split('-')[1];
//    if (this.classList.contains('js-addedwish-detail')) {// mostramos u ocultamos el corazon segun sea
//      let add = favoritoAddDel(idprod); //ejecutamos la funcion de borrar favorito
//      if (add == 1) {
//        $(this).removeClass('js-addedwish-detail');
//        //$(this).off('click');
//      }
//    } else {
//      let add = favoritoAddDel(idprod); //ejecutamos la funcion de borrar favorito
//      if (add == 1) {
//        $(this).addClass('js-addedwish-detail');
//      }
//    }
////    }
//  });
//});

/* PerfectScrollbar ---------------------------------------------*/
$('.js-pscroll').each(function () {
  $(this).css('position', 'relative');
  $(this).css('overflow', 'hidden');
  let ps = new PerfectScrollbar(this, {
    wheelSpeed: 1,
    scrollingThreshold: 1000,
    wheelPropagation: false,
  });
  $(window).on('resize', function () {
    ps.update();
  })
});

/*FUNCIONES DE LA PAGINA------------------------------------------------------*/

/*Agregar Producto Carrito---------------------------------------------------*/
$('.js-addcart-detail').each(function () { //busca todos los elementos con la clase '.js-addcart-detail'
  let nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html(); //de este 'this' elemento al que se le da clic se sube al padre, del paadre, del padre delpadre, para llegar a su elemento con la clase '.js-name-detail' y capturar el nombre 
  $(this).on('click', function () {// en este elemento al que le damos click ejecutamos la funcion 

    let id = this.getAttribute('id'); //de este elemento al que le damos click capruramos el valor del atributo 'id'
    let cant = Number(document.querySelector('#cant-product').value);
    isNaN(cant) || cant < 1 ? (swal(nameProduct, "La cantidad debe de ser mayor o igual a 1 ", "error"), false) : true;
    let talle = document.querySelector('#talle-product') ? document.querySelector('#talle-product').value : null;
    let color = document.querySelector('#color-product') ? document.querySelector('#color-product').value : null;


    let formData = new FormData();
    formData.append('id', id);
    formData.append('cant', cant);
    formData.append('talle', talle);
    formData.append('color', color);
    fetch(base_url + 'tienda/addCarrito',
            {
              method: 'POST',
              body: formData
            }).then(res => res.json())
            .then(objData => {
              if (objData.status) {
                document.getElementById('productosCarrito').innerHTML = objData.htmlCarrito;
                document.querySelector('.header-cart-total').innerHTML = 'Total: ' + objData.totalCarrito;

                document.querySelectorAll('.cantCarrito').forEach((function (x) { // agregamos a todos los elementos que tengan la clase cantCarrito  
                  x.setAttribute("data-notify", objData.cantCarrito);
                }));
                swal(nameProduct, "ha sigo agregadoi al carrito!", "success");
              } else {
                swal("Error", objData.msg, "error");
              }
            }).catch(error => console.error('Error:', error));

  });
});

/* Borrar producto Carrito====================================================================*/
function fntdelItem(element) {
  let option = element.getAttribute('op');
  let idpr = element.getAttribute('idpr');
  if (option == 1 || option == 2) {

    let formData = new FormData();
    formData.append('id', idpr);
    formData.append('option', option);
    fetch(base_url + 'tienda/delCarrito', {
      method: 'POST',
      body: formData
    }).then(res => res.json())
            .catch(error => console.error('Error:', error))
            .then(objData => {
              if (objData.status) {
                if (option == 1) {
                  document.querySelector('#productosCarrito').innerHTML = objData.htmlCarrito;
                  document.querySelectorAll('.cantCarrito').forEach((function (x) { // agregamos a todos los elementos que tengan la clase cantCarrito  
                    x.setAttribute("data-notify", objData.cantCarrito);
                  }));
                } else if (option == 2) {
                  element.parentNode.parentNode.remove();
                  document.querySelector('#subTotalCompra').innerHTML = objData.subTotal; //
                  document.querySelector('#totalCompra').innerHTML = objData.total; //     
                  if (document.querySelectorAll("#tblCarrito tr").length == 1) {
                    swal({
                      title: "UPSS YA NO HAY PRODUCTOS EN EL CARRITO",
                      text: "Seras redireccionado a la pagina de inicio",
                      icon: 'info',
                      buttons: 'OK',
                      dangerMode: true
                    }).then((value) => {
                      window.location.href = base_url;
                    })
                  }
                }
              } else {
                swal("Error", objData.msg, "error");
              }
            });
  }
}




/*==================================================================
 [ +/- num product ]   los botones*/
$('.btn-num-product-down').on('click', function () {
//  if (sessionlogin == 0) {// VALIDAMOS QUE LA SESSION ESTE INICIADA, SI ELUSUARIO ESTA LOGUEADO PODRA AGRAGAR EL PROCTO AL CARRITO, DE LO CONTRARO SOLICITA QUE SE LOGUEE
//    mostrarModalLogin();//MODAL DE 
//  } else {
  let numProduct = Number($(this).next().val());
  if (numProduct > 1)
    $(this).next().val(numProduct - 1);
  if (document.querySelector("#btnComprar")) {
    let idpr = this.getAttribute('idpr');
    let cant = $(this).next().val();
    fntUpdateCant(idpr, cant);
  }
//  }
});
$('.btn-num-product-up').on('click', function () {
//  if (sessionlogin == 0) {// VALIDAMOS QUE LA SESSION ESTE INICIADA, SI ELUSUARIO ESTA LOGUEADO PODRA AGRAGAR EL PROCTO AL CARRITO, DE LO CONTRARO SOLICITA QUE SE LOGUEE
//    mostrarModalLogin();//MODAL DE 
//  } else {
  let numProduct = Number($(this).prev().val());
  $(this).prev().val(numProduct + 1);
  if (document.querySelector("#btnComprar")) {
    let idpr = this.getAttribute('idpr');
    let cant = $(this).prev().val();
    fntUpdateCant(idpr, cant);
  }
//  }
});
/*==================================================================*/
/* [input num ]   */
if (document.querySelector('.num-product')) {
  let inputCant = document.querySelectorAll('.num-product');
  inputCant.forEach(function (inputCant) {
    inputCant.addEventListener('keyup', function () {
      let idpr = this.getAttribute('idpr');
      let cant = this.value;
      fntUpdateCant(idpr, cant);
    });
  });
}


/*Agragar cant producto carrito ==================================================================*/
function fntUpdateCant(idpr, cant) {

  if (cant <= 0) {
    document.querySelector("#btnComprar").classList.add("notBlock");
  } else {
    document.querySelector("#btnComprar").classList.remove("notBlock");
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + 'tienda/updCarrito';
    let formData = new FormData();
    formData.append('id', idpr);
    formData.append('cant', cant);
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
      if (request.readyState == 4 && request.status == 200) {

        let objData = JSON.parse(request.responseText);
        if (objData.status) {
          let colSubtotal = document.getElementsByClassName(idpr)[0]; //anteriormente al elemento row-table le agregamos una clase que es el id encriptado del peoducto

          colSubtotal.cells[4].textContent = objData.totalProducto; //con .cells[4] nos dirigimos a td con la posicion 4, y con .textcontent le asignamos el valor que viene del servidor
          document.querySelector('#subTotalCompra').innerHTML = objData.subTotal; //
          document.querySelector('#totalCompra').innerHTML = objData.total; //                  

          /*  document.querySelectorAll('.cantCarrito').forEach.setAttribute("data-notify", objData.cantCarrito);
           document.querySelectorAll('.cantCarrito').forEach((function (x) { // agregamos a todos los elementos que tengan la clase cantCarrito  
           x.setAttribute("data-notify", objData.cantCarrito);
           }));*/
          // swal(nameProduct, "ha sigo agregadoi al carrito!", "success");
        } else {
          swal("Error", objData.msg, "error");
        }


      }
    }
  }
}

/*Aceptar condiciones registro==================================================================*/
if (document.querySelector('#condiciones')) {
  let condiciones = document.querySelector('#condiciones');
  condiciones.addEventListener('click', function () {
    let opcion = this.checked;
    if (opcion) {
      document.querySelector("#btnRegistrarme").classList.remove('notBlock')
    } else {
      document.querySelector("#btnRegistrarme").classList.add('notBlock')
    }
  });
}

/*FORMULARIO DE CONTACTO ==================================================================*/
if (document.querySelector('#formContacto')) {
  let form = document.querySelector('#formContacto');
  form.onsubmit = function (e) {
    e.preventDefault();
    let nombre = document.querySelector('#txtNombre').value;
    let apellido = document.querySelector('#txtApellido').value;
    let telefono = document.querySelector('#txtTelefono').value;
    let email = document.querySelector('#txtEmail').value;
    if (nombre === '' || apellido === '' || telefono === '' || email === '') {
      swal("Datos Incompletos", 'COMPLETE LOS DATOS NECESARIOS', "error");
    } else {

//      divLoading.style.display = "flex"; //muestra imagen de espera 

      let ajaxUrl = base_url + 'contacto/formContacto';
      let formData = new FormData(form); // creamos un nuevo objeto formulario y le agregamos los datos seguientes

      fetch(ajaxUrl, {
        method: 'POST',
        body: formData
      }).then(res => res.json())
              .then(objData => {
                if (objData.status) {
                  swal({
                    title: 'GRACIAS POR CONTACTARNOS',
                    text: objData.msg,
                    icon: 'success',
                    buttons: 'Volver al home',
                    dangerMode: true
                  }).then((value) => {
                    window.location.href = base_url + 'home';
//              window.location.reload();

                  });

                } else {
                  swal("Error", objData.msg, "error");
                }

              });

    }
  };
}


function mostrarModalLogin() {
  let modal_login = document.querySelector("#modalLogin");
  if (modal_login.classList.contains('notBlock')) {
    modal_login.classList.remove('notBlock');
  }

  setTimeout(() => {
    modal_login.classList.add('show-modal1');
  }, 5);

  //$('.js-modal1').addClass('show-modal1');
}
/*REGISTRO DE NUEVO USUARIO==================================================================*/

/*WIZARD Validacion de pasos ------------------------------------------------------------------------------*/
if (document.querySelector('.campo-requerido')) {
  let campos = document.querySelectorAll('.campo-requerido');
  campos.forEach(function (inputCant) {
    inputCant.addEventListener('blur', function () {
      let id_elemento = '#' + this.id;
      let valor = this.value.trim();
      let caract_min = 3;
      let feedback = '#feedback-' + this.name;//selecciona el name del input para seleccional el id fedback
      if (valor.length >= caract_min) { // si el valor es mayor a caracteres minimos , entonces esta ok
        //document.querySelector(id_elemento).classList.add('is-valid');
        document.querySelector(id_elemento).classList.remove('is-invalid');
        document.querySelector(id_elemento).value = valor;
        document.querySelector(feedback).classList.remove("invalid-feedback");
        document.querySelector(feedback).classList.add("notBlock");
      } else {// si por el contrario el valor es NO mayor a caracteres minimos , entonces esta esta incorrecto 
        //document.querySelector(id_elemento).classList.remove('is-valid');
        document.querySelector(id_elemento).classList.add('is-invalid');
        document.querySelector(id_elemento).value = valor;
        document.querySelector(feedback).classList.add("invalid-feedback");
        document.querySelector(feedback).classList.remove("notBlock");
      }
    });
  });
}
/*Validacion de Email en el formulario de registro*/
function validarMailExist(elemento) {
  elemento = '#' + elemento;
  let mail = document.querySelector(elemento).value.trim();//limpiamos los espacios recibidos
  let variable2 = elemento.replace('#', '');
  let feedback = '#feedback-' + variable2;
  if (mail.length < 8) {
    document.querySelector(elemento).classList.remove('is-valid');
    document.querySelector(elemento).classList.add('is-invalid');
    document.querySelector(feedback).classList.add('invalid-feedback');
    document.querySelector(feedback).classList.remove('notBlock');
    document.querySelector(feedback).innerHTML = 'Mail Invalido';
  } else {
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + 'registro/validarMail';
    let formData = new FormData();
    formData.append('mail', mail);
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function (resultado) {
      if (request.readyState == 4 && request.status == 200) {

        let resp = JSON.parse(request.responseText);
        //let mail = document.querySelector(elemento).value.trim();
        if (resp == 'OK') {

          document.querySelector(elemento).classList.remove('is-invalid');
          document.querySelector(elemento).classList.add('is-valid');
          document.querySelector(feedback).innerHTML = '';
          document.querySelector(feedback).classList.remove('invalid-feedback');
          document.querySelector(feedback).classList.add('notBlock');
        } else if (resp == 'Exist') {
          document.querySelector(elemento).classList.remove('is-valid');
          document.querySelector(elemento).classList.add('is-invalid');
          document.querySelector(feedback).innerHTML = 'Este Email ya se encuentra registrado';
          document.querySelector(feedback).classList.remove('notBlock');
          document.querySelector(feedback).classList.add('invalid-feedback');
        } else if (resp == 'ivalid_format') {
          document.querySelector(elemento).classList.remove('is-valid');
          document.querySelector(elemento).classList.add('is-invalid');
          document.querySelector(feedback).innerHTML = 'Formato de Email invalido ';
          document.querySelector(feedback).classList.remove('notBlock');
          document.querySelector(feedback).classList.add('invalid-feedback');
        }
      }
    }
  }
  document.querySelector(elemento).value = mail;
}
/* WIZARD Boton Siguiente -------------------------------------------------------------*/
function btnPasoSiguiente(num) { //valida que los campos tipo texto y numerico no se encuentren vacios antes de pasar de pagina  
  let validar = 0;
  let campos = document.querySelectorAll('.paso-' + num);//seleccionamos todos loca campos con la clase .paso-' + num
  campos.forEach(function (item, index) { //recorremos la lista
    let id_elemento = item.id;//en el elemento item, del indice en el que nos encontramos, seleccionamos su atributo ID
    let valor = item.value;
    let element = document.getElementById(id_elemento);//consultamos si el el lemento tiene una clase con element.matches(CLASE)
    if (element.matches('.is-invalid')) {
      validar++;
    } else {
      if (valor == '') { // si el elemto es igual a nada, indica que presionaron el boton siguiente sin llenar el formulario 
        //document.querySelector(id_elemento).classList.add('is-valid');
        document.getElementById(id_elemento).classList.add('is-invalid');
        validar++;
      }
    }
  })
  if (document.getElementById('metodoEntregaSelect')) { // SOLO APLICA A carrito/procesarpago
    if (document.getElementById('metodoEntregaSelect').value == 'retiro') { // Si metodoEntregaSelect existe y su valor es == retiro, no validamos los campor del paso 1 del wizard 
      validar = 0;
    }
  }
  if (validar == 0) {
    let active = $('.wizard .nav-tabs li.active'); // habilitamos el cambio de pagina al elemento siguiente 
    nextTab(active);
  }
}

/* WIZARD Formulario de Registro------------------------------------------------------------------------------*/
if (document.querySelector('#nvoUsusario')) {
  let formRegister = document.querySelector("#nvoUsusario");
  formRegister.onsubmit = function (e) {
    e.preventDefault();
//    divLoading.style.display = "flex"; //muestra imagen de espera 

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + 'registro/regNvoUsuario';
    let formData = new FormData(formRegister);
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
//      divLoading.style.display = "none"; // oculta imagen de carga 
      if (request.readyState == 4 && request.status == 200) {

        let objData = JSON.parse(request.responseText);
        if (objData.status) {
          setTimeout(() => window.history.back(), (1000 * 30));
          swal({
            title: 'Registro Exitoso',
            text: objData.msg,
            icon: 'info',
            buttons: 'OK',
            dangerMode: true
          }).then((value) => {
            window.history.back();
            // window.location.reload();
          });


        } else {
          swal("Error", objData.msg, "error");
        }
      }
    }
  }
}

function btnBuscarEnter(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    //e.btnBuscar();
    let searchW = document.querySelector('#searchW').value;// selecciona campo de busqueda web 
    let searchM = document.querySelector('#searchM').value;// selecciona campo de busqueda mobil 
    let buscar = searchW == '' ? searchM : searchW;
    window.location.href = base_url + 'tienda/search/' + buscar;
  }
}

if (document.querySelector('.wrap-slick3-dots') && (document.querySelector('.slick-track'))) {
//determina la altura de las imagenes en miniatura de la pagina de prosucto
  alturaSlick3Dots();
  window.onresize = () => alturaSlick3Dots();
  screen.orientation.onchange = () => alturaSlick3Dots();

}
function alturaSlick3Dots() {
  setTimeout(() => {//se establece una retardo en la lectura y escritura de los divs
    let altura = document.querySelector('.slick-track').clientHeight;
    document.querySelector('.wrap-slick3-dots').style.height = altura + 'px';
  }, 50);
}
