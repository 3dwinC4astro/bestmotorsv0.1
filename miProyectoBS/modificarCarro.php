<?php
// Incluir el archivo de conexión a la base de datos
include "lib/conexion.php";

// Verificar si se recibieron los datos del formulario de modificación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $idCarro = $_POST['idCarro'];
    $valor = $_POST['valor'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $anio = $_POST['anio'];
    $color = $_POST['color'];
    $kilometraje = $_POST['kilometraje'];
    $tipoCombustible = $_POST['tipoCombustible'];
    $puertas = $_POST['puertas'];
    $placa = $_POST['placa'];

    // Preparar la consulta SQL para actualizar los datos del concesionario
    $query = "UPDATE Carro SET valor=?, marca=?, modelo=?, anio=?, color=?, kilometraje=?, tipoCombustible=?, puertas=?, placa=? WHERE idCarro=?";
    $stmt = mysqli_prepare($conexion, $query);
    
    // Verificar si la consulta se preparó correctamente
    if ($stmt) {
        // Vincular los parámetros
        mysqli_stmt_bind_param($stmt, "sssssssssi", $valor, $marca, $modelo, $anio, $color, $kilometraje, $tipoCombustible, $puertas, $placa, $idCarro);
        
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
