<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */     						
require("config/configuracion.php");
if(isset($_SESSION["id_usuario"]))
{
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");
	$usuario=$_SESSION["id_usuario"];
	$id_registro=isset($_GET['id_registro']) ? $_GET['id_registro'] : $_POST['id_registro'];	

	//consulta datos del funcionario
	$sql_aux2="SELECT cf.id_guia, f.nombre FROM courier_funcionarios_guia cf LEFT JOIN courier_funcionario f ON cf.id_funcionario = f.id  WHERE cf.id='$id_registro'";
	$consulta_aux2=mysql_query($sql_aux2,$conexion) or die (exit('Error 1'.mysql_error()));
	$fila_aux2=mysql_fetch_array($consulta_aux2);
	$nombre=$fila_aux2["nombre"];
	$id_guia=$fila_aux2["id_guia"];

	//Crea registro en el historial
	$registro_novedad ="Funcionario del Courier Eliminado: ".
						"<br>".
						"Nombre:".$nombre
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
	mysql_query($sql_trak,$conexion) or die (exit('Error 2'.mysql_error()));

	//Eliminamos cualquier usuario previamente asociado pues solo puede existir un funcionario para esta labor
	$sql="DELETE FROM courier_funcionarios_guia WHERE id='$id_registro'";
	mysql_query($sql,$conexion) or die (exit('Error 3'.mysql_error()));
	echo "Ok";
}
else
{
	echo "Error 0";
}
?>