<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if (isset($_POST["id_guia"]))
{
	$id_guia=$_POST["id_guia"];
	$sql="SELECT * FROM guia WHERE id='$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
	$fila=mysql_fetch_array($consulta) or die (exit('Error '.mysql_error()));
	$id_aerolinea=$fila['id_aerolinea'];
	$piezas=$fila['piezas'];
	$peso=$fila['peso'];
	$id_aerolinea=$fila['id_aerolinea'];
	
	//recuperando datos de aerolinea
	$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
	$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error '.mysql_error()));
	$fila2=mysql_fetch_array($consulta2);
	$aerolinea=$fila2['nombre'];
}
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
</p>
<p align="center"><strong><font size="+2" color="#0066FF">DESPACHO GUIAS HALL</font></strong><img src="imagenes/down_arrow_select.jpg" width="52" height="52" align="absmiddle" /></p>
<hr />
<table width="70%" border="2" cellspacing="2" cellpadding="2"  bordercolor="#0099CC" align="center">
  <tr>
    <td width="16%" bgcolor="#D6D6D6"><strong>AEROLINEA</strong></td>
    <td width="84%" bgcolor="#D6D6D6"><?php echo $aerolinea ?></td>
  </tr>
  <tr>
    <td bgcolor="#D6D6D6"><strong>PIEZAS</strong></td>
    <td bgcolor="#D6D6D6"><?php echo $piezas ?></td>
  </tr>
  <tr>
    <td bgcolor="#D6D6D6"><strong>PESO</strong></td>
    <td bgcolor="#D6D6D6"><?php echo $peso ?></td>
  </tr>
</table>
<hr />
<hr />
<p align="center"><strong><font size="+1" color="#0000CC"><em><u>DATOS ADICIONALES</u></em></font></strong></p>
<form name="complementarios" method="post" action="despacho_hall3.php" onsubmit="return validar();">
	<table width="70%" border="2" cellspacing="2" cellpadding="2" align="center" bordercolor="#CC0000">
	  <tr>
	    <td width="16%" bgcolor="#D6D6D6"><strong>DESCRIPCION:</strong></td>
	    <td bgcolor="#D6D6D6" align="center"><textarea id="descripcion" name="descripcion" cols="40" rows="5"></textarea></td>
	  </tr>
	</table>
	<table width="70%" border="2" cellspacing="2" cellpadding="2" align="center" bordercolor="#CC0000">
	    <tr>
	        <td align="center"  bgcolor="#D6D6D6">
	        	<button type="submit" name="guardar" id="guardar"  tabindex="6">
	        		<img src="imagenes/save.jpg" width="59" height="59" /><br />Guardar
	            </button>
	        	<button type="reset" name="reset" id="reset" tabindex="7"> 
	        		<img src="imagenes/limpiar.jpg" width="59" height="59" /><br />Limpiar
	            </button>
	        	<button type="button" name="cancelar" id="cancelar" onclick="document.location='base.php'" tabindex="8">
	        		<img src="imagenes/error.png" width="59" height="59" /><br />Cancelar
	            </button>                                                
	          </td>
	    </tr>
	</table>
	<input type="hidden" name="id_guia" value="<?php echo $id_guia ?>" />
</form>
</body>
</html>
<script language="javascript">
// funcion para validar
function validar()
{
	if (document.forms[0].descripcion.value=="")
	{
		alert("Atencion: Debe digitar una DESCRIPCION");
		document.forms[0].descripcion.focus();
		return(false);
	}
}
</script>