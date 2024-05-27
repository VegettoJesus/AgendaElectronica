<?php
session_start();
include '../conn/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $op = $_POST['op'];

    // Obtener rutas de archivos asociadas al registro
    $sql_select = "SELECT imagenes, archivos FROM tblAgenda WHERE Op = ?";
    $params_select = array($op);

    $stmt_select = sqlsrv_query($conn, $sql_select, $params_select);

    if ($stmt_select === false) {
        echo json_encode(['success' => false, 'error' => sqlsrv_errors()]);
        exit();
    }

    $imagePath = null;
    $filePaths = array();
    if ($row = sqlsrv_fetch_array($stmt_select, SQLSRV_FETCH_ASSOC)) {
        $imagePath = $row['imagenes'];
        $filePathsStr = $row['archivos'];
        if (!empty($filePathsStr)) {
            $filePaths = explode(',', $filePathsStr);
        }
    }

    sqlsrv_free_stmt($stmt_select);

    // Eliminar registro de la base de datos
    $sql_delete = "DELETE FROM tblAgenda WHERE Op = ?";
    $params_delete = array($op);

    $stmt_delete = sqlsrv_query($conn, $sql_delete, $params_delete);

    if ($stmt_delete === false) {
        echo json_encode(['success' => false, 'error' => sqlsrv_errors()]);
        exit();
    }

    // Eliminar archivos del sistema de archivos
    if ($imagePath && file_exists($imagePath)) {
        unlink($imagePath);
    }

    foreach ($filePaths as $filePath) {
        if ($filePath && file_exists($filePath)) {
            unlink($filePath);
        }
    }

    echo json_encode(['success' => true]);
}
?>
