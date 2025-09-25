"use strict";
let tableProveedores;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
//al momento de cargar el documento ejecutara esta funcion y en esta funcion tendremos todo el script de datatable
document.addEventListener('DOMContentLoaded', function () {
  tablaProveedores();

});


function tablaProveedores() {
  tableProveedores = $('#tableProveedores').DataTable({
    "destroy": true, //destuye la tabla existente, 
    "stateSave": true, //  guardar estado: restaurar el estado de la tabla en la recarga de la página
    "responsive": true, //tabla responsive, adaptablle a la ventana 
    // "ServerSide": false,//habilita le procesamiento de datos del lado del servidor (util para procesamiento de mas de 50.000 registros)
    "search": true,
    "searching": true, //activa - desactiva el cuadro de busqueda 
    // "bProcessing": true,
    // "info": true,
    // "paging": true, //paginado de la tabla
    "iDisplayLength": 25, //registros iniciales mostrados
    //"order": [[4, "DESC"]], // "ordering": true, "order": [[0, "desc"]], //ordenar y ordenar por 
    dom: '<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4"<"dt-buttons btn-group flex-wrap"B>><"col-sm-12 col-md-4"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    "language": {//url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "sSearch": "Buscar:",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
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
    "ajax": {url: base_url + "productos/getProveedores", dataSrc: ""}, //urlTablaFiltrada()
    "columns": [
      {"data": "idproveedor"}, //0
      {"data": "img"}, //1
      {"data": "nombre"}, //2
      {"data": "descripcion"}, //3
      {"data": "options"} //4

    ],
    "columnDefs": [
      {"targets": [0], 'className': "text-rigth align-middle", "visible": false, "searchable": false, orderable: false},
      {"targets": [1], 'className': "text-rigth align-middle", "searchable": false, orderable: false},
      {"targets": [2], 'className': "text-rigth align-middle"}, //, "visible": false
      {"targets": [3], 'className': "text-center align-middle"},
      {"targets": [4], 'className': "text-center align-middle", "searchable": false}

    ],
    buttons: [
      {
        extend: 'excel',
        text: 'excel',
        ttleAtter: 'Exportar a Excel',
        className: 'btn btn-danger excelButton',
        exportOptions: {
          modifier: {
            search: 'none'
          }
        }
      },
      {
        extend: 'pdf',
        text: 'PDF',
        ttleAtter: 'Exportar a PDF',
        className: 'btn btn-primary',
        exportOptions: {
          modifier: {
            search: 'none'
          }
        }
      },
      {
        extend: 'csv',
        text: 'Exportar a CSV',
        ttleAtter: 'Exportar a CSV',
        className: 'btn btn-warning',
        exportOptions: {
          modifier: {
            search: 'none'
          }
        }
      }
    ]
  });
}

function removePhoto() {
  if (document.querySelector('#img')) {
    document.querySelector('#img').remove();
  }
  document.querySelector('#foto').value = ""; //limpia el valor de la letiable foto 
  document.querySelector('.delPhoto').classList.add("notBlock"); //oculta el div con la clase delPhoto que es la X eliminar foto
  document.querySelector('#foto_blob_type').value = '';
  document.querySelector('#foto_blob_name').value = '';
}
function alertFoto(mensaje) {
  document.querySelector('#form_alert').innerHTML = '<p class="errorArchivo">' + mensaje + '.</p>'; //se inserta mensaje de archivo invalido
  if (document.querySelector('#img')) { //si se cargo el archivo en id img
    document.querySelector('#img').remove(); // se remueve    
  }

  document.querySelector('.delPhoto').classList.add("notBlock"); // cambia el estilo .delPhoto por notBlock
  foto.value = ""; // el valor vuelve a vacio
  return false; // se retorna un false, para que no continue avanazando la ejecucion del script
}
function dataURLtoFile(dataurl, filename) {
  let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
          bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }
  return new File([u8arr], filename, {type: mime});
}

