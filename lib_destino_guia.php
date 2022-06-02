<?php
//Evaluar si la disposicion no exige ningun tipo de deposito
if ($id_disposicion ==28 || $id_disposicion ==21 || $id_disposicion ==20 || $id_disposicion ==19 || $id_disposicion ==25 || $id_disposicion ==29 || $id_disposicion ==23 || $id_disposicion ==13 || $id_disposicion ==15)
	{
		$sql3="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$destino=$fila3["nombre"];
	}
else
{
	$id_deposito=$fila["id_deposito"];
	$sql3="SELECT nombre FROM deposito WHERE id='$id_deposito'";
	$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila3=mysql_fetch_array($consulta3);
	$destino=$fila3["nombre"];
}
if ($id_disposicion == 26 || $id_disposicion == 27) 
{
	$destino="BODEGA DIAN";
}
?>