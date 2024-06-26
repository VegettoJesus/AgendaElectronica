<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../");
    exit();
}
?>
<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.6.0/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <title>Calendario</title>
  </head>
  <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fa fa-bars"></i>
        </label>
        <a href="../vistas/calendarioMisTareas.php" class="enlace"></a>
        <img src="../assets/img/escudo.png" alt="logo" class="logo">
        <b class="nomLogo">AGENDA ELECTRONICA</b>
        <ul>
            <li><a class="active" href="../vistas/calendarioMisTareas.php">Calendario</a></li>
            <li><a href="../vistas/tareas.php">Tareas</a></li>
            <li><a href="../controller/cerrarSesion.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
  <body>
    <div class="container">
        <div class="tipo-botones">
            <button type="input" class="btn btn-primary" id="button-tareas-mis" style="padding-top: 7px;padding-left: 0px; padding-right: 0px;"><a href="../vistas/calendarioMisTareas.php" style="color: white;text-decoration-line: none;padding: 8px;">Ver mis tareas</a></button>
            <button type="input" class="btn btn-primary active" id="button-tareas-general" style="padding-top: 7px;padding-left: 0px; padding-right: 0px;"><a href="../vistas/calendarioGeneral.php" style="color: white;text-decoration-line: none;padding: 8px;">Ver todas las tareas</a></button>
        </div>
        <div id="calendar"></div>
    </div>
    <div class="modal fade tipoLetra" id="modalVisualizarEvento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-sm-9">
                        <h1 class="modal-title fs-5" name="tituloV" id="tituloV"></h1>
                    </div>
                    <div class="col-sm-2">
                        <div id="colorV" style="width: 50px; height: 50px; border-radius: 50%; border: 1px solid black;"></div>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-4">
                    <div class="justify-content-center">
                        <div >
                            <input type="hidden" id="opV" name="opV">
                            <div class="mb-3 row">
                                <div class="col-sm-4">
                                    <label class="col-sm-2 col-form-label">TIPO:</label>
                                    <p id="tipoV" name="tipoV"></p>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-2 col-form-label">PERSONAL:</label>
                                    <p id="personalV" name="personalV"></p>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-2 col-form-label">ESTADO:</label>
                                    <p id="estadoV" name="estadoV"></p>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label" style="font-weight: bold;">FECHA</label>
                                <div class="col-sm-5">
                                    <label class="col-sm-2 col-form-label">Desde:</label>
                                    <p name="fechaDesdeV" id="fechaDesdeV"></p>
                                </div>
                                <div class="col-sm-5">
                                    <label class="col-sm-2 col-form-label">Hasta:</label>
                                    <p name="fechaHastaV" id="fechaHastaV"></p>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label" style="font-weight: bold;">HORA</label>
                                <div class="col-sm-5">
                                    <label class="col-sm-2 col-form-label">Desde:</label>
                                    <p name="horaDesdeV" id="horaDesdeV"></p>
                                </div>
                                <div class="col-sm-5">
                                    <label class="col-sm-2 col-form-label">Hasta:</label>
                                    <p  name="horaHastaV" id="horaHastaV"></p>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Descripción</label>
                                <div>
                                    <p id="descripcionV" name="descripcionV"></p>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Imagen</label>
                                <div>
                                    <img id="rutaOriginal" name="rutaOriginal" style="max-width: 420px; max-height: 420px;">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <button id="btnDescargarImagen" class="btn btn-warning" style="display: none;">
                                    <i class="fa fa-download"></i> Descargar Imagen
                                </button>
                            </div>
                            <div class="mb-3 row">
                                <button id="btnDescargarArchivos" class="btn btn-success" style="display: none;">
                                    <i class="fa fa-download"></i> Descargar Archivos
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/calendarioGeneral.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>