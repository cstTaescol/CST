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
<p class="titulo_tab_principal">Consulta de Despachos</p>
<table width="600" align="center" class="decoracion_tabla">
  <tr>
    <td width="70" class="celda_tabla_principal">
    	<button name="terminar" type="button" onclick="document.location='consulta_identificar_remesa.php'" <?php  $id_objeto=66; include("config/provilegios_objeto.php");  echo $activacion ?>>
        	<img src="imagenes/aceptar-act.png" title="Consulta de las Remesas generadas" />
         </button>
    </td>
    <td width="530" class="celda_tabla_principal celda_boton">Consultar Despacho de Remesas</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal">
    	<button name="terminar" type="button" onclick="document.location='consulta_identificar_ddirecto.php'" <?php  $id_objeto=67; include("config/provilegios_objeto.php");  echo $activacion ?>>
        	<img src="imagenes/aceptar-act.png" title="Consultar los Descargues Directos generados" />
        </button>
   </td>
    <td class="celda_tabla_principal celda_boton">Consultar Despachos por Descargues Directos </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal">
    	<button name="terminar" type="button" onClick="document.location='consulta_identificar_cabotaje.php'" <?php  $id_objeto=68; include("config/provilegios_objeto.php");  echo $activacion ?>>
  			<img src="imagenes/aceptar-act.png" title="Consultar los Cabotajes generados" />
        </button>
    </td>
    <td class="celda_tabla_principal celda_boton">Consultar Despachos por Cabotajes</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal">
    	<button name="terminar" type="button" onclick="document.location='consulta_identificar_correo.php'" <?php  $id_objeto=87; include("config/provilegios_objeto.php");  echo $activacion ?>> 
        	<img src="imagenes/aceptar-act.png" title="Consultar los despachos por Correo generados" />
        </button>
    </td>
    <td class="celda_tabla_principal celda_boton">Consultar Despachos por Correo </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal">
    	<button name="terminar" type="button" onclick="document.location='consulta_identificar_trasbordo.php'" <?php  $id_objeto=69; include("config/provilegios_objeto.php");  echo $activacion ?>> 
        <img src="imagenes/aceptar-act.png" title="Consultar los despachos por TRASBORDOS generados" />
        </button>
     </td>
    <td class="celda_tabla_principal celda_boton">Consultar Despachos por Trasbordo </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal ">
    	<button name="terminar" type="button" onclick="document.location='consulta_identificar_otros.php'" <?php  $id_objeto=70; include("config/provilegios_objeto.php");  echo $activacion ?>> 
        <img src="imagenes/aceptar-act.png" title="Consultar los despachos por OTROS generados" />
        </button>
    </td>
    <td class="celda_tabla_principal celda_boton">Consultar Otos Despachos</td>
  </tr>
</table>
</body>
</html>