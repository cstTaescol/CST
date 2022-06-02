<?php 
session_start(); /*   "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

$id_disposicion=$_GET['id_disposicion'];
$id_guia=$_GET['id_guia'];
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$id_usuario=$_SESSION['id_usuario'];

switch ($id_disposicion)
{
	case ($id_disposicion == 10 || $id_disposicion == 11 || $id_disposicion == 18 || $id_disposicion == 22): //despacho a depositios
		/*
		PROCEDIMIENTO:
		1. Elimina el registro del despacho
		2. Modificacion de la Guia por Retorno
		3. Almacenamiento del traking
			Adicionalmente, si la remesa solo contiene el registro de despacho de esta guia, se Cambia a estado Inactivo la Remesa
		*/		
		$id_remesa=$_GET['id_despacho'];
		$id_registro=$_GET['id_registro'];
		//Cantidad de registros de la remesa
		$sql="SELECT id FROM carga_remesa WHERE id_remesa = '$id_remesa'";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$nfilas=mysql_num_rows($consulta);
		//****************************************************
		
		//carga datos necesarios para las modificaciones
		$sql="SELECT o.id, o.piezas AS pi_despacho, o.peso AS pe_despacho,o.volumen AS vo_despacho,g.* FROM carga_remesa o LEFT JOIN guia g ON o.id_guia=g.id WHERE o.id_remesa = '$id_remesa'";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila=mysql_fetch_array($consulta);
		//*****************************************************
		$hija=$fila['hija'];
		//valores de este despacho que retornaran, mencionadas en el tracking
		$pi_despacho=$fila['pi_despacho'];
		$pe_despacho=$fila['pe_despacho'];
		$vo_despacho=$fila['vo_despacho'];
		//valores que deben regresar al total de la guia al cambiar de estado
		$piezas_despacho=$fila['piezas_despacho'] - $pi_despacho;
		$peso_despacho=$fila['peso_despacho'] - $pe_despacho;
		$volumen_despacho=$fila['volumen_despacho'] - $vo_despacho;

		$id_tipo_bloqueo=$fila['id_tipo_bloqueo'];
		if ($id_tipo_bloqueo == 10)
			$id_tipo_bloqueo=10;
			else
				$id_tipo_bloqueo=6;
	
		//1. Elimina el registro del despacho
		$sql_despacho="DELETE FROM carga_remesa WHERE id='$id_registro'";
		mysql_query($sql_despacho,$conexion) or die (mysql_error());
	
		//2. Modificacion de la Guia por Retorno
		$sql_guia="UPDATE guia SET 
							id_tipo_bloqueo='$id_tipo_bloqueo',
							piezas_despacho='$piezas_despacho',
							peso_despacho='$peso_despacho',
							volumen_despacho='$volumen_despacho'
							WHERE id = '$id_guia'";
		mysql_query($sql_guia,$conexion) or die (mysql_error());
		
		//3. almacenamiento del traking
		$sql_trak="INSERT INTO tracking (id_guia,
										fecha_creacion,
										hora,
										evento,
										tipo_tracking,
										id_usuario) 
										value ('$id_guia',
												'$fecha',
												'$hora',
												'GUIA No. $hija NO PUDO SER ENTREGADA Y RETORNAN A BODEGA:<br>
												PIEZAS:$pi_despacho, PESO:$pe_despacho, VOLUMEN:$vo_despacho QUE PERTENECIAN A LA REMESA No.$id_remesa',
												'1',
												'$id_usuario')";
		mysql_query($sql_trak,$conexion) or die (mysql_error());
		if ($nfilas==1) 
		{
			//4. Modificacion el Despacho
			$sql_remesa="UPDATE remesa SET 
								estado='I'
								WHERE id = '$id_remesa'";
			mysql_query($sql_remesa,$conexion) or die (mysql_error());
		}
		echo '
		<script language="javascript">
			alert("ATENCION:DESPACHO ELIMINADO, La guia volvera a estar disponible para el despacho desde este momento.");
			document.location="inventario_general.php";
		</script>';
	break;
	
	case ($id_disposicion == 26 || $id_disposicion == 27): //despacho a correo		
	/*
		PROCEDIMIENTO:
		1. Elimina el registro del despacho
		2. Modificacion de la Guia por Retorno
		3. Almacenamiento del traking
			Adicionalmente, si el envio solo contiene el registro de despacho de esta guia, se Cambia a estado Inactivo
	*/		
		$id_correo=$_GET['id_despacho'];
		$id_registro=$_GET['id_registro'];
		
		//Cantidad de registros de la remesa
		$sql="SELECT id,piezas,peso,volumen FROM carga_correo WHERE id_correo = '$id_correo'";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$nfilas=mysql_num_rows($consulta); // - Cantidad de registros
		$fila=mysql_fetch_array($consulta);
		$pi_despacho=$fila['piezas'];
		$pe_despacho=$fila['peso'];
		$vo_despacho=$fila['volumen'];
		//****************************************************
		
		//carga datos necesarios para las modificaciones
		$sql="SELECT * FROM guia WHERE id='$id_guia'";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila=mysql_fetch_array($consulta);
		//*****************************************************
		$hija=$fila['hija'];
		$piezas_despacho=$fila['piezas_despacho'] - $pi_despacho;
		$peso_despacho=$fila['peso_despacho'] - $pe_despacho;
		$volumen_despacho=$fila['volumen_despacho'] - $vo_despacho;
		
		$id_tipo_bloqueo=$fila['id_tipo_bloqueo'];
		if ($id_tipo_bloqueo == 10)
			$id_tipo_bloqueo=10;
			else
				$id_tipo_bloqueo=6;
		
		//1. Elimina el registro del despacho
		$sql_despacho="DELETE FROM carga_correo WHERE id='$id_registro'";
		mysql_query($sql_despacho,$conexion) or die (mysql_error());
	
		//2. Modificacion de la Guia por Retorno
		$sql_guia="UPDATE guia SET 
							id_tipo_bloqueo='$id_tipo_bloqueo',
							piezas_despacho='$piezas_despacho',
							peso_despacho='$peso_despacho',
							volumen_despacho='$volumen_despacho'
							WHERE id = '$id_guia'";
		mysql_query($sql_guia,$conexion) or die (mysql_error());
		
		//3. almacenamiento del traking
		$sql_trak="INSERT INTO tracking (id_guia,
										fecha_creacion,
										hora,
										evento,
										tipo_tracking,
										id_usuario) 
										value ('$id_guia',
												'$fecha',
												'$hora',
												'GUIA No. $hija NO PUDO SER ENTREGADA Y RETORNAN A BODEGA:<br>
												PIEZAS:$pi_despacho, PESO:$pe_despacho, VOLUMEN:$vo_despacho QUE PERTENECIAN AL DESPACHO POR CORREO No.$id_correo',
												'1',
												'$id_usuario')";
		mysql_query($sql_trak,$conexion) or die (mysql_error());
		if ($nfilas==1) //cuando solo se habia registrado 1 guia, y se elimina el despacho, anula todo el despacho
		{
			//4. Modificacion el Despacho
			$sql_remesa="UPDATE correo SET 
								estado='I'
								WHERE id = '$id_correo'";
			mysql_query($sql_remesa,$conexion) or die (mysql_error());
		}
		echo '
		<script language="javascript">
			alert("ATENCION:DESPACHO ELIMINADO, La guia volvera a estar disponible para el despacho desde este momento.");
			document.location="inventario_general.php";
		</script>';
	break;
	
	case ($id_disposicion == 15 || $id_disposicion == 25 || $id_disposicion == 28 || $id_disposicion == 29): //despacho a otros
	/*
		PROCEDIMIENTO:
		1. Elimina el registro del despacho
		2. Modificacion de la Guia por Retorno
		3. Almacenamiento del traking
			Adicionalmente, si el envio solo contiene el registro de despacho de esta guia, se Cambia a estado Inactivo
	*/		
		$id_otros=$_GET['id_despacho'];
		$id_registro=$_GET['id_registro'];
		//Cantidad de registros de la remesa
		$sql="SELECT * FROM carga_otros WHERE id_otros = '$id_otros'";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$nfilas=mysql_num_rows($consulta); // - Cantidad de registros
		$fila=mysql_fetch_array($consulta);
		$pi_despacho=$fila['piezas'];
		$pe_despacho=$fila['peso'];
		$vo_despacho=$fila['volumen'];
		//****************************************************
		
		//carga datos necesarios para las modificaciones
		$sql="SELECT * FROM guia WHERE id='$id_guia'";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila=mysql_fetch_array($consulta);
		//*****************************************************
		$hija=$fila['hija'];
		$piezas_despacho=$fila['piezas_despacho'] - $pi_despacho;
		$peso_despacho=$fila['peso_despacho'] - $pe_despacho;
		$volumen_despacho=$fila['volumen_despacho'] - $vo_despacho;
		
		$id_tipo_bloqueo=$fila['id_tipo_bloqueo'];
		if ($id_tipo_bloqueo == 10)
			$id_tipo_bloqueo=10;
			else
				$id_tipo_bloqueo=6;
		
		//1. Elimina el registro del despacho
		$sql_despacho="DELETE FROM carga_otros WHERE id='$id_registro'";
		mysql_query($sql_despacho,$conexion) or die (mysql_error());
	
		//2. Modificacion de la Guia por Retorno
		$sql_guia="UPDATE guia SET 
							id_tipo_bloqueo='$id_tipo_bloqueo',
							piezas_despacho='$piezas_despacho',
							peso_despacho='$peso_despacho',
							volumen_despacho='$volumen_despacho'
							WHERE id = '$id_guia'";
		mysql_query($sql_guia,$conexion) or die (mysql_error());
		
		//3. almacenamiento del traking
		$sql_trak="INSERT INTO tracking (id_guia,
										fecha_creacion,
										hora,
										evento,
										tipo_tracking,
										id_usuario) 
										value ('$id_guia',
												'$fecha',
												'$hora',
												'GUIA No. $hija NO PUDO SER ENTREGADA Y RETORNAN A BODEGA:<br>
												PIEZAS:$pi_despacho, PESO:$pe_despacho, VOLUMEN:$vo_despacho QUE PERTENECIAN AL DESPACHO POR OTROS No.$id_otros',
												'1',
												'$id_usuario')";
		mysql_query($sql_trak,$conexion) or die (mysql_error());
		if ($nfilas==1) 
		{
			//4. Modificacion del despacho
			$sql_remesa="UPDATE otros SET 
								estado='I'
								WHERE id = '$id_otros'";
			mysql_query($sql_remesa,$conexion) or die (mysql_error());
		}
		echo '
		<script language="javascript">
			alert("ATENCION:DESPACHO ELIMINADO, La guia volvera a estar disponible para el despacho desde este momento.");
			document.location="inventario_general.php";
		</script>';	
	break;
	
	case ($id_disposicion == 12 || $id_disposicion == 16 || $id_disposicion == 17 || $id_disposicion == 24): //despacho a cabotaje
	/*
		PROCEDIMIENTO:
		1. Cambiar el Despacho a Inactivo
		2. Modificacion de la Guia por Retorno
		3. Almacenamiento del traking
	*/		
		$id_cabotaje=$_GET['id_despacho'];
		//carga datos necesarios para las modificaciones
		$sql="SELECT o.piezas AS pi_despacho, o.peso AS pe_despacho,o.volumen AS vo_despacho,g.* FROM cabotaje o LEFT JOIN guia g ON o.id_guia=g.id WHERE g.id='$id_guia' AND o.id='$id_cabotaje'";		
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila=mysql_fetch_array($consulta);
		//*****************************************************
		$hija=$fila['hija'];
		//valores de este despacho que retornaran, mencionadas en el tracking
		$pi_despacho=$fila['pi_despacho'];
		$pe_despacho=$fila['pe_despacho'];
		$vo_despacho=$fila['vo_despacho'];
		//valores que deben regresar al total de la guia al cambiar de estado
		$piezas_despacho=$fila['piezas_despacho'] - $pi_despacho;
		$peso_despacho=$fila['peso_despacho'] - $pe_despacho;
		$volumen_despacho=$fila['volumen_despacho'] - $vo_despacho;
		//*****************************
		$id_tipo_bloqueo=$fila['id_tipo_bloqueo'];
		if ($id_tipo_bloqueo == 10)
			$id_tipo_bloqueo=10;
			else
				$id_tipo_bloqueo=6;
		//1. Modificacion del despacho
		$sql_remesa="UPDATE cabotaje SET 
							estado='I'
							WHERE id = '$id_cabotaje'";
		mysql_query($sql_remesa,$conexion) or die (mysql_error());

		//2. Modificacion de la Guia por Retorno
		$sql_guia="UPDATE guia SET 
							id_tipo_bloqueo='$id_tipo_bloqueo',
							piezas_despacho='$piezas_despacho',
							peso_despacho='$peso_despacho',
							volumen_despacho='$volumen_despacho'
							WHERE id = '$id_guia'";
		mysql_query($sql_guia,$conexion) or die (mysql_error());
		
		//3. almacenamiento del traking
		$sql_trak="INSERT INTO tracking (id_guia,
										fecha_creacion,
										hora,
										evento,
										tipo_tracking,
										id_usuario) 
										value ('$id_guia',
												'$fecha',
												'$hora',
												'GUIA No. $hija NO PUDO SER ENTREGADA Y RETORNAN A BODEGA:<br>
												PIEZAS:$pi_despacho, PESO:$pe_despacho, VOLUMEN:$vo_despacho QUE PERTENECIAN AL DESPACHO POR CABOTAJE No.$id_cabotaje',
												'1',
												'$id_usuario')";
		mysql_query($sql_trak,$conexion) or die (mysql_error());
		echo '
		<script language="javascript">
			alert("ATENCION:DESPACHO ELIMINADO, La guia volvera a estar disponible para el despacho desde este momento.");
			document.location="inventario_general.php";
		</script>';	
	break;
	case ($id_disposicion == 13 || $id_disposicion == 14 || $id_disposicion == 23): //despacho a trasbordo
	/*
		PROCEDIMIENTO:
		1. Cambiar el Despacho a Inactivo
		2. Modificacion de la Guia por Retorno
		3. Almacenamiento del traking
	*/		
		$id_trasbordo=$_GET['id_despacho'];
		//carga datos necesarios para las modificaciones
		$sql="SELECT o.piezas AS pi_despacho, o.peso AS pe_despacho,o.volumen AS vo_despacho,g.* FROM trasbordo o LEFT JOIN guia g ON o.id_guia=g.id WHERE g.id='$id_guia' AND o.id='$id_trasbordo'";		
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 8: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila=mysql_fetch_array($consulta);
		//*****************************************************
		$hija=$fila['hija'];
		//valores de este despacho que retornaran, mencionadas en el tracking
		$pi_despacho=$fila['pi_despacho'];
		$pe_despacho=$fila['pe_despacho'];
		$vo_despacho=$fila['vo_despacho'];
		//valores que deben regresar al total de la guia al cambiar de estado
		$piezas_despacho=$fila['piezas_despacho'] - $pi_despacho;
		$peso_despacho=$fila['peso_despacho'] - $pe_despacho;
		$volumen_despacho=$fila['volumen_despacho'] - $vo_despacho;
		//*****************************
		$id_tipo_bloqueo=$fila['id_tipo_bloqueo'];
		if ($id_tipo_bloqueo == 10)
			$id_tipo_bloqueo=10;
			else
				$id_tipo_bloqueo=6;
		
		//1. Modificacion del despacho
		$sql_remesa="UPDATE trasbordo SET 
							estado='I'
							WHERE id = '$id_trasbordo'";
		mysql_query($sql_remesa,$conexion) or die (mysql_error());

		//2. Modificacion de la Guia por Retorno
		$sql_guia="UPDATE guia SET 
							id_tipo_bloqueo='$id_tipo_bloqueo',
							piezas_despacho='$piezas_despacho',
							peso_despacho='$peso_despacho',
							volumen_despacho='$volumen_despacho'
							WHERE id = '$id_guia'";
		mysql_query($sql_guia,$conexion) or die (mysql_error());
		
		//3. almacenamiento del traking
		$sql_trak="INSERT INTO tracking (id_guia,
										fecha_creacion,
										hora,
										evento,
										tipo_tracking,
										id_usuario) 
										value ('$id_guia',
												'$fecha',
												'$hora',
												'GUIA No. $hija NO PUDO SER ENTREGADA Y RETORNAN A BODEGA:<br>
												PIEZAS:$pi_despacho, PESO:$pe_despacho, VOLUMEN:$vo_despacho QUE PERTENECIAN AL DESPACHO POR TRASBORDO No.$id_trasbordo',
												'1',
												'$id_usuario')";
		mysql_query($sql_trak,$conexion) or die (mysql_error());
		echo '
		<script language="javascript">
			alert("ATENCION:DESPACHO ELIMINADO, La guia volvera a estar disponible para el despacho desde este momento.");
			document.location="inventario_general.php";
		</script>';	
	break;
	
	case ($id_disposicion == 19 || $id_disposicion == 20 || $id_disposicion == 21): //despacho a descargue directo
	/*
		PROCEDIMIENTO:
		1. Cambiar el Despacho a Inactivo
		2. Modificacion de la Guia por Retorno
		3. Almacenamiento del traking
	*/		
		$id_ddirecto=$_GET['id_despacho'];
		//carga datos necesarios para las modificaciones
		$sql="SELECT o.piezas AS pi_despacho, o.peso AS pe_despacho,o.volumen AS vo_despacho,g.* FROM descargue_directo o LEFT JOIN guia g ON o.id_guia=g.id WHERE g.id='$id_guia' AND o.id='$id_ddirecto'";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 9: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila=mysql_fetch_array($consulta);
		//*****************************************************
		$hija=$fila['hija'];
		//valores de este despacho que retornaran, mencionadas en el tracking
		$pi_despacho=$fila['pi_despacho'];
		$pe_despacho=$fila['pe_despacho'];
		$vo_despacho=$fila['vo_despacho'];
		//valores que deben regresar al total de la guia al cambiar de estado
		$piezas_despacho=$fila['piezas_despacho'] - $pi_despacho;
		$peso_despacho=$fila['peso_despacho'] - $pe_despacho;
		$volumen_despacho=$fila['volumen_despacho'] - $vo_despacho;
		
		$id_tipo_bloqueo=$fila['id_tipo_bloqueo'];
		if ($id_tipo_bloqueo == 10)
			$id_tipo_bloqueo=10;
			else
				$id_tipo_bloqueo=6;
		
		
		//1. Modificacion del despacho
		$sql_remesa="UPDATE descargue_directo SET 
							estado='I'
							WHERE id = '$id_ddirecto'";
		mysql_query($sql_remesa,$conexion) or die (mysql_error());

		//2. Modificacion de la Guia por Retorno
		$sql_guia="UPDATE guia SET 
							id_tipo_bloqueo='$id_tipo_bloqueo',
							piezas_despacho='$piezas_despacho',
							peso_despacho='$peso_despacho',
							volumen_despacho='$volumen_despacho'
							WHERE id = '$id_guia'";
		mysql_query($sql_guia,$conexion) or die (mysql_error());
		
		//3. almacenamiento del traking
		$sql_trak="INSERT INTO tracking (id_guia,
										fecha_creacion,
										hora,
										evento,
										tipo_tracking,
										id_usuario) 
										value ('$id_guia',
												'$fecha',
												'$hora',
												'GUIA No. $hija NO PUDO SER ENTREGADA Y RETORNAN A BODEGA:<br>
												PIEZAS:$pi_despacho, PESO:$pe_despacho, VOLUMEN:$vo_despacho QUE PERTENECIAN AL DESPACHO POR DESCARGUE DIRECTO No.$id_ddirecto',
												'1',
												'$id_usuario')";
		mysql_query($sql_trak,$conexion) or die (mysql_error());
		echo '
		<script language="javascript">
			alert("ATENCION:DESPACHO ELIMINADO, La guia volvera a estar disponible para el despacho desde este momento.");
			document.location="inventario_general.php";
		</script>';
	break;
}
?>
