<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$contador = 0;
$cantidadguias=$_POST["cantidadguias"];
$id_aerolinea=$_POST["id_aerolinea"];
$ruta=$_POST["ruta"];
$nvuelo=$_POST["nvuelo"];
$matricula=$_POST["matricula"];
$hora_estimada=$_POST["hora_estimada"];
$cod_ciudad_embarcadora=$_POST['cod_ciudad_embarcadora'];
$pais_origen=$_POST['pais_origen'];

$hora_estimada2=explode(":",$hora_estimada);
$hora=date("H:i:s");
$id_usuario=$_SESSION['id_usuario'];
$fecha=date("Y").date("m").date("d");

//1. almacenamiento de los datos en la tabla del vuelo
$sql="INSERT INTO vuelo (fecha_creacion,
						 hora,
						 id_usuario,
						 id_aerolinea,
						 id_ruta,
						 nvuelo,
						 matricula,
						 hora_estimada,
						 cod_pais_embarque,
						 cod_ciudad_embarque
						 ) value (
						 '$fecha',
						 '$hora',
						 '$id_usuario',
						 '$id_aerolinea',
						 '$ruta',
						 '$nvuelo',
						 '$matricula',
						 '$hora_estimada',
						 '$pais_origen',
						 '$cod_ciudad_embarcadora')";
mysql_query($sql,$conexion) or die ('<font color="red" size="5">ATENCION: Error al ingresar la Gu&iacute;a:'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>');
$id_vuelo = mysql_insert_id($conexion); //obtiene el id de la ultima inserción		

//2. Actualización de datos en cada una de las guias.
for ($i=1; $i<=$cantidadguias; $i++)
{
	$nombrechek="chkacepto".$i;
	if (isset($_POST["$nombrechek"]))
		{
			$id_guia=$_POST["$nombrechek"];
			$contador++;
			//Carga datos de la guia Master
			$sql2="SELECT master FROM guia WHERE id='$id_guia'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR MASTER: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila2=mysql_fetch_array($consulta2);
			$id_master=$fila2['master'];
			
			if ($id_master != "")
			{
				$sql="UPDATE guia SET id_tipo_bloqueo='7', id_vuelo='$id_vuelo', cod_ciudad_embarque='$cod_ciudad_embarcadora', cod_pais_embarque='$pais_origen' WHERE id='$id_master'";
				mysql_query($sql,$conexion) or die ('<font color="red" size="5">ATENCION: Error al ingresar la Gu&iacute;a MASTER:'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>');
			}
			
			$sql="UPDATE guia SET id_tipo_bloqueo='7', id_vuelo='$id_vuelo', cod_ciudad_embarque='$cod_ciudad_embarcadora', cod_pais_embarque='$pais_origen' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die ('<font color="red" size="5">ATENCION: Error al ingresar la Gu&iacute;a:'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>');

			//3. almacenamiento del traking
			$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('$id_guia','$fecha','$hora','GUIA ASIGNADA AL VUELO: $nvuelo','2','$id_usuario')";
			mysql_query($sql_trak,$conexion) or die (mysql_error());
		}
}
if ($contador == 0)
{
	//Borrar el vuelo creado si no se registró ninguna guia para asignarle
	$sql="DELETE FROM vuelo WHERE id='$id_vuelo'";
	mysql_query($sql,$conexion);
	//***************************************
	
	//Redirecciona al paso anterior
	echo '<script language="javascript"> 
			alert("ERROR: No Seleccion ninguna GUIA."); 
			document.location="vuelo_nuevo2.php?hh_estimada='.$hora_estimada2[0].'&mm_estimada='.$hora_estimada2[1].'&ss_estimada='.$hora_estimada2[2].'&aerolinea='.$id_aerolinea.'&ruta='.$ruta.'&nvuelo='.$nvuelo.'&matricula='.$matricula.'";
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
    	<div class="msg"><br />Guias Asignadas de Manera Exitosa.</div>
        <meta http-equiv="Refresh" content="2;url=base.php">
    </body>
</html>
