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

if(isset($_GET["id"]))
{
	$id_correo=$_GET["id"];
	//1.
	$sql="SELECT * FROM carga_correo WHERE id_correo = '$id_correo'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		$id_registro=$fila['id'];
		$id_guia=$fila['id_guia'];
		//Despachados en este registro
		$piezas=$fila['piezas']; 
		$peso=$fila['peso'];
		$volumen=$fila['volumen'];
		
		//Datos de la guia
		$sql2="SELECT * FROM guia WHERE id = '$id_guia'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$id_tipo_bloqueo=$fila2['id_tipo_bloqueo'];
		
		$piezas_despacho=$fila2['piezas_despacho']-$piezas;
		$peso_despacho=$fila2['peso_despacho']-$peso;
		$volumen_despacho=$fila2['volumen_despacho']-$volumen;		
		
		if ($id_tipo_bloqueo != 10)
		{			
			$id_tipo_bloqueo=6;
		}

		//valores que deben regresar al total de la guia al cambiar de estado
		$sql_guia="UPDATE guia SET 
							id_tipo_bloqueo='$id_tipo_bloqueo',
							piezas_despacho='$piezas_despacho',
							peso_despacho='$peso_despacho',
							volumen_despacho='$volumen_despacho'
							WHERE id = '$id_guia'";
		mysql_query($sql_guia,$conexion) or die ("ERROR 3: ".mysql_error());
		//2.
		$sql_despacho="DELETE FROM carga_correo WHERE id='$id_registro'";
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
												 'GUIA RETORNA CON PIEZAS=$piezas - PESO=$peso - VOLUMEN=$volumen<BR>
												 POR QUE ELIMINAN EL DESPACHO No.$id_correo',
												 '1',
												 '$id_usuario')";
		mysql_query($sql_trak,$conexion) or die ("ERROR 5: ".mysql_error());
	}
	$sql_guia="UPDATE correo SET 
						estado='I'
						WHERE id = '$id_correo'";
	mysql_query($sql_guia,$conexion) or die ("ERROR 6: ".mysql_error());
	echo "
			<script language=\"javascript\">
				alert(\"DESPACHO Eliminado Satisfactoriamente, Las GUIAS asociadas a este DESPACHO, han vuelto a estar disponibles en el INVENTARIO.\");
				document.location='base.php';
			</script>";
}
else
	{
		echo "
			<script language=\"javascript\">
				alert(\"ERROR:No se pudo obtener el DESPACHO\");
				document.location='base.php';
			</script>";
	}