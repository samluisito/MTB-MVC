"use strict";
//document.write('<script type="text/javascript" src="' + media + 'admin/plugins/JsBarcode.all.min.js"></script>');
//let divLoading = document.querySelector("#divLoading");

let tableProductos;
let tinymcel;
let choiseListCategoria;
let choiseFiltroListCategoria;
let choiseListProveedor;
let dolarHoy = document.getElementById("dolarHoy").value;
document.addEventListener('DOMContentLoaded', () => {
  opcionesFiltroCategoria();
  filtrarTablaProductos();
  pluginsProductos();
});
//GENERADOR DE cODIGOS DE BARRA


/*modal Imagen */

/* variables para el recorte de iamgen y el envio por ajax-------------------------------------------*/
let URL = window.URL || window.webkitURL;
let file = document.getElementById("foto"); //file es input de selección, donde se garga el archivo
let image = document.getElementById("img-original"); //es elemento img dentro del modal donde se montará la imagen seleccionada (para ser cortada)
let btncrop = document.getElementById("btn-crop");
let cropper = null; //el objeto cropper que habrá que Crearlo al mostrar el modal y destruirlo al cerrarlo
let img_type = '';
let img_name = '';
let ancho = 600;
let alto = 600;
let relacionAspecto = 0.81 / 1;
let parentId = '';
function dataURLtoFile(dataurl, filename) {// convertir una img.src a archivo
  let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
          bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }
  return new File([u8arr], filename, {type: mime});
}
function alertFoto(mensaje) {
  let prevImg = document.querySelector("#" + parentId + " .prevImage"); //obtiene el div donde se encuentra la imagen 
  prevImg.innerHTML = '<p class="errorArchivo">' + mensaje + '.</p>';
  image.src = '';
  cropper = null;
  return false;
}

$('#div-modal-recorte').on('shown.bs.modal', function () { //al mostrar el modal ejecutan las siquientes funciones  
//crea el marco de selección sobre el objeto image
  cropper = new Cropper(image, {
    minCropBoxWidth: ancho * 0.30,
    minCropBoxHeight: alto * 0.30,
    zoomOnWheel: false,
    zoomable: false,
    zoomOnTouch: false,
    preview: "#div-preview", //donde se mostrará la parte seleccionada
    viewMode: 1 //3: indica que no se podrá seleccionar fuera de los límites
            //aspectRatio: relacionAspecto //NaN libre elección, 1 cuadrado, proporción del lado horizontal con respecto al vertical
  });
  /*modal.on-shown*/
}).on('hidden.bs.modal', function () {
  cropper.destroy();
  image.src = '';
  cropper = null;
}); //modal.on-hidden    

btncrop.addEventListener("click", function () {//configuramos el click del boton crop
  let imgrecorte = cropper.getCroppedCanvas().toDataURL(img_type); //obtenemos la zona seleccionada
  let prevImg = document.querySelector("#" + parentId + " .prevImage"); //obtiene el div donde se encuentra la imagen 
  prevImg.innerHTML = `<img class="loading" src="${base_url}Assets/images/loading.svg" >`; // inserta una imagen de carga mientras llega la respuesta del servidor 
  $('#div-modal-recorte').modal('hide'); //escondo el modal

  let myImage = new Image(); // y creamos un nuevo objeto imagen
  myImage.src = imgrecorte;
  let imgFile = dataURLtoFile(myImage.src, img_name); // Convertir a Archivo tipo jpg
  imgrecorte = '';
  let idProducto = document.querySelector("#idProducto").value; // este elemento toma el id del producto previamente cargado
  let nombreProducto = document.querySelector("#txtNombre").value; // este elemento toma el Nombre del producto previamente cargado

  let formData = new FormData(); //se enviaran los datos como i se enviara un formulario, pero omo no hay formulario para el div se crean las variables 
  formData.append('idproducto', idProducto);
  formData.append('nombreProducto', nombreProducto);
  formData.append('foto', imgFile);
  fetch(base_url + '/Productos/setImage', {method: "POST", body: formData})
          .then(objData => objData.json())
          .then(objData => {

            if (objData.status) {
//              prevImg.innerHTML = `<img src="${myImage.src}">`; // retira la imagen de carga e inserta la imagen desde el servidor 
//              document.querySelector("#" + parentId + " .btnDeleteImage").setAttribute("img_name", objData.img_name);
//              document.querySelector("#" + parentId + " .btnUploadfile").classList.add("notBlock");
//              document.querySelector("#" + parentId + " .btnDeleteImage").classList.remove("notBlock");
//              document.querySelector("#" + parentId + " .btnDeleteImage").classList.remove("notBlock");

              let key = objData.data.id;

              let html = contDivImg(key, objData.img_name, myImage.src, myImage.src);// genera el html a insetar
              let elemento = document.querySelector("#" + parentId);
              elemento.innerHTML = html;
              elemento.setAttribute("data-forden", objData.data.posicion);
              elemento.setAttribute("id", `div${key}`);

              /*refrescamos la tabla para actualizar las miniaturas*/
              filtrarTablaProductos(); //
              ligthbbox_reload();
              tableProductos.ajax.reload(null, false); // RECARGA EL DATA TABLE DESPUES DE EJECUTAR EL SWAL
            } else {
              Swal.fire("Error", objData.msg, "error");
            }
          }).catch((err) => window.alert(err));
}); //btncrop.on-click

//Subir imagen 
function fntInputFile() {
  let inputUploadfile = document.querySelectorAll(".inputUploadfile"); // esta variable hace referencia a la clase del imput que tenemos en l formularo, el cual carga la imagen
  inputUploadfile.forEach(function (inputUploadfile) {// recorremos todos los elementos que tenga esta clase, 
    inputUploadfile.addEventListener('change', function (e) { //escuchamos el change del input - file a esta variable, le asignamos el evento change, que ejecutara una funcion al haber cambios ,
      parentId = this.parentNode.getAttribute("id");
      let files = e.target.files;
      if (files && files.length > 0) {
        img_type = files[0]['type']; // Tipo del archivo 
        img_name = files[0]['name'];
        let tpo_img_correcto = img_type !== 'image/jpeg.' ? 1 : img_type !== 'image/jpg.' ? 1 : img_type !== 'image/png.' ? 1 : img_type !== 'image/webp.' ? 1 : 0;
        if (!tpo_img_correcto) {//(img_type !== 'image/jpeg.' || img_type !== 'image/jpg.' || img_type !== 'image/png.' || img_type !== 'image/webp.') { //se valida que los tipos de imagen sean png, jpg, jpeg,   si no lo son 
          alertFoto('El archivo debe de ser tipo: .jpg .jpeg .png');
        }
        let objfile = files[0]; //el objeto file[0] tiene las propiedades: name, size, type, lastmodified, lastmodifiedate
        //para poder visualizar el archivo de imagen lo debemos pasar a una url        
        if (URL) { //el objeto URL está en fase experimental así que si no existe usaria FileReader, crea una url del estilo: blob:http://localhost:1024/129e832d-2545-471f-8e70-20355d8e33eb
          image.src = URL.createObjectURL(objfile);
        } else if (FileReader) {
          let reader = new FileReader();
          reader.onload = () => image.src = reader.result;
          reader.readAsDataURL(objfile);
        }

        image.onload = function () { //una vez cargada la imagen validamos el alto y ancho , 
          //console.log(image.width + ' ancho && alto' + image.height);
          if (image.width < ancho || alto > image.height) {
            alertFoto('Imagen axtual: ' + image.width + 'x' + image.height + 'px, minimo: 600x600px, ideal mayor a 1200x1200px');
          } else {
            $('#div-modal-recorte').modal('show'); //mostramos el modal 
          }
        };
      }
    });
  });
}

