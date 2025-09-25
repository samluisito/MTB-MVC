//al momento de cargar el documento ejecutara esta funcion y en esta funcion tendremos todo el script de datatable
var chartVisitas;
var chartVisitPorPag;
var chartVisitPorRegion;
var chartVisitPorLocalidad;
document.addEventListener('DOMContentLoaded', function () {
  var fecha = new Date();
  var anioActual = fecha.getFullYear();
  var mesActual = fecha.getMonth() + 1;
  tpoPagoMesAnio(anioActual, mesActual);
  ventasPorMes(anioActual, mesActual);
  ventasMensualesPorAnio(anioActual);
//  btnSearchVisitasMesAnio()
  visitantesVisitas('m', 'n', anioActual, mesActual, 'Argentina');
  visitPorPag('m', 'n', anioActual, mesActual);
  visitPorRegion('m', 'n', anioActual, mesActual);
  dolarPesoPorMes(anioActual, mesActual);
  estyle_head_datepicker();
});

function getWeekNumber(fecha) {
  // Copy date so don't modify original
  fecha = new Date(Date.UTC(fecha.getFullYear(), fecha.getMonth(), fecha.getDate()));
  // Set to nearest Thursday: current date + 4 - current day number
  // Make Sunday's day number 7
  fecha.setUTCDate(fecha.getUTCDate() + 4 - (fecha.getUTCDay() || 7));
  // Get first day of year
  var yearStart = new Date(Date.UTC(fecha.getUTCFullYear(), 0, 1));
  // Calculate full weeks to nearest Thursday
  var weekNo = Math.ceil((((fecha - yearStart) / 86400000) + 1) / 7);
  // Return array of year and week number
  //return [fecha.getUTCFullYear(), weekNo];

  if (weekNo <= 9) {
    weekNo = '0' + weekNo;
  }
  // Return week number
  return weekNo;
}

function estyle_head_datepicker() {
  var css = '.ui-datepicker-calendar {display: none;}',
          head = document.head || document.getElementsByTagName('head')[0],
          style = document.createElement('style');
  head.appendChild(style);
  style.type = 'text/css';
  if (style.styleSheet) {// This is required for IE8 and below.
    style.styleSheet.cssText = css;
  } else {
    style.appendChild(document.createTextNode(css));
  }
}
/*Selector de color ============================================================================*/
function getChartColorsArray(e) {
  if (null !== document.getElementById(e)) {
    let r = document.getElementById(e).getAttribute("data-colors");
    return (r = JSON.parse(r)).map(function (e) {
      let r = e.replace(" ", "");
      if (-1 === r.indexOf(",")) {
        let t = getComputedStyle(document.documentElement).getPropertyValue(r);
        return t || r;
      }
      let a = e.split(",");
      return 2 != a.length ? r : "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(a[0]) + "," + a[1] + ")";
    });
  }
}


/*Grafico Circular pagoMesAnio ------------------------------------------------*/
function btnSearchPagoMesAnio() {
  let fecha = document.querySelector('.pagoMes').value; //captura el valor de la fecha
  if (fecha === '' || fecha.length < 6) {  // si la fecha no es correcta 
    swal('', 'La fecha ingrasada no es correcta', 'error');
    return false;
  }
  fecha = fecha.split('-'); // convierte el string de fecha en un array , segun el elemto separador ingresado
  tpoPagoMesAnio(fecha[1], fecha[0]);
}
function tpoPagoMesAnio(anio, mes) {

  if (document.getElementById('pagoMesAnio')) {
    fetch(base_url + 'dashboard/getPagosAnioMes/' + anio + '/' + mes)
            .then(response => response.json())
            .then((objData) => {

              let mes = objData.mes;
              let anio = objData.anio;
              var arrDatos = [];
              for (var i = 0; i < objData.tipospago.length; i++) {
                let a = {
                  name: new String(objData.tipospago[i].nombre_tpago),
                  y: new Number(objData.tipospago[i].total)
                };
                arrDatos.push(a)
              }

              // var arrDatos = new String(JSON.stringify(arrDatos));
              var arrDatos = JSON.stringify(arrDatos);
              var arrDatos = JSON.parse(arrDatos);
              Highcharts.chart('pagoMesAnio', {
                chart: {
                  plotBackgroundColor: null,
                  plotBorderWidth: null,
                  plotShadow: false,
                  type: 'pie'
                },
                title: {
                  text: 'Browser market shares in January, 2018'
                },
                tooltip: {
                  pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                  point: {
                    valueSuffix: '%'
                  }
                },
                plotOptions: {
                  pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                      enabled: true,
                      format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                  }
                },
                series: [{
                    name: 'Brands',
                    colorByPoint: true,
                    data:
                            arrDatos
                            //[{name:"PayPal","y":3300}]
                            // [{name: 'Chrome', y: 61.41}, {name: 'Internet Explorer', y: 11.84}]

                  }]
              });
            });
  }


}

