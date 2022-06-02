<?php
//Consulta para cargar las piezas y peso que ya se despaletizaron de esta guia.
$sql3="SELECT COUNT(id) AS cantidad, SUM(piezas) AS piezas, SUM(peso) AS peso, SUM(volumen) AS volumen FROM guia WHERE id_vuelo='$id_vuelo' AND id_tipo_bloqueo = 9";
$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila3=mysql_fetch_array($consulta3);
$cantidadGuiasSobrantes=$fila3["cantidad"];
$piezasSobrantes=$fila3["piezas"];
$pesoSobrante=$fila3["peso"];
$volumenSobrante=$fila3["volumen"];
if ($piezasSobrantes == "")$piezasSobrantes=0;
if ($pesoSobrante == "")$pesoSobrante=0;
if ($volumenSobrante == "")$volumenSobrante=0;
?>