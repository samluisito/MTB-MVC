var tableModulos;

//al momento de cargar el documento ejecutara esta funcion y en esta funcion tendremos todo el script de datatable
document.addEventListener('DOMContentLoaded', function () {
  tablaModulos();
});

function tablaModulos() {
  tableModulos = $('#tableModulos').DataTable({

//$(document).ready(function() {
    //$('#tableModulos').DataTable({
    "destroy": true,
    "aServerSide": true,
    "responsive": true,
    "language": {url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
    },
    "ajax": {url: base_url + "modulos/getmodulos", dataSrc: "",
    },
    "columns": [
      {"data": "idmodulo"},
      {"data": "titulo"},
      {"data": "descripcion"},
      {"data": "status"},
      {"data": "options", "searchable": false}
    ],
    "bProcessing": true,
    "iDisplayLength": 10,
    "order": [[0, "asc"]]
  });
}

function nvoModulo(nombreModal) {
  //Nombre del Formulario Modal

  document.querySelector('#idModulo').value = ""; //limpiamos el imput para que no incluya nungun valor despues de actualizar algun registro
  document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
  document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
  document.querySelector('#titleModal').innerHTML = "Nuevo Modulo";
//  document.querySelector('#btnText').innerHTML = "Guardar";
  //ocultamos el Status ya que tendra un valor de 1 = habilitado
  document.querySelector('#listStatusLabel').hidden = true;
  document.querySelector('#listStatus').hidden = true;
  document.querySelector('#formModulo').reset(); // reseteamos los campos del formulario


  // muestra el modal que se este pasando como parametro en la funcion
  var modalfun = '';
  var modalfun = '#' + nombreModal;

  formModulo();
}

function fntEdit(idModulopag) {

//CAMBIAMOS ATRIBUTOS DEL MODAL 
  /*con document document.querySelector nos referimos al elemento con el id o la clase que pasamos como parametro
   innerHTML le indicamos que reemplace el texto existente por el siguiente
   con classList.replace indicamos que reemplace la clase de estilos A por clase de estilos B */
  document.querySelector('#titleModal').innerHTML = "Actualizar Rol";//con document document.querySelector nos referimos al elemento con el id o la clase que pasamos como parametro
  document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");//reemplazamos las clases de estilos 
  document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");//reemplazamos las clases de estilos 
//  document.querySelector('#btnText').innerHTML = "Actualizar";//reemplazamos el trxto boton del formulario
  //Mostramos el status 
  document.querySelector('#listStatusLabel').hidden = false;
  document.querySelector('#listStatus').hidden = false;

  //INSERTAMOS DATOS EN EL MODAL 
  var idModulo = idModulopag;
  var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  var ajaxUrl = base_url + 'modulos/getModulo/' + idModulo;
  request.open("GET", ajaxUrl, true);
  request.send();

  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      //pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario

      var objData = JSON.parse(request.responseText);
      if (objData.status) {

        document.querySelector('#idModulo').value = objData.data.idmodulo;
        document.querySelector('#txtTitulo').value = objData.data.titulo;
        document.querySelector('#txtDescripcion').value = objData.data.descripcion;
        if (objData.data.status === 1) {

          var optionSelect = '<option value="1" selected class="notBlock">Activo</option>';
          //seteamos esta variable con la clase ccs notBlock la cual tiene atributo dysplay oculto
        } else {
          var optionSelect = '<option value="0" selected class="notBlock">Inactivo</option>';
        }
        var htmlSelect = optionSelect + ' <option value="1">Activo</option> <option value="0">Inactivo</option>';

        document.querySelector('#listStatus').innerHTML = htmlSelect;
        //y esta funcion ejecuta modal('show') refiriendose al elemento boostrap con el id = modalFormRol $('#modalFormRol')
//        $('#modalFormModulo').modal('show');
        formModulo();
      } else {
        Swal.fire("Error", objData.msg, "error");
      }
    }
  };

}

