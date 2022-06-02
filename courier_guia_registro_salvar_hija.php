<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
if(isset($_POST["id_guia"]))$master=$_POST["id_guia"];
if(isset($_POST["no_guia"]))$no_guia=strtoupper($_POST["no_guia"]);
if(isset($_POST["piezas"]))$piezas=$_POST["piezas"];
if(isset($_POST["peso"]))$peso=$_POST["peso"];
if(isset($_POST["courier_docAprehension"]))$courier_docAprehension=strtoupper($_POST["courier_docAprehension"]);
if(isset($_POST["observaciones"]))$observaciones=strtoupper($_POST["observaciones"]);
if(isset($_POST["modulo"]))$modulo=$_POST["modulo"];
if(isset($_POST["destino"]))$funcionarios=$_POST["destino"];
if(isset($_POST["id_tipo_actuacion_aduanera"]))$id_tipo_actuacion_aduanera=$_POST["id_tipo_actuacion_aduanera"];
if(isset($_POST["entidad"]))$entidad=$_POST["entidad"];


switch ($modulo) 
{
	case 1:
			$posicion=$_POST["id_posicion_aprehensiones"];
		break;
	
	case 2:
			$posicion=$_POST["id_posicion_valores"];
		break;
}

$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$usuario=$_SESSION["id_usuario"];

//1. Ingresar los datos Generales
$sql="INSERT INTO guia (id_tipo_guia,						  
						  master,
						  hija,
						  piezas,
						  peso,						  
						  courier_docAprehension,
						  observaciones,
						  id_tipo_actuacion_aduanera,
						  courier_id_posicion,
						  fecha_creacion,
						  hora,
						  id_tipo_bloqueo,
						  courier_id_entidad 
						  ) 
							VALUE ('6',	
									'$master',							  	
									'$no_guia',
									'$piezas',
									'$peso',									
									'$courier_docAprehension',
									'$observaciones',
									'$id_tipo_actuacion_aduanera',
									'$posicion',
									'$fecha',
									'$hora',
									'1',
									'$entidad'
									)";
mysql_query($sql,$conexion) or die (exit('Error 1'.mysql_error()));
$id_guia = mysql_insert_id($conexion); //Obtiene el id de la ultima insercion


for ($i=0;$i<count($funcionarios);$i++) 
{ 
	//1. Ingresar los datos de los funcionarios seleccionados en el select multiple
	$id_funcionario=$funcionarios[$i];
	$sql="INSERT INTO courier_funcionarios_guia_hija (id_guia,id_funcionario) VALUE('$id_guia','$id_funcionario')";
	mysql_query($sql,$conexion) or die (exit('Error 2'.mysql_error()));
} 
		
//2. Ingresar los datos del Tracking de la guia
if($observaciones !="")
$observaciones = "<br>Observaciones: ".$observaciones;

$sql2="INSERT INTO tracking (id_guia,
				  evento,
				  fecha_creacion,
				  hora,
				  tipo_tracking,
				  id_usuario) 
					VALUE ('$id_guia',
						  	'Guia creada $observaciones',
							'$fecha',
							'$hora',
							'1',
							'$usuario')";
mysql_query($sql2,$conexion) or die (exit('Error 3'.mysql_error()));
//REENVIO DESPUES DE GUARDADO
echo $id_guia;
?>
