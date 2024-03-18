<?php
include "lib/conexion.php";

if(isset($_GET['placa'])) {
    // Sanitizar la entrada para evitar inyecciones SQL
    $placa = mysqli_real_escape_string($conexion, $_GET['placa']);

    // Sentencia SQL para eliminar el carro según la placa
    $sql = "DELETE FROM Carro WHERE placa = ?";

    $stmt = mysqli_prepare($conexion, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $placa);
        mysqli_stmt_execute($stmt);

        // Verificar si se eliminó correctamente
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            echo "El carro con placa $placa fue eliminado correctamente.";
        } else {
            echo "No se pudo eliminar el carro con placa $placa.";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "Error al preparar la sentencia SQL: " . mysqli_error($conexion);
    }
} else {
    echo "No se proporcionó la placa del carro a eliminar.";
}

mysqli_close($conexion);
?>
