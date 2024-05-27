$(document).ready(function() {
  $('#tipo').change(function() {
    var selectedTipo = $(this).val();
    if (selectedTipo === 'PERSONAL') {
        $('#divPersonal').show();
        $('#estado').val(1);
        $('#color').val('red');
    } else {
        $('#divPersonal').hide();
        $('#personal').val('');
        $('#estado').val(0);
        $('#color').val('black');
    }
  });

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
              color = 'black'; 
              break;
      }
      $('#colorE').val(color);
  });
  
  $('#btnNuevo').click(function() {
      $('#exampleModal').modal('show');
  });

  document.getElementById('btnBuscar').addEventListener('click', function() {
    const pers = document.getElementById('pers').value;
    const fechaD = document.getElementById('fechaD').value;
    const fechaH = document.getElementById('fechaH').value;
    console.log(pers," ",fechaD," ",fechaH)

    $.ajax({
        url: "../controller/listar.php",
        type: "POST",
        data: {
            pers: pers,
            fechaD: fechaD,
            fechaH: fechaH
        },
        dataType: "json",
        success: function(data) {
            $('#tablaAgenda').DataTable().clear().destroy();
            $('#tablaAgenda').DataTable({
                "processing": true,
                "data": data,
                "columns": [
                    {"data": "Op"},
                    {"data": "titulo"},
                    {"data": "tipo"},
                    {"data": "personal"},
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            let fechaDesde = row.fechaDesde;
                            let fechaHasta = row.fechaHasta;
                            return fechaDesde + ' - ' + fechaHasta;
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            let horaDesde = row.horaDesde;
                            let horaHasta = row.horaHasta;
                            return horaDesde + ' - ' + horaHasta;
                        }
                    },
                    {"data": "descripcion", "render": function(data, type, row) {
                        return (data.length > 5) ? data.substring(0, 5) + "..." : data;
                    }},
                    {"data": "estado", "render": function(data, type, row) {
                        var estado = "";
                        switch (data) {
                            case 0:
                                estado = "<span>NINGUNO</span>";
                                break;
                            case 1:
                                estado = "<span style='color: red;'>INICIO</span>";
                                break;
                            case 2:
                                estado = "<span style='color: #b9b950;'>EN PROCESO</span>";
                                break;
                            case 3:
                                estado = "<span style='color: green;'>FINALIZADO</span>";
                                break;
                            case null:
                                estado = "<span>NINGUNO</span>";
                                break;
                        }
                        return estado;
                    }},
                    {"data": null, "render": function(data, type, row) {
                        return '<button class="btn btn-warning btn-sm boton-editar" type="button" data-op="' + row.Op + '">Editar</button> <button class="btn btn-danger btn-sm boton-eliminar" type="button" data-op="' + row.Op + '">Eliminar</button>';
                    }}
                ]
            });
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
  });

});

$('#forRegistrarTarea2').submit(function(event) {
  
  var titulo = $('#titulo').val().trim();
  var fechaDesde = $('#fechaDesde').val().trim();
  var fechaHasta = $('#fechaHasta').val().trim();
  var horaDesde = $('#horaDesde').val().trim();
  var horaHasta = $('#horaHasta').val().trim();
  var tipo = $('#tipo').val()
  var personal = $('#personal').val()

  if (titulo === '' || fechaDesde === '' || fechaHasta === '' || horaDesde === '' || horaHasta === '' || tipo === '') {

      Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Por favor, complete los campos Titulo, Fecha, Hora y Tipo.'
      });
      
      event.preventDefault();
  }
  if(tipo === 'PERSONAL'){
    if(personal === null){
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Por favor, complete el campo "Personal".'
      });
      event.preventDefault();
    }
    
  }
});
$('#forEditarTarea2').submit(function(event) {
  var titulo = $('#tituloE').val().trim();
  var fechaDesde = $('#fechaDesdeE').val().trim();
  var fechaHasta = $('#fechaHastaE').val().trim();
  var horaDesde = $('#horaDesdeE').val().trim();
  var horaHasta = $('#horaHastaE').val().trim();
  var tipo = $('#tipoE').val().trim();
  var personal = $('#personalE').val().trim();

  if (titulo === '' || fechaDesde === '' || fechaHasta === '' || horaDesde === '' || horaHasta === '' || tipo === '') {

    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Por favor, complete los campos Titulo, Fecha, Hora y Tipo.'
    });
    
    event.preventDefault();
  }
  if(tipo === 'PERSONAL'){
    if(personal === null){
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Por favor, complete el campo "Personal".'
      });
      event.preventDefault();
    }
    
  }
});

$('#tablaAgenda').on('click', '.boton-editar', function(){
  var op = $(this).data('op'); 
  $.ajax({
      url: '../controller/obtenerDatos.php',
      type: 'POST',
      data: {op: op},
      dataType: 'json',
      success: function(data) {
        if (data.length > 0) {
          var agenda = data[0];
          var fechaDesdeParts = agenda.fechaDesde.split('-');
          var fechaDesdeISO = fechaDesdeParts[2] + '-' + fechaDesdeParts[1] + '-' + fechaDesdeParts[0];
          var fechaHastaParts = agenda.fechaHasta.split('-');
          var fechaHastaISO = fechaHastaParts[2] + '-' + fechaHastaParts[1] + '-' + fechaHastaParts[0];
          $('#modalEditar').find('#opE').val(agenda.Op); 
          $('#modalEditar').find('#tituloE').val(agenda.titulo);
          $('#modalEditar').find('#tipoE').val(agenda.tipo);
          $('#modalEditar').find('#personalE').val(agenda.personal);
          $('#modalEditar').find('#fechaDesdeE').val(fechaDesdeISO);
          $('#modalEditar').find('#fechaHastaE').val(fechaHastaISO);
          $('#modalEditar').find('#horaDesdeE').val(agenda.horaDesde);
          $('#modalEditar').find('#horaHastaE').val(agenda.horaHasta);
          $('#modalEditar').find('#descripcionE').val(agenda.descripcion);
          $('#modalEditar').find('#rutaOriginal').val(agenda.imagenes);
          $('#modalEditar').find('#colorE').val(agenda.color);
          $('#modalEditar').find('#estadoE').val(agenda.estado);
          $('#modalEditar').find('#rutaOriginalArch').val(agenda.archivos);

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

          $('#modalEditar').modal('show');
        } else {
            console.error('No se encontraron datos para el Op proporcionado');
        }
      },
      error: function(xhr, status, error) {
          console.error(xhr.responseText);
      }
  });
});

$('#tablaAgenda').on('click', '.boton-eliminar', function() {
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


