let tableBanners;
let rowTable = "";

document.addEventListener('DOMContentLoaded', function () {
  tablaBanners();

});

// selectpicker selector de Item
function selectUrlItem(tpo = null, iditem = null) {
  let tipo = tpo === null ? document.querySelector('#listTpo').value : tpo;
  let item = iditem === null ? document.querySelector('#listItem').value : iditem;
  fetch(base_url + 'homebanner/getUrlItem/' + tipo + '/' + item).
          then(objData => objData.json())
          .then(objData => {
            if (objData.status) {
              document.querySelector('#txtUrl').innerHTML = objData.url;
              document.querySelector('#txtUrl').href = base_url + objData.url;
            }
          });
}

// selectpicker selector de Item
function selectItem(id = null) {
  let idItem = id === null ? '' : '/' + id;
  let tipo = document.querySelector('#listTpo').value;
  fetch(base_url + 'homebanner/getItemPorTipo/' + tipo + idItem)
          .then(objData => objData.text())
          .then(objData => {//pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario
            document.querySelector('#listItem').innerHTML = objData;

          });
}


function tablaBanners() {
  tableBanners = $('#tableBanners').DataTable({

//$(document).ready(function() {
//$('#tableBanners').DataTable({
    "destroy": true,
    "aServerSide": true,
    "responsive": true,
    "bProcessing": true,
    "iDisplayLength": 25,
    "order": [[0, "asc"]],
    "language": {url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
    },
    "ajax": {url: base_url + "homebanner/getBanners", dataSrc: ""},
    "columns": [
      {"data": "img"},
      {"data": "nombre"},
      {"data": "tipo"},
      // {"data": "status"},
      {"data": "options", "searchable": false}
    ]
  });
}

function removePhoto() {
  if (document.querySelector('#img')) {
    document.querySelector('#img').remove();
  }
  document.querySelector('#foto').value = ""; //limpia el valor de la variable foto 
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

/*-----------------------------------------------------------------------------------------------------------------------*/
function dataURLtoFile(dataurl, filename) {
  // console.log(dataurl + ' ' + filename);
  var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
          bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }
  return new File([u8arr], filename, {type: mime});
}
/*-----------------------------------------------------------------------------------------------------------------------*/
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
  let blob = new Blob(byteArrays, {type: contentType});
  return blob;
}

/* variables para el recorte de iamgen y el envio por ajax-------------------------------------------*/
let URL = window.URL || window.webkitURL;
let file = document.getElementById("foto"); //file es input de selección, donde se garga el archivo
let image = document.getElementById("img-original"); //es elemento img dentro del modal donde se montará la imagen seleccionada (para ser cortada)
let btncrop = document.getElementById("btn-crop");
let cropper = null; //el objeto cropper que habrá que Crearlo al mostrar el modal y destruirlo al cerrarlo
let img_type = '';
let img_name = '';
let ancho = 1920 * 0.60;
let alto = 930 * 0.60;
https://share.vidyard.com/watch/VyNdhLqgeqjRvUReT2sqnA?
        document.getElementById('prevPhotoLabel').innerHTML = '(1920 x 930 o -30%) ';

$('#div-modal-recorte').on('shown.bs.modal', function () { //al mostrar el modal ejecutan las siquientes funciones 
//crea el marco de selección sobre el objeto image
  //let mostrar = 'sl'; //document.getElementById("listPublicarCat").value;
  let ancho_cropp = 0;
  let alto_cropp = 0;
  let relacionAspecto = 2.06 / 1;
// if (mostrar === 'sl') {
  ancho_cropp = 1920 * 0.10;
  alto_cropp = 930 * 0.10;
  relacionAspecto = 2.06 / 1;
  document.querySelector('.prevPhoto').classList.add(".prevPhoto-Carrusel");
  document.querySelector('.prevPhoto').classList.remove(".prevPhoto-Banner");

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
});//modal.on-hidden 
file.addEventListener("change", function (e) {//escuchamos el change del input-file
  let files = e.target.files;
  if (files && files.length > 0) {
    img_type = files[0]['type']; // Tipo del archivo 
    img_name = files[0]['name'];
    let tpo_img_correcto = img_type !== 'image/jpeg.' ? 1 : img_type !== 'image/jpg.' ? 1 : img_type !== 'image/png.' ? 1 : img_type !== 'image/webp.' ? 1 : 0;
    if (!tpo_img_correcto) {//(img_type ! === 'image/jpeg.' || img_type ! === 'image/jpg.' || img_type ! === 'image/png.' || img_type ! === 'image/webp.') { //se valida que los tipos de imagen sean png, jpg, jpeg, si no lo son 
      alertFoto('El archivo debe de ser tipo .jpg/jpeg .png ,wepb y es: ' + img_type);
    }
    let objfile = files[0];
    //el objeto file tiene las propiedades: name, size, type, lastmodified, lastmodifiedate
    //para poder visualizar el archivo de imagen lo debemos pasar a una url 
    if (URL) { //el objeto URL está en fase experimental así que si no existe usaria FileReader, crea una url del estilo: blob:http://localhost:1024/129e832d-2545-471f-8e70-20355d8e33eb
      image.src = URL.createObjectURL(objfile);
    } else if (FileReader) {
      let reader = new FileReader();
      reader.onload = function (e) {
        image.src = reader.result;
      };
      reader.readAsDataURL(objfile);
    }
    image.onload = function () { //una vez cargada la imagen validamos el alto y ancho , 

      if (image.width < ancho && alto < image.height) {
        alertFoto('El tamaño minimo es de ' + ancho + ' de ancho x ' + alto + ' de alto y es ' + image.width + 'x' + image.height);
      } else {
        document.querySelector('#form_alert').innerHTML = ''; // el aviso de archivo erroneo pasa a vacio
        $('#div-modal-recorte').modal('show'); //mostramos el modal 
      }
    };
  }
});//file.on-change

