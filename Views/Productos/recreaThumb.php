<?php $arrData = json_encode($data['ArrJson'], JSON_UNESCAPED_UNICODE); ?>
<p id="completado">Completado 0/0</p>

<ul id="lista"></ul>


<script  >const base_url = '<?= base_url(); ?>'; const arrData = <?= $arrData ?>;</script>
<script type='text/javascript'>
  document.addEventListener('DOMContentLoaded', () => {
    processArray(arrData);
  });

//  arrData = JSON.parse(arrData);


  async function processArray(arrData) {
// console.log(arrData);
    let count = arrData.length;
    console.log(count);
    let cuenta = 1;

    for (const item of arrData) {
      cuenta = cuenta + 1;
      let id_prod = item.productoid;
      console.log(id_prod);

      let response = await recreaThumb(id_prod);

      let elemetoLi = document.getElementById('lista');
      let html_li = response + elemetoLi.innerHTML;
//      console.log(html_li);
      elemetoLi.innerHTML = html_li;
      let elemetoCompletado = document.getElementById('completado');
      elemetoCompletado.innerHTML = 'Completado ' + cuenta + '/' + (count + 1);
    }

  }


  async function recreaThumb(id) {
    let retornar;
    let url = base_url + 'productos/recreaThumbProcesar/' + id;
//    console.log(url);
    await fetch(url)
            .then(response => response.text()).then(data => {
      retornar = data;
    });
    return retornar;
  }



</script>