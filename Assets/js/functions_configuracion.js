"use strict";

let tableRegiones;


document.addEventListener('DOMContentLoaded', function () {
  if (document.querySelector("#formConfiguracion")) {
    formConfiguracion();
    allFotoConfiguracion();
  }
  if (document.querySelector("#formTiposDePago")) {
    formTiposDePago();
  }

});

function allFotoConfiguracion() {
  let fotoinput = document.querySelectorAll('.fotoinput');// seleccionamos todos los elementos con la clase foto input
  fotoinput.forEach((function (fotoinput) { // recorremos la una lista con los elementos fotoimput 
    let inputid = fotoinput.id; // se genera la variable foto 
    let foto = document.querySelector("#" + inputid); // se genera la variable foto      
    //document.querySelector("#foto_remove").value = "0"; //punto de cambio para actualizacion imagen de categoria
    foto.onchange = function (e) {  // el evento onChage se ejecuta cuan hay algun cambio en el nombre o el valor del imput id=foto
      e.preventDefault();
      let uploadFoto = document.querySelector("#" + inputid).value; //capturamos el valor en el imput como en cualquier otro elemento
      let fileimg = document.querySelector("#" + inputid).files; // capturamos el archivo en el input foto
      let nav = window.URL || window.webkitURL;
      let contactAlert = document.querySelector('#form_alert' + inputid);
      if (uploadFoto !== '') { //si es diferente a vacio se captura el tipo y el nombre para validarlos
        let type = fileimg[0].type; // Tipo del archivo 
        let name = fileimg[0].name; // nombre del archivo
        if (type !== 'image/jpeg' && type !== 'image/jpg' && type !== 'image/png') { //se valida que los tipos de imagen sean png, jpg, jpeg,   si no lo son 
          contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>'; //se inserta mensaje de archivo invalido
          if (document.querySelector('#img' + inputid)) { //si se cargo el archivo en id img
            document.querySelector('#img' + inputid).remove(); // se remueve    
          }
          //document.querySelector('.delPhoto').classList.add("notBlock");// cambia el estilo .delPhoto por notBlock
          foto.value = ""; // el valor vuelve a vacio
          return false; // se retorna un false, para que no continue avanazando la ejecucion del script
        } else { //si el tipo de archivo es imagen, 
          contactAlert.innerHTML = ''; // el aviso de archivo erroneo pasa a vacio
          if (document.querySelector('#img' + inputid)) { //verifica si existe este elemento 
            document.querySelector('#img' + inputid).remove(); // si el elemto existe lo remueve
          }
          //document.querySelector('.delPhoto').classList.remove("notBlock"); //se dirige a este elemento y remueve la clase notBlock, lo que mostraria la X de eliminar imagen
          let objeto_url = nav.createObjectURL(this.files[0]);// objeto_url hace referencia a la vaiable nav, que previamente esta cargandola ruta de la imagen, y se crea un nuevo objeto con createObjectURL haciendo referencia al archivo seleccionado y y en su posicion 0 extrae esa ruta
          document.querySelector(".prevPhoto-" + inputid + "  div").innerHTML = "<img id='img" + inputid + "' src=" + objeto_url + " requiered=''>"; // hace referencia a la clase prevPhoto que dentro de este elemnto hay un div, y en su HTML crea un nuevo elemto img con el id=img y crea en el src la ruta temporal de la imagen 
        }
      } else {// si no se ha seleccionado foto muestra una alerta 
        alert("No selecciono foto");
        if (document.querySelector('#img' + inputid)) {
          document.querySelector('#img' + inputid).remove();
        }
      }
      document.querySelector("#foto_remove_" + inputid).value = 1;
    };
    if (document.querySelector(".delPhoto")) {
      let delPhoto = document.querySelector(".delPhoto");
      delPhoto.onclick = function (e) {
        document.querySelector("#foto_remove_" + inputid).value = 1;
        document.querySelector('#' + inputid).value = ""; //limpia el valor de la variable foto 
        document.querySelector('.delPhoto').classList.add("notBlock"); //oculta el div con la clase delPhoto que es la X eliminar foto
        if (document.querySelector('#img' + inputid)) {
          document.querySelector('#img' + inputid).remove();
        }
      };
    }
    ;
  }));
}
/* ACTUALIZAR --------------------------------------------------------------------------------------*/
function formConfiguracion() {
  let formConfiguracion = document.querySelector("#formConfiguracion"); // LA VARIABLE GUARDA LA UBICACION DEL FORMULARIO 
  formConfiguracion.onsubmit = function (e) { //SE AGREGA LA ACCION ONSUBMIT Y ELECUTA LA FUNCION DESCRITA 
    e.preventDefault(); //EVITA QUE LA PAGINA SE RECARGE
    // divLoading.style.display = "flex"; // muestra una imagen para la espera de la carga del formulario
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + 'configuracion/setConfiguracion';
    let formData = new FormData(formConfiguracion);
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
//      divLoading.style.display = "none";// oculta la imagen de la espera de la carga del del formulario 
      if (request.readyState === 4 && request.status === 200) {
        let objData = JSON.parse(request.responseText);
        if (objData.status) {
          //libreria alerta 
          Swal.fire({
            icon: 'success',
            title: 'Configuracion General',
            text: objData.msg,
            timer: 1500,
            timerProgressBar: true,
            showConfirmButton: false
          }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
              location.reload();
            }
          });
        } else {
          Swal.fire("Error", objData.msg, "error");
        }
        // divLoading.style.display = "none";// oculta la imagen de la espera de la carga del del formulario 
      }
    };
  };
}


