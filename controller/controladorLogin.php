<?php 
if (file_exists('../conn/conexion.php')) {
    include '../conn/conexion.php';
} 
elseif (file_exists('conn/conexion.php')) {
    include 'conn/conexion.php';
} 
session_start();
if(isset($_POST["btnIngresar"])){ 
    $usuario = isset($_POST["user"]) ? $_POST["user"] : '';
    $clave = isset($_POST["pass"]) ? $_POST["pass"] : '';

    if (!empty($usuario) && !empty($clave)) {
        $query = "SELECT * FROM new_usuarios WHERE id_usuario = ? AND cast(DecryptByPassPhrasE(?, contrasenia) as varchar(200)) = ?";
        $params = array($usuario, $clave, $clave);
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    
        if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $_SESSION['id'] = $row['id_NewUsuario'];
            $_SESSION['usuario'] = $row['id_usuario'];
            $_SESSION['nombres'] = $row['nombres'];
            $_SESSION['apellidos'] = $row['apellidos'];
            header("Location: vistas/calendarioMisTareas.php");
            exit();
        } else {
            header("Location: login.php?error=access_denied");
            exit();
        }
    
        sqlsrv_free_stmt($stmt);
    } else {
        header("Location: login.php?error=empty_fields");
        exit();
    }
}
?>

