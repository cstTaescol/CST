<?php
//Esta libreria identifica el numero de la guia master a partir de el id de la guia master en una guia hija
if ($master == "") 
{
	$master="-";
}
else
{
	//Carga datos de la guia Master
	$sql_master="SELECT master FROM guia WHERE id='$master'";
	$consulta_master=mysql_query ($sql_master,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila_master=mysql_fetch_array($consulta_master);
	$master=$fila_master['master'];
}	
?>