function b64toBlob(b64Data, contentType, sliceSize) {
  /**
   * Convierte una cadena de base64 en un Blob de acuerdo al tipo de datos que se envie y su contentType.
   * 
   * @param b64Data {String} La cadena de texto en base64 sin su incluido
   * @param contentType {String} el tipo de contenido p. ej (image/jpeg - image/png - text/plain)
   * @param sliceSize {Int} Tamaño de las porcioens a procesar el número de caracteres en bytes
   * @see http://stackoverflow.com/questions/16245767/creating-a-blob-from-a-base64-string-in-javascript
   * @return Blob
   */
  contentType = contentType || '';
  sliceSize = sliceSize || 512;
  let byteCharacters = atob(b64Data);
  let byteArrays = [];
  for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
    let slice = byteCharacters.slice(offset, offset + sliceSize);
    let byteNumbers = new Array(slice.length);
    for (let i = 0; i < slice.length; i++) {
      byteNumbers[i] = slice.charCodeAt(i);
    }
    let byteArray = new Uint8Array(byteNumbers);
    byteArrays.push(byteArray);
  }

  return new Blob(byteArrays, {type: contentType});
}

/* letiables para el recorte de iamgen y el envio por ajax-------------------------------------------*/
let URL = window.URL || window.webkitURL;
let file = document.getElementById("foto"); //file es input de selección, donde se garga el archivo
let image = document.getElementById("img-original"); //es elemento img dentro del modal donde se montará la imagen seleccionada (para ser cortada)
let btncrop = document.getElementById("btn-crop");
let cropper = null; //el objeto cropper que habrá que Crearlo al mostrar el modal y destruirlo al cerrarlo
let img_type = '';
let img_name = '';
let ancho = 0;
let alto = 0;
let reader;
// escuchamos cambio en el elemento de lsita Mostrar en'


$('#div-modal-recorte').on('shown.bs.modal', function () { //al mostrar el modal ejecutan las siquientes funciones  
//crea el marco de selección sobre el objeto image
//let mostrar = document.getElementById("listPublicarCat").value;
  let ancho_cropp = 300 * 0.30;
  let alto_cropp = 300 * 0.30;
  let relacionAspecto = 1 / 1;
  cropper = new Cropper(image, {
    minCropBoxWidth: ancho_cropp,
    minCropBoxHeight: alto_cropp,
    zoomOnWheel: false,
    zoomable: false,
    zoomOnTouch: false,
    preview: "#div-preview", //donde se mostrará la parte seleccionada
    viewMode: 1, //3: indica que no se podrá seleccionar fuera de los límites
    aspectRatio: relacionAspecto //NaN libre elección, 1 cuadrado, proporción del lado horizontal con respecto al vertical
  });
  /*modal.on-shown*/
}).on('hidden.bs.modal', function () {
  cropper.destroy();
  image.src = '';
  cropper = null;
})//modal.on-hidden   


file.addEventListener("change", function (e) {//escuchamos el change del input-file
  let files = e.target.files;
  if (files && files.length > 0) {
    img_type = files[0]['type']; // Tipo del archivo 
    img_name = files[0]['name'];
    if (img_type !== 'image/jpeg' && img_type !== 'image/jpg' && img_type !== 'image/png' && img_type !== 'image/webp') { //se valida que los tipos de imagen sean png, jpg, jpeg,   si no lo son 
      alertFoto('El archivo debe de ser tipo .jpg/jpeg .png .webp');
    }
    let objfile = files[0];
    //el objeto file tiene las propiedades: name, size, type, lastmodified, lastmodifiedate
    //para poder visualizar el archivo de imagen lo debemos pasar a una url        
    if (URL) { //el objeto URL está en fase experimental así que si no existe usaria FileReader, crea una url del estilo: blob:http://localhost:1024/129e832d-2545-471f-8e70-20355d8e33eb
      image.src = URL.createObjectURL(objfile);
    } else if (FileReader) {
      reader = new FileReader();
      reader.onload = function (e) {
        image.src = reader.result;
      };
      reader.readAsDataURL(objfile);
    }
    image.onload = function () { //una vez cargada la imagen validamos el alto y ancho , 

      if (image.width < ancho && alto < image.height) {
        alertFoto('El tamaño minimo es de 720x460');
      } else {
        document.querySelector('#form_alert').innerHTML = ''; // el aviso de archivo erroneo pasa a vacio
        $('#div-modal-recorte').modal('show'); //mostramos el modal 
      }
    }
  }
})//file.on-change

