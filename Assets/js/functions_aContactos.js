var tableContactos = "";
document.addEventListener('DOMContentLoaded', function () {
  //al momento de cargar el documento ejecutara esta funcion y en esta funcion tendremos todo el script de datatable
  if (document.querySelector("#tableContactos")) {
    dataTablaContactos();
  }

}, false);

function dataTablaContactos() {

  tableContactos = $('#tableContactos').DataTable({
    "destroy": false,

    "stateSave": true, //  guardar estado: restaurar el estado de la tabla en la recarga de la página
    // "ServerSide": false,//habilita le procesamiento de datos del lado del servidor (util para procesamiento de mas de 50.000 registros)
    "responsive": true, //tabla responsive, adaptablle a la ventana 

    "search": true,
    "searching": true, //activa - desactiva el cuadro de busqueda 
    "Processing": true,
    "info": true,

    "paging": true, //paginado de la tabla
    "iDisplayLength": 25, //registros iniciales mostrados
    "ordering": true, "order": [[0, "desc"]], //ordenar y ordenar por 
    // "scrollX": "200px", //pixeles en lo que muestra el escroll 
    //   "scrollCollapse": true, //  muestra el acroll al colapsar

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
    "ajax": {url: base_url + "aContactos/getContactos", dataSrc: "", },
    "columns": [
      {"data": "idcontacto", "visible": false, "searchable": false, "all": true},
      {"data": "origen"},
      {"data": "nombre"},
      {"data": "apellido"},
      {"data": "telefono"},
      {"data": "email"},
      {"data": "localidad", "searchable": false, "all": true},
      {"data": "fechaRegistro", "searchable": false, "all": true},
      {"data": "options", "searchable": false, "all": true}
    ],

    dom: '<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4"<"dt-buttons btn-group flex-wrap"B>><"col-sm-12 col-md-4"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
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

function fntVerContact(id) {
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrl = base_url + 'AContactos/getCto/' + id;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {

      let objData = JSON.parse(request.responseText);
      if (objData.status) {

        document.querySelector('#verNombre').innerHTML = objData.data.nombre;
        document.querySelector('#verApellido').innerHTML = objData.data.apellido;
        document.querySelector('#verTelefono').innerHTML = objData.data.telefono;
        document.querySelector('#verEmail').innerHTML = objData.data.email;
        document.querySelector('#verLocalidad').innerHTML = objData.data.localidad;
        document.querySelector('#verFecha').innerHTML = objData.data.fechaRegistro;
        document.querySelector('#verMensaje').innerHTML = objData.data.mensaje;
        $('#modalVerCto').modal('show')
      } else {
        swal("Error", objData.msg, "error");
      }
    }
  }
}


