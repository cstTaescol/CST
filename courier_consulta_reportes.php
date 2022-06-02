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
  require("menu.php");
?>
<p class="titulo_tab_principal">Generador de Reportes</p>

<table width="600" align="center" class="decoracion_tabla">
  <tr>
    <td width="70" class="celda_tabla_principal">
	<button name="personalizado" type="button" onclick="document.location='courier_reporteador1.php'" <?php  $id_objeto=149; include("config/provilegios_objeto.php");  echo $activacion ?>>
    	<img src="imagenes/aceptar-act.png" width="45" height="43" title="Generar Reportes Personalizados" />
    </button>
    </td>
    <td width="530" class="celda_tabla_principal celda_boton">Reporte Personalizado</td>
  </tr>
  <tr>
    <td width="70" class="celda_tabla_principal">
        <button name="transportador" type="button" onclick="document.location='courier_consulta_reportes_aBodega1.php'" <?php  $id_objeto=150; include("config/provilegios_objeto.php");  echo $activacion ?>>
            <img src="imagenes/gohome.png" width="45" height="43" title="Analisis mensual de la Bodega" />
        </button>
    </td>
    <td width="530" class="celda_tabla_principal celda_boton">An&aacute;lisis de Bodega</td>
  </tr>  
  <tr>
    <td width="70" class="celda_tabla_principal">
        <button name="transportador" type="button" onclick="document.location='courier_consulta_reporteXfecha.php'" <?php  $id_objeto=151; include("config/provilegios_objeto.php");  echo $activacion ?>>
            <img src="imagenes/info.png" width="45" height="43" title="Analisis mensual de la Bodega" />
        </button>
    </td>
    <td width="530" class="celda_tabla_principal celda_boton">An&aacute;lisis de Tiempos de Gu&iacute;a</td>
  </tr>  

</table>

<p align="center" class="asterisco">
	<img src="imagenes/excel.jpg" width="45" height="43" align="absmiddle" />
    Los reportes generados, deben ser abiertos preferiblemente con Microsoft Excel.
</p>
</body>
</html>
