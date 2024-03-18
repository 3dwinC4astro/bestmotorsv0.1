<?php 
include "lib/header.php";
include "lib/conexion.php";

// Consulta SQL con sentencia preparada para evitar inyección de SQL
$query = "SELECT idConcesionario, nombre, telefono, correo, encargado, horario, ciudad, direccion FROM Concesionario";

if(isset($_GET['busqueda'])) {
    $busqueda = $_GET['busqueda'];
    $query .= " WHERE nombre LIKE ? OR ciudad LIKE ? OR idConcesionario LIKE ?";
}

$stmt = mysqli_prepare($conexion, $query);
if ($stmt) {
    if(isset($_GET['busqueda'])) {
        $busqueda = '%' . $_GET['busqueda'] . '%';
        mysqli_stmt_bind_param($stmt, "sss", $busqueda, $busqueda, $busqueda); 
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

?>

<script>
    function showConfirmation(nombre) {
        if (confirm("¿Estás seguro de que deseas eliminar este concesionario?")) {
            // Hacer una solicitud AJAX para eliminar el concesionario
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Recargar la página para mostrar los cambios actualizados
                    location.reload();
                }
            };
            xhttp.open("GET", "/miProyectoBS/eliminarConcesionario.php?nombre=" + nombre, true);
            xhttp.send();
        }
    }
</script>

<form class="d-flex" method="GET" action="" style="padding-left: 8.5%; margin-top: 10px; margin-bottom: 10px;">
  <input class="form-control me-2" style="width: 250px;" type="search" placeholder="Nombre o Ciudad" aria-label="Search" name="busqueda">
  <button class="btn btn-outline-success me-2" type="submit">Buscar</button>
  <button type="button" class="btn btn-outline-success me-2" data-bs-toggle="modal" data-bs-target="#exampleModal1">Agregar</button>
  <button class="btn btn-outline-success" type="button" style="padding-left: 8px; padding-right: 8px; margin-right: 8px;" onclick="window.location.href='carros.php'">Carros</button>
 
  <?php
$total_concesionarios = 0;

if(isset($result) && mysqli_num_rows($result) > 0) {
    $total_concesionarios = mysqli_num_rows($result);
}

echo '<span style="margin-left: 350px; font-size: larger; font-weight: bold;">Total concesionarios: ' . $total_concesionarios . '</span>';
?>

</form>

<!-- Modal de Agregar -->
<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="width: 350px; background-color: #E8F6F3;">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel1">Agregar Nuevo Concesionario</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" style="width: 150px;">
        <form id="agregarConcesionarioForm"> <!-- Añadir un ID único al formulario -->
          <label for="nombre">Nombre</label>
          <input type="text" name="nombre" id="nombre" required style="width: 250px;"><br>
          <label for="telefono">Telefono</label>
          <input type="tel" name="telefono" id="telefono" required style="width: 250px;"><br>
          <label for="correo">Correo</label>
          <input type="email" name="correo" id="correo" required style="width: 250px;"><br>
          <label for="encargado">Encargado</label>
          <input type="text" name="encargado" id="encargado" required style="width: 250px;"><br>
          <label for="horario">Horario</label>
          <input name="horario" id="horario" required style="width: 250px;"><br>
          <label for="ciudad">Ciudad</label>
          <input type="text" name="ciudad" id="ciudad" required style="width: 250px;"><br>
          <label for="direccion">Dirección</label>
          <input type="text" name="direccion" id="direccion" required style="width: 250px;"><br>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="agregarConcesionarioBtn">Agregar</button> 
      </div>
    </div>
  </div>
</div>

<!-- Modal de Modificar -->
<div class="modal fade" id="modalModificarConcesionario" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="width: 350px;">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel2">Modificar Concesionario</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" style="width: 150px;">
        <form id="modificarConcesionarioForm"> <!-- Añadir un ID único al formulario -->
          <label for="nombre">Nombre</label>
          <input type="text" name="nombre" id="mod_nombre" required style="width: 250px;"><br>
          <label for="telefono">Telefono</label>
          <input type="tel" name="telefono" id="mod_telefono" required style="width: 250px;"><br>
          <label for="correo">Correo</label>
          <input type="email" name="correo" id="mod_correo" required style="width: 250px;"><br>
          <label for="encargado">Encargado</label>
          <input type="text" name="encargado" id="mod_encargado" required style="width: 250px;"><br>
          <label for="horario">Horario</label>
          <input name="horario" id="mod_horario" required style="width: 250px;"><br>
          <label for="ciudad">Ciudad</label>
          <input type="text" name="ciudad" id="mod_ciudad" required style="width: 250px;"><br>
          <label for="direccion">Dirección</label>
          <input type="text" name="direccion" id="mod_direccion" required style="width: 250px;"><br>
          <input type="hidden" name="idConcesionario" id="mod_idConcesionario">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="modificarConcesionarioBtn">Guardar Cambios</button> 
      </div>
    </div>
  </div>
