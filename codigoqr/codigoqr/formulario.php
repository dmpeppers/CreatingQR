<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
</head>
<body>
    <h2>Add User</h2>
    
    <form action="formulario.php" method="get" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
      
        
        <label for="iniciales">Initials:</label>
        <input type="text" name="iniciales" id="iniciales" required>
  
        <label for="id_nivel_acceso">Nombre de acceso</label>
        <select name="id_nivel_acceso" required>
            
            <option value="" disabled selected></option>
            <option value="4">ALUMNO_S</option>
            <option value="5">ALUMNO_N</option>
            <option value="1">CIENTIFICOS</option>
            <option value="2">COLABORADORES</option>
            <option value="3">PRENSA</option>
        </select>
      
        
        <input type="submit" value="Add User">
        
    </form>
</body>
</html>

<?php
include('conexion.php');
include('encriptacion.php');

    $nombre = $_GET['nombre'];
    $iniciales = $_GET['iniciales'];
    $id_nivel_acceso = $_GET['id_nivel_acceso'];
    if (!empty($nombre) && !empty($iniciales) && !empty($id_nivel_acceso)) {
    $nombre_acceso = [
        1 => 'CIENTIFICOS',
        2=> 'COLABORADORES',
        3=> 'PRENSA',
        4=> 'ALUMNO_S',
        5=> 'ALUMNO_N'
    ];
    echo "<p>$nombre $iniciales $id_nivel_acceso[$nombre_acceso]</p>";


    
    $sql = "INSERT INTO PERSONAS (nombre, iniciales, id_nivel_acceso) VALUES (?, ?, ?)";
    $params = [$nombre, $iniciales, $id_nivel_acceso];

    $stmt = sqlsrv_query($conexion, $sql, $params);
    if ($stmt) {
        echo "User added successfully.";
    } else {
        echo "Error: " . print_r(sqlsrv_errors(), true);
    }

    sqlsrv_free_stmt($stmt);
}

    encriptarTodo();

sqlsrv_close($conexion);
?>