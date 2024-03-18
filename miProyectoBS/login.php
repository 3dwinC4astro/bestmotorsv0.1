<?php
include "lib/header.php";
include "lib/conexion.php";
session_start(); // Iniciar sesión

if(isset($_SESSION['usuario'])) { // Si el usuario ya está logueado, redirigirlo a la página principal
    header("Location: index.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar las credenciales (supongamos que tienes un usuario y una contraseña hardcoded)
    $usuario_correcto = "usuario";
    $contrasena_correcta = "contrasena";

    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    if($usuario === $usuario_correcto && $contrasena === $contrasena_correcta) {
        $_SESSION['usuario'] = $usuario; // Establecer la sesión
        header("Location: carros.php"); // Redirigir al usuario a la página de carros
        exit;
    } else {
        $mensaje_error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
          
        }

        .centrado {
            background-color:aquamarine;
            width: 300px; /* Puedes ajustar el ancho según sea necesario */
         
            padding: 20px;
        }

        .circle {
            display: inline-block;
            width: 50px;
            height: 50px;
            line-height: 50px;
            border-radius: 60%;
            background-color: yellow;
            text-align: center;
            color: black;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer; /* Añadido para indicar que es clickeable */
            transition: background-color 0.3s; /* Transición de color de fondo */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="centrado">
        <div style="display: flex; align-items: center;">
    <span class="circle" id="circleLink">BM</span>
    <h2 style="margin-left: 10px;">Iniciar sesión</h2>
</div>

        
            <?php if(isset($mensaje_error)): ?>
                <p style="color: red;"><?php echo $mensaje_error; ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <label for="usuario">Usuario:</label><br>
                <input type="text" id="usuario" name="usuario" required><br>
                <label for="contrasena">Contraseña:</label><br>
                <input type="password" id="contrasena" name="contrasena" required><br><br>
                <input type="submit" value="Iniciar sesión">
            </form>
        </div>
    </div>
</body>
</html>
