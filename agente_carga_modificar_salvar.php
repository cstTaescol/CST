<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");

$msg_exit = 0;
if(isset($_POST["id"]))
{
	$id=$_POST["id"];
	$direccion=strtoupper($_POST["direccion"]);
	$telefono=strtoupper($_POST["telefono"]);
	$emails=$_POST["emails"];
	$sql="UPDATE agente_carga SET telefono='$telefono',direccion='$direccion', emails='$emails' WHERE id = '$id'";
	mysql_query($sql,$conexion);
	$msg_exit=1;
}
echo $msg_exit;
?>