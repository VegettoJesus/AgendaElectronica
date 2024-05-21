<?php
session_start();
include '../conn/conexion.php';

$sql = "SELECT 
    a.Op, 
    a.titulo, 
    a.tipo, 
    CASE 
        WHEN u.id_usuario IS NULL THEN 'NINGUNO' 
        ELSE CONCAT(u.apellidos, ' ', u.nombres) 
    END AS personal, 
    CONVERT(varchar, a.fechaDesde, 105) AS fechaDesde, 
    CONVERT(varchar, a.fechaHasta, 105) AS fechaHasta, 
    a.horaDesde, 
    a.horaHasta, 
    a.descripcion, 
    a.estado,
    a.color,
    a.imagenes 
    FROM 
    tblAgenda a
    LEFT JOIN 
    new_usuarios u ON a.personal = u.id_usuario;";


$result = sqlsrv_query($conn, $sql);

if ($result === false) {
    echo json_encode(array('error' => 'Error al ejecutar la consulta SQL: ' . print_r(sqlsrv_errors(), true)));
    exit();
}

$data = array();

while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $data[] = $row;
}

sqlsrv_free_stmt($result);

header('Content-Type: application/json');
echo json_encode($data);
?>
