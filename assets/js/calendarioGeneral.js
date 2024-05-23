$(document).ready(function(){
    $('#calendar').fullCalendar({
        events: {
            url: '../controller/getEventosGeneral.php',
            type: 'POST',
            success: function(data) {
                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('addEventSource', data);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching events:", status, error);
                alert('Error al cargar los eventos');
            }
        },
        eventClick: function(calEvent, jsEvent, view) {
            var eventId = calEvent.id; 
            $('#modalVisualizarEvento').data('eventId', eventId);
            $('#modalVisualizarEvento').modal('show');
        },
        selectable: true,
        selectHelper: true,
        select: function(start, end) {
            var startDate = moment(start).format('YYYY-MM-DD');
            var endDate = moment(end).subtract(1, 'days').format('YYYY-MM-DD');

            var currentTime = moment().format('HH:mm');

            $('#fechaDesde').val(startDate);
            $('#fechaHasta').val(endDate);
            $('#horaDesde').val(currentTime);

            $('#modalCalendario').modal('show');
        },
        locale: 'es',
        header: {
            left: 'month,agendaWeek,agendaDay,list',
            center: 'title',
            right: 'prev,today,next'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            list: 'Lista'
        },
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']
    });
});
$('#modalVisualizarEvento').on('show.bs.modal', function (event) {
    var modal = $(this);
    var eventId = modal.data('eventId');
    $.ajax({
        url: '../controller/obtenerDatos.php',
        type: 'POST',
        data: {op: eventId},
        dataType: 'json',
        success: function(data) {
          if (data.length > 0) {
            var agenda = data[0];
            var fechaDesdeParts = agenda.fechaDesde.split('-');
            var fechaDesdeISO = fechaDesdeParts[2] + '-' + fechaDesdeParts[1] + '-' + fechaDesdeParts[0];
            var fechaHastaParts = agenda.fechaHasta.split('-');
            var fechaHastaISO = fechaHastaParts[2] + '-' + fechaHastaParts[1] + '-' + fechaHastaParts[0];
            $('#modalVisualizarEvento').find('#opV').val(agenda.Op); 
            $('#modalVisualizarEvento').find('#tituloV').text(agenda.titulo);
            $('#modalVisualizarEvento').find('#fechaDesdeV').text(fechaDesdeISO);
            $('#modalVisualizarEvento').find('#fechaHastaV').text(fechaHastaISO);
            $('#modalVisualizarEvento').find('#horaDesdeV').text(agenda.horaDesde);
            $('#modalVisualizarEvento').find('#horaHastaV').text(agenda.horaHasta);
            $('#modalVisualizarEvento').find('#descripcionV').text(agenda.descripcion);
            $('#modalVisualizarEvento').find('#colorV').css('background-color', agenda.color);

            $('#modalVisualizarEvento').find('#rutaOriginal').attr('src', agenda.imagenes);


            if (agenda.imagenes.trim() === "") {
                $('#modalVisualizarEvento').find('#rutaOriginal').hide();
                if ($('#modalVisualizarEvento').find('#noImagen').length === 0) {
                    $('#modalVisualizarEvento').find('#rutaOriginal').after('<p id="noImagen">No contiene imagen</p>');
                }
            } else {
                $('#modalVisualizarEvento').find('#rutaOriginal').show();
                $('#modalVisualizarEvento').find('#rutaOriginal').attr('src', agenda.imagenes);
                $('#modalVisualizarEvento').find('#noImagen').remove();
            }

            $('#modalVisualizarEvento').modal('show');
          } else {
              console.error('No se encontraron datos para el Op proporcionado');
          }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

});