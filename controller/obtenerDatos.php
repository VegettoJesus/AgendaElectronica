<?php
session_start();
include '../conn/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['op'])) {
        $op = $_POST['op'];

        $sql = "SELECT Op, titulo, tipo, personal, CONVERT(varchar, fechaDesde, 105) AS fechaDesde, CONVERT(varchar, fechaHasta, 105) AS fechaHasta, horaDesde, horaHasta, descripcion, estado, color, imagenes, archivos FROM tblAgenda WHERE Op = ?";
        $params = array($op);
        
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
    } else {
        echo json_encode(array('error' => 'Falta el parámetro "op"'));
    }
}
?>
