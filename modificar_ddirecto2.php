<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

/*
PROCEDIMIENTO:
1. Obtener los datos posible a modificacion
2. Comprobar estados de telefono
3. Modificar el Descargue Directo
*/		

if(isset($_POST["id_ddirecto"]))
{
	//1
	$id_ddirecto=$_POST["id_ddirecto"];
	$levante=$_POST["levante"];
	$declaracion=$_POST["declaracion"];
	$nombre=$_POST["nombre"];
	$agencia=$_POST["agencia"];
	$cedula=$_POST["cedula"];
	$conductor=$_POST["conductor"];
	$cedula_conductor=$_POST["cedula_conductor"];
	$placa=$_POST["placa"];
	$id_usuario=$_SESSION["id_usuario"];
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");

	
	//2
	if(isset($_POST["telefono"]))
		$telefono=$_POST["telefono"];
		else
			$telefono="";

	//3
	$sql="UPDATE descargue_directo SET levante='$levante',
							declaracion='$declaracion', 
							nombre_entregado='$nombre', 
							agencia='$agencia', 
							cedula_entregado='$cedula', 
							telefono_entregado='$telefono',
							nombre_conductor='$conductor',
							cedula_conductor='$cedula_conductor',
							placa='$placa',
							id_usuario='$id_usuario',
							fecha='$fecha',
							hora='$hora'
							WHERE id='$id_ddirecto'";
	mysql_query($sql,$conexion) or die (mysql_error());

	echo "
	<script language=\"javascript\">
		alert(\"DESCARGUE DIRECTO Modificado Satisfactoriamente\");
		document.location='consulta_identificar_ddirecto.php';
	</script>";
}
else
{
	echo "
	<script language=\"javascript\">
		alert(\"ERROR al obtener el codigo de DESCARGUE DIRECTO\");
		document.location='base.php';
	</script>";
	
}
?>