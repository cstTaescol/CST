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

function restablecimiento($id_despacho)
{
	global $conexion;
	$sql="SELECT * FROM carga_correo WHERE id_correo='$id_despacho'";
	$consulta=mysql_query ($sql,$conexion) or die (exit("0-0-0-1"));
	while($fila=mysql_fetch_array($consulta))
	{
		$id_guia=$fila['id_guia'];
		$retirado_piezas=$fila['piezas'];
		$retirado_peso=$fila['peso'];
		$retirado_volumen=$fila['volumen'];
		$sql_guia="SELECT piezas_despacho, peso_despacho, volumen_despacho FROM guia WHERE id='$id_guia'";
		$consulta_guia=mysql_query ($sql_guia,$conexion) or die (exit("0-0-0-2"));
		$fila_guia=mysql_fetch_array ($consulta_guia);
		$restablecido_piezas=$fila_guia['piezas_despacho'] - $retirado_piezas;
		$restablecido_peso=$fila_guia['peso_despacho'] - $retirado_peso;
		$restablecido_volumen=$fila_guia['volumen_despacho'] - $retirado_volumen;
		//restablece la guia
		$sql="UPDATE guia SET piezas_despacho=$restablecido_piezas, peso_despacho=$restablecido_peso, volumen_despacho=$restablecido_volumen, id_tipo_bloqueo=3 WHERE id='$id_guia'";
		mysql_query ($sql,$conexion) or die (exit("0-0-0-3"));
	}
	//Elimina el despacho
	$sql="DELETE FROM carga_correo WHERE id_correo='$id_despacho'";
	mysql_query ($sql,$conexion) or die (exit("0-0-0-4"));
	$sql="DELETE FROM correo WHERE id='$id_despacho'";
	mysql_query ($sql,$conexion) or die (exit("0-0-0-5"));
}