/*Grafico Lineal ventasPorMes ------------------------------------------------*/
function btnSearchVentasMesAnio() {
  let fecha = document.querySelector('.ventasMes').value;
  fecha = fecha.split('-');
  ventasPorMes(fecha[1], fecha[0]);
}
function ventasPorMes(anio, mes) {
  if (document.getElementById('ventasPorMes')) {
    fetch(base_url + base_url + 'dashboard/getVentasAnioMes/' + anio + '/' + mes)
            .then(response => response.json())
            .then((objData) => {
              let mes = objData.mes;
              let anio = objData.anio;
              let totalMes = objData.total_v;
              let dias = [];
              let totales = [];
              /*mes seleccionado*/
              for (let venta of objData.ventas) {
                dias.push(venta.dia);
                totales.push(venta.total);
              }
              /*mes previo*/
              let totalMes_p = objData.total_v_prev;
              let mes_p = objData.mes_prev;
              let dias_p = [];
              let totales_p = [];
              for (let venta of objData.ventas_prev) {
                dias_p.push(venta.dia);
                totales_p.push(venta.total);
              }

              let diasmax = dias.length >= dias_p.length ? dias : diasmax = dias_p;
              // divLoading.style.display = "none";// oculta la imagen de la espera de la carga del del formulario 
              Highcharts.chart('ventasPorMes', {
                chart: {
                  type: 'line'
                },
                title: {
                  text: 'Ventas de ' + mes + ' de ' + anio
                },
                subtitle: {
                  text: ''
                },
                xAxis: {
                  categories: diasmax //['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },
                yAxis: {
                  title: {
                    text: 'Monto total de ventas por dia'
                  }
                },
                plotOptions: {
                  line: {
                    dataLabels: {
                      enabled: true
                    },
                    enableMouseTracking: false
                  }
                },
                series: [{
                    name: 'Ventas ' + mes + ' = ' + totalMes,
                    data: totales //[7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
                  }, {
                    name: 'Ventas ' + mes_p + ' = ' + totalMes_p,
                    data: totales_p //[3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
                  }]
              });
            });
  }
}

/*Grafico BarrDatos ventasPorAnio ------------------------------------------------*/
function btnSearchVentasAnualPorMes() {
  let fecha = document.querySelector('.ventasAnualPorMes').value;
  if (fecha === '' || fecha.length < 4) {
    swal('', 'La fecha ingrasada no es correcta', 'error');
    return false;
  }
//   fecha = fecha.split('-');
//ventasPorMes(fecha[1], fecha[0]);
  ventasMensualesPorAnio(fecha);
}
function enterSearchVentasAnualPorMes(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    e.preventDefault();
    //var boton = document.getElementById("boton");
    //angular.element(boton).triggerHandler('click');
    let fecha = document.querySelector('.ventasAnualPorMes').value;
    if (fecha === '' || fecha.length < 4) {
      swal('', 'La fecha ingrasada no es correcta', 'error');
      return false;
    }
//   fecha = fecha.split('-');
//ventasPorMes(fecha[1], fecha[0]);
    ventasMensualesPorAnio(fecha);
  }
}
function ventasMensualesPorAnio(anio) {
  fetch(base_url + 'dashboard/getVentasMensuales/' + anio)
          .then(response => response.json())
          .then((objData) => {
            let totalVentas = 0;
            let arrDataMes = new Array();
            let arrDataVentas = new Array();
            for (let data of objData) {
              //let a = [data.mes, data.t_ventas];
              totalVentas += data.t_ventas;
              arrDataMes.push(data.mes);
              arrDataVentas.push(data.t_ventas);
            }
            totalVentas = "$ " + totalVentas; //"+ thousands"

            if (document.querySelector('#column_chart')) {
              let options = {series: [{data: arrDataVentas}],
                chart: {type: "line", width: 130, height: 55, sparkline: {enabled: !0}},
                colors: getChartColorsArray("mini-1"),
                stroke: {curve: "smooth", width: 2.5},
                tooltip: {
                  fixed: {enabled: !1},
                  x: {show: !1},
                  y: {
                    title: {
                      formatter: function (e) {
                        return "";
                      }
                    }
                  },
                  marker: {show: !1}
                }
              };
              let chart = new ApexCharts(document.querySelector("#mini-1"), options).render();
              options = {
                chart: {height: 410, type: "bar", toolbar: {show: !1}},
                plotOptions: {bar: {borderRadius: 3, horizontal: !1, columnWidth: "64%", endingShape: "rounded"}
                },
                dataLabels: {enabled: !1},
                stroke: {show: !0, width: 2, colors: ["transparent"]},
                series: [
                  {name: "Net Profit", data: arrDataVentas}, //[95, 40, 73, 60, 51, 37, 30]
                  {name: "Revenue", data: arrDataVentas}//[75, 26, 53, 44, 37, 26, 23]
                ],
                colors: getChartColorsArray("column_chart"),
                xaxis: {categories: arrDataMes}, // ["Jan", "Feb", "Mar", "Apr", "May", "June", "July"]
                grid: {borderColor: "#f1f1f1"},
                fill: {opacity: 1},
                legend: {show: !1},
                tooltip: {
                  y: {
                    formatter: function (e) {
                      return "$ " + e; //"+ thousands"
                    }
                  }
                }
              };
              chart = new ApexCharts(document.querySelector("#column_chart"), options).render();
            }
            if (document.querySelector('#ventasPorAnio')) {
              Highcharts.chart('ventasPorAnio', {
                chart: {type: 'column'},
                title: {text: 'Ventas mensuales del ' + anio},
                subtitle: {text: ''}, //'Source: <a href="http://en.wikipedia.org/wiki/List_of_cities_proper_by_population">Wikipedia</a>'
                xAxis: {type: 'category',
                  labels: {rotation: -45,
                    style: {fontSize: '13px', fontFamily: 'Verdana, sans-serif'
                    }
                  }
                },
                yAxis: {min: 0,
                  title: {text: 'Monto total de ventas por mes'}
                },
                legend: {enabled: false},
                tooltip: {pointFormat: ' Monto total de ventas : <b>{point.y:3,3,3,3.2f} </b>'},
                series: [{
                    name: 'Population',
                    data: arrData,
                    dataLabels: {
                      enabled: true,
                      rotation: -90,
                      color: '#FFFFFF',
                      align: 'right',
                      format: '{point.y:3,3,3,3.2f}', // 2 decimal, espacio por miles
                      y: 10, // 10 pixels down from the top
                      style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                      }
                    }
                  }]
              });
            }

          });
}



