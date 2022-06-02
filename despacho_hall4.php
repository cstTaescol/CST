<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_registro=$_REQUEST["id_registro"];
?>
<html>
<head>
<script language="javascript">
function abrir(url)
{
	popupWin = window.open(url,'','directories, status, scrollbars, resizable, dependent, width=640, height=480, left=100, top=100')
	//  popupWin = window.open('pdf_remesa.php','nombre_ventana','menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
}
//Funcion para confirmar la eliminacion
function conf_eliminar(url)
{
var respuesta=confirm('ATENCION: Confirma que Desea Eliminar el DESPACHO?,  Las GUIAS despachadas seran Retornadas al INVENTARIO');
if (respuesta)
	{
		window.location="eliminar_hall1.php?id_registro="+url;
	}
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p align="center"><strong><font size="+2" color="#0066FF">DESPACHO GUIAS HALL</font></strong><img src="imagenes/down_arrow_select.jpg" width="52" height="52" align="absmiddle" /></p>
<p>Despacho Identificado con exito... N&uacute;mero: <font size="+2" color="006600"><strong><?php echo $id_registro; ?></strong></font></p>
<p>Ahora puede proceder a imprimir el documento de despacho.</p>
<p>
<button name="terminar" type="button" onClick="document.location='base.php'">
	<img src="imagenes/chek-gris.jpg" width="49" height="49" alt="Terminar" /><br />
  <strong>Terminar</strong>
</button>
<button name="modificar" type="button" onClick="document.location='modificar_hall1.php?id_registro=<?php echo $id_registro; ?>'">
	<img src="imagenes/kservices.png" width="49" height="49" alt="Modificar" /><br />
  <strong>Modificar</strong>
</button>
<button name="eliminar" type="button" onClick="conf_eliminar(<?php echo $id_registro; ?>);">
	<img src="imagenes/error.png" width="49" height="49" alt="Eliminar" /><br />
  <strong>Eliminar</strong>
</button>
<button name="imprimir" type="button" onClick="abrir('pdf_hall.php?id_registro=<?php echo $id_registro ?>')">
	<img src="imagenes/imprimir.jpg" width="89" height="88" alt="Imprimir" /><br />
  <strong>Imprimir</strong>
</button>
</p>
<p>&nbsp;</p>
</body>
</html>