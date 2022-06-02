<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */     						
require("config/configuracion.php");
if(isset($_SESSION["id_usuario"]))
{
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");
	$usuario=$_SESSION["id_usuario"];
	$nombre=isset($_GET['nombre']) ? strtoupper($_GET['nombre']) : strtoupper($_POST['nombre']);
	$cc=isset($_GET['cc']) ? $_GET['cc'] : $_POST['cc'];
	$id_consignatario=isset($_GET['id_consignatario']) ? $_GET['id_consignatario'] : $_POST['id_consignatario'];
	$id_guia=isset($_GET['id_guia']) ? $_GET['id_guia'] : $_POST['id_guia'];

	//Insertamos datos nuevos
	$sql="INSERT INTO courier_funcionario (
								 				nombre,
								  				no_documento,
								  				id_entidad,
								  				id_consignatario
								  			  	) 
										VALUE (
												'$nombre',
											   	'$cc',
											   	'7',
											   	'$id_consignatario'
											   )";
	mysql_query($sql,$conexion) or die (exit('Error 1'.mysql_error()));
	$id_funcionario = mysql_insert_id($conexion); //Obtiene el id de la ultima insercion

	//Eliminamos cualquier usuario previamente asociado pues solo puede existir un funcionario para esta labor
	$sql="DELETE FROM courier_funcionarios_guia WHERE id_guia='$id_guia' AND tipo = 'C'";
	mysql_query($sql,$conexion) or die (exit('Error 2'.mysql_error()));

	//Insertamos datos nuevos
	$sql_trak="INSERT INTO courier_funcionarios_guia (
										 				id_funcionario,
										  				id_guia,
										  				tipo
										  			  ) 
												VALUE (
														'$id_funcionario',
													   	'$id_guia',
													   	'C'
													   )";
	mysql_query($sql_trak,$conexion) or die (exit('Error 3'.mysql_error()));	

	//Crea registro en el historial
	$registro_novedad ="Funcionario del Courier registrado por Seguridad: ".
						"<br>".
						"Nombre:".$nombre.
						"<br>".
						"CC:".$cc
						;
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
	mysql_query($sql_trak,$conexion) or die (exit('Error 4'.mysql_error()));
	echo "Ok";
}
else
{
	echo "Error 0";
}
?>