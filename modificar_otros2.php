<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

/*
PROCEDIMIENTO:
1. Obtener los datos posible a modificacion
3. Modificar el Despacho
*/		

if(isset($_POST["id"]))
{
	//1
	$id_otros=$_POST["id"];
	$descripcion=strtoupper($_POST["descripcion"]);

	//2
	$sql="UPDATE otros SET observaciones='$descripcion'
							WHERE id='$id_otros'";
	mysql_query($sql,$conexion) or die (mysql_error());

	echo "
	<script language=\"javascript\">
		alert(\"DESPACHO Modificado Satisfactoriamente\");
		document.location='consulta_identificar_otros.php';
	</script>";
}
else
{
	echo "
	<script language=\"javascript\">
		alert(\"ERROR al obtener el codigo de DESPACHO\");
		document.location='base.php';
	</script>";
	
}
?>