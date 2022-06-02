<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$i=0;
$id_despacho="";
$fecha_registrada="";
$hora_registrada="";
$hija="";
$id_aerolinea="";
$agencia="";
$piezas="";
$peso="";
$estado_botones='disabled="disabled"';
$mensaje1="";
if (isset($_REQUEST["ndespacho"]))
{
	$id_despacho=$_REQUEST["ndespacho"];
	$sql="SELECT * FROM descargue_directo WHERE id = '$id_despacho' AND estado='A'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		if ($fila["cumplido_nombre"] != "")	//Cuando ya se ha reportado el cumplido
		{
			$mensaje1="<p align=\"center\"><font color=\"green\" size=\"-1\">ESTE DESPACHO YA TIENE ASOCIADO UN CUMPLIDO</font></p>";
		}
		else
			{
				$estado_botones='';
			}
		
		$fecha_registrada=$fila["fecha"];
		$hora_registrada=$fila["hora"];
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		$agencia=$fila["agencia"];
		$id_guia=$fila["id_guia"];

		//carga datos
		$sql2="SELECT hija, id_aerolinea FROM guia WHERE id='$id_guia'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 2". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$hija=$fila2['hija'];
		$id_aerolinea=$fila2['id_aerolinea'];
		
		//carga datos
		$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 3". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$id_aerolinea=$fila2['nombre'];
	}	
}
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
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>
        <form method="post" name="consulta" id="consulta" action="#" >
            Descargue Directo No.<input type="text" name="ndespacho" id="ndespacho" /><input type="submit" value="Buscar" /><br />
        	<p align="center">Ingrese el n&uacute;mero del Descargue Directo al que desea reportar el Cumplido.</p>
        </form>
    </td>
  </tr>
</table>
<script language="javascript">
	document.getElementById('ndespacho').focus();
</script>
</p>
<p>
<form method="post" name="registro" action="cumplido_ddirecto2.php"  enctype="multipart/form-data">
<table width="50%" border="1" align="center" bordercolor="#0066CC">
  <tr>
    <td colspan="2">
        <p align="center"><font color="red" size="+1">DESCRIPCION DEL DESPACHO</font>
        <hr />
        <?php echo $mensaje1 ?>
        <font color="blue"><strong>DESPACHO No:</strong></font><?php echo $id_despacho ?>
        <br>
        <font color="blue"><strong>FECHA:</strong></font> <?php echo $fecha_registrada ?><br>
        <font color="blue"><strong>HORA:</strong></font> <?php echo $hora_registrada ?><br>
        <input type="hidden" name="id_despacho" value="<?php echo $id_despacho ?>">
    </td>
  </tr>
  <tr>
    <td colspan="2">
    	<strong><font color="blue">No. GUIA:</font></strong>:<?php echo $hija ?><br />
        <strong><font color="blue">AEROLINEA:</font></strong>:<?php echo $id_aerolinea ?><br />
        <strong><font color="blue">AGENCIA:</font></strong>:<?php echo $agencia ?><br />
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="middle" bgcolor="#999999"><font color="#FFFFFF"><strong>CANTIDAD DEL DESPACHO</strong></font></td>
  </tr>
  <tr>
    <td>
      <p><strong><em>PIEZAS</em></strong><em>:</em><?php echo $piezas ?></p>
      </td>
    <td>
      <p><strong><em>PESO</em></strong>:<?php echo $peso ?></p>
      </td>
  </tr>
  <tr>
    <td colspan="2" align="center"><p>&nbsp;
      <button type="button" onClick="abrir('pdf_descargue_directo.php?id_ddirecto=<?php echo $id_despacho ?>')" <?php echo $estado_botones?>>
      	<img src="imagenes/pdf.jpg" width="69" height="68" title="Generar archivo PDF" />
      </button>
    	<button type="button" tabindex="3" onclick="document.location='cumplidos_seleccionar.php'"><img src="imagenes/back.jpg" width="69" height="68" alt="Anterior" /></button>
		<button type="submit" tabindex="2"  <?php echo $estado_botones?>><img src="imagenes/next.jpg" width="69" height="68" alt="Sigueinte" /></button>
    </td>
  </tr>
</table>
</form>
</p>
</body>
</html>