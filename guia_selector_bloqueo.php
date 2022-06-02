<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_guia=$_GET['id_guia'];

$sql="SELECT id_tipo_bloqueo FROM guia WHERE id='$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
$fila=mysql_fetch_array($consulta);
$id_tipo_bloqueo=$fila["id_tipo_bloqueo"];
switch ($id_tipo_bloqueo)
{
	case 3: //En bodega
	$estado_bloqueo='';
	$estado_desbloqueo='disabled="disabled"';
	break;

	case 5: //Bloqueada
	$estado_bloqueo='';
	$estado_desbloqueo='';
	break;

	case 6: // Retorno
	$estado_bloqueo='disabled="disabled"';
	$estado_desbloqueo='disabled="disabled"';
	break;

	case 10: // Bloqueo Parcial
	$estado_bloqueo='';
	$estado_desbloqueo='';
	break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
.styled-select select {
   background: #CCC;
   width: 268px;
   padding: 5px;
   font-size: 16px;
   border: 3px solid #03C;
   height: 34px;
}
</style>
</head>

<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Des/Bloqueos</p>
<table align="center">
  <tr>
    <td width="200" height="175" align="center" class="celda_tabla_principal">
    	<button onclick="document.location='guia_bloqueo.php?id_guia=<?php echo $id_guia ?>'" <?php echo $estado_bloqueo ?> <?php $id_objeto=49; include("config/provilegios_objeto.php"); echo $activacion; ?>>
        	<img src="imagenes/bloquear-act.png" width="50" />            
        </button>
        <div class="letreros_tabla">Bloqueo</div>
   	</td>
    <td width="200" height="175" align="center" class="celda_tabla_principal">
   	  <button onclick="document.location='guia_desbloqueo.php?id_guia=<?php echo $id_guia ?>'" <?php echo $estado_desbloqueo ?> <?php echo $estado_bloqueo ?> <?php $id_objeto=50; include("config/provilegios_objeto.php"); echo $activacion; ?>>
      	<img src="imagenes/desbloquear-act.png" width="50" />        
      </button>
      <div class="letreros_tabla">Desbloqueo</div>
    </td>
  </tr>
</table>
</body>
</html>