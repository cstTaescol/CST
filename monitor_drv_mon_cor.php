<?php
error_reporting(0);
//1. Consulta para verificar la disponibilidad del Servidor de Correo
include("config/configuracion.php");
$pagina_inicio = file_get_contents(SERVIDORDECORREO); 
if ($pagina_inicio)
{
	echo 1;
}
else
{
	echo 0;
}
?>