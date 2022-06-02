<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$respuesta="0";
if (isset($_POST['nguias']))
{
	$nguias=$_POST['nguias'];
	for ($i=1; $i <= $nguias; $i++)
	{
		if (isset($_POST["ckmaster_$i"]))
		{	
			$id_guia=$_POST["ckmaster_$i"];
			$sql="UPDATE guia SET id_tipo_bloqueo='3' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (mysql_error());
			$respuesta="1";
		}
	}	
}
else
{
	$respuesta="0";
}
echo $respuesta;
?>