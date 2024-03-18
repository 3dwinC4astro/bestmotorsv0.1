<?php 
include "lib/header.php";
include "lib/conexion.php";

// Función para prevenir SQL Injection
function prevenir_inyeccion($conexion, $string) {
    return mysqli_real_escape_string($conexion, $string);
}

?>

<div class="container overflow-hidden text-center" style="background-color: #E8F6F3;">
    <div class="row gy-5">
        <nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary justify-content-center">
            <div class="container">
                <form class="d-flex" method="GET" action="">
                <input class="form-control me-2"  type="search" placeholder="Marca, Modelo,Color, Valor y Año." aria-label="Search" name="busqueda" style="width: 300px; font-size: 14px; opacity: 0.7; border-color: green;">
<button class="btn btn-outline-success" type="submit" style="margin-right: 55px;">Buscar</button>

                    <!-- Dropdown para Ciudad -->
                    <div class="dropdown" id="ciudadDropdown" style="margin-right: 15px;">
                        <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                           Ciudad
                        </button>
                        <ul class="dropdown-menu">
                            <?php
                            // Consultar las ciudades disponibles en la base de datos
                            $query_ciudades = "SELECT DISTINCT ciudad FROM Concesionario";
                            $result_ciudades = mysqli_query($conexion, $query_ciudades);
                            while ($row_ciudad = mysqli_fetch_assoc($result_ciudades)) {
                                echo '<li><a class="dropdown-item ciudad" href="#" data-ciudad="' . htmlspecialchars($row_ciudad['ciudad']) . '">' . htmlspecialchars($row_ciudad['ciudad']) . '</a></li>';
                            }
                            mysqli_free_result($result_ciudades);
                            ?>
                        </ul>
                    </div>

                    <!-- Dropdown para Año -->
                    <div class="dropdown" id="anioDropdown" style="margin-right: 15px;">
                        <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                           Año
                        </button>
                        <ul class="dropdown-menu">
                            <?php
                            // Consultar los años disponibles en la base de datos
                            $query_anios = "SELECT DISTINCT anio FROM Carro";
                            $result_anios = mysqli_query($conexion, $query_anios);
                            while ($row_anio = mysqli_fetch_assoc($result_anios)) {
                                echo '<li><a class="dropdown-item anio" href="#" data-anio="' . htmlspecialchars($row_anio['anio']) . '">' . htmlspecialchars($row_anio['anio']) . '</a></li>';
                            }
                            mysqli_free_result($result_anios);
                            ?>
                        </ul>
                    </div>

                    <!-- Dropdown para Valor -->
                    <div class="dropdown" id="valorDropdown" style="margin-right: 15px;">
                        <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                           Valor
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item valor" href="#" data-valor="-20">Menos de 20 millones</a></li>
                            <li><a class="dropdown-item valor" href="#" data-valor="20-50">Entre 20 y 50 millones</a></li>
                            <li><a class="dropdown-item valor" href="#" data-valor="50+">Más de 50 millones</a></li>
                        </ul>
                    </div>

                    <!-- Dropdown para Marca -->
                    <div class="dropdown" id="marcaDropdown" style="margin-right: 15px;">
                        <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                           Marca
                        </button>
                        <ul class="dropdown-menu">
                        <?php
                            // Consultar las marcas disponibles en la base de datos
                            $query_marcas = "SELECT DISTINCT marca FROM Carro";
                            $result_marcas = mysqli_query($conexion, $query_marcas);
                            while ($row_marca = mysqli_fetch_assoc($result_marcas)) {
                                echo '<li><a class="dropdown-item marca" href="#" data-marca="' . htmlspecialchars($row_marca['marca']) . '">' . htmlspecialchars($row_marca['marca']) . '</a></li>';
                            }
                            mysqli_free_result($result_marcas);
                            ?>
                        </ul>
                    </div>
                </form>
                <div >
            <a href="carros.php">
    <img src="img/ajustes.png" alt="Descripción de la imagen" style="max-width: 30px; height: 30px;">
    </a>
