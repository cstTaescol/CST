<?php 
session_start(); /*    "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$contador=0;
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
    </body>
</html>
<?php
// Al recibir la opcion de Guardar ********
if(isset($_POST["nmanifiesto"]))
{
	$id_vuelo=$_POST["id_vuelo"];
	//Calcula rangos de fecha y hora frente a las registradas y del servidor
	$hora_registrada=$_POST["hh_manifiesto"].$_POST["mm_manifiesto"].$_POST["ss_manifiesto"];
	$hora_servidor=date("His");
	$fecha_creacion=date("Y").date("m").date("d");
	$fecha_registrada=explode("-",$_POST["fecha_manifiesto"]);
	$fecha_registrada=$fecha_registrada[0].$fecha_registrada[1].$fecha_registrada[2];
	if ($fecha_registrada > $fecha_creacion)
	{
		echo "<script language=\"javascript\">
				alert ('ATENCION: La seleccionada FECHA no puede ser superior que la del Servidor');
				document.location='vuelo_manifiesto_seleccionar.php?vuelo=$id_vuelo';
			</script>";
		exit();
	}
	if ($hora_registrada > $hora_servidor && $fecha_creacion == $fecha_registrada)
	{
		echo "<script language=\"javascript\">
				alert ('ATENCION: No puede ingresar una HORA superior a la del Servidor');
				document.location='vuelo_manifiesto_seleccionar.php?vuelo=$id_vuelo';
			</script>";
		exit();
	}
	
	//Actualizar cada una de las guias
	$nguias=$_SESSION["nguias"];
	$hora_registro=date("H:i:s");
	$id_usuario=$_SESSION['id_usuario'];
	
	for ($i=1; $i<=$nguias; $i++)
	{
		$valor=$_POST["valor".$i];
		if (isset($_POST["chkacepto$i"]) && $valor==0) //cuando seleccione una guia que anteriormente NO estaba seleccionada(valor=0)
		{
			//1 Actualiza el estado de una guia asociada a este vuelo a un estado de manifestada
			$id_guia=$_POST["guia$i"];
			$sql="UPDATE guia SET id_tipo_bloqueo='2' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (mysql_error());
			
			//2 Ingresamos el evento al historial de la guia
			$sql_trak="INSERT INTO tracking (id_guia,
											 	evento,
										 		fecha_creacion,
										 		hora,
											 	tipo_tracking,
										 		id_usuario) 
												value ('$id_guia',
												   'GUIA MANIFESTADA',
												   '$fecha_creacion',
												   '$hora_registro',
												   '1',
												   '$id_usuario')";
			mysql_query($sql_trak,$conexion) or die (mysql_error());
			$contador++;
		}

		if (!isset($_POST["chkacepto$i"]) && $valor==1) //cuando DE-seleccione una guia que anteriormente SI estaba seleccionada(valor=1)
		{
			//Consulta que no sea la última guia asociada al vuelo
			$sql="SELECT id FROM guia WHERE id_vuelo ='$id_vuelo' AND id_tipo_bloqueo='2'";
			$consulta=mysql_query($sql,$conexion) or die ('<font color="red" size="5">ATENCION: Error al consultar datos del Vuelo:'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>'.exit("sistema detenido"));			
			$nfilas=mysql_num_rows($consulta);
			if($nfilas == 1)
			{
				echo '<script language="javascript"> 
						alert("ERROR: No puede QUITAR la seleccion de todas las GUIAS."); 
						document.location="vuelo_manifiesto_seleccionar.php?vuelo='.$id_vuelo.'";
					</script>';
				exit();
			}
			else
				$contador++;;
			//*****************
			// Consulta que Ubiesen seleccionado almenos una de las guias
			if ($contador == 0)
			{
				echo '<script language="javascript"> 
						alert("ERROR: No puede eliminar TODAS las GUIA."); 
						document.location="vuelo_manifiesto_seleccionar.php?vuelo='.$id_vuelo.'";
					</script>';
				exit();
			}
		
			//1 Actualiza el estado de una guia asociada a este vuelo a un estado de De-seleccionada - Solo digitada
			$id_guia=$_POST["guia$i"];
			$sql="UPDATE guia SET id_tipo_bloqueo='1' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (mysql_error());
			
			//2 Ingresamos el evento al historial de la guia
			$sql_trak="INSERT INTO tracking (id_guia,
											 	evento,
										 		fecha_creacion,
										 		hora,
											 	tipo_tracking,
										 		id_usuario) 
												value ('$id_guia',
												   'QUITADA DEL MANIFIESTO',
												   '$fecha_creacion',
												   '$hora_registro',
												   '1',
												   '$id_usuario')";
			mysql_query($sql_trak,$conexion) or die (mysql_error());
		}
	}
	//**************************************************
	// Consulta que Ubiesen seleccionado almenos una de las guias
	if ($contador == 0)
	{
		echo '<script language="javascript"> 
				alert("ERROR: No selecciono ninguna GUIA."); 
				document.location="vuelo_manifiesto_seleccionar.php?vuelo='.$id_vuelo.'";
			</script>';
		exit();
	}
	
	
	//***************************************************
	$nmanifiesto=strtoupper($_POST["nmanifiesto"]);
	$fecha_manifiesto=$_POST["fecha_manifiesto"];
	$hora_manifiesto=$_POST["hh_manifiesto"].":".$_POST["mm_manifiesto"].":".$_POST["ss_manifiesto"];
	
	// Actualizar el vuelo
	$sql="UPDATE vuelo SET nmanifiesto='$nmanifiesto', fecha_manifiesto='$fecha_manifiesto', hora_manifiesto='$hora_manifiesto',estado='M',usuario_manifesto='$id_usuario' WHERE id='$id_vuelo'";
	mysql_query($sql,$conexion) or die (mysql_error());	
	//*************************
			
	echo '<script language="javascript"> 
			alert("Datos del Vuelo Actualizados de Manera Exitosa"); 
			document.location="base.php";
		</script>';
}
//******************************************
?>
