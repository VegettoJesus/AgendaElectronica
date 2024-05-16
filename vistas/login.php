
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

    <div class="container">
        <div class="info">
            <h2>bienvenido</h2>
            <hr/>
            <div class="escudo">
                <img src="../assets/img/escudo.png">
            </div>
            <p class="txt-1">Agenda Electronica</p>
        </div>
        <form method="post" action="" class="form" >
            <h2>Login</h2>
            <?php 
                include '../controller/controladorLogin.php';
            ?>
            <div class="inputs">
                <input type="text" class="box" name="user" id="user" placeholder="Ingrese tu usuario">
                <i class="bx2 fa fa-user fa-2x"></i>
                <input type="password" class="box pass" name="pass"  id="pass" placeholder="Ingrese tu contraseña">
                <i class="bx2 fa fa-lock fa-2x"></i>
                <i class="bx fa fa-eye"></i>
                <input type="submit" name="btnIngresar" value="Iniciar Sesión" class="submit">
            </div>
        </form>
    </div>
    <script src="../assets/js/login.js"></script>
</body>
</html>