/*Graficos de Visitas ============================================================================*/

/*VisitasVisitantes------------------------------------------------------------------------------------------------*/
function visitMesMostrarpor() {
  let visitMostrarpor = document.querySelector('#selectVisitasMostrarPor').value;
  let fecha_type = document.querySelector('#dateVisitas');
  let fecha_type_label = document.querySelector('#labelVisitas');

  let anio_max = f_visit_hasta.getFullYear();
  let anio_min = f_visit_desde.getFullYear();

  let mes_max = f_visit_hasta.getMonth() + 1;
  let mes_min = f_visit_desde.getMonth() + 1;

  let semana_min = 'W' + getWeekNumber(f_visit_desde);
  let semana_max = 'W' + getWeekNumber(f_visit_hasta);

  if (visitMostrarpor === 'm') {
    fecha_type.setAttribute("min", anio_min + '-' + mes_min);
    fecha_type.setAttribute("max", anio_max + '-' + mes_max);
    fecha_type.type = 'month';
    let cero = mes_max <= 9 ? '0' : '';
    fecha_type.value = anio_max + '-' + cero + mes_max;
    fecha_type_label.innerHTML = 'Mes';
  } else if (visitMostrarpor === 's') {
    fecha_type.setAttribute("min", anio_min + '-' + semana_min);
    fecha_type.setAttribute("max", anio_max + '-' + semana_max);
    fecha_type.type = 'week';
    fecha_type.value = anio_max + '-' + semana_max;
    fecha_type_label.innerHTML = 'Semana';
  }

}

