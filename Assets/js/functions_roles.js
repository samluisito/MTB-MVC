let tableRoles;
//al momento de cargar el documento ejecutara esta funcion y en esta funcion tendremos todo el script de datatable
document.addEventListener('DOMContentLoaded', function () {
  tablaRoles();
});

function tablaRoles() {
  tableRoles = $('#tableRoles').DataTable({

//$(document).ready(function() {
    //$('#tableRoles').DataTable({
    "destroy": true,
    "aServerSide": true,
    "responsive": true,
    "language": {url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
    },
    "ajax": {url: base_url + "roles/getRoles", dataSrc: "",
    },
    "columns": [
      {"data": "idrol"},
      {"data": "nombrerol"},
      {"data": "descripcion"},
      {"data": "status"},
      {"data": "options", "searchable": false}
    ],
    "bProcessing": true,
    "iDisplayLength": 10,
    "order": [[0, "asc"]]
  });
}

function formRol() {
//NUEVO ROL
  let formRol = document.querySelector("#formRol"); // LA VARIABLE GUARDA LA UBICACION DEL FORMULARIO 
  formRol.onsubmit = function (e) { //SE AGREGA LA ACCION ONSUBMIT Y ELECUTA LA FUNCION DESCRITA 
    e.preventDefault(); //EVITA QUE LA PAGINA SE RECARGE

    let intIdRol = document.querySelector("#idRol").value; //identificara los datos del formulario pertnecen a nuevo o a actualizar
    let strNombre = document.querySelector("#txtNombre").value;
    let strDescripcion = document.querySelector("#txtDescripcion").value;

    if (intIdRol > 0) { //si el id rol es 0 significa que es un nuevo rol
      let intStatus = document.querySelector("#listStatus").value;
    } else {
      let intStatus = document.querySelector("#listStatus").value = 1;
    }
    if (strNombre === '' || strDescripcion === '') {
      Swal.fire("atencion", "todos los campos son obligatorios.", "error");
      return false;
    }

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + 'roles/setRol';
    let formData = new FormData(formRol);
    // let forElement = document.querySelector('#formRol');
    // let formData = new formData(formElement);
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {

        let objData = JSON.parse(request.responseText);
        if (objData.status) {
          $('#modalFormRol').modal("hide");
          formRol.reset();
          // Funciones de los botones de accion fntEditRol(); fntDelRol();fntPermisos();

          //libreria alerta 
          Swal.fire("Roles de Ususario", objData.msg, "success");
          //RECARGA EL DATA TABLE DESPUES DE EJECUTAR EL SWAL
          tableRoles.ajax.reload(null, false);
        } else {
          Swal.fire("Error", objData.msg, "error");
        }
      }
    }
  }
}

function nvoRol($nombreModal) {
//Nombre del Formulario Modal
  document.querySelector('#idRol').value = ""; //limpiamos el imput para que no incluya nungun valor despues de actualizar algun registro
  document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
  document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
  document.querySelector('#titleModal').innerHTML = "Nuevo Rol";
  //document.querySelector('#btnText').innerHTML = "Guardar";
  document.querySelector('#formRol').reset(); // reseteamos los campos del formulario

  //ocultamos el Status ya que tendra un valor de 1 = habilitado
  document.querySelector('#listStatusLabel').hidden = true;
  document.querySelector('#listStatus').hidden = true;

  document.querySelector('#formRol').reset(); // reseteamos los campos del formulario


  $('#modalFormRol').modal('show');

  formRol();
}

