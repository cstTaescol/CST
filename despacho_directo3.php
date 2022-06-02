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
$cont=0; 
if(isset($_POST["id_guia"]))
{	
	$observacion="";
	$cont=0; 
	$id_guia=$_POST["id_guia"];
	$piezas_almacenar=$_POST["piezas"];
	$peso_almacenar=$_POST["peso"];
	$volumen_almacenar=$_POST["volumen"];
	if(isset($_POST["levante"]))$levante=strtoupper($_POST["levante"]);
	if(isset($_POST["declaracion"]))$declaracion=strtoupper($_POST["declaracion"]);
	if(isset($_POST["nombre"]))$nombre=strtoupper($_POST["nombre"]);
	if(isset($_POST["agencia"]))$agencia=strtoupper($_POST["agencia"]);
	if(isset($_POST["cedula"]))$cedula=strtoupper($_POST["cedula"]);
	if(isset($_POST["telefono"]))$telefono=strtoupper($_POST["telefono"]);
	if(isset($_POST["conductor"]))$conductor=strtoupper($_POST["conductor"]);
	if(isset($_POST["cedula_conductor"]))$cedula_conductor=strtoupper($_POST["cedula_conductor"]);
	if(isset($_POST["placa"]))$placa=strtoupper($_POST["placa"]);
	if(isset($_POST["observacion"]))$observacion=strtoupper($_POST["observacion"]);
	
	$id_usuario=$_SESSION["id_usuario"];
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");
	
	//Verificamos que la guia parcial no este sin piezas y peso disponible para el despacho.
	$sql="SELECT * FROM guia WHERE id='$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die (exit("0-1-0-".mysql_error()));
	$fila=mysql_fetch_array($consulta);
	include("config/evaluador_inconsistencias.php");
	$id_tipo_bloqueo=$fila['id_tipo_bloqueo'];
	if ($id_tipo_bloqueo == 10)
	{
		if ($fila["bloqueo_peso"] == "")
			$bloqueo_peso=0;
		else
			$bloqueo_peso=$fila["bloqueo_peso"];
	
		if ($fila["bloqueo_piezas"] == "")
			$bloqueo_piezas=0;
		else
			$bloqueo_piezas=$fila["bloqueo_piezas"];
	
		$peso_sinbloqueo=($peso-$fila['peso_despacho']) - $bloqueo_peso;
		$piezas_sinbloqueo=($piezas-$fila['piezas_despacho']) - $bloqueo_piezas;
		
		if ($peso_sinbloqueo == 0 || $piezas_sinbloqueo == 0)
		{
			echo "0-2-0- Esta GUIA se encuentra BLOQUEADA y no tienes PIEZAS PARCIALES desbloqueadas";
			exit();
		}	
	}
	$id_aerolinea=$fila['id_aerolinea'];
	// identificando de Aerolinea
	$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
	$consulta_add=mysql_query ($sql_add,$conexion) or die (exit('Error '.mysql_error()));
	$fila_add=mysql_fetch_array($consulta_add);
	$aerolinea=$fila_add["nombre"];
	//***************************
	
	// Evaluador de Futuro Estado de la Guia
	// Tomamos la cantidad  de piezas para saber si se despacha toda la guia o unicamente un parcial
	$piezas_despacho=$fila["piezas_despacho"];
	if ($piezas == ($piezas_despacho + $piezas_almacenar))
	{
		if ($id_tipo_bloqueo != 10) $id_tipo_bloqueo=4;
	}
	else
		{
			if ($id_tipo_bloqueo != 10) $id_tipo_bloqueo=3;
		}

	$posicion="";
	//Ubica la Posicion en Bodega
	$sql_posiscion="SELECT p.*,pc.* FROM posicion_carga pc LEFT JOIN posicion p ON pc.id_posicion=p.id WHERE pc.id_guia='$id_guia'";
	$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit('Error '.mysql_error()));
	while($fila_posicion=mysql_fetch_array($consulta_posicion))
	{
		$plaqueta=$fila_posicion['rack']."-".$fila_posicion['seccion']."-".$fila_posicion['nivel']."-".$fila_posicion['lado'];
		$posicion=$posicion." -> $plaqueta";
	}
	
	//Ingresar los datos Generales de la remesa
	$sql="INSERT INTO descargue_directo (id_guia,
							  piezas,
							  peso,
							  volumen,
							  levante,
							  declaracion,
							  nombre_entregado,
							  agencia,
							  cedula_entregado,
							  telefono_entregado,
							  nombre_conductor,
							  cedula_conductor,
							  placa,
							  observacion,
							  posicion_carga,
							  id_usuario,
							  fecha,
							  hora) 
								VALUE ('$id_guia',
										'$piezas_almacenar',
										'$peso_almacenar',
										'$volumen_almacenar',
										'$levante',
										'$declaracion',
										'$nombre',
										'$agencia',
										'$cedula',
										'$telefono',
										'$conductor',
										'$cedula_conductor',
										'$placa',
										'$observacion',
										'$posicion',
										'$id_usuario',
										'$fecha',
										'$hora')";
	mysql_query($sql,$conexion) or die (exit("0-3-0-".mysql_error()));
	$id_despacho = mysql_insert_id($conexion); //Obtiene el id de la ultima insercion
	$piezas_despacho=$fila['piezas_despacho'];
	$peso_despacho=$fila['peso_despacho'];
	$volumen_despacho=$fila['volumen_despacho'];
	
	$sql2="UPDATE guia SET 
				id_tipo_bloqueo='$id_tipo_bloqueo',
				piezas_despacho='$piezas_almacenar'+'$piezas_despacho',
				peso_despacho='$peso_almacenar'+'$peso_despacho',
				volumen_despacho='$volumen_almacenar'+'$volumen_despacho' 
				WHERE id='$id_guia'";	
	mysql_query($sql2,$conexion) or die (exit("0-4-0-".mysql_error()));
	
	if ( $observacion != "" )
		$observacion_registrada = "OBSERVACION DEL DESPACHO: ". $observacion;
	else
		$observacion_registrada = "";
			
	// Ingresar los datos del Tracking de la guia
	$sql2="INSERT INTO tracking (id_guia,
							  evento,
							  fecha_creacion,
							  hora,
							  tipo_tracking,
							  id_usuario) 
								VALUE ('$id_guia',
										'GUIA ENTREGADA AL CLIENTE.<br>
										DESPACHO DIRECTO No.$id_despacho - RECOGIDA POR: $nombre<br>							
										DESPACHO DE PIEZAS=$piezas_almacenar, PESO=$peso_almacenar, VOLUMEN=$volumen_almacenar<br> $observacion_registrada',
										'$fecha',
										'$hora',
										'1',
										'$id_usuario')";
	mysql_query($sql2,$conexion) or die (exit("0-5-0-".mysql_error()));
	
	//Si despacho toda la Guia, elimina del mapa de la bodega, todas las posiciones de esa guia.
	//Remocion de datos			
	if ($fila['id_tipo_bloqueo']!=10)
	{
		//Si despacho toda la Guia, elimina del mapa de la bodega, todas las posiciones de esa guia.
		//Ubica la Posicion en Bodega
		$sql_posiscion="SELECT id FROM posicion_carga WHERE id_guia='$id_guia'";
		$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit("0-6-0-".mysql_error()));
		while($fila_posicion=mysql_fetch_array($consulta_posicion))
		{
			$id_posicion=$fila_posicion['id'];
			//Elimina de Posicion
			$sql="DELETE FROM posicion_carga WHERE id='$id_posicion'";
			mysql_query ($sql,$conexion) or die (exit("0-7-0-".mysql_error()));
		}
	}
	
	// Registra evento en Correo de Salida
	$addmensaje="AEROLINEA:$aerolinea. ->DESPACHADA";
	include("config/mail.php");
	//***********************************
	
	echo $msg_exit = "1-0-$id_despacho- ";
}
else
	echo $msg_exit = "0-8-0- ";

?>