function btnSearchVisitasMesAnio() {
  let fecha = document.querySelector('#dateVisitas').value;
  let mostrar_por = document.querySelector('#selectVisitasMostrarPor').value;

  fecha = fecha.split('-');
  visitantesVisitas(mostrar_por, 'a', fecha[0], fecha[1]);
}

function visitantesVisitas(mostrar_por, accion, anio, mes) {
  let pais = document.querySelector('#selectVisitasPais').value;

  fetch(base_url + 'dashboard/getVisitAnioMes/' + mostrar_por + '/' + anio + '/' + mes + '?pais=' + pais)
          .then(response => response.json())
          .then((objData) => {

            let anio = objData.anio;
            let mes = objData.mes;
            let dias = [];
            /*Pag Visitadas*/
            let total_pagVisitadas = objData.total_pagVisitadas;
            let pv_x_mes = [];
            for (let p_visit of objData.pagVisitadas) {
              dias.push(p_visit.dia);
              pv_x_mes.push(p_visit.total);
            }
            /*Visitantes*/
            let total_visitantes = objData.total_visitantes;
            let visitantes_x_mes = [];
            for (let visitas of objData.visitantes) {
              visitantes_x_mes.push(visitas.total);
            }


            if (accion === 'n') {//crea un nuevo chart
              nuevoChartVisitantesVisitas(anio, mes, dias, total_pagVisitadas, pv_x_mes, total_visitantes, visitantes_x_mes);
            } else if (accion === 'a') {//actualiza las series
              actualizaChartVisitantesVisitas(anio, mes, dias, total_pagVisitadas, pv_x_mes, total_visitantes, visitantes_x_mes);
            }
          });
}
function nuevoChartVisitantesVisitas(anio, mes, dias, total_pagVisitadas, pv_x_mes, total_visitantes, visitantes_x_mes) {
  //Nuevo Chart
//  if (document.querySelector('#Chart-Line-Data-Labels-Visitantes-Visitas')) {
  let options = {
    series: [
      {name: "Paginas Visitadas " + total_pagVisitadas, data: pv_x_mes}, //+ total_pagVisitadas
      {name: "Visitantes " + total_visitantes, data: visitantes_x_mes//+ total_visitantes
      }
    ],
    chart: {
      height: 350, type: 'line',
      dropShadow: {enabled: true, color: '#000', top: 18, left: 7, blur: 10, opacity: 0.2},
      toolbar: {show: false}
    },
    colors: getChartColorsArray('Chart-Line-Data-Labels-Visitantes-Visitas'),
    dataLabels: {enabled: true},
    stroke: {curve: 'straight'}, //smooth
    title: {text: 'Visitas y Visitantes ' + mes, align: 'left'
    },
    grid: {borderColor: '#e7e7e7',
      row: {colors: ['#f3f3f3', 'transparent'], opacity: 0.5}// toma una matriz que se repetirá en las columnas
    },
    markers: {size: 1},
    xaxis: {categories: dias,
      title: {text: 'Dias'}
    },
    yaxis: {
      title: {text: 'Totales'}, //min: 30, //max: 40
    },
    legend: {
      position: 'top',
      horizontalAlign: 'right',
      floating: true,
      offsetY: -25,
      offsetX: -5
    }
  };
  chartVisitas = new ApexCharts(document.querySelector("#Chart-Line-Data-Labels-Visitantes-Visitas"), options);
  chartVisitas.render();

//  }
//Anterior Chart
  /*
   if (document.querySelector('#chartVisitaVisitante')) {
   Highcharts.chart('chartVisitaVisitante', {
   chart: {
   type: 'line'
   },
   title: {
   text: 'Total Paginas Visitadas y Visitantes de ' + mes + ' de ' + anio
   },
   subtitle: {
   text: ''
   },
   xAxis: {
   categories: dias //['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
   },
   yAxis: {
   title: {
   text: 'Total Paginas Visitadas y Visitantes por dia'
   }
   },
   plotOptions: {
   line: {
   dataLabels: {
   enabled: true
   },
   enableMouseTracking: false
   }
   },
   series: [{
   name: 'Paginas Visitas ' + mes + ' = ' + total_pagVisitadas,
   data: total_pv //[7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
   }, {
   name: 'Visitantes ' + mes + ' = ' + total_visitantes,
   data: total_v //[3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
   }]
   });
   }*/
}
function actualizaChartVisitantesVisitas(anio, mes, dias, total_pagVisitadas, pv_x_mes, total_visitantes, visitantes_x_mes) {
  //Nuevo Chart
//  if (document.querySelector('#Chart-Line-Data-Labels-Visitantes-Visitas')) {
  let optionsUpdate = {
    title: {text: 'Visitas y Visitantes ' + mes, align: 'left'},
    xaxis: {categories: dias, title: {text: 'Dias'}},
    yaxis: {title: {text: 'Totales'}}
  };
  chartVisitas.updateOptions(optionsUpdate);

  let seriesUpdate = [
    {name: "Paginas Visitadas " + total_pagVisitadas, data: pv_x_mes}, //+ total_pagVisitadas
    {name: "Visitantes " + total_visitantes, data: visitantes_x_mes//+ total_visitantes
    }
  ];
  chartVisitas.updateSeries(seriesUpdate);

//  }

}

