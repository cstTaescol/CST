<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_registro=$_REQUEST["id_registro"];
$id_usuario=$_SESSION['id_usuario'];
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<p align="center">
	Espere un Momento, se est&aacute; procesando su informaci&oacute;n<br>
	<img src="imagenes/cargando.gif" width="20" height="21" alt="cargando">
</p>
<?php
//1. Carga estado actual de la preinspeccion para cambiarlo
$sql="SELECT estado,id_guia FROM preinspeccion WHERE id='$id_registro'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$estado=$fila['estado'];
$id_guia=$fila['id_guia'];

if ($estado=="A")
{
	$estado="I";
}
else
	{
		$estado="A";
	}
mysql_query ("UPDATE preinspeccion SET estado='$estado' WHERE id='$id_registro'",$conexion) or die ("ERROR 2 : ". mysql_error(). " INFORME AL SOPORTE TECNICO");


if ($estado=="A")
{
	$estado="ACTIVO";
}
else
	{
		$estado="INACTIVO";
	}

//2. almacenamiento del traking
$sql_trak="INSERT INTO tracking (id_guia,
								 fecha_creacion,
								 hora,
								 evento,
								 tipo_tracking,
								 id_usuario) 
									VALUE ('$id_guia',
										   '$fecha',
										   '$hora',
										   'PRE-INSPECCION No.$id_registro CAMBIA A ESTADO $estado',
										   '1',
										   '$id_usuario')";
mysql_query	($sql_trak,	$conexion) or die ("ERROR 3 : ". mysql_error(). " INFORME AL SOPORTE TECNICO");								   
echo "
	<script>
		alert ('EL REGISTRO FUE MODIFICADO SATISFACTORIAMENTE');
		document.location='base.php';
	</script>
	";
?>
</body>
</html>

