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
<script language="javascript">
function abrir(url)
{
	popupWin = window.open(url,'','directories, status, scrollbars, resizable, dependent, width=640, height=480, left=100, top=100')
	//  popupWin = window.open('pdf_remesa.php','nombre_ventana','menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
}
</script>
</head>

<body>
<?php require("menu.php"); ?>
<p align="center"><font size="+2" color="#0066FF"><strong>REPORTAR CUMPLIDOS</strong></font> <img src="imagenes/sello.gif" width="50" height="50" align="absmiddle" />
</p>
<table width="607" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="65"><button name="terminar" type="button" onclick="document.location='cumplido_remesa1.php';" <?php  $id_objeto=78; include("config/provilegios_objeto.php");  echo $activacion ?>><img src="imagenes/chek-blue.jpg" width="45" height="43" title="Reportar Cumplido de Remesa" /></button></td>
    <td width="536">Reportar Cumplido de Remesa</td>
  </tr>
  <tr>
    <td><button name="terminar" type="button" onclick="document.location='cumplido_ddirecto1.php'" <?php  $id_objeto=79; include("config/provilegios_objeto.php");  echo $activacion ?>><img src="imagenes/chek-orange.jpg" width="45" height="43" alt="Reportar Cumplido para Descargues Directos" /></button></td>
    <td>Reportar Cumplido para Descargues Directos</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><button name="terminar" type="button" onclick="document.location='base.php'"> <img src="imagenes/error.png" width="45" height="43" alt="Cancelar" /></button></td>
    <td>Cancelar</td>
  </tr>
</table>
</body>
</html>