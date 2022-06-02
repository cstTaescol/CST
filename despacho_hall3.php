<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
echo "<p align='center'>Espere unos momentos mientras procesamos su informaci&oacute;n...<p>";
$cont=0; 
$id_guia=$_POST["id_guia"];
$observaciones=strtoupper($_POST["descripcion"]);
$id_usuario=$_SESSION["id_usuario"];
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");

//Verificamos que la guia parcial no este sin piezas y peso disponible para el despacho.
$sql="SELECT * FROM guia WHERE id='$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
include("config/evaluador_inconsistencias.php");

//Ingresar los datos Generales de la remesa
$sql="INSERT INTO hall (id_guia,
						  piezas,
						  peso,
						  volumen,
						  observaciones,
						  id_usuario,
						  fecha,
						  hora) 
							VALUE ('$id_guia',
								  	'$piezas',
									'$peso',
									'$volumen',
									'$observaciones',
									'$id_usuario',
									'$fecha',
									'$hora')";
mysql_query($sql,$conexion) or die (exit('Error 2'.mysql_error()));
$id_registro = mysql_insert_id($conexion); //Obtiene el id de la ultima inserci�n
$sql2="UPDATE guia SET 
			id_tipo_bloqueo='4',
			piezas_despacho='$piezas',
			peso_despacho='$peso',
			volumen_despacho='$volumen' 
			WHERE id='$id_guia'";	
mysql_query($sql2,$conexion) or die (exit('Error 3'.mysql_error()));
		
// Ingresar los datos del Tracking de la guia
$sql2="INSERT INTO tracking (id_guia,
						  evento,
						  fecha_creacion,
						  hora,
						  tipo_tracking,
						  id_usuario) 
							VALUE ('$id_guia',
								  	'GUIA DESPACHADA EN REGISTRO No $id_registro',
									'$fecha',
									'$hora',
									'1',
									'$id_usuario')";
mysql_query($sql2,$conexion) or die (exit('Error 4'.mysql_error()));

//Si despacho toda la Guia, elimina del mapa de la bodega, todas las posiciones de esa guia.
//Remocion de datos			
if ($fila['id_tipo_bloqueo']!=10)
{
	//Si despacho toda la Guia, elimina del mapa de la bodega, todas las posiciones de esa guia.
	//Ubica la Posicion en Bodega
	$sql_posiscion="SELECT id FROM posicion_carga WHERE id_guia='$id_guia'";
	$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit('Error '.mysql_error()));
	while($fila_posicion=mysql_fetch_array($consulta_posicion))
	{
		$id_posicion=$fila_posicion['id'];
		//Elimina de Posicion
		$sql="DELETE FROM posicion_carga WHERE id='$id_posicion'";
		mysql_query ($sql,$conexion) or die (exit('Error al liberar la posicion '.mysql_error()));
	}
}

// Registra evento en Correo de Salida
$addmensaje="DESPACHADA";
include("config/mail.php");
//***********************************
echo '<meta http-equiv="Refresh" content="0;url=despacho_hall4.php?id_registro='.$id_registro.'">';
?>
