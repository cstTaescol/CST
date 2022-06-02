<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("configuracion.php");

$msg_exit = 0;
if(isset($_POST["SIGLA"]))
{
	$SIGLA=strtoupper($_POST["SIGLA"]);
	$URLCLIENTE=$_POST["URLCLIENTE"];
	$SERVIDORDECORREO=$_POST["SERVIDORDECORREO"];
	$URLAPLICACION=$_POST["URLAPLICACION"];
	$CORREOSEGUIMIENTO=$_POST["CORREOSEGUIMIENTO"];
	$FOTO_MAX_SIZE=$_POST["FOTO_MAX_SIZE"];
	$TIEMPOSESION=$_POST["TIEMPOSESION"];
	$sql="UPDATE configuracion SET SIGLA='$SIGLA',
								URLCLIENTE='$URLCLIENTE',
								SERVIDORDECORREO='$SERVIDORDECORREO',
								URLAPLICACION='$URLAPLICACION',
								CORREOSEGUIMIENTO='$CORREOSEGUIMIENTO',
								TIEMPOSESION='$TIEMPOSESION', 
								FOTO_MAX_SIZE='$FOTO_MAX_SIZE'
									WHERE id = '1'";
	mysql_query($sql,$conexion) or die (exit('Error 1'.mysql_error()));
	$msg_exit=1;
}
echo $msg_exit;
?>