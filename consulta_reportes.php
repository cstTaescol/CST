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
	popupWin = window.open(url,'reporte','directories, status, scrollbars, resizable, dependent, width=640, height=480, left=100, top=100')
	//  popupWin = window.open('pdf_remesa.php','nombre_ventana','menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Generador de Reportes</p>

<table width="600" align="center" class="decoracion_tabla">
  <tr>
    <td width="70" class="celda_tabla_principal">
    	<button type="button" name="inventario"  onclick="abrir('reportes/reporte_inventario1.php');" <?php  $id_objeto=73; include("config/provilegios_objeto.php");  echo $activacion ?>>
        <img src="imagenes/home-act.png" width="45" height="43" alt="Reporte de Existencias en Inventario" />
        </button>    
    </td>
    <td width="530" class="celda_tabla_principal celda_boton">Reporte de Inventario</td>
  </tr>
  <tr>
    <td width="70" class="celda_tabla_principal">
	<button name="personalizado" type="button" onclick="document.location='reporteador1.php'" <?php  $id_objeto=74; include("config/provilegios_objeto.php");  echo $activacion ?>>
    	<img src="imagenes/aceptar-act.png" width="45" height="43" title="Generar Reportes Personalizados" />
    </button>
    </td>
    <td width="530" class="celda_tabla_principal celda_boton">Reporte Personalizado</td>
  </tr>
  <tr>
    <td width="70" class="celda_tabla_principal">
        <button name="transportador" type="button" onclick="document.location='reporteador2.php'" <?php  $id_objeto=75; include("config/provilegios_objeto.php");  echo $activacion ?>>
            <img src="imagenes/camion.png" width="45" height="43" title="Generar Reportes Por Transportador" />
        </button>
    </td>
    <td width="530" class="celda_tabla_principal celda_boton">Reporte Transportador</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal">
        <button name="transportador" type="button" onclick="document.location='reporteador4.php?tipo=F'" <?php  $id_objeto=106; include("config/provilegios_objeto.php");  echo $activacion ?>>
            <img src="imagenes/$.png" width="45" height="43" title="Generar Reportes Por Facturacion" />
        </button>    
    </td>
    <td class="celda_tabla_principal celda_boton">Reporte Facturaci&oacute;n</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal">
        <button type="button" onclick="document.location='consulta_identificar_vuelo_despaletizado.php?tipo_reporte=completo'" <?php  $id_objeto=107; include("config/provilegios_objeto.php");  echo $activacion ?>>
            <img src="imagenes/quitar_link-act.png" width="45" height="43" align="absmiddle"  />
        </button>            
    </td>
    <td class="celda_tabla_principal celda_boton">Reporte Despaletizaje Completo (Vuelo Finalizado)</td>    
  </tr>
  <tr>
    <td class="celda_tabla_principal">
        <button type="button" onclick="document.location='consulta_identificar_vuelo_despaletizado.php?tipo_reporte=reducido'" <?php  $id_objeto=108; include("config/provilegios_objeto.php");  echo $activacion ?>>
            <img src="imagenes/excel.jpg" width="45" height="43" align="absmiddle"  />
        </button>
        <button type="button" onclick="document.location='consulta_identificar_vuelo_despaletizado.php?tipo_reporte=reducido_pdf'" <?php  $id_objeto=108; include("config/provilegios_objeto.php");  echo $activacion ?>>
            <img src="imagenes/pdf.jpg" width="45" height="43" align="absmiddle"  />
        </button>         
                    
    </td>
    <td class="celda_tabla_principal celda_boton">Reporte Despaletizaje Parcial (Vuelo en Despaletizaje)</td>    
  </tr>  
  <tr>
    <td width="70" class="celda_tabla_principal">
        <button name="vuelo" type="button" onclick="document.location='consulta_identificar_vuelo.php'" <?php  $id_objeto=76; include("config/provilegios_objeto.php");  echo $activacion ?>>
            <img src="imagenes/avion1.png" width="45" height="43" title="Generar Por Vuelo" />
        </button>
    </td>
    <td width="530" class="celda_tabla_principal celda_boton">Reporte de Vuelo</td>
  </tr>  
  <tr>
    <td width="70" class="celda_tabla_principal">
        <button name="despachos" type="button" onclick="document.location='reporteador4.php?tipo=D'" <?php  $id_objeto=155; include("config/provilegios_objeto.php");  echo $activacion ?>>
            <img src="imagenes/cargo-box.png" width="45" height="43" title="Generar Por Despachos" />
        </button>
    </td>
    <td width="530" class="celda_tabla_principal celda_boton">Reporte de Gu&iacute;as Despachadas</td>
  </tr>
  
  
</table>

<p align="center" class="asterisco">
	<img src="imagenes/excel.jpg" width="45" height="43" align="absmiddle" />
    Los reportes generados, deben ser abiertos preferiblemente con Microsoft Excel.
</p>
</body>
</html>