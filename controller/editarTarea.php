<?php
session_start();
include '../conn/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $op = $_POST['opE'];
    $titulo = $_POST['tituloE'];
    $tipo = $_POST['tipoE'];
    $personal = $_POST['personalE'];
    $fechaDesde = str_replace("-", "", trim($_POST['fechaDesdeE']));
    $fechaHasta = str_replace("-", "", trim($_POST['fechaHastaE']));
    $horaDesde = $_POST['horaDesdeE'];
    $horaHasta = $_POST['horaHastaE'];
    $descripcion = $_POST['descripcionE'];
    $color = $_POST['colorE'];
    $estado = $_POST['estadoE'];
    $imagen = $_FILES['formFileE']['name'];
    $imagenTmp = $_FILES['formFileE']['tmp_name'];
    $rutaOriginal = $_POST['rutaOriginal'];

    $imagenRuta = $rutaOriginal;

    if ($personal != null && $personal != "") {
        $principalMkdir = "../assets/imgTareas/" . $personal;
        if (!file_exists($principalMkdir)) {
            mkdir($principalMkdir, 0777, true);
        }
    } else {
        $principalMkdir = "../assets/imgTareas/General";
        if (!file_exists($principalMkdir)) {
            mkdir($principalMkdir, 0777, true);
        }
    }

    if (!empty($imagen)) {
        if (!empty($rutaOriginal) && file_exists($rutaOriginal) && strpos($rutaOriginal, 'General') === false) {
            unlink($rutaOriginal);
        }

        $targetDir = $principalMkdir . "/";
        $targetFile = $targetDir . basename($imagen);
        move_uploaded_file($imagenTmp, $targetFile);
        $imagenRuta = $targetFile;
    }

    $sql = "UPDATE tblAgenda SET 
                titulo = ?, 
                tipo = ?, 
                personal = ?, 
                fechaDesde = ?, 
                fechaHasta = ?, 
                horaDesde = ?, 
                horaHasta = ?, 
                descripcion = ?, 
                imagenes = ?, 
                color = ?,
                estado = ?
            WHERE Op = ?";
    
    $params = array($titulo, $tipo, $personal, $fechaDesde, $fechaHasta, $horaDesde, $horaHasta, $descripcion, $imagenRuta, $color, $estado, $op);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        header("Location: ../vistas/tareas.php");
        exit();
    }
}
?>
