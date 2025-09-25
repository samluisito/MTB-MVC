
function controlTag(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla === 8)
        return true;
    else if (tecla === 0 || tecla === 9)
        return true;
    patron = /[0-9\s]/;
    n = String.fromCharCode(tecla);
    return patron.test(n);
}

function testText(txtString) {
    var stringText = new RegExp(/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü\s]+$/);
    if (stringText.test(txtString)) {
        return true;
    } else {
        return false;
    }
}

function testEntero(intCant) {
    var intCantidad = new RegExp(/^([0-9])*$/);
    if (intCantidad.test(intCant)) {
        return true;
    } else {
        return false;
    }
}

function fntEmailValidate(email) {
    var stringEmail = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
    if (stringEmail.test(email) === false) {
        return false;
    } else {
        return true;
    }
}

function fntValidText() {
    let validText = document.querySelectorAll(".validText");
    validText.forEach(function (validText) {
        validText.addEventListener('keyup', function () {
            let inputValue = this.value;
            if (!testText(inputValue)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
}

function fntValidNumber() {
    let validNumber = document.querySelectorAll(".validNumber");
    validNumber.forEach(function (validNumber) {
        validNumber.addEventListener('keyup', function () {
            let inputValue = this.value;
            if (!testEntero(inputValue)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
}

function fntValidEmail() {
    let validEmail = document.querySelectorAll(".validEmail");
    validEmail.forEach(function (validEmail) {
        validEmail.addEventListener('keyup', function () {
            let inputValue = this.value;
            if (!fntEmailValidate(inputValue)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
}
function cleanFormatNumero(numero) {
    //monto = '  $ 3.500,22';
    let monto = numero;
    let montoTotal;
    montoTotal = monto.replace(/(\r?\n|\r)/gm, "");
    montoTotal = montoTotal.replace('$', "");
    montoTotal = montoTotal.replace('.', "");
    montoTotal = montoTotal.replace(',', ".");
    montoTotal = montoTotal.replace(/ /g, "");
    //montoTotal = parseFloat(montoTotal);
    return new Number(montoTotal);
}

function number_format(numero, cantDecimales, sepDecimal, sepMil) { // eslint-disable-line camelcase
    //  discuss at: https://locutus.io/php/number_format/
    //   elemplo 1: number_format(1234.56)    //   returns 1: '1,235'
    //   elemplo 2: number_format(1234.56, 2, ',', ' ')    //   returns 2: '1 234,56'
    //   elemplo 3: number_format(1234.5678, 2, '.', '')    //   returns 3: '1234.57'
    //   elemplo 4: number_format(67, 2, ',', '.')    //   returns 4: '67,00'
    //   elemplo 5: number_format(1000)    //   returns 5: '1,000'
    //   elemplo 6: number_format(67.311, 2)    //   returns 6: '67.31'
    //   elemplo 7: number_format(1000.55, 1)    //   returns 7: '1,000.6'
    //   elemplo 8: number_format(67000, 5, ',', '.')    //   returns 8: '67.000,00000'
    //   elemplo 9: number_format(0.9, 0)    //   returns 9: '1'
    //  elemplo 10: number_format('1.20', 2)    //  returns 10: '1.20'
    //  elemplo 11: number_format('1.20', 4)    //  returns 11: '1.2000'
    //  elemplo 12: number_format('1.2000', 3)    //  returns 12: '1.200'
    //  elemplo 13: number_format('1 000,50', 2, '.', ' ')    //  returns 13: '100 050.00'
    //  elemplo 14: number_format(1e-8, 8, '.', '')    //  returns 14: '0.00000001'
    numero = (numero + '').replace(/[^0-9+\-Ee.]/g, '');
    const n = !isFinite(+numero) ? 0 : +numero;
    const prec = !isFinite(+cantDecimales) ? 0 : Math.abs(cantDecimales);
    const sep = (typeof sepMil === 'undefined') ? ',' : sepMil;
    const dec = (typeof sepDecimal === 'undefined') ? '.' : sepDecimal;
    let s = '';
    const toFixedFix = function (n, prec) {
        if (('' + n).indexOf('e') === -1) {
            return +(Math.round(n + 'e+' + prec) + 'e-' + prec);
        } else {
            const arr = ('' + n).split('e');
            let sig = '';
            if (+arr[1] + prec > 0) {
                sig = '+';
            }
            return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec);
        }
    };
    // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
window.addEventListener('load', function () {
    fntValidText();
    fntValidEmail();
    fntValidNumber();
}, false);

