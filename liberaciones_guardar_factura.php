<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$id_usuario=$_SESSION['id_usuario'];
/****************************************
PROCEDIMIENTO:

1. CAPTURAR LOS DATOS NECESARIOS PARA LA ACTUALIZACION
2. DETERMINAR SI ES GUIA MASTER PARA MODIFICAR TODAS LAS HIJAS O MODIFICAR SOLO LA GUIA HIJA.
3. ACTUALIZAR LAS GUIAS NECESARIAS INCLUYENDO LA GUIA MASTER SI ES EL CASO.
4. ACTUALIZAR EL HISTORIAL DE LAS GUIAS
****************************************/

//1
$fechafac=$_POST['fecha'];
$nfactura=strtoupper($_POST['nfactura']);
$id_guia=$_POST['id_guia'];
$subtipo=$_POST['subtipo'];
$valor=$_POST['valor'];
$iva=$_POST['iva'];
$metodo=$_POST['metodo'];
if (isset($_POST['facturadoa']))
	$facturadoa=strtoupper($_POST['facturadoa']);
	else
		$facturadoa="";
//2
if ($subtipo == "master")
{
	//consulta de Tipo de Guia para redireccionamiento final
	$sql="SELECT id_tipo_guia FROM guia WHERE id = '$id_guia'";	
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1:". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$id_tipo_guia=$fila['id_tipo_guia'];	

	//3 en este caso actualizamos el registro de la guia master, luego se actualizaran todas las guias dependientes	
	if($metodo=="crear")
	{		
		$sql_ex="INSERT INTO guia_factura (nfactura,valor_factura,iva,fecha_factura,facturadoa,id_guia) VALUE ('$nfactura','$valor','$iva','$fechafac','$facturadoa','$id_guia')";
		$mensaje_tracking="Gu&iacute;a Asociada A La Factura  No. $nfactura";
	}
	else
	{
		$id_registro=$_POST['id_registro'];
		//consulta de No factura actual que se modificará en todos los registros hijos
		$sql="SELECT nfactura FROM guia_factura WHERE id = '$id_registro'";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2:". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila=mysql_fetch_array($consulta);
		$nfactura_almacenado=$fila['nfactura'];

		//Ejecucion de la actualización de la master
		$sql_ex="UPDATE guia_factura SET nfactura='$nfactura',valor_factura='$valor',iva='$iva',fecha_factura='$fechafac',facturadoa='$facturadoa' WHERE id='$id_registro'";		

		//Actualiza todos los registros que asocian las guias hijas de la master
		$sql_ex_hijos="UPDATE guia_factura SET nfactura='$nfactura',valor_factura='$valor',iva='$iva',fecha_factura='$fechafac',facturadoa='$facturadoa' WHERE nfactura='$nfactura_almacenado'";	
		mysql_query ($sql_ex_hijos,$conexion) or die ("ERROR 3:". mysql_error(). " INFORME AL SOPORTE TECNICO");	

		//Mensaje Para el Tracking
		$mensaje_tracking="Factura No. $nfactura MODIFICADA";
	}	
	mysql_query ($sql_ex,$conexion) or die ("ERROR 4:". mysql_error(). " INFORME AL SOPORTE TECNICO");

	//4 Traking de la Master
	$sql_tracking="INSERT INTO tracking (id_guia,
									fecha_creacion,
									hora,
									evento,
									tipo_tracking,
									id_usuario) 
										value ('$id_guia',
												'$fecha',
												'$hora',
												'$mensaje_tracking',
												'1',
												'$id_usuario')";
	mysql_query($sql_tracking,$conexion) or die ("ERROR 5: ".mysql_error());		

	//Proceso con Guias Dependientes 3 y 4
	$sql="SELECT id FROM guia WHERE master = '$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 6:". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		$id_guia_hija=$fila['id'];
		if($metodo=="crear")
		{		
			$sql_ex="INSERT INTO guia_factura (nfactura,valor_factura,iva,fecha_factura,facturadoa,id_guia) VALUE ('$nfactura','$valor','$iva','$fechafac','$facturadoa','$id_guia_hija')";
			$mensaje_tracking="Gu&iacute;a Asociada A La Factura  No. $nfactura";
		}
		else
		{
			$mensaje_tracking="Factura No. $nfactura MODIFICADA";
		}		
		mysql_query ($sql_ex,$conexion) or die ("ERROR 7:". mysql_error(). " INFORME AL SOPORTE TECNICO");

		//Traking de la hija
		$sql_tracking="INSERT INTO tracking (id_guia,
											fecha_creacion,
											hora,
											evento,
											tipo_tracking,
											id_usuario) 
												value ('$id_guia_hija',
														'$fecha',
														'$hora',
														'$mensaje_tracking',
														'1',
														'$id_usuario')";
		mysql_query($sql_tracking,$conexion) or die ("ERROR 8: ".mysql_error());		
	}
}
else
{
	//consulta de Tipo de Guia para redireccionamiento final
	$sql="SELECT id_tipo_guia FROM guia WHERE id = '$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 9:". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$id_tipo_guia=$fila['id_tipo_guia'];

	//3 en este caso actualizamos solo el registro de esta guia
	if($metodo=="crear")
	{		
		$sql_ex="INSERT INTO guia_factura (nfactura,valor_factura,iva,fecha_factura,facturadoa,id_guia) VALUE ('$nfactura','$valor','$iva','$fechafac','$facturadoa','$id_guia')";
		$mensaje_tracking="Gu&iacute;a Asociada A La Factura  No. $nfactura";
	}
	else
	{
		$id_registro=$_POST['id_registro'];
		$sql_ex="UPDATE guia_factura SET nfactura='$nfactura',valor_factura='$valor',iva='$iva',fecha_factura='$fechafac',facturadoa='$facturadoa' WHERE id='$id_registro'";
		$mensaje_tracking="Factura No. $nfactura MODIFICADA";
	}	
	mysql_query ($sql_ex,$conexion) or die ("ERROR 10:". mysql_error(). " INFORME AL SOPORTE TECNICO");

	//4 Traking de la Master
	$sql_tracking="INSERT INTO tracking (id_guia,
									fecha_creacion,
									hora,
									evento,
									tipo_tracking,
									id_usuario) 
										value ('$id_guia',
												'$fecha',
												'$hora',
												'$mensaje_tracking',
												'1',
												'$id_usuario')";
	mysql_query($sql_tracking,$conexion) or die ("ERROR 11: ".mysql_error());		
}

if(($id_tipo_guia=='5') || ($id_tipo_guia=='6'))
{
	echo "
		<script language=\"javascript\">
			alert(\"GUIA Asociada a Facturacion de manera Satisfactoria\");
			document.location='courier_liberaciones_listar_guias.php';
		</script>";
}
else
{
	echo "
		<script language=\"javascript\">
			alert(\"GUIA Asociada a Facturacion de manera Satisfactoria\");
			document.location='liberaciones_listar_guias.php';
		</script>";
}



?>