btncrop.addEventListener("click", function () {//configuramos el click del boton crop
// let canvas = cropper.getCroppedCanvas();
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
  document.querySelector("#foto_remove").value = 1;
  image.src = '';
  reader = null;
});//btncrop.on-click


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

function formBanner() {
//NUEVO - ACTUALIZAR
  let formBanner = document.querySelector("#formBanner"); // LA VARIABLE GUARDA LA UBICACION DEL FORMULARIO 


  formBanner.onsubmit = function (e) { //SE AGREGA LA ACCION ONSUBMIT Y ELECUTA LA FUNCION DESCRITA 
    e.preventDefault(); //EVITA QUE LA PAGINA SE RECARGE

    let intIdBanner = document.querySelector("#idBanner").value; //identificara los datos del formulario pertnecen a nuevo o a actualizar
    let strNombre = document.querySelector("#txtNombre").value;
    let strDescripcion = document.querySelector("#txtDescripcion").value;
    let intStatus = '';

    if (intIdBanner > 0) { //si el id es 0 significa que es un nuevo banner
      intStatus = document.querySelector("#listStatus").value;
    } else {
      intStatus = document.querySelector("#listStatus").value = 1;
    }
    if (strNombre === '' || strDescripcion === '' || intStatus === '') {
      Swal.fire("atencion", "todos los campos son obligatorios.", "error");
      return false;
    }

    //divLoading.style.display = "flex"; // muestra una imagen para la espera de la carga del formulario

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + 'homebanner/setBanner';
    let formData = new FormData(formBanner);
    var nombrefoto = document.getElementById("foto_blob_name").value;
    if (nombrefoto !== '') {//indica que hay una imagen, ya sea recien cargada o no 
      var ImageURL = document.getElementById("img").src;

      var blob = dataURLtoFile(ImageURL, nombrefoto); // Convertir a blob
      formData.append('foto', blob);
    }
    $.ajax({
      url: ajaxUrl, // la URL para la petición
      // Añade como información la instancia de FormData anteriormente creada y modificada
      data: formData, // la información a enviar // (también es posible utilizar una cadena de datos)
      type: 'POST', // especifica si será una petición POST o GET
      contentType: false,
      processData: false,
      cache: false,
      dataType: 'json', // Cambia el datatype de acuerdo al tipo de información que recibes de tu servidor
      error: function (xhr, status, error) { // código a ejecutar si la petición falla; // son pasados como argumentos a la función // el objeto de la petición en crudo y código de estatus de la petición
        Swal.fire("Error", xhr + ' - ' + ' - ' + status + ' - ' + error, "error");
        return false;
      },
      success: function (data) { // código a ejecutar si la petición es satisfactoria; // la respuesta es pasada como argumento a la función
        let objData = data;
        if (objData.status) {
          $('#modalFormBanner').modal("hide");
          Swal.fire("Banner de Productos", objData.msg, "success"); //libreria alerta 
          tableBanners.ajax.reload(null, false); //RECARGA EL DATA TABLE DESPUES DE EJECUTAR EL SWAL
          removePhoto();
          formBanner.reset();
        } else {
          Swal.fire("Error", objData.msg, "error");
        }
      }

    });
  };
}


/**************************************************************/

