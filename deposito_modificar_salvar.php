<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");

$msg_exit = 0;
if(isset($_POST["cod_dian"]))
{
	$nombre=strtoupper($_POST["nombre"]);
	$cod_deposito=$_POST["cod_dian"];
	$direccion=strtoupper($_POST["direccion"]);
	$telefono=$_POST["telefono"];
	$id_admon_aduana=$_POST["admon_aduana"];
	$id_tipo_deposito=$_POST["tipo_deposito"];
	$deposito_fpu=$_POST["deposito_fpu"];

	$sql="UPDATE deposito SET nombre='$nombre', direccion='$direccion', telefono='$telefono',id_admon_aduana='$id_admon_aduana',id_tipo_deposito='$id_tipo_deposito',fpu='$deposito_fpu' WHERE id = '$cod_deposito'";											
	mysql_query($sql,$conexion);
	$msg_exit=1;	
}
echo $msg_exit;
?>