function fntDelItem(element) {
  let nameImg = document.querySelector(element + ' .btnDeleteImage').getAttribute("img_name"); //identificamos el elmento por medio del kay y de la clase document.querySelector(element+' .btnDeleteImage') y con getAttribute("img_name") capturamos el valor del atributo identificado
  let idProducto = document.querySelector('#idProducto').value;
  let formData = new FormData(); //se enviaran los datos como i se enviara un formulario, pero omo no hay formulario para el div se crean las variables 
  formData.append('idproducto', idProducto);
  formData.append('file', nameImg);
  fetch(base_url + '/Productos/delFile', {method: "POST", body: formData})
          .then(objData => objData.json())
          .then(objData => {
            if (objData.status) {//Swal.fire("Error", objData.msg, "success");
              let itemRemove = document.querySelector(element); //selecciona el elemnto 
              itemRemove.parentNode.removeChild(itemRemove); // con parentNode selecciona al padre de itemRemove y con removeChild elimina al hijo. (se suicida por medio del padre) 

              objData.data;
              let count = objData.data.length;
//              console.log(count);
              for (let p = 0; p < count; p++) { // repasamos la lista , donde generamos el html generar imagen con cada uno de los elementos del array 
                let element = document.querySelector(`#div${objData.data[p].id}`);
//                console.log(objData.data[p].id);
//                console.log(element);
                element.setAttribute("data-forden", objData.data[p].posicion);
              }
              //refrescamso la tabla de productos para actualizar las minituras
              filtrarTablaProductos(); //
              tableProductos.ajax.reload(null, false); // RECARGA EL DATA TABLE DESPUES DE EJECUTAR EL SWAL      
            } else {
              Swal.fire("Error", objData.msg, "error");
            }
          }).catch(e => console.log(e));
}

document.getElementById("costoPeso").onchange = () => calcular("costoPeso", "costoUSD", "d", 2);
document.getElementById("costoPeso").onkeyup = () => calcular("costoPeso", "costoUSD", "d", 2);
document.getElementById("costoUSD").onchange = () => calcular("costoUSD", "costoPeso", "m", 0);
document.getElementById("costoUSD").onkeyup = () => calcular("costoUSD", "costoPeso", "m", 0);

document.getElementById("precioUSD").onchange = () => calcular("precioUSD", "precioPeso", "m", 0);
document.getElementById("precioUSD").onkeyup = () => calcular("precioUSD", "precioPeso", "m", 0);
document.getElementById("precioPeso").onchange = () => calcular("precioPeso", "precioUSD", "d", 2);
document.getElementById("precioPeso").onkeyup = () => calcular("precioPeso", "precioUSD", "d", 2);
document.getElementById("porcentaje").onchange = () => sumarPorcentaje();
document.getElementById("porcentaje").onkeyup = () => sumarPorcentaje();

document.getElementById("ofertaPesos").onchange = () => calcular("ofertaPesos", "ofertaDolar", "d", 2);
document.getElementById("ofertaPesos").onkeyup = () => calcular("ofertaPesos", "ofertaDolar", "d", 2);
document.getElementById("ofertaDolar").onchange = () => calcular("ofertaDolar", "ofertaPesos", "m", 0);
document.getElementById("ofertaDolar").onkeyup = () => calcular("ofertaDolar", "ofertaPesos", "m", 0);

function calcular(origen, destino, operador, decimales) {
  let precio = document.getElementById(origen).value;
  let valor = (operador === 'd' ? precio / dolarHoy : precio * dolarHoy);
  document.getElementById(destino).value = parseFloat(valor).toFixed(decimales);
  calcularDiferencia();
  calcularPorciento();
}
function sumarPorcentaje() {
  let costo = parseFloat(document.getElementById('costoUSD').value);
  let porciento = parseFloat(document.getElementById('porcentaje').value);
  if (costo > 0 && porciento > 0) {

    let montoPorciento = (costo / 100) * porciento + costo;
    document.getElementById('precioUSD').value = parseFloat(montoPorciento).toFixed(2);
    document.getElementById('precioPeso').value = parseFloat(montoPorciento * dolarHoy).toFixed(0);
    calcularDiferencia();
  }
}
function calcularPorciento() {
  let costo = document.getElementById('costoUSD').value;
  let precio = document.getElementById('precioUSD').value;
  let porcentaje = ((precio - costo) / costo) * 100;
  if (costo > 0 && precio > 0) {
    document.getElementById('porcentaje').value = parseFloat(porcentaje).toFixed(2);

  }
}

function calcularDiferencia() {
  let costo = document.getElementById('costoUSD').value;
  let precio = document.getElementById('precioUSD').value;
  let costop = document.getElementById('costoPeso').value;
  let preciop = document.getElementById('precioPeso').value;

  if (costo > 0 && precio > 0) {
    document.getElementById('diferenciaDolar').value = parseFloat(precio - costo).toFixed(2);
  }
  if (costop > 0 && preciop > 0) {
    document.getElementById('diferenciaPeso').value = parseFloat(preciop - costop).toFixed(0);
  }
}

