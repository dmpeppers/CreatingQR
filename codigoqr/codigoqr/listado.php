<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado</title>
    <script>
    function updateFileName(input, id) {
        const fileName = input.files[0].name;
        
    }
    function refreshPage(){
   
        
        location.href('listado.php');
    }
</script>
</head>
<body>


<?php
    include('conexion.php');
    $id = $_POST['id'];
    if($id=="") {
     
    $consulta = "SELECT p.id, nombre, iniciales, nombre_acceso, n.id as idAccesos FROM PERSONAS p INNER JOIN NIVELES_ACCESOS n ON n.id = id_nivel_acceso"; 
    $declaracion = sqlsrv_query($conexion, $consulta);

    if($declaracion === false){
        die(print_r(sqlsrv_errors(), true));
    }

    echo"
    <table border='2px black'>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Iniciales</th>
            <th>Nombre Acceso</th>
            <th>Imagen</th>
            <th>Editar</th>
            <!-- si esta la imagen mostrarla sino poner un mensaje sin imagen y boton -->
        </tr>
    ";
    
    
    
  
    while($datosPersona = sqlsrv_fetch_array($declaracion, SQLSRV_FETCH_ASSOC)){
        
        if($datosPersona){
            $imagePath = 'personas/id' . htmlspecialchars($datosPersona['id']) . '.jpg';
           echo "<tr>
                <td>";
                
                $id = htmlspecialchars($datosPersona['id']);
                echo  " $id </td>
                        <td>";
                $nombre = htmlspecialchars($datosPersona['nombre']);
                echo " $nombre </td>
                <td>";
                $iniciales = htmlspecialchars($datosPersona['iniciales']); 
                echo "$iniciales </td>
                <td>";
                $nombre_acceso = htmlspecialchars($datosPersona['nombre_acceso']);
                $idAccesos = htmlspecialchars($datosPersona['idAccesos']);
                echo "$nombre_acceso </td>";
                if (file_exists($imagePath)) {
                    // Display the image
                    echo "<td><img src='" . $imagePath . "' alt='Profile Image' width='100'></td>";
                } else {
                    // If the image doesn't exist, show the upload form
                    echo "<td>
                            <form action='listado.php' method='post' enctype='multipart/form-data'>
                                <input type='hidden' name='id' value='" . htmlspecialchars($datosPersona['id']) . "'>
                                 <input type='file' name='file' onchange='updateFileName(this, " . htmlspecialchars($datosPersona['id']) . ") '>
                                <button type='submit'>Enviar</button>
                            </form>
                </td>";    
                }
                echo "<td><form action='editar.php' method='get'>
                         <input type='hidden' name='id' value='$id'>
                         <input type='hidden' name='nombre' value='$nombre'>
                        <input type='hidden' name='iniciales' value='$iniciales'>
                        <input type='hidden' name='idAccesos' value='$idAccesos'>
                            <button type='submit'>Enviar</button>
                </form></td>";
                echo "</tr>";

            
        }
}

echo "</table>";
    }
else {
    
    $uploadDir = 'personas/';
    
    // New file name will be idX.jpg, where X is the person's ID
    $newFileName = 'id' . $id . '.jpg'; 
    
    // Complete path for the new file
    $uploadFile = $uploadDir . $newFileName;
    
    // Move uploaded file and rename it to idX.jpg
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        echo "File uploaded successfully as " . $newFileName;
        // Optionally, redirect back to your page
        $reloadConsulta = "SELECT p.id, nombre, iniciales, nombre_acceso FROM PERSONAS p INNER JOIN NIVELES_ACCESOS n ON n.id = id_nivel_acceso";
        $reloaddeclaracion = sqlsrv_query($conexion, $reloadConsulta);
        header("Location: listado.php"); 
       
    } else {
        echo "Error uploading the file.";
    }
} 

sqlsrv_free_stmt($declaracion);

sqlsrv_close($conexion);
?>

    
</body>
</html>