function formModulo() {
  $(modalFormModulo).modal('show');
  //FORMULARIO NUEVO MODULO
  var formModulo = document.querySelector("#formModulo"); // LA VARIABLE GUARDA LA INFORMACION DEL FORMULARIO 
  formModulo.onsubmit = function (e) { //SE AGREGA LA ACCION ONSUBMIT(ENVIAR FORMULACIO) Y ELECUTA LA FUNCION DESCRITA, SE DECLARAN LOS DATOS A ENVIAR
    e.preventDefault(); //EVITA QUE LA PAGINA SE RECARGE

    var intIdModulo = document.querySelector("#idModulo").value; //identificara los datos del formulario pertnecen a nuevo o a actualizar
    var strTitulo = document.querySelector("#txtTitulo").value;
    var strDescripcion = document.querySelector("#txtDescripcion").value;
    if (intIdModulo > 0) {
      var intStatus = document.querySelector("#listStatus").value;
    } else {
      var intStatus = document.querySelector("#listStatus").value = 1;

    }

    if (strTitulo === '' || strDescripcion === '' || intStatus === '') { // Se valida que ningun dato venga vacio
      Swal.fire("atencion", "todos los campos son obligatorios.", "error");
      return false;
    }

    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + 'modulos/setModulo';
    var formData = new FormData(formModulo);

    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {

        var objData = JSON.parse(request.responseText);
        if (objData.status) {
          $('#modalFormModulo').modal("hide");//CERRAMOS EL MODAL
          formModulo.reset();// LIMPIAMOS LOS DATOS DEL MODAL

          //libreria alerta 
          Swal.fire("Roles de Ususario", objData.msg, "success");
          //RECARGA EL DATA TABLE DESPUES DE EJECUTAR EL SWAL
          tableModulos.ajax.reload();
        } else {
          Swal.fire("Error", objData.msg, "error");
        }
      }
    };
  };
}

function fntDel(idModuloPag) {
  /* var btnDelRol = document.querySelectorAll(".btnDelRol"); // querySelectorAll Repasamos la pagina en busca de todos los elementos que tengan la clase btnEditRol yel resultado lo dejamos dentro de una variable
   
   btnDelRol.forEach(function (btnDelRol) { //repasamos la variable con el ciclo forEach, a cada uno de ellos le va a asignar una funcion que va a pasa como parametro la variable btnEditRol
   
   btnDelRol.addEventListener('click', function () { */ //luego a esa variable que estamos pasando como parametro, con addEventListener le agregamos el evento click seguido una funcion a ejecutarse al recibir el click

  var idModulo = idModuloPag; // this.getAttribute('rl')this hace referencia al elemento al que le damos click, getAttribute trae el dato del elemento ('rl')

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

      //document.querySelector('#idModulo').value = objData.data.idmodulo;
      var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
      var ajaxUrlDelModulo = base_url + 'modulos/delModulo/';
      var arrData = "idModulo=" + idModulo;
      request.open("POST", ajaxUrlDelModulo, true);
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      request.send(arrData);
      request.onreadystatechange = function () {

        if (request.readyState === 4 && request.status === 200) {
          //pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario
          var objData = JSON.parse(request.responseText);
          if (objData.status) {
            Swal.fire("eliminar!", objData.msg, "success");
            tableModulos.ajax.reload(null, false); // refrescamos la tabla
            // refrescamos el nuevo rolnvoRol() 
          } else {
            Swal.fire("Atencio!", objData.msg, "error");
          }
        }
      };
    }
  });
}

function fntStatus(idModulopag) {

  var idModulo = idModulopag; // this.getAttribute('rl')this hace referencia al elemento al que le damos click, getAttribute trae el dato del elemento ('rl')

  var btnStatusModulo = "#btnStatusModulo" + idModulo;
  var intStatus = document.querySelector("#btnStatusModulo" + idModulo).value; //identificara los datos del formulario pertnecen a nuevo o a actualizar
  var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  var ajaxUrl = base_url + 'modulos/statusModulo/' + '?idModulo=' + idModulo + '&intStatus=' + intStatus;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {

    if (request.readyState === 4 && request.status === 200) {
      //pasamos los datos a objeto json y le asignamos el valor a cada uno de los elementos del formulario
      var objData = JSON.parse(request.responseText);
      if (objData.status) {
        // Swal.fire("ESTADO", objData.msg, "info"); //mostramos el resultado de la operacion

        tableModulos.ajax.reload(null, false); // refrescamos la tabla
        // nvoModulo() //refrescamos el nuevo rol

      } else {
        Swal.fire("Atencio!", objData.msg, "error");
      }
    }

  };

}