</div>
            </div>





        </nav>
        <?php
        // Obtener el valor del input de búsqueda
        $busqueda = isset($_GET['busqueda']) ? prevenir_inyeccion($conexion, $_GET['busqueda']) : '';
        // Obtener el valor de la ciudad seleccionada
        $ciudad = isset($_GET['ciudad']) ? prevenir_inyeccion($conexion, $_GET['ciudad']) : '';
        // Obtener el valor del año seleccionado
        $anio = isset($_GET['anio']) ? prevenir_inyeccion($conexion, $_GET['anio']) : '';
        // Obtener el valor del filtro de valor seleccionado
        $valor = isset($_GET['valor']) ? prevenir_inyeccion($conexion, $_GET['valor']) : '';
        // Obtener el valor de la marca seleccionada
        $marca = isset($_GET['marca']) ? prevenir_inyeccion($conexion, $_GET['marca']) : '';

        // Realizar consulta para obtener información de los vehículos filtrados
        $query = "SELECT idCarro, valor, marca, modelo, anio, color, kilometraje, tipoCombustible, puertas, placa, imagen, idConcesionario FROM Carro WHERE (marca LIKE '%$busqueda%' OR modelo LIKE '%$busqueda%' OR color LIKE '%$busqueda%' OR valor LIKE '%$busqueda%' OR anio LIKE '%$busqueda%')";

        // Si se ha seleccionado una ciudad, agregarla como condición en la consulta
        if (!empty($ciudad)) {
            $query .= " AND idConcesionario IN (SELECT idConcesionario FROM Concesionario WHERE ciudad = '$ciudad')";
        }

        // Si se ha seleccionado un año, agregarlo como condición en la consulta
        if (!empty($anio)) {
            $query .= " AND anio = '$anio'";
        }

        // Si se ha seleccionado un filtro de valor, agregarlo como condición en la consulta
        if (!empty($valor)) {
            switch ($valor) {
                case '-20':
                    $query .= " AND valor < 20000000";
                    break;
                case '20-50':
                    $query .= " AND valor BETWEEN 20000000 AND 50000000";
                    break;
                case '50+':
                    $query .= " AND valor > 50000000";
                    break;
                default:
                    // No hacer nada si el valor del filtro no coincide con las opciones esperadas
                    break;
            }
        }

        // Si se ha seleccionado una marca, agregarla como condición en la consulta
        if (!empty($marca)) {
            $query .= " AND marca = '$marca'";
        }

        $result = mysqli_query($conexion, $query);

        $total_carros = mysqli_num_rows($result); // Obtener el número total de carros

        echo '<span style=" font-size: larger; font-weight: bold;">Carros encontrados: ' . $total_carros . '</span>';

        // Iterar sobre los resultados de la consulta y mostrar información de cada vehículo
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="col-6 mb-4">'; // Agregamos la clase "mb-4" para aplicar un margen en la parte inferior de cada carro
            
            echo '<form action="imagen.php" method="post" id="idCarro" target="_blank">';
            echo '<input type="hidden" name="idCarro" value="' . $row['idCarro'] . '">';
            echo '<button type="submit" style="border: none; background: none; padding: 0; margin: 0;" onclick="openInNewTab()">';
            echo '<img src="' . htmlspecialchars($row['imagen']) . '" class="img-thumbnail" alt="..." style="width: 450px; height: 300px;">';
            echo '</button>';
            echo '</form>';
         
            

            
            
                        
            echo '<div class="p-3">';
            echo '<span style="color: #28B463; font-size: 30px;"><strong>$' . number_format($row['valor'], 0, '.', '.') . '</strong></span><br>';
            echo '<strong>Marca:</strong> ' . htmlspecialchars($row['marca']) . '<br>';
            echo '<strong>Modelo:</strong> ' . htmlspecialchars($row['modelo']) . '<br>';
            echo '<strong>Año:</strong> ' . htmlspecialchars($row['anio']) . '<br>';
            echo '<strong>Color:</strong> ' . htmlspecialchars($row['color']) . '<br>';
            echo '<strong>Kilometraje:</strong> ' . htmlspecialchars($row['kilometraje'])  . '<br>';
            echo '<strong>Combustible:</strong> ' . htmlspecialchars($row['tipoCombustible']) . '<br>';
            echo '<strong>Puertas:</strong> ' . htmlspecialchars($row['puertas']) . '<br>';
            echo '<strong>Placa:</strong> ' . htmlspecialchars($row['placa']). '<br>';
            


            
            // Accordion con información del concesionario
            echo '<div class="accordion accordion-flush" id="accordionFlushExample">';
            echo '<div class="accordion-item">';
            echo '<h2 class="accordion-header">';
            echo '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse' . htmlspecialchars($row['idConcesionario']) . '" aria-expanded="false" aria-controls="flush-collapse' . htmlspecialchars($row['idConcesionario']) . '">';
            echo 'Concesionario';
            echo '</button>';
            echo '</h2>';
            echo '<div id="flush-collapse' . htmlspecialchars($row['idConcesionario']) . '" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">';
            echo '<div class="accordion-body">';
            // Consulta para obtener información del concesionario
            $concesionario_query = "SELECT nombre, telefono, correo, encargado, horario, ciudad, direccion FROM Concesionario WHERE idConcesionario = " . htmlspecialchars($row['idConcesionario']);
            $concesionario_result = mysqli_query($conexion, $concesionario_query);
            $concesionario_row = mysqli_fetch_assoc($concesionario_result);
            echo '<strong>Nombre:</strong> ' . htmlspecialchars($concesionario_row['nombre']) . '<br>';
            echo '<strong>Teléfono:</strong> ' . htmlspecialchars($concesionario_row['telefono']) . '<br>'; // Actualizado
            echo '<strong>Correo:</strong> ' . htmlspecialchars($concesionario_row['correo']) . '<br>'; // Actualizado
            echo '<strong>Encargado:</strong> ' . htmlspecialchars($concesionario_row['encargado']) . '<br>';
            echo '<strong>Horario:</strong> ' . htmlspecialchars($concesionario_row['horario']) . '<br>'; // Actualizado
            echo '<strong>Ciudad:</strong> ' . htmlspecialchars($concesionario_row['ciudad']) . '<br>';
            echo '<strong>Dirección:</strong> ' . htmlspecialchars($concesionario_row['direccion']) . '<br>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            echo '</div>';
            echo '</div>';
            
            // Liberar el resultado de la consulta del concesionario
            mysqli_free_result($concesionario_result); 
        }

        // Liberar el resultado
        mysqli_free_result($result);
        ?>
    </div>
