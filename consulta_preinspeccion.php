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
<p class="titulo_tab_principal">Pre-Inspecci&oacute;n</p>
<table align="center">
  <tr>
    <td width="300" class="celda_tabla_principal">
    	<div class="letreros_tabla">
            <button name="terminar" type="button" onclick="document.location='consulta_guia_buscar.php?origen=preinspeccion'" <?php  $id_objeto=81; include("config/provilegios_objeto.php");  echo $activacion ?>><img src="imagenes/aceptar-act.png" width="45" height="43" title="Crear una autorizacion de Pre-inspeccion para una Guia." />
            </button>
        </div>
     </td>
    <td class="celda_tabla_principal celda_boton">Crear Autorizacion de Pre-Inspecci&oacute;n.</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal">
    	<div class="letreros_tabla">
    		<button name="consultar" type="button" onclick="document.location='consulta_identificar_preinspeccion.php'" <?php  $id_objeto=82; include("config/provilegios_objeto.php");  echo $activacion ?>><img src="imagenes/buscar-act.png" width="45" height="43" title="Consultar una autorizacion de Pre-Inspeccion" />
            </button>
        </div>
     </td>
    <td class="celda_tabla_principal celda_boton">Consultar Autorizaciones de Pre-Inspecci&oacute;n.</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal">
    	<div class="letreros_tabla">
            <button name="terminar" type="button" onclick="document.location='base.php'"> <img src="imagenes/cancelar-act.png" width="45" height="43" title="Cancelar" />
            </button>
        </div>
    </td>
    <td class="celda_tabla_principal celda_boton">Cancelar</td>
  </tr>
</table>
<p align="left"><br />
</p>
</body>
</html>