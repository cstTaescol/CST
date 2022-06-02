<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$id_despacho=$_GET["id_ddirecto"];
$sql="SELECT cumplido_nombre, cumplido_documento, cumplido_foto FROM descargue_directo WHERE id = '$id_despacho' AND estado='A'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$cumplido_nombre=$fila["cumplido_nombre"];
$cumplido_documento=$fila["cumplido_documento"];
$cumplido_foto=$fila["cumplido_foto"];

if ($cumplido_nombre == "")
{
	$cumplido_nombre="NO SE HA REGISTRADO";
	$cumplido_documento="NO SE HA REGISTRADO";
	$cumplido_foto="imagen_no_disponible.jpg";
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CUMPLIDO</title>
<style type="text/css">
<!--
.subtitulos {
	color: #FFF;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<br />
<table width="520" height="302" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr bgcolor="#003366">
    <td colspan="2" align="center" valign="middle" bgcolor="#003366"><p><font size="+2" color="#0066FF"><strong>DATOS DE CUMPLIDO</strong></font></p>
      <span class="subtitulos">DESCARGUES DIRECTOS</span></td>
  </tr>
  <tr>
    <td width="360" bgcolor="#666666" class="subtitulos">NOMBRE:</td>
    <td width="160" rowspan="4" align="center" valign="middle"><a href="fotos/cumplidos/<?php echo $cumplido_foto ?>"><img src="fotos/cumplidos/<?php echo $cumplido_foto ?>" width="150" height="150" alt="Imagen persona que recibe la carga" /></a></td>
  </tr>
  <tr bgcolor="#0033CC">
    <td height="57" bgcolor="#003366" class="subtitulos">&nbsp;&nbsp;&nbsp;<?php echo $cumplido_nombre ?></td>
  </tr>
  <tr bgcolor="#003366">
    <td bgcolor="#666666" class="subtitulos">DOCUMENTO:</td>
  </tr>
  <tr bgcolor="#003366">
    <td bgcolor="#003366" class="subtitulos">&nbsp;&nbsp;&nbsp;<?php echo $cumplido_documento ?></td>
  </tr>
</table>
</body>
</html>