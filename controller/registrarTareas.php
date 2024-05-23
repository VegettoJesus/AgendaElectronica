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

    if($personal!=null && $personal!=""){
        $principalMkdir = "../assets/imgTareas/".$personal;
        if( !file_exists($principalMkdir) ){                         
            mkdir( $principalMkdir , 0777 , true );
        }
    }else{
        $principalMkdir = "../assets/imgTareas/General";
        if( !file_exists($principalMkdir) ){                         
            mkdir( $principalMkdir , 0777 , true );
        }
    }

    if (!empty($_FILES['formFile']['name'])) {

        $imagen = $_FILES['formFile']['name'];
        $imagenTmp = $_FILES['formFile']['tmp_name'];

        $targetDir = $principalMkdir."/";
        $targetFile = $targetDir . basename($imagen);
        move_uploaded_file($imagenTmp, $targetFile);
        $imagenRuta = $targetFile;
    } else {
        $imagenRuta = '';
    }

    $sql = "INSERT INTO tblAgenda (titulo, tipo, personal, fechaDesde, fechaHasta, horaDesde, horaHasta, descripcion, imagenes, color, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $params = array($titulo, $tipo, $personal, $fechaDesde, $fechaHasta, $horaDesde, $horaHasta, $descripcion, $imagenRuta, $color,$estado);
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }
}
?>
