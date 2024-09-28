<?php
$servidor = "yourserver";
$opcionesConexion = array(
    "Database" => "PRACTICAS",
    "UID" => "bbdd",
    "PWD" => "2420",
    "CharacterSet" => "UTF-8"
);

// Conectar a bbdd
$conexion = sqlsrv_connect($servidor, $opcionesConexion);

if (!$conexion) {
    die(print_r(sqlsrv_errors(), true));
}

?>
