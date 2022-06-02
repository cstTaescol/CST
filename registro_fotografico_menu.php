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
<p class="titulo_tab_principal">Registro Fotografico</p>
<table width="600" align="center" class="decoracion_tabla">
  <tr>
    <td width="70" class="celda_tabla_principal">
    	<button name="terminar" type="button" onclick="document.location='consulta_guia_buscar.php?origen=registro_fotografico&boton=foto_bodega'" <?php  $id_objeto=98; include("config/provilegios_objeto.php");  echo $activacion ?>>
        	<img src="imagenes/caja2.png" width="45" height="43" title="Registro Fotografico" />
         </button>
    </td>
    <td width="530" class="celda_tabla_principal celda_boton">Bodega</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal">
    	<button name="terminar" type="button" onclick="document.location='consulta_guia_buscar.php?origen=registro_fotografico&boton=foto_seguridad'" <?php  $id_objeto=100; include("config/provilegios_objeto.php");  echo $activacion ?>>
        <img src="imagenes/poli1.png" width="45" height="43" title="Registro Fotografico" />
        </button>
    </td>
    <td class="celda_tabla_principal celda_boton">Seguridad</td>
  </tr>  
  <tr>
    <td class="celda_tabla_principal">
    	<button name="terminar" type="button" onclick="document.location='consulta_guia_buscar.php?origen=registro_fotografico&boton=foto_despacho'" <?php  $id_objeto=99; include("config/provilegios_objeto.php");  echo $activacion ?>>
        	<img src="imagenes/camion.png" width="45" height="43" title="Registro Fotografico" />
        </button>
    </td>
    <td class="celda_tabla_principal celda_boton">Despacho</td>
  </tr>
</table>
</body>
</html>