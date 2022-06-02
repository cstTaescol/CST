<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");

$msg_exit = 0;
if(isset($_POST["id"]))
{
	$id=$_POST["id"];
	$marca=strtoupper($_POST["marca"]);
	$modelo=strtoupper($_POST["modelo"]);
	$carroceria=strtoupper($_POST["carroceria"]);
	$id_transportador=$_POST["id_transportador"];
	$sql="UPDATE vehiculo SET marca='$marca',modelo='$modelo',carroceria='$carroceria',id_transportador='$id_transportador'	WHERE id = '$id'";
	mysql_query($sql,$conexion);
	$msg_exit=1;
}
echo $msg_exit;
?>