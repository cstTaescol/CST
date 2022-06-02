<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");

$msg_exit = 0;
if(isset($_POST["id"]))
{
	$id=$_POST["id"];
	$telefono=strtoupper($_POST["telefono"]);
	$direccion=strtoupper($_POST["direccion"]);
	$email=$_POST["emails"];
	$sql="UPDATE consignatario SET telefono='$telefono',
								direccion='$direccion',
								emails='$email' 
									WHERE id = '$id'";
	mysql_query($sql,$conexion);
	$msg_exit=1;
}
echo $msg_exit;
?>