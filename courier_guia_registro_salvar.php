<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
if(isset($_SESSION["id_usuario"]))
{
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");
	$usuario=$_SESSION["id_usuario"];
	if(isset($_POST["cod_1178"]))$cod_1178=$_POST["cod_1178"];
	if(isset($_POST["id_aerolinea"]))$id_aerolinea=$_POST["id_aerolinea"];
	if(isset($_POST["cod_consignatario"]))$cod_consignatario=$_POST["cod_consignatario"];
	if(isset($_POST["date"]))$courier_dato_llegada=$_POST["date"] . " " . $_POST["hour"] . ":" . $_POST["minute"];

	//evaluacion de las guias recibidas antes de guardar
	for($i=1; $i <= 100; $i++)
	{
		$no_guia=strtoupper($_POST['no_guia'.$i]);	
		if(isset($_POST["piezas".$i]))$piezas=$_POST["piezas".$i];
		if(isset($_POST["peso".$i]))$peso=$_POST["peso".$i];
		
		if($no_guia != "")
		{
			if(($piezas=="") || $peso=="")
			{
				echo "Error 1: No Ingresó los datos de Piezas y/o Peso correspondiente a la GUIA en el registro No. ".$i;
				exit();
			}
		}
	}

	//almacena las guias de la cuadricula luego de superar la evaluacion de datos completos
	for($i=1; $i <= 100; $i++)
	{
		$no_guia=strtoupper($_POST['no_guia'.$i]);
		$piezas=$_POST['piezas'.$i];
		$peso=$_POST['peso'.$i];
		if($no_guia != "")
		{
			if(($piezas != "") AND ($peso != ""))	
			{
				$observaciones=strtoupper($_POST['observaciones'.$i]);		
				//1. Ingresar los datos Generales
				$sql="INSERT INTO guia (id_tipo_guia,
										  id_aerolinea,
										  master,
										  piezas,
										  peso,
										  id_consignatario,
										  courier_1178,
										  courier_dato_llegada,
										  observaciones,
										  fecha_creacion,
										  hora,
										  id_tipo_bloqueo 
										  ) 
											VALUE ('5',
												  	'$id_aerolinea',
													'$no_guia',
													'$piezas',
													'$peso',
													'$cod_consignatario',
													'$cod_1178',
													'$courier_dato_llegada',
													'$observaciones',
													'$fecha',
													'$hora',
													'1'
													)";
				mysql_query($sql,$conexion) or die (exit('Error 2'.mysql_error()));
				$id_guia = mysql_insert_id($conexion); //Obtiene el id de la ultima insercion

					
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
				echo "ok";
			}
			else
			{
				echo "Error 4: No Ingreso los datos de Piezas y Peso correspondiente a la GUIA en la linea ".$i;
			}
		}
	}
}
else
{
	echo "Error 0";
}	
?>
