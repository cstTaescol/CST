<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");

$msg_exit = 0;
if(isset($_POST["id"]))
{
	$id=$_POST["id"];
	$id_consignatario=$_POST["id_consignatario"];
	$no_acta=strtoupper($_POST["no_acta"]);  
	$sql="UPDATE vehiculo_courier SET id_consignatario='$id_consignatario', no_acta='$no_acta' WHERE id = '$id'";
	mysql_query($sql,$conexion);
	$msg_exit=1;
}
echo $msg_exit;
?>