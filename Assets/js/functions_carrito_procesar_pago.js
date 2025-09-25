document.addEventListener('DOMContentLoaded', function () {
    getShipping();
})
/*Funviona General */

/*Aceptar condiciones registro==================================================================*/
if (document.querySelector('#condiciones')) {
    let condiciones = document.querySelector('#condiciones');
    condiciones.addEventListener('click', function () {
        let opcion = this.checked;
        if (opcion) {
            document.querySelector("#btnRegistrarme").classList.remove('notBlock')
        } else {
            document.querySelector("#btnRegistrarme").classList.add('notBlock')
        }
    });
}

/*selector ratio de Envio o Retiro ==================================================================*/

function actualizarTotalDeCarrito(subtotal, shipping) {
    /*actualiza los elementos de envio y total, segun con los valores recibidos*/
    document.querySelectorAll('.shipping_monto').forEach(function (valor) {//actualiza el valor de COSTO ENVIO en panatalla 
        valor.innerHTML = '';
        valor.innerHTML = '$ ' + number_format(shipping, 2, ',', '.');
    })
    document.querySelectorAll('.total_monto').forEach(function (valor) {//actualiza LOS TOTALES en pantalla
        valor.innerHTML = '';
        valor.innerHTML = '$ ' + number_format(subtotal + shipping, 2, ',', '.');
    })
    document.querySelectorAll('.selctmethodpago').forEach((x) => x.checked = false);// las opciones de pago quefaran deseleccionadas 
    document.querySelectorAll('.metodopagodiv').forEach((x) => x.classList.add('notBlock'));// los div opciones de pago quedaran ocultos 

}
/*selector ratio de Envio o Retiro ==================================================================*/
function getShipping() {
    /*si se selecciona retirar en tienda el valor del costo de envio sera 0,
     *si se selecciona entrega se buscara el valor actual del costo de envio,
     *el los valores subtotal y costo de envio se procesaran en actualizarTotalDeCarrito para mostrarlos en el pag*/

    if (document.querySelector('#metodoEntregaSelect').value == 'retiro') {
        let subtotal = new Number(cleanFormatNumero(document.querySelector('.subtotal_monto').innerHTML));
        actualizarTotalDeCarrito(subtotal, 0)
    } else if (document.querySelector('#metodoEntregaSelect').value == 'entrega') {

        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url + 'carrito/getShipping';
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                let objData = JSON.parse(request.responseText);

                let subtotal = cleanFormatNumero(document.querySelector('.subtotal_monto').innerHTML);
                let shipping = cleanFormatNumero(objData.shipping);
                actualizarTotalDeCarrito(subtotal, shipping)

            }
        }
    }
}
if (document.querySelector('.selctMetodoEntrega')) {
    let optmetodo = document.querySelectorAll('.selctMetodoEntrega'); //seleccionamos todos los elementos con la clase .selctMetodoEntrega
    optmetodo.forEach(function (optmetodo) {  //recorremos el array de los metodos de selctMetodoEntrega 
        optmetodo.addEventListener('click', function () {  // 
            let tipmetodo = document.querySelectorAll('.metodoEntregaDiv'); // oculatmos todos los div de metodos de pago 
            tipmetodo.forEach(element => {
                element.classList.add('notBlock');
            });

            document.querySelector('#' + this.value + 'Div').classList.remove('notBlock'); // mostramos el div elegido 
            document.querySelector('#metodoEntregaSelect').value = this.value; // escribimos la variable en un tipo de pago para su procesamiento    

            getShipping();
        })
    });
}

/*selector ratio de medios de pago carrito ==================================================================*/
if (document.querySelector('.selctmethodpago')) {
    let optmetodo = document.querySelectorAll('.selctmethodpago'); //seleccionamos todos los elementos con la clase .methodpago
    optmetodo.forEach(function (optmetodo) {  //recorremos el array de los metodos de pago 
        optmetodo.addEventListener('click', function () {  // 
            let tipmetodo = document.querySelectorAll('.metodopagodiv'); // oculatmos todos los div de metodos de pago 
            tipmetodo.forEach(element => {
                element.classList.add('notBlock');
            });
            this.value == 'mp' ? crearPreferenciaMP() : '';
            this.value == 'pp' ? createButtonPayPal() : '';

            document.querySelector('#' + this.value + 'Div').classList.remove('notBlock'); // mostramos el div elegido 
            document.querySelector('#idTPseleccionado').value = this.value; // escribimos la variable del pago para su procesamiento posterior 
        })
    });
}

/*==================================================================*/
/* [input num ]   */
if (document.querySelector('#txtDireccion')) {
    let direccion = document.querySelector("#txtDireccion");
    direccion.addEventListener('keyup', function () {
        let dir = this.value;
        fntViewPago();
    });
}
/*==================================================================*/
/* [input num ]   */
if (document.querySelector('#txtCiudad')) {
    let ciudad = document.querySelector('#txtCiudad');
    ciudad.addEventListener('keyup', function () {
        let c = this.value;
        fntViewPago();
    });
}

