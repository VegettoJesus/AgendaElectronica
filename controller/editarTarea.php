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
    $rutaOriginalArch = $_POST['rutaOriginalArch'];

    $imagenRuta = $rutaOriginal;
    $archRuta = $rutaOriginalArch;

    if ($personal != null && $personal != "") {
        $imgDir = "../assets/imgTareas/" . $personal;
        $fileDir = "../assets/archTareas/" . $personal;
        if (!file_exists($imgDir)) {
            mkdir($imgDir, 0777, true);
        }
        if (!file_exists($fileDir)) {
            mkdir($fileDir, 0777, true);
        }
    } else {
        $imgDir = "../assets/imgTareas/General";
        $fileDir = "../assets/archTareas/General";
        if (!file_exists($imgDir)) {
            mkdir($imgDir, 0777, true);
        }
        if (!file_exists($fileDir)) {
            mkdir($fileDir, 0777, true);
        }
    }

    // Handle image upload
    if (!empty($imagen)) {
        if (!empty($rutaOriginal) && file_exists($rutaOriginal) && strpos($rutaOriginal, 'General') === false) {
            unlink($rutaOriginal);
        }

        $targetFile = $imgDir . "/" . basename($imagen);
        move_uploaded_file($imagenTmp, $targetFile);
        $imagenRuta = $targetFile;
    }

    // Handle multiple files upload
    $archivosRutas = array();
    if (!empty($_FILES['formFilesE']['name'][0])) {
        // Delete old files if new files are uploaded
        if (!empty($archRuta)) {
            $oldFiles = explode(',', $archRuta);
            foreach ($oldFiles as $oldFile) {
                if (file_exists($oldFile) && strpos($oldFile, 'General') === false) {
                    unlink($oldFile);
                }
            }
        }

        foreach ($_FILES['formFilesE']['tmp_name'] as $key => $tmp_name) {
            $archivoNombre = $_FILES['formFilesE']['name'][$key];
            $archivoTmp = $_FILES['formFilesE']['tmp_name'][$key];
            $archivoRuta = $fileDir . "/" . basename($archivoNombre);
            move_uploaded_file($archivoTmp, $archivoRuta);
            $archivosRutas[] = $archivoRuta;
        }
    }else {
        // Si no hay nuevos archivos, mantener la ruta original
        if (!empty($archRuta)) {
            $archivosRutas = explode(',', $archRuta);
        }
    }

    $archivosRutaStr = implode(",", $archivosRutas);

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
                estado = ?,
                archivos = ?
            WHERE Op = ?";
    
    $params = array($titulo, $tipo, $personal, $fechaDesde, $fechaHasta, $horaDesde, $horaHasta, $descripcion, $imagenRuta, $color, $estado, $archivosRutaStr, $op);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }
}
?>
