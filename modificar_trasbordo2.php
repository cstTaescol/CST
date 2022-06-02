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
	$id_trasbordo=$_POST["id"];
	$observaciones=strtoupper($_POST["observaciones"]);
	$destinatario=strtoupper($_POST["destinatario"]);

	//2
	$sql="UPDATE trasbordo SET observaciones='$observaciones',
							destinatario='$destinatario'
							WHERE id='$id_trasbordo'";
	mysql_query($sql,$conexion) or die (mysql_error());

	echo "
	<script language=\"javascript\">
		alert(\"DESPACHO Modificado Satisfactoriamente\");
		document.location='consulta_identificar_trasbordo.php';
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