<?php
include('conexion.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Codigos QR</title>
    <style>
        .qrcode {
            padding: 10px;
            display: inline-flex;
        }
    </style>
    
    <!-- jQuery 1.8.3 CDN -->
    <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.3.min.js"></script>

    <!-- QRCode.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>
    <h1>Generación de Códigos QR</h1>

    <?php

        // Consulta 
        $consulta = "SELECT QR FROM PERSONAS";
        $declaracion = sqlsrv_query($conexion, $consulta);
        if($declaracion === false){
            die(print_r(sqlsrv_errors(), true));
        }
    
        while($persona_data = sqlsrv_fetch_array($declaracion, SQLSRV_FETCH_ASSOC)){
            if ($persona_data) {
                // Escape del código QR para evitar inyección de código
                $qrCodigo = ($persona_data['QR']);
               
    ?>
    <div class="qrcode" data-code="https://practicas.federatio.com/codigoqr/confirmacion.php?code=<?php echo $qrCodigo; ?>">

    </div>
    
    <?php
            }
        }
        // Liberar la declaración y cerrar la conexión
        sqlsrv_free_stmt($declaracion);
        sqlsrv_close($conexion);
    ?>
    
    <script>
        $(document).ready(function(){
            // Recorremos todos los divs con la clase 'qrcode'
            $('.qrcode').each(function(){
                var dataToEncode = $(this).data('code');  // Obtenemos el código QR desde el atributo data-code
                
                var qrcode = new QRCode(this, {
                    text: dataToEncode,
                    width: 128,  // Ancho del código QR
                    height: 128  // Altura del código QR
                });
            });
        });
    </script>

    <?php

    ?>
</body>
</html>
