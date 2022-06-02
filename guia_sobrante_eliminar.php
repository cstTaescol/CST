<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_sobrante=$_REQUEST['id_sobrante'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GUIA SOBRANTE ELIMINAR</title>
</head>
<body>
<p align="center">Espere, estamos procesando su informaci&oacute;n </p>
<p align="center"><img src="imagenes/cargando.gif" width="20" height="21" alt="Cargando" /></p>
<?php
$sql="UPDATE despaletizaje_sobantes SET estado='I' WHERE id='$id_sobrante'";
mysql_query($sql,$conexion) or die ("Error al Eliminar". mysql_error());
echo '<script language="javascript">
			alert ("Datos Anulados de Manera Exitosa");
			window.opener.location.reload();
			self.close();
		</script>';
?>
</body>
</html>
