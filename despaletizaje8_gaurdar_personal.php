<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
/*
	Valores $msg_exit
	valor1: error o exito
	valor2: si hay error, codigo del error sobre este script
	valor3: codigo de despacho
	valor4: mensaje
*/
$msg_exit = "0-0-0- ";
if(isset($_REQUEST["vuelo"]))
{	
	$id_vuelo=$_REQUEST["vuelo"];
	$despaletizaje_inicio=$_POST["despaletizaje_inicio"].":00";
	$despaletizaje_fin=$_POST["despaletizaje_fin"].":00";
	$despaletizaje_fecha_hora_doc=$_POST["despaletizaje_fecha_doc"]." ".$_POST["despaletizaje_hora_doc"];
	$despaletizaje_cantidad_pallets=$_POST["despaletizaje_cantidad_pallets"];
	if(isset($_POST["despaletizaje_coordinador_tae"]))$despaletizaje_coordinador_tae=strtoupper($_POST["despaletizaje_coordinador_tae"]);
	if(isset($_POST["despaletizaje_coordinador_seguridad"]))$despaletizaje_coordinador_seguridad=strtoupper($_POST["despaletizaje_coordinador_seguridad"]);
	if(isset($_POST["despaletizaje_observaciones"]))$despaletizaje_observaciones=strtoupper($_POST["despaletizaje_observaciones"]);
	if(isset($_POST["despaletizaje_auxiliar1"]))$despaletizaje_auxiliar1=strtoupper($_POST["despaletizaje_auxiliar1"]);
	if(isset($_POST["despaletizaje_auxiliar2"]))$despaletizaje_auxiliar2=strtoupper($_POST["despaletizaje_auxiliar2"]);
	if(isset($_POST["despaletizaje_auxiliar3"]))$despaletizaje_auxiliar3=strtoupper($_POST["despaletizaje_auxiliar3"]);
	if(isset($_POST["despaletizaje_operario1"]))$despaletizaje_operario1=strtoupper($_POST["despaletizaje_operario1"]);
	if(isset($_POST["despaletizaje_operario2"]))$despaletizaje_operario2=strtoupper($_POST["despaletizaje_operario2"]);
	if(isset($_POST["despaletizaje_operario3"]))$despaletizaje_operario3=strtoupper($_POST["despaletizaje_operario3"]);
	if(isset($_POST["despaletizaje_comercio_exterior"]))$despaletizaje_comercio_exterior=strtoupper($_POST["despaletizaje_comercio_exterior"]);
	if(isset($_POST["despaletizaje_elaboradopor"]))$despaletizaje_elaboradopor=strtoupper($_POST["despaletizaje_elaboradopor"]);
	if(isset($_POST["despaletizaje_elaboradopor_cargo"]))$despaletizaje_elaboradopor_cargo=strtoupper($_POST["despaletizaje_elaboradopor_cargo"]);
	
	//Ingresar los datos Generales de la remesa
	$sql2="UPDATE vuelo SET 
				despaletizaje_inicio='$despaletizaje_inicio',
				despaletizaje_fin='$despaletizaje_fin',
				despaletizaje_cantidad_pallets='$despaletizaje_cantidad_pallets',
				despaletizaje_coordinador_tae='$despaletizaje_coordinador_tae',
				despaletizaje_coordinador_seguridad='$despaletizaje_coordinador_seguridad',
				despaletizaje_observaciones='$despaletizaje_observaciones',
				despaletizaje_auxiliar1='$despaletizaje_auxiliar1',
				despaletizaje_auxiliar2='$despaletizaje_auxiliar2',
				despaletizaje_auxiliar3='$despaletizaje_auxiliar3',
				despaletizaje_operario1='$despaletizaje_operario1',
				despaletizaje_operario2='$despaletizaje_operario2',
				despaletizaje_operario3='$despaletizaje_operario3',
				despaletizaje_comercio_exterior='$despaletizaje_comercio_exterior',
				despaletizaje_elaboradopor='$despaletizaje_elaboradopor',
				despaletizaje_elaboradopor_cargo='$despaletizaje_elaboradopor_cargo',
				despaletizaje_fecha_hora_doc='$despaletizaje_fecha_hora_doc'
				WHERE id='$id_vuelo'";	
	mysql_query($sql2,$conexion) or die (exit("0-1-0-".mysql_error()));
	echo $msg_exit = "1-0-0- ";
}
else
	echo $msg_exit = "0-2-0- ";

?>