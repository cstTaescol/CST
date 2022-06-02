<?php
//Consulta para cargar las piezas y peso que ya se despaletizaron de esta guia.
$sql3="SELECT SUM(piezas_recibido) AS total_piezas, SUM(peso_recibido) AS total_peso FROM despaletizaje WHERE id_vuelo='$id_vuelo' AND id_guia='$id_guia'";
$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila3=mysql_fetch_array($consulta3);
$piezas_recibido=$fila3["total_piezas"];
$peso_recibido=round($fila3["total_peso"],2);
if ($piezas_recibido == "")$piezas_recibido=0;
if ($peso_recibido == "")$peso_recibido=0;
?>