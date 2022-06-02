<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");

$msg_exit = 0;
if(isset($_POST["id_guia"]))
{
	$id_guia=$_POST["id_guia"];
	$master=strtoupper($_POST["guia"]);
	$embarcador=strtoupper($_POST["embarcador"]);
	$consignatario=strtoupper($_POST["consignatario"]);
	$descripcion=strtoupper($_POST["descripcion"]);
	$observaciones=strtoupper($_POST["observaciones"]);
	$sql="UPDATE guia SET master='$master',
								id_embarcador='$embarcador',
								id_consignatario='$consignatario',
								descripcion='$descripcion',
								observaciones='$observaciones'
									WHERE id = '$id_guia'";
	mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
	$msg_exit=1;
}
echo $msg_exit;
?>