let tableClientes = "";
document.addEventListener('DOMContentLoaded', function () {

  //al momento de cargar el documento ejecutara esta funcion y en esta funcion tendremos todo el script de datatable
  if (document.querySelector("#tableClientes")) {
    dataTablaClientes();
  }

}, false);
function setCliente() {
  let formUsuario = document.querySelector("#formCliente");
  formUsuario.onsubmit = function (e) {
    e.preventDefault();
    //identificara los datos del formulario pertnecen a nuevo o a actualizar

    let strIdentificacion = document.querySelector("#txtIdentificacion").value;
    let strNombre = document.querySelector("#txtNombre").value;
    let strApellido = document.querySelector("#txtApellido").value;
    let strEmail = document.querySelector("#txtEmail").value;
    let intTelefono = document.querySelector("#txtTelefono").value;

    let intTipoUsuario = document.querySelector("#txtNit").value;
    let intStatus = document.querySelector("#txtNombreFiscal").value;
    let Password = document.querySelector("#txtDirFiscal").value;
    if (strIdentificacion === '' || strNombre === '' || strApellido === '' || strEmail === '' || intTelefono === '' || intTipoUsuario === '')
    {
      Swal.fire("atencion", "todos los campos son obligatorios.", "error");
      return false;
    }

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + 'Clientes/setCliente';
    let formData = new FormData(formUsuario);
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function ()
    {
      if (request.readyState === 4 && request.status === 200)
      {
        let objData = JSON.parse(request.responseText);
        if (objData.status)
        {
          $('#modalFormCliente').modal("hide");
          formUsuario.reset();
          //libreria alerta 
          Swal.fire("Usuarios", objData.msg, "success");
          //RECARGA EL DATA TABLE DESPUES DE EJECUTAR EL SWAL
          tableClientes.ajax.reload(null, false);  // refrescamos la tabla

        } else {
          Swal.fire("Error", objData.msg, "error");
        }
      }
    };
  };
}
function ftnNvoCte() {
//NUEVO USUARIO
  document.getElementById('idUsuario').value = ""; //limpiamos el imput para que no incluya nungun valor despues de actualizar algun registro
  document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
  document.getElementById('btnActionForm').classList.replace("btn-info", "btn-primary");
  document.getElementById('btnText').innerHTML = "Guardar";
  document.getElementById('titleModal').innerHTML = "Nuevo Cliente";
  document.getElementById('formCliente').reset(); // reseteamos los campos del formulario


  // muestra el modal que se este pasando como parametro en la funcion
  $('#modalFormCliente').modal('show');
  setCliente();
}
function fntEdit(idPersona) {

  document.getElementById('titleModal').innerHTML = "Actualizar Cliente";
  document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
  document.getElementById('btnActionForm').classList.replace("btn-primary", "btn-info");
  document.getElementById('btnText').innerHTML = "Actualizar";


  let idUser = idPersona;
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrlUserId = base_url + 'clientes/getCte/' + idUser;
  request.open("GET", ajaxUrlUserId, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {

      let objData = JSON.parse(request.responseText);
      if (objData.status) {

        document.getElementById('idUsuario').value = objData.data.idpersona;
        document.getElementById('txtIdentificacion').value = objData.data.identificacion;
        document.getElementById('txtNombre').value = objData.data.nombres;
        document.getElementById('txtApellido').value = objData.data.apellidos;
        document.getElementById('txtTelefono').value = objData.data.telefono;
        document.getElementById('txtEmail').value = objData.data.email_user;
        document.getElementById('txtNit').value = objData.data.nit;
        document.getElementById('txtNombreFiscal').value = objData.data.nombrefiscal;
        document.getElementById('txtDirFiscal').value = objData.data.direccionfiscal;

        /*
         $('#listRolid').selectpicker('render');
         if (objData.data.status == 1) {
         document.getElementById('listStatus').value = 1;
         } else {
         document.getElementById('listStatus').value = 2;
         }
         $('#listStatus').selectpicker('render');
         
         */
        $('#modalFormCliente').modal('show');
        setCliente();
      } else {
        Swal.fire("Error", objData.msg, "error");
      }
    }
  }

}


