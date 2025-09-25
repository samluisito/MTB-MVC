let tableCategorias;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
//al momento de cargar el documento ejecutara esta funcion y en esta funcion tendremos todo el script de datatable
document.addEventListener('DOMContentLoaded', function () {
  tablaCategorias();
});
function tablaCategorias() {
  tableCategorias = $('#tableCategorias').DataTable({

//$(document).ready(function() {
//$('#tableRoles').DataTable({
    "destroy": true,
    "aServerSide": true,
    "responsive": true,
    "bProcessing": true,
    "iDisplayLength": 25,
    "order": [[0, "asc"]],
    "language": {url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"},
    "ajax": {url: base_url + "categorias/getCategorias", dataSrc: ""},
    "columns": [
      {"data": "idcategoria"},
      {"data": "img"},
      {"data": "nombre"},
      {"data": "descripcion"},
      {"data": "options", "searchable": false}
    ],
    dom: '<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4"<"dt-buttons btn-group flex-wrap"B>><"col-sm-12 col-md-4"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
  });
}


function alertFoto(mensaje) {
  document.querySelector('#form_alert').innerHTML = '<p class="errorArchivo">' + mensaje + '.</p>'; //se inserta mensaje de archivo invalido
  if (document.querySelector('#img')) { //si se cargo el archivo en id img
    document.querySelector('#img').remove(); // se remueve    
  }

  document.querySelector('#delPhoto').classList.add("notBlock"); // cambia el estilo delPhoto por notBlock
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
var URL = window.URL || window.webkitURL;
var image = document.getElementById("img-original"); //es elemento img dentro del modal donde se montará la imagen seleccionada (para ser cortada)
var btncrop = document.getElementById("btn-crop");
var cropper = null; //el objeto cropper que habrá que Crearlo al mostrar el modal y destruirlo al cerrarlo

var ancho = 1573;
var alto = 760;

function removePhoto(id = '') {
  if (document.querySelector('#img' + id)) {
    document.querySelector('#img' + id).remove();
  }
  document.querySelector('#foto' + id).value = ""; //limpia el valor de la letiable foto 
  document.querySelector('#delPhoto' + id).classList.add("notBlock"); //oculta el div con la clase delPhoto que es la X eliminar foto
  document.querySelector('#foto_blob_type' + id).value = '';
  document.querySelector('#foto_blob_name' + id).value = '';
}
let file;//= document.getElementById("foto"); //file es input de selección, donde se garga el archivo
let img_type;
let img_name;

function formFile(id) {}
$('#div-modal-recorte').on('shown.bs.modal', () => { //al mostrar el modal ejecutan las siquientes funciones  
//crea el marco de selección sobre el objeto image
  cropper = new Cropper(image, {
    minCropBoxWidth: 720 * 0.30,
    minCropBoxHeight: 460 * 0.30,
    zoomOnWheel: false,
    zoomable: false,
    zoomOnTouch: false,
    preview: "#div-preview", //donde se mostrará la parte seleccionada
    viewMode: 1, //3: indica que no se podrá seleccionar fuera de los límites
    aspectRatio: 1.57 / 1 //NaN libre elección, 1 cuadrado, proporción del lado horizontal con respecto al vertical
  }); /*modal.on-shown*/
}).on('hidden.bs.modal', function () {
  cropper.destroy();
  image.src = '';
  cropper = null;
});//modal.on-hidden    

file = document.getElementById("foto");//+ id
file.addEventListener("change", (e) => {//escuchamos el change del input-file
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
      let reader = new FileReader();
      reader.onload = function (e) {
        image.src = reader.result;
      };
      reader.readAsDataURL(objfile);
    }
    image.onload = () => { //una vez cargada la imagen validamos el alto y ancho , 

      if (image.width < ancho && alto < image.height) {
        alertFoto('El tamaño minimo es de 720x460');
      } else {
        document.querySelector('#form_alert').innerHTML = ''; // el aviso de archivo erroneo pasa a vacio
        $('#div-modal-recorte').modal('show'); //mostramos el modal 
      }
    };
  }
});//file.on-change