$msg_exit = "0-0-0- ";
$cont=0;
$error="";
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$usuario=$_SESSION["id_usuario"];
if(isset($_POST["cantidadguias"]))
{	
	$cantidadguias=$_POST["cantidadguias"];
	$id_aerolinea=$_POST["id_aerolinea"];
	$entrega=$_POST["entrega"];
	$url_retorno="despacho_correo1.php";
	
	//Define el  mensaje en el traking segun el tipo de entrega de la guia
	if ($entrega == "D")
		$destino_entrega="ENTREGA DIRECTA EN BODEGA";
	else
		$destino_entrega="ENTREGA EN BODEGA1 DIAN";
	
	if (isset($_POST["auxiliaram"]))
		$auxiliaram=strtoupper($_POST["auxiliaram"]);
		else
			$auxiliaram="";
			
	if (isset($_POST["operarioam"]))
		$operarioam=strtoupper($_POST["operarioam"]);
		else
			$operarioam="";
			
	if (isset($_POST["auxiliarpm"]))
		$auxiliarpm=strtoupper($_POST["auxiliarpm"]);
		else
			$auxiliarpm="";
	
	if (isset($_POST["operariopm"]))
		$operariopm=strtoupper($_POST["operariopm"]);
		else
			$operariopm="";
			
	if (isset($_POST["tpallets"]))
		$tpallets=$_POST["tpallets"];
		else
			$tpallets=0;
			
	if (isset($_POST["tmallas"]))
		$tmallas=$_POST["tmallas"];
		else
			$tmallas=0;
	
	if (isset($_POST["tcorreas"]))
		$tcorreas=$_POST["tcorreas"];
		else
			$tcorreas=0;
	
	if (isset($_POST["tdollys"]))
		$tdollys=$_POST["tdollys"];
		else
			$tdollys=0;
	
	if (isset($_POST["coordinador"]))
		$coordinador=strtoupper($_POST["coordinador"]);
		else
			$coordinador="";
			
	if (isset($_POST["jefe"]))
		$jefe=strtoupper($_POST["jefe"]);
		else
			$jefe="";
	
	if (isset($_POST["supervisor"]))
		$supervisor=strtoupper($_POST["supervisor"]);
			else
		$supervisor="";
		
	// identificando de Aerolinea
	$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
	$consulta_add=mysql_query ($sql_add,$conexion) or die (exit('Error '.mysql_error()));
	$fila_add=mysql_fetch_array($consulta_add);
	$aerolinea=$fila_add["nombre"];
	//***************************		
		
	// 1. Ingresar datos a la tabla de Correo
	$sql="INSERT INTO correo (id_aerolinea,
				  aux_entrega_am,
				  oper_entrega_am,
				  aux_entrega_pm,
				  oper_entrega_pm,
				  tipo_entrega,
				  tpallets,
				  tmallas,
				  tcorreas,
				  tdollys,
				  coordinador,
				  jefe,
				  supervisor,
				  id_usuario,
				  hora,
				  fecha) 
					VALUE ('$id_aerolinea',
							'$auxiliaram',
							'$operarioam',
							'$auxiliarpm',
							'$operariopm',
							'$entrega',
							'$tpallets',
							'$tmallas',
							'$tcorreas',
							'$tdollys',
							'$coordinador',
							'$jefe',
							'$supervisor',
							'$usuario',
							'$hora',
							'$fecha')";
	mysql_query($sql,$conexion) or die (exit("0-1-0- "));
	$id_despacho = mysql_insert_id($conexion); //Obtiene el id de la ultima inserción

	for ($i=1; $i<=$cantidadguias; $i++)
	{
		if (isset($_POST["accion$i"]))
			{
				$cont++;
				$id_guia=$_POST["accion$i"];			
				if ($_POST["piezas$i"]== "" || $_POST["peso$i"]== "" || $_POST["piezas$i"]==0 || $_POST["peso$i"]== 0)
				{
					restablecimiento($id_despacho);
					echo $msg_exit = "0-2-0- El servidor no registro PIEZAS o PESO para despachar en una de las guias";
					exit();
				}
				//Datos de la guia para descargar las piezas totales como despachadas****************************************
				$sql="SELECT * FROM guia WHERE id='$id_guia'";
				$consulta=mysql_query ($sql,$conexion) or die (exit("0-3-0- "));
				$fila=mysql_fetch_array($consulta);
				include("config/evaluador_inconsistencias.php");
				$id_tipo_bloqueo=$fila['id_tipo_bloqueo'];
				
	
				//Captura de datos a despachar
				$recibido_piezas =  $_POST["piezas$i"];
				$recibido_peso =  $_POST["peso$i"];
				
				$piezas_despacho=$fila['piezas_despacho'] + $recibido_piezas;
				$peso_despacho=$fila['peso_despacho'] + $recibido_peso;
	
				//Verificacion de despacho NO Superrior al existente
				if ($piezas_despacho > $piezas || $peso_despacho > $peso)
				{
					restablecimiento($id_despacho);
					echo $msg_exit = "0-4-0- Intenta despachar mas contenido del que tiene disponible en una de las guias";
					exit();
				}
				
				//************************
				//Verificacion de despacho TOTAL o PARCIAL para TODOS los items.
				if (($piezas-$piezas_despacho) == 0)
				{
					if (($peso-$peso_despacho) != 0)
					{
						restablecimiento($id_despacho);
						echo $msg_exit = "0-5-0- Intenta despachar todo el contenido, solo en uno de los items de la guia";
						exit();
					}
					else
					{
						if ($fila['id_tipo_bloqueo'] == 10)
						{
							$id_tipo_bloqueo=10; //
						}
						else
						{
							$id_tipo_bloqueo=4; //cuando se despacha toda la guia por que piezas y peso quedaro en 0
							//Si despacho toda la Guia, elimina del mapa de la bodega, todas las posiciones de esa guia.
							//Ubica la Posicion en Bodega
							$sql_posiscion="SELECT id FROM posicion_carga WHERE id_guia='$id_guia'";
							$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit("0-6-0- "));
							while($fila_posicion=mysql_fetch_array($consulta_posicion))
							{
								$id_posicion=$fila_posicion['id'];
								//Elimina de Posicion
								$sql="DELETE FROM posicion_carga WHERE id='$id_posicion'";
								mysql_query ($sql,$conexion) or die (exit("0-7-0- "));
							}
						}
					}
				}
				
				if (($peso-$peso_despacho) == 0)
				{
					if (($piezas-$piezas_despacho) != 0)
					{
						restablecimiento($id_despacho);
						echo $msg_exit = "0-8-0- Intenta despachar todo el contenido, solo en uno de los items de la guia";
						exit();
					}
					else
					{
						if ($fila['id_tipo_bloqueo'] == 10)
						{
							$id_tipo_bloqueo=10; //
						}
						else
						{
							$id_tipo_bloqueo=4; //cuando se despacha toda la guia por que piezas y peso quedaro en 0
							//Si despacho toda la Guia, elimina del mapa de la bodega, todas las posiciones de esa guia.
							//Ubica la Posicion en Bodega
							$sql_posiscion="SELECT id FROM posicion_carga WHERE id_guia='$id_guia'";
							$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit("0-9-0- "));
							while($fila_posicion=mysql_fetch_array($consulta_posicion))
							{
								$id_posicion=$fila_posicion['id'];
								//Elimina de Posicion
								$sql="DELETE FROM posicion_carga WHERE id='$id_posicion'";
								mysql_query ($sql,$conexion) or die (exit("0-9-0- Error al liberar la posicion"));
							}
						}
					}				
				}
				
				//Calculo de Volumen Prorrateado
				$piezas_sinbloqueo=$piezas-$fila['bloqueo_piezas'];
				$volumen_despacho=number_format((($volumen / $piezas) * $recibido_piezas),2,".","");
				//captura por linea seleccionada
				if (isset($_POST["palet$i"]))
					$npallets=strtoupper($_POST["palet$i"]);
					else
						$npallets="";
				//captura por linea seleccionada
				if (isset($_POST["pcs$i"]))
					$npcs=$_POST["pcs$i"];
					else
						$npcs="";
				//captura por linea seleccionada
				if (isset($_POST["observaciones$i"]))
					$observaciones=strtoupper($_POST["observaciones$i"]);
					else
						$observaciones="";
				//captura por linea seleccionada
				if (isset($_POST["hh$i"]))
					$hh=$_POST["hh$i"];
						else
						$hh="";
				//captura por linea seleccionada
				if (isset($_POST["mm$i"]))
					$mm=$_POST["mm$i"];
						else
						$mm="";
				//captura por linea seleccionada
				if (isset($_POST["ss$i"]))
					$ss=$_POST["ss$i"];
						else
						$ss="";
				$hora_salida=$hh.":".$mm.":".$ss;
	
				//captura por linea seleccionada
				if (isset($_POST["hhi$i"]))
					$hhi=$_POST["hhi$i"];
						else
						$hhi="";
				//captura por linea seleccionada
				if (isset($_POST["mmi$i"]))
					$mmi=$_POST["mmi$i"];
						else
						$mmi="";
				//captura por linea seleccionada
				if (isset($_POST["ssi$i"]))
					$ssi=$_POST["ssi$i"];
						else
						$ssi="";
				$hora_inicio=$hhi.":".$mmi.":".$ssi;
			
				//2. inserta datos de cada linea
				$sql="INSERT INTO carga_correo (id_guia,
												id_correo,
												npallets,
												npcs,
												hora_inicio,
												hora_salida,
												observaciones,
												piezas,
												peso,
												volumen)
												VALUE
													('$id_guia',
													 '$id_despacho',
													 '$npallets',
													 '$npcs',
													 '$hora_inicio',
													 '$hora_salida',
													 '$observaciones',
													 '$recibido_piezas',
													 '$recibido_peso',
													 '$volumen_despacho')";
				mysql_query($sql,$conexion) or die (exit("0-11-0- "));
				
				//3. Modifica los datos de la Guía
				$sql2="UPDATE guia SET 
										id_tipo_bloqueo='$id_tipo_bloqueo',
										piezas_despacho='$piezas_despacho',
										peso_despacho='$peso_despacho',
										volumen_despacho='$volumen_despacho' 
											WHERE id='$id_guia'";	
				mysql_query($sql2,$conexion) or die (exit("0-12-0- "));
				
				//4. Ingresar los datos del Tracking de la guia
				$sql="INSERT INTO tracking (id_guia,
								  evento,
								  fecha_creacion,
								  hora,
								  tipo_tracking,
								  id_usuario) 
									VALUE ('$id_guia',
											'GUIA CON $destino_entrega,  EN DESPACHO No. $id_despacho<br>
											DESPACHO DE PIEZAS=$recibido_piezas, PESO=$recibido_peso,  VOLUMEN=$volumen_despacho',
											'$fecha',
											'$hora',
											'1',
											'$usuario')";
				mysql_query($sql,$conexion) or die (exit("0-13-0- "));
	
				//5 Registra evento en Correo de Salida
				$addmensaje="AEROLINEA:$aerolinea. ->DESPACHADA";
				include("config/mail.php");
				//***********************************
			}
	}
	
	if ($cont==0) //PRESENTA ERROR CUANDO HAY UNA GUIA PERO NO SE PERMITE SELECCIONAR POR BLOQUEO PARCIAL, CREANDO UN ID DE CORREO SIN USO
		{
			$sql="DELETE FROM correo WHERE id='$id_despacho'";
			mysql_query($sql,$conexion) or die (exit("0-14-0- "));
			echo $msg_exit = "0-14-0- Debe seleccionar alguna guia";
		}
	else
		{
			echo $msg_exit = "1-0-$id_despacho- ";
		}
}

?>