</div>

<?php
if(isset($result) && mysqli_num_rows($result) > 0) {
    echo '<div class="container">';
    echo '<table class="table table-success table-striped">';

    echo '<tr>';
    echo '<th>Nombre</th>';
    echo '<th>Telefono</th>';
    echo '<th>Correo</th>';
    echo '<th>Encargado</th>';
    echo '<th>Ciudad</th>';
    echo '<th>Dirección</th>';
    echo '<th>Horario</th>';
    echo '<th>Acciones</th>';
    echo '</tr>';

    while($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['nombre']) . '</td>'; // Se utiliza htmlspecialchars para evitar ataques XSS
        echo '<td>' . htmlspecialchars($row['telefono']) . '</td>';
        echo '<td>' . htmlspecialchars($row['correo']) . '</td>';
        echo '<td>' . htmlspecialchars($row['encargado']) . '</td>';
        echo '<td>' . htmlspecialchars($row['ciudad']) . '</td>';
        echo '<td>' . htmlspecialchars($row['direccion']) . '</td>';
        echo '<td>' . htmlspecialchars($row['horario']) . '</td>';
        echo '<td>'; // Abre la celda de la columna de acciones
        echo '<button type="button" class="btn btn-danger" style="margin-right: 5px; width: 95px; margin-bottom: 5px;" onclick="showConfirmation(\'' . htmlspecialchars($row['nombre']) . '\');">Eliminar</button>'; // Se utiliza htmlspecialchars para evitar ataques XSS
        echo '<button type="button" class="btn btn-warning" style="margin-right: 5px; width: 95px;" onclick="abrirModalModificar(\'' . htmlspecialchars($row['idConcesionario']) . '\', \'' . htmlspecialchars($row['nombre']) . '\', \'' . htmlspecialchars($row['telefono']) . '\', \'' . htmlspecialchars($row['correo']) . '\', \'' . htmlspecialchars($row['encargado']) . '\', \'' . htmlspecialchars($row['horario']) . '\', \'' . htmlspecialchars($row['ciudad']) . '\', \'' . htmlspecialchars($row['direccion']) . '\');">Modificar</button>';
        echo '</td>'; // Cierra la celda de la columna de acciones
        echo '</tr>';
    }

    echo '</table>'; // Cerramos la etiqueta de la tabla
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

<script>
  // Función para abrir el modal de modificación y mostrar los datos del concesionario seleccionado
  function abrirModalModificar(idConcesionario, nombre, telefono, correo, encargado, horario, ciudad, direccion) {
    document.getElementById("mod_idConcesionario").value = idConcesionario;
    document.getElementById("mod_nombre").value = nombre;
    document.getElementById("mod_telefono").value = telefono;
    document.getElementById("mod_correo").value = correo;
    document.getElementById("mod_encargado").value = encargado;
    document.getElementById("mod_horario").value = horario;
    document.getElementById("mod_ciudad").value = ciudad;
    document.getElementById("mod_direccion").value = direccion;
    $('#modalModificarConcesionario').modal('show');
  }

  // Evento de clic para el botón Modificar en el modal de modificación
  document.getElementById("modificarConcesionarioBtn").addEventListener("click", function() {
    // Obtener los datos del formulario de modificación
    var formData = new FormData(document.getElementById("modificarConcesionarioForm"));

    // Hacer una solicitud AJAX para guardar los cambios en la base de datos
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // Recargar la página para mostrar los cambios actualizados
        location.reload();
      }
    };
    xhttp.open("POST", "/miProyectoBS/modificarConcesionario.php", true); 
    xhttp.send(formData);
  });
</script>
<script>
    document.getElementById("agregarConcesionarioBtn").addEventListener("click", function() {
        var formData = new FormData(document.getElementById("agregarConcesionarioForm"));

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                location.reload();
            }
        };
        xhttp.open("POST", "/miProyectoBS/agregarConcesionario.php", true);
        xhttp.send(formData);
    });
</script>
