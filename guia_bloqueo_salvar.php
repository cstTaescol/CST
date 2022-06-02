<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$fecha_creacion=date("Y").date("m").date("d");
$hora_registro=date("H:i:s");
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$id_usuario=$_SESSION['id_usuario'];
?>
<html>
	<head>
    	<style>
			body{
				background-image:url(imagenes/background.png);
				background-repeat:repeat;
				margin:0;
				padding:0;
			}
			.msg{
				border-radius: 50px;
				background-color: #999999;
				position: relative;
				color:#FFF;
				text-align:center;
				font-family: Arial, Helvetica, sans-serif;
				font-size:25px;	
				font-weight:bold;
				margin-left: auto;
				width: 500px;
				height:100px;
				margin-right: auto;
				top: 30%;
			}
		</style>
    </head>
    <body>
		<div id="cargando"><p align="center"><img src="imagenes/cargando.gif" width="20" height="21" /><br>Procesando</p></div>
<?php
if(isset($_POST["id_guia"])) 
{
	$id_guia=$_POST["id_guia"];
	$sql="SELECT * FROM guia WHERE id ='$id_guia'";
	$consulta=mysql_query($sql,$conexion) or die (exit('Error '.mysql_error()));
	$fila=mysql_fetch_array($consulta);
	include("config/evaluador_inconsistencias.php");
	$inmoforzosa=$fila["n_acta_inmoforzosa"];
	$inmomanual=$fila["n_acta_bloqmanual"];
	$id_aerolinea=$fila["id_aerolinea"];

	// identificando de Aerolinea
	$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
	$consulta_add=mysql_query ($sql_add,$conexion) or die (exit('Error '.mysql_error()));
	$fila_add=mysql_fetch_array($consulta_add);
	$aerolinea=$fila_add["nombre"];
}
else
{
	exit ("Error al obtener la Gu&iacute;a, Consulte con el Soporte T&eacute;cnico");
}

