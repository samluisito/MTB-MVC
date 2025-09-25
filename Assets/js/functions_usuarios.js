
document.addEventListener('DOMContentLoaded', function () {
  //al momento de cargar el documento ejecutara esta funcion y en esta funcion tendremos todo el script de datatable
  tablaUsuarios();
  fntRolesUsuarios();
}, true);
let tableUsuarios;

function fntRolesUsuarios() {
  let ajaxUrl = base_url + 'roles/getSelectRolesTipo';
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      document.querySelector('#listRolid').innerHTML = request.responseText;
      document.querySelector('#listRolid').value = 1;
    }
  };
}

function tablaUsuarios() {
  if (document.querySelector("#tableUsuarios")) {
    tableUsuarios = $('#tableUsuarios').DataTable({
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
          extend: 'pdfHtml5',
          text: 'PDF',
          ttleAtter: 'Exportar a Excel',
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

      ],
      "destroy": true,
      "aServerSide": true, //presenta la primera vista y carga el 
      "responsive": true,
      //"scrollY": "500px",
      "scrollCollapse": true,
      "bProcessing": true,
      "iDisplayLength": 10,
      "order": [[0, "asc"]],
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
      "ajax": {url: base_url + "usuarios/getUsuarios", dataSrc: "",
      },
      "columns": [
        {"data": "idpersona"},
        {"data": "identificacion", "visible": false, "searchable": false, "all": true},
        {"data": "nombres"},
        {"data": "apellidos"},
        {"data": "telefono"},
        {"data": "email_user"},
        {"data": "nombrerol"},
        {"data": "status"},
        {"data": "options", "searchable": false, "all": true}
      ]
    });
  }
}

function setForm() {
//NUEVO USUARIO
  $('#modalFormUsuario').modal('show');
  let formUsuario = document.querySelector("#formUsuario");
  formUsuario.onsubmit = function (e) {
    console.log('linea: 120');
    e.preventDefault();
    let strIdentificacion = document.querySelector("#txtIdentificacion").value;
    let strNombre = document.querySelector("#txtNombre").value;
    let strApellido = document.querySelector("#txtApellido").value;
    let strEmail = document.querySelector("#txtEmail").value;
    let intTelefono = document.querySelector("#txtTelefono").value;
    let intTipoUsuario = document.querySelector("#listRolid").value;
    let Password = document.querySelector("#txtPassword").value;
    if (strIdentificacion === '' || strNombre === '' || strApellido === '' || strEmail === '' || intTelefono === '' || intTipoUsuario === '') {
      swal.fire("atencion", "todos los campos son obligatorios.", "error");
      return false;
    }
    let formData = new FormData(document.querySelector("#formUsuario"));
    fetch(ajaxUrl = base_url + 'usuarios/setUsuario',
            {method: "POST",
             // headers: {"Content-type": "application/x-www-form-urlencoded"},
              body: formData})
            .then(objData => objData.json()).then(objData => {
      if (objData.status) {
        $('#modalFormUsuario').modal("hide");

        swal.fire("Usuarios", objData.msg, "success");
        tableUsuarios.ajax.reload(null, false);  // refrescamos la tabla
      } else {
        swal.fire("Error", objData.msg, "error");
      }
    });
  };
}

function nuevo() {
//Nombre del Formulario Modalconsole.log($nombreModal);

  document.querySelector('#idUsuario').value = ""; //limpiamos el imput para que no incluya nungun valor despues de actualizar algun registro
  document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
  document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
  document.querySelector('#titleModal').innerHTML = "Nuevo Usuario";
  document.querySelector('#formUsuario').reset(); // reseteamos los campos del formulario
  setForm();
}
function fntEdit(idPersona) {

  document.querySelector('#titleModal').innerHTML = "Actualizar Usuario";
  document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
  document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");

  fetch(base_url + 'usuarios/getUser/' + idPersona)
          .then(objData => objData.json()).then(objData => {
    if (objData.status) {
      document.querySelector('#idUsuario').value = objData.data.idpersona;
      document.querySelector('#txtIdentificacion').value = objData.data.identificacion;
      document.querySelector('#txtNombre').value = objData.data.nombres;
      document.querySelector('#txtApellido').value = objData.data.apellidos;
      document.querySelector('#txtTelefono').value = objData.data.telefono;
      document.querySelector('#txtEmail').value = objData.data.email_user;
      document.querySelector('#listRolid').value = objData.data.idrol;
      setForm();
    } else {
      swal.fire("Error", objData.msg, "error");
    }
  });
}

function fntVer(idPersona) {
  fetch(base_url + 'usuarios/getUser/' + idPersona)
          .then(objData => objData.json()).then(objData => {
    if (objData.status) {
      let estadoUsuario = objData.data.status === 1 ?
              '<span class="bagde badge-success">Activo</option>' :
              '<span class="badge badge-danger">Inactivo</option>';
      document.querySelector('#verIdentificacion').innerHTML = objData.data.identificacion;
      document.querySelector('#verNombre').innerHTML = objData.data.nombres;
      document.querySelector('#verApellido').innerHTML = objData.data.apellidos;
      document.querySelector('#verTelefono').innerHTML = objData.data.telefono;
      document.querySelector('#verEmail').innerHTML = objData.data.email_user;
      document.querySelector('#verTpoUser').innerHTML = objData.data.nombrerol;
      document.querySelector('#verEstado').innerHTML = estadoUsuario;
      document.querySelector('#verFechReg').innerHTML = objData.data.fechaRegistro;
      $('#modalVerUsuario').modal('show')
    } else {
      swal.fire("Error", objData.msg, "error");
    }
  });

}




