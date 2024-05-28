<?php
// Configuración de la conexión
$serverName = "192.168.10.1"; // Puede ser una dirección IP o el nombre del servidor
$connectionOptions = array(
    "Database" => "insttel", // Nombre de la base de datos
    "Uid" => "sa", // Usuario de la base de datos
    "PWD" => "" // Contraseña del usuario
);

// Establecer la conexión
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Verificar la conexión
if ($conn === false) {
    die('Error al conectar con la base de datos: ' . print_r(sqlsrv_errors(), true));
}

// Función para cerrar la conexión
function cerrarConexion($conn) {
    sqlsrv_close($conn);
}
?>
