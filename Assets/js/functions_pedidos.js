/*document.write('<script type="text/javascript" src="' + base_url + 'Assets/js/plugins/JsBarcode.all.min.js"></script>'); */
function fntView(id) {
  $('#modalpedido').modal('show');
}
/* ======================================================================================================================================== */

let tablePedidos;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

/* ======================================================================================================================================== */

document.addEventListener('DOMContentLoaded', function () {
  if (document.querySelector('#tablePedidos')) {
    tablaPedidos();
  }
});
/* ======================================================================================================================================== */

function tablaPedidos() {
  tablePedidos = $('#tablePedidos').DataTable({
    "destroy": true, //destuye la tabla existente, 
    "stateSave": true, //  guardar estado: restaurar el estado de la tabla en la recarga de la página
    "responsive": true, //tabla responsive, adaptablle a la ventana 
    // "ServerSide": false,//habilita le procesamiento de datos del lado del servidor (util para procesamiento de mas de 50.000 registros)
    "search": true,
    "searching": true, //activa - desactiva el cuadro de busqueda 
    "bProcessing": true,
    // "info": true,
    // "paging": true, //paginado de la tabla
    "iDisplayLength": 25, //registros iniciales mostrados
    "order": [[4, "DESC"]], // "ordering": true, "order": [[0, "desc"]], //ordenar y ordenar por 
    dom: '<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4"<"dt-buttons btn-group flex-wrap"B>><"col-sm-12 col-md-4"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    "language": {url: "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
    },
    "ajax": {url: base_url + "pedidos/getPedidos", dataSrc: "",
    },
    "columns": [
      {"data": "idpedido"}, //0
      {"data": "fecha"}, //1
      {"data": "transaccion"}, //2
      {"data": "monto"}, //3
      {"data": "nombre_tpago"}, //4
      {"data": "status"}, //5
      {"data": "options", "searchable": false}//6
    ],
    "columnDefs": [
      {'className': "text-rigth align-middle", "targets": [3]},
      {'className': "text-rigth align-middle", "targets": [4]},
      {'className': "text-rigth align-middle", "targets": [5]}
    ],
    buttons: [
      {
        extend: 'excel',
        text: 'excel',
        ttleAtter: 'Exportar a Excel',
        className: 'btn btn-danger excelButton',
        exportOptions: {
          "columns": [0, 1, 2, 3, 4, 5]
                  /*modifier: {
                   search: 'none'
                   }*/
        }
      },
      {
        extend: 'pdf',
        text: 'PDF',
        ttleAtter: 'Exportar a PDF',
        className: 'btn btn-primary',
        exportOptions: {
          "columns": [0, 1, 2, 3, 4, 5],
          modifier: {
            search: 'none'
          }
        }
      },
      {
        extend: 'csv',
        text: '<i class="fas fa-file-csv"></i>  CSV',
        ttleAtter: 'Exportar a CSV',
        className: 'btn btn-warning m-auto',
        exportOptions: {
          "columns": [0, 1, 2, 3, 4, 5],
          modifier: {
            search: 'none'
          }
        }
      }

    ]
  });
}


/* ======================================================================================================================================== */

function fntVer(id) {
//$('#modalVerProducto').modal('show');

  let idProd = id;
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrlUserId = base_url + 'pedidos/getTransaccion/' + idProd;
  request.open("GET", ajaxUrlUserId, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {

      let objData = JSON.parse(request.responseText);
      if (objData.status) {

        let htmlImage = "";
        let objProducto = objData.data;
        let status = objProducto.status == 1 ? //muestra el espan segun el estado 
                '<span class="bagde badge-success">Activo</option>' : '<span class="badge badge-danger">Inactivo</option>';
        document.querySelector('#celCodigo').innerHTML = objProducto.codigo;
        document.querySelector('#celNombre').innerHTML = objProducto.nombre;
        document.querySelector('#celPrecio').innerHTML = objProducto.precio;
        document.querySelector('#celStock').innerHTML = objProducto.stock;
        document.querySelector('#celCategoria').innerHTML = objProducto.categoria;
        document.querySelector('#celEstado').innerHTML = objProducto.status;
        document.querySelector('#celDescripcion').innerHTML = objProducto.descripcion;
        //document.querySelector('#celImgCategoria').innerHTML = '<img src = "' + objData.data.url_img + '">';

        if (objProducto.images.length > 0) {
          let objProImages = objProducto.images;
          for (let p = 0; p < objProImages.length; p++) {
            htmlImage += `<img src = "${objProImages[p].url_image}"></img>`;
          }

        }
        document.querySelector("#celFotos").innerHTML = htmlImage;
        $('#modalVerCategoria').modal('show')

      } else {
        Swal.fire("Error", objData.msg, "error");
      }
    }
  }

}
/* ======================================================================================================================================== */