function dataTablaClientes() {

  tableClientes = $('#tableClientes').DataTable({
    "destroy": true,

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
        "sSortAscending": ": Actilet para ordenar la columna de manera ascendente",
        "sSortDescending": ": Actilet para ordenar la columna de manera descendente"
      },
      "buttons": {
        "copy": "Copiar",
        "colvis": "Visibilidad"
      }

    },
    "ajax": {url: base_url + "clientes/getClientes", dataSrc: "", },
    "columns": [
      {"data": "idpersona", "visible": false, "searchable": false, "all": true},
      {"data": "identificacion"},
      {"data": "nombres"},
      {"data": "apellidos"},
      {"data": "telefono"},
      {"data": "email_user"},
      {"data": "status", "searchable": false, "all": true},
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

function fntVer(idPersona) {

  let id = idPersona;
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrlUserId = base_url + 'clientes/getCte/' + id;
  request.open("GET", ajaxUrlUserId, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {

      let objData = JSON.parse(request.responseText);
      if (objData.status) {

        let estadoCliente = objData.data.status === 1 ?
                '<span class="bagde badge-success">Activo</option>' :
                '<span class="badge badge-danger">Inactivo</option>';

        document.getElementById('verIdentificacion').innerHTML = objData.data.identificacion;
        document.getElementById('verNombre').innerHTML = objData.data.nombres;
        document.getElementById('verApellido').innerHTML = objData.data.apellidos;
        document.getElementById('verTelefono').innerHTML = objData.data.telefono;
        document.getElementById('verEmail').innerHTML = objData.data.email_user;
        document.getElementById('verNit').innerHTML = objData.data.nit;
        document.getElementById('verNombreFiscal').innerHTML = objData.data.nombrefiscal;
        document.getElementById('verDirFiscal').innerHTML = objData.data.direccionfiscal;
        document.getElementById('verEstado').innerHTML = estadoCliente;
        document.getElementById('verFechReg').innerHTML = objData.data.fechaRegistro;
        $('#modalVerCte').modal('show')
      } else {
        Swal.fire("Error", objData.msg, "error");
      }
    }
  }
  /* });
   });*/
}

function fntDel(idPersona) {

  let idUsuario = idPersona; //this.getAttribute('us');      this hace referencia al elemento al que le damos click, getAttribute trae el dato del elemento ('rl')

  Swal.fire({title: "Eliminar Usuario",
    text: "Quieres eliminar el Rol?",
    type: "warning",
    showCancelButton: true,
    showConfirmButton: true,
    confirmButtonText: "Si, Eliminar",
    cancelButtonText: "No, Cancelar",
    closeOnConfirm: false,
    coseOnCancel: true},
          function (isConfirm) {

            if (isConfirm) {//si se hace click en isConfirm (elemento de Swal.fire), se ejecuta la funcion 

              let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
              let ajaxUrlDelRol = base_url + 'clientes/delCliente/';
              let arrData = "idUsuario=" + idUsuario;
              request.open("POST", ajaxUrlDelRol, true);
              request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
              request.send(arrData);
              request.onreadystatechange = function () {

                if (request.readyState === 4 && request.status === 200) {
                  //pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario
                  let objData = JSON.parse(request.responseText);
                  if (objData.status) {
                    Swal.fire("eliminar!", objData.msg, "success");
                    tableClientes.ajax.reload(null, false);  // refrescamos la tabla
                    ;
                  } else {
                    Swal.fire("Atencio!", objData.msg, "error");
                  }
                }
              }
            }
          });

}

function fntStatus(idpag) {

  let id = idpag; // this.getAttribute('rl')this hace referencia al elemento al que le damos click, getAttribute trae el dato del elemento ('rl')

  let btnStatusRol = "#btnStatusRol" + id
  let intStatus = document.querySelector("#btnStatusRol" + id).value; //identificara los datos del formulario pertnecen a nuevo o a actualizar

  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrl = base_url + 'clientes/status/' + '?id=' + id + '&intStatus=' + intStatus;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {

    if (request.readyState === 4 && request.status === 200) {
      //pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        // Swal.fire("ESTADO", objData.msg, "info"); //mostramos el resultado de la operacion

        tableClientes.ajax.reload(null, false);  // refrescamos la tabla
        // nvoRol()  //refrescamos el nuevo rol

      } else {
        swal.fire("Atencio!", objData.msg, "error");
      }
    }
  };

}

