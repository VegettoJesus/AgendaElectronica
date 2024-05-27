<?php
session_start();
include '../conn/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $tipo = $_POST['tipo'];
    $personal = $_POST['personal'];
    $fechaDesde = str_replace("-","",trim($_POST['fechaDesde']));
    $fechaHasta = str_replace("-","",trim($_POST['fechaHasta']));
    $horaDesde = $_POST['horaDesde'];
    $horaHasta = $_POST['horaHasta'];
    $descripcion = $_POST['descripcion'];
    $imagen = $_FILES['formFile']['name'];
    $imagenTmp = $_FILES['formFile']['tmp_name'];
    $color = $_POST['color'];
    $estado = $_POST['estado'];

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
    if (!empty($_FILES['formFile']['name'])) {
        $imagen = $_FILES['formFile']['name'];
        $imagenTmp = $_FILES['formFile']['tmp_name'];
        $targetFile = $imgDir . "/" . basename($imagen);
        move_uploaded_file($imagenTmp, $targetFile);
        $imagenRuta = $targetFile;
    } else {
        $imagenRuta = '';
    }

    // Handle multiple files upload
    $archivosRutas = array();
    if (!empty($_FILES['formFiles']['name'][0])) {
        foreach ($_FILES['formFiles']['tmp_name'] as $key => $tmp_name) {
            $archivoNombre = $_FILES['formFiles']['name'][$key];
            $archivoTmp = $_FILES['formFiles']['tmp_name'][$key];
            $archivoRuta = $fileDir . "/" . basename($archivoNombre);
            move_uploaded_file($archivoTmp, $archivoRuta);
            $archivosRutas[] = $archivoRuta;
        }
    }

    $archivosRutaStr = empty($archivosRutas) ? '' : implode(",", $archivosRutas);

    $sql = "INSERT INTO tblAgenda (titulo, tipo, personal, fechaDesde, fechaHasta, horaDesde, horaHasta, descripcion, imagenes, color, estado, archivos) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $params = array($titulo, $tipo, $personal, $fechaDesde, $fechaHasta, $horaDesde, $horaHasta, $descripcion, $imagenRuta, $color, $estado, $archivosRutaStr);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }
}
?>