/*
 function fntEdit2() {
 $('#modalFormPerfil').modal('show');
 if (document.querySelector("#formPerfil")) {
 //$("#txtPasswordConfirm").on("focusout", function() { if ($(this).val() === "") { $(this).attr("type", "text"); } }); 
 let formPerfil = document.querySelector("#formPerfil");
 formPerfil.onsubmit = function (e) {
 e.preventDefault();
 //identificara los datos del formulario pertnecen a nuevo o a actualizar
 let strIdentificacion = document.querySelector("#txtIdentificacion").value;
 let strNombre = document.querySelector("#txtNombre").value;
 let strApellido = document.querySelector("#txtApellido").value;
 let intTelefono = document.querySelector("#txtTelefono").value;
 
 
 
 let strPassword = document.querySelector("#txtPassword").value;
 let strPasswordConfirm = document.querySelector("#txtPasswordConfirm").value;
 
 if (strIdentificacion === '' || strNombre === '' || strApellido === '' || intTelefono === '')
 {
 swal.fire("atencion", 'todos los campos con ( * ) son obligatorios.', "error");
 return false;
 }
 
 if (strPassword != "" || strPasswordConfirm != "") {
 if (strPassword != strPasswordConfirm) {
 swal.fire("Error", "Las Contraseñas no son iguales", "error");
 return false;
 }
 if (strPassword.length < 5) {
 swal.fire("Error", "Las contraseña debe tener minimo 5 caracteres", "info");
 return false
 }
 
 }
 
 
 
 //validamos los elementos con requeridos con *
 let elementValid = document.getElementsByClassName("valid"); //Retorna un objecto similar a un array de los elementos hijos que tengan todos los nombres de clase indicados
 console.log(elementValid);
 for (let i = 0; i < elementValid.length; i++) { //recorremos el array generado en elementvalid 
 if (elementValid[i].classList.contains('is-invlid')) { // si hay un elemento con invalido se emite el swal.fire con el error
 swal.fire("atencion", "Porfavor verifique los campos en rojo.", "error");
 return false;
 }
 }
 
 let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
 let ajaxUrl = base_url + 'usuarios/putperfil';
 let formData = new FormData(formPerfil);
 request.open("POST", ajaxUrl, true);
 request.send(formData);
 request.onreadystatechange = function () {
 
 if (request.readyState != 4)
 return;
 if (request.status === 200) {
 let objData = JSON.parse(request.responseText);
 if (objData.status)
 {
 $('#modalFormPerfil').modal("hide");
 
 swal.fire({
 title: "",
 text: objData.msg,
 type: "success",
 confirmButtonText: "Aceptar",
 closeOnConfirm: false
 }, function (isConfirm) {
 if (isConfirm) {
 location.reload();
 }
 });
 
 } else {
 swal.fire("Error", objData.msg, "error");
 }
 }
 }
 }
 
 }
 }
 */

function fntDataFiscal() {

  if (document.querySelector("#formDataFiscal")) {
    let formDataFiscal = document.querySelector("#formDataFiscal");
    formDataFiscal.onsubmit = function (e) {
      e.preventDefault();

      let strNit = document.querySelector("#txtNit").value;
      let strNombreFiscal = document.querySelector("#txtNombreFiscal").value;
      let strDirFiscal = document.querySelector("#txtDirFiscal").value;
      if (strNit === '' || strNombreFiscal === '' || strDirFiscal === '') {
        swal.fire("atencion", 'todos los campos con ( * ) son obligatorios.', "error");
        return false;
      }
      let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
      let ajaxUrl = base_url + 'usuarios/putdatfiscal';
      let formData = new FormData(formDataFiscal);
      request.open("POST", ajaxUrl, true);
      request.send(formData);
      request.onreadystatechange = function () {

        if (request.readyState != 4)
          return;
        if (request.status === 200) {
          let objData = JSON.parse(request.responseText);
          if (objData.status)
          {
            $('#modalFormPerfil').modal("hide");

            swal.fire({
              title: "",
              text: objData.msg,
              type: "success",
              confirmButtonText: "Aceptar",
              closeOnConfirm: false
            }, function (isConfirm) {
              if (isConfirm) {
                location.reload();
              }
            });

          } else {
            swal.fire("Error", objData.msg, "error");
          }
        }
      }
    }

  }
}



function fntDel(idPersona) {
  swal.fire(
          {title: "Eliminar Usuario", text: "Quieres eliminar el Al Usuario?", type: "question",
            showCancelButton: true, showConfirmButton: true, confirmButtonText: "Si, Eliminar", cancelButtonText: "No, Cancelar",
            closeOnConfirm: false, closeOnCancel: true},
          (isConfirm) => {
    if (isConfirm) {//si se hace click en isConfirm (elemento de swal.fire), se ejecuta la funcion 
      let arrData = "idUsuario=" + idPersona;
      fetch(base_url + 'usuarios/delUser/',
              {method: "POST",
                headers: {"Content-type": "application/x-www-form-urlencoded"},
                body: arrData})
              .then(objData => objData.json()).then(objData => {
        if (objData.status) {
          swal.fire("eliminar!", objData.msg, "success");
          tableUsuarios.ajax.reload(null, false);  // refrescamos la tabla

        } else {
          swal.fire("Atencio!", objData.msg, "error");
        }
      });
    }
  });
}