//function  fntPrintBarCode(area) {
//  let elementArea = document.querySelector(area);
//  let vprint = window.open('', 'popimpr', 'heirth=400, width=600');
//  vprint.document.write(elementArea.innerHTML);
//  vprint.document.close();
//  vprint.print();
//  vprint.close();
//}
//function fntBarcode() {
//  let codigo = document.querySelector("#txtCodigo").value;
//  JsBarcode("#barcode", codigo, {
//    height: 50,
//    textMargin: 10});
//  document.querySelector("#divBarCode").classList.remove("notBlock");
//}
function pluginsProductos() {
  
////BARCODE
//  if (document.querySelector("#txtCodigo")) {
//    let inputCodigo = document.querySelector("#txtCodigo");
//    inputCodigo.onkeyup = function () {
//      inputCodigo.value.length >= 4 ? // esta clase genera la imagen del codigo de barras y la habilita para mostrarla 
//              fntBarcode() :
//              document.querySelector("#divBarCode").classList.add("notBlock");
//    };
//  }

//TINYMCE EDITOR DE TECTO ENRIQUECIDO   contextmenu    textcolor   
tinymce.init({
  selector: '#txtDescripcion',
  width: "100%",
  plugins: [
    "lists", // permite agregar listas numeradas 'numlist' y con viñetas 'bullist'
    'advlist', //amplía los controles bullist y numlist de la barra de herramientas al agregar 'list-style-type' formatos con estilo CSS y tipos de viñetas a los controles.
    'charmap', //agrega un cuadro de diálogo al editor con un mapa de caracteres Unicode especiales
    'code', // permite al usuario editar el código HTML oculto
    'emoticons', // permite la insersion de emoticonos
    //'fullscreen', //agrega capacidades de edición de pantalla completa
    'help', //agrega un botón y / o elemento de menú
    'hr', // barra horizontal() permite al usuario insertar una barra horizontal en el punto de inserción del cursor
    // 'image', // permite insertar una imagen en el área editable. El complemento también agrega un botón en la barra de herramientas y un Insert/edit image elemento de menú debajo del Insert menú.
    // 'imagetools', // agrega una barra de herramientas de edición contextual a las imágenes en el editor. Si la barra de herramientas no aparece al hacer clic en la imagen, es posible que deba habilitar imagetools_cors_hostso imagetools_proxy
    'insertdatetime', // permite al usuario insertar fácilmente la fecha y / o la hora actual en el punto de inserción del cursor.
    //'link', // permite vincular enlaces externos, como URL de sitios web, al texto seleccionado en su documento.
    //'media', // agrega la capacidad para agregar elementos de audio y video HTML5 
    'nonbreaking', //  agrega un botón para insertar entidades de espacio que no se rompan &nbsp;
    //'pagebreak', //  permite insertar saltos de página
    //'paste', //  filtrará / limpiará el contenido pegado desde MsWord y agrega un elemento de menú Pasteastext en el menú Edit Paste.
    //'preview', //  agrega un botón de vista previa a la barra de herramientas.
    //'print', //  agrega un botón de impresión a la barra de herramientas
    //'searchreplace', //  agrega cuadros de diálogo de búsqueda / reemplazo
    //  'spellchecker', //  habilita la función de revisión ortográfica spellchecker composer 
    // 'table', //  agrega funcionalidad de administración de tablas
    // 'template', //  agrega soporte para plantillas personalizadas. https://www.tiny.cloud/docs/plugins/opensource/template/#templates
    // 'toc', //  generará una tabla de contenido básica 
    'visualblocks ', //  permite al usuario ver elementos a nivel de bloque 
    'visualchars ', //  agrega la capacidad de ver caracteres invisibles como se &nbsp;
    'wordcount ' //  agrega la capacidad de ver caracteres invisibles como se &nbsp;
  ]
  , toolbar1:
          ' undo ' + // Para deshacer la última operación.
          ' redo |' + // Para rehacer la última operación deshecha.
          ' fontselect ' + // Lista desplegable con familias de fuentes para aplicar a la selección.
          ' fontsizeselect ' + // Lista desplegable con tamaños de fuente para aplicar a la selección.
          //   ' styleselect ' + // Lista desplegable con estilos para aplicar a la selección.

          ' formatselect ' + // Lista desplegable con formatos de bloque para aplicar a la selección.
//              ' h1 ' + // Cambia la línea actual al estilo "Título 1".
//              ' h2 ' + // Cambia la línea actual al estilo "Título 2".
//              ' h3 ' + // Cambia la línea actual al estilo "Título 3".
//              ' h4 ' + // Cambia la línea actual al estilo "Título 4".
//              ' h5 ' + // Cambia la línea actual al estilo "Título 5".
//              ' h6 ' + // Cambia la línea actual al estilo "Título 6".
          ' lineheight ' + // Lista desplegable con alturas de línea para aplicar a la selección. > Nota 
          ' indent ' + // Sangra el elemento de la lista o el elemento de bloque actual.
          ' outdent |' + // Supera el elemento de la lista o el elemento de bloque actual.
          ' alignleft ' + // Izquierda alinea el bloque o la imagen actual.
          ' aligncenter ' + // El centro alinea el bloque o la imagen actual.
          ' alignright ' + // Derecha alinea el bloque o la imagen actual.
          ' alignjustify ' + // Completo alinea el bloque o la imagen actual.
//            ' alignnone |' + // Elimina la alineación del bloque o imagen actual.

          //' blockquote |' + // Aplica el formato de cotización de bloque al elemento de nivel de bloque actual.

          ' backcolor ' + // Aplica color de fondo a la selección.
          ' forecolor |' + // Aplica color de primer plano / texto a la selección.

          ' bold ' + // Aplica el formato de negrita a la selección actual.
          ' italic ' + // Aplica el formato de cursiva a la selección actual.
          ' underline ' + // Aplica el formato de subrayado a la selección actual.

          //          ' strikethrough |' + // Aplica el formato de tachado a la selección actual.
          //        ' subscript ' + // Aplica formato de subíndice a la selección actual.
          //      ' superscript |' + // Aplica formato de superíndice a la selección actual.

          ' copy ' + // Copia la selección actual en el portapapeles.
          ' cut ' + // Corta la selección actual al portapapeles.
          ' paste |' + // Pega el portapapeles actual en el editor.


          //  ' newdocument ' + // Crea un nuevo documento.

          //    ' remove ' + // Elimina (elimina) el contenido seleccionado o el contenido antes de la posición del cursor.
          //    ' selectall ' + // Selecciona todo el contenido del editor.
          //    ' visualaid '+  // Alterna las ayudas visuales para elementos invisibles.

          // '', toolbar2:
          ' removeformat |' + // Elimina el formato de la selección actual.
          ' numlist bullist |' +
          //          ' charmap |' +
//            ' code |' +
          ' emoticons |' +
//            ' fullscreen |' +
//            ' image |' +
          //  ' insertdatetime |' +
//            ' link |' +
//            ' media |' +
//            ' nonbreaking |' +
          ' hr |' +
//            ' pagebreak |' +
          // ' paste pastetext |' +
          //  ' preview |' +
          // ' print |' +
          // ' searchreplace |' +
          // ' spellchecker |' +
          'table | ' +
          //'tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol |' +
          // 'template | ' +
          //'toc | ' +
//            'visualblocks | ' +
//            'visualchars | ' +
          //  'wordcount | ' +
          ' help |' +
          ''
  , menubar: 'file edit insert view format table tools help'


          // , contextmenu: "",
          // content_css: base_url + 'Assets/css/tinymce.css'
  , fullscreen_native: false // permite que el editor use el modo de pantalla completa del navegador, en lugar de llenar la ventana del navegador 
  , height: '300'
  , statubar: true
  , a11y_advanced_options: true // habilita opciones adcionales a imagenes
  , image_advtab: true //agrega una pestaña "Avanzado" al cuadro de diálogo de la imagen, que permite agregar estilos personalizados, espaciado y bordes a las imágenes.
  , imagetools_toolbar: //selección exacta de botones que aparecerán en la barra de herramientas
          'rotateleft rotateright | flipv fliph | editimage imageoptions'
  , default_link_target: '_blank' //permite establecer un target valor predeterminado para los links
  , link_context_toolbar: true //habilitará una barra de herramientas contextual que aparecerá cuando el cursor del usuario esté dentro de un enlace
  , link_title: true // permite deshabilitar el campo Titulo del cuedro de dialogo de Link
  , media_live_embeds: true // permite habilitar vista previa del contenido de video incrustado, en lugar de una imagen de marcador de posición
  , pagebreak_separator: // especifica cómo se debe generar el salto de página en el código fuente HTML  se puede envolver en <p>etiquetas </p>.
          '<!-- salto de pagina -->'
  , browser_spellcheck: true// habilita la correccion ortografica nativa del explorador
  , contextmenu: false // establece los valores del menu contextual (click derecho) 'link image | table'  false para deshabilitar
  , fontsize_formats: '6pt 8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt'
          //,  font_formats: 'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; AkrutiKndPadmini=Akpdmi-n'
  , block_formats: 'Paragraph=p; Header 3=h3; Header 4=h4; Header 5=h5; Header 6=h6'
  , style_formats: [
    {title: 'Headings', items: [
        //  {title: 'Heading 1', format: 'h1'},
        //  {title: 'Heading 2', format: 'h2'},
        {title: 'Heading 3', format: 'h3'},
        {title: 'Heading 4', format: 'h4'},
        {title: 'Heading 5', format: 'h5'},
        {title: 'Heading 6', format: 'h6'}
      ]},
    {title: 'Inline', items: [
        {title: 'Bold', format: 'bold'},
        {title: 'Italic', format: 'italic'},
        {title: 'Underline', format: 'underline'},
        {title: 'Strikethrough', format: 'strikethrough'},
        {title: 'Superscript', format: 'superscript'},
        {title: 'Subscript', format: 'subscript'},
        {title: 'Code', format: 'code'}
      ]},
    {title: 'Blocks', items: [
        {title: 'Paragraph', format: 'p'},
        {title: 'Blockquote', format: 'blockquote'},
        {title: 'Div', format: 'div'},
        {title: 'Pre', format: 'pre'}
      ]},
    {title: 'Align', items: [
        {title: 'Left', format: 'alignleft'},
        {title: 'Center', format: 'aligncenter'},
        {title: 'Right', format: 'alignright'},
        {title: 'Justify', format: 'alignjustify'}
      ]}
  ]
  , theme: 'silver'
  , language: 'es'


  , branding: false //eshabilitar el enlace " Powered by Tiny "

});
tinymce.init({
  selector: '#txtDetalle',
  width: "100%",
  plugins: [
    "lists", // permite agregar listas numeradas 'numlist' y con viñetas 'bullist'
    'advlist', //amplía los controles bullist y numlist de la barra de herramientas al agregar 'list-style-type' formatos con estilo CSS y tipos de viñetas a los controles.
    'charmap', //agrega un cuadro de diálogo al editor con un mapa de caracteres Unicode especiales
    'code', // permite al usuario editar el código HTML oculto
    'emoticons', // permite la insersion de emoticonos
    'fullscreen', //agrega capacidades de edición de pantalla completa
    'help', //agrega un botón y / o elemento de menú
    'hr', // barra horizontal() permite al usuario insertar una barra horizontal en el punto de inserción del cursor
    // 'image', // permite insertar una imagen en el área editable. El complemento también agrega un botón en la barra de herramientas y un Insert/edit image elemento de menú debajo del Insert menú.
    // 'imagetools', // agrega una barra de herramientas de edición contextual a las imágenes en el editor. Si la barra de herramientas no aparece al hacer clic en la imagen, es posible que deba habilitar imagetools_cors_hostso imagetools_proxy
    'insertdatetime', // permite al usuario insertar fácilmente la fecha y / o la hora actual en el punto de inserción del cursor.
    'link', // permite vincular enlaces externos, como URL de sitios web, al texto seleccionado en su documento.
    //'media', // agrega la capacidad para agregar elementos de audio y video HTML5 
    'nonbreaking', //  agrega un botón para insertar entidades de espacio que no se rompan &nbsp;
    'pagebreak', //  permite insertar saltos de página
    'paste', //  filtrará / limpiará el contenido pegado desde MsWord y agrega un elemento de menú Pasteastext en el menú Edit Paste.
    //'preview', //  agrega un botón de vista previa a la barra de herramientas.
    //'print', //  agrega un botón de impresión a la barra de herramientas
    'searchreplace', //  agrega cuadros de diálogo de búsqueda / reemplazo
    //  'spellchecker', //  habilita la función de revisión ortográfica spellchecker composer 
    'table', //  agrega funcionalidad de administración de tablas
    // 'template', //  agrega soporte para plantillas personalizadas. https://www.tiny.cloud/docs/plugins/opensource/template/#templates
    // 'toc', //  generará una tabla de contenido básica 
    'visualblocks ', //  permite al usuario ver elementos a nivel de bloque 
    'visualchars ', //  agrega la capacidad de ver caracteres invisibles como se &nbsp;
    'wordcount ' //  agrega la capacidad de ver caracteres invisibles como se &nbsp;
  ]
  , toolbar1:
          ' undo ' + // Para deshacer la última operación.
          ' redo |' + // Para rehacer la última operación deshecha.
          ' fontselect ' + // Lista desplegable con familias de fuentes para aplicar a la selección.
          ' fontsizeselect ' + // Lista desplegable con tamaños de fuente para aplicar a la selección.
          //   ' styleselect ' + // Lista desplegable con estilos para aplicar a la selección.

          ' formatselect ' + // Lista desplegable con formatos de bloque para aplicar a la selección.
//              ' h1 ' + // Cambia la línea actual al estilo "Título 1".
//              ' h2 ' + // Cambia la línea actual al estilo "Título 2".
//              ' h3 ' + // Cambia la línea actual al estilo "Título 3".
//              ' h4 ' + // Cambia la línea actual al estilo "Título 4".
//              ' h5 ' + // Cambia la línea actual al estilo "Título 5".
//              ' h6 ' + // Cambia la línea actual al estilo "Título 6".
          ' lineheight ' + // Lista desplegable con alturas de línea para aplicar a la selección. > Nota 
          ' indent ' + // Sangra el elemento de la lista o el elemento de bloque actual.
          ' outdent |' + // Supera el elemento de la lista o el elemento de bloque actual.
          ' alignleft ' + // Izquierda alinea el bloque o la imagen actual.
          ' aligncenter ' + // El centro alinea el bloque o la imagen actual.
          ' alignright ' + // Derecha alinea el bloque o la imagen actual.
          ' alignjustify ' + // Completo alinea el bloque o la imagen actual.
          ' alignnone |' + // Elimina la alineación del bloque o imagen actual.

          //' blockquote |' + // Aplica el formato de cotización de bloque al elemento de nivel de bloque actual.

          ' backcolor ' + // Aplica color de fondo a la selección.
          ' forecolor |' + // Aplica color de primer plano / texto a la selección.

          ' bold ' + // Aplica el formato de negrita a la selección actual.
          ' italic ' + // Aplica el formato de cursiva a la selección actual.
          ' underline ' + // Aplica el formato de subrayado a la selección actual.

          ' strikethrough |' + // Aplica el formato de tachado a la selección actual.
          ' subscript ' + // Aplica formato de subíndice a la selección actual.
          ' superscript |' + // Aplica formato de superíndice a la selección actual.

          ' copy ' + // Copia la selección actual en el portapapeles.
          ' cut ' + // Corta la selección actual al portapapeles.
          ' paste |' + // Pega el portapapeles actual en el editor.


          //  ' newdocument ' + // Crea un nuevo documento.

          //    ' remove ' + // Elimina (elimina) el contenido seleccionado o el contenido antes de la posición del cursor.
          //    ' selectall ' + // Selecciona todo el contenido del editor.
          //    ' visualaid '+  // Alterna las ayudas visuales para elementos invisibles.

          // '', toolbar2:
          ' removeformat |' + // Elimina el formato de la selección actual.
          ' numlist bullist |' +
          ' charmap |' +
          ' code |' +
          ' emoticons |' +
          ' fullscreen |' +
          ' image |' +
          //  ' insertdatetime |' +
          ' link |' +
          ' media |' +
          ' nonbreaking |' +
          ' hr |' +
          ' pagebreak |' +
          // ' paste pastetext |' +
          //  ' preview |' +
          // ' print |' +
          // ' searchreplace |' +
          // ' spellchecker |' +
          'table | ' +
          //'tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol |' +
          // 'template | ' +
          //'toc | ' +
          'visualblocks | ' +
          'visualchars | ' +
          //  'wordcount | ' +
          ' help |' +
          ''
  , menubar: 'file edit insert view format table tools help'


          // , contextmenu: "",
          // content_css: base_url + 'Assets/css/tinymce.css'
  , fullscreen_native: false // permite que el editor use el modo de pantalla completa del navegador, en lugar de llenar la ventana del navegador 
  , height: '300'
  , statubar: true
  , a11y_advanced_options: true // habilita opciones adcionales a imagenes
  , image_advtab: true //agrega una pestaña "Avanzado" al cuadro de diálogo de la imagen, que permite agregar estilos personalizados, espaciado y bordes a las imágenes.
  , imagetools_toolbar: //selección exacta de botones que aparecerán en la barra de herramientas
          'rotateleft rotateright | flipv fliph | editimage imageoptions'
  , default_link_target: '_blank' //permite establecer un target valor predeterminado para los links
  , link_context_toolbar: true //habilitará una barra de herramientas contextual que aparecerá cuando el cursor del usuario esté dentro de un enlace
  , link_title: true // permite deshabilitar el campo Titulo del cuedro de dialogo de Link
  , media_live_embeds: true // permite habilitar vista previa del contenido de video incrustado, en lugar de una imagen de marcador de posición
  , pagebreak_separator: // especifica cómo se debe generar el salto de página en el código fuente HTML  se puede envolver en <p>etiquetas </p>.
          '<!-- salto de pagina -->'
  , browser_spellcheck: true// habilita la correccion ortografica nativa del explorador
  , contextmenu: false // establece los valores del menu contextual (click derecho) 'link image | table'  false para deshabilitar
  , fontsize_formats: '6pt 8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt'
          //,  font_formats: 'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; AkrutiKndPadmini=Akpdmi-n'
  , block_formats: 'Paragraph=p; Header 3=h3; Header 4=h4; Header 5=h5; Header 6=h6'
  , style_formats: [
    {title: 'Headings', items: [
        //  {title: 'Heading 1', format: 'h1'},
        //  {title: 'Heading 2', format: 'h2'},
        {title: 'Heading 3', format: 'h3'},
        {title: 'Heading 4', format: 'h4'},
        {title: 'Heading 5', format: 'h5'},
        {title: 'Heading 6', format: 'h6'}
      ]},
    {title: 'Inline', items: [
        {title: 'Bold', format: 'bold'},
        {title: 'Italic', format: 'italic'},
        {title: 'Underline', format: 'underline'},
        {title: 'Strikethrough', format: 'strikethrough'},
        {title: 'Superscript', format: 'superscript'},
        {title: 'Subscript', format: 'subscript'},
        {title: 'Code', format: 'code'}
      ]},
    {title: 'Blocks', items: [
        {title: 'Paragraph', format: 'p'},
        {title: 'Blockquote', format: 'blockquote'},
        {title: 'Div', format: 'div'},
        {title: 'Pre', format: 'pre'}
      ]},
    {title: 'Align', items: [
        {title: 'Left', format: 'alignleft'},
        {title: 'Center', format: 'aligncenter'},
        {title: 'Right', format: 'alignright'},
        {title: 'Justify', format: 'alignjustify'}
      ]}
  ]
  , theme: 'silver'
  , language: 'es'
  , branding: false //deshabilitar el enlace " Powered by Tiny "

});
//SCRIPT PARA APTURA DE HIPERENLACE DE TINYMCE, SUPERPONE EL EDITOR PARA QUE LOS MODALS FUNCIONEN ADECUADAMENTE 
$(document).on('focusin', function (e) {
  if ($(e.target).closest(".tox-dialog").length) {
    e.stopImmediatePropagation();
  }
});
// SELECTPICKER selector de categorias
if (document.querySelector('#listCategoria')) {
  fetch(base_url + 'categorias/getSelectCategoriasChoise')
          .then(res => res.json()).then((res) => {
    let opciones = document.getElementById('listCategoria');
    choiseListCategoria = new Choices(opciones, {shouldSort: false, searchPlaceholderValue: "Buscar Categoria", itemSelectText: '<-',
      choices: res
    });
    choiseListCategoria.setChoiceByValue(0);
  });
}

// SELECTPICKER selector de proveedores
if (document.querySelector('#listProveedor')) {
  fetch(base_url + 'productos/getSelectProveedoresChoise')
          .then(res => res.json()).then((res) => {
    let opciones = document.getElementById('listProveedor');
    choiseListProveedor = new Choices(opciones, {shouldSort: false, searchPlaceholderValue: "Buscar proveedor", itemSelectText: '<-',
      choices: res
    });
    //choiseListCategoria.setChoiceByValue(0);
  });
}

//Galeria de imagenes
if (document.querySelector('.btnAddImage')) {
  let btnAddImage = document.querySelector('.btnAddImage');
  btnAddImage.onclick = function (e) {
    let key = Date.now();
    let newElement = document.createElement("div");
    newElement.id = "div" + key;
    newElement.innerHTML = contDivImg(key);//genera el html a insertar
    document.querySelector("#containerImages").appendChild(newElement);
    document.querySelector('#div' + key + " .btnUploadfile").click();
    fntInputFile();

  };
}
}
let lightbox;
let lightboxDesc;
let lightboxvideo;
function ligthbbox_reload() {

  lightbox = GLightbox({
    selector: ".image-popup",
    title: false
  });


}
function contDivImg(key, img_name = null, img_url = null, thumb_url = null, posicion = null) {
  return `${posicion === null ? '' : `<div id="div${key}" data-forden="${posicion}">`}
            <div class="botonPrevProx row mx-0">
                  <button type="button" class="btn btn-outline-dark btn-sm col-6" onClick="fotoMovePos('-')"><i class="fas fa-arrow-left"></i></button>
                  <button type="button" class="btn btn-outline-dark btn-sm col-6" onClick="fotoMovePos('+')"><i class="fas fa-arrow-right"></i></button>
            </div>          
              <div class="prevImage ${img_url === null ? '"' : `prod-pic-rel" style="background: url(${img_url})"`}>
              ${img_url === null ? '' : `<a href="${img_url}" class="prod-scale-img thumb preview-thumb image-popup">
                                            <img src="${thumb_url}" class="img-fluid " alt="work-thumbnail"> 
                                          </a>`}
            </div>
            <input type="file" name="foto" id="img${key}" class="inputUploadfile">
            <label for="img${key}" class="btnUploadfile ${img_url === null ? '' : 'notBlock'}"><i class="fa fa-upload "></i></label>
            <button class="btnDeleteImage ${img_url !== null ? '' : 'notBlock'}" type="button" onclick="fntDelItem('#div${key}')" ${img_name === null ? '' : `img_name="${img_name}"`} ><i class="fa fa-trash"></i></button>
          ${posicion === null ? '' : `</div>`}`;

}
//lightbox = GLightbox({
//  selector: ".image-popup",
//  title: false
//});
//  lightboxDesc = GLightbox({
//    selector: ".image-popup-desc"
//  });
//  lightboxvideo = GLightbox({
//    selector: ".image-popup-video-map",
//    title: false
//  });

function urlTablaFiltrada() {
  let cat = "&cat=" + document.getElementById("filtro_categoria").value;
  let premin = "&premin=" + document.getElementById("filtro_montoMinimo").value;
  let premax = "&premax=" + document.getElementById("filtro_montoMaximo").value;
  let estado = "&estado=" + document.getElementById("filtro_estado").value;
  return  cat + premin + premax + estado;
}

// LA VARIABLE GUARDA LA UBICACION DEL FORMULARIO 
function filtrarTablaProductos() {
  document.querySelector("#formFiltroTableProductos").onclick = function (e) { //SE AGREGA LA ACCION ONSUBMIT Y ELECUTA LA FUNCION DESCRITA 
    e.preventDefault(); //EVITA QUE LA PAGINA SE RECARGE
    tableProductos.ajax.url(base_url + "productos/getProductos" + urlTablaFiltrada()).load();
  };
}
//Lista de categorias para el filto de la tabla 
function opcionesFiltroCategoria() {
  fetch(base_url + 'productos/getCategoriasProducto')
          .then(res => res.text()).then((res) => {
    let opciones = document.getElementById('filtro_categoria');
    let pevValor = opciones.value === '' ? 0 : opciones.value; // captura el calor antes de refrescar las opciones
    opciones.innerHTML = `<option value='0'>Todo</option>` + res; //agrega las opciones
    opciones.options[pevValor].selected = true; //setea el valor
    opciones.selectedIndex = pevValor; //refresca el input on la opcion seleccionada
//    let a = document.getElementById('filtro_categoria');
    choiseFiltroListCategoria = new Choices(opciones, {shouldSort: false, searchPlaceholderValue: "Buscar Categoria", itemSelectText: '<-'});
  });
}
tableProductos = $('#tableProductos').DataTable({
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
  "order": [], //[[4, "DESC"]], // "ordering": true, "order": [[0, "desc"]], //ordenar y ordenar por 
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
  "ajax": {url: base_url + "productos/getProductos" + urlTablaFiltrada(), dataSrc: ""},
  "columns": [
    {"data": "idproducto"}, //0
    {"data": "img_prod"}, //1
    {"data": "codigo"}, //2
    {"data": "nombre"}, //3
    {"data": "categoria"}, //4
    {"data": "precio"}, //5
    {"data": "stock"}, //6
    {"data": "options"}, //7
    {"data": "descripcion"}, //8
    {"data": "img_url"}, //9
    {"data": "ruta"}//10
  ],
  "columnDefs": [
    {"targets": [0], 'className': "text-rigth align-middle", orderable: false, searchable: false, visible: false},
    {"targets": [1], 'className': "text-rigth align-middle", orderable: false, searchable: false},
    {"targets": [2], 'className': "text-rigth align-middle", orderable: false, searchable: false, visible: false},
    {"targets": [3], 'className': "text-center align-middle", orderable: false},
    {"targets": [4], 'className': "text-center align-middle", orderable: false},
    {"targets": [5], 'className': "text-center align-middle", orderable: false},
    {"targets": [6], 'className': "text-center align-middle", orderable: false},
    {"targets": [7], 'className': "text-center align-middle", orderable: false, searchable: false},
    {"targets": [8], 'className': "text-rigth align-middle", orderable: false, searchable: false, visible: false},
    {"targets": [9], 'className': "text-rigth align-middle", orderable: false, searchable: false, visible: false},
    {"targets": [10], 'className': "text-rigth align-middle", orderable: false, searchable: false, visible: false}
  ],
  buttons: [
//    {
//      extend: 'excel',
//      text: 'excel',
//      ttleAtter: 'Exportar a Excel',
//      className: 'btn btn-danger excelButton',
//      exportOptions: {        modifier: {          search: 'none'        }      }    
//      },
//    {
//      extend: 'pdf',
//      text: 'PDF',
//      ttleAtter: 'Exportar a PDF',
//      className: 'btn btn-primary',
//      exportOptions: {        modifier: {          search: 'none'        }      }
//    },
    {
      extend: 'csv',
      text: 'Exportar a CSV',
      ttleAtter: 'Exportar a CSV',
      className: 'btn btn-warning',
      exportOptions: {modifier: {search: 'none'}}
    }
//   , {
//      extend: 'csv',
//      text: 'Exportar a CSV',
//      ttleAtter: 'Exportar a CSV',
//      className: 'btn btn-warning',
//      exportOptions: {modifier: {search: 'none'}}
//    }
  ]
});

function nvoProducto() {
//Nombre del Formulario Modal

  document.querySelector('#idProducto').value = ""; //limpiamos el imput para que no incluya nungun valor despues de actualizar algun registro
  document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
  document.querySelector('.btnDuplicar').classList.add("notBlock"); //Mostramos el boton de agrear fotos  

  document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
  document.querySelector('.modal-title').innerHTML = "Nuevo";
  document.querySelector('#btnText').innerHTML = "Guardar";
  document.querySelector("#formProducto").reset(); // reseteamos los campos del formulario
  document.querySelector('#opcionesCompartir').innerHTML = '';
//  document.querySelector("#divBarCode").classList.add("notBlock");
  document.querySelector('#containerGallery').classList.add("notBlock"); //Mostramos el boton de agrear fotos  
  document.querySelector("#containerImages").innerHTML = "";

  document.querySelector('#pagina_prev').innerHTML = ""; //Mostramos el boton de agrear fotos  
  document.querySelector('#pagina_poss').innerHTML = ""; //Mostramos el boton de agrear fotos  
  document.querySelector('#pagina_prox').innerHTML = ""; //Mostramos el boton de agrear fotos 
  formProducto();
}
function fntEdit(id) {
  //INSERTAMOS DATOS EN EL MODAL 
  let idProd = id;
  fetch(base_url + 'productos/getProducto/' + idProd + urlTablaFiltrada())
          .then(objData => objData.json()).then(objData => {
    if (objData.status) {
      //CAMBIAMOS ATRIBUTOS DEL MODAL
      document.querySelector("#formProducto").reset(); // reseteamos los campos del formulario
      document.querySelector('.modal-title').innerHTML = "Actualizar";
      document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
      document.querySelector(".btnDuplicar").classList.remove("notBlock");
      document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
      document.querySelector('#btnText').innerHTML = "Guardar";
//  document.querySelector("#divBarCode").classList.remove("notBlock");
      document.querySelector('#containerGallery').classList.remove("notBlock"); //Mostramos el boton de agrear fotos  


      let htmlImage = "";
      let objProducto = objData.data;
      document.querySelector('#idProducto').value = objProducto.idproducto;
      document.querySelector('#txtNombre').value = objProducto.nombre;
      tinymce.get("txtDescripcion").setContent(objProducto.descripcion); // pasa los datos del text area al tinymce
      tinymce.get("txtDetalle").setContent(objProducto.detalle); // pasa los datos del text area al tinymce
      document.querySelector('#txtMarca').value = objProducto.marca;
      document.querySelector('#txtEtiquetas').value = objProducto.etiquetas;
      //Atributos
      document.querySelector('#txtCodigo').value = objProducto.codigo;
//      objProducto.codigo > 0 ? document.querySelector("#divBarCode").classList.remove("notBlock") : '';
      document.querySelector('#listGrupoEtario').value = objProducto.age_group;
      document.querySelector('#listGenero').value = objProducto.gender;
      document.querySelector('#txtTalla').value = objProducto.size;
      document.querySelector('#txtColor').value = objProducto.color;
      document.querySelector('#txtMaterial').value = objProducto.material;
      document.querySelector('#txtEstilo').value = objProducto.style;
      document.querySelector('#txtEstampado').value = objProducto.pattern;




//      objProducto.codigo.length >= 4 ? // esta clase genera la imagen del codigo de barras y la habilita para mostrarla 
//              fntBarcode(objProducto.codigo) :
//              document.querySelector("#divBarCode").classList.add("notBlock");
      document.getElementById('txtStock').value = objProducto.stock;
      document.getElementById('list_stock_status').value = objProducto.stock_status;

      choiseListCategoria.setChoiceByValue(objProducto.categoriaid); // listCategoria
      getCategoriaFBGG(objProducto.categoriaid, objProducto.cat_facebook_id, objProducto.cat_google_id);

      choiseListProveedor.setChoiceByValue(objProducto.proveedorid); // listProveedor
      document.getElementById('listStatus').value = objProducto.status;
      let urlProd = base_url + 'tienda/producto/' + objProducto.ruta;
      let divCompartir = `
          <a class="btn btn-outline-primary m-1" href="#" onclick="window.open('http://www.facebook.com/sharer/sharer.php?display=popup&u=${urlProd}', 'facebook-share-dialog', 'toolbar=0, status=0, width = 400, height = 550'); return false;" data-tooltip="Facebook"><i class="fab fa-facebook"></i></a>
          <a class="btn btn-outline-primary m-1" href="#" onclick="window.open('https://twitter.com/intent/tweet?text=${objProducto.nombre}%0A&url=${urlProd}','twitter-share-dialog', 'toolbar=0, status=0,width=650,height=400,modal=yes');return false;"   data-tooltip="Twitter"><i class="fab fa-twitter"></i></a> 
          <a class="btn btn-outline-primary m-1" href="#" onclick="window.open('https://wa.me/?url=${objProducto.nombre}&text=%0A${urlProd}','WhatsApp-share-dialog', 'toolbar=0, status=0,width = 800,height = 1000,modal=yes');return false;"  data-tooltip="WhatsApp"><i class="fab fa-whatsapp" aria-hidden="true"></i></a> 
          <a class="btn btn-outline-primary m-1" href="#" onclick="window.open('https://t.me/share/url?url=${objProducto.nombre}%0A${urlProd}&text=${objProducto.nombre}&to=','telegram-share-dialog', 'toolbar=0, status=0,width = 500,height = 500,modal=yes');return false;" data-tooltip="Telegram"><i class="fab fa-telegram" aria-hidden="true"></i></a>`;
      document.querySelector('#opcionesCompartir').innerHTML = divCompartir;
      document.querySelector('#costoUSD').value = objProducto.costo;
      document.querySelector("#costoPeso").value = parseFloat(objProducto.costo * dolarHoy).toFixed(0);
      document.querySelector('#precioUSD').value = objProducto.precio;
      document.querySelector("#precioPeso").value = parseFloat(objProducto.precio * dolarHoy).toFixed(0);
      calcularDiferencia();
      calcularPorciento(objProducto.costo, objProducto.precio);
      document.querySelector('#ofertaDolar').value = objProducto.oferta;
      document.querySelector("#ofertaPesos").value = parseFloat(((objProducto.oferta / 10) * dolarHoy) * 10).toFixed(0);
      document.querySelector("#ofertaFechaInicio").value = objProducto.oferta_f_ini;
      document.querySelector("#ofertaFechaFin").value = objProducto.oferta_f_fin;
      //div de imagenes
      if (objProducto.images.length > 0) { // SI EL OBJETO IMAGES TIENE ALMENOS 1 ELMENTO SE REALIZA EL SIGUIENTE CODIGO 
        let objProImages = objProducto.images; // pasamos el objeto con las images a una variable, para trabajar comodamente 
        document.querySelector('[data-forden="1"]');
        for (let p = 0; p < objProImages.length; p++) { // repasamos la lista , donde generamos el html generar imagen con cada uno de los elementos del array 
          let key = objProImages[p].id;
          htmlImage += contDivImg(key, objProImages[p].img, objProImages[p].url_image, objProImages[p].url_thumb, objProImages[p].posicion);

        }
      }
      document.querySelector("#containerImages").innerHTML = htmlImage;

      //paginador
      document.querySelector('#pagina_prev').innerHTML = objProducto.prev === 0 ? '' : '<button class="page-item page-link  pull-left" onClick="fntEdit(' + objProducto.prev + ')" style="margin: auto;"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>';
      document.querySelector('#pagina_prox').innerHTML = objProducto.prox === 0 ? '' : '<button class="page-item page-link pull-right" onClick="fntEdit(' + objProducto.prox + ')" style="margin: auto;"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>';
      document.querySelector('#pagina_poss').innerHTML = '<span class="text-primary" >' + objProducto.posicion + '</span>';
      formProducto();
      ligthbbox_reload();
    } else {
      Swal.fire("Error", objData.msg, "error");
    }
  }).catch((err) => window.alert(err));

}

document.querySelector('#listCategoria').onchange = async function () {
  await getCategoriaFBGG(this.value);
};

async function getCategoriaFBGG(categoriaid, set_id_fb = 0, set_id_gg = 0) {
  let url = base_url + 'categorias/getCateFb_paraProductos/' + categoriaid;
  await fetch(url).then(objData => objData.json()).
          then(objData => {


            let list_fb = document.querySelector('#listCatFB');
            list_fb.innerHTML = objData.facebook;
            list_fb.value = set_id_fb;

            let list_google = document.querySelector('#listCatGoogle');
            list_google.innerHTML = objData.google;
            list_google.value = set_id_gg;
          });

}


function formProducto() {
// pluginsProductos();   
  $('#modalFormProducto').modal('show');
  //NUEVO PRODUCTO - EDITAR PRODUCTO
  let formProducto = document.querySelector("#formProducto"); // LA VARIABLE GUARDA LA UBICACION DEL FORMULARIO 
  formProducto.onsubmit = function (e) { //SE AGREGA LA ACCION ONSUBMIT Y ELECUTA LA FUNCION DESCRITA 
    e.preventDefault(); //EVITA QUE LA PAGINA SE RECARGE

    let strNombre = document.querySelector("#txtNombre").value; //identificara los datos del formulario pertnecen a nuevo o a actualizar
    let strCodigo = document.querySelector("#txtCodigo").value;
    let strPrecio = document.querySelector("#precioUSD").value;
    let strStock = document.querySelector("#txtStock").value;
    if (strNombre === '' || strPrecio === "" || strStock === "") {//strCodigo == "" ||
      Swal.fire("atencion", "todos los campos son obligatorios.", "error");
      return false;
    }

    tinymce.triggerSave(); //Pasamos todo lo que tenga el editor al text area que esta ocupando 

    let formData = new FormData(formProducto);
    //  divLoading.style.display = "flex";
    fetch(base_url + 'productos/setProducto', {method: "POST", body: formData})
            .then(objData => objData.json()).then(objData => {
      // divLoading.style.display = "none";
      if (objData.status) {
        Swal.fire("Productos", objData.msg, "success"); // ejecuta un mensaje 
        // document.querySelector('#idProducto').value = objData.idproducto; //Pasamos el id del producto recien creado, para sociar las fotos 
        // document.querySelector('#containerGallery').classList.remove("notBlock"); //Mostramos el boton de agrear fotos  
        //opcionesFiltroCategoria();
        fntEdit(objData.idproducto);
        filtrarTablaProductos(); //
        tableProductos.ajax.reload(null, false); // RECARGA EL DATA TABLE DESPUES DE EJECUTAR EL SWAL
      } else {
        Swal.fire("Error", objData.msg, "error");
      }
    });
  };
}

function fntVer(id) {
  let idProd = id;
  fetch(base_url + 'productos/getProducto/' + idProd + urlTablaFiltrada())
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
function fntStatus(id) {
  let boton = document.querySelector("#btnStatus" + id);
  fetch(base_url + 'productos/statusChange/' + '?id=' + id + '&intStatus=' + boton.value)
          .then(response => response.json()).then(objData => {
    if (objData.status) {
      //tableRoles.ajax.reload(null, false);  // refrescamos la tabla
      if (boton.value === 1) {
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
function fntDel(id) {
  // this.getAttribute('rl')this hace referencia al elemento al que le damos click, getAttribute trae el dato del elemento ('rl')
  Swal.fire({
    title: "Quieres Eliminar Producto?",
    text: "esta accion es irreversible",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, estoy seguro',
    cancelButtonText: 'No, Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {//si se hace click en isConfirm (elemento de Swal.fire), se ejecuta la funcion
      //document.querySelector('#idProducto').value = objData.data.idproducto;
      let ajaxUrlDel = base_url + 'productos/delProducto';
      let arrData = "id=" + id;
      let cabecera = {"Content-type": "application/x-www-form-urlencoded"};
      fetch(ajaxUrlDel, {method: "POST", headers: cabecera, body: arrData})
              .then(objData => objData.json()).then(objData => {
        if (objData.status) {
          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: objData.msg,
            showConfirmButton: false,
            timer: 1500
          });
          // Swal.fire('', objData.msg, "success");
          tableProductos.ajax.reload(null, false); // refrescamos la tabla
        } else {
          Swal.fire("Atencio!", objData.msg, "error");
        }
      }).catch(err => console.error(err));
    }
  });
}

function csvFB() {
  fetch(base_url + 'productos/getProductosCategoriaCSV/' + urlTablaFiltrada())
          .then(response => response.blob())
          .then(blob => URL.createObjectURL(blob))
          .then(uril => {
            let link = document.createElement("a");
            link.href = uril;
            link.download = "catalog_products.csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
          });
}

function fotoMovePos(accion) {//mueve las fotos de izquierda a derecha, la primera foto de la izquierda es la foto de portada
  let a = event.target.parentNode.parentNode;  //elemento que acciona el evento icono. elemto boton. elemento div botones. div contenedor padre
  let a_pos = parseInt(a.getAttribute("data-forden"));//parseInt comprueba el primer argumento, una cadena, e intenta devolver un entero de la base especificada
//  console.log('posisicion a = ' + a_pos);  //prueba de posicion
//  console.log('posisicion a esNaN = ' + isNaN(a_pos)); // prueba de posicion
  if (isNaN(a_pos) === true) { //si a_pos es igual a NaN entonces bajamos un nivel en el elemento a.
    a = a.parentNode;
    a_pos = parseInt(a.getAttribute("data-forden"));
  }
//  console.log('posisicion a esNaN validado = ' + a_pos);

  let a_id = (a.getAttribute("id"));//  console.log('div-id a = ' + a_id);

  let pos = accion === '+' ? a_pos + 1 : a_pos - 1;//  console.log('posisicion b = ' + pos);
  let b = document.querySelector(`[data-forden="${pos}"]`);//  console.log(b);

  if (b !== null) {
    // let b_pos = parseInt(b.getAttribute("data-forden"));//    console.log('posisicion b = ' + b_pos)
    let b_id = (b.getAttribute("id"));//    console.log('div-id b = ' + b_id)
    let ahtml = a.innerHTML;
    let bhtml = b.innerHTML;

    let formData = new FormData(); //se enviaran los datos como i se enviara un formulario, pero omo no hay formulario para el div se crean las variables 
    formData.append('a_id', a_id);
    formData.append('a_pos', a_pos);
    formData.append('b_id', b_id);
    formData.append('b_pos', pos);

    let img_cargando = `<img class="loading " data-mdb-animation="fade-out" src="${base_url}Assets/images/loading.svg" >`;
    a.childNodes[3].innerHTML = img_cargando;
    b.childNodes[3].innerHTML = img_cargando;


    fetch(base_url + '/Productos/setPosicionImg', {method: "POST", body: formData})
            .then(objData => objData.json())
            .then(objData => {

              a.setAttribute("id", b_id);
              a.innerHTML = bhtml;

              b.setAttribute("id", a_id);
              b.innerHTML = ahtml;

              if (a_pos === 0 || pos === 0) { // si alguna de las posiciones es igual a '0', refresca la grilla
                filtrarTablaProductos(); //
                tableProductos.ajax.reload(null, false);
              }
            });

  }

}
function fntDuplicar() {
  Swal.fire("Productos", "Copia realizada en un nuevo item", "success"); // ejecuta un mensaje 

  document.querySelector('#idProducto').value = ""; //limpiamos el imput para que no incluya nungun valor despues de actualizar algun registro
  document.querySelector('#containerGallery').classList.add("notBlock");
  document.querySelector("#containerImages").innerHTML = "";
  document.querySelector('#pagina_prev').innerHTML = ""; //oculatamos el boton 
  document.querySelector('#pagina_poss').innerHTML = ""; //oculatamos el boton 
  document.querySelector('#pagina_prox').innerHTML = ""; //oculatamos el boton 

}

//======================================================================================================

function fntVerProvee() {
  let id = document.querySelector("#listProveedor").value;
  fetch(base_url + 'productos/getProveedor/' + id)
          .then(res => res.json()).then(objData => {
    if (objData.status) {
      let estadoProveedor = objData.data.status === 1 ? //muestra el espan segun el estado 
              '<span class="bagde badge-success">Activo</option>' :
              '<span class="badge badge-danger">Inactivo</option>';
      document.querySelector('#verProvID').innerHTML = objData.data.idproveedor;
      document.querySelector('#verProvNombre').innerHTML = objData.data.nombre;
      document.querySelector('#verProvDescripcion').innerHTML = objData.data.descripcion;
      document.querySelector('#verProvDireccion').innerHTML = objData.data.direccion;
      document.querySelector('#verProvTelefono').innerHTML = objData.data.telf_local;
      document.querySelector('#verProvCelular').innerHTML = objData.data.telf_mobil;
      let divCompartir = '';
      divCompartir += objData.data.web !== '' ? `<a class="btn btn-outline-primary m-1" href="#" onclick="window.open('${objData.data.web}', 'web-share-dialog'); return false;" ><i class="fas fa-globe"></i></a>` : '';//'toolbar=0, status=0' data-tooltip="Web"
      divCompartir += objData.data.fb !== '' ? `<a class="btn btn-outline-primary m-1" href="#" onclick="window.open('${objData.data.fb}', 'facebook-share-dialog'); return false;" data-tooltip="Facebook"><i class="fab fa-facebook"></i></a> ` : '';//, 'toolbar=0, status=0'
      divCompartir += objData.data.fb !== '' ? `<a class="btn btn-outline-primary m-1" href="#" onclick="window.open('${objData.data.ig}', 'instagram-share-dialog'); return false;" data-tooltip="Instagram"><i class="fab fa-instagram"></i></a> ` : '';//, 'toolbar=0, status=0'
      //<a class="btn btn-outline-primary m-1" href="#" onclick="window.open('https://twitter.com/intent/tweet?text=${objProducto.nombre}%0A&url=${urlProd}','twitter-share-dialog', 'toolbar=0, status=0,width=650,height=400,modal=yes');return false;"   data-tooltip="Twitter"><i class="fab fa-twitter"></i></a> `:'';
      divCompartir += objData.data.telf_mobil !== '' ? `<a class="btn btn-outline-primary m-1" href="#" onclick="window.open('https://wa.me/${objData.data.telf_mobil}','WhatsApp-share-dialog');return false;"  data-tooltip="WhatsApp"><i class="fab fa-whatsapp" aria-hidden="true"></i></a> ` : '';//, 'toolbar=0, status=0,width = 800,height = 1000,modal=yes'
      divCompartir += objData.data.telf_mobil !== '' ? `<a class="btn btn-outline-primary m-1" href="#" onclick="window.open('https://t.me/${objData.data.telf_mobil}','telegram-share-dialog');return false;" data-tooltip="Telegram"><i class="fab fa-telegram" aria-hidden="true"></i></a>` : '';//, 'toolbar=0, status=0,width = 500,height = 500,modal=yes'
      document.querySelector('#verProvLinks').innerHTML = divCompartir;
      document.querySelector('#verProvFecha').innerHTML = objData.data.fecha;
      document.querySelector('#verProvEstado').innerHTML = estadoProveedor;
      document.querySelector('#verProvImgProveedor').innerHTML = '<img src = "' + objData.data.url_img + '">';

      objData.data.prev === 0 ? document.querySelector('#pagina_prev').innerHTML = '' : document.querySelector('#pagina_prev').innerHTML = '<button class="page-item page-link pull-left" onClick="fntEdit(' + objData.data.prev + ')" >«</button>';
      objData.data.prox === 0 ? document.querySelector('#pagina_prox').innerHTML = '' : document.querySelector('#pagina_prox').innerHTML = '<button class="page-item page-link pull-right" onClick="fntEdit(' + objData.data.prox + ')" >»</button>';
      document.querySelector('#pagina_poss').innerHTML = '<span class="text-primary" >' + objData.data.posicion + '</span>';

      $('#modalVerProveedor').modal('show');
    } else {
      Swal.fire("Error", objData.msg, "error");
    }
  }).catch(err => console.error(err));
}

