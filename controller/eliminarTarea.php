<?php
session_start();
include '../conn/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $op = $_POST['op'];

    $sql_select = "SELECT imagenes FROM tblAgenda WHERE Op = ?";
    $params_select = array($op);

    $stmt_select = sqlsrv_query($conn, $sql_select, $params_select);

    if ($stmt_select === false) {
        echo json_encode(['success' => false, 'error' => sqlsrv_errors()]);
        exit();
    }

    $imagePath = null;
    if ($row = sqlsrv_fetch_array($stmt_select, SQLSRV_FETCH_ASSOC)) {
        $imagePath = $row['imagenes'];
    }

    sqlsrv_free_stmt($stmt_select);

    $sql_delete = "DELETE FROM tblAgenda WHERE Op = ?";
    $params_delete = array($op);

    $stmt_delete = sqlsrv_query($conn, $sql_delete, $params_delete);

    if ($stmt_delete === false) {
        echo json_encode(['success' => false, 'error' => sqlsrv_errors()]);
        exit();
    }

    if ($imagePath && file_exists($imagePath)) {
        unlink($imagePath);
    }

    echo json_encode(['success' => true]);
}
?>
