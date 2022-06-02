<?php
sleep(1);
//1. Consulta para verificar la disponibilidad del Servidor de Correo
include("config/configuracion.php");
$respuesta=0; //error por defecto

//Consulta el turno que será Atendido
$sql="SELECT id,no_turno,id_courier,id_linea FROM courier_turno WHERE visualizado=FALSE AND estado='A' ORDER BY date_creacion ASC ";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error 1'.mysql_error()));
$nfilas=mysql_num_rows($consulta);
$fila=mysql_fetch_array($consulta);
$id_turno=$fila['id'];

//Se actualiza el turno luego del tiempo de visualizacion y se marca como Visualizado
$sql="UPDATE courier_turno SET visualizado=TRUE WHERE id='$id_turno'";
mysql_query ($sql,$conexion) or die (exit('Error 2'.mysql_error()));
$respuesta=$nfilas;
echo $respuesta;
?>