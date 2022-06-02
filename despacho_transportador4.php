<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$exclusivo="N";
$refrigerado="N";
$observaciones="";
$cont=0; 
$cantidadguias=$_POST["cantidadguias"];
if(isset($_POST["observaciones"]))$observaciones=strtoupper($_POST["observaciones"]);
if(isset($_POST["refrigerado"]))$refrigerado=$_POST["refrigerado"];
if(isset($_POST["exclusivo"]))$exclusivo=$_POST["exclusivo"];
$transportador=$_SESSION["transportador"];
$vehiculo=$_SESSION["vehiculo"];
$conductor=$_SESSION["conductor"];
$deposito=$_SESSION["deposito"];
$planilla_envio=$_SESSION["planilla_envio"];
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$usuario=$_SESSION["id_usuario"];
//1. Ingresar los datos Generales de la remesa
$sql="INSERT INTO remesa (fecha,
						  hora,
						  observacion,
						  id_transportador,
						  id_vehiculo,
						  id_conductor,
						  id_deposito,
						  refrigerado,
						  exclusivo,
						  id_usuario,
						  planilla_envio) 
							VALUE ('$fecha',
								  	'$hora',
									'$observaciones',
									'$transportador',
									'$vehiculo',
									'$conductor',
									'$deposito',
									'$refrigerado',
									'$exclusivo',
									'$usuario',
									'$planilla_envio')";
mysql_query($sql,$conexion) or die (exit('Error '.mysql_error()));
$id_remesa = mysql_insert_id($conexion); //Obtiene el id de la ultima inserci�n

for($i=1; $i <= $cantidadguias; $i++)
{
	$guia="guia".$i;
	if (isset($_POST["$guia"]))
	{
		$cont++;
		$piezas="piezas".$i;
		$peso="peso".$i;
		$volumen="volumen".$i;
		$completa="completa".$i;
		$id_guia=$_POST["$guia"];
		$piezas=$_POST["$piezas"];
		$peso=$_POST["$peso"];
		$volumen=$_POST["$volumen"];
		$observaciones_obtenidas=$_POST["observacionesdespacho$i"];
		$completa=$_POST["$completa"];
		//2. Ingresar los datos de las Guias cargadas en la remesa
		$sql2="INSERT INTO carga_remesa (id_remesa,
						  id_guia,
						  piezas,
						  peso,
						  volumen) 
							VALUE ('$id_remesa',
								  	'$id_guia',
									'$piezas',
									'$peso',
									'$volumen')";
		mysql_query($sql2,$conexion) or die (mysql_error());
		
		//3.  Actualiza datos y estado de la Guia
		//*************Carga datos de guia
		$sql3="SELECT * FROM guia WHERE id='$id_guia'";
		$consulta3=mysql_query ($sql3,$conexion) or die (exit('Error '.mysql_error()));
		$fila3=mysql_fetch_array($consulta3);
		$piezas_despacho=$fila3['piezas_despacho'];
		$peso_despacho=$fila3['peso_despacho'];
		$volumen_despacho=$fila3['volumen_despacho'];
		$observaciones_despacho=$fila3['observaciones_despacho'];
		$totalpiezas=$piezas+$piezas_despacho;
		$totalpeso=$peso+$peso_despacho;
		$totalvolumen=$volumen+$volumen_despacho;
		$id_tipo_bloqueo=$fila3['id_tipo_bloqueo'];
		$id_aerolinea=$fila3['id_aerolinea'];
		
		// identificando de Aerolinea
		$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die (exit('Error '.mysql_error()));
		$fila_add=mysql_fetch_array($consulta_add);
		$aerolinea=$fila_add["nombre"];
		//***************************
				
		if ($completa=="S")
		{	
			if ($id_tipo_bloqueo == 10)
			{
				$id_tipo_bloqueo=10;
			}
			else
				{
					$id_tipo_bloqueo=4;
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
			$parcial="";
		}
			else
			{
				if ($id_tipo_bloqueo == 10)
				{
					$id_tipo_bloqueo=10;
				}
				else
					{
						$id_tipo_bloqueo=3;
					}
				$parcial="PARCIALMENTE CON PIEZAS:$piezas PESO:$peso, VOLUMEN:$volumen";
			}
		$observaciones_despacho=$observaciones_despacho."-".$observaciones_obtenidas;
		
		$sql2="UPDATE guia SET 
					id_tipo_bloqueo='$id_tipo_bloqueo',
					piezas_despacho='$totalpiezas',
					peso_despacho='$totalpeso',
					volumen_despacho='$totalvolumen',
					observaciones_despacho='$observaciones_despacho'
					WHERE id='$id_guia'";	
		mysql_query($sql2,$conexion) or die (exit('Error '.mysql_error()));
		
		//4. Ingresar los datos del Tracking de la guia
		$sql2="INSERT INTO tracking (id_guia,
						  evento,
						  fecha_creacion,
						  hora,
						  tipo_tracking,
						  id_usuario) 
							VALUE ('$id_guia',
								  	'GUIA DESPACHADA HACIA EL DEPOSITO EN REMESA No. $id_remesa<br> $parcial $observaciones_obtenidas',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql2,$conexion) or die (exit('Error '.mysql_error()));
		
		//5 Registra evento en Correo de Salida
		$addmensaje="AEROLINEA:$aerolinea ->DESPACHADA";
		include("config/mail.php");
		//***********************************
	}
}
//REENVIO DESPUES DE GUARDADO
echo $id_remesa;
?>
