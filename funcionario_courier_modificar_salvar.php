<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");

$msg_exit = 0;
if(isset($_POST["id_registro"]))
{
	$id_registro=$_POST["id_registro"];
	$no_documento=$_POST["no_documento"];
	$nombre=strtoupper($_POST["nombre"]);  
	$id_consignatario=$_POST["id_courier"];
	$sql="UPDATE courier_funcionario SET no_documento='$no_documento', nombre='$nombre', id_consignatario='$id_consignatario' WHERE id = '$id_registro'";
	mysql_query($sql,$conexion);
	$msg_exit=1;
}
echo $msg_exit;
?>