<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */     						
require("config/configuracion.php");
if(isset($_SESSION["id_usuario"]))
{	
	$fecha=date("Y-m-d");
	$date_creacion= date("Y-m-d H:i:s");
	$hora=date("H:i:s");
	$usuario=$_SESSION["id_usuario"];
	$metadata="";	
	$nfilas=isset($_GET['nfilas']) ? strtoupper($_GET['nfilas']) : strtoupper($_POST['nfilas']);
	$id_courier=isset($_GET['id_courier']) ? $_GET['id_courier'] : $_POST['id_courier'];
	$id_linea=isset($_GET['id_linea']) ? $_GET['id_linea'] : $_POST['id_linea'];


	$contSeleccion=0;
	
	//Evaluacion de que existe alguna guia seleccionada
	for ($i=1; $i<=$nfilas; $i++) 
	{ 
		$nombrechek="ck".$i;
		if (isset($_POST["$nombrechek"]))
		{
			$contSeleccion++;					
		}
	}
	if ($contSeleccion == 0)
	{
		echo "Error 1: No ha seleccionado ninguna GUIA";
		exit();
	}

	//consulta la cantidad de Turnos ya existen en esa misma fecha
	$sql_aux="SELECT id FROM courier_turno WHERE date_creacion LIKE '%$fecha%'";
	$consulta_aux=mysql_query($sql_aux,$conexion) or die ("Error 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$catidadTurnos=mysql_num_rows($consulta_aux) + 1;
	$no_turno="D".date("d")."-".$catidadTurnos;


	//Consulta Auxiliar
	$sql2="SELECT nombre FROM courier_linea WHERE id ='$id_linea'";
	$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 2'.mysql_error()));
	$fila2 = mysql_fetch_array($consulta2);
	$linea=$fila2['nombre'];	

	//Insertamos datos nuevos
	$sql="INSERT INTO courier_turno (
					 				no_turno,
					  				id_courier,
					  				date_creacion,
					  				id_linea,
					  				id_funcionario_creacion
					  			  	) 
							VALUE (
									'$no_turno',
								   	'$id_courier',
								   	'$date_creacion',
								   	'$id_linea',
								   	'$usuario'
								   )";
	mysql_query($sql,$conexion) or die ("Error 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$id_registro=mysql_insert_id($conexion);
 		
	for ($i=1; $i<=$nfilas; $i++) 
	{ 
		$nombrechek="ck".$i;
		if (isset($_POST["$nombrechek"]))
		{
			$id_guia=$_POST["$nombrechek"];

			//Insertamos listado de Guias asociadas a este turno
			$sql2="INSERT INTO courier_turno_guia (
							 				id_turno,
							  				id_guia
							  			  	) 
									VALUE (
											'$id_registro',
										   	'$id_guia'
										   )";
			mysql_query($sql2,$conexion) or die ("Error 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");			



			//Actualizacion de los datos de la guia
			$sql2="UPDATE guia SET courier_id_linea='$id_linea' WHERE id = '$id_guia'";
			mysql_query($sql2,$conexion) or die (exit('Error 5'.mysql_error()));
			  

			//Crea registro en el historial
			$registro_novedad ="Turno Asignado: ".
								"<br>".
								$no_turno.
								"<br>L&iacute;nea<br>".
								$linea;
			$sql_trak="INSERT INTO tracking (id_guia,
											 fecha_creacion,
											 hora,
											 evento,
											 tipo_tracking,
											 id_usuario) 
												VALUE ('$id_guia',
													   '$fecha',
													   '$hora',
													   '$registro_novedad',
													   '1',
													   '$id_usuario')";
			mysql_query($sql_trak,$conexion) or die (exit('Error 6'.mysql_error()));
		}
	}
	echo "Turno Creado No:<h2>".$no_turno."</h2>";
}
else
{
	echo "Error 0";
}
?>