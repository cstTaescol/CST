<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$id_usuario=$_SESSION['id_usuario'];

/*
PROCEDIMIENTO:
1. Cambiar el estado de las guias liberadas y regresamos el contenido del despacho
2. Liberar los despachos asociados a esta remesa, eliminar registros de carga remesa
3. Agregar Evento al seguimiento de la guia
4. Modificar la remesa a estado I
*/

if(isset($_GET["id_remesa"]))
{
	$id_remesa=$_GET["id_remesa"];
	//1.
	$sql="SELECT id,id_guia,piezas,peso,volumen FROM carga_remesa WHERE id_remesa = '$id_remesa'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		$id_registro=$fila['id'];
		$id_guia=$fila['id_guia'];
		
		//carga datos necesarios para las modificaciones
		$sql2="SELECT piezas_despacho,peso_despacho,volumen_despacho,id_tipo_bloqueo FROM guia  WHERE id= '$id_guia'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		//*****************************************************

		//valores que deben regresar al total de la guia al cambiar de estado
		$piezas=$fila2['piezas_despacho']-$fila['piezas'];
		$peso=$fila2['peso_despacho']-$fila['peso'];
		$volumen=$fila2['volumen_despacho']-$fila['volumen'];
	
		$id_tipo_bloqueo=$fila2['id_tipo_bloqueo'];
		if ($id_tipo_bloqueo != 10)
			$id_tipo_bloqueo=6;

		$sql_guia="UPDATE guia SET 
							id_tipo_bloqueo='$id_tipo_bloqueo',
							piezas_despacho='$piezas',
							peso_despacho='$peso',
							volumen_despacho='$volumen'
							WHERE id = '$id_guia'";
		mysql_query($sql_guia,$conexion) or die ("ERROR 3: ".mysql_error());
		//2.
		$sql_despacho="DELETE FROM carga_remesa WHERE id='$id_registro'";
		mysql_query($sql_despacho,$conexion) or die ("ERROR 4: ".mysql_error());
		
		//3. almacenamiento del traking
		$sql_trak="INSERT INTO tracking (id_guia,
										 fecha_creacion,
										 hora,
										 evento,
										 tipo_tracking,
										 id_usuario) value 
												('$id_guia',
												 '$fecha',
												 '$hora',
												 'GUIA RETORNA POR QUE ELIMINAN LA REMESA No.$id_remesa',
												 '1',
												 '$id_usuario')";
		mysql_query($sql_trak,$conexion) or die ("ERROR 5: ".mysql_error());
	}
	$sql_guia="UPDATE remesa SET 
						estado='I'
						WHERE id = '$id_remesa'";
	mysql_query($sql_guia,$conexion) or die ("ERROR 6: ".mysql_error());
	echo "
			<script language=\"javascript\">
				alert(\"REMESA Eliminada Satisfactoriamente, Las GUIAS asociadas a este DESPACHO, han vuelto a estar disponibles en el INVENTARIO.\");
				document.location='base.php';
			</script>";
}
else
	{
		echo "
			<script language=\"javascript\">
				alert(\"ERROR:No se pudo obtener la REMESA\");
				document.location='base.php';
			</script>";
	}