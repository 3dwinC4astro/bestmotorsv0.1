<?php
// Incluir el archivo de conexión a la base de datos
include "lib/conexion.php";

// Verificar si se ha enviado una solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $idCarro = $_POST['idCarro'];

    // Consultar la base de datos para obtener la imagen correspondiente al idCarro
    $sql = "SELECT imagen FROM Carro WHERE idCarro = '$idCarro';";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        // Mostrar la imagen dentro de un contenedor con estilos CSS
        echo '<div style="display: flex; justify-content: center; align-items: center; height: 100vh; background-color: black; border: 5px solid black;">';
        while($row = $result->fetch_assoc()) {
            echo '<img src="' . $row['imagen'] . '" alt="Imagen del vehículo" style="max-width: 100%; max-height: 100%; object-fit: contain;">';
        }
        echo '</div>';
    } else {
        echo "0 resultados";
    }
} else {
    echo "Error: No se ha enviado una solicitud POST";
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
