<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

$id_usuario=$_SESSION['id_usuario'];
$id_guia=$_POST['id_guia'];
$id_registro=$_POST['id_registro'];
$observaciones=strtoupper($_POST['descripcion']);
//Caracteres especiales
$texto=$observaciones;
include("config/caracteres_especiales.php");
$observaciones=$texto;
//**********************


$fecha_creacion=date("Y").date("m").date("d");
$hora_registro=date("H:i:s");

/* PROCEDIMIENTO
1. Actualizar Datos de la inconsistencia
2. almacenamiento del traking
3. Aviso de Guardado Exitoso
*****************************************/

//1. Actualizar Datos de la inconsistencia
$sql="UPDATE inconsistencias SET observaciones='$observaciones', estado='F' WHERE id='$id_registro'";
mysql_query($sql,$conexion) or die ("Error al Modificar las Inconsistencias ". mysql_error());

//2. almacenamiento del traking
$sql_trak="INSERT INTO tracking (id_guia,
								 fecha_creacion,
								 hora,
								 evento,
								 tipo_tracking,
								 id_usuario) 
									value ('$id_guia',
										   '$fecha_creacion',
										   '$hora_registro',
										   'SOLUCIONARON LA INCONSISTENCIA POR GUIA FALTANTE, CON LA SIGUIENTE OBERVACION:$observaciones',
										   '1',
										   '$id_usuario')";
mysql_query($sql_trak,$conexion) or die (mysql_error());

//3. Aviso de Guardado Exitoso
echo '<script language="javascript">
		alert("Atencion: Guia Solucionada Satisfactoriamente");
		document.location="base.php";
	</script>';