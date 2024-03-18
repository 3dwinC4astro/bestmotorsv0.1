<?php
// Incluir el archivo de conexión a la base de datos
include "lib/conexion.php";

// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];
    $encargado = $_POST["encargado"];
    $horario = $_POST["horario"];
    $ciudad = $_POST["ciudad"];
    $direccion = $_POST["direccion"];

    // Preparar la consulta SQL para insertar un nuevo concesionario
    $query = "INSERT INTO Concesionario (nombre, telefono, correo, encargado, horario, ciudad, direccion) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    // Preparar la sentencia
    $stmt = mysqli_prepare($conexion, $query);
    
    // Vincular los parámetros a la sentencia preparada
    mysqli_stmt_bind_param($stmt, "sssssss", $nombre, $telefono, $correo, $encargado, $horario, $ciudad, $direccion);
    
    // Ejecutar la sentencia
    if(mysqli_stmt_execute($stmt)) {
        // Si la inserción fue exitosa
        echo "¡Concesionario agregado correctamente!";
    } else {
        // Si ocurrió un error durante la inserción
        echo "Error al agregar el concesionario: " . mysqli_error($conexion);
    }

    // Cerrar la sentencia
    mysqli_stmt_close($stmt);
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
