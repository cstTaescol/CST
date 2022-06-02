<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");

$msg_exit = 0;
if(isset($_POST["id"]))
{
	$id=$_POST["id"];
	$nombre=strtoupper($_POST["nombre"]);
	$telefono1=strtoupper($_POST["telefono1"]);
	$telefono2=strtoupper($_POST["telefono2"]);
	$id_transportador=$_POST["id_transportador"];
	$sql="UPDATE conductor SET 	nombre='$nombre',telefono1='$telefono1',telefono2='$telefono2',id_transportador='$id_transportador'	WHERE id = '$id'";
	mysql_query($sql,$conexion);
	$msg_exit=1;
}
echo $msg_exit;
?>