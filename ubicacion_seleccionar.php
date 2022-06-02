<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$accion=$_REQUEST["accion"];
switch ($accion)
{
	case "consultar":
		$evento="consulta";
	break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php 
include ("menu.php");
?>
<p class="titulo_tab_principal">Mapa de Bodega</p>
<table width="400" border="0" align="center">
  <tr>
    <td width="200" height="175" align="center" valign="middle" class="celda_tabla_principal">
    	<button type="button" name="ubicar" onclick="document.location='ubicacion_seleccionar_bodega.php?evento=<?php echo $evento ?>'">
        	<img src="imagenes/home-act.png"/>
         </button>
   		<p class="asterisco">Ver Mapa</p>
    </td>
    <td width="200" align="center" valign="middle" class="celda_tabla_principal">
		<button type="button" name="ubicar" onclick="document.location='ubicacion_guias_ubicadas.php?evento=<?php echo $evento ?>'">
        	<img src="imagenes/buscar-act.png"/>
        </button>
    	<p class="asterisco">Identificar Guia</p>
    </td>
  </tr>
</table>
</body>
</html>
