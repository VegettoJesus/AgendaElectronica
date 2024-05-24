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
        views: {
            listMonth: { buttonText: 'Lista del Mes' }
        },
        defaultView: 'month',
        locale: 'es',
        header: {
            left: 'month,agendaWeek,agendaDay,listMonth',
            center: 'title',
            right: 'prev,today,next'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            listMonth: 'Lista'
        },
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
        dayRender: function(date, cell) {
            var today =$.fullCalendar.moment()
            if (date.get('date')==today.get('date')) {
                cell.css("background-color", "rgb(92, 154, 222)");
            }
        },
        eventAfterAllRender: function(view) {
            if (view.name === 'agendaWeek' || view.name === 'agendaDay') {
                $(".fc-today").css("background-color", "rgb(92, 154, 222)");
            }
        }
        
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
            $('#modalVisualizarEvento').find('#tipoV').text(agenda.tipo);
            $('#modalVisualizarEvento').find('#rutaOriginal').attr('src', agenda.imagenes);
            var personal = agenda.personal ? agenda.personal : "NINGUNO";
            $('#modalVisualizarEvento').find('#personalV').text(personal);


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

            var estadoEvento = agenda.estado;
            $('#modalVisualizarEvento').find('#estadoV').removeClass('estado-inicio estado-proceso estado-finalizado estado-ninguno');

            if (estadoEvento === 1) {
                valor = "INICIO";
                $('#modalVisualizarEvento').find('#estadoV').text(valor).addClass('estado-inicio');
            } else if (estadoEvento === 2) {
                valor = "EN PROCESO";
                $('#modalVisualizarEvento').find('#estadoV').text(valor).addClass('estado-proceso');
            } else if (estadoEvento === 3) {
                valor = "FINALIZADO";
                $('#modalVisualizarEvento').find('#estadoV').text(valor).addClass('estado-finalizado');
            } else {
                valor = "NINGUNO";
                $('#modalVisualizarEvento').find('#estadoV').text(valor).addClass('estado-ninguno');
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