/*VisitPorPag------------------------------------------------------------------------------------------------*/
function selectVisitPorPag() {
  let visitMostrarpor = document.querySelector('#selectVisitPorPag').value;
  let fecha_type = document.querySelector('#dateVisitPorPag');
  let fecha_type_label = document.querySelector('#labelVisitPorPag');

  let anio_max = f_visit_hasta.getFullYear();
  let anio_min = f_visit_desde.getFullYear();

  let mes_max = f_visit_hasta.getMonth() + 1;
  mes_max = mes_max <= 9 ? '0' + mes_max : mes_max;
  let mes_min = f_visit_desde.getMonth() + 1;
  mes_min = mes_min <= 9 ? '0' + mes_min : mes_min;

  let dia_max = f_visit_hasta.getDate() + 1;
  dia_max = dia_max <= 9 ? '0' + dia_max : dia_max;

  let dia_min = f_visit_desde.getDate() + 1;
  dia_min = dia_min <= 9 ? '0' + dia_min : dia_min;

  let semana_min = 'W' + getWeekNumber(f_visit_desde);
  let semana_max = 'W' + getWeekNumber(f_visit_hasta);

  if (visitMostrarpor === 'm') {
    fecha_type.setAttribute("min", anio_min + '-' + mes_min);
    fecha_type.setAttribute("max", anio_max + '-' + mes_max);
    fecha_type.type = 'month';
    fecha_type.value = anio_max + '-' + mes_max;
    fecha_type_label.innerHTML = 'Mes';
  } else if (visitMostrarpor === 's') {
    fecha_type.setAttribute("min", anio_min + '-' + semana_min);
    fecha_type.setAttribute("max", anio_max + '-' + semana_max);
    fecha_type.type = 'week';
    fecha_type.value = anio_max + '-' + semana_max;
    fecha_type_label.innerHTML = 'Semana';

  } else if (visitMostrarpor === 'd') {
    fecha_type.setAttribute("min", anio_min + '-' + mes_min + '-' + dia_max);
    fecha_type.setAttribute("max", anio_max + '-' + mes_max + '-' + dia_max);
    fecha_type.type = 'date';
    fecha_type.value = anio_max + '-' + mes_max + '-' + dia_max;
    fecha_type_label.innerHTML = 'Dia';

  }

}
function btnSearchVisitPorPag() {
  let fecha = document.querySelector('#dateVisitPorPag').value;
  let mostrar_por = document.querySelector('#selectVisitPorPag').value;

  fecha = fecha.split('-');
  visitPorPag(mostrar_por, 'a', fecha[0], fecha[1], fecha[2]);
}

