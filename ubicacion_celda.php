<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

//Discriminacion de aerolinea de usuario TIPO 9
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND g.id_aerolinea = '$id_aerolinea_user'";	
//*************************************

$hijas="<strong>GUIA</strong><hr />";
$piezas="<strong>PIEZAS</strong> <hr />";
$pesos="<strong>PESO</strong> <hr />";
$mover="<strong>MOVER</strong> <hr />";
$eliminar="<strong>ELIMINAR</strong> <hr />";
$impresion="
	<table align=\"center\" >
	  <tr>
		<td align=\"center\" class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Guia</div></td>
		<td align=\"center\" class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Piezas</div></td>
		<td align=\"center\" class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Peso</div></td>
		<td align=\"center\" class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Eliminar</div></td>
		<td align=\"center\" class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">Mover</div></td>
	  </tr>";

if (isset($_REQUEST['id_posicion']))
{
	$id_posicion=$_REQUEST['id_posicion'];
}
else
{
	echo "
		<script>
			alert('ERROR, El servidor no ha identificado una ubicacion, vuelva a intentarlo');
			document.location='ubicacion_mapa.php';
		</script>";
		exit();
}
//Identificacion de Posiciones.
$sql_posiscion="SELECT * FROM posicion WHERE id='$id_posicion'";
$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila_posicion=mysql_fetch_array($consulta_posicion);
$plaqueta=$fila_posicion['rack']."-".$fila_posicion['seccion']."-".$fila_posicion['nivel']."-".$fila_posicion['lado'];

//Privilegios Eliminar guia del Mapa
$id_objeto=89; 
include("config/provilegios_objeto.php");  
if ($activacion == 'disabled="disabled"')
	$url_destino1="#";
else
	$url_destino1="ubicacion_liberar.php";
//---------------------------

//Privilegios Mover guia del Mapa
$id_objeto=88; 
include("config/provilegios_objeto.php");  
if ($activacion == 'disabled="disabled"')
	$url_destino2="#";
else
	$url_destino2="ubicacion_seleccionar_bodega.php";
//---------------------------

//Identificacion de Posiciones.
$sql_posiscion="SELECT p.piezas,p.peso,p.id AS id_celda, g.hija,g.id FROM posicion_carga p LEFT JOIN guia g ON p.id_guia=g.id WHERE p.id_posicion='$id_posicion' $sql_aerolinea";
$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila_posicion=mysql_fetch_array($consulta_posicion))
	{
		$id_guia=$fila_posicion['id'];
		$hija=$fila_posicion['hija'];
		$id_celda=$fila_posicion['id_celda'];
		$piezas=$fila_posicion['piezas'];
		$peso=$fila_posicion['peso'];
		$eliminar="<a href=\"$url_destino1?celda=$id_celda&msg=$plaqueta&id_guia=$id_guia\"><img src=\"imagenes/eliminar-act.png\" width=\"40\" height=\"40\" border=\"1\" title=\"Eliminar la Gu&iacute;a $hija de esta Posici&oacute;n\"/></a><br />";
		$mover="<a href=\"$url_destino2?evento=mover&celda=$id_celda&msg=$plaqueta&id_guia=$id_guia\"><img src=\"imagenes/trasbordo.jpg\" width=\"40\" height=\"40\" border=\"1\" title=\"Mover la Gu&iacute;a $hija de esta Posici&oacute;n\"/></a><br />";
		$impresion .= "<tr>
							<td align=\"center\" class=\"celda_tabla_principal celda_boton\"><a href=\"consulta_guia.php?id_guia=$id_guia\">$hija</a></td>
							<td align=\"center\" class=\"celda_tabla_principal celda_boton\">$piezas</td>
							<td align=\"center\" class=\"celda_tabla_principal celda_boton\">$peso</td>
							<td align=\"center\" class=\"celda_tabla_principal celda_boton\">$eliminar</td>
							<td align=\"center\" class=\"celda_tabla_principal celda_boton\">$mover</td>
						  </tr>";
		
	}
$impresion .= "</table>";
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
<table align="center" width="700px">
  <tr>
    <td align="center" class="celda_tabla_principal"><div class="asterisco">Listado de Guias en Esta Posicion</div></td>
    <td align="center" valign="middle" bgcolor="#FFFF00"><div class="letreros_tabla"><?php echo $plaqueta;?></div></td>
  </tr>
  <tr class="celda_tabla_principal">
    <td colspan="2" ><?php echo $impresion;	?></td>
  </tr>
</table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
    	<button type="button" onclick="document.location='ubicacion_mapa.php'">
            <img src="imagenes/home-act.png" /><br />Bodega 2
        </button>
    	<button type="button" onclick="document.location='ubicacion_mapa2.php'">
            <img src="imagenes/home-act.png" /><br />Bodega 4
        </button>
       	<button type="button" onclick="document.location='ubicacion_ubicar.php'">
      		<img src="imagenes/home-act.png" title="Ubicar una nueva Gu&iacute;a"/><br />Ubicar otra Guia
        </button>
      </td>
    </tr>
 </table>    
</body>
</html>