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
//Privilegios Consultar Todo el Modulo
$id_objeto=2; 
include("config/provilegios_modulo.php");  
//---------------------------
?>
<p class="titulo_tab_principal">Parametros de Usuario</p>
<table width="610"  border="0" align="center">
  <tr>
    <td class="celda_tabla_principal celda_boton" width="250" height="123" align="center" valign="middle">
    	<button type="button" onclick="document.location='usuario_registro.php';">
        	<img src="imagenes/agregar-act.png" title="Crear  un nuevo usuario" />
        </button>
    </td>
    <td class="celda_tabla_principal celda_boton" width="218" align="center" valign="middle">
    	<button type="button" onclick="document.location='usuario_lista.php';">
        	<img src="imagenes/buscar-act.png" title="Modificar los datos de un Usuario Existente" />
          </button>
    </td>
  </tr>
  <tr>
    <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">CREAR USUARIO</div></td>
    <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">BUSCAR</div></td>
  </tr>
</table>
<p align="center">
</body>
</html>