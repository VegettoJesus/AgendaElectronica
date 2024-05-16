<?php 
include '../conn/conexion.php';

$usuario = isset($_POST["user"]) ? $_POST["user"] : '';
$clave = isset($_POST["pass"]) ? $_POST["pass"] : '';

if(!empty($_POST["btnIngresar"])){
    if (!empty($usuario) && !empty($clave)) {
        $query = "SELECT * FROM new_usuarios WHERE id_usuario = ? AND cast(DecryptByPassPhrasE(?, contrasenia) as varchar(200)) = ?";
        $params = array($usuario, $clave, $clave);
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    
        if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            header("Location: calendarioMisTareas.php");
            exit();
        } else {
            echo '<div style="padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            position: relative;
            display: block; color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;">ACCESO DENEGADO</div>';
        }
    
        sqlsrv_free_stmt($stmt);
    } 
}else{
    echo '<div style="padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
    position: relative;
    display: block; color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;">LOS CAMPOS ESTÁN VACÍOS</div>';
}
?>