function visitPorPag(mostrar_por, accion, anio, mes, dia = null) {
  let pais = document.querySelector('#selectVisitasPais').value;

  dia = mostrar_por === 'd' ? '/' + dia : '';
  fetch(base_url + 'dashboard/getVisitPorPag/' + mostrar_por + '/' + anio + '/' + mes + dia + '?pais=' + pais)
          .then(response => response.json())
          .then((objData) => {
            let array_pag_name = [];// objData.pagina;
            let array_pag_count = [];// objData.cantidad;
            for (let p_visit of objData) {
              array_pag_name.push(p_visit.pagina);
              array_pag_count.push(Number(p_visit.cantidad));
            }

            if (accion === 'n') {//crea un nuevo chart
              nuevoChartVisitPorPag(array_pag_name, array_pag_count);
            } else if (accion === 'a') {//actualizaChart las series
              actualizaChartVisitPorPag(array_pag_name, array_pag_count);
            }


          });
}
function nuevoChartVisitPorPag(array_pag_name, array_pag_count) {//getChartColorsArray("pie_chart"),
//  if (document.querySelector('#Chart-Visitas-por-pagina')) {
  let colors = chartBarColors = getChartColorsArray("pie_chart");

  var options = {
    series: [{name: 'Paginas', data: array_pag_count}],
    annotations: {
      points: [{
          x: 'Bananas',
          seriesIndex: 0,
          label: {
            borderColor: '#775DD0',
            offsetY: 0,
            style: {
              color: '#fff',
              background: '#775DD0',
            },
            text: 'Bananas are good',
          }
        }]
    },
    chart: {height: 350, type: 'bar'},
    plotOptions: {bar: {borderRadius: 10, columnWidth: '50%', }},
    dataLabels: {enabled: false}, stroke: {width: 2},

    grid: {row: {colors: ['#fff', '#f2f2f2']}},
    xaxis: {labels: {rotate: -45}, categories: array_pag_name, tickPlacement: 'on'},
    yaxis: {
      title: {text: 'Visitas', },
    },
    fill: {
      type: 'gradient',
      gradient: {
        shade: 'light',
        type: "horizontal",
        shadeIntensity: 0.25,
        gradientToColors: undefined,
        inverseColors: true,
        opacityFrom: 0.85,
        opacityTo: 0.85,
        stops: [50, 0, 100]
      },
    }
  };



  chartVisitPorPag = new ApexCharts(document.querySelector("#Chart-Visitas-por-pagina"), options);
  chartVisitPorPag.render();
//  }


}
function actualizaChartVisitPorPag(array_pag_name, array_pag_count) {
  let options = {
    series: [{name: 'Paginas', data: array_pag_count}],
    xaxis: {categories: array_pag_name, },
  };
  chartVisitPorPag.updateOptions(options);

}

