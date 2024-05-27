var mostrarModalEvento = true;
$(document).ready(function(){
    $('#estadoE').change(function() {
        var estado = $(this).val();
        var color;
          switch (estado) {
              case '1':
                  color = 'red';
                  break;
              case '2':
                  color = '#b9b950';
                  break;
              case '3':
                  color = 'green';
                  break;
              default:
                  color = '#563d7c'; 
                  break;
          }
          $('#colorE').val(color);
      });
    $('#calendar').fullCalendar({
        events: {
            url: '../controller/getEventos.php',
            type: 'POST',
            success: function(data) {
                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('addEventSource', data);
                console.log($('#calendar').fullCalendar('removeEvents'))
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
            var endDate;

            if ($('#calendar').fullCalendar('getView').type === 'month') {
                endDate = moment(end).subtract(1, 'days').format('YYYY-MM-DD');
            } else {
                endDate = moment(end).format('YYYY-MM-DD');
            }

            var startTime = moment(start).format('HH:mm');
            var endTime = moment(end).subtract(1, 'days').format('HH:mm');

            $('#fechaDesde').val(startDate);
            $('#fechaHasta').val(endDate);
            $('#horaDesde').val(startTime);
            $('#horaHasta').val(endTime);

            $('#modalCalendario').modal('show');
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
        },
        eventRender: function(event, element) {
            element.find('.fc-time').hide();
            element.css('font-weight', 'bold');
            element.css('text-transform', 'uppercase');
            var estado = '';
            if (event.estado === 1) {
                estado = ' - INICIO';
            } else if (event.estado === 2) {
                estado = ' - EN PROCESO';
            } else if (event.estado === 3) {
                estado = ' - FINALIZADO';
            } else {
                estado = '';
            }

            var estadoElement = $('<span class="estado-evento">' + estado + '</span>');
            element.find('.fc-title').append(estadoElement);
        }
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
    $('.fc-highlight').remove();

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
                $('#btnDescargarImagen').hide();
                if ($('#modalEvento').find('#noImagen').length === 0) {
                    $('#modalEvento').find('#rutaOriginal').after('<p id="noImagen">No contiene imagen</p>');
                }
            } else {
                $('#modalEvento').find('#rutaOriginal').show();
                $('#btnDescargarImagen').show();
                $('#modalEvento').find('#rutaOriginal').attr('src', agenda.imagenes);
                $('#modalEvento').find('#noImagen').remove();
                $('#btnDescargarImagen').click(function() {
                    var link = document.createElement('a');
                    link.href = agenda.imagenes.trim(); 
                    link.download = ''; 
                    link.target = '_blank'; 
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            }

            if (agenda.archivos !== "" && agenda.archivos !== null) {
                $('#btnDescargarArchivos').show();
                $('#btnDescargarArchivos').on('click', function() {
                    var archivos = agenda.archivos.split(',');
                    var zip = new JSZip();
                
                    archivos.forEach(function(archivo) {
                        var nombreArchivo = archivo.substring(archivo.lastIndexOf('/') + 1);
                        var xhr = new XMLHttpRequest();
                        xhr.open('GET', archivo, true);
                        xhr.responseType = 'blob';
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                zip.file(nombreArchivo, xhr.response);
                                if (Object.keys(zip.files).length === archivos.length) {
                                    zip.generateAsync({ type: 'blob' }).then(function(content) {
                                        saveAs(content, agenda.titulo + '.zip');
                                    });
                                }
                            }
                        };
                        xhr.send();
                    });
                });
            } else {
                $('#btnDescargarArchivos').hide();
            }

            if (agenda.tipo === 'MIS TAREAS') {
                $('#btnEliminarEvento').show();
                $('#btnEliminarEvento').data('op', agenda.Op);
            } else {
                $('#btnEliminarEvento').hide();
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
            $('#modalEditarEvento').find('#rutaOriginalArch').val(agenda.archivos);
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
                $('#colorE').val('#563d7c');
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
$('#modalEvento').on('click', '#btnEliminarEvento', function() {
    var op = $(this).data('op'); 
  
    Swal.fire({
      title: '¿Estás seguro?',
      text: "No podrás revertir esto",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '../controller/eliminarTarea.php',
          type: 'POST',
          data: { op: op },
          dataType: 'json',
          success: function(data) {
            if (data.success) {
              Swal.fire(
                'Eliminado',
                'La tarea ha sido eliminada.',
                'success'
              );
              location.reload();
            } else {
              Swal.fire(
                'Error',
                'Hubo un problema al eliminar la tarea.',
                'error'
              );
            }
          },
          error: function(xhr, status, error) {
            Swal.fire(
              'Error',
              'Hubo un problema al eliminar la tarea.',
              'error'
            );
            console.error(xhr.responseText);
          }
        });
      }
    });
  });