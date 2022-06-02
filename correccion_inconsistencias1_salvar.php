<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$id_usuario=$_SESSION['id_usuario'];
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");

$id_guia=$_REQUEST['id_guia'];
$piezas=$_REQUEST['piezas'];
$peso=$_REQUEST['peso'];
$volumen=isset($_REQUEST['volumen']) ? $_REQUEST['volumen'] : $peso;
$piezas_inconsistencia=$_REQUEST['piezas_inconsistencia'];
$peso_inconsistencia=$_REQUEST['peso_inconsistencia'];

if (($piezas) && ($peso) && ($volumen) && ($piezas_inconsistencia) && ($peso_inconsistencia))
{
	$sql_guia="UPDATE guia SET piezas_inconsistencia = '$piezas_inconsistencia',".
							  "peso_inconsistencia = '$peso_inconsistencia',".
							  "volumen_inconsistencia = '$volumen',".
							  "piezas = '$piezas',".
							  "peso = '$peso',".
							  "volumen = '$volumen',".
							  "piezas_faltantes = NULL,".
							  "peso_faltante = NULL ".
							  "WHERE id='$id_guia'";
	$sql_inconsistencias="DELETE FROM inconsistencias WHERE id_guia = '$id_guia'";
	$msg_track="GUIA CON VALORES ACTUALIZADOS:<br>
				PIEZAS=$piezas PESO:$peso VOLUMEN=$volumen<br>
				PIEZAS DESPALETIZADAS=$piezas_inconsistencia PESO DESPALETIZADO:$peso_inconsistencia<br>
				";
	// Alacenamiento de guia
	mysql_query ($sql_guia,$conexion) or die (exit('Error de insercion: '.mysql_error()));

	// Alacenamiento de inconsistencias
	mysql_query ($sql_inconsistencias,$conexion) or die (exit('Error de insercion: '.mysql_error()));


	// Almacenamiento del traking
	$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('".$id_guia."','".$fecha."','".$hora."','GUIA MODIFICADA CON LA SIGIENTE INFORMACION: $msg_track','1','".$id_usuario."')";
	mysql_query($sql_trak,$conexion) or die (exit('Error de insercion: '.mysql_error()));
	echo "Registro Exitoso";				
}
else{
	echo "Error: No se recibieron todos los datos";
}
?>