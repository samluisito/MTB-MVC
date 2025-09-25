

document.addEventListener('DOMContentLoaded', function () {
  time_ciclo(10, 20);
});


function time_ciclo(ciclo, min) {

  let time = 1000 * 60 * min;
  for (let i = 1; i < ciclo; i++) {
    delay(i, time);
  }
}

function delay(i, time) {
  setTimeout(() => {
//    let cabecera = {"Content-type": "application/x-www-form-urlencoded"};
    fetch(base_url + 'home/liveSesion')//, {method: "POST", headers: cabecera, body: arrData}
            .then(objData => objData.json()).then(objData => {
      let today = new Date();// obtener la fecha y la hora
      let now = today.toLocaleString();
      console.log(now);
      if (objData.status) {
        console.log(objData.msg);
      } else {
        console.log('session off');
      }
    }).catch(err => console.log(err));
  }, (time * i));
}