function fntEdit(idRolpag) {
  /*/ querySelectorAll Repasamos la pagina en busca de todos los elementos que tengan la clase btnEditRol yel resultado lo dejamos dentro de una letiable
   let btnEditRol1 = document.querySelectorAll(".btnEditRol");
   
   //repasamos la vatiable con el ciclo forEach, a cada uno de ellos le va a asignar una funcion que va a pasa como parametro la letiable btnEditRol
   btnEditRol1.forEach(function (btnEditRol) {
   
   //luego a esa letiable que estamos pasando como parametro, con addEventListener le agregamos el evento click seguido una funcion a ejecutarse al recibir el click
   btnEditRol.addEventListener('click', function () {*/

//CAMBIAMOS ATRIBUTOS DEL MODAL
//con document document.querySelector nos referimos al elemento con el id o la clase que pasamos como parametro
//innerHTML le indicamos que reemplace el texto existente por el siguiente
//con classList.replace indicamos que reemplace la clase de estilos A por clase de estilos B
  document.querySelector('#titleModal').innerHTML = "Editar Rol";
  document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
  document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
  //document.querySelector('#btnText').innerHTML = "Actualizar";
  //ocultamos el Status ya que tendra un valor de 1 = habilitado
  document.querySelector('#listStatusLabel').hidden = false;
  document.querySelector('#listStatus').hidden = false;

  //INSERTAMOS DATOS EN EL MODAL 
  let idRol = idRolpag;
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrl = base_url + 'roles/getRol/' + idRol;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      //pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario

      let objData = JSON.parse(request.responseText);
      if (objData.status) {

        document.querySelector('#idRol').value = objData.data.idrol;
        document.querySelector('#txtNombre').value = objData.data.nombrerol;
        document.querySelector('#txtDescripcion').value = objData.data.descripcion;
//                if (objData.data.status === 1) {
//
//                    let optionSelect = '<option value="1" selected class="notBlock">Activo</option>';
//                    //seteamos esta letiable con la clase ccs notBlock la cual tiene atributo dysplay oculto
//                } else {
//                    let optionSelect = '<option value="0" selected class="notBlock">Inactivo</option>';
//                }
//                let htmlSelect = optionSelect + ' <option value="1">Activo</option>    <option value="0">Inactivo</option>';
//                document.querySelector('#listStatus').innerHTML = htmlSelect;
        document.querySelector('#listStatus').value = objData.data.status;
        //y esta funcion ejecuta modal('show') refiriendose al elemento boostrap con el id = modalFormRol $('#modalFormRol')
        $('#modalFormRol').modal('show');
        formRol();
      } else {
        Swal.fire("Error", objData.msg, "error");
      }
    }
  }
  /*    });
   });  */
}

function fntDel(idRolpag) {
  /*  let btnDelRol = document.querySelectorAll(".btnDelRol");    // querySelectorAll Repasamos la pagina en busca de todos los elementos que tengan la clase btnEditRol yel resultado lo dejamos dentro de una letiable
   
   btnDelRol.forEach(function (btnDelRol) {     //repasamos la letiable con el ciclo forEach, a cada uno de ellos le va a asignar una funcion que va a pasa como parametro la letiable btnEditRol
   
   btnDelRol.addEventListener('click', function () { */   //luego a esa letiable que estamos pasando como parametro, con addEventListener le agregamos el evento click seguido una funcion a ejecutarse al recibir el click

  let idRol = idRolpag; // this.getAttribute('rl')this hace referencia al elemento al que le damos click, getAttribute trae el dato del elemento ('rl')

  Swal.fire({title: "Eliminar rol",
    text: "Quieres eliminar el Rol?",
    type: "warning",
    showCancelButton: true,
    showConfirmButton: true,
    // confirmButtonText: "Si, Eliminar",
    cancelButtonText: "No, Cancelar",
    closeOnConfirm: false,
    coseOnCancel: true
  }, function (isConfirm) { //si se hace click en isConfirm (elemento de Swal.fire), se ejecuta la funcion 

    if (isConfirm) {

      document.querySelector('#idRol').value = objData.data.idrol;
      let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
      let ajaxUrlDelRol = base_url + 'roles/delRol/';
      let arrData = "idrol=" + idRol;
      request.open("POST", ajaxUrlDelRol, true);
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      request.send(arrData);
      request.onreadystatechange = function () {

        if (request.readyState === 4 && request.status === 200) {
          //pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario
          let objData = JSON.parse(request.responseText);
          if (objData.status) {
            Swal.fire("eliminar!", objData.msg, "success");
            tableRoles.ajax.reload(null, false);  // refrescamos la tabla
            nvoRol()   // refrescamos el nuevo rol
          } else {
            Swal.fire("Atencio!", objData.msg, "error");
          }
        }
      }
    }
  });
}

