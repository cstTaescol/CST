<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$cont=0;
$error="";
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$usuario=$_SESSION["id_usuario"];
$cantidadguias=$_POST["cantidadguias"];
$descripcion=strtoupper($_POST["descripcion"]);

// 1. Ingresar datos a la tabla de Correo
$sql="INSERT INTO otros (observaciones,
			  hora,
			  fecha,
			  id_usuario) 
				VALUE ('$descripcion',
						'$hora',
						'$fecha',
						'$usuario')";
mysql_query($sql,$conexion) or die (exit('Error 1 '.mysql_error()));
$id_otros = mysql_insert_id($conexion); //Obtiene el id de la ultima inserción

for ($i=1; $i<=$cantidadguias; $i++)
{
	if (isset($_POST["accion$i"]))
		{
			$cont++;
			$id_guia=$_POST["accion$i"];
			
			//Datos de la guia para descargar las piezas totales como despachadas****************************************
			$sql="SELECT * FROM guia WHERE id='$id_guia'";
			$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
			$fila=mysql_fetch_array($consulta);
			include("config/evaluador_inconsistencias.php");
			//**********************************************/
			echo $fila['id_tipo_bloqueo'];
			if ($fila['id_tipo_bloqueo'] == 10)
			{		
				$peso=$peso-$fila["bloqueo_peso"]-$fila["peso_despacho"];
				$piezas_sinbloqueo=$piezas-$fila['bloqueo_piezas'];
				$volumen=(($volumen / $piezas) * $piezas_sinbloqueo)-$fila["volumen_despacho"];
				$piezas=$piezas-$fila['bloqueo_piezas']-$fila["piezas_despacho"];
				$id_tipo_bloqueo=10;
			}
			else
				{
					$peso=$peso-$fila["peso_despacho"];
					$piezas_sinbloqueo=$piezas-$fila['piezas_despacho'];
					$volumen=$volumen -$fila["volumen_despacho"];
					$piezas=$piezas-$fila["piezas_despacho"];	
					$id_tipo_bloqueo=4;
				}
			//*************************************
			$piezas_despacho=$fila['piezas_despacho']+$piezas;
			$peso_despacho=$fila['peso_despacho']+$peso;
			$volumen_despacho=$fila['volumen_despacho']+$volumen;
			$id_aerolinea=$fila['id_aerolinea'];
			// identificando de Aerolinea
			$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die (exit('Error '.mysql_error()));
			$fila_add=mysql_fetch_array($consulta_add);
			$aerolinea=$fila_add["nombre"];
			//***************************


			//2. inserta datos de cada linea
			$sql="INSERT INTO carga_otros (id_guia,
											id_otros,
											piezas,
											peso,
											volumen)
											VALUE
												('$id_guia',
												 '$id_otros',
												 '$piezas',
												 '$peso',
												 '$volumen')";
			mysql_query($sql,$conexion) or die (exit('Error '.mysql_error()));
			
			//3. Modifica los datos de la Guía
			$sql2="UPDATE guia SET 
									id_tipo_bloqueo=$id_tipo_bloqueo,
									piezas_despacho='$piezas_despacho',
									peso_despacho='$peso_despacho',
									volumen_despacho='$volumen_despacho' 
										WHERE id='$id_guia'";	
			mysql_query($sql2,$conexion) or die (exit('Error '.mysql_error()));
			//4. Ingresar los datos del Tracking de la guia
			$sql="INSERT INTO tracking (id_guia,
							  evento,
							  fecha_creacion,
							  hora,
							  tipo_tracking,
							  id_usuario) 
								VALUE ('$id_guia',
										'GUIA DESPACHADA COMO OTROS DESPACHOS No. $id_otros<br>
										 DESPACHO DE PIEZAS=$piezas, PESO=$peso,  VOLUMEN=$volumen',
										'$fecha',
										'$hora',
										'1',
										'$usuario')";
			mysql_query($sql,$conexion) or die (exit('Error '.mysql_error()));
			//5. Si despacho toda la Guia, elimina del mapa de la bodega, todas las posiciones de esa guia.
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
			
			//6. Registra evento en Correo de Salida
			$addmensaje="AEROLINEA:$aerolinea. ->DESPACHADA";
			include("config/mail.php");
			//***********************************			
		}
}
if ($cont==0)
	{
		$sql="DELETE FROM otros WHERE id='$id_otros'";
		mysql_query($sql,$conexion) or die (exit('Error '.mysql_error()));
		echo '<script type="text/javascript">
				alert("ERROR: Debe seleccionar minimo una Guia")
		   	</script>';
		echo '<meta http-equiv="Refresh" content="0;url=despacho_otros1.php">';
	}
else
	{echo '<meta http-equiv="Refresh" content="0;url=despacho_otros3.php?id='.$id_otros.'">';}
?>
