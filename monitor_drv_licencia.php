<?php
//1. Consulta para verificar la disponibilidad del Servidor de Correo
include("config/configuracion.php");

$respuesta=0; //error por defecto
$licencia=CLAVE;
$cliente=CLIENTE;
$respuesta="http://felcomcolombia.com/sic/servidor/licenciamiento.php?lc=$licencia&cliente=$cliente";
echo $respuesta;
?>