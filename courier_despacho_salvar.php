<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
if(isset($_POST["observaciones"]))$observaciones_despacho=strtoupper($_POST["observaciones"]);
if(isset($_POST["date"]))$date=$_POST["date"];
if(isset($_POST["hour"]))$hour=$_POST["hour"];
if(isset($_POST["minute"]))$minute=$_POST["minute"];
if(isset($_POST["piezas"]))$piezas=$_POST["piezas"];
if(isset($_POST["peso"]))$peso=$_POST["peso"];
if(isset($_POST["id_guia"]))$id_guia=$_POST["id_guia"];
$courier_dato_finalizacion=$date . " " . $hour . ":" . $minute;	
//echo "2|¡¡|0|¡¡|$id_guia|¡¡| No ha asignado funcionarios a esta guia";

//1. Insertamos datos nuevos
$sql_update="UPDATE 
				guia 
			SET 
				observaciones_despacho ='$observaciones_despacho',
				courier_dato_fin='$courier_dato_finalizacion',
				id_tipo_bloqueo='4' 
			WHERE 
				id='$id_guia'";
mysql_query($sql_update,$conexion) or die (exit('Error 1 '.mysql_error()));

$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$usuario=$_SESSION["id_usuario"];

//2. Ingresar los datos del Tracking de la guia
$sql2="INSERT INTO tracking (id_guia,
				  evento,
				  fecha_creacion,
				  hora,
				  tipo_tracking,
				  id_usuario) 
					VALUE ('$id_guia',
						  	'Guia Finalizada: $courier_dato_finalizacion <br> Observaciones:$observaciones_despacho',
							'$fecha',
							'$hora',
							'1',
							'$usuario')";
mysql_query($sql2,$conexion) or die (exit('Error 2 '.mysql_error()));

//3. Crear Datos del Despacho
$sql2="INSERT INTO courier_despacho (id_guia,
									piezas,
									peso,
									id_usuario,
									fecha,
									hora) 
										VALUE ('$id_guia',
											  	'$piezas',
												'$peso',
												'$usuario',
												'$fecha',
												'$hora')";
mysql_query($sql2,$conexion) or die (exit('Error 3 '.mysql_error()));
$id_despacho = mysql_insert_id($conexion); //Obtiene el id de la ultima insercion
//REENVIO DESPUES DE GUARDADO
echo "1|¡¡|0|¡¡|$id_despacho|¡¡|0";
?>