</div>

<script>
    const form = document.querySelector('form');

    function createHiddenInput(name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        return input;
    }

    function handleFilterSelection(event, fieldName, value) {
        event.preventDefault();
        const existingInput = form.querySelector(`input[name="${fieldName}"]`);
        if (existingInput) {
            existingInput.value = value;
        } else {
            const newInput = createHiddenInput(fieldName, value);
            form.appendChild(newInput);
        }
        form.submit();
    }


    document.querySelectorAll('.ciudad').forEach(link => {
        link.addEventListener('click', function(event) {
            const ciudadSeleccionada = this.getAttribute('data-ciudad');
            handleFilterSelection(event, 'ciudad', ciudadSeleccionada);
        });
    });



    document.querySelectorAll('.anio').forEach(link => {
        link.addEventListener('click', function(event) {
            const anioSeleccionado = this.getAttribute('data-anio');
            handleFilterSelection(event, 'anio', anioSeleccionado);
        });
    });

    document.querySelectorAll('.valor').forEach(link => {
        link.addEventListener('click', function(event) {
            const valorSeleccionado = this.getAttribute('data-valor');
            handleFilterSelection(event, 'valor', valorSeleccionado);
        });
    });

    document.querySelectorAll('.marca').forEach(link => {
        link.addEventListener('click', function(event) {
            const marcaSeleccionada = this.getAttribute('data-marca');
            handleFilterSelection(event, 'marca', marcaSeleccionada);
        });
    });
</script>
  
<script>
            function openInNewTab() {
                document.getElementById("idCarro").target = "_blank";
            }
            </script>

<?php include "lib/footer.php"; ?>
