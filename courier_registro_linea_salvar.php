<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
if(isset($_POST["id_linea"]))$courier_id_linea=$_POST["id_linea"];
if(isset($_POST["date"]))$date=$_POST["date"];
if(isset($_POST["hour"]))$hour=$_POST["hour"];
if(isset($_POST["minute"]))$minute=$_POST["minute"];
if(isset($_POST["id_guia"]))$id_guia=$_POST["id_guia"];
$courier_dato_inicio=$date . " " . $hour . ":" . $minute;	

//identificando el total de usuarios asignados a esta guia
$sql3="SELECT id FROM courier_funcionarios_guia WHERE id_guia='$id_guia'";
$consulta3=mysql_query ($sql3,$conexion) or die (exit('Error 1 '.mysql_error()));
$nfilas=mysql_num_rows($consulta3);
if ($nfilas == 0)
{
	echo "2|¡¡|0|¡¡|$id_guia|¡¡| No ha asignado funcionarios a esta guia";
	exit();
}
else
{
	if(($courier_id_linea != 0) || ($courier_id_linea != ""))
	{
		//identificando linea
		$sql3="SELECT nombre FROM courier_linea WHERE id='$courier_id_linea'";
		$consulta3=mysql_query ($sql3,$conexion) or die (exit('Error 2 '.mysql_error()));
		$fila3=mysql_fetch_array($consulta3);
		$linea=$fila3["nombre"];

		//Insertamos datos nuevos
		$sql_update="UPDATE 
						guia 
					SET 
						courier_id_linea='$courier_id_linea',
						courier_dato_inicio='$courier_dato_inicio'
					WHERE 
						id='$id_guia'";
		mysql_query($sql_update,$conexion) or die (exit('Error 3 '.mysql_error()));

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
								  	'Guia En L&iacute;nea: $linea <br> Datos de Inicio: $courier_dato_inicio',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql2,$conexion) or die (exit('Error 4 '.mysql_error()));
	}
	else
	{
		//Insertamos datos nuevos
		$sql_update="UPDATE 
						guia 
					SET 						
						courier_dato_inicio='$courier_dato_inicio'
					WHERE 
						id='$id_guia'";
		mysql_query($sql_update,$conexion) or die (exit('Error 5 '.mysql_error()));

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
								  	'Datos de Inicio: $courier_dato_inicio',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql2,$conexion) or die (exit('Error 6 '.mysql_error()));
	}

	//REENVIO DESPUES DE GUARDADO
	echo "1|¡¡|0|¡¡|$id_guia|¡¡|0";
}
?>