/*OCULTAR MODTRAR DIV METODOS DE PAGO ==================================================================*/
function fntViewPago() {
    let direccion = document.querySelector("#txtDireccion").value;
    var ciudad = document.querySelector('#txtCiudad').value;
    if (direccion == "" || ciudad == "") {
        document.querySelector('#divMetodoPago ').classList.remove('notBlock');
    } else {
        document.querySelector('#divMetodoPago ').classList.remove('notBlock');
    }
}

/*procesar venta ContraEntrega  =============================*/
if (document.querySelector('#ceButton')) {
    let btnPago = document.querySelector('#ceButton');
    btnPago.addEventListener('click', function (e) {
        e.preventDefault();

//        let dir = document.querySelector('#txtDireccion').value;
//        let cdad = document.querySelector('#txtCiudad').value;
//        let metodoEntrega = document.querySelector('#metodoEntregaSelect').value;




        let tpopago = document.querySelector('#idTPseleccionado').value;
        let subtotal_monto = cleanFormatNumero(document.querySelector('.subtotal_monto').innerHTML);
        let shipping_monto = cleanFormatNumero(document.querySelector('.shipping_monto').innerHTML);
        let total_monto = cleanFormatNumero(document.querySelector('.total_monto').innerHTML);

        //divLoading.style.display = "flex"; //muestra imagen de espera 
        let form = document.querySelector('#formEenvioRetiro');
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url + 'carrito/procesarVentaCE';
        let formData = new FormData(form); // creamos un nuevo objeto formulario y le agregamos los datos seguientes

        formData.append('tpopago', tpopago);
        formData.append('subtotal_monto', subtotal_monto);
        formData.append('shipping_monto', shipping_monto);
        formData.append('total_monto', total_monto);
        request.open("POST", ajaxUrl, true);
        request.send(formData);
        request.onreadystatechange = function () {
            //divLoading.style.display = "none"; // oculta imagen de carga 

            if (request.readyState == 4 && request.status == 200) {
                let objData = JSON.parse(request.responseText);
                if (objData.status) {

                    swal({
                        title: 'Pedido Exitoso',
                        text: objData.msg,
                        icon: 'info',
                        buttons: 'OK',
                        dangerMode: true
                    }).then((value) => {
                        window.location.href = base_url + 'carrito/confirmarpedido';
                    })
                } else {
                    swal("Error", objData.msg, "error");
                }
            }
        }
    }, false);
}
/*procesar venta Transferencia bancaria  =============================*/

function validarTranferenciaId(idtrans) {
    fetch(base_url + 'carrito/validaridtransferencia/' + idtrans)
            .then(response => response.json())
            .then(data => retornar = data);
}
if (document.querySelector('#tbButton')) {
    let btnPago = document.querySelector('#tbButton');
    btnPago.addEventListener('click', function (e) {
        e.preventDefault();

        let idtrans = document.querySelector('#idTranfer').value.replace(/\s+/g, ''); //generamos una variable limpia de espacios 
        // validarTranferenciaId(idtrans);

        let tpopago = document.querySelector('#idTPseleccionado').value;
        let subtotal_monto = cleanFormatNumero(document.querySelector('.subtotal_monto').innerHTML);
        let shipping_monto = cleanFormatNumero(document.querySelector('.shipping_monto').innerHTML);
        let total_monto = cleanFormatNumero(document.querySelector('.total_monto').innerHTML);

        if (idtrans == '') {
            swal("Por Favor", 'ingrese el numero de comprobante', "error");
            return false;
        } else {

//            divLoading.style.display = "flex"; //muestra imagen de espera 
            let form = document.querySelector('#formEenvioRetiro');

            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + 'carrito/procesarventatb';
            let formData = new FormData(form); // creamos un nuevo objeto formulario y le agregamos los datos seguientes

            formData.append('transaccionid', idtrans);
            formData.append('tpopago', tpopago);
            formData.append('subtotal_monto', subtotal_monto);
            formData.append('shipping_monto', shipping_monto);
            formData.append('total_monto', total_monto);

            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
//                divLoading.style.display = "none"; // oculta imagen de carga 

                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        window.location.href = base_url + 'carrito/confirmarpedido';

                        swal({
                            title: 'Pedido Exitoso',
                            text: objData.msg,
                            icon: 'info',
                            buttons: 'OK',
                            dangerMode: true
                        }).then((value) => {
                            window.location.href = base_url + 'carrito/confirmarpedido';
                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }

                }
            }
        }
    }, false);
}
/* Preprocesar venta Paypal==================================================================*/
function createButtonPayPal() {
    document.getElementById("paypal-button-container").innerHTML = ""; // limpiamos el Div del boton paypal, para evitar un posible boton doble
    let montoTotal = cleanFormatNumero(document.querySelector('.total_monto').innerHTML);// tomamos el montototal de la pagina y pasamos a un numero sin formato de moneda
    // console.log("PP Compra de Articulos en por " + montoTotal);
    paypal.Buttons({
        createOrder: function (data, actions) { // This function sets up the details of the transaction, including the amount and line item details.
            return actions.order.create({
                purchase_units: [{
                        amount: {value: montoTotal},
                        description: "Compra de Articulos por $ " + montoTotal,
                    }]
            });
        },
        onApprove: function (data, actions) { // This function captures the funds from the transaction.
            return actions.order.capture().then(function (details) { // This function shows a transaction success message to your buyer.
                //let base_url = "<?= base_url() ?>";
                //let tpopago = 'pp';

                let tpopago = document.querySelector('#idTPseleccionado').value;
                let subtotal_monto = cleanFormatNumero(document.querySelector('.subtotal_monto').innerHTML);
                let shipping_monto = cleanFormatNumero(document.querySelector('.shipping_monto').innerHTML);
                let total_monto = montoTotal;

//                divLoading.style.display = "flex"; //muestra imagen de espera 
                let form = document.querySelector('#formEenvioRetiro');

                let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                let ajaxUrl = base_url + 'carrito/procesarventapp';
                let formData = new FormData(form);

                formData.append('tpopago', tpopago);
                formData.append('datapay', JSON.stringify(details));

                formData.append('subtotal_monto', subtotal_monto);
                formData.append('shipping_monto', shipping_monto);
                formData.append('total_monto', total_monto);

                request.open("POST", ajaxUrl, true);
                request.send(formData);

//                divLoading.style.display = "none"; // oculta imagen de carga 
                request.onreadystatechange = function () {
                    if (request.readyState == 4 && request.status == 200) {
                        let objData = JSON.parse(request.responseText);
                        if (objData.status) {
                            //swal("ok", objData.msg, "info");
                            window.location = base_url + 'carrito/confirmarpedido';
                        } else {
                            swal("Error", objData.msg, "error");
                        }

                    }
                }
            });
        }
    }).render('#paypal-button-container');


}



