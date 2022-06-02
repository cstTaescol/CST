<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if(isset($_REQUEST['id_registro']))
{
	$id_usuario=$_SESSION['id_usuario'];
	$fecha_creacion=date("Y").date("m").date("d");
	$hora_registro=date("H:i:s");

	$id_guia=$_REQUEST['id_guia'];
	$id_registro=$_REQUEST['id_registro'];
	$id_vuelo=$_REQUEST['id_vuelo'];
	
	//*************************	
	$sql_adicional="SELECT piezas_recibido, peso_recibido FROM despaletizaje WHERE id='$id_registro'";
	$consulta_adicional=mysql_query ($sql_adicional,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila_adicional=mysql_fetch_array($consulta_adicional);
	$piezas_recibido=$fila_adicional["piezas_recibido"];
	$peso_recibido=$fila_adicional["peso_recibido"];
	//*************************
	
	//1. eliminar despaletizaje	
	$sql_despacho="DELETE FROM despaletizaje WHERE id='$id_registro'";
	mysql_query($sql_despacho,$conexion) or die ("ERROR 2: ".mysql_error());	
				
	//2. almacenamiento del traking
	$sql_trak="INSERT INTO tracking (id_guia,
									 fecha_creacion,
									 hora,
									 evento,
									 tipo_tracking,
									 id_usuario) 
										VALUE ('$id_guia',
											   '$fecha_creacion',
											   '$hora_registro',
											   'DESPALETIZAJE ELIMINADO CON:<br>
											   PIEZAS:$piezas_recibido<br>
											   PESO:$peso_recibido',
											   '1',
											   '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die ("Error 3 ".mysql_error());

	echo "<script language=\"javascript\">
			alert ('Datos eliminados de manera Exitosa');
			document.location='despaletizaje1.php?vuelo=$id_vuelo';
		</script>";
}
else
{
	echo "<script language=\"javascript\">
			alert ('ERROR:El servidor no pudo obtener los datos, intente nuevamente, si el problema persiste comuniquese con el soporte tecnico');
			document.location='base.php';
		</script>";
}
?>