btncrop.addEventListener("click", function () {//configuramos el click del boton crop
//    let canvas = cropper.getCroppedCanvas();
  let imgrecorte = cropper.getCroppedCanvas().toDataURL(img_type);//obtenemos la zona seleccionada
  let myImage = new Image();// y creamos un nuevo objeto imagen
  myImage.src = imgrecorte;
  imgrecorte = '';
  document.querySelector(`#prevPhoto div`).innerHTML = "<img id='img' src=" + myImage.src + ">";// ${id} actualiza la imagen vista previa del formulario
  document.querySelector('#foto_blob_type').value = img_type; // + id almacena en json en un pimputo tipo hide para enviarlo enel formulario
  document.querySelector('#foto_blob_name').value = img_name; // + id  almacena en json en un pimputo tipo hide para enviarlo enel formulario
  document.querySelector('#delPhoto').classList.remove("notBlock"); // + id  se dirige a este elemento y remueve la clase notBlock, lo que mostraria la X de eliminar imagen
  file.value = ""    //resetea el elemento input-file (file-upload)
  $('#div-modal-recorte').modal('hide') //escondo el modal
  document.querySelector("#foto_remove").value = 1;// + id
  image.src = '';
  reader = null;
});//btncrop.on-click

document.querySelector("#delPhoto").onclick = (e) => {//+ id
  document.querySelector("#foto_remove").value = 1;// + id
  document.querySelector(`#prevPhoto div`).innerHTML = "";//${id}
  img_type = '';
  img_name = '';
  removePhoto(id);
};

//}

function formCategoria(form, id) {
//NUEVO - ACTUALIZAR
  let formulario = document.querySelector(`#${form}` + id); // LA VARIABLE GUARDA LA UBICACION DEL FORMULARIO 
  formulario.onsubmit = (e) => { //SE AGREGA LA ACCION ONSUBMIT Y ELECUTA LA FUNCION DESCRITA 
    e.preventDefault(); //EVITA QUE LA PAGINA SE RECARGE
    console.log(formulario)

    let intIdCategoria = document.querySelector("#idCategoria" + id).value; //identificara los datos del formulario pertnecen a nuevo o a actualizar
    let strNombre = document.querySelector("#txtNombre" + id).value;
    let strDescripcion = document.querySelector("#txtDescripcion" + id).value;
//    if (intIdCategoria > 0) { //si el id rol es 0 significa que es un nuevo rol
//      document.querySelector("#listStatus" + id).value = 0;
//    }
    if (strNombre === '' || strDescripcion === '') {
      Swal.fire("atencion", "todos los campos son obligatorios.", "error");
      return false;
    }
    //divLoading.style.display = "flex"; // muestra una imagen para la espera de la carga del formulario
    let formData = new FormData(formulario);
    let nombrefoto = document.getElementById("foto_blob_name" + id).value;
    if (nombrefoto !== '') {
      let ImageURL = document.getElementById("img" + id).src;
//            let block = ImageURL.split(";");
//            let contentType = block[0].split(":")[1]; // Obtener tipo de contenido, en este caso "image/gif"
//            let realData = block[1].split(",")[1]; // Obtener información real en base64, en este caso "iVBORw0KGg...."
//            let blob = b64toBlob(realData, contentType); // Convertir a blob
      let blob = dataURLtoFile(ImageURL, nombrefoto); // Convertir a blob
      formData.append('foto', blob);
    }
    $.ajax({
      url: base_url + 'categorias/setCategoria',
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
        let objData = data;
        if (objData.status) {
          //$('#modalFormCategoria').modal("hide");
          document.querySelector("#idCategoria" + id).value = objData.id;
          if (document.querySelector('#acordeon' + id)) {
            document.querySelector('#acordeon' + id).innerHTML = `Categoria ${objData.id} - ${objData.nombre}`;
          }
          // 
          Swal.fire("Categoria de Productos", objData.msg, "success"); //libreria alerta 
          tableCategorias.ajax.reload(null, false); //RECARGA EL DATA TABLE DESPUES DE EJECUTAR EL SWAL
          //removePhoto(id);
          //formulario.reset();
        } else {
          Swal.fire("Error", objData.msg, "error");
        }
      } //, complete: (data)=> { console.log(data); }
    });
  };
}


