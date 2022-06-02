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
if(isset($_POST["id_guia"]))
{	
	$cont=0; 
	$id_guia=$_POST["id_guia"];
	//consulta a la guia
	$sql="SELECT * FROM guia WHERE id='$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die (exit("0-1-0- "));
	$fila=mysql_fetch_array($consulta);
	
	$piezas_capturado=$_POST["piezas"];
	$peso_capturado=$_POST["peso"];
	$volumen_capturado=$_POST["volumen"];
	
	$piezas_despacho=$fila["piezas_despacho"] + $piezas_capturado;
	$peso_despacho=$fila["peso_despacho"] + $peso_capturado;
	$volumen_despacho=$fila["volumen_despacho"] + $volumen_capturado;
	
	include("config/evaluador_inconsistencias.php");
	$peso -= $peso_despacho;
	// Cuando es bloqueo parcial conservamos el tipo de bloqueo
	if ($fila['id_tipo_bloqueo']==10) 
	{
		$id_tipo_bloqueo=10;	
	}
	else
		{
			// Cuando es el bloqueo no es parcual, verificamos si se despacha toda la guia
			if ($peso == 0)
			{
					$id_tipo_bloqueo=4;
			}		
			else
				$id_tipo_bloqueo=$fila['id_tipo_bloqueo'];
		}
	// identificando de Aerolinea
	$id_aerolinea=$fila["id_aerolinea"];
	$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
	$consulta_add=mysql_query ($sql_add,$conexion) or die (exit('Error '.mysql_error()));
	$fila_add=mysql_fetch_array($consulta_add);
	$aerolinea=$fila_add["nombre"];
	//***************************
		
	
	$fecha_captura=$_POST["fecha"];
	$hora_captura=$_POST["hora"];
	$destinatario=strtoupper($_POST["destinatario"]);
	$observaciones=strtoupper($_POST["observaciones"]);
	$id_usuario=$_SESSION["id_usuario"];
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");
	//1. Ingresar los datos Generales de la remesa
	$sql="INSERT INTO trasbordo (id_guia,
							  destinatario,
							  id_usuario,
							  fecha,
							  hora,
							  observaciones,
							  piezas,
							  peso,
							  volumen) 
								VALUE ('$id_guia',
										'$destinatario',
										'$id_usuario',
										'$fecha_captura',
										'$hora_captura',
										'$observaciones',
										'$piezas_capturado',
										'$peso_capturado',
										'$volumen_capturado')";
	mysql_query($sql,$conexion) or die (exit("0-2-0- "));
	$id_despacho = mysql_insert_id($conexion); //Obtiene el id de la ultima inserción
	
	//2. Actualizamos los datos de la Guia
	$sql2="UPDATE guia SET 
				id_tipo_bloqueo='$id_tipo_bloqueo',
				piezas_despacho='$piezas_despacho',
				peso_despacho='$peso_despacho',
				volumen_despacho='$volumen_despacho' 
				WHERE id='$id_guia'";	
	mysql_query($sql2,$conexion) or die (exit("0-3-0- "));
			
	//3. Ingresar los datos del Tracking de la guia
	$sql2="INSERT INTO tracking (id_guia,
							  evento,
							  fecha_creacion,
							  hora,
							  tipo_tracking,
							  id_usuario) 
								VALUE ('$id_guia',
										'GUIA ENTREGADA POR TRASBORDO No. $id_despacho EN: $destinatario <br>
										DESPACHO DE PIEZAS=$piezas_capturado, PESO=$peso_capturado,  VOLUMEN=$volumen_capturado',
										'$fecha',
										'$hora',
										'1',
										'$id_usuario')";
	mysql_query($sql2,$conexion) or die (exit("0-4-0- "));
	
	//4. Si despacho toda la Guia, elimina del mapa de la bodega, todas las posiciones de esa guia.
	//Si despacho toda la Guia, elimina del mapa de la bodega, todas las posiciones de esa guia.
	//Ubica la Posicion en Bodega
	
	$sql_posiscion="SELECT id FROM posicion_carga WHERE id_guia='$id_guia'";
	$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit("0-5-0- "));
	while($fila_posicion=mysql_fetch_array($consulta_posicion))
	{
		$id_posicion=$fila_posicion['id'];
		//Elimina de Posicion
		$sql="DELETE FROM posicion_carga WHERE id='$id_posicion'";
		mysql_query ($sql,$conexion) or die (exit("0-6-0- "));
	}
	
	//5. Registra evento en Correo de Salida
	$addmensaje="AEROLINEA: $aerolinea ->DESPACHADA";
	include("config/mail.php");
	//***********************************
	echo $msg_exit = "1-0-$id_despacho- ";
}
else
	echo $msg_exit = "0-7-0- ";

?>