function nvoBanner() {
//Nombre del Formulario Modal
  document.querySelector('#idBanner').value = ""; //limpiamos el imput para que no incluya nungun valor despues de actualizar algun registro
  document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
  document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
  document.querySelector('#titleModal').innerHTML = "Nueva Banner";
  //document.querySelector('#btnText').innerHTML = "Guardar";
  document.querySelector('#formBanner').reset(); // reseteamos los campos del formulario

  document.querySelector("#foto_remove").value = ""; //punto de cambio para actualizacion imagen de categoria

  //ocultamos el Status ya que tendra un valor de 1 = habilitado
  document.querySelector('#listStatusLabel').hidden = true;
  document.querySelector('#listStatus').hidden = true;
  // document.querySelector('#formBanner').reset(); // reseteamos los campos del formulario

  $('#modalFormBanner').modal('show');
  removePhoto();
// fotoBanner();
  //modalRecortefoto();
  formBanner();
}
/**************************************************************/
function fntVer(id) {
  let idCat = id;
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrlUserId = base_url + 'homebanner/getBanner/' + idCat;
  request.open("GET", ajaxUrlUserId, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {

      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        let estadoBanner = objData.data.status === 1 ? //muestra el espan segun el estado 

                '<span class="bagde badge-success">Activo</option>' :
                '<span class="badge badge-danger">Inactivo</option>';
        document.querySelector('#celID').innerHTML = objData.data.idbanner;
        document.querySelector('#celNombre').innerHTML = objData.data.nombre;
        document.querySelector('#celDescripcion').innerHTML = objData.data.descripcion;
        document.querySelector('#celEstado').innerHTML = estadoBanner;
        document.querySelector('#celImgBanner').innerHTML = '<img src = "' + objData.data.url_img + '">';
        $('#modalVerBanner').modal('show');
      } else {
        Swal.fire("Error", objData.msg, "error");
      }
    }
  };
  /* });
   });*/
}
/**************************************************************/


function fntEdit(id) {
//CAMBIAMOS ATRIBUTOS DEL MODAL

  document.querySelector('#titleModal').innerHTML = "Actualizar Banner";
  document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
  document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
  document.querySelector("#foto_remove").value = ""; //punto de cambio para actualizacion imagen de categoria
  //INSERTAMOS DATOS EN EL MODAL 
  let idCat = id;
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrl = base_url + 'homebanner/getBanner/' + idCat;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      //pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario
      let objData = JSON.parse(request.response);
      if (objData.status) {
        document.querySelector('#idBanner').value = objData.data.idbanner;
        document.querySelector('#txtNombre').value = objData.data.nombre;
        document.querySelector('#txtDescripcion').value = objData.data.descripcion;
        document.querySelector("#foto_actual").value = objData.data.img;


        document.querySelector('#img') ?
                document.querySelector('#img').src = objData.data.url_img :
                document.querySelector('.prevPhoto div').innerHTML = "<img id= 'img' src = '" + objData.data.url_img + "'>";
        objData.data.img === 'categorias.png' ? //mostramos la X de eliminar si la foto es distinta de la foto base
                document.querySelector('.delPhoto').classList.add("notBlock") :
                document.querySelector('.delPhoto').classList.remove("notBlock");

        selectItem(objData.data.itemid);//consulta y actualiza la lista de items , respeta el item seleccuinado
        document.querySelector('#listTpo').value = objData.data.tipo;

        document.querySelector('#listItem').value = objData.data.itemid;
        selectUrlItem(objData.data.tipo, objData.data.itemid);//consulta y actualiza la url del item seleccionado


        document.querySelector('#listStatus').value = objData.data.status;
        //y esta funcion ejecuta modal('show') refiriendose al elemento boostrap con el id = modalformBanner $('#modalformBanner')
        $('#modalFormBanner').modal('show');

        formBanner();
      } else {
        Swal.fire("Error", objData.msg, "error");
      }
    }
  };
}
/**************************************************************/


/**************************************************************/
function fntDel(id) {
  // this.getAttribute('rl')this hace referencia al elemento al que le damos click, getAttribute trae el dato del elemento ('rl')
  Swal.fire({
    title: "Quieres Eliminar Banner",
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
      let ajaxUrlDel = base_url + 'homebanner/delBanner';
      let arrData = "id=" + id;
      let cabecera = {"Content-type": "application/x-www-form-urlencoded"};
      fetch(ajaxUrlDel, {method: "POST", headers: cabecera, body: arrData})
              .then(objData => objData.json()).then(objData => {
        if (objData.status) {
          Swal.fire("HA SIDO UN EXITO", objData.msg, "success");
          tableBanners.ajax.reload(null, false); // refrescamos la tabla
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
  fetch(base_url + 'homebanner/statusBannerChange/' + '?id=' + id + '&intStatus=' + boton.value)
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
