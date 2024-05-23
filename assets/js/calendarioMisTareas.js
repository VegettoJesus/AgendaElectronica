var mostrarModalEvento = true;
$(document).ready(function(){
    $('#calendar').fullCalendar({
        events: {
            url: '../controller/getEventos.php',
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
            $('#modalEvento').data('eventId', eventId);
            $('#modalEditarEvento').data('eventId', eventId);
            $('#modalEvento').modal('show');
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
$('#forRegistrarTarea').submit(function(event) {
    var titulo = $('#titulo').val().trim();
    var fechaDesde = $('#fechaDesde').val().trim();
    var fechaHasta = $('#fechaHasta').val().trim();
    var horaDesde = $('#horaDesde').val().trim();
    var horaHasta = $('#horaHasta').val().trim();

    if (titulo === '' || fechaDesde === '' || fechaHasta === '' || horaDesde === '' || horaHasta === '') {

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, complete los campos Titulo, Fecha y Hora.'
        });

        event.preventDefault();
    }
});

$('#forEditarTarea').submit(function(event) {
    var titulo = $('#tituloE').val().trim();
    var fechaDesde = $('#fechaDesdeE').val().trim();
    var fechaHasta = $('#fechaHastaE').val().trim();
    var horaDesde = $('#horaDesdeE').val().trim();
    var horaHasta = $('#horaHastaE').val().trim();

    if (titulo === '' || fechaDesde === '' || fechaHasta === '' || horaDesde === '' || horaHasta === '') {

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, complete los campos Titulo, Fecha y Hora.'
        });

        event.preventDefault();
    }
});

$('#modalEvento').on('show.bs.modal', function (event) {
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
            $('#modalEvento').find('#opV').val(agenda.Op); 
            $('#modalEvento').find('#tituloV').text(agenda.titulo);
            $('#modalEvento').find('#tipoV').text(agenda.tipo);
            $('#modalEvento').find('#personalV').val(agenda.personal);
            $('#modalEvento').find('#fechaDesdeV').text(fechaDesdeISO);
            $('#modalEvento').find('#fechaHastaV').text(fechaHastaISO);
            $('#modalEvento').find('#horaDesdeV').text(agenda.horaDesde);
            $('#modalEvento').find('#horaHastaV').text(agenda.horaHasta);
            $('#modalEvento').find('#descripcionV').text(agenda.descripcion);
            $('#modalEvento').find('#colorV').css('background-color', agenda.color);

            $('#modalEvento').find('#rutaOriginal').attr('src', agenda.imagenes);


            if (agenda.imagenes.trim() === "") {
                $('#modalEvento').find('#rutaOriginal').hide();
                if ($('#modalEvento').find('#noImagen').length === 0) {
                    $('#modalEvento').find('#rutaOriginal').after('<p id="noImagen">No contiene imagen</p>');
                }
            } else {
                $('#modalEvento').find('#rutaOriginal').show();
                $('#modalEvento').find('#rutaOriginal').attr('src', agenda.imagenes);
                $('#modalEvento').find('#noImagen').remove();
            }
            
            var estadoEvento = agenda.estado;
            $('#modalEvento').find('#estadoV').removeClass('estado-inicio estado-proceso estado-finalizado estado-ninguno');

            if (estadoEvento === 1) {
                valor = "INICIO";
                $('#modalEvento').find('#estadoV').text(valor).addClass('estado-inicio');
            } else if (estadoEvento === 2) {
                valor = "EN PROCESO";
                $('#modalEvento').find('#estadoV').text(valor).addClass('estado-proceso');
            } else if (estadoEvento === 3) {
                valor = "FINALIZADO";
                $('#modalEvento').find('#estadoV').text(valor).addClass('estado-finalizado');
            } else {
                valor = "NINGUNO";
                $('#modalEvento').find('#estadoV').text(valor).addClass('estado-ninguno');
            }

            $('#modalEvento').modal('show');
          } else {
              console.error('No se encontraron datos para el Op proporcionado');
          }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

});

$('#btnEditarEvento').click(function() {
    console.log(mostrarModalEvento)
    if (mostrarModalEvento) {
        $('#modalEvento').modal('hide'); 
        $('#modalEditarEvento').modal('show'); 
    }
});

$('#modalEditarEvento').on('show.bs.modal', function (event) {
    mostrarModalEvento = true;
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
            $('#modalEditarEvento').find('#opE').val(agenda.Op); 
            $('#modalEditarEvento').find('#tituloE').val(agenda.titulo);
            $('#modalEditarEvento').find('#tipoE').val(agenda.tipo);
            $('#modalEditarEvento').find('#personalE').val(agenda.personal);
            $('#modalEditarEvento').find('#fechaDesdeE').val(fechaDesdeISO);
            $('#modalEditarEvento').find('#fechaHastaE').val(fechaHastaISO);
            $('#modalEditarEvento').find('#horaDesdeE').val(agenda.horaDesde);
            $('#modalEditarEvento').find('#horaHastaE').val(agenda.horaHasta);
            $('#modalEditarEvento').find('#descripcionE').val(agenda.descripcion);
            $('#modalEditarEvento').find('#rutaOriginal').val(agenda.imagenes);
            $('#modalEditarEvento').find('#colorE').val(agenda.color);
            $('#modalEditarEvento').find('#estadoE').val(agenda.estado);


            if (agenda.tipo === 'MIS TAREAS') {
                $('#modalEditarEvento').find('#tituloE, #fechaDesdeE, #fechaHastaE, #horaDesdeE, #horaHastaE, #descripcionE, #colorE, #formFileE').prop('readonly', false).attr('tabindex', '-1');
            } else {
                $('#modalEditarEvento').find('#tituloE, #fechaDesdeE, #fechaHastaE, #horaDesdeE, #horaHastaE, #descripcionE, #colorE, #formFileE').prop('readonly', true).attr('tabindex', '-1');
            
                var fileInput = $('#modalEditarEvento').find('#formFileE');
                var colorInput = $('#modalEditarEvento').find('#colorE');
            
                fileInput.click(function(event) {
                    if ($(this).prop('readonly')) {
                        event.preventDefault(); 
                    }
                });
            
                colorInput.click(function(event) {
                    if ($(this).prop('readonly')) {
                        event.preventDefault(); 
                    }
                });
            }
            
            if (agenda.tipo === 'PERSONAL') {
              $('#divPersonalE').show();
              $('#divEstadoE').show();
            } else {
                $('#divPersonalE').hide();
                $('#divEstadoE').hide();
            }
        
            $('#tipoE').on('change', function() {
                var selectedTipo = $(this).val();
                if (selectedTipo === 'PERSONAL') {
                    $('#divPersonalE').show();
                    $('#divEstadoE').show();
                    $('#estadoE').val(1);
                } else {
                    $('#divPersonalE').hide();
                    $('#divEstadoE').hide();
                    $('#personalE').val('');
                    $('#estadoE').val(0);
                }
            });
  
            $('#modalEditarEvento').modal('show');
          } else {
              console.error('No se encontraron datos para el Op proporcionado');
          }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
    $('#modalEditarEvento').on('hidden.bs.modal', function (event) {
        if (mostrarModalEvento) {
            $('#modalEvento').modal('show');
        }
    });
    
    $('#modalEditarEvento').find('.btn-close').click(function() {
        $('#modalEditarEvento').modal('hide');
        mostrarModalEvento = false;
    });

});
