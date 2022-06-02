<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
?>
<div id="cargando"><p align="center"><img src="imagenes/cargando.gif" width="20" height="21" /><br>Procesando</p></div>
<?php
if (isset($_REQUEST['id_usuario_modificar']))
{
	$id_usuario_modificar=$_REQUEST['id_usuario_modificar'];
	$sql="SELECT estado FROM usuario WHERE id = $id_usuario_modificar";
	$consulta=mysql_query($sql,$conexion);
	$fila=mysql_fetch_array($consulta);
	$estadoactual=$fila['estado'];
	if ($estadoactual == "A")
		$estadonuevo="I";
	else
		$estadonuevo="A";
	
	$sql="UPDATE usuario SET estado='$estadonuevo' WHERE id = '$id_usuario_modificar'";
	mysql_query($sql,$conexion) or die (exit("ERROR AL MODIFICAR EL USUARIO ".mysql_error()));
	echo "
		<script>
			alert('Usuario modificado satisfactoriamente.');
			document.location='usuario_lista.php';
		</script>
	";													 
}
else
{
	echo "
		<script>
			alert('ERROR:El servidor no pudo recibir todos los datos, intente nuevamente.');
			document.location='usuario_lista.php';
		</script>
	";
}
?>