/**************************************************************/

function nvaCategoria() {
//Nombre del Formulario Modal
  document.querySelector('#idCategoria').value = ""; //limpiamos el imput para que no incluya nungun valor despues de actualizar algun registro
  document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
  document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
  document.querySelector('#titleModal').innerHTML = "Nueva Categoria";
  document.querySelector('#btnText').innerHTML = "Guardar";
  document.querySelector('#formCategoria').reset(); // reseteamos los campos del formulario

  document.querySelector("#foto_remove").value = ""; //punto de cambio para actualizacion imagen de categoria

  //ocultamos el Status ya que tendra un valor de 1 = habilitado
  document.querySelector('#listStatusLabel').hidden = true;
  document.querySelector('#listStatus').hidden = true;
  // document.querySelector('#formCategoria').reset(); // reseteamos los campos del formulario
  document.querySelector('#pagina_prev').innerHTML = '';
  document.querySelector('#pagina_prox').innerHTML = '';
  document.querySelector('#pagina_poss').innerHTML = '';

  $('#modalFormCategoria').modal('show');
  removePhoto();
//    fotoCategoria();
  //modalRecortefoto();
  formFile('');
  formCategoria('formCategoria', '');
}

/**************************************************************/
function subcategoriaHtml(form, id, list_select_fb, list_select_gg, idcategoria = '', padre_cat_id = '', nombre = '', ruta = '', descripcion = '', img = '', tags = '', status = 0) {
  let idpadre = padre_cat_id === '' ? document.querySelector(`#idCategoria`).value : padre_cat_id;
  let formulario_id = form + id;
  let html = `
<h2 class="accordion-header" id="heading${id}">
  <button id="acordeon${id}"class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${id}" aria-expanded="false" aria-controls="collapseOne" ${id} >
    subCategoria - ${nombre} 
  </button>
</h2>
<div id="collapse${id}" class="accordion-collapse collapse" aria-labelledby="heading${id}" data-bs-parent="#accordionSubCategoria${id}" style="">
  <div class="accordion-body">
    <form id="${formulario_id}" name="formSubCategoria${id}">
      <input type="hidden" id="idCategoria${id}" name="idCategoria" value="${idcategoria}"><!-- este elemento estara oculto y su funcion es setear el id del Categoria a actualizar -->   
      <input type="hidden" id="idCatPadre${idpadre}" name="idCatPadre" value="${idpadre}"><!-- este elemento estara oculto y su funcion es setear el id del Categoria a actualizar -->    
      <div class="p-2">            
        <div class="row">
          <div class="col-lg-6">
            <div class="mb-3">
              <label class="control-label">Nombre <span class="required">*</span></label>
              <input class="form-control" id="txtNombre${id}"name="txtNombre"type="text" placeholder="Ingrese el nombre de la Categoria" required="" value="${nombre}">
            </div>
            <div class="mb-3">
              <label class="control-label">Descripcion <span class="required">*</span></label>
              <textarea class="form-control" id="txtDescripcion${id}" name="txtDescripcion" rows="4,5" placeholder="Descripcion de la Categoria" required="" >${descripcion}</textarea>
            </div>
          </div>
          <div class="col-lg-6 ">
            <div class="row">
              <div class="d-flex justify-content-center align-middle p-3">
              <input type="hidden" id="foto_actual${id}" name="foto_actual" value="${img}"><!-- -->
              <input type="hidden" id="foto_remove${id}" name="foto_remove" value=""><!-- -->
              <input type="hidden" id="foto_blob_name${id}" name="foto_blob_name" value=""><!-- -->
              <input type="hidden" id="foto_blob_type${id}" name="foto_blob_type" value=""><!-- -->
                <div class="photo"> <!-- Estilos de la imagen -->
                  <label id="prevPhotoLabel${id}"for="foto">Resolucion Minima 500x320)</label>
                  <div id="prevPhoto${id}" class="prevPhoto prevPhoto-subCategoria"> <!-- Donde se mostrara la vistaa previa de la imagen-->
                    <span id="delPhoto${id}" class="delPhoto notBlock">X</span> <!-- no estara visible y se le aplicaran algunos estilos -->
                    <label for="foto"></label> <!-- ocupara el ancho para poder seleccionar la foto -->
                    <div>
                       <img id="imgminiat${id}" src="${media}images/portada_categoria.png"> <!-- imagen previa -->
                    </div>
                  </div>
                  <div class="upimg"> <!-- junto al imput tipo file serviran para cargar la foto -->
                    <input type="file" accept="image/jpg , image/jpeg , image/png" name="foto" id="foto${id}">
                  </div>
                  <div id="form_alert${id}"></div>  <!-- aca se mostrata un texto  -->
                </div>
              </div>
            </div>
          </div>
        </div><!--end row-->
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="listStatus" id="listCatFBLabel">Categoria Facebook <span class="required">*</span></label>
              <select class="form-select" id="listCatFB${id}"name="listCatFB">
                ${list_select_fb}
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="listStatus" id="listCatGoogleLabel">Categoria Google <span class="required">*</span></label>
              <select class="form-select" id="listCatGoogle${id}"name="listCatGoogle">
                ${list_select_gg}
              </select>
            </div>
          </div>
        </div> <!--end row-->
        <div class="row">
          <!--                              <div class="mt-2 d-flex align-middle">-->
          <div class="col-sm-6 mb-3">
            <div class="mb-3">
              <label class="control-label">Etiquetas </label>
              <input class="form-control" id="txtTags${id}"name="txtTags"type="text" placeholder="Separe las etiquetas por coma  ',' " value="${tags}">
            </div>
          </div>
          <div class="col-sm-4 mb-3">
            <label class="control-label">Esatdo </label>
            <select class="form-select pr-5" id="listStatus${id}" name="listStatus" value="${status}">
              <option value="0" ${status === 0 ? "selected" : ''} > Inactivo</option>
              <option value="1" ${status === 1 ? "selected" : ''} > Activo</option>
            </select>
          </div>
          <div class="col-sm-2 mb-3">
            <label class="control-label">...................</label>
            <button  type="submit" form="${formulario_id}" class="btn btn-primary" >
              <i class="fa fa-check-circle" aria-hidden="true"></i><span id="btnText${id}">&nbsp;Guardar</span></button>
          </div> 
          <!--</div>--> 
        </div>
      </div>
    </form> 
  </div>
</div>
`;
  return html;
}
async function nvaSubCategoria() {
  let id = Date.now();
  let acordeon = document.querySelector('#accordionCategorias');
  let div = document.createElement("div");
  div.classList.add('accordion-item');

  let idCatFb = document.querySelector("#listCatFB").value;
  let list_select_fb;
  await fetch(base_url + 'categorias/getCategoriasRS/facebook/' + idCatFb + '?activo=' + 0)
          .then(response => response.text()).then(data => {
    list_select_fb = data;
  });

  let idCatGg = document.querySelector("#listCatGoogle").value;
  let list_select_gg;
  await fetch(base_url + 'categorias/getCategoriasRS/google/' + idCatGg + '?activo=' + 0)
          .then(response => response.text()).then(objData => {
    list_select_gg = objData;
  });
  div.innerHTML = subcategoriaHtml('formSubCategoria', id, list_select_fb, list_select_gg);

  acordeon.append(div);
  formFile(id);
  formCategoria('formSubCategoria', id);
}


