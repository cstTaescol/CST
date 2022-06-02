<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$errores=false;
$fecha_creacion=date("Y").date("m").date("d");
$hora_registro=date("H:i:s");
$id_usuario=$_SESSION['id_usuario'];

if (isset($_REQUEST["celda"]))
	$id_posicion=$_REQUEST["celda"];
else
	$errores=true;

if (isset($_REQUEST["msg"]))
	$plaqueta=$_REQUEST["msg"];
else
	$errores=true;

if (isset($_REQUEST["id_guia"]))
	$id_guia=$_REQUEST["id_guia"];
else
	$errores=true;

if ($errores==true)
{
	echo "
		<script language=\"javascript\">
			alert(\"Error al comunicarse con el Servidor, Intente nuevamente\");
			document.location='ubicacion_mapa.php';
		</script>";
	exit();
}

echo '<p align="center"><img src="imagenes/cargando.gif" width="20" height="21" /><br>Procesando</p>';
//Elimina de Posicion
$sql="DELETE FROM posicion_carga WHERE id='$id_posicion'";
mysql_query ($sql,$conexion) or die (exit('Error al liberar la posicion '.mysql_error()));
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
											   'GUIA RETIRADA DE LA POSICION $plaqueta EN BODEGA',
											   '1',
											   '$id_usuario')";
mysql_query($sql_trak,$conexion) or die (exit('Error al actualizar el historial'.mysql_error()));

//Identifica el mapa de la posicion que liberó de acuerdo al numero de celda que tenía seleccionada.
if ($id_posicion < 1520)
	$mapa_destino="ubicacion_mapa.php";
else
	$mapa_destino="ubicacion_mapa2.php";
//******************************

echo "
<script language=\"javascript\">
	alert(\"Ubicacion Liberada de $plaqueta\");
	document.location='$mapa_destino';
</script>";
?>
