<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<html>
<head>
<title>MODIFICACION DE FALTANTE TOTAL</title>
</head>
<body>

<p align="center">Espere, Estamos procesando su informaci&oacute;n.</p>
<p align="center"><img src="imagenes/cargando.gif" width="20" height="21" /></p>
<?php
if(isset($_REQUEST['id_guia']))
{
	$id_usuario=$_SESSION['id_usuario'];
	$fecha_creacion=date("Y").date("m").date("d");
	$hora_registro=date("H:i:s");

	$id_guia=$_REQUEST['id_guia'];
	
	//*************************	
	$sql_adicional="SELECT faltante_total FROM guia WHERE id='$id_guia'";
	$consulta_adicional=mysql_query ($sql_adicional,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila_adicional=mysql_fetch_array($consulta_adicional);
	$faltante_total=$fila_adicional["faltante_total"];
	//*************************
	if ($faltante_total == "N")
		$faltante_total="S";
	else
		$faltante_total="N";
	
	//1. Modifica el estado de faltante total de la Guia
	$sql_despacho="UPDATE guia SET faltante_total='$faltante_total' WHERE id='$id_guia'";
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
											   'GUIA MODIFICADA EN EL DESPALETIZAJE POR:<br>
											   	FALTANTE TOTAL = $faltante_total.',
											   '1',
											   '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die ("Error 3 ".mysql_error());

	echo '<script language="javascript">
			window.opener.location.reload();
			self.close();
		</script>';
}
else
{
	echo '<script language="javascript">
			alert ("ERROR: No se obtuvieron los datos PRINCIPALES en el SERVIDOR, Comuniquese con el soporte TECNICO");
			document.location="base.php";
			self.close();
		</script>';
}
?>
