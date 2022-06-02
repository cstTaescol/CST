<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");

$msg_exit = 0;
if(isset($_POST["id"]))
{
	$id=$_POST["id"];
	$nit=strtoupper($_POST["nit"]);
	$telefono1=strtoupper($_POST["telefono1"]);
	$telefono2=strtoupper($_POST["telefono2"]);
	$direccion=strtoupper($_POST["direccion"]);
	$contacto=strtoupper($_POST["contacto"]);
	$email=$_POST["mail"];
	$horas_despaletizaje=strtoupper($_POST["horas_despaletizaje"]);
	$tipo_importacion=false;
	$tipo_courier=false;
	if(isset($_POST["tipo_importacion"]))
	{
		$tipo_importacion=true;
	}
	if(isset($_POST["tipo_courier"]))
	{
		$tipo_courier=true;
	}	
	if($tipo_importacion==false && $tipo_courier== false)
	{
		//Si el usuario no marca ninguna casilla, entonces por Default se asigna la aerolinea a importaciones
		$tipo_importacion=true;
	}
	
	$sql="UPDATE aerolinea SET nit='$nit',
								telefono1='$telefono1',
								telefono2='$telefono2',
								direccion='$direccion',
								contacto='$contacto', 
								email='$email',
								horas_despaletizaje='$horas_despaletizaje',
								importacion='$tipo_importacion',
								courier='$tipo_courier'								
									WHERE id = '$id'";
	mysql_query($sql,$conexion);
	$msg_exit=1;
}
echo $msg_exit;
?>