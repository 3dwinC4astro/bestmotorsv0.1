<?php
// Incluir el archivo de conexión a la base de datos
include "lib/conexion.php";

// Verificar si se recibieron los datos del formulario de modificación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $idConcesionario = $_POST['idConcesionario'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $encargado = $_POST['encargado'];
    $horario = $_POST['horario'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];

    // Preparar la consulta SQL para actualizar los datos del concesionario
    $query = "UPDATE Concesionario SET nombre=?, telefono=?, correo=?, encargado=?, horario=?, ciudad=?, direccion=? WHERE idConcesionario=?";
    $stmt = mysqli_prepare($conexion, $query);
    
    // Verificar si la consulta se preparó correctamente
    if ($stmt) {
        // Vincular los parámetros
        mysqli_stmt_bind_param($stmt, "sssssssi", $nombre, $telefono, $correo, $encargado, $horario, $ciudad, $direccion, $idConcesionario);
        
        // Ejecutar la consulta
        if (mysqli_stmt_execute($stmt)) {
            // Enviar una respuesta de éxito
            echo "Los datos del concesionario se han actualizado correctamente.";
        } else {
            // Enviar una respuesta de error si la consulta falló
            echo "Error al actualizar los datos del concesionario: " . mysqli_error($conexion);
        }

        // Cerrar la declaración
        mysqli_stmt_close($stmt);
    } else {
        // Enviar una respuesta de error si la consulta no se preparó correctamente
        echo "Error al preparar la consulta: " . mysqli_error($conexion);
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conexion);
} else {
    // Enviar una respuesta de error si la solicitud no es POST
    echo "Este script solo responde a solicitudes POST.";
}
?>
