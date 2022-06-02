<?php session_start(); /*     "This product includes PHP software, freely available from
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
<?php include("menu.php");?>
<p align="center"><font size="+3"><strong>PRE-INSPECCION</strong></font></p>
<form name="buscar" method="post" action="preinspeccion_validar1.php">
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" align="right" bgcolor="#3366CC"><input type="submit" value="Buscar" /></td>
    <td width="500" bgcolor="#3366CC"><input name="registro" id="registro" type="text" size="50" tabindex="1"  /></td>
    <script language="javascript">
		document.getElementById("registro").focus();
	</script>
  </tr>
  <tr>
    <td height="37" colspan="2" align="right" bgcolor="#FFFFFF">Ingrese el C&oacute;digo del la Pre-inspeccion que va a Validar.</td>
    </tr>
</table>
</form>

</body>
</html>