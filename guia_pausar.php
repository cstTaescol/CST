<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<link href="tema/estilo.css" rel="stylesheet" type="text/css" />
<?php
if (isset($_GET["id_guia"]))
{
	$id_guia=$_GET["id_guia"];
	$id_usuario=$_SESSION['id_usuario'];
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");

	$sql="SELECT id_tipo_bloqueo,id_tipo_bloqueo_anterior FROM guia WHERE id='$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 01 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$bloqueo_actual=$fila['id_tipo_bloqueo'];
	$bloqueo_anterior=$fila['id_tipo_bloqueo_anterior'];
	
	if ($bloqueo_actual == 11) //ENVIAN UN DES-PAUSADO
	{
		$bloqueo_activo=$bloqueo_anterior;
		$bloqueo_buffer='NULL';
		$msg_tracking="GUIA DES-PAUSADA";
	}
	else
		{
			$bloqueo_activo=11;
			$bloqueo_buffer=$bloqueo_actual;
			$msg_tracking="GUIA PAUSADA";			
		}


	//1. almacenamiento de los datos en la tabla de guia
	$sql="UPDATE guia SET id_tipo_bloqueo=$bloqueo_activo,
							id_tipo_bloqueo_anterior=$bloqueo_buffer
							WHERE id='$id_guia'"; 
	mysql_query($sql,$conexion) or die (exit("Error 02 ".mysql_error(). " INFORME AL SOPORTE TECNICO"));
	
	//2. almacenamiento del traking
	$sql_trak="INSERT INTO tracking (id_guia,
									 fecha_creacion,
									 hora,
									 evento,
									 tipo_tracking,
									 id_usuario) 
										value ('$id_guia',
											   '$fecha',
											   '$hora',
											   '$msg_tracking',
											   '1',
											   '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die (exit("Error".mysql_error()));


	//3. Aviso de Guardado Exitoso
	echo '<div class="msg">Guia Procesada</div>';
	
	echo '<meta http-equiv="Refresh" content="1;url=consulta_guia.php?id_guia='.$id_guia.'">';
}
else
{
	echo '<div class="msg">Error al Obtener la Guia</div>';
	echo '<meta http-equiv="Refresh" content="1;url=consulta_guia.php?id_guia='.$id_guia.'">';
}
?>