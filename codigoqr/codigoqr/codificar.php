<?php
    
// Definir una clave de encriptación (debe ser segura y almacenarse con cuidado)
function encriptar($id, $nombre, $iniciales, $nombre_acceso){
    $clave_secreta = 'F3d3R4tI0Vv_2024';
    // Método de encriptación
    $metodo_encriptacion = 'aes-256-cbc';

    // Generar un IV (vector de inicialización) aleatorio
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($metodo_encriptacion));

    // Datos a encriptar
    $datos = $id . ", ". $nombre . ", " . $iniciales . ", " . $nombre_acceso;

    // Encriptar los datos
    $datos_encriptados = openssl_encrypt($datos, $metodo_encriptacion, $clave_secreta, 0, $iv);

    // El IV debe almacenarse junto con los datos encriptados
    $datos_encriptados_con_iv = base64_encode($iv . $datos_encriptados);

    
    return $datos_encriptados_con_iv;
}

function desencriptar($qr){
    $clave_secreta = 'F3d3R4tI0Vv_2024';
    // Método de encriptación
    $metodo_encriptacion = 'aes-256-cbc';

    // Para desencriptar, extraer el IV y los datos encriptados
    $iv_y_datos_encriptados = base64_decode($qr);
    $iv_extraido = substr($iv_y_datos_encriptados, 0, openssl_cipher_iv_length($metodo_encriptacion));
    $datos_encriptados_extraidos = substr($iv_y_datos_encriptados, openssl_cipher_iv_length($metodo_encriptacion));

    // Desencriptar los datos
    $datos_desencriptados = openssl_decrypt($datos_encriptados_extraidos, $metodo_encriptacion, $clave_secreta, 0, $iv_extraido);

    
    return $datos_desencriptados;
}

?>
