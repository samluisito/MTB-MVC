//document.write('<script type="text/javascript" src="' + media + '/js/plugins/JsBarcode.all.min.js"></script>');

let tableEntradas;

let divLoading = document.querySelector("#divLoading");
let tinymece;

document.addEventListener('DOMContentLoaded', function () {
  tablaEntradas();
  pluginsEntradas();

});

//Subir imagen 
function fntInputFile() {
  let inputUploadfile = document.querySelectorAll(".inputUploadfile");// esta variable hace referencia a la clase del imput que tenemos en l formularo, el cual carga la imagen
  inputUploadfile.forEach(function (inputUploadfile) {// recorremos todos los elementos que tenga esta clase, 
    inputUploadfile.addEventListener('change', function () { // a esta variable, le asignamos el evento change, que ejecutara una funcion al haber cambios ,

      let idEntrada = document.querySelector("#idEntrada").value; // este elemento toma el id del entrada previamente cargado
      let nombreEntrada = document.querySelector("#txtTitulo").value; // este elemento toma el Nombre del entrada previamente cargado
      let parentId = this.parentNode.getAttribute("id"); // con parentNode se refiere al elemento padre de this que hace referebcia al elemento (input) al que le damos clic (entonces el elemento padre, es el div con el id=key) y con getAttribute se hace referencia al id de este elemento
      let idFile = this.getAttribute("id"); // captura el id del elemento this, que es el input al que le damos clic     
      let uploadFoto = document.querySelector("#" + idFile).value; //captura el valor del elemento con el id previamente obtenido 
      let fileimg = document.querySelector("#" + idFile).files; // elemento captura el archivo del input con el id correspondiente
      let prevImg = document.querySelector("#" + parentId + " .prevImage"); //obtiene el div donde se encuentra la imagen 
      let nav = window.URL || window.webkitURL;

      if (uploadFoto != '') { //validamos si el valor de uploadFoto es diferente de vacio
        let type = fileimg[0].type;
        let name = fileimg[0].name;
        if (type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png') {
          prevImg.innerHTML = "Archivo no válido";
          uploadFoto.value = "";
          return false;
        } else {

          let objeto_url = nav.createObjectURL(this.files[0]);
          prevImg.innerHTML = `<img class="loading" src="${base_url}Assets/images/loading.svg" >`;// inserta una imagen de carga mientras llega la respuesta del servidor 

          let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
          let ajaxUrl = base_url + '/ABlog/setImage';
          let formData = new FormData(); //se enviaran los datos como i se enviara un formulario, pero omo no hay formulario para el div se crean las variables 

          formData.append('identrada', idEntrada);
          formData.append('nombreEntrada', nombreEntrada);
          formData.append("foto", this.files[0]);
          request.open("POST", ajaxUrl, true);
          request.send(formData);
          request.onreadystatechange = function () {
            if (request.readyState != 4)
              return;
            if (request.status == 200) {
              let objData = JSON.parse(request.responseText);
              if (objData.status) {
                prevImg.innerHTML = `<img src="${objeto_url}">`; // retira la imagen de carga e inserta la imagen desde el servidor 

                document.querySelector("#" + parentId + " .btnDeleteImage").setAttribute("imgname", objData.imgname);
                document.querySelector("#" + parentId + " .btnUploadfile").classList.add("notBlock");
                document.querySelector("#" + parentId + " .btnDeleteImage").classList.remove("notBlock");

              } else {
                swal("Error", objData.msg, "error");
              }
            }
          }

        }
      }

    });
  });
}