btncrop.addEventListener("click", function () {//configuramos el click del boton crop
//    let canvas = cropper.getCroppedCanvas();
  let imgrecorte = cropper.getCroppedCanvas().toDataURL(img_type); //obtenemos la zona seleccionada
  let myImage = new Image(); // y creamos un nuevo objeto imagen
  myImage.src = imgrecorte;
  imgrecorte = '';
  document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src=" + myImage.src + ">"; // actualiza la imagen vista previa del formulario
  document.querySelector('#foto_blob_type').value = img_type; //almacena en json en un pimputo tipo hide para enviarlo enel formulario
  document.querySelector('#foto_blob_name').value = img_name; //almacena en json en un pimputo tipo hide para enviarlo enel formulario
  document.querySelector('.delPhoto').classList.remove("notBlock"); //se dirige a este elemento y remueve la clase notBlock, lo que mostraria la X de eliminar imagen
  file.value = ""; //resetea el elemento input-file (file-upload)
  $('#div-modal-recorte').modal('hide'); //escondo el modal
  document.querySelector("#foto_remove").value = '';
  image.src = '';
  reader = null;
})//btncrop.on-click


if (document.querySelector(".delPhoto")) {
  let delPhoto = document.querySelector(".delPhoto");
  delPhoto.onclick = function (e) {
    document.querySelector("#foto_remove").value = 1;
    document.querySelector('.prevPhoto div').innerHTML = "";
    let img_type = '';
    let img_name = '';
    removePhoto();
  };
}

