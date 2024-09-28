
<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    echo "id: $id<br>";
    $nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
    $iniciales = isset($_GET['iniciales']) ? $_GET['iniciales'] : '';
    $idAccesos = isset($_GET['idAccesos']) ? $_GET['idAccesos'] : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <p><?=$nombre." ".$iniciales." ".$idAccesos?></p>
    <h2>Edit User</h2>
    <form action="editar.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($nombre); ?>" required>
        <br><br>
        
        <label for="initials">Initials:</label>
        <input type="text" name="initials" id="initials" value="<?php echo htmlspecialchars($iniciales); ?>" required>
        <br><br>

        <label for="id_name">Access Level:</label>
        <select name="id_name" id="id_name" required>
            <option value="" disabled>Select Access Level</option> <!-- Opción por defecto -->
            <option value="4" <?php echo ($idAccesos == 4) ? 'selected' : ''; ?>>ALUMNO_S</option>
            <option value="5" <?php echo ($idAccesos == 5) ? 'selected' : ''; ?>>ALUMNO_N</option>
            <option value="1" <?php echo ($idAccesos == 1) ? 'selected' : ''; ?>>CIENTIFICOS</option>
            <option value="2" <?php echo ($idAccesos == 2) ? 'selected' : ''; ?>>COLABORADORES</option>
            <option value="3" <?php echo ($idAccesos == 3) ? 'selected' : ''; ?>>PRENSA</option>
        </select>
        <br><br>
        
        <input type="submit" value="Update User">
    </form>
</body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : ''; // Valor estático o editado
    $initials = isset($_POST['initials']) ? $_POST['initials'] : ''; // Valor editado
    $id_name = isset($_POST['id_name']) ? $_POST['id_name'] : ''; // Valor editado

    // Actualizar los datos del usuario
    $sql = "UPDATE PERSONAS 
    SET nombre = ?,
    iniciales = ?,
    id_nivel_acceso = ? 
    WHERE id = ?";

    $params = array($name, $initials, $id_name, $id);
    
    // Para depurar: imprimir los valores que se están actualizando
    echo "Updating with values: " . implode(", ", $params);    
    
    $stmt = sqlsrv_query($conexion, $sql, $params);
    if ($stmt) {
        echo "User updated successfully.";
    } else {
        echo "Error: " . print_r(sqlsrv_errors(), true);
    }

    if($stmt === false){
        die(print_r(sqlsrv_errors(), true));
    }
    // Liberar la consulta y cerrar la conexión
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conexion);
}
?>
