FullCalendar.globalLocales.push(function () {
    'use strict';

    var esUs = {
        code: "es",
        week: {
            dow: 0, // Sunday is the first day of the week.
            doy: 6  // The week that contains Jan 1st is the first week of the year.
        },
        buttonText: {
            prev: "Ant",
            next: "Sig",
            today: "Hoy",
            month: "Mes",
            week: "Semana",
            day: "Día",
            list: "Agenda"
        },
        weekText: "Sm",
        allDayText: "Todo el día",
        moreLinkText: "más",
        noEventsText: "No hay eventos para mostrar"
    };

    return esUs;

}());


document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('CalendarioWeb');


    var calendar = new FullCalendar.Calendar(calendarEl, {

        locale: 'es',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today miBoton',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                    //'month, basicWeek, basicDay, agendaDay',
        },
        
        
        customButtons:{
            miBoton:{
                text: "Alert",
                click:function(){
                    swal("Alerta","mensaje","info");
                },
            }
            
        }

    });
    calendar.render();
});






//$(document).ready(function() {    $('#CalendarioWeb').fullCalendar();})
