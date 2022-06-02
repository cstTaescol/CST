<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_guia=$_REQUEST['id_guia'];
$tipo=$_REQUEST['tipo'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<font size="+2" color="#0066FF"><strong>REGISTRO FOTOGRAFICO</strong></font> <img src="imagenes/camara.jpg" width="50" height="50" align="absmiddle" />
<hr />
<p>
<form method="post" name="registro" id="registro" onsubmit="return validar();" action="registro_fotografico_subir_guardar.php"  enctype="multipart/form-data">
<table width="50%" border="1" align="center" bordercolor="#0066CC">
  <tr>
    <td>
    	<p><font color="blue"><strong>FOTO DE LA MERCANCIA</strong></font></p><p align="center">
    	<input name="scan" id="scan" type="file" size="15" /></p><br>
    	<input type="hidden" name="id_guia" value="<?php echo $id_guia ?>" />
    	<input type="hidden" name="tipo" value="<?php echo $tipo ?>" />
    </td>
  </tr>
  <tr>
    <td align="center"><input type="reset" value="Limpiar"><input type="submit" value="Guardar" ></td>
  </tr>
</table>
</form>
</p>
</body>
</html>
<script language="javascript">
	// funcion para validar
	function validar()
	{	
		if (document.registro.scan.value=="")
		{
			alert("Atencion: Se requiere que Adjunte el  ARCHIVO");
			return(false);
		}
	}
</script>	
