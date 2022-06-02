<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

/*
PROCEDIMIENTO:
1. Obtener los datos posible a modificacion
2. Comprobar estados de exclusivos, refrigerados y observaciones
3. Modificar la remesa
*/		

if(isset($_POST["id_remesa"]))
{
	//1
	$id_remesa=$_POST["id_remesa"];
	$id_transportador=$_POST["transportador"];
	$id_conductor=$_POST["conductor"];
	$id_vehiculo=$_POST["vehiculo"];
	$planilla_envio=strtoupper($_POST["planilla_envio"]);

	//2
	if(isset($_POST["exclusivo"]))
		$exclusivo=$_POST["exclusivo"];
		else
			$exclusivo="N";

	if(isset($_POST["refrigerado"]))
		$refrigerado=$_POST["refrigerado"];
		else
			$refrigerado="N";
			
	if(isset($_POST["observacion"]))
		$observacion=strtoupper($_POST["observacion"]);
		else
			$observacion="";
	//3
	$sql="UPDATE remesa SET id_transportador='$id_transportador',
							id_vehiculo='$id_vehiculo', 
							id_conductor='$id_conductor', 
							refrigerado='$refrigerado', 
							exclusivo='$exclusivo', 
							observacion='$observacion',
							planilla_envio='$planilla_envio' 
							WHERE id='$id_remesa'";
	mysql_query($sql,$conexion) or die (mysql_error());

	echo "
	<script language=\"javascript\">
		alert(\"REMESA Modificada Satisfactoriamente\");
		document.location='consulta_identificar_remesa.php';
	</script>";
}
else
{
	echo "
	<script language=\"javascript\">
		alert(\"ERROR al obtener el codigo de la REMESA\");
		document.location='base.php';
	</script>";
	
}
?>