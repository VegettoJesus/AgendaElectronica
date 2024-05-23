<?php 
session_start();
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
            <button type="input" class="btn btn-primary active" id="button-tareas-mis" style="padding-top: 7px;padding-left: 0px; padding-right: 0px;"><a href="../vistas/calendarioMisTareas.php" style="color: white;text-decoration-line: none;padding: 8px;">Mis Tareas</a></button>
            <button type="input" class="btn btn-primary" id="button-tareas-general" style="padding-top: 7px;padding-left: 0px; padding-right: 0px;"><a href="../vistas/calendarioGeneral.php" style="color: white;text-decoration-line: none;padding: 8px;">General</a></button>
        </div>
        <div id="calendar"></div>
    </div>
    <div class="modal fade tipoLetra" id="modalCalendario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Crear Tarea</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="forRegistrarTarea" action="../controller/registrarTareas.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body p-4">
                        <div class="justify-content-center">
                            <div >
                                <input type="hidden" id="estado" name="estado" value="0">
                                <input type="hidden" id="tipo" name="tipo" value="MIS TAREAS">
                                <input type="hidden" id="personal" name="personal" value="<?php echo $_SESSION['usuario']; ?>">
                                <div class="mb-3 col">
                                    <label class="col-form-label">Titulo</label>
                                    <input type="text" class="form-control" name="titulo" id="titulo">
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label" style="font-weight: bold;">FECHA</label>
                                    <div class="col-sm-5">
                                        <label class="col-sm-2 col-form-label">Desde:</label>
                                        <input type="date" class="form-control" name="fechaDesde" id="fechaDesde">
                                    </div>
                                    <div class="col-sm-5">
                                        <label class="col-sm-2 col-form-label">Hasta:</label>
                                        <input type="date" class="form-control" name="fechaHasta" id="fechaHasta">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label" style="font-weight: bold;">HORA</label>
                                    <div class="col-sm-5">
                                        <label class="col-sm-2 col-form-label">Desde:</label>
                                        <input type="time" class="form-control" name="horaDesde" id="horaDesde">
                                    </div>
                                    <div class="col-sm-5">
                                        <label class="col-sm-2 col-form-label">Hasta:</label>
                                        <input type="time" class="form-control" name="horaHasta" id="horaHasta">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Descripción</label>
                                    <div>
                                        <textarea class="form-control" id="descripcion" name="descripcion" style="height: 100px"></textarea>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="form-label">Imagen:</label>
                                    <div>
                                        <input class="form-control" type="file" id="formFile" name="formFile">
                                    </div> 
                                </div>
                                <div class="mb-3 row">
                                    <label class="form-label">Color</label>
                                    <div>
                                        <input type="color" class="form-control form-control-color" id="color" name="color" value="#563d7c" title="Choose your color">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade tipoLetra" id="modalEvento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" name="tituloV" id="tituloV"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="justify-content-center">
                        <div >
                            <input type="hidden" id="opV" name="opV">
                            <input type="hidden" id="personalV" name="personalV" value="<?php echo $_SESSION['usuario']; ?>">
                            <div class="mb-3 row">
                                <div class="col-sm-4">
                                    <label class="col-sm-2 col-form-label">TIPO:</label>
                                    <p id="tipoV" name="tipoV"></p>
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-sm-2 col-form-label">ESTADO:</label>
                                    <p id="estadoV" name="estadoV"></p>
                                </div>
                                <div class="col-sm-4">
                                    <div id="colorV" style="width: 50px; height: 50px; border-radius: 50%; border: 1px solid black;"></div>
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
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-warning" id="btnEditarEvento">Editar</button>
                    <button type="button" class="btn btn-danger" id="btnEliminarEvento" style="display: none;">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade tipoLetra" id="modalEditarEvento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Editar Tarea</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="forEditarTarea" action="../controller/editarTarea.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body p-4">
                        <div class="justify-content-center">
                            <div >
                                <input type="hidden" id="opE" name="opE">
                                <input type="hidden" id="rutaOriginal" name="rutaOriginal">
                                <input type="hidden" id="tipoE" name="tipoE" value="MIS TAREAS">
                                <input type="hidden" id="personalE" name="personalE" value="<?php echo $_SESSION['usuario']; ?>">
                                <div class="mb-3 col">
                                    <label class="col-form-label">Titulo</label>
                                    <input type="text" class="form-control" name="tituloE" id="tituloE">
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label" style="font-weight: bold;">FECHA</label>
                                    <div class="col-sm-5">
                                        <label class="col-sm-2 col-form-label">Desde:</label>
                                        <input type="date" class="form-control" name="fechaDesdeE" id="fechaDesdeE">
                                    </div>
                                    <div class="col-sm-5">
                                        <label class="col-sm-2 col-form-label">Hasta:</label>
                                        <input type="date" class="form-control" name="fechaHastaE" id="fechaHastaE">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label" style="font-weight: bold;">HORA</label>
                                    <div class="col-sm-5">
                                        <label class="col-sm-2 col-form-label">Desde:</label>
                                        <input type="time" class="form-control" name="horaDesdeE" id="horaDesdeE">
                                    </div>
                                    <div class="col-sm-5">
                                        <label class="col-sm-2 col-form-label">Hasta:</label>
                                        <input type="time" class="form-control" name="horaHastaE" id="horaHastaE">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label">Descripción</label>
                                    <div>
                                        <textarea class="form-control" id="descripcionE" name="descripcionE" style="height: 100px"></textarea>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="form-label">Imagen:</label>
                                    <div>
                                        <input class="form-control" type="file" id="formFileE" name="formFileE">
                                    </div> 
                                </div>
                                <div class="mb-3 row">
                                    <div class="col-sm-5">
                                        <label class="col-sm-2 col-form-label">Color</label>
                                        <input type="color" class="form-control form-control-color" id="colorE" name="colorE" title="Choose your color">
                                    </div>
                                    <div class="col-sm-5" id="divEstadoE" style="display: none;">
                                        <label class="col-sm-2 col-form-label">Estado</label>
                                        <select class="form-select" id="estadoE" name="estadoE">
                                            <option disabled value="0">SELECCIONE</option>
                                            <option value="1">INICIO</option>
                                            <option value="2">EN PROCESO</option>
                                            <option value="3">FINALIZADO</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/js/calendarioMisTareas.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>