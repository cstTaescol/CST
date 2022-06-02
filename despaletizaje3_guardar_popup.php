<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if(isset($_POST['id_guia']))
{
	$id_usuario=$_SESSION['id_usuario'];
	$fecha_creacion=date("Y").date("m").date("d");
	$hora_registro=date("H:i:s");

	$id_guia=$_POST['id_guia'];
	$id_vuelo=$_POST['id_vuelo'];
	$despaletizaje_piezas=$_POST['despaletizaje_piezas'];
	$despaletizaje_peso=$_POST['despaletizaje_peso'];
	$observaciones_despaletizaje=strtoupper($_POST['observaciones']);

	if (isset($_POST['estiba']))
	{
		$estiba="S";
	}
	else
	{
		$estiba="N";
	}

	if (isset($_POST['huacal']))
	{
		$huacal="S";
	}
	else
	{
		$huacal="N";
	}

	if (isset($_POST['yute']))
	{
		$yute="S";
	}
	else
	{
		$yute="N";
	}

	if (isset($_POST['caja']))
	{
		$caja="S";
	}
	else
	{
		$caja="N";
	}

	if (isset($_POST['caneca']))
	{
		$caneca="S";
	}
	else
	{
		$caneca="N";
	}

	if (isset($_POST['larguero']))
	{
		$larguero="S";
	}
	else
	{
		$larguero="N";
	}
	
	
	//BOTONES DE ESTADO DE LA CARGA
	if (isset($_POST['rota']))
	{
		$rota="S";
	}
	else
	{
		$rota="N";
	}
		
	if (isset($_POST['abollada']))
	{
		$abollada="S";
	}
	else
	{
		$abollada="N";
	}

	if (isset($_POST['abierta']))
	{
		$abierta="S";
	}
	else
	{
		$abierta="N";
	}

	if (isset($_POST['humeda']))
	{
		$humeda="S";
	}
	else
	{
		$humeda="N";
	}

	if (isset($_POST['destilacion']))
	{
		$destilacion="S";
	}
	else
	{
		$destilacion="N";
	}

	if (isset($_POST['recintada']))
	{
		$recintada="S";
	}
	else
	{
		$recintada="N";
	}


	//1. almacenamiento de los datos en la tabla de despaletizaje
	$sql="INSERT INTO despaletizaje (id_guia,
									 id_vuelo,
									 piezas_recibido,
									 peso_recibido,
									 estiba,
									 huacal,
									 yute,
									 caja,
									 caneca,
									 larguero,
									 abollada,
									 recintada,
									 abierta,
									 rota,
									 destilacion,
									 humeda,
									 observacion) 
										VALUE ('$id_guia',
											   '$id_vuelo',
											   '$despaletizaje_piezas',
											   '$despaletizaje_peso',
											   '$estiba',
											   '$huacal',
											   '$yute',
											   '$caja',
											   '$caneca',
											   '$larguero',
											   '$abollada',
											   '$recintada',
											   '$abierta',
											   '$rota',
											   '$destilacion',
											   '$humeda',
											   '$observaciones_despaletizaje')";	
	mysql_query($sql,$conexion) or die ("Error 1 ". mysql_error());

	//2. Almacenamiento en Observaciones_Bodega de La Guia
	$sql="SELECT observaciones_bodega FROM guia WHERE id=$id_guia";	
	$consulta=mysql_query($sql,$conexion) or die ("Error 2: ".mysql_error());
	$fila=mysql_fetch_array($consulta);
	$observaciones_bodega=$fila["observaciones_bodega"]."<br />".$observaciones_despaletizaje;
	$sql="UPDATE guia SET observaciones_bodega='$observaciones_bodega' WHERE id=$id_guia";
	mysql_query($sql,$conexion) or die ("Error 2: ".mysql_error());
	
	//3. almacenamiento del traking
	$sql_trak="INSERT INTO tracking (id_guia,
									 fecha_creacion,
									 hora,
									 evento,
									 tipo_tracking,
									 id_usuario) 
										VALUE ('$id_guia',
											   '$fecha_creacion',
											   '$hora_registro',
											   'GUIA DESPALETIZADA CON:<br>
											   PIEZAS:$despaletizaje_piezas<br>
											   PESO:$despaletizaje_peso<br>
											   OBSERVACION:$observaciones_despaletizaje',
											   '1',
											   '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die ("Error 3 ".mysql_error());


	//Consulta para cargar las piezas y peso que ya se despaletizaron de esta guia.
	$sql3="SELECT SUM(piezas_recibido) AS total_piezas, SUM(peso_recibido) AS total_peso FROM despaletizaje WHERE id_vuelo='$id_vuelo' AND id_guia='$id_guia'";
	$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila3=mysql_fetch_array($consulta3);
	$piezas_recibido=$fila3["total_piezas"];
	$peso_recibido=round($fila3["total_peso"],2);
	if ($piezas_recibido == "")$piezas_recibido=0;
	if ($peso_recibido == "")$peso_recibido=0;
	//*************************

	//*************************
	$sql_adicional="SELECT hija, piezas, peso FROM guia WHERE id='$id_guia'";
	$consulta_adicional=mysql_query ($sql_adicional,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila_adicional=mysql_fetch_array($consulta_adicional);
	$hija=$fila_adicional["hija"];
	$direfencia_piezas=$fila_adicional["piezas"] - $piezas_recibido;
	$diferencia_peso=$fila_adicional["peso"] - $peso_recibido;
	//*************************
	if ($direfencia_piezas < 0)
	{
		$msg_piezas= "Sobrantes";
		$direfencia_piezas=$direfencia_piezas-($direfencia_piezas*2);
	}
	else
		$msg_piezas= "Pendientes";
		
	if ($diferencia_peso < 0)
	{
		$msg_peso= "Sobrantes";
		$diferencia_peso=$diferencia_peso-($diferencia_peso*2);
	}
	else
		$msg_peso= "Pendientes";
		
		
		
	echo "<script language=\"javascript\">
			alert (\"Datos almacenados de manera Exitosa para la Guia No $hija, \\nDiferencia de Piezas=$direfencia_piezas $msg_piezas, \\nDiferencia de Peso=$diferencia_peso $msg_peso\");
			document.location='despaletizaje1.php?vuelo=$id_vuelo';
			self.close();
		</script>";
}
else
{
	echo "<script language=\"javascript\">
			alert (\"ERROR: No se obtuvieron los datos PRINCIPALES, Comuniquese con el SOPORTE TECNICO\");
			document.location=\"base.php\";
		</script>";
}
?>
