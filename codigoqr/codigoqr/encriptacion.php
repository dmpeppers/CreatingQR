
<?php

function encriptarTodo() {
    
    include('conexion.php');
    
    
    $consulta = "SELECT p.id, nombre, iniciales, nombre_acceso FROM PERSONAS p INNER JOIN NIVELES_ACCESOS n ON n.id = id_nivel_acceso"; 
    $declaracion = sqlsrv_query($conexion, $consulta);

    if($declaracion === false){
        die(print_r(sqlsrv_errors(), true));
    }
    
    echo"
    <h1>Encriptacion</h1>";
    
    
    
    include('codificar.php');
    while($datosPersona = sqlsrv_fetch_array($declaracion, SQLSRV_FETCH_ASSOC)){
        
        if($datosPersona){
            
                 $id      = htmlspecialchars($datosPersona['id']);
                 $nombre = htmlspecialchars($datosPersona['nombre']);
                 $iniciales = htmlspecialchars($datosPersona['iniciales']);
                 $nombre_acceso = htmlspecialchars($datosPersona['nombre_acceso']);
         

            $encriptado = encriptar($id, $nombre, $iniciales, $nombre_acceso);

           
            $insertar = "UPDATE PERSONAS SET QR = ? WHERE id = ? AND QR IS NULL";
            $params = array($encriptado, $id);
            $stmt = sqlsrv_query($conexion, $insertar, $params);
            if($declaracion === false){
                die(print_r(sqlsrv_errors(), true));
            }
        }
}


sqlsrv_free_stmt($declaracion);
sqlsrv_free_stmt($stmt);
sqlsrv_close($conexion);
}

?>