/*Visitas por region------------------------------------------------------------------------------------------------*/
function selectVisitPorRegion() {
  let visitMostrarpor = document.querySelector('#selectVisitPorRegion').value;
  let fecha_type = document.querySelector('#dateVisitPorRegion');
  let fecha_type_label = document.querySelector('#labelVisitPorRegion');

  let anio_max = f_visit_hasta.getFullYear();
  let anio_min = f_visit_desde.getFullYear();

  let mes_max = f_visit_hasta.getMonth() + 1;
  mes_max = mes_max <= 9 ? '0' + mes_max : mes_max;
  let mes_min = f_visit_desde.getMonth() + 1;
  mes_min = mes_min <= 9 ? '0' + mes_min : mes_min;

  let dia_max = f_visit_hasta.getDate() + 1;
  dia_max = dia_max <= 9 ? '0' + dia_max : dia_max;

  let dia_min = f_visit_desde.getDate() + 1;
  dia_min = dia_min <= 9 ? '0' + dia_min : dia_min;

  let semana_min = 'W' + getWeekNumber(f_visit_desde);
  let semana_max = 'W' + getWeekNumber(f_visit_hasta);

  if (visitMostrarpor === 'm') {
    fecha_type.setAttribute("min", anio_min + '-' + mes_min);
    fecha_type.setAttribute("max", anio_max + '-' + mes_max);
    fecha_type.type = 'month';
    fecha_type.value = anio_max + '-' + mes_max;
    fecha_type_label.innerHTML = 'Mes';
  } else if (visitMostrarpor === 's') {
    fecha_type.setAttribute("min", anio_min + '-' + semana_min);
    fecha_type.setAttribute("max", anio_max + '-' + semana_max);
    fecha_type.type = 'week';
    fecha_type.value = anio_max + '-' + semana_max;
    fecha_type_label.innerHTML = 'Semana';

  } else if (visitMostrarpor === 'd') {
    fecha_type.setAttribute("min", anio_min + '-' + mes_min + '-' + dia_max);
    fecha_type.setAttribute("max", anio_max + '-' + mes_max + '-' + dia_max);
    fecha_type.type = 'date';
    fecha_type.value = anio_max + '-' + mes_max + '-' + dia_max;
    fecha_type_label.innerHTML = 'Dia';

  }

}
function btnSearchVisitPorRegion() {
  let fecha = document.querySelector('#dateVisitPorRegion').value;
  let mostrar_por = document.querySelector('#selectVisitPorRegion').value;

  fecha = fecha.split('-');
  visitPorRegion(mostrar_por, 'a', fecha[0], fecha[1], fecha[2]);
}
let accion_loc = 'n';
let temp_array_pag_name;
function visitPorRegion(mostrar_por, accion, anio, mes, dia = null) {
  let pais = document.querySelector('#selectVisitasPais').value;

  dia = mostrar_por === 'd' ? '/' + dia : '';
  fetch(base_url + 'dashboard/getVisitPorRegion/' + mostrar_por + '/' + anio + '/' + mes + dia + '?pais=' + pais)
          .then(response => response.json())
          .then((objData) => {
            let array_pag_name = [];// objData.pagina;
            let array_pag_count = [];// objData.cantidad;
            for (let p_visit of objData) {
              array_pag_name.push(p_visit.ciudad);
              array_pag_count.push(Number(p_visit.cantidad));
            }

//            temp_array_pag_name = array_pag_name.length === 0 ? temp_array_pag_name : array_pag_name;
            temp_array_pag_name = array_pag_name;
            if (accion === 'n') {//crea un nuevo chart
              nuevoChartVisitPorRegion(temp_array_pag_name, array_pag_count);

            } else if (accion === 'a') {//actualiza las series
              actualizaChartVisitPorRegion(temp_array_pag_name, array_pag_count);
              if (chartVisitPorLocalidad != '') {
                if (accion_loc !== 'n') {
                  chartVisitPorLocalidad.destroy();
                }
                accion_loc = 'n';
              }

            }
          });
}
function nuevoChartVisitPorRegion(array_pag_name, array_pag_count) {//getChartColorsArray("pie_chart"),
//  if (document.querySelector('#Chart-Visitas-por-pagina')) {
  let colors = chartBarColors = getChartColorsArray("pie_chart");

  var options = {
    series: [{data: array_pag_count}],
    chart: {type: 'bar', height: 350,
      events: {
        dataPointSelection: function (e) {
          let ele = e.currentTarget;
          let pos = ele.getAttribute('j');
          let localidad = temp_array_pag_name[pos];
          visitPorLocalidad(localidad);//.
        }}
    },
    plotOptions: {
      bar: {borderRadius: 4, horizontal: true}
    },
    dataLabels: {enabled: false},
    xaxis: {categories: array_pag_name},

  };

  chartVisitPorRegion = new ApexCharts(document.querySelector("#Chart-VisitPorRegion"), options);
  chartVisitPorRegion.render();
}
function actualizaChartVisitPorRegion(array_pag_name, array_pag_count) {
  let options = {
    series: [{name: 'Ciudades', data: array_pag_count}],
    xaxis: {categories: array_pag_name, }
  };
  chartVisitPorRegion.updateOptions(options);

}


function visitPorLocalidad(region_name) {
  let fecha = document.querySelector('#dateVisitPorRegion').value;
  fecha = fecha.split('-');
  let anio = fecha[0];
  let mes = fecha[1];
  let dia = fecha[2];
  let mostrar_por = document.querySelector('#selectVisitPorRegion').value;


  dia = mostrar_por === 'd' ? '/' + dia : '';
  fetch(base_url + 'dashboard/getVisitPorRegion/' + mostrar_por + '/' + anio + '/' + mes + dia + '?ciudad=' + region_name)
          .then(response => response.json())
          .then((objData) => {
            let array_pag_name = [];// objData.pagina;
            let array_pag_count = [];// objData.cantidad;
            for (let p_visit of objData) {
              array_pag_name.push(p_visit.localidad);
              array_pag_count.push(Number(p_visit.cantidad));
            }
            if (accion_loc === 'n') {//crea un nuevo chart
              accion_loc = 'a';
              nuevoChartVisitPorLocalidad(array_pag_name, array_pag_count);
            } else if (accion_loc === 'a') {//actualiza las series
              actualizaChartVisitPorLocalidad(array_pag_name, array_pag_count);
            }
          });
}
function nuevoChartVisitPorLocalidad(array_pag_name, array_pag_count) {
  let options_localidad = {
    series: [{
        name: 'Localidad',
        data: array_pag_count
      }],
    chart: {
      type: 'bar',
      height: 350
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '55%',
        endingShape: 'rounded'
      },
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent']
    },
    xaxis: {categories: array_pag_name},
    yaxis: {
      title: {
        text: 'Visitas'
      }
    },
    fill: {
      opacity: 1
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return "$ " + val + " thousands"
        }
      }
    }
  };

  chartVisitPorLocalidad = new ApexCharts(document.querySelector("#Chart-VisitPorLocalidad"), options_localidad);
  chartVisitPorLocalidad.render();
}


function actualizaChartVisitPorLocalidad(array_pag_name, array_pag_count) {
  let options = {
    series: [{name: 'Localidad', data: array_pag_count}],
    xaxis: {categories: array_pag_name, },
  };
  chartVisitPorLocalidad.updateOptions(options);

}
/*Graficos de Dolar ==============================================================================*/
function btnSearchDolarPesoMesAnio() {
  let fecha = document.querySelector('#dateVisitas').value;
  fecha = fecha.split('-');
  dolarPesoPorMes(fecha[1], fecha[0]);
}
function dolarPesoPorMes(anio, mes) {
  fetch(base_url + 'dashboard/getDolarPesoAnioMes/' + anio + '/' + mes)
          .then(response => response.json())
          .then((objData) => {

            let anio = objData.anio;
            let mes = objData.mes;
            document.getElementById('fecha_maximo_dia_blue').innerHTML = objData.dolar_maximo_periodo.fecha;
            document.getElementById('data_compra_blue').innerHTML = objData.dolar_maximo_periodo.blue_compra;
            document.getElementById('data_venta_blue').innerHTML = objData.dolar_maximo_periodo.blue_venta;
            document.getElementById('data_compra_oficial').innerHTML = objData.dolar_maximo_periodo.oficial_compra;
            document.getElementById('data_venta_oficial').innerHTML = objData.dolar_maximo_periodo.oficial_venta;
            /*Cotizacion por dia*/
            let dias = [];
            let arr_oficial_compra_mes = [];
            let arr_oficial_venta_mes = [];
            let arr_blue_compra_mes = [];
            let arr_blue_venta_mes = [];
            for (let cotizacion_dia of objData.dolar_por_dia) {
              dias.push(cotizacion_dia.dia);
              arr_oficial_compra_mes.push(cotizacion_dia.oficial_compra);
              arr_oficial_venta_mes.push(cotizacion_dia.oficial_venta);
              arr_blue_compra_mes.push(cotizacion_dia.blue_compra);
              arr_blue_venta_mes.push(cotizacion_dia.blue_venta);
            }

            let options = {
              chart: {
                type: 'line',
                height: 380,
                //stacked: true,
                zoom: {enabled: false},
                toolbar: {show: false},
                //dropShadow: {enabled: true, color: '#000', top: 18, left: 7, blur: 10, opacity: 0.2}
              },
              //colors: getChartColorsArray('Chart-Line-Data-Labels-Dolar-Peso-Periodo'),
              dataLabels: {enabled: false},
              stroke: {width: [3, 3], curve: "straight"}, //{curve: 'smooth'},
              series: [
                {name: "Dolar Blue Venta", data: arr_blue_venta_mes},
                {name: "Dolar Blue Compra", data: arr_blue_compra_mes},

                {name: "Dolar Oficial Venta", data: arr_oficial_venta_mes},
                {name: "Dolar Oficial Compra", data: arr_oficial_compra_mes},
              ],
              title: {text: 'Evolucion del dolar en ' + mes, align: 'left', style: {fontWeight: 500}},
              grid: {borderColor: '#e7e7e7',
                row: {colors: ['#f3f3f3', 'transparent'], opacity: 0.2}//0.5 toma una matriz que se repetirá en las columnas
              },
              markers: {style: "inverted", size: 3}, //1
              xaxis: {categories: dias, title: {text: 'Dias'}},
              yaxis: {title: {text: 'Cotizacion'}}, //min: 30, //max: 40
              legend: {
                position: 'top',
                horizontalAlign: 'right',
                floating: true,
                offsetY: -25,
                offsetX: -5
              },
              responsive: [{
                  breakpoint: 480,
                  options: {chart: {toolbar: {show: !1}}, legend: {show: !1, position: 'bottom', offsetX: -10, offsetY: 0
                    }
                  }
                }],
              plotOptions: {bar: {horizontal: false, borderRadius: 1}},
            };
            let chart = new ApexCharts(document.querySelector("#Chart-Line-Data-Labels-Dolar-Peso-Periodo"), options);
            chart.render();
          });
}