function fntStatus(idRol) {

  let intStatus = document.querySelector("#btnStatusRol" + idRol).value; //identificara los datos del formulario pertnecen a nuevo o a actualizar

  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrl = base_url + 'roles/statusRol/' + '?idRol=' + idRol + '&intStatus=' + intStatus;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      let objData = JSON.parse(request.responseText);      //pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario
      if (objData.status) {
        tableRoles.ajax.reload(null, false);  // refrescamos la tabla
      } else {
        Swal.fire("Atencio!", objData.msg, "error");
      }
    }
  };
}
let tablePermisos;
function fntPermisos(idRolPag) {

  let idpr = idRolPag
  tablePermisos = $('#tablePermisos').DataTable({
    "destroy": true, //destruye datos previos en la tabla
    //     "ServerSide": false,//habilita le procesamiento de datos del lado del servidor (util para procesamiento de mas de 50.000 registros)
    "responsive": true, //tabla responsive, adaptablle a la ventana 
    "paging": false, //paginado de la tabla
    "iDisplayLength": 25, //registros iniciales mostrados
    "ordering": true, "order": [[0, "desc"]], //ordenar y ordenar por 
    "search": false,
    "searching": false, //activa - desactiva el cuadro de busqueda 
    "Processing": true, //mensaje de procesando mientras espera datos del server 
    "info": false, // informacion de pie de pagina "Mostrando registros del 1 al 7 de un total de 7 registros"
    "scrollX": "200px", //pixeles en lo que muestra el escroll o barra 
    //"scrollCollapse": true, // se muestra al colapsar

    "language": {url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
    },
    "ajax": {url: base_url + "roles/getpermisos/?idrol=" + idpr, dataSrc: "",

    },
    "columns": [
      {"data": "idmodulo", "visible": false, "searchable": false, "all": true},
      {"data": "rolid", "visible": false, "searchable": false, "all": true},
      {"data": "moduloid", "visible": false, "searchable": false, "all": true},
      {"data": "titulo"},
      {"data": "ver"},
      {"data": "crear"},
      {"data": "actualizar"},
      {"data": "eliminar"},
    ],

  });

  $('#modalPermisos').modal('show');



  /*
   let formRol = document.querySelector("#formPermisos"); // LA VARIABLE GUARDA LA UBICACION DEL FORMULARIO 
   formRol.onsubmit = function (e) { //SE AGREGA LA ACCION ONSUBMIT Y ELECUTA LA FUNCION DESCRITA 
   e.preventDefault(); //EVITA QUE LA PAGINA SE RECARGE
   
   let intIdRol = document.querySelector("#idRol").value; //identificara los datos del formulario pertnecen a nuevo o a actualizar
   let strNombre = document.querySelector("#txtNombre").value;
   let strDescripcion = document.querySelector("#txtDescripcion").value;
   
   if (intIdRol > 0) { //si el id rol es 0 significa que es un nuevo rol
   let intStatus = document.querySelector("#listStatus").value;
   } else {
   let intStatus = document.querySelector("#listStatus").value = 1;
   }
   let strDescripcion = document.querySelector("#fliping-toggle").value;
   
   
   
   let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
   let ajaxUrl = base_url + 'roles/setPermisos';
   let formData = new FormData(formRol);
   console.log(formData);
   // let forElement = document.querySelector('#formRol');
   // let formData = new formData(formElement);
   request.open("POST", ajaxUrl, true);
   request.send(formData);
   
   request.onreadystatechange = function () {
   if (request.readyState === 4 && request.status === 200) {
   
   
   let objData = JSON.parse(request.responseText);
   if (objData.status) {
   // $('#modalFormRol').modal("hide");
   //formRol.reset();
   // Funciones de los botones de accion fntEditRol(); fntDelRol();fntPermisos();
   
   //libreria alerta 
   Swal.fire("Roles de Ususario", objData.msg, "success");
   //RECARGA EL DATA TABLE DESPUES DE EJECUTAR EL SWAL  tableRoles.ajax.reload(null, false);
   
   } else {
   Swal.fire("Error", objData.msg, "error");
   }
   }
   }
   }*/
}

function fntActionPermiso(Permiso, idPermiso) {
  let idPerm = idPermiso;
  let tpoPer = Permiso;
  let idCheck = tpoPer + idPerm;

  let estado = document.querySelector('#' + idCheck).value;

  let valor;
  if (estado === 0) {
    valor = 1;
  } else if (estado === 1) {
    valor = 0;
  }
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrl = base_url + 'roles/setPermiso';
  let DataPer = "idPermiso=" + idPerm + "&tpoPermiso=" + tpoPer + "&estado=" + valor;
  // let formData = new formData(formElement);
  request.open("POST", ajaxUrl, true);
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.send(DataPer);
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {

      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.querySelector('#' + idCheck).value = objData.value;
      } else {
        Swal.fire("Error", objData.msg, "error");
      }
    }
  }






}


/*
 function openModal1(nombreModal) {
 // muestra el modal que se este pasando como parametro en la funcion
 let modalfun = '';
 modalfun = '#' + nombreModal;
 $(modalfun).modal('show');
 
 //Nuevo Rol modal
 $('#tablaRoles').DataTable();
 let formRol = document.querySelector("#formRol");
 formRol.onsubmit = function (e) {
 }
 
 }
 */
//PRUEBA EN CONSOLA RED console.log(request); console.log(request);

// console.log("nombremodal"+$nombreModal);
// console.log($modalfun);