function fntTransaccion(idtrans) {
  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrlUserId = base_url + 'pedidos/getTransaccion/' + idtrans;
  divLoading.style.display = "flex";
  request.open("GET", ajaxUrlUserId, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      divLoading.style.display = "none";

      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        document.querySelector('#divModalTrans').innerHTML = objData.html;

        $('#modalReembolso').modal('show');
      } else {
        Swal.fire("Error", objData.msg, "error");
      }

      return false;
    }
  }
}

/* ======================================================================================================================================== */

function fntReembolsar() {

  let idtransaccion = document.querySelector('#idtransaccion').value;
  let observacion = document.querySelector('#txtObservacion').value;

  if (idtransaccion == '' || observacion == '') {
    Swal.fire("", 'por favor describe el motivo de la devolucion en el campo observacion', "error");
    return false;
  }

  Swal.fire({
    title: 'Hacer Reembolso?',
    text: 'Realmente quieres hacer el reembolso',
    type: 'warning',
    showCancelButton: true,
    cancelButtonText: 'NO',
    confirmButtonText: 'SI estoy seguro',
    closeOnConfirm: true,
    closeONCancel: true
  }, function (isConfirm) {
    if (isConfirm) {
      $('#modalReembolso').modal('hide');
      divLoading.style.display = "flex";
      let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
      let ajaxUrlUserId = base_url + 'pedidos/setReembolso';


      let formData = new FormData();

      formData.append('idtransaccion', idtransaccion)
      formData.append('observacion', observacion)

      request.open("POST", ajaxUrlUserId, true);
      request.send(formData);

      request.onreadystatechange = function () {
        divLoading.style.display = "none";
        if (request.readyState == 4 && request.status == 200) {

          let objData = JSON.parse(request.responseText);
          if (objData.status) {

            Swal.fire({
              title: 'Debolucion Exitosa',
              text: objData.msg,
              type: 'info',
              showCancelButton: false,
              cancelButtonText: 'ok',
              confirmButtonText: 'OK!',
              closeOnConfirm: true,
              closeONCancel: true
            }, function (isConfirm) {
              window.location.reload();
            })
          } else {
            Swal.fire("Error", objData.msg, "error");
          }

          return false;
        }
      }
    }
  });
}

/* EDITAR ESTADO DEL PEDIDO ======================================================================================================================================== */
function fntEditPedido(idpedido) {
  let request = (window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP'));
  let ajaxUrl = base_url + 'pedidos/getpedido/' + idpedido;
  divLoading.style.display = "flex";

  request.open("GET", ajaxUrl, true);
  request.send();

  request.onreadystatechange = function (e) {
    e.preventDefault();
    if (request.readyState == 4 && request.status == 200) {
      divLoading.style.display = "none";
      let objData = JSON.parse(request.responseText);
      if (objData.status) {

        document.querySelector('#divModal').innerHTML = objData.html
        $('#modalFormPedido').modal('show');
        $('select').selectpicker();
        fntUpdatePedido();
      } else {
        Swal.fire('Error', objData.msg, 'error');
      }
      return false;
    }
  }
}


/* ======================================================================================================================================== */
function fntUpdatePedido() {
  let formUpdatePedido = document.querySelector("#formUpdatePedido");
  formUpdatePedido.onsubmit = function (e) {
    e.preventDefault();

    let transaccion;
    if (document.querySelector("#txtTransaccion")) {
      transaccion = document.querySelector("#txtTransaccion").value;
      if (transaccion == "") {
        Swal.fire("", "Complete los datos para continuar", "error");
        return false;
      }
    }

    divLoading.style.display = "flex";

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + 'pedidos/setPedido';
    let formData = new FormData(formUpdatePedido);
    request.open("POST", ajaxUrl, true);
    request.send(formData);

    request.onreadystatechange = function () {

      divLoading.style.display = "none";

      if (request.readyState == 4 && request.status == 200) {
        let objData = JSON.parse(request.responseText);
        if (objData.status) {
          Swal.fire("OK", objData.msg, "success");
          //RECARGA EL DATA TABLE DESPUES DE EJECUTAR EL SWAL
          tablePedidos.ajax.reload(null, false);  // refrescamos la tabla
          $('#modalFormPedido').modal('hide');
          document.querySelector('#divModal').innerHTML = '';
        } else {
          Swal.fire('Error', objData.msg, 'error');
        }
        return false;
      }
    }

  }
}



/* ======================================================================================================================================== */

//function printPantalla(seccion) {
//    console.log(seccion);
//    window.print(seccion);
//    
//}
/* ======================================================================================================================================== */

function reenviarEmail(idpedido) {

  divLoading.style.display = "flex";

  let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
  let ajaxUrl = base_url + 'pedidos/reenviarEmail/' + idpedido;

  request.open("GET", ajaxUrl, true);
  request.send();

  request.onreadystatechange = function () {

    divLoading.style.display = "none";

    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        Swal.fire("OK", objData.msg, "success");

      } else {
        Swal.fire('Error', objData.msg, 'error');
      }
      return false;
    }
  }
}

function nvoPedido() {
  Swal.fire("PROXIMAMENTE", 'Por ahora no puede agregar pedidos', "info");

}