if ($_POST["accion"] == "B") //para la recepcion de la orden de bloqueo
{
	if ($_POST["rdcantidad"] == "T") //T Total o P Parcial
	{
		$piezasb=$piezas;
		$pesob=$peso;
		$id_tipo_bloqueo=5;
		$msgaccion="BLOQUEO TOTAL";
	}
	else
		{
			$piezasb=$_POST["piezasb"];
			$pesob=$_POST["pesob"];
			$id_tipo_bloqueo=10;
			$msgaccion="BLOQUEO PARCIAL";
		}
	$movilizacion=$_POST["movilizacion"];
	// identificando movilizacion
	$sql3="SELECT nombre FROM movilizacion WHERE id='$movilizacion'";
	$consulta3=mysql_query ($sql3,$conexion) or die (exit('Error '.mysql_error()));
	$fila3=mysql_fetch_array($consulta3);
	$tipoblok=$fila3["nombre"];
	
	
	if (isset($_POST["ckinclusionf"]))
	{
		$txtinclusionf=$_POST["txtinclusionf"];
	}
	else
		{
			$txtinclusionf="";
		}
	if (isset($_POST["ckactamanual"]))
	{
		$txtactamanual=$_POST["txtactamanual"];
	}
	else
		{
			$txtactamanual="";
		}
	$descripcion=strtoupper($_POST["descripcion"]);
	if ($descripcion=="")
		$descripcion="";
		
	//1. modificacin del estado de la guia
	$sql="UPDATE guia SET id_tipo_bloqueo='$id_tipo_bloqueo', id_inmovilizacion='$movilizacion',descripcion_bloqueo='$descripcion', n_acta_inmoforzosa='$txtinclusionf', n_acta_bloqmanual='$txtactamanual',bloqueo_piezas='$piezasb',bloqueo_peso='$pesob'  WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (exit('Error '.mysql_error()));

	//2. almacenamiento del traking
	$sql_trak="INSERT INTO tracking (id_guia,
									 fecha_creacion,
									 hora,
									 evento,
									 tipo_tracking,
									 id_usuario) 
									value 
									('$id_guia',
									 '$fecha_creacion',
									 '$hora_registro',
									 'CAMBIO DE ESTADO A: $msgaccion<br>
									 	Tipo de Bloqueo=$tipoblok<br>
										No. Inclusi&oacute;n Forzosa:$txtinclusionf<br>
										No. Acta Manual:$txtactamanual<br>
										Bloqueo de: Piezas=$piezasb - Peso=$pesob
									 ',
									 '1',
									 '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die (exit('Error '.mysql_error()));
	
	//3 Registra evento en Correo de Salida
	$addmensaje="AEROLINEA:$aerolinea. -> INMOVILIZADA POR: -> INCLUSION FORZOSA No.$txtinclusionf -> ACTA MANUAL No.$txtactamanual";
	include("config/mail.php");
	//***********************************
	
	echo '<script language="javascript">
			alert("Atencion: Guia bloqueada por '.$msgaccion.'");
			document.location="inventario_general.php";
		</script>';
}
else //para la resepcion de la orden de des-bloqueo
{
	$desbloqueo=false;
	if ($_POST["rdcantidad"] == "T") //T Total o P Parcial
	{
		$piezas_digitadas=$fila["bloqueo_piezas"];
		$peso_digitado=$fila["bloqueo_peso"];
		$piezasb="0";
		$pesob="0";
		$id_tipo_bloqueo=3;
		$msgaccion="DESBLOQUEO TOTAL";
	}
	else
		{
			$piezas_digitadas=$_POST["piezasb"];
			$peso_digitado=$_POST["pesob"];
			$piezasb=$fila["bloqueo_piezas"]-$piezas_digitadas;
			$pesob=$fila["bloqueo_peso"]-$peso_digitado;
			$id_tipo_bloqueo=10;
			$msgaccion="DESBLOQUEO PARCIAL";
		}
	
	
	$movilizacion=$_POST["movilizacion"];
	// identificando movilizacion
	$sql3="SELECT nombre FROM movilizacion WHERE id='$movilizacion'";
	$consulta3=mysql_query ($sql3,$conexion) or die (exit('Error 1'.mysql_error()));
	$fila3=mysql_fetch_array($consulta3);
	$tipoblok=$fila3["nombre"];

	$descripcion=strtoupper($_POST["descripcion"]);
	if ($descripcion=="")
		$descripcion="";
		
	if (isset($_POST["txtinclusionf"]))
	{
		$txtinclusionf=$_POST["txtinclusionf"];
	}
	else
		{
			$txtinclusionf="";
		}

	if (isset($_POST["txtactamanual"]))
	{
		$txtactamanual=$_POST["txtactamanual"];
	}
	else
		{
			$txtactamanual="";
		}
	// Se verifica que cumpla todas las condiciones para ser desbloqueada	
	//vefificacion de registro de inclusion forzosa y digitacion de acta 1154
	if ($inmoforzosa != "")
	{
		if ($txtinclusionf != "")
			{
				$desbloqueo1154=true;
			}
		else
			{
				$desbloqueo1154=false;
			}
	}
	else
		{
			$desbloqueo1154=true;
		}
	
	//vefificacion de registro de inmovilizacion manual y acta de levante manual
	if ($inmomanual != "")
	{
		if ($txtactamanual != "")
			{
				$desbloqueomanual=true;
			}
		else
			{
				$desbloqueomanual=false;
			}
	}	
	else
		{
			$desbloqueomanual=true;
		}
	
	if ($desbloqueo1154==true && $desbloqueomanual==true)
	{
		$variablo_bloqueo="id_tipo_bloqueo='$id_tipo_bloqueo',";
		$variable_piezas="bloqueo_piezas='$piezasb',bloqueo_peso='$pesob',";
		
	}
	else
		{
			$variablo_bloqueo="";
			$variable_piezas="";
			$msgaccion="CON BLOQUEO ACTUALIZADO, NO SE APLICARA HASTA QUE SE COMPLETEN TODOS LOS DATOS";
			$piezas_digitadas="N/A";
			$peso_digitado="N/A";
		}
		
	//1. modificacin del estado de la guia
	$sql="UPDATE guia SET $variablo_bloqueo $variable_piezas descripcion_bloqueo='$descripcion', n_acta_movilizacion='$txtinclusionf', n_acta_desbloqmanual='$txtactamanual', id_movilizacion='$movilizacion' WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (exit('Error 2'.mysql_error()));


	//2. almacenamiento del traking
	$sql_trak="INSERT INTO tracking (id_guia,
									 fecha_creacion,
									 hora,
									 evento,
									 tipo_tracking,
									 id_usuario) 
									value 
									('$id_guia',
									 '$fecha_creacion',
									 '$hora_registro',
									 'CAMBIO DE ESTADO A: $msgaccion<br>
									 	Tipo de Bloqueo=$tipoblok<br>
										No. Acta 1154:$txtinclusionf<br>
										No. Movilizaci&oacute;n Manual:$txtactamanual<br>
										Desbloqueo de:Piezas=$piezas_digitadas - Peso=$peso_digitado
									 ',
									 '1',
									 '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die (exit('Error 3'.mysql_error()));
	
	//3 Registra evento en Correo de Salida
	$addmensaje="AEROLINEA:$aerolinea. -> MOVILIZADA POR: -> ACTA No.$txtinclusionf -> ACTA MANUAL No.$txtactamanual";
	include("config/mail.php");
	//***********************************
	
	echo '<script language="javascript">
			alert("ATENCION: GUIA '.$msgaccion.'");
			document.location="inventario_general.php";
		</script>';
}
?>
</body>
</html>