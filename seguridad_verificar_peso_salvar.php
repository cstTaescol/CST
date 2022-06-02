<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$id_guia=$_POST['id_guia'];
$verificado_piezas=$_POST['piezas'];
$verificado_peso=$_POST['peso'];
$verificado_observacion=strtoupper($_POST['observacion']);

if ($verificado_observacion != "")
	$verificado_observacion = "-".$verificado_observacion; 

//Carga datos de la Guia
$sql="SELECT observaciones FROM guia WHERE id='$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$observaciones=$fila["observaciones"];
if ($observaciones != "")
	$observaciones .= "<br><br>$verificado_observacion";
else
	$observaciones = $verificado_observacion;

// Alacenamiento de peso verificado
$sql="INSERT INTO peso_verificado (id_guia,piezas,peso) VALUE ('$id_guia','$verificado_piezas','$verificado_peso')";
mysql_query ($sql,$conexion) or die (exit('Error de insercion: '.mysql_error()));

// Alacenamiento de guia
$sql="UPDATE guia SET observaciones='$observaciones' WHERE id='$id_guia'";
mysql_query ($sql,$conexion) or die (exit('Error de insercion: '.mysql_error()));

// Almacenamiento del traking
$id_usuario=$_SESSION['id_usuario'];
$fecha_creacion=date("Y").date("m").date("d");
$hora_registro=date("H:i:s");
$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('".$id_guia."','".$fecha_creacion."','".$hora_registro."','VERIFICACION DE PESO REGISTRADO CON:<br> PIEZAS:".$verificado_piezas."<br>PESO:".$verificado_peso."<br>OBSERVACION:".$verificado_observacion."','1','".$id_usuario."')";
mysql_query($sql_trak,$conexion) or die (exit('Error de insercion: '.mysql_error()));
echo "Registro Exitoso";
?>