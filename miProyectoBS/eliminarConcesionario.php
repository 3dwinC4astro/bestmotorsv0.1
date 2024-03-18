<?php
include "lib/conexion.php";

if(isset($_GET['nombre'])) {
    $nombre = $_GET['nombre'];

    // Sentencia SQL para eliminar el concesionario según su nombre
    $sql = "DELETE FROM Concesionario WHERE nombre = ?";

    $stmt = mysqli_prepare($conexion, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $nombre); 
        mysqli_stmt_execute($stmt);

        // Verificamos si se eliminó correctamente
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Eliminado correctamente.";
        } else {
            // Verificamos si hay un error de integridad referencial
            if (mysqli_errno($conexion) == 1451) {
                echo "No se ha podido eliminar ya que el concesionario está en uso.";
            } else {
                echo "No se pudo eliminar.";
            }
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "Error al preparar la sentencia SQL: " . mysqli_error($conexion);
    }
} else {
    echo "No se proporcionó un nombre para eliminar.";
}

mysqli_close($conexion);
?>
