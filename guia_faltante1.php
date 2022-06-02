<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$impresion="";

$id_usuario=$_SESSION['id_usuario'];
$id_guia=$_GET['id_guia'];
$id_registro=$_GET['id_registro'];
$piezas=$_GET['piezas'];
$peso=$_GET['peso'];

//Consulta de datos
$sql="SELECT hija,fecha_inconsistencia,id_vuelo,id_aerolinea FROM guia WHERE id='$id_guia'";
$consulta=mysql_query($sql,$conexion);
$fila=mysql_fetch_array($consulta);
$hija=$fila['hija'];
$id_vuelo=$fila['id_vuelo'];
$id_aerolinea=$fila['id_aerolinea'];
$fecha_inconsistencia=$fila['fecha_inconsistencia'];

//Consulta de datos
$sql="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$nvuelo=$fila['nvuelo'];

//Consulta de datos
$sql="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$aerolinea=$fila['nombre'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
<!--
.fondo_tabla {
	color: #CCC;
}
-->
</style>
<script language="javascript">
// funcion para validar
function validar()
{	
	if (document.forms[0].descripcion.value=="")
	{
		alert("Atencion: Se Requiere la DESCRIPCION");
		document.forms[0].descripcion.focus();
		return(false);
	}
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Descripcion de Faltanes</p>
<p align="center" class="asterisco">Guia No. <font color="red"><strong> <?php echo $hija ?></p>
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $aerolinea ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Vuelo</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $nvuelo ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Guia</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $hija ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas Faltantes</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $piezas ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso Faltante</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $peso ?> Kg.</td>
  </tr>
</table>
<br />
<form action="guia_faltante2.php" method="post" onsubmit="return validar();">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Descripcion por solucion de faltante</div></td>
  </tr>
  <tr>
    <td height="85"  class="celda_tabla_principal celda_boton">
    	<textarea name="descripcion" cols="80" rows="7" id="descripcion"></textarea>
        <input type="hidden" name="id_registro" value="<?php echo $id_registro ?>" />
        <input type="hidden" name="id_guia" value="<?php echo $id_guia ?>" />
    </td>
  </tr>
</table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
      	<button type="reset" name="reset" id="reset" tabindex="3"> <img src="imagenes/descargar-act.png" alt="" title="Limpiar" /></button>
        <button type="submit" name="guardar" id="guardar" tabindex="2"> <img src="imagenes/guardar-act.png" alt="" title="Guardar" /> </button>
      </td>
    </tr>
</table>
</form>
</body>
</html>