function formProveedor() {
//NUEVO - ACTUALIZAR
  let formProveedor = document.querySelector("#formProveedor"); // LA VARIABLE GUARDA LA UBICACION DEL FORMULARIO 
  formProveedor.onsubmit = function (e) { //SE AGREGA LA ACCION ONSUBMIT Y ELECUTA LA FUNCION DESCRITA 
    e.preventDefault(); //EVITA QUE LA PAGINA SE RECARGE

    let intIdProveedor = document.querySelector("#idProveedor").value; //identificara los datos del formulario pertnecen a nuevo o a actualizar
    let strNombre = document.querySelector("#txtNombre").value;
    let strDescripcion = document.querySelector("#txtDescripcion").value;
    if (intIdProveedor > 0) { //si el id rol es 0 significa que es un nuevo rol
      let intStatus = document.querySelector("#listStatus").value;
    } else {
      let intStatus = document.querySelector("#listStatus").value = 1;
    }
    if (strNombre === '' || strDescripcion === '') {
      Swal.fire("atencion", "todos los campos son obligatorios.", "error");
      //return false;
    }

    //divLoading.style.display = "flex"; // muestra una imagen para la espera de la carga del formulario

    let ajaxUrl = base_url + 'productos/setProveedor';
    let formData = new FormData(formProveedor);
    let nombrefoto = document.getElementById("foto_blob_name").value;
    if (nombrefoto != '') {

      let ImageURL = document.getElementById("img").src;
//            let block = ImageURL.split(";");
//            let contentType = block[0].split(":")[1]; // Obtener tipo de contenido, en este caso "image/gif"
//            let realData = block[1].split(",")[1]; // Obtener información real en base64, en este caso "iVBORw0KGg...."
//            let blob = b64toBlob(realData, contentType); // Convertir a blob
      let blob = dataURLtoFile(ImageURL, nombrefoto); // Convertir a blob
      formData.append('foto', blob);
    }
    $.ajax({
      url: ajaxUrl,
      // Añade como información la instancia de FormData anteriormente creada y modificada
      data: formData,
      type: "POST",
      contentType: false,
      processData: false,
      cache: false,
      // Cambia el datatype de acuerdo al tipo de información que recibes de tu servidor
      dataType: "json",
      error: function (err) {
        console.error(err);
      },
      success: function (data) {
        console.log("Solciitud finalizada.");
        let objData = data;
        if (objData.status) {
          $('#modalFormProveedor').modal("hide");
          Swal.fire("Proveedor de Productos", objData.msg, "success"); //libreria alerta 
          tableProveedores.ajax.reload(null, false); //RECARGA EL DATA TABLE DESPUES DE EJECUTAR EL SWAL
          removePhoto();
          formProveedor.reset();
        } else {
          Swal.fire("Error", objData.msg, "error");
        }
      },
      complete: function (data) {
        //console.log(data);

      }
    });
  };
}
/**************************************************************/
function nvoProveedor() {
//Nombre del Formulario Modal
  document.querySelector('#idProveedor').value = ""; //limpiamos el imput para que no incluya nungun valor despues de actualizar algun registro
  document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
  document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
  document.querySelector('#titleModal').innerHTML = "Nueva Proveedor";
  document.querySelector('#btnText').innerHTML = "Guardar";
  document.querySelector('#formProveedor').reset(); // reseteamos los campos del formulario

  document.querySelector("#foto_remove").value = ""; //punto de cambio para actualizacion imagen de categoria

  //ocultamos el Status ya que tendra un valor de 1 = habilitado
  document.querySelector('#listStatusLabel').hidden = true;
  document.querySelector('#listStatus').hidden = true;
  // document.querySelector('#formProveedor').reset(); // reseteamos los campos del formulario
  document.querySelector('#pagina_prev').innerHTML = '';
  document.querySelector('#pagina_prox').innerHTML = '';
  document.querySelector('#pagina_poss').innerHTML = '';
  $('#modalFormProveedor').modal('show');
  removePhoto();
//    fotoProveedor();
  //modalRecortefoto();
  formProveedor();
}
/**************************************************************/
function fntVer(id) {

  fetch(base_url + 'productos/getProveedor/' + id)
          .then(res => res.json()).then(objData => {
    if (objData.status) {
      let estadoProveedor = objData.data.status === 1 ? //muestra el espan segun el estado 
              '<span class="bagde badge-success">Activo</option>' :
              '<span class="badge badge-danger">Inactivo</option>';
      document.querySelector('#verID').innerHTML = objData.data.idproveedor;
      document.querySelector('#verNombre').innerHTML = objData.data.nombre;
      document.querySelector('#verDescripcion').innerHTML = objData.data.descripcion;
      document.querySelector('#verDireccion').innerHTML = objData.data.direccion;
      document.querySelector('#verTelefono').innerHTML = objData.data.telf_local;
      document.querySelector('#verCelular').innerHTML = objData.data.telf_mobil;
      let divCompartir = '';
      divCompartir += objData.data.web !== '' ? `<a class="btn btn-outline-primary m-1" href="#" onclick="window.open('${objData.data.web}', 'web-share-dialog'); return false;" ><i class="fas fa-globe"></i></a>` : '';//'toolbar=0, status=0' data-tooltip="Web"
      divCompartir += objData.data.fb !== '' ? `<a class="btn btn-outline-primary m-1" href="#" onclick="window.open('${objData.data.fb}', 'facebook-share-dialog'); return false;" data-tooltip="Facebook"><i class="fab fa-facebook"></i></a> ` : '';//, 'toolbar=0, status=0'
      divCompartir += objData.data.fb !== '' ? `<a class="btn btn-outline-primary m-1" href="#" onclick="window.open('${objData.data.ig}', 'instagram-share-dialog'); return false;" data-tooltip="Instagram"><i class="fab fa-instagram"></i></a> ` : '';//, 'toolbar=0, status=0'
      //<a class="btn btn-outline-primary m-1" href="#" onclick="window.open('https://twitter.com/intent/tweet?text=${objProducto.nombre}%0A&url=${urlProd}','twitter-share-dialog', 'toolbar=0, status=0,width=650,height=400,modal=yes');return false;"   data-tooltip="Twitter"><i class="fab fa-twitter"></i></a> `:'';
      divCompartir += objData.data.telf_mobil !== '' ? `<a class="btn btn-outline-primary m-1" href="#" onclick="window.open('https://wa.me/${objData.data.telf_mobil}','WhatsApp-share-dialog');return false;"  data-tooltip="WhatsApp"><i class="fab fa-whatsapp" aria-hidden="true"></i></a> ` : '';//, 'toolbar=0, status=0,width = 800,height = 1000,modal=yes'
      divCompartir += objData.data.telf_mobil !== '' ? `<a class="btn btn-outline-primary m-1" href="#" onclick="window.open('https://t.me/${objData.data.telf_mobil}','telegram-share-dialog');return false;" data-tooltip="Telegram"><i class="fab fa-telegram" aria-hidden="true"></i></a>` : '';//, 'toolbar=0, status=0,width = 500,height = 500,modal=yes'
      document.querySelector('#verLinks').innerHTML = divCompartir;
      document.querySelector('#verFecha').innerHTML = objData.data.fecha;
      document.querySelector('#verEstado').innerHTML = estadoProveedor;
      document.querySelector('#verImgProveedor').innerHTML = '<img src = "' + objData.data.url_img + '">';

      objData.data.prev === 0 ? document.querySelector('#pagina_prev').innerHTML = '' : document.querySelector('#pagina_prev').innerHTML = '<button class="page-item page-link pull-left" onClick="fntEdit(' + objData.data.prev + ')" >«</button>';
      objData.data.prox === 0 ? document.querySelector('#pagina_prox').innerHTML = '' : document.querySelector('#pagina_prox').innerHTML = '<button class="page-item page-link pull-right" onClick="fntEdit(' + objData.data.prox + ')" >»</button>';
      document.querySelector('#pagina_poss').innerHTML = '<span class="text-primary" >' + objData.data.posicion + '</span>';

      $('#modalVerProveedor').modal('show');
    } else {
      Swal.fire("Error", objData.msg, "error");
    }
  }).catch(err => console.error(err));
}
/**************************************************************/
function fntEdit(id) {
//CAMBIAMOS ATRIBUTOS DEL MODAL
//con document document.querySelector nos referimos al elemento con el id o la clase que pasamos como parametro
//innerHTML le indicamos que reemplace el texto existente por el siguiente
//con classList.replace indicamos que reemplace la clase de estilos A por clase de estilos B
  document.querySelector('#titleModal').innerHTML = "Actualizar Proveedor";
  document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
  document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
  document.querySelector('#btnText').innerHTML = "Actualizar";
  document.querySelector("#foto_remove").value = ""; //punto de cambio para actualizacion imagen de categoria


  //CONSULTA DATOS AL SERVER
  fetch(base_url + 'productos/getProveedor/' + id)
          .then(res => res.json())
          .then(objData => {

            if (objData.status) { //INSERTA DATOS EN EL MODAL 
              document.querySelector('#idProveedor').value = objData.data.idproveedor;
              document.querySelector('#txtNombre').value = objData.data.nombre;
              document.querySelector('#txtDescripcion').value = objData.data.descripcion;
              document.querySelector('#txtDireccion').value = objData.data.direccion;
              document.querySelector('#txtWeb').value = objData.data.web;
              document.querySelector('#txtFacebook').value = objData.data.fb;
              document.querySelector('#txtInstagram').value = objData.data.ig;
              document.querySelector('#txtTelefono').value = objData.data.telf_local;
              document.querySelector("#txtMobil").value = objData.data.telf_mobil;
              document.querySelector('#listStatus').value = objData.data.status;

              // agregamos la imagen 
              document.querySelector("#foto_actual").value = objData.data.img;
              document.querySelector('#img') ?
                      document.querySelector('#img').src = objData.data.url_img :
                      document.querySelector('.prevPhoto div').innerHTML = "<img id= 'img' src = '" + objData.data.url_img + "'>";
              //mostramos la X de eliminar si la foto es distinta de la foto base
              objData.data.img === 'categorias.png' ?
                      document.querySelector('.delPhoto').classList.add("notBlock") :
                      document.querySelector('.delPhoto').classList.remove("notBlock");


              objData.data.prev === 0 ? document.querySelector('#pagina_prev').innerHTML = '' : document.querySelector('#pagina_prev').innerHTML = '<button class="page-item page-link pull-left" onClick="fntEdit(' + objData.data.prev + ')" >«</button>';
              objData.data.prox === 0 ? document.querySelector('#pagina_prox').innerHTML = '' : document.querySelector('#pagina_prox').innerHTML = '<button class="page-item page-link pull-right" onClick="fntEdit(' + objData.data.prox + ')" >»</button>';
              document.querySelector('#pagina_poss').innerHTML = '<span class="text-primary" >' + objData.data.posicion + '</span>';
              $('#modalFormProveedor').modal('show');
              formProveedor();
            } else {
              Swal.fire("Error", objData.msg, "error");
            }
          }).catch();


}
/**************************************************************/
function fntDel(id) {
  // this.getAttribute('rl')this hace referencia al elemento al que le damos click, getAttribute trae el dato del elemento ('rl')
  Swal.fire({
    title: "Quieres Eliminar Proveedor",
    text: "esta accion es irreversible",
    icon: "warning",
    showCancelButton: true,
    showConfirmButton: true,
    // confirmButtonText: "Si, Eliminar",
    cancelButtonText: "No, Cancelar",
    closeOnConfirm: false,
    coseOnCancel: true
  }).then((result) => {
    if (result.isConfirmed) {//si se hace click en isConfirm (elemento de Swal.fire), se ejecuta la funcion
      //document.querySelector('#idProducto').value = objData.data.idproducto;
      let ajaxUrlDel = base_url + 'productos/delProveedor';
      let arrData = "id=" + id;
      let cabecera = {"Content-type": "application/x-www-form-urlencoded"};
      fetch(ajaxUrlDel, {method: "POST", headers: cabecera, body: arrData})
              .then(objData => objData.json()).then(objData => {
        if (objData.status) {
          Swal.fire("HA SIDO UN EXITO", objData.msg, "success");
          tableProveedores.ajax.reload(null, false); // refrescamos la tabla
        } else {
          Swal.fire("Atencio!", objData.msg, "error");
        }
      }).catch(err => console.log(err));
    }
  });
}

/**************************************************************/
function fntStatus(id) {
  let boton = document.querySelector("#btnStatus" + id);
  fetch(base_url + 'productos/statusProveedorChange/' + '?id=' + id + '&intStatus=' + boton.value)
          .then(response => response.json()).then(objData => {
    if (objData.status) {
      if (boton.value == 1) {
        boton.value = 0, boton.classList.replace('btn-success', 'btn-danger');
      } else {
        boton.value = 1, boton.classList.replace('btn-danger', 'btn-success');
      }
    } else {
      Swal.fire("Atencio!", objData.msg, "error");
    }
  }).catch(err => Swal.fire("Atencio!", err, "error"));

  //pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario
}
/**************************************************************/

function fntDuplicar(){
  
}