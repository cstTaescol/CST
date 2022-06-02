<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
//Datos de Sesion y seguimiento
$id_usuario=$_SESSION['id_usuario'];
$fecha_creacion=date("Y").date("m").date("d");
$hora_registro=date("H:i:s");
$id_tipo_bloqueo=1; 

/*Respuesta emitida
1-1-1

Valor 1
Codigo de Error: 0= Sin error

Valor 2
Dato Adicional

Valor 3
Mensaje de Retorno 
*/
require("config/configuracion.php");

//Evaluacion de recolección de datos
if(isset($_REQUEST["volumen"]))
{
	$volumen=$_REQUEST["volumen"];	
}
else{
	echo "1-1-Error al Obtener el volumen";
	die();	
}

if(isset($_REQUEST["id_guia"]))
{
	$id_guia=$_REQUEST["id_guia"];
}
else{
	echo "1-2-Error al Obtener el Id de la Guia";
	die();	
}

//Evaluación de reguistros errados
if($volumen == '.' || $volumen == '0' || $volumen == '0.' || $volumen == '0.0' || $volumen == '')
{	
	echo "1-3-Error con los datos Enviados";
	die();
}
else
{
	//1. Actualizacion del registro
	$sql="UPDATE guia SET volumen_inconsistencia='$volumen' WHERE id='$id_guia'"; 
	mysql_query($sql,$conexion) or die("1-4-".mysql_error());

	//2. Almacenamiento del traking
	$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('$id_guia','$fecha_creacion','$hora_registro','CORRECCION DE GUIA POR NUEVO VOLUMEN:$volumen','1','$id_usuario')";
	mysql_query($sql_trak,$conexion) or die("1-5-".mysql_error());

	//Salida Exitosa
	echo "0-0-Actualizacion Exitosa";

}
?>