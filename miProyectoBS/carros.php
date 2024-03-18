<?php 
include "lib/header.php";
include "lib/conexion.php";



session_start();
if(!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}



// Consulta SQL con sentencia preparada para evitar inyección de SQL
$query = "SELECT idCarro, valor, marca, modelo, anio, color, kilometraje, tipoCombustible, puertas, placa, imagen FROM Carro";

if(isset($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
    $query .= " WHERE marca LIKE ? OR placa LIKE ?";
}

$stmt = mysqli_prepare($conexion, $query);
if ($stmt) {
    if(isset($_GET['busqueda'])) {
        $busqueda = '%' . $_GET['busqueda'] . '%';
        mysqli_stmt_bind_param($stmt, "ss", $busqueda, $busqueda);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

?>

<script>
    function showConfirmation(placa) {
        if (confirm("¿Estás seguro de que deseas eliminar este carro?")) {
            // Hacer una solicitud AJAX para eliminar el carro
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Recargar la página para mostrar los cambios actualizados
                    location.reload();
                }
            };
            xhttp.open("GET", "/miProyectoBS/eliminarCarro.php?placa=" + placa, true);
            xhttp.send();
        }
    }
</script>

<form class="d-flex" method="GET" action="" style="padding-left: 8.5%; margin-top: 10px; margin-bottom: 10px;">
  <input class="form-control me-2" style="width: 150px; border-style: solid;" type="search" placeholder="Placa" aria-label="Search" name="busqueda">
  <button class="btn btn-outline-success me-2" type="submit">Buscar</button>
  <button type="button" class="btn btn-outline-success me-2" data-bs-toggle="modal" data-bs-target="#exampleModal">Agregar</button>
  <button class="btn btn-outline-success" type="button" style="padding-left: 8px; padding-right: 8px; margin-right: 8px;" onclick="window.location.href='concesionarios.php'">Concesionarios</button>
  
  



  <a href="logout.php">
  <img src="img/salir.png" alt="" style="max-width: 40px; height: 40px;">
  </a>






  
  
  
  <?php
$total_carros = 0;

if(isset($result) && mysqli_num_rows($result) > 0) {
    $total_carros = mysqli_num_rows($result);
}

echo '<span style="margin-left: 450px; font-size: larger; font-weight: bold;">Total Carros: ' . $total_carros . '</span>';
?>
</form>


<!-- Modal de Agregar -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"  >
  <div class="modal-dialog ">
    <div class="modal-content" style="width: 540px; background-color: #E8F6F3;" >
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel1">Agregar Nuevo Carro</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" style="width: 150px;">
       <form id="agregarCarroForm"> <!-- Añadir un ID único al formulario -->
       
       <table>

<tr>
  <td>
    <label for="valor">Valor</label>
    <input type="text" name="valor" id="valor" required style="width: 250px;">
    <label for="marca">Marca</label>
    <input type="text" name="marca" id="marca" required style="width: 250px;"><br>
  </td>
  <td>
    <label for="modelo">Modelo</label>
    <input type="text" name="modelo" id="modelo" required style="width: 250px;"><br>
    <label for="anio">Año</label>
    <input type="text" name="anio" id="anio" required style="width: 250px;"><br>
  </td>
</tr>
<tr>
  <td>
    <label for="color">Color</label>
    <input type="text" name="color" id="color" required style="width: 250px;"><br>
    <label for="kilometraje">Kilometraje</label>
    <input type="text" name="kilometraje" id="kilometraje" required style="width: 250px;"><br>
  </td>
  <td>
    <label for="tipoCombustible">Tipo de combustible</label>
    <input type="text" name="tipoCombustible" id="tipoCombustible" required style="width: 250px;"><br>
    <label for="puertas">Puertas</label>
    <input type="text" name="puertas" id="puertas" required style="width: 250px;"><br>
  </td>
</tr>
<tr>
  <td>
    <label for="placa">Placa</label>
    <input type="text" name="placa" id="placa" required style="width: 250px;"><br> 
  </td>
  <td>
    <label for="imagen">Imagen</label>
    <input type="file" name="imagen" id="imagen" accept="image/*" required style="width: 250px;">
</form>

 
  </td>
</tr>
<tr>
  <td>
  <label for="idConcesionario">Concesionario</label>
  <select name="idConcesionario" id="idConcesionario" required style="width: 250px;">
    <?php
    // Consultar los concesionarios disponibles en la base de datos
    $query_concesionarios = "SELECT idConcesionario, nombre FROM Concesionario";
    $result_concesionarios = mysqli_query($conexion, $query_concesionarios);
    while ($row_concesionario = mysqli_fetch_assoc($result_concesionarios)) {
        echo '<option value="' . htmlspecialchars($row_concesionario['idConcesionario']) . '">' . htmlspecialchars($row_concesionario['nombre']) . '</option>';
    }
    mysqli_free_result($result_concesionarios);
    ?>
</select>



  </td>
  <td></td>
</tr>

</table>


        </form>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="agregarCarroBtn">Agregar</button> 
      </div>
    </div>
  </div>
</div>



<!-- Modal modificarCarro -->
<div class="modal fade" id="modalModificarCarro" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel2">Modificar Carro</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="modificarCarroForm" >
          <input type="hidden" name="idCarro" id="mod_idCarro">
            <label for="mod_valor">Valor</label>
            <input type="text" name="valor" id="mod_valor" required style="width: 250px;"><br><br>
            <label for="mod_marca">Marca</label>
            <input type="text" name="marca" id="mod_marca" required style="width: 250px;"><br><br>
            <label for="mod_modelo">Modelo</label>
            <input type="text" name="modelo" id="mod_modelo" required style="width: 250px;"><br><br>
            <label for="mod_anio">Año</label>
            <input type="text" name="anio" id="mod_anio" required style="width: 250px;"><br><br>
            <label for="mod_color">Color</label>
            <input type="text" name="color" id="mod_color" required style="width: 250px;"><br><br>
            <label for="mod_kilometraje">Kilometraje</label>
            <input type="text" name="kilometraje" id="mod_kilometraje" required style="width: 250px;"><br><br>
            <label for="mod_tipoCombustible">Tipo de combustible</label>
            <input type="text" name="tipoCombustible" id="mod_tipoCombustible" required style="width: 250px;"><br><br>
            <label for="mod_puertas">Puertas</label>
            <input type="text" name="puertas" id="mod_puertas" required style="width: 250px;"><br><br>
            <label for="mod_placa">Placa</label>
            <input type="text" name="placa" id="mod_placa" required style="width: 250px;"><br><br>
          
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" id="modificarCarroBtn" class="btn btn-primary">Guardar cambios</button>
      </div>
    </div>
  </div>
</div>






<?php
if(isset($result) && mysqli_num_rows($result) > 0) {
    echo '<div class="container">';
    echo '<table class="table table-success table-striped">';
    echo '<tr>';
    echo '<th>Valor</th>';
    echo '<th>Marca</th>';
    echo '<th>Modelo</th>';
    echo '<th>Año</th>';
    echo '<th>Color</th>';
    echo '<th>Kilometraje</th>';
    echo '<th>Tipo de Combustible</th>';
    echo '<th>Puertas</th>';
    echo '<th>Placa</th>';
    echo '<th>Imagen</th>';
    echo '<th>Acciones</th>'; // Agregamos una columna adicional para los botones
    echo '</tr>';

    while($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['valor'] . '</td>';
        echo '<td>' . $row['marca'] . '</td>';
        echo '<td>' . $row['modelo'] . '</td>';
        echo '<td>' . $row['anio'] . '</td>';
        echo '<td>' . $row['color'] . '</td>';
        echo '<td>' . $row['kilometraje'] . '</td>';
        echo '<td>' . $row['tipoCombustible'] . '</td>';
        echo '<td>' . $row['puertas'] . '</td>';
        echo '<td>' . $row['placa'] . '</td>';
        echo '<td><img src="' . $row['imagen'] . '" alt="Imagen del vehículo" width="100" height="60"></td>';
        echo '<td>'; // Abre la celda de la columna de acciones
        echo '<button type="button" class="btn btn-danger" style=" width: 95px; ;" onclick="showConfirmation(\'' . htmlspecialchars($row['placa']) . '\');">Eliminar</button>'; 
        echo '<button type="button" class="btn btn-warning" style=" width: 95px;" onclick="abrirModalModificar(\'' . htmlspecialchars($row['idCarro']) . '\',\'' . htmlspecialchars($row['valor']) . '\', \'' . htmlspecialchars($row['marca']) . '\', \'' . htmlspecialchars($row['modelo']) . '\', \'' . htmlspecialchars($row['anio']) . '\', \'' . htmlspecialchars($row['color']) . '\', \'' . htmlspecialchars($row['kilometraje']) . '\', \'' . htmlspecialchars($row['tipoCombustible']) . '\', \'' . htmlspecialchars($row['puertas']) . '\', \'' . htmlspecialchars($row['placa']) . '\');">Modificar</button>';

        echo '</td>'; // Cierra la celda de la columna de acciones
        echo '</tr>';
    }

    echo '</table>';
    echo '</div>';
} else {
    echo '<div class="container">';
    echo 'No hay resultados';
    echo '</div>';
}

mysqli_close($conexion);

?>

<?php include "lib/footer.php"; ?>
<!-- Bootstrap JS y jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<!-- Aquí va el contenido HTML -->
<script>
  // Función para abrir el modal de modificación y mostrar los datos del carro seleccionado
  function abrirModalModificar(idCarro, valor, marca, modelo, anio, color, kilometraje, tipoCombustible, puertas, placa) {
    document.getElementById("mod_idCarro").value = idCarro;
    document.getElementById("mod_valor").value = valor;
    document.getElementById("mod_marca").value = marca;
    document.getElementById("mod_modelo").value = modelo;
    document.getElementById("mod_anio").value = anio;
    document.getElementById("mod_color").value = color;
    document.getElementById("mod_kilometraje").value = kilometraje;
    document.getElementById("mod_tipoCombustible").value = tipoCombustible;
    document.getElementById("mod_puertas").value = puertas;
    document.getElementById("mod_placa").value = placa;
    $('#modalModificarCarro').modal('show');
  }



  // Evento de clic para el botón Modificar en el modal de modificación
  document.getElementById("modificarCarroBtn").addEventListener("click", function() {
    // Obtener los datos del formulario de modificación
    var formData = new FormData(document.getElementById("modificarCarroForm"));

    // Hacer una solicitud AJAX para guardar los cambios en la base de datos
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // Recargar la página para mostrar los cambios actualizados
        location.reload();
      }
    };
    xhttp.open("POST", "/miProyectoBS/modificarCarro.php", true); 
    xhttp.send(formData);
  });
</script>




<script>
document.getElementById("agregarCarroBtn").addEventListener("click", function() {
    var formData = new FormData(document.getElementById("agregarCarroForm"));
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) {
                location.reload();
            } else {
                // Mostrar mensaje de error
                document.getElementById("errorContainer").innerHTML = "Error al agregar el carro.";
                document.getElementById("errorContainer").style.display = "block";
            }
        }
    };

    xhttp.open("POST", "/miProyectoBS/agregarCarro.php", true);
    xhttp.send(formData);
});
</script>