/* TIPOS DE PAGO ====================================================================================*/
function fntCheckTP(idCheck) {
  let obj = '#' + idCheck;
  console.log(obj);
  let estado = document.querySelector(obj).value;
  document.querySelector('#' + idCheck).value = estado === 0 ? 1 : 0;
}
//ACTUALIZAR
function formTiposDePago() {
  let formTiposDePago = document.querySelector("#formTiposDePago"); // LA VARIABLE GUARDA LA UBICACION DEL FORMULARIO 

  formTiposDePago.onsubmit = function (e) { //SE AGREGA LA ACCION ONSUBMIT Y ELECUTA LA FUNCION DESCRITA 
    e.preventDefault(); //EVITA QUE LA PAGINA SE RECARGE

    //let check = document.querySelector("#ceCheck").value;

//    divLoading.style.display = "flex"; // muestra una imagen para la espera de la carga del formulario
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + 'configuracion/setTiposDePago';
    let formData = new FormData(formTiposDePago);

    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
//      divLoading.style.display = "none";// oculta la imagen de la espera de la carga del del formulario 

      if (request.readyState === 4 && request.status === 200) {

        let objData = JSON.parse(request.responseText);
        if (objData.status) {

          //libreria alerta 
          Swal.fire({title: "Configuracion General",
            text: objData.msg,
            type: "success",
            showCancelButton: false,
            showConfirmButton: true,
            confirmButtonText: "OK",
            //cancelButtonText: "No, Cancelar",
            closeOnConfirm: false,
            coseOnCancel: false
          }, function (isConfirm) { //si se hace click en isConfirm (elemento de Swal.fire), se ejecuta la funcion 

            if (isConfirm) {
              location.reload();
            }
          });

        } else {
          Swal.fire("Error", objData.msg, "error");
        }
        // divLoading.style.display = "none";// oculta la imagen de la espera de la carga del del formulario 
      }
    };
  };
}

