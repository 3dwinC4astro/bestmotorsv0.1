<?php
// Incluir el archivo de conexión a la base de datos
include "lib/conexion.php";

// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $valor = $_POST["valor"];
    $marca = $_POST["marca"];
    $modelo = $_POST["modelo"];
    $anio = $_POST["anio"];
    $color = $_POST["color"];
    $kilometraje = $_POST["kilometraje"];
    $tipoCombustible = $_POST["tipoCombustible"];
    $puertas = $_POST["puertas"];
    $placa = $_POST["placa"];
    $idConcesionario = $_POST["idConcesionario"];

    // Verificar si se ha enviado algún archivo
    if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Ruta donde se guardará la imagen
        $ruta_destino = 'C:/xampp/htdocs/miProyectoBS/img/' . basename($_FILES['imagen']['name']);
        $ruta_destino = 'img/'. basename($_FILES['imagen']['name']);
        // Movemos el archivo desde la ubicación temporal a la ruta destino
        if(move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            // Preparar la consulta SQL para insertar un nuevo carro
            $query = "INSERT INTO Carro (valor, marca, modelo, anio, color, kilometraje, tipoCombustible, puertas, placa, imagen, idConcesionario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            // Preparar la sentencia
            $stmt = mysqli_prepare($conexion, $query);
            
            // Vincular los parámetros a la sentencia preparada
            mysqli_stmt_bind_param($stmt, "ssssssssssi", $valor, $marca, $modelo, $anio, $color, $kilometraje, $tipoCombustible, $puertas, $placa, $ruta_destino, $idConcesionario);
            
            // Ejecutar la sentencia
            if(mysqli_stmt_execute($stmt)) {
                // Si la inserción fue exitosa
                echo "¡Carro agregado correctamente!";
            } else {
                // Si ocurrió un error durante la inserción
                echo "Error al agregar el carro: " . mysqli_error($conexion);
            }

            // Cerrar la sentencia
            mysqli_stmt_close($stmt);
        } else {
            echo "Ha ocurrido un error al subir la imagen.";
        }
    } else {
        echo "No se ha seleccionado ninguna imagen.";
    }
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
