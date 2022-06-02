<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$nregistros=$_POST['nregistros'];

for ($i=1; $i <= $nregistros; $i++)
{
	$planilla_recepcion=strtoupper($_POST["planilla_recepcion$i"]);
	$id_remesa=$_POST["id_remesa_$i"];
	
	//carga de guias despachadas con la remesa
	$sql="SELECT planilla_recepcion FROM remesa WHERE id='$id_remesa'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$planilla_recepcion_original=$fila["planilla_recepcion"];
	
	if ($planilla_recepcion != $planilla_recepcion_original)
	{
		// Alacenamiento de guia
		$sql="UPDATE remesa SET planilla_recepcion ='$planilla_recepcion' WHERE id='$id_remesa'";
		mysql_query ($sql,$conexion) or die (exit('Error de insercion: '.mysql_error()));
		
		//carga de guias despachadas con la remesa
		$sql="SELECT * FROM carga_remesa WHERE id_remesa='$id_remesa'";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		while($fila=mysql_fetch_array($consulta))
			{
				$id_guia=$fila["id_guia"];			
				$id_usuario=$_SESSION['id_usuario'];
				$fecha_creacion=date("Y").date("m").date("d");
				$hora_registro=date("H:i:s");
				$sql_trak="INSERT INTO tracking (id_guia,
												fecha_creacion,
												hora,
												evento,
												tipo_tracking,
												id_usuario) 
													value ('".$id_guia."',
															'".$fecha_creacion."',
															'".$hora_registro."',
															'ACTUALIZADO EL REGISTRO CON PLANILLA DE RECEPCION No. $planilla_recepcion',
															'1',
															'".$id_usuario."')";
				mysql_query($sql_trak,$conexion) or die (exit('Error de insercion: '.mysql_error()));
			}
	}
}
echo "Registro Exitoso";
?>