/*CONFIGURACION REGIONAL ======================================================*/
tableRegiones = $('#tableRegiones').DataTable({
  "destroy": true, //destuye la tabla existente, 
  "stateSave": true, //  guardar estado: restaurar el estado de la tabla en la recarga de la página
  "responsive": true, //tabla responsive, adaptablle a la ventana 
  "search": true,
  "searching": true, //activa - desactiva el cuadro de busqueda 
  "iDisplayLength": 25, //registros iniciales mostrados
  "order": [], //[[4, "DESC"]], // "ordering": true, "order": [[0, "desc"]], //ordenar y ordenar por 
  dom: '<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4"<"dt-buttons btn-group flex-wrap"B>><"col-sm-12 col-md-4"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
  "language": {//url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "Coño Panita, no tengo nada que mostrarte",
    "sEmptyTable": "La tabla esta vacia, cargale datos ",
    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Espera... Ten Paciencia... Falta poco...",
    "oPaginate": {
      "sFirst": "Primero",
      "sLast": "Último",
      "sNext": "Siguiente",
      "sPrevious": "Anterior"
    },
    "oAria": {
      "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
      "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    },
    "buttons": {
      "copy": "Copiar",
      "colvis": "Visibilidad"
    }

  },
  "ajax": {url: base_url + "configuracion/getRegionesList", dataSrc: ""},
  "columns": [
    {"data": "idregion"}, //0
    {"data": "region"}, //1
    {"data": "idioma"}, //2
    {"data": "moneda"}, //3
    {"data": "zona_horaria"}, //4
    {"data": "options"}, //4

  ],
  "columnDefs": [
    {"targets": [0], 'className': "text-rigth align-middle", orderable: false, searchable: false, visible: true},
    {"targets": [1], 'className': "text-rigth align-middle", orderable: false, searchable: true},
    {"targets": [2], 'className': "text-rigth align-middle", orderable: false, searchable: false, visible: true},
    {"targets": [3], 'className': "text-center align-middle", orderable: false},
    {"targets": [4], 'className': "text-center align-middle", orderable: false},
    {"targets": [5], 'className': "text-center align-middle", orderable: false, searchable: false}

  ],
  buttons: [
    {
      extend: 'csv',
      text: 'Exportar a CSV',
      ttleAtter: 'Exportar a CSV',
      className: 'btn btn-warning',
      exportOptions: {modifier: {search: 'none'}}
    }

  ]
});

function nvaRegion() {
//Nombre del Formulario Modal

  document.querySelector('#idRegion').value = ""; //limpiamos el imput para que no incluya nungun valor despues de actualizar algun registro
  document.querySelector('.modal-title').innerHTML = "Nueva Region";
  document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
  document.querySelector('.btnDuplicar').classList.add("notBlock"); //Mostramos el boton de agrear fotos  

  document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
  document.querySelector('#btnText').innerHTML = "Guardar";

//Oculta el paginado
  document.querySelector('#pagina_prev').innerHTML = "";
  document.querySelector('#pagina_poss').innerHTML = "";
  document.querySelector('#pagina_prox').innerHTML = "";
  formRegion();

}
function fntEdit(id) {
  //INSERTAMOS DATOS EN EL MODAL 

  fetch(base_url + 'configuracion/getRegion/' + id)
          .then(objData => objData.json()).then(objData => {
    if (objData.status) {
      //CAMBIAMOS ATRIBUTOS DEL MODAL
      document.querySelector("#formRegion").reset(); // reseteamos los campos del formulario
      document.querySelector('.modal-title').innerHTML = "Editar Region";
      document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
      document.querySelector(".btnDuplicar").classList.remove("notBlock");
      document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
      document.querySelector('#btnText').innerHTML = "Guardar";

      let region = objData.data;
      document.querySelector('#idRegion').value = region.idregion;
      document.querySelector('#txtNombre').value = region.region;
      document.querySelector('#txtAbrev').value = region.region_abrev;
      document.querySelector('#txtIdioma').value = region.idioma;
      document.querySelector('#txtTimeZone').value = region.timezone;
      document.querySelector('#txtMoneda').value = region.moneda;
      document.querySelector('#txtFormatoMoneda').value = region.moneda_formato;
      document.querySelector('#txtSimboloMoneda').value = region.moneda_simbolo;
      document.querySelector('#txtSPM').value = region.moneda_separador_miles;
      document.querySelector('#txtSPD').value = region.moneda_separador_decimales;
      document.querySelector('#txtUTC').value = region.zona_horaria;
      document.querySelector('#txtFormatoFecha').value = region.fecha_formato;

      //paginador
      document.querySelector('#pagina_prev').innerHTML = region.prev === 0 ? '' : '<button class="page-item page-link  pull-left" onClick="fntEdit(' + region.prev + ')" style="margin: auto;"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>';
      document.querySelector('#pagina_prox').innerHTML = region.prox === 0 ? '' : '<button class="page-item page-link pull-right" onClick="fntEdit(' + region.prox + ')" style="margin: auto;"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>';
      document.querySelector('#pagina_poss').innerHTML = '<span class="text-primary" >' + region.posicion + '</span>';
      formRegion();

    } else {
      Swal.fire("Error", objData.msg, "error");
    }
  }).catch((err) => console.error(err));

}

function formRegion() {
  $('#modalFormRegion').modal('show');
  //NUEVO PRODUCTO - EDITAR PRODUCTO
  let formRegion = document.querySelector("#formRegion"); // LA VARIABLE GUARDA LA UBICACION DEL FORMULARIO 
  formRegion.onsubmit = function (e) { //SE AGREGA LA ACCION ONSUBMIT Y ELECUTA LA FUNCION DESCRITA 
    e.preventDefault(); //EVITA QUE LA PAGINA SE RECARGE

    let formData = new FormData(formRegion);
    //  divLoading.style.display = "flex";
    fetch(base_url + 'productos/setRegion', {method: "POST", body: formData})
            .then(objData => objData.json()).then(objData => {
      // divLoading.style.display = "none";
      if (objData.status) {
        Swal.fire("Regiones", objData.msg, "success"); // ejecuta un mensaje 
        fntEdit(objData.idregion);
        tableRegiones.ajax.reload(null, false); // RECARGA EL DATA TABLE DESPUES DE EJECUTAR EL SWAL
      } else {
        Swal.fire("Error", objData.msg, "error");
      }
    });
  };
}

function fntVer(id) {
  let idProd = id;
  fetch(base_url + 'configuracion/getRegion/' + id)
          .then(objData => objData.json()).then(objData => {
    if (objData.status) {
      let objProducto = objData.data;

      document.querySelector('#celCodigo').innerHTML = objProducto.codigo;
      document.querySelector('#celNombre').innerHTML = objProducto.nombre;
      document.querySelector('#celPrecio').innerHTML = parseFloat(objProducto.precio * dolarHoy).toFixed(2);
      document.querySelector('#celStock').innerHTML = objProducto.stock;
      document.querySelector('#celCategoria').innerHTML = objProducto.categoria;
      //let status = objProducto.status === 1 ? '<span class="bagde badge-success">Activo</option>' : '<span class="badge badge-danger">Inactivo</option>'; //muestra el espan segun el estado 
      document.querySelector('#celProveedor').innerHTML = objProducto.proveedor;
      document.querySelector('#celDescripcion').innerHTML = objProducto.descripcion;
      let urlProd = base_url + 'tienda/producto/' + objProducto.ruta;
      document.querySelector('#opcionesCompartir2').innerHTML = `
          <a class="btn btn-outline-primary m-1" href="#" onclick="window.open('http://www.facebook.com/sharer/sharer.php?display=popup&u=${urlProd}', 'facebook-share-dialog', 'toolbar=0, status=0, width = 400, height = 550'); return false;" data-tooltip="Facebook"><i class="fab fa-facebook"></i></a>
          <a class="btn btn-outline-primary m-1" href="#" onclick="window.open('https://twitter.com/intent/tweet?text=${objProducto.nombre}%0A&url=${urlProd}','twitter-share-dialog', 'toolbar=0, status=0,width=650,height=400,modal=yes');return false;"   data-tooltip="Twitter"><i class="fab fa-twitter"></i></a> 
          <a class="btn btn-outline-primary m-1" href="#" onclick="window.open('https://wa.me/?url=${objProducto.nombre}&text=%0A${urlProd}','WhatsApp-share-dialog', 'toolbar=0, status=0,width = 800,height = 1000,modal=yes');return false;"  data-tooltip="WhatsApp"><i class="fab fa-whatsapp" aria-hidden="true"></i></a> 
          <a class="btn btn-outline-primary m-1" href="#" onclick="window.open('https://t.me/share/url?url=${objProducto.nombre}%0A${urlProd}&text=${objProducto.nombre}&to=','telegram-share-dialog', 'toolbar=0, status=0,width = 500,height = 500,modal=yes');return false;" data-tooltip="Telegram"><i class="fab fa-telegram" aria-hidden="true"></i></a>`;
      document.querySelector('#pagina_prev2').innerHTML = objProducto.prev === 0 ? '' : '<button class="page-item page-link  pull-left" onClick="fntVer(' + objProducto.prev + ')" style="margin: auto;"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>';
      document.querySelector('#pagina_prox2').innerHTML = objProducto.prox === 0 ? '' : '<button class="page-item page-link pull-right" onClick="fntVer(' + objProducto.prox + ')" style="margin: auto;"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>';
      document.querySelector('#pagina_poss2').innerHTML = '<span class="text-primary" >' + objProducto.posicion + '</span>';
      let htmlImage = "";
      if (objProducto.images.length > 0) {
        let objProImages = objProducto.images;
        for (let p = 0; p < objProImages.length; p++) {
          htmlImage += `<div class="prod-pic-rel">
                           <a href="${objProImages[p].url_image}" class="prod-scale-img thumb preview-thumb image-popup">
                              <img src="${objProImages[p].url_thumb}" class="img-fluid " alt="work-thumbnail"> 
                           </a>
                        </div>
                  `;
        }
      }
      document.querySelector("#celFotos").innerHTML = htmlImage;
      ligthbbox_reload();
      $('#modalVerProducto').modal('show');
    } else {
      Swal.fire("Error", objData.msg, "error");
    }
  });
}