/* Preprocesar venta Mercadopago==================================================================*/
function createCheckoutButton(preferenceid) {
    var script = document.createElement("script");
    // The source domain must be completed according to the site for which you are integrating.
    // For example: for Argentina ".com.ar" or for Brazil ".com.br".
    script.src = "https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js";
    script.type = "text/javascript";
    script.dataset.preferenceId = preferenceid;
    document.getElementById("mpButtonContainer").innerHTML = "";
    document.getElementById("mpButtonContainer").appendChild(script);
}
function createCheckoutButtonV2(preferenceid) {
//    if (document.getElementsByClassName('mercadopago-button')) {
//        alert(document.getElementsByClassName('mercadopago-button'));
//    }
    document.getElementById("mpButtonContainer").innerHTML = '';
    mp.checkout({
        preference: {id: preferenceid},
        render: {
            container: '#mpButtonContainer', // Indica dónde se mostrará el botón de pago
            label: ' Pagar con MercadoPago ', // Cambia el texto del botón de pago (opcional)
        }
    });
}
function crearPreferenciaMP() {
//if (document.querySelector('#mpp')) {
    //document.querySelector('.mercadopago-button').classList.add('notBlocka');
//    document.querySelector('#mp').addEventListener('click', function (e) {
//        e.preventDefault();

    // This function shows a transaction success message to your buyer.
//    let dir = document.querySelector('#txtDireccion').value;
//    let cdad = document.querySelector('#txtCiudad').value;
    //let preference_id = document.querySelector('#data-preference-id').value;
//    let monto = document.querySelector('#data-preference-monto').value;
//    let subtotal = document.querySelector('#data-preference-subtotal').value;
//    let envio = document.querySelector('#data-preference-envio').value;
   // let idtrans = document.querySelector('#idTranfer').value.replace(/\s+/g, ''); //generamos una variable limpia de espacios 
//console.log(idTranfer)
    let tpopago = document.querySelector('#idTPseleccionado').value;
    let subtotal_monto = cleanFormatNumero(document.querySelector('.subtotal_monto').innerHTML);
    let shipping_monto = cleanFormatNumero(document.querySelector('.shipping_monto').innerHTML);
    let total_monto = cleanFormatNumero(document.querySelector('.total_monto').innerHTML);

    let form = document.querySelector('#formEenvioRetiro');

//    divLoading.style.display = "flex"; //muestra imagen de espera 
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url + 'carrito/prefereciaMP';
    let formData = new FormData(form);

   // formData.append('transaccionid', idtrans);
    formData.append('tpopago', tpopago);
//    formData.append('subtotal_monto', subtotal_monto);
//    formData.append('shipping_monto', shipping_monto);
//    formData.append('total_monto', total_monto);

    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {

//        divLoading.style.display = "none"; // oculta imagen de carga 

        if (request.readyState == 4 && request.status == 200) {
          
            let objData = JSON.parse(request.responseText);
            //createCheckoutButton(objData.id);
            createCheckoutButtonV2(objData.id);
        }
    }
//    });
}
