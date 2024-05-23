<?php
session_start();
include '../conn/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pers = isset($_POST['pers']) ? $_POST['pers'] : '';
    $fechaD = isset($_POST['fechaD']) ? $_POST['fechaD'] : '';
    $fechaH = isset($_POST['fechaH']) ? $_POST['fechaH'] : '';

    $fechaD = str_replace('-', '', $fechaD);
    $fechaH = str_replace('-', '', $fechaH);

    $sql = "{CALL JCEAgendaListar(?, ?, ?)}";
    $params = array($pers, $fechaD, $fechaH);

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $data = array();

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }

    sqlsrv_free_stmt($stmt);

    header('Content-Type: application/json');
    echo json_encode($data);
}
?>
