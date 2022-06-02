<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
//Discriminacion de aerolinea de usuario TIPO 2
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id_aerolinea = '$id_aerolinea_user'";	
//*************************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript">
//Validacion de campos numéricos
function numeric2(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9-.]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
$id_objeto=97;
include("config/provilegios_modulo.php");
?>
<p align="center"><strong><font size="+2" color="#0066FF">DESPACHO GUIAS HALL</font></strong><img src="imagenes/down_arrow_select.jpg" width="52" height="52" align="absmiddle" /></p>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
  <table width="40%" height="75" border="1" cellpadding="0" cellspacing="0" align="center" bordercolor="#0099CC">
  <tr>
    <td width="138" rowspan="3" align="center"><strong>BUSCAR GUIA</strong></td>
    <td height="21" align="center" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="81" rowspan="3" align="center" valign="middle"><button type="submit" name="buscar"><img src="imagenes/lupa.jpg" width="50" align="absmiddle" /> Buscar</button></td>
  </tr>
  <tr>
    <td height="31" align="center" bgcolor="#0066FF"><input name="guia" type="text" id="guia" size="30" /></td>
    <script type="text/javascript">
		document.forms[0].guia.focus();
	</script>
  </tr>
  <tr>
    <td width="299" height="21">&nbsp;</td>
  </tr>
</table>
</form>
<?php
$tablas="";
if (isset($_POST["guia"]))
{
	$guia=$_POST["guia"];
	$sql="SELECT * FROM guia WHERE hija LIKE '%$guia%' AND id_tipo_bloqueo='11' $sql_aerolinea ORDER BY id_aerolinea ASC";
}
else
{
	$sql="SELECT * FROM guia WHERE id_tipo_bloqueo='11' $sql_aerolinea ORDER BY id_aerolinea ASC";
}
$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
while($fila=mysql_fetch_array($consulta))
{
	include("config/evaluador_inconsistencias.php");
	$peso=$peso-$fila["peso_despacho"];
	$piezas_sinbloqueo=$piezas-$fila['piezas_despacho'];
	$volumen=$volumen -$fila["volumen_despacho"];
	$piezas=$piezas-$fila["piezas_despacho"];			
	$master=$fila['master'];
	require("config/master.php");
	
	//recuperando datos de consignatario		
	$id_aerolinea=$fila['id_aerolinea'];
	$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
	$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error '.mysql_error()));
	$fila2=mysql_fetch_array($consulta2);
	$id_aerolinea=$fila2['nombre'];
	$id_guia=$fila['id'];
	$tablas=$tablas.'
	<form method="post" action="despacho_hall2.php">
	<tr>
			<td bgcolor="#FFFFFF">
			<button type="submit"><img src="imagenes/chek-green.jpg" width="30"/></button>
			</td>
			<td bgcolor="#FFFFFF">'.$master.'</td>
			<td bgcolor="#FFFFFF"><a href="consulta_guia.php?id_guia='.$id_guia.'">'.$fila['hija'].'</a></td>
			<td bgcolor="#FFFFFF">'.$piezas.'</td>
			<td bgcolor="#FFFFFF">'.$peso.'</td>
			<td bgcolor="#FFFFFF">'.$id_aerolinea.'</td>
			<input name="id_guia" type="hidden" value="'.$id_guia.'" />
	</tr>
	</form>
	';
}
?>
<table width="100%" border="1" cellspacing="8" cellpadding="8">
  <tr>
    <td height="66" bgcolor="#0066FF">
    <table width="80%" border="1" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td width="5%" bgcolor="#00CC00">&nbsp;</td>
        <td width="17%" align="center" bgcolor="#CCCCCC"><strong>CONSOLIDADO</strong></td>
        <td width="19%" align="center" bgcolor="#CCCCCC"><strong>GUIA</strong></td>
        <td width="7%" align="center" bgcolor="#CCCCCC"><strong>PIEZAS</strong></td>
        <td width="8%" align="center" bgcolor="#CCCCCC"><strong>PESO</strong></td>
        <td width="33%" align="center" bgcolor="#CCCCCC"><strong>AEROLINEA</strong></td>
      </tr>
      <?php echo $tablas ?>
    </table>
    </td>
  </tr>
</table>
</body>
</html>