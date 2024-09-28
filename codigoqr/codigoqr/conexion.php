<?php
$servidor = "82.223.31.134";
$opcionesConexion = array(
    "Database" => "PRACTICAS",
    "UID" => "bbdd",
    "PWD" => "2423dcl",
    "CharacterSet" => "UTF-8"
);

// Conectar a bbdd
$conexion = sqlsrv_connect($servidor, $opcionesConexion);

if (!$conexion) {
    die(print_r(sqlsrv_errors(), true));
}

?>