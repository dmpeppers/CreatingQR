<?php
include('conexion.php');

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            margin : 0;
            padding: 0 ;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .bienvenido,
        .entrada,
        .salida{
            width: 100%;
            height: 70vh;
            margin: 0;
            padding: 0px;
            display: flex;
            justify-content: center;
            align-content: space-between;
            flex-direction: column;
            background-size: cover;
            background-repeat: no-repeat;
        }
        .entrada{
            background-image: url('img/1.png');
           
            
        }
        .salida{
            background-image: url('img/2.png'); 
        }

        .bienvenido{
            background-image: url('img/3.png'); 
        }
       img{
            width: 100px;
            margin-left: 37%;
            margin-right: 37%;
       }
       h1 {
        font-size : 2.5em;
        text-align: center;
        color: white;
        text-shadow: 0 1px 2px black;
       }

       p {
        font-size : 2em;
        text-align: center;
        color: black
       }
       .hidden{
        display : none;
       }


    </style>
</head>
<body>
        
<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    

    $codigo = $_GET['code'] ?? null;
    $codigo = str_replace(' ', '+', $codigo);

    if (!$codigo) {
        echo "<h1>Error, código no existe.</h1>";
    } else {
        // Consulta con parámetro
        $consulta = "SELECT QR FROM PERSONAS WHERE QR = ?";
        $parametros = array($codigo);
        $declaracion = sqlsrv_query($conexion, $consulta, $parametros);

        if ($declaracion === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_has_rows($declaracion)) {
        
            include('codificar.php');
            $desencriptador = desencriptar($codigo);
            $lista = explode(',', $desencriptador);
            

            if ($lista[0] !== null) {
                $datoId = $lista[0];
                $nombre = $lista[1];
                $iniciales = $lista[2];
            } else {
                $datoId = $desencriptador;  // Manejar el caso sin coma
            }

           
?>
            
<?php
            $num = (int)($datoId);
            
            $consultaBuscar = "WITH UltimaFecha AS (
                            SELECT MAX(fecha) AS fecha_mas_reciente
                            FROM ESTATUS
                            )
                            SELECT TOP 1 id_persona, hora, fecha, tipo
                            FROM ESTATUS
                            WHERE fecha = (SELECT fecha_mas_reciente FROM UltimaFecha)
                            AND id_persona = ?
                            ORDER BY hora DESC;
";              

            $parametrosBuscar = array($num);
            $declaracionBuscar = sqlsrv_query($conexion, $consultaBuscar, $parametrosBuscar);

            if ($declaracionBuscar === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            $imagen = "personas/id".$num.".jpg";
            if (!sqlsrv_has_rows($declaracionBuscar) || $declaracionBuscar==null) {
                
                echo "<div class='bienvenido'><h1>Bienvenido,a $nombre</h1>
                <img src='$imagen'></div>";
            }
                
            
                $tipo='';
                while ($fila = sqlsrv_fetch_array($declaracionBuscar, SQLSRV_FETCH_ASSOC)) {
                   $tipo = $fila['tipo'];
                   $horaAnterior = $fila['hora'];
                   $id_persona = $fila['id_persona'];
               
                   if($tipo === "E"){
                      ?>
                          <div id="contenedorSalida" class="<?= $tipo === 'E' ? 'salida' : 'hidden' ?>">
                          <h1><?="$nombre"?> <?="$iniciales"?></h1>
                          <img src="<?=$imagen?>">
                       <?php
                       echo "<p><strong>Hora de entrada fue: " . date_format($horaAnterior, 'h:i:s') . "</strong></p>";
                       ?>
                       </div>
                       <?php
                   }else{
                       ?>
                       <div id="contenedorEntrada" class="<?= $tipo === 'S' ? 'entrada' : 'hidden' ?>">
                       <h1><?="$nombre"?> <?="$iniciales"?></h1>   
                       <img src="<?=$imagen?>">
                       <?php
                       echo "<p><strong>Hora de salida fue: " . date_format($horaAnterior, 'h:i:s') . "</strong></p>";
                       ?>
                       </div>
                       <?php
                   }
                   
                
            }
                
            
         

            sqlsrv_free_stmt($declaracionBuscar);

            $insertar = "INSERT INTO ESTATUS(id_persona, tipo) 
                VALUES(?, ?)";

            if ($tipo === 'E') {
                $tipo = 'S';
            } else {
                $tipo = 'E';
            }

            $parametrosInsertar = array($num, $tipo);
            $declaracionInsertar = sqlsrv_query($conexion, $insertar, $parametrosInsertar);

            if ($declaracionInsertar === false) {
                die(print_r(sqlsrv_errors(), true));
            }

            // Liberar el recurso de la consulta
            sqlsrv_free_stmt($declaracionInsertar);

               // Recorrer resultados

               
            $contadorEntrada = "
            SELECT COUNT(tipo) AS count_tipo_E
            FROM ESTATUS
            WHERE id_persona = ?
            AND tipo = 'E';
            ";
            $parametrosEntrada = array($num);

            // Execute the query
            $declaracionEntrada = sqlsrv_query($conexion, $contadorEntrada, $parametrosEntrada);

            // Check if query execution failed
            if ($declaracionEntrada === false) {
            die(print_r(sqlsrv_errors(), true));  // Print SQL errors and stop execution
            }
            ?>
            <div class="cantidad">
            <?php
            // Fetch the result (if there is any)
            if ($entradavar = sqlsrv_fetch_array($declaracionEntrada, SQLSRV_FETCH_ASSOC)) {
            $count_tipo_E = $entradavar['count_tipo_E'];  // Extract the count from the result
           
            echo "<p>Número de Entradas: " . $count_tipo_E . "</p>";  // Output the count
            } else {
            echo "<p>No se encontraron entradas.</p>";  // In case no result is fetched
            }

            // Free the query resources
            sqlsrv_free_stmt($declaracionEntrada);

            $contadorSalida = "
            SELECT COUNT(tipo) AS count_tipo_S
            FROM ESTATUS
            WHERE id_persona = ?
            AND tipo = 'S';
            ";
            $parametrosSalida = array($num);

            // Execute the query
            $declaracionSalida = sqlsrv_query($conexion, $contadorSalida, $parametrosSalida);

            // Check if query execution failed
            if ($declaracionSalida === false) {
            die(print_r(sqlsrv_errors(), true));  // Print SQL errors and stop execution
            }

            // Fetch the result (if there is any)
            if ($entradavar = sqlsrv_fetch_array($declaracionSalida, SQLSRV_FETCH_ASSOC)) {
            $count_tipo_S = $entradavar['count_tipo_S'];  // Extract the count from the result
           
            echo "<p>Número de Salidas: " . $count_tipo_S . "</p>";  // Output the count

            } else {
            echo "<p>No se encontraron Salidas.</p>";  // In case no result is fetched
            }
            ?>
            </div>
            <?php
            // Free the query resources
            sqlsrv_free_stmt($declaracionSalida);
  

        } else {
            echo "<p>No se encontró el código</p>";
        }
    }
       
        sqlsrv_free_stmt($declaracion);
    sqlsrv_close($conexion);
?>

</body>
</html>
