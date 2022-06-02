<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");


if(isset($_REQUEST['id_guia']) and isset($_REQUEST['tipo']) and isset($_REQUEST['id_registro']) and isset($_REQUEST['nombre']))
{
	$tipo=$_REQUEST['tipo'];
	$id_guia=$_REQUEST['id_guia'];
	$id_registro=$_REQUEST['id_registro'];
	$nombre=$_REQUEST['nombre'];
	$id_usuario=$_SESSION['id_usuario'];
	$fecha_creacion=date("Y").date("m").date("d");
	$hora_registro=date("H:i:s");
	
	//Elimina registro
	$sql="DELETE FROM registro_fotografico WHERE id='$id_registro'";
	mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	
	$ruta="fotos/mercancia/".$nombre;
	//Elimina archivo
	if(file_exists($ruta))
	{
		if(unlink($ruta))
		{
			echo "El archivo fue borrado SATISFACTORIAMENTE";
		}
		else 
		{
			echo "ERROR:No se pudo borrar el archivo";
		}
	} 
	else {
		echo "ERROR:No Existe el archivo";
	}
	
	//Crear el Tracking
	$sql_trak="INSERT INTO tracking (id_guia,
									fecha_creacion,
									hora,
									evento,
									tipo_tracking,
									id_usuario) 
									value ('$id_guia',
											'$fecha_creacion',
											'$hora_registro',
											'FOTO ELIMINADA: $nombre',
											'1',
											'$id_usuario')";
	mysql_query($sql_trak,$conexion) or die (mysql_error());
	header("Location: registro_fotografico_eliminar.php?id_guia=$id_guia&tipo=$tipo");
	}
else
{
	?>
    <script>
		alert('Error: El servidor no pudo obtener la informacion, intentelo de nuevo');
		window.close();
	</script>
    <?php
    exit();
}
?>



