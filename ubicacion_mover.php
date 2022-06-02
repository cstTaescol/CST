<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_usuario=$_SESSION['id_usuario'];
$fecha_creacion=date("Y").date("m").date("d");
$hora_registro=date("H:i:s");
$errores=false;

if(isset($_REQUEST['celda']))
	$id_celda=$_REQUEST['celda'];
else
	$errores=true;

if(isset($_REQUEST['msg']))
	$msg_posicion_antigua=$_REQUEST['msg'];
else
	$errores=true;

if(isset($_REQUEST['id_guia']))
	$id_guia=$_REQUEST['id_guia'];
else
	$errores=true;

if(isset($_REQUEST['id_posicion']))
	$id_posicion_nueva=$_REQUEST['id_posicion'];
else
	$errores=true;

if ($errores==true)
{
	echo "
		<script language=\"javascript\">
			alert(\"Error al comunicarse con el Servidor, Intente nuevamente\");
			document.location='ubicacion1.php';
		</script>";
	exit();
}

echo '<p align="center"><img src="imagenes/cargando.gif" width="20" height="21" /><br>Procesando</p>';

//Ubica la Posicion en Bodega
$sql_posiscion="SELECT * FROM posicion WHERE id='$id_posicion_nueva'";
$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit('Error al cargar datos de la nueva ubicacion '.mysql_error()));
$fila_posicion=mysql_fetch_array($consulta_posicion);
$plaqueta=$fila_posicion['rack']."-".$fila_posicion['seccion']."-".$fila_posicion['nivel']."-".$fila_posicion['lado'];
if ($fila_posicion['rack'] < "J")
	$mapa_destino="ubicacion_mapa.php";
else
	$mapa_destino="ubicacion_mapa2.php";
//****************************************************************

$sql="UPDATE posicion_carga SET id_posicion ='$id_posicion_nueva', fondo = '' WHERE id='$id_celda'";
mysql_query($sql,$conexion) or die (exit('Error al liberar posicion Antigua '.mysql_error()));

//Carga los datos de la ubicación que se reemplazaran
$sql_posiscion="SELECT * FROM posicion_carga WHERE id='$id_celda'";
$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit('Error al cargar datos de la celda '.mysql_error()));
$fila_posicion=mysql_fetch_array($consulta_posicion);
$piezas_almacenadas=$fila_posicion['piezas'];
$peso_almacenado=$fila_posicion['peso'];
$observaciones_almacenadas=$fila_posicion['observacion'];
//****************************************************************

//Almacenamiento del traking
$sql_trak="INSERT INTO tracking (id_guia,
								 fecha_creacion,
								 hora,
								 evento,
								 tipo_tracking,
								 id_usuario)
										value ('$id_guia',
											   '$fecha_creacion',
											   '$hora_registro',
											   'GUIA RE-UBICADA EN LA POSICION:$plaqueta CON:<br />
											   	PIEZAS:$piezas_almacenadas.<br />
												PESO:$peso_almacenado.<br />
												OBSERVACION:$observaciones_almacenadas.<br />',
											   '1',
											   '$id_usuario')";
mysql_query($sql_trak,$conexion) or die (mysql_error());

//Regresa a mover mas posiciones.
echo "
<script language=\"javascript\">
	alert(\"Ubicacion Reemplazada con $plaqueta\");
	document.location='$mapa_destino?id_guia=$id_guia'; 
</script>";

?>