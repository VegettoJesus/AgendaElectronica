<?php
session_start();
include '../conn/conexion.php';

header('Content-Type: application/json');


$tipo = "GENERAL"; 

$sql = "SELECT Op, titulo, CONVERT(varchar, fechaDesde, 105) AS fechaDesde, CONVERT(varchar, fechaHasta, 105) AS fechaHasta, horaDesde, horaHasta, color FROM tblAgenda";
$params = array($tipo);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    echo json_encode(['error' => sqlsrv_errors()]);
    exit();
} else {
    $events = array(); 

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

        $fechaDesde = date_create_from_format('d-m-Y', $row['fechaDesde']);
        $fechaHasta = date_create_from_format('d-m-Y', $row['fechaHasta']);

        if ($fechaDesde && $fechaHasta) {
            $event = array(
                'id' => $row['Op'],
                'title' => $row['titulo'],
                'start' => $fechaDesde->format('Y-m-d') . 'T' . $row['horaDesde'], 
                'end' => $fechaHasta->format('Y-m-d') . 'T' . $row['horaHasta'], 
                'color' => $row['color']
            );

            array_push($events, $event);
        }
    }

    echo json_encode($events);
}
?>