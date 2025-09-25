document.querySelector('#portada').style.background = '#0000000';
console.log('212');

//console.log(document.querySelector('.portada').style.background);

document.querySelector('#section_footer').innerHTML = '';

var d = new Date(mantenimiento_hasta + 'UTC');
var curr_date = d.getDate();
var curr_month = d.getMonth() + 1; //Months are zero based
var curr_year = d.getFullYear();
console.log(curr_year + "-" + curr_month + "-" + curr_date);


var fecha_hasta = mantenimiento_hasta.split(' ')[0];
var hora_hasta = mantenimiento_hasta.split(' ')[1];

console.log(mantenimiento_hasta);
console.log(fecha_hasta);
console.log(hora_hasta);


simplyCountdown('#cuenta', {
  year: fecha_hasta.split('-')[0], // required
  month: fecha_hasta.split('-')[1], // required
  day: fecha_hasta.split('-')[2], // required
  hours: hora_hasta.split(':')[0], // Default is 0 [0-23] integer
  minutes: hora_hasta.split(':')[1], // Default is 0 [0-59] integer
  seconds: hora_hasta.split(':')[2], // Default is 0 [0-59] integer
  words: {//words displayed into the countdown
    days: 'Día',
    hours: 'Hora',
    minutes: 'Minuto',
    seconds: 'Segundo',
    pluralLetter: 's'
  },
  plural: true, //use plurals
  inline: false, //set to true to get an inline basic countdown like : 24 days, 4 hours, 2 minutes, 5 seconds
  inlineClass: 'simply-countdown-inline', //inline css span class in case of inline = true
  // in case of inline set to false
  enableUtc: false, //Use UTC as default
  onEnd: function () {
    location.href = base_url;
    /*document.getElementById('portada').classList.add('oculta');*/
    return;
  }, //Callback on countdown end, put your own function here
  refresh: 1000, // default refresh every 1s
  sectionClass: 'simply-section', //section css class
  amountClass: 'simply-amount', // amount css class
  wordClass: 'simply-word', // word css class
  zeroPad: false,
  countUp: false
});


////document.querySelectorAll('.simply-section').classList.add("col-md-3") ;
//
//// Select all the elements with example class.
//var section = document.querySelectorAll('.simply-amount');
//
//// Loop through the elements.
//for (var i = 0; i < section.length; i++) {
//  // Add the class margin to the individual elements.
//  section[i].classList.add('respon2');
//}
//var section = document.querySelectorAll('.simply-word');
//
//// Loop through the elements.
//for (var i = 0; i < section.length; i++) {
//  // Add the class margin to the individual elements.
//  section[i].classList.add('respon2');
//}