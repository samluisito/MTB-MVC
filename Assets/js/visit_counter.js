function sumarDias(fecha, dias) {
  /*devuelve una fecha con el formato AAAA MM DD sumandole un dia a la fecha*/
  fecha.setDate(fecha.getDate() + dias);
  return fecha;
}
function fechadehoy() {
  /*devuelve una fecha con el formato AAAA MM DD*/
  let d = new Date();
  return new Date(d.getFullYear(), d.getMonth(), d.getDate());
}

function getVisitado() {
  /*busca en el sessionStorage si la pagina ha sido visitada anteriormente y la devuelve*/
  /*si no hay registro de visita devuelve la fecha actual*/
  /*se agrega metodo para limpiar sessionStorage de visitas anteriores a hoy*/

  let pagv_recuperados = JSON.parse(sessionStorage.getItem('pagv'));//busca en el sesion storage las paginas visitadas
  if (pagv_recuperados) {//si hay existe y hay datos
    let pagv_temporal = [];//declara una variable tipo array 
    let fecha_retorno = ''; //declara variable para fecha tipo string 
    for (var i = 0; i < pagv_recuperados.length; i++) {//se recorre el array recuperado del local session 
      if (pagv_recuperados[i].page_title == document.title) {// se compara si el nombre de la pagina adctual y el nombre de la pagina guardada en el array son el mismo 
        fecha_retorno = pagv_recuperados[i].fecha_visit;// si es el mimo, se pasa la fecha del elemento array a la variable fecha retorno 
      }
      let fecha_recu = new Date(pagv_recuperados[i].fecha_visit);// a la fecha recuperada se convierte objeto fecha 
      if (fecha_recu.valueOf() > fechadehoy().valueOf()) {// comparamos si la fecha de visita es mayor a hoy 
        pagv_temporal.push({'page_title': pagv_recuperados[i].page_title, 'fecha_visit': pagv_recuperados[i].fecha_visit});
      }
    }

    // sessionStorage.setItem('pagv', JSON.stringify(pagv_temporal));

    if (fecha_retorno === '') {
      return fechadehoy();
    } else {
      return new Date(fecha_retorno);
    }
  } else {
    return fechadehoy();
  }
}

function idnav() {
  var idlocal = localStorage.getItem('visid');//busca en el sesion storage las paginas visitadas

  if (idlocal) {//si hay existe y hay datos
    //console.log('LocalId: ' + idlocal);
    return idlocal;
  } else {
    fetch(base_url + 'visitas/getUnicoId')
            .then(response => response.json())
            .then(objData => {
              idlocal = objData.id;
              localStorage.setItem('visid', idlocal);
            });
  }
  return idlocal;
}

/*  
 console.log(idnav());
 
 console.log('getVisitado ' + String(getVisitado().valueOf()) + ' es <= a hoy ' + String(fechadehoy().valueOf()) + '? ' + String(getVisitado().valueOf() <= fechadehoy().valueOf()));
 */
/*CONTADOR DE VISITAS*/
if (getVisitado().valueOf() <= fechadehoy().valueOf()) {
  registrarVisita();

  function setVisita() {

    if (sessionStorage.getItem('pagv')) {

      let pagv_recuperados = JSON.parse(sessionStorage.getItem('pagv'));
      let pagv_temporal = [];

      if (sessionStorage.getItem('pagv').length) {

        for (var i = 0; i < pagv_recuperados.length; i++) {
          let fecha_recu = new Date(pagv_recuperados[i].fecha_visit);
          if (fecha_recu.valueOf() > fechadehoy().valueOf()) {
            pagv_temporal.push({'page_title': pagv_recuperados[i].page_title, 'fecha_visit': pagv_recuperados[i].fecha_visit});
          }
        }
      }

      pagv_temporal.push({'page_title': document.title, 'fecha_visit': sumarDias(fechadehoy(), 1)});
      sessionStorage.setItem('pagv', JSON.stringify(pagv_temporal));
    } else {
      let sessionvisit = [{'page_title': document.title, 'fecha_visit': sumarDias(fechadehoy(), 1)}];
      sessionStorage.setItem('pagv', JSON.stringify(sessionvisit));
    }
  }

  async function registrarVisita() {

    // Preferiblemente debería ser la URL absoluta
    // Ejemplo: http://localhost/contador_visitas_php_avanzado/contador/registrar_visita.php
    let url = base_url + "Visitas/registrar_visita";
    let payload = {
      pagina: document.title,
      url: window.location.href,
      idnav: idnav()
    };
    let respuestaRaw = await fetch(url, {
      method: "POST",
      body: JSON.stringify(payload)
    });
    let respuesta = await respuestaRaw.json();
    if (respuesta) {
      //console.log('visita registrada en servidor');
      setVisita();
    }
  }
}