/**************************************************************/
function fntVer(id) {
  let idCat = id;
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrlUserId = base_url + 'categorias/get/' + idCat;
  request.open("GET", ajaxUrlUserId, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {

      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        let estadoCategoria = objData.data.status === 1 ? //muestra el espan segun el estado 
                '<span class="bagde badge-success">Activo</option>' :
                '<span class="badge badge-danger">Inactivo</option>';
        document.querySelector('#celID').innerHTML = objData.data.idcategoria;
        document.querySelector('#celNombre').innerHTML = objData.data.nombre;
        document.querySelector('#celDescripcion').innerHTML = objData.data.descripcion;
        document.querySelector('#celEstado').innerHTML = estadoCategoria;
        document.querySelector('#celImgCategoria').innerHTML = '<img src = "' + objData.data.url_img + '">';
        $('#modalVerCategoria').modal('show')

      } else {
        Swal.fire("Error", objData.msg, "error");
      }
    }
  }
  /* });
   });*/
}
/**************************************************************/
async function fntEdit(id) {
//CAMBIAMOS ATRIBUTOS DEL MODAL
//con document document.querySelector nos referimos al elemento con el id o la clase que pasamos como parametro
//innerHTML le indicamos que reemplace el texto existente por el siguiente
//con classList.replace indicamos que reemplace la clase de estilos A por clase de estilos B
  document.querySelector('#titleModal').innerHTML = "Actualizar Categoria";
  document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
  document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
  document.querySelector('#btnText').innerHTML = "Actualizar";
  document.querySelector("#foto_remove").value = ""; //punto de cambio para actualizacion imagen de categoria


  //INSERTAMOS DATOS EN EL MODAL 
  let idCat = id;
  let ajaxUrl = base_url + 'Categorias/get/' + idCat;
  await fetch(ajaxUrl).then(response => response.json()).then(async objData => {
    if (objData.status) {

      document.querySelector('#idCategoria').value = objData.data.idcategoria;
      document.querySelector('#txtNombre').value = objData.data.nombre;

      let   idCatFb = objData.data.cat_facebook_id;
      document.querySelector('#listCatFB').value = idCatFb;
      let   idCatGg = objData.data.cat_google_id;
      document.querySelector('#listCatGoogle').value = idCatGg;

      document.querySelector('#txtDescripcion').value = objData.data.descripcion;
      document.querySelector("#foto_actual").value = objData.data.img;
      if (document.querySelector('#img')) { // agregamos la imagen 
        document.querySelector('#img').src = objData.data.url_img;
      } else {
        document.querySelector('.prevPhoto div').innerHTML = "<img id= 'img' src = '" + objData.data.url_img + "'>";
      }

      if (objData.data.img === 'categorias.png') { //mostramos la X de eliminar si la foto es distinta de la foto base
        document.querySelector('#delPhoto').classList.add("notBlock");
      } else {
        document.querySelector('.delPhoto').classList.remove("notBlock");
      }

      objData.data.prev === 0 ? document.querySelector('#pagina_prev').innerHTML = '' : document.querySelector('#pagina_prev').innerHTML = '<button class="page-item page-link pull-left" onClick="fntEdit(' + objData.data.prev + ')" >«</button>';
      objData.data.prox === 0 ? document.querySelector('#pagina_prox').innerHTML = '' : document.querySelector('#pagina_prox').innerHTML = '<button class="page-item page-link pull-right" onClick="fntEdit(' + objData.data.prox + ')" >»</button>';
      document.querySelector('#pagina_poss').innerHTML = '<span class="text-primary" >' + objData.data.posicion + '</span>';
      $('#modalFormCategoria').modal('show');
      formFile('');
      formCategoria('formCategoria', '');

      let subCat = objData.data.subCategorias;
      let countSubCat = subCat.length;

      let acordeon = document.querySelector('#accordionCategorias');
      acordeon.innerHTML = '';
      for (let i = 0; i < countSubCat; i++) {

        let id = subCat[i].idcategoria;
        let descripcion = subCat[i].descripcion;
        let idcategoria = subCat[i].idcategoria;
        let img = subCat[i].img;
        let nombre = subCat[i].nombre;
        let padre_cat_id = subCat[i].padre_cat_id;
        let ruta = subCat[i].ruta;
        let status = subCat[i].status;
        let tags = subCat[i].tags;

        let id_cat_fb = subCat[i].cat_facebook_id;
        let id_cat_gg = subCat[i].cat_google_id;

        let list_select_fb;
        await fetch(base_url + 'categorias/getCategoriasRS/facebook/' + idCatFb + '?activo=' + id_cat_fb)
                .then(response => response.text()).then(data => {
          list_select_fb = data;
        });

        let list_select_gg;
        await fetch(base_url + 'categorias/getCategoriasRS/google/' + idCatGg + '?activo=' + id_cat_gg)
                .then(response => response.text()).then(objData => {
          list_select_gg = objData;
        });
        // console.log(list_select_gg);
//        console.log(list_select_fb);
        let div = document.createElement("div");
        div.classList.add('accordion-item');
        div.innerHTML = await subcategoriaHtml('formSubCategoria', id, list_select_fb, list_select_gg, idcategoria, padre_cat_id, nombre, ruta, descripcion, img, tags, status);
        acordeon.append(div);
        await formFile(id);
        await formCategoria('formSubCategoria', id);
      }
    } else {
      Swal.fire("Error", objData.msg, "error");
    }
  });




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
      let ajaxUrlDel = base_url + 'categorias/delCategoria/';
      let arrData = "id=" + id;
      let cabecera = {"Content-type": "application/x-www-form-urlencoded"};
      fetch(ajaxUrlDel, {method: "POST", headers: cabecera, body: arrData})
              .then(objData => objData.json()).then(objData => {
        if (objData.status) {
          Swal.fire("HA SIDO UN EXITO", objData.msg, "success");
          tableCategorias.ajax.reload(null, false); // refrescamos la tabla
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
  fetch(base_url + 'categorias/statusCategoriaChange/' + '?id=' + id + '&intStatus=' + boton.value)
          .then(response => response.json()).then(objData => {
    if (objData.status) {
      if (boton.value == 1) {
        boton.value = 0, boton.classList.replace('btn-success', 'btn-danger');
      } else {
        boton.value = 1, boton.classList.replace('btn-danger', 'btn-success');
      }
// tableRoles.ajax.reload(null, false); // refrescamos la tabla
    } else {
      Swal.fire("Atencion!", objData.msg, "error");
    }
  }).catch(err => Swal.fire("Atencion!", err, "error"));
  //pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario
}























/**************************************************************/
jQuery(document).on("click", "a.change-image", function (event) {
// a#next-image es el botón algo como: <a class="next-image">Siguiente</a> "#" se refiere al id del elemento (Debe ser único).
  event.preventDefault();
  // event.preventDefault(); Detiene la acción por defecto del botón, como es un hipervinculo, 
  // recargaría la página aunque agregues codigo javascript, recuerda tener en function( event ) "evento" que se refiere a la acción que se realizó
  let url = this.href;
  // let url = this.href; decimos que la url será lo que este botón tenga como atributo "href"
  jQuery.get(url, null, function (respuesta) {
    // En jQuery.get estas haciendo una petición tipo GET a la url que le asignamos, 
    // en null irían las letiables adicionales que queremos enviar al servidor, 
    // algo como: {id_image: 55, is_ajax=true} traducido como: http://midominio.com/?id_image=5&is_ajax=true
    // "respuesta" se refiere a lo que el servidor retornó, esto es en tu caso todo el HTML que está en la siguiente página.
    let html = jQuery(respuesta);
    // let html = jQuery( respuesta ); Metemos en la letiable html el HTML del servidor ya convertiro en un objeto jQuery que podemos manipular.
    let container_gallery = html.find("#container_gallery");
    //let container_gallery = html.find("#container_gallery"); asignamos en "container_gallery" la etiqueta que sea  <div id="container_gallery">[botones e imagenes estan aqui dentro]</div>
    jQuery("#container_gallery").replaceWith(container_gallery);
    // jQuery("#container-gallery").html( container_gallery.html() ); reemplazamos el contenido de lo actual con lo que acabamos de obtener.
  });
});
