<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$impresion="";

//Discriminacion de aerolinea de usuario TIPO 2
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id_aerolinea = '$id_aerolinea_user'";	
//*************************************


//Privilegios Solucionar Guia Faltante
$id_objeto=55; 
include("config/provilegios_objeto.php");  

//carga de guias sobrantes
$sql="SELECT * FROM inconsistencias WHERE estado = 'A' ORDER BY fecha DESC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
{
	$id_guia=$fila["id_guia"];
	//carga datos
	$sql2="SELECT hija, id_vuelo, id_aerolinea FROM guia WHERE id='$id_guia' $sql_aerolinea";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 2". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$id_vuelo=$fila2['id_vuelo'];
	$id_aerolinea=$fila2['id_aerolinea'];
	$hija=$fila2['hija'];
	if ($id_vuelo != "")
	{
		$piezas=$fila["variacion_piezas"];
		$peso=number_format($fila["variacion_peso"],2,",",".");
		$id_usuario=$fila["id_usuario"];
		$fecha_creacion=$fila["fecha"];
		$hora_creacion=$fila["hora"];
		$id_registro=$fila["id"];
		
		//carga datos
		$sql2="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 3". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$nvuelo=$fila2['nvuelo'];
	
		//carga datos
		$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 4". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$aerolinea=$fila2['nombre'];
	
		//carga datos
		$sql2="SELECT nombre FROM usuario WHERE id='$id_usuario'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 5". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$usuario=$fila2['nombre'];
		
		$impresion=$impresion."
		  <tr>
			<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
				<button type='button' $activacion  onclick='document.location=\"guia_faltante1.php?id_guia=$id_guia&id_registro=$id_registro&peso=$peso&piezas=$piezas\";'>
					<img src=\"imagenes/aceptar-act.png\" title=\"Seleccionar\" />
				</button>			
			</td>
			<td class=\"celda_tabla_principal celda_boton\"><a href=\"consulta_guia.php?id_guia=$id_guia\">$hija</a></td>
			<td class=\"celda_tabla_principal celda_boton\">$nvuelo</td>
			<td class=\"celda_tabla_principal celda_boton\">$aerolinea</td>
			<td align=\"right\" class=\"celda_tabla_principal celda_boton\">$piezas</td>
			<td align=\"right\" class=\"celda_tabla_principal celda_boton\">$peso</td>
			<td class=\"celda_tabla_principal celda_boton\">$fecha_creacion - ($hora_creacion)</td>
			<td class=\"celda_tabla_principal celda_boton\">$usuario</td>
		  </tr>";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo1 {
	color: #CCCCCC;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<?php require("menu.php"); 
//Privilegios Consultar Todo el Modulo
$id_objeto=54; 
include("config/provilegios_modulo.php");  
//---------------------------
?>
<p class="titulo_tab_principal">Inventario de Faltantes</p>
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Solucionar</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Vuelo</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas Faltantes</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso Faltante</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
	<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Usuario</div></td>
  </tr>
  <?php echo $impresion ?>
</table><br>
<hr>
<p><strong>ATENCI&Oacute;N</strong>: Si aplica este procedimiento la Gu&iacute;a desaparecer&aacute; del uso de Inventarios y Bodega.</p>
</body>
</html>
<?php
mysql_free_result($consulta);
mysql_free_result($consulta2);
mysql_close($conexion);
?>