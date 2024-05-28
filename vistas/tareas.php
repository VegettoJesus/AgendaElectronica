<?php 
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include '../conn/conexion.php';
$sql = "SELECT id_Usuario, CONCAT(apellidos, ' ', nombres) AS Personal FROM new_usuarios WHERE estado = '0' ORDER BY 2";
$result2 = sqlsrv_query($conn, $sql);
if ($result2 === false) {
    die('Error al ejecutar la consulta: ' . print_r(sqlsrv_errors(), true));
}

$sql2 = "SELECT id_Usuario, CONCAT(apellidos, ' ', nombres) AS Personal FROM new_usuarios WHERE estado = '0' ORDER BY 2";
$result = sqlsrv_query($conn, $sql);
if ($result === false) {
    die('Error al ejecutar la consulta: ' . print_r(sqlsrv_errors(), true));
}

$sql_edicion_tarea = "SELECT id_Usuario, CONCAT(apellidos, ' ', nombres) AS Personal FROM new_usuarios WHERE estado = '0' ORDER BY 2";
$result_edicion_tarea = sqlsrv_query($conn, $sql_edicion_tarea);
if ($result_edicion_tarea === false) {
    die('Error al ejecutar la consulta para edición de tarea: ' . print_r(sqlsrv_errors(), true));
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
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
            <li><a href="../vistas/calendarioMisTareas.php">Calendario</a></li>
            <li><a class="active" href="../vistas/tareas.php">Tareas</a></li>
            <li><a href="../controller/cerrarSesion.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
  <body>
    <div class="container"  style="font-family: 'estilo_letra_1';">
        <div class="panel-group">
            <div class="panel panel-primary">
                <div class="panel-heading fs-3"> Lista de Tareas </div>
                <div class="panel-body">
                    <form action="">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 mipanel-row-fila row-fila">
                                    <label class="col-form-label">Personal</label>
                                    <select class="form-select" id="pers" name="pers">
                                        <option selected value="0">TODOS</option>
                                        <?php 
                                        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                            $personal = $row["Personal"];
                                            echo "<option value='" . utf8_encode($row["id_Usuario"]) . "'>" . utf8_encode($personal) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 mipanel-row-fila row-fila">
                                    <label class="col-form-label">Desde:</label>
                                    <input type="date" class="form-control" value="<?=date('Y-m-d')?>" name="fechaD" id="fechaD">
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 mipanel-row-fila row-fila">
                                    <label class="col-form-label">Hasta:</label>
                                    <input type="date" class="form-control" value="<?=date('Y-m-d')?>" name="fechaH" id="fechaH">
                                </div>
                                <div class="col-lg-1 col-md-3 col-sm-4 col-xs-4 mipanel-row-fila row-fila" style="margin-top: 35px;">
                                    <button class="btn btn-primary mipanel-btn-img-texto btn-block" id="btnBuscar" type="button">Buscar</button>
                                </div>
                                <div class="col-lg-1 col-md-3 col-sm-4 col-xs-4 mipanel-row-fila row-fila" style="margin-top: 35px;">
                                    <button class="btn btn-success mipanel-btn-img-texto btn-block" id="btnNuevo" type="button">Nuevo</button>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-5">
                                <div class="mi-panel" id="listado_tareas">
                                    <div class="panel-group">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive text-center">
                                                    <table class="table table-condensed table-bordered mi-tabla" id="tablaAgenda">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Op</th>
                                                                <th class="text-center">Titulo</th>
                                                                <th class="text-center">Tipo</th>
                                                                <th class="text-center">Personal</th>
                                                                <th class="text-center">Fecha</th>
                                                                <th class="text-center">Hora</th>
                                                                <th class="text-center">Descripción</th>
                                                                <th class="text-center">Estado</th>
                                                                <th class="text-center">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade tipoLetra" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Nueva Tarea</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="forRegistrarTarea2" action="../controller/registrarTareas.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body p-4">
                        <div class="justify-content-center">
                            <div >
                                <input type="hidden" id="estado" name="estado">
                                <input type="hidden" id="formFiles" name="formFiles[]" multiple>
                                <input type="hidden" id="color" name="color">
                                <div class="mb-3 col">
                                    <label class="col-form-label">Titulo</label>
                                    <input type="text" class="form-control" name="titulo" id="titulo">
                                </div>
                                <div class="mb-3 col">
                                    <label class="col-form-label">Tipo</label>
                                    <select class="form-select" id="tipo" name="tipo">
                                        <option value="" selected disabled="true">SELECCIONE</option>
                                        <option value="GENERAL">GENERAL</option>
                                        <option value="PERSONAL">PERSONAL</option>
                                    </select>
                                </div>
                                <div class="mb-3 col"  id="divPersonal" style="display: none;">
                                    <label class="col-form-label">Personal</label>
                                    <select class="form-select" id="personal" name="personal">
                                        <option value="" selected disabled="true">SELECCIONE</option>
                                        <?php 
                                        while ($row = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
                                            $personal = $row["Personal"];
                                            echo "<option value='" . utf8_encode($row["id_Usuario"]) . "'>" . utf8_encode($personal) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label" style="font-weight: bold;">FECHA</label>
                                    <div class="col-sm-5">
                                        <label class="col-sm-2 col-form-label">Desde:</label>
                                        <input type="date" class="form-control" value="<?=date('Y-m-d')?>" name="fechaDesde" id="fechaDesde">
                                    </div>
                                    <div class="col-sm-5">
                                        <label class="col-sm-2 col-form-label">Hasta:</label>
                                        <input type="date" class="form-control" value="<?=date('Y-m-d')?>" name="fechaHasta" id="fechaHasta">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-2 col-form-label" style="font-weight: bold;">HORA</label>
                                    <div class="col-sm-5">
                                        <label class="col-sm-2 col-form-label">Desde:</label>
                                        <input type="time" class="form-control" value="00:00" name="horaDesde" id="horaDesde">
                                    </div>
                                    <div class="col-sm-5">
                                        <label class="col-sm-2 col-form-label">Hasta:</label>
                                        <input type="time" class="form-control" value="23:59" name="horaHasta" id="horaHasta">
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
                                        <input class="form-control archivosAdjuntar" type="file" id="formFile" name="formFile">
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
    <div class="modal fade tipoLetra" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Editar Tarea</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="forEditarTarea2" action="../controller/editarTarea.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body p-4">
                        <div class="justify-content-center">
                            <div >
                                <input type="hidden" id="opE" name="opE">
                                <input type="hidden" id="rutaOriginal" name="rutaOriginal">
                                <input type="hidden" id="rutaOriginalArch" name="rutaOriginalArch">
                                <input type="hidden" id="colorE" name="colorE">
                                <div class="mb-3 col">
                                    <label class="col-form-label">Titulo</label>
                                    <input type="text" class="form-control" name="tituloE" id="tituloE">
                                </div>
                                <div class="mb-3 col">
                                    <label class="col-form-label">Tipo</label>
                                    <select class="form-select" id="tipoE" name="tipoE">
                                        <option disabled selected>SELECCIONE</option>
                                        <option value="GENERAL">GENERAL</option>
                                        <option value="PERSONAL">PERSONAL</option>
                                    </select>
                                </div>
                                <div class="mb-3 col"  id="divPersonalE" style="display: none;">
                                    <label class="col-form-label">Personal</label>
                                    <select class="form-select" id="personalE" name="personalE">
                                        <option disabled selected value="">SELECCIONE</option>
                                        <?php 
                                        while ($row = sqlsrv_fetch_array($result_edicion_tarea, SQLSRV_FETCH_ASSOC)) {
                                            $personal = $row["Personal"];
                                            echo "<option value='" . utf8_encode($row["id_Usuario"]) . "'>" . utf8_encode($personal) . "</option>";
                                        }
                                        ?>
                                    </select>
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
                                        <input class="form-control archivosAdjuntar" type="file" id="formFileE" name="formFileE">
                                    </div> 
                                </div>
                                <div class="mb-3 row">
                                    <div class="col-sm-5" id="divEstadoE" style="display: none;">
                                        <label class="col-sm-2 col-form-label">Estado</label>
                                        <select class="form-select" id="estadoE" name="estadoE">
                                            <option disabled selected value="0">SELECCIONE</option>
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
    <script src="../assets/js/tareas.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 </body>
</html>