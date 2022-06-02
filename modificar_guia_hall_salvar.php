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
$hija=strtoupper($_REQUEST['guia']);
if(isset($_REQUEST['volumen']))
{
	$volumen=$_REQUEST['volumen'];
}
else
{
	$volumen=$peso;
}

if ($volumen == 0)
	$volumen=$_REQUEST['volumen'];


// Alacenamiento de guia
$sql_guia="UPDATE guia SET piezas='$piezas',peso='$peso',volumen='$volumen', hija = '$hija' WHERE id='$id_guia'";
mysql_query ($sql_guia,$conexion) or die (exit('Error de insercion: '.mysql_error()));

// Almacenamiento del traking
$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('".$id_guia."','".$fecha."','".$hora."','GUIA MODIFICADA CON LA SIGIENTE INFORMACION: No GUIA:$hija, PIEZAS:$piezas, PESO:$peso, VOLUMEN:$volumen','1','".$id_usuario."')";
mysql_query($sql_trak,$conexion) or die (exit('Error de insercion: '.mysql_error()));
echo "Registro Exitoso";
?>