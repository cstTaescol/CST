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
<?php require("menu.php"); ?>
<p class="titulo_tab_principal">Eliminando...<img src="../Simec/img/cargando.gif" width="96" height="16" /></p>
<?php 
if(isset($_GET['tipo']))
{
	unlink($_GET['ruta']) or die (exit("Error."));	
	$tipo=$_GET['tipo'];
	switch ($tipo)
	{
		case 'PLANILLA_CARGUE':
			$rangoini=$_GET['parametro1'];
			$rangofin=$_GET['parametro2'];
			$id_registro=$_GET['id'];			
			$sql="DELETE FROM planilla_cargue WHERE id = '$id_registro'";
			mysql_query ($sql,$conexion) or die ("ERROR 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");					
			echo '<meta http-equiv="Refresh" content="2;url=upload_consulta_x_fecha.php?rangoini='.$rangoini.'&rangofin='.$rangofin.'&tipo=PLANILLA_CARGUE">';			
		break;

		case 'PLANILLA_DESPALETIZAJE':
			$rangoini=$_GET['parametro1'];
			$rangofin=$_GET['parametro2'];
			$id_registro=$_GET['id'];			
			$sql="DELETE FROM planilla_despaletizaje WHERE id = '$id_registro'";
			mysql_query ($sql,$conexion) or die ("ERROR 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");					
			echo '<meta http-equiv="Refresh" content="2;url=upload_consulta_x_fecha.php?rangoini='.$rangoini.'&rangofin='.$rangofin.'&tipo=PLANILLA_DESPALETIZAJE">';			//pendiente
		break;
	}	
}
?>
</body>
