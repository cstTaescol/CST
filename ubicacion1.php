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
</head>
<body>
<?php 
require ("menu.php");
//Privilegios Consultar Todo el Modulo
$id_objeto=85; 
include("config/provilegios_modulo.php");  
//---------------------------
?>
<p class="titulo_tab_principal">Bodega</p>
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Ubicar</div></td>
    <td align="center" width="100" class="celda_tabla_principal">
    	<button type="button" name="ubicar" onclick="document.location='ubicacion_ubicar.php'">
        	<img src="imagenes/home-act.png" title="Ubicar"/>
        </button>
    </td>
    <td width="220" class="celda_tabla_principal celda_boton">Seleccione esta opci&oacute;n si desea dar ubicaci&oacute;n en Bodega a alguna de las gu&iacute;as del Inventario</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Consultar</div></td>
    <td align="center" class="celda_tabla_principal">
    	<button type="button" name="ubicar" onclick="document.location='ubicacion_seleccionar.php?accion=consultar'">
        	<img src="imagenes/buscar-act.png" title="Buscar" />
         </button>
    </td>
    <td class="celda_tabla_principal celda_boton">Consultar el Mapa de la Bodega o la Ubicaci&oacute;n de una Gu&iacute;a.</td>
  </tr>
</table>
</body>
</html>
