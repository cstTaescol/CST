<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$id_usuario=$_SESSION['id_usuario'];

if (isset($_GET['id_guia']))
{
	$id_guia=$_GET["id_guia"];
	$procedimiento=$_GET["procedimiento"];
	if ($procedimiento == "E")
	{
		//ubicar el vuelo a ver si existen mas guias de ese vuelo que se encuentren en asignacion o manifestadas. si no hay mas finalizar el vuelo.
		$sql="SELECT id_vuelo FROM guia WHERE id = '$id_guia'";
		$consulta=mysql_query($sql,$conexion) or die ("Error 0".mysql_error());
		$fila=mysql_fetch_array($consulta);
		$id_vuelo=$fila['id_vuelo'];
		
		//elimina de la tabla de guia
		$sql="DELETE FROM guia WHERE id = '$id_guia'";
		mysql_query($sql,$conexion) or die ("Error 1".mysql_error());
		
		//Finaliza el vuelo si no contiene mas guias manifestadas o asignadas en el mismo vuelo
		$sql="SELECT id FROM guia WHERE id_vuelo = '$id_vuelo' AND (id_tipo_bloqueo='2' OR id_tipo_bloqueo='7') AND id_tipo_guia != '2'";
		$consulta=mysql_query($sql,$conexion) or die ("Error 2".mysql_error());
		$nfilas=mysql_num_rows($consulta);
		if ($nfilas == 0)
		{
			$sql="UPDATE vuelo SET estado ='F', id_usuario_finalizador='$id_usuario', hora_finalizado='$hora', fecha_finalizacion='$fecha' WHERE id='$id_vuelo'";
			mysql_query($sql,$conexion) or die (mysql_error());
		}
	
		//elimina de la tabla de traking
		$sql="DELETE FROM tracking WHERE id_guia = '$id_guia'";
		mysql_query($sql,$conexion) or die ("Error 3".mysql_error());
	
		//elimina de la tabla de INCONSISTENCIAS
		$sql="DELETE FROM inconsistencias WHERE id_guia = '$id_guia'";
		mysql_query($sql,$conexion) or die ("Error 4".mysql_error());
		
		echo "<script type=\"text/javascript\">
				alert(\"La Guia se ELIMINO de manera Exitosa\");
				document.location='base.php';
			</script>";	
	}
	else
		{   //PROCESO de ANULAR la guia
			//Identifica si es ina guia Pausada.
			$sql="SELECT id_tipo_bloqueo FROM guia WHERE id = '$id_guia'";
			$consulta=mysql_query($sql,$conexion) or die ("Error Tipo Bloqueo ".mysql_error());
			$fila=mysql_fetch_array($consulta);
			$id_tipo_bloqueo=$fila['id_tipo_bloqueo'];		
		
			$sql="SELECT id FROM carga_remesa WHERE id = '$id_guia'";
			$consulta=mysql_query($sql,$conexion) or die ("Error Carga Remesa".mysql_error());
			$nfilas=mysql_num_rows($consulta);

			if ($nfilas >= 1 && $id_tipo_bloqueo != 11)
			{
				echo "<script type=\"text/javascript\">
					alert(\"La Guia no se puede ANULAR por que es una guia PARCIAL\");
					document.location='consulta_guia.php?id_guia=$id_guia';
				</script>";	
				exit();
			}
			else
			{
				//ubicar el vuelo a ver si existen mas guias de ese vuelo que se encuentren en asignacion o manifestadas. si no hay mas finalizar el vuelo.
				$sql="SELECT id_vuelo FROM guia WHERE id = '$id_guia'";
				$consulta=mysql_query($sql,$conexion) or die ("Error 0".mysql_error());
				$fila=mysql_fetch_array($consulta);
				$id_vuelo=$fila['id_vuelo'];
				//***********************************
				
				//Inactiva la guia
				$sql="UPDATE guia SET id_tipo_bloqueo = '8' WHERE id = '$id_guia'";
				mysql_query($sql,$conexion) or die ("Error 3".mysql_error());
				//************************************

				//Finaliza el vuelo si no contiene mas guias manifestadas o asignadas en el mismo vuelo
				$sql="SELECT id FROM guia WHERE id_vuelo = '$id_vuelo' AND (id_tipo_bloqueo='2' OR id_tipo_bloqueo='7') AND id_tipo_guia != '2'";
				$consulta=mysql_query($sql,$conexion) or die ("Error 2".mysql_error());
				$nfilas=mysql_num_rows($consulta);
				if ($nfilas == 0)
				{
					$sql="UPDATE vuelo SET estado ='F', id_usuario_finalizador='$id_usuario', hora_finalizado='$hora', fecha_finalizacion='$fecha' WHERE id='$id_vuelo'";
					mysql_query($sql,$conexion) or die (mysql_error());
				}
				//Elimina las posiciones de bodega
				$sql="DELETE FROM posicion_carga WHERE id='$id_guia'";
				mysql_query ($sql,$conexion) or die (exit('Error al liberar la posicion '.mysql_error()));

				
				//Actualiza el traking.
				$sql_trak="INSERT INTO tracking (id_guia,
												fecha_creacion,
												hora,
												evento,
												tipo_tracking,
												id_usuario) 
												value ('$id_guia',
														'$fecha',
														'$hora',
														'GUIA ANULADA',
														'1',
														'$id_usuario')";
				mysql_query($sql_trak,$conexion) or die (mysql_error());
	
				echo "<script type=\"text/javascript\">
						alert(\"La Guia se ANULO de manera Exitosa\");
						document.location='base.php';
					</script>";	
			}
		}
}
?>