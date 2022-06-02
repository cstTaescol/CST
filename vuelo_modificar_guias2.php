<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$contador = 0;
$cantidadguias=$_POST["cantidadguias"];
$id_vuelo=$_POST["id_vuelo"];
$hora=date("H:i:s");
$id_usuario=$_SESSION['id_usuario'];
$fecha=date("Y").date("m").date("d");

//datos del vuelo
$sql3="SELECT nvuelo,id_aerolinea,id_ruta,hora_estimada,matricula FROM vuelo WHERE id='$id_vuelo'";
$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila3=mysql_fetch_array($consulta3);
$nvuelo=$fila3["nvuelo"];
$aerolinea=$fila3["id_aerolinea"];
$id_ruta=$fila3["id_ruta"];
$hora_estimada=$fila3["hora_estimada"];
$matricula=$fila3["matricula"];


//1. Actualización de datos en cada una de las guias.
for ($i=1; $i<=$cantidadguias; $i++)
{
	$id_guia=$_POST["guia$i"];
	$valor=$_POST["valor$i"]; // valor que contiene 0 o 1 e indica que originalmente estaba o no asignada a un vuelo
	$nombrechek="chkacepto".$i;
	if (isset($_POST["$nombrechek"]) && $valor==0) //cuando seleccione una guia que anteriormente NO estaba seleccionada(valor=0)
		{			
			$contador++;
			//Carga datos de la guia Master
			$sql2="SELECT master FROM guia WHERE id='$id_guia'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR MASTER: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila2=mysql_fetch_array($consulta2);
			$id_master=$fila2['master'];
			
			if ($id_master != "")
			{
				$sql="UPDATE guia SET id_tipo_bloqueo='7', id_vuelo='$id_vuelo' WHERE id='$id_master'";
				mysql_query($sql,$conexion) or die ('<font color="red" size="5">ATENCION: Error al ingresar la Gu&iacute;a MASTER:'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>');
			}

			$sql="UPDATE guia SET id_tipo_bloqueo='7', id_vuelo='$id_vuelo' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die ('<font color="red" size="5">ATENCION: Error al modificar la Gu&iacute;a:'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>'.exit("sistema detenido"));
			//2. almacenamiento del traking
			$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('$id_guia','$fecha','$hora','GUIA ASIGNADA AL VUELO: $nvuelo','2','$id_usuario')";
			mysql_query($sql_trak,$conexion) or die (mysql_error());
		}

	if (!isset($_POST["$nombrechek"]) && $valor==1) //cuando DE-seleccione una guia que anteriormente SI estaba seleccionada(valor=1)
		{	
			//Consulta que no sea la última guia asociada al vuelo
			$sql="SELECT id FROM guia WHERE id_vuelo ='$id_vuelo'";
			$consulta=mysql_query($sql,$conexion) or die ('<font color="red" size="5">ATENCION: Error al modificar la consultar guias del vuelo:'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>'.exit("sistema detenido"));			
			$nfilas=mysql_num_rows($consulta);
			if($nfilas == 1)
			{
				echo '<script language="javascript"> 
						alert("ERROR: No puede QUITAR la seleccion de todas las GUIAS."); 
						document.location="vuelo_modificar_guias1.php?id_vuelo='.$id_vuelo.'&nvuelo='.$nvuelo.'&aerolinea='.$aerolinea.'&ruta='.$id_ruta.'&matricula='.$matricula.'&hora_estimada='.$hora_estimada.'";
					</script>';
				exit();
			}
			else
				$contador++;;
			//*****************
			
			$sql="UPDATE guia SET id_tipo_bloqueo='1', id_vuelo='' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die ('<font color="red" size="5">ATENCION: Error al modificar la Gu&iacute;a:'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>'.exit("sistema detenido"));
			//2. almacenamiento del traking
			$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('$id_guia','$fecha','$hora','GUIA RETIRADA DEL VUELO:$nvuelo','2','$id_usuario')";
			mysql_query($sql_trak,$conexion) or die (mysql_error());
			
			//Carga datos de la guia Master
			$sql2="SELECT master FROM guia WHERE id='$id_guia'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR MASTER: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila2=mysql_fetch_array($consulta2);
			$id_master=$fila2['master'];
						
			//Calcula la cantidad de guias asociadas a este consolidado que estan asociadas a un vuelo o no. se determinará liberar o no este master.
			$sql2="SELECT id FROM guia WHERE master='$id_master' AND id_vuelo !='' ";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR MASTER: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$nmaster=mysql_num_rows($consulta2);
			if ($nmaster == 0)
			{
				$sql="UPDATE guia SET id_tipo_bloqueo='1' WHERE id='$id_master'";
				mysql_query($sql,$conexion) or die ('<font color="red" size="5">ATENCION: Error al ingresar la Gu&iacute;a MASTER:'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>');
			}
		}
}
//Comprueba que ubiesen seleccionado alguna guia
if ($contador == 0)
{
	//Redirecciona al paso anterior
	echo '<script language="javascript"> 
			alert("ERROR: No Seleccion ninguna GUIA."); 
			document.location="vuelo_modificar_guias1.php?id_vuelo='.$id_vuelo.'&nvuelo='.$nvuelo.'&aerolinea='.$aerolinea.'&ruta='.$id_ruta.'&matricula='.$matricula.'&hora_estimada='.$hora_estimada.'";
		</script>';
	exit();
}
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
    	<div class="msg"><br />Vuelo Modificado de Manera Exitosa.</div>
        <meta http-equiv="Refresh" content="2;url=base.php">
    </body>
</html>