function fntDelItem(element) {
  let nameImg = document.querySelector(element + ' .btnDeleteImage').getAttribute("imgname"); //identificamos el elmento por medio del kay y de la clase document.querySelector(element+' .btnDeleteImage') y con getAttribute("imgname") capturamos el valor del atributo identificado
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrl = base_url + '/ABlog/delFile';
  let idEntrada = document.querySelector('#idEntrada').value;

  let formData = new FormData(); //se enviaran los datos como i se enviara un formulario, pero omo no hay formulario para el div se crean las variables 
  formData.append('identrada', idEntrada);
  formData.append('file', nameImg);

  request.open("POST", ajaxUrl, true);
  request.send(formData);

  request.onreadystatechange = function () {
    if (request.readyState != 4)
      return;
    if (request.status == 200) {
      let objData = JSON.parse(request.responseText);


      if (objData.status) {
//swal("Error", objData.msg, "success");
        let itemRemove = document.querySelector(element);//selecciona el elemnto 
        itemRemove.parentNode.removeChild(itemRemove);// con parentNode selecciona al padre de itemRemove y con removeChild elimina al hijo. (se suicida por medio del padre) 


      } else {
        swal("Error", objData.msg, "error");
      }
    }

  }

}

function pluginsEntradas() {

//TINYMCE EDITOR DE TECTO ENRIQUECIDO   contextmenu    textcolor   
  tinymce.init({
    selector: '#txtTexto',
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
      'image', // permite insertar una imagen en el área editable. El complemento también agrega un botón en la barra de herramientas y un Insert/edit image elemento de menú debajo del Insert menú.
      'imagetools', // agrega una barra de herramientas de edición contextual a las imágenes en el editor. Si la barra de herramientas no aparece al hacer clic en la imagen, es posible que deba habilitar imagetools_cors_hostso imagetools_proxy
      'insertdatetime', // permite al usuario insertar fácilmente la fecha y / o la hora actual en el punto de inserción del cursor.
      'link', // permite vincular enlaces externos, como URL de sitios web, al texto seleccionado en su documento.
      'media', // agrega la capacidad para agregar elementos de audio y video HTML5 
      'nonbreaking', //  agrega un botón para insertar entidades de espacio que no se rompan &nbsp;
      'pagebreak', //  permite insertar saltos de página
      'paste', //  filtrará / limpiará el contenido pegado desde MsWord y agrega un elemento de menú Pasteastext en el menú Edit Paste.
      'preview', //  agrega un botón de vista previa a la barra de herramientas.
      'print', //  agrega un botón de impresión a la barra de herramientas
      //'searchreplace', //  agrega cuadros de diálogo de búsqueda / reemplazo
      //  'spellchecker', //  habilita la función de revisión ortográfica spellchecker composer 
      'table', //  agrega funcionalidad de administración de tablas
      // 'template', //  agrega soporte para plantillas personalizadas. https://www.tiny.cloud/docs/plugins/opensource/template/#templates
      // 'toc', //  generará una tabla de contenido básica 
      'visualblocks ', //  permite al usuario ver elementos a nivel de bloque 
      'visualchars ', //  agrega la capacidad de ver caracteres invisibles como se &nbsp;
      'wordcount ', //  agrega la capacidad de ver caracteres invisibles como se &nbsp;
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

            ' blockquote |' + // Aplica el formato de cotización de bloque al elemento de nivel de bloque actual.

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
            ' paste pastetext |' +
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
    , height: '400'
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

// selectpicker selector de categorias
  if (document.querySelector('#listCategoria')) {
    let ajaxUrl = base_url + 'categorias/getSelectCategorias/blog';
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState == 4 && request.status == 200) {//pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario
        document.querySelector('#listCategoria').innerHTML = request.responseText;
        $('#listCategoria').selectpicker('render');
      }
    }
  }

//Galeria de imagenes
  if (document.querySelector('.btnAddImage')) {
    let btnAddImage = document.querySelector('.btnAddImage');
    btnAddImage.onclick = function (e) {
      let key = Date.now();
      let newElement = document.createElement("div");
      newElement.id = "div" + key;
      newElement.innerHTML = `<div class="prevImage " >
                                   </div>
                                   <input type="file" name="foto" id="img${key}" class="inputUploadfile">
                                   <label for="img${key}" class="btnUploadfile"><i class="fa fa-upload "></i></label>
                                   <button class="btnDeleteImage notBlock" type="button" onclick="fntDelItem('#div${key}')"><i class="fa fa-trash"></i></button>`;
      document.querySelector("#containerImages").appendChild(newElement);
      document.querySelector('#div' + key + " .btnUploadfile").click(); //abre el buscador de imagener para el imputid recien creado
      fntInputFile();
    }
  }
}

function tablaEntradas() {
  tableEntradas = $('#tableEntradas').DataTable({

    "destroy": false, //destuye la tabla existente, 
    "stateSave": true, //  guardar estado: restaurar el estado de la tabla en la recarga de la página
    "responsive": true, //tabla responsive, adaptablle a la ventana 
    "paging": true, //paginado de la tabla

    // "ServerSide": false,//habilita le procesamiento de datos del lado del servidor (util para procesamiento de mas de 50.000 registros)
    "search": true,
    "searching": true, //activa - desactiva el cuadro de busqueda 
    "Processing": true,
    "info": true,

    "paging": true, //paginado de la tabla
    "iDisplayLength": 25, //registros iniciales mostrados
    "ordering": true, "order": [[0, "desc"]], //ordenar y ordenar por 

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
    "ajax": {url: base_url + "ABlog/getEntradas", dataSrc: "",
    },
    "columns": [
      {"data": "img_entrada"},
      {"data": "titulo"},
      {"data": "descripcion"},
      {"data": "autor"},
      {"data": "categoria"},
      {"data": "options", "searchable": false}
    ],
    "columnDefs": [
      {'className': "text-center", "targets": [0]},
      {'className': "text-rigth", "targets": [2]},
      {'className': "text-center", "targets": [5]}
    ],
    dom: 'lBfrtilp',

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
        text: 'CSV',
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

function nvaEntrada() {
//Nombre del Formulario Modal

  document.querySelector('#idEntrada').value = ""; //limpiamos el imput para que no incluya nungun valor despues de actualizar algun registro
  document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
  document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
  document.querySelector('#titleModal').innerHTML = "Nueva Entrada";
  document.querySelector('#btnText').innerHTML = "Guardar";
  document.querySelector("#formEntrada").reset(); // reseteamos los campos del formulario

//    document.querySelector("#divBarCode").classList.add("notBlock");
  document.querySelector('#containerGallery').classList.add("notBlock"); //Mostramos el boton de agrear fotos  
  document.querySelector("#containerImages").innerHTML = "";
  document.querySelector('#pagina_prev').innerHTML = '';
  document.querySelector('#pagina_prox').innerHTML = '';
  document.querySelector('#pagina_poss').innerHTML = '';
  //ocultamos el Status ya que tendra un valor de 1 = habilitado
  //document.querySelector('#listStatusLabel').hidden = true;
  //  document.querySelector('#listStatus').hidden = true;
  // document.querySelector('#formEntrada').reset(); // reseteamos los campos del formulario

  formEntrada();
}
function fntEdit(id) {
//CAMBIAMOS ATRIBUTOS DEL MODAL
//con document document.querySelector nos referimos al elemento con el id o la clase que pasamos como parametro
//innerHTML le indicamos que reemplace el texto existente por el siguiente
//con classList.replace indicamos que reemplace la clase de estilos A por clase de estilos B
  document.querySelector('#titleModal').innerHTML = "Actualizar Entrada";
  document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
  document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
  document.querySelector('#btnText').innerHTML = "Actualizar";

  document.querySelector('#containerGallery').classList.remove("notBlock"); //Mostramos el boton de agrear fotos  

  // document.querySelector('#listStatusLabel').hidden = false;      //ocultamos el Status ya que tendra un valor de 1 = habilitado
  //  document.querySelector('#listStatus').hidden = false;
  //INSERTAMOS DATOS EN EL MODAL 
  // let idEntrada = id;

  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrlUserId = base_url + 'ABlog/getEntrada/' + id;
  request.open("GET", ajaxUrlUserId, true);
  request.send();
  request.onreadystatechange = function () { // se valida que hay una respuesta del servidor 
    if (request.readyState == 4 && request.status == 200) {

      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        console.log(objData);

        let htmlImage = "";
        let objEntrada = objData.data;
        //let status = objEntrada.status == 1 ? //muestra el espan segun el estado 
        //      '<span class="bagde badge-success">Activo</option>' : '<span class="badge badge-danger">Inactivo</option>';

        document.querySelector('#idEntrada').value = objEntrada.identrada;
        document.querySelector('#txtTitulo').value = objEntrada.titulo;
        document.querySelector('#txtDescripcion').value = objEntrada.descripcion;
        document.querySelector('#txtTexto').value = objEntrada.txt_entrada;
        document.querySelector('#txtTags').value = objEntrada.tags;
        tinymce.activeEditor.setContent(objEntrada.txt_entrada); // pasa los datos del text area al tinymce

        document.querySelector('#listCategoria').value = objEntrada.categoriaid;
        $('#listCategoria').selectpicker('render'); // renderizamos la lista 

        document.querySelector('#listStatus').value = objEntrada.status;
        $('#listStatus').selectpicker('render'); // renderizamos la lista 

        objData.data.prev == 0 ? document.querySelector('#pagina_prev').innerHTML = '' : document.querySelector('#pagina_prev').innerHTML = '<button class="page-item page-link pull-left" onClick="fntEdit(' + objEntrada.prev + ')" >«</button>';
        objData.data.prox == 0 ? document.querySelector('#pagina_prox').innerHTML = '' : document.querySelector('#pagina_prox').innerHTML = '<button class="page-item page-link pull-right" onClick="fntEdit(' + objEntrada.prox + ')" >«</button>';
        document.querySelector('#pagina_poss').innerHTML = '<span class="text-primary" >' + objEntrada.posicion + '</span>';



        if (objEntrada.images.length > 0) { // SI EL OBJETO IMAGES TIENE ALMENOS 1 ELMENTO SE REALIZA EL SIGUIENTE CODIGO 
          let objProImages = objEntrada.images; // pasamos el objeto con las images a una variable, para trabajar comodamente 

          for (let p = 0; p < objProImages.length; p++) { // repasamos la lista , donde generamos el html generar imagen con cada uno de los elementos del array 
            let key = Date.now() + p;
            htmlImage += `<div id="div${key}">
                                        <div class="prevImage">
                                            <img src="${objProImages[p].url_image}"></img>
                                        </div>

                                        <input type="file" name="foto" id="img${key}" class="inputUploadfile">
                                        <label for="img${key}" class="btnUploadfile notBlock"><i class="fa fa-upload "></i></label>

                                        <button class="btnDeleteImage" type="button" onclick="fntDelItem('#div${key}')" imgname="${objProImages[p].img}">
                                        <i class="fa fa-trash"></i>
                                        </button>
                                    </div>`;
          }
        }



        document.querySelector("#containerImages").innerHTML = htmlImage;
        document.querySelector("#containerGallery").classList.remove("notBlock");

        //$('#modalFormEntrada').modal('show');
        formEntrada()

      } else {
        swal("Error", objData.msg, "error");
      }
    }
  }

}

function formEntrada() {
// pluginsEntradas();   
  $('#modalFormEntrada').modal('show');
  //NUEVA ENTRADA - EDITAR ENTRADA
  let formEntrada = document.querySelector("#formEntrada"); // LA VARIABLE GUARDA LA UBICACION DEL FORMULARIO 
  formEntrada.onsubmit = function (e) { //SE AGREGA LA ACCION ONSUBMIT Y ELECUTA LA FUNCION DESCRITA 
    e.preventDefault(); //EVITA QUE LA PAGINA SE RECARGE

    let txtTitulo = document.querySelector("#txtTitulo").value; //identificara los datos del formulario pertnecen a nuevo o a actualizar
    let txtDescripcion = document.querySelector("#txtDescripcion").value; //identificara los datos del formulario pertnecen a nuevo o a actualizar
    let txtTexto = document.querySelector("#txtTexto").value; //identificara los datos del formulario pertnecen a nuevo o a actualizar
    if (txtTitulo == "" || txtDescripcion == "" || txtTexto == "") {
      swal("atencion", "todos los campos son obligatorios.", "error");
      return false;
    }

    //divLoading.style.display = "flex";
    tinymce.triggerSave(); //Pasamos todo lo que tenga el editor al text area que esta ocupando 

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + 'ABlog/setEntrada';
    let formData = new FormData(formEntrada);
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {

      divLoading.style.display = "none";

      if (request.readyState == 4 && request.status == 200) {
        let objData = JSON.parse(request.responseText); //parseamos el array que viene del lado server en formato jadon  

        if (objData.status) {
          //$('#modalFormEntrada').modal("hide");
          //  removePhoto();
          // formEntrada.reset();

          //libreria alerta 
          swal("Entradas", objData.msg, "success"); // ejecuta un mensaje 

          document.querySelector('#idEntrada').value = objData.identrada; //Pasamos el id del entrada recien creado, para sociar las fotos 
          document.querySelector('#containerGallery').classList.remove("notBlock"); //Mostramos el boton de agrear fotos  

          tableEntradas.ajax.reload(null, false); // RECARGA EL DATA TABLE DESPUES DE EJECUTAR EL SWAL

        } else {
          swal("Error", objData.msg, "error");
        }
      }
    }
  }
}

function fntVer(id) {
//$('#modalVerEntrada').modal('show');

  let idProd = id;
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrlUserId = base_url + 'entradas/getEntrada/' + idProd;
  request.open("GET", ajaxUrlUserId, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {

      let objData = JSON.parse(request.responseText);
      if (objData.status) {

        let htmlImage = "";
        let objEntrada = objData.data;
        let status = objEntrada.status == 1 ? //muestra el espan segun el estado 
                '<span class="bagde badge-success">Activo</option>' : '<span class="badge badge-danger">Inactivo</option>';
        document.querySelector('#celCodigo').innerHTML = objEntrada.codigo;
        document.querySelector('#celNombre').innerHTML = objEntrada.nombre;
        document.querySelector('#celPrecio').innerHTML = objEntrada.precio;
        document.querySelector('#celStock').innerHTML = objEntrada.stock;
        document.querySelector('#celCategoria').innerHTML = objEntrada.categoria;
        document.querySelector('#celEstado').innerHTML = objEntrada.status;
        document.querySelector('#celDescripcion').innerHTML = objEntrada.descripcion;
        //document.querySelector('#celImgCategoria').innerHTML = '<img src = "' + objData.data.url_img + '">';

        if (objEntrada.images.length > 0) {
          let objProImages = objEntrada.images;
          for (let p = 0; p < objProImages.length; p++) {
            htmlImage += `<img src = "${objProImages[p].url_image}"></img>`;
          }

        }
        document.querySelector("#celFotos").innerHTML = htmlImage;

        $('#modalVerEntrada').modal('show')

      } else {
        swal("Error", objData.msg, "error");
      }
    }
  }

}

function fntDel(id) {

  let idEntrada = id; // this.getAttribute('rl')this hace referencia al elemento al que le damos click, getAttribute trae el dato del elemento ('rl')

  swal({title: "Eliminar entrada",
    text: "Quieres eliminar el Entrada?",
    type: "warning",
    showCancelButton: true,
    showConfirmButton: true,
    // confirmButtonText: "Si, Eliminar",
    cancelButtonText: "No, Cancelar",
    closeOnConfirm: false,
    coseOnCancel: true
  }, function (isConfirm) { //si se hace click en isConfirm (elemento de swal), se ejecuta la funcion 

    if (isConfirm) {

      //document.querySelector('#idEntrada').value = objData.data.identrada;
      let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
      let ajaxUrlDel = base_url + 'entradas/delEntrada/';
      let arrData = "identrada=" + idEntrada;
      request.open("POST", ajaxUrlDel, true);
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      request.send(arrData);
      request.onreadystatechange = function () {

        if (request.readyState == 4 && request.status == 200) {
          //pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario
          let objData = JSON.parse(request.responseText);
          if (objData.status) {
            swal("eliminar!", objData.msg, "success");
            tableEntradas.ajax.reload(null, false); // refrescamos la tabla
            // refrescamos el nuevo entrada   nvoEntrada() 
          } else {
            swal("Atencio!", objData.msg, "error");
          }
        }
      }
    }
  });
}