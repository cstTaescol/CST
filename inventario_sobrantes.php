<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$impresion="";
$piezastotal=0;
$pesototal=0;
$volumentotal=0;
$cantidadguias=0;
$posicion="";

//Discriminacion de aerolinea de usuario TIPO 2
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id_aerolinea = '$id_aerolinea_user'";	
//*************************************

//Privilegios Solucionar Guia Sobrante
$id_objeto=53; 
include("config/provilegios_objeto.php");  
//---------------------------

//carga de guias sobrantes
$sql="SELECT * FROM guia WHERE id_tipo_bloqueo = '9' $sql_aerolinea";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
{
	$id_guia=$fila["id"];
	$hija=$fila["hija"];
	$piezas=$fila["piezas"];
	$peso=number_format($fila["peso"],2,",",".");
	$volumen=number_format($fila["volumen"],2,",",".");
	$fecha_vencimiento_registrada=$fila["fecha_vencimiento"];
	$id_vuelo=$fila["id_vuelo"];
	$id_aerolinea=$fila["id_aerolinea"];
	$piezastotal=$piezastotal+$piezas;
	$pesototal=$pesototal+$peso;
	$volumentotal=$volumentotal+$volumen;
	$cantidadguias++;

	// Formato de Colores por linea segun vencimiento
	$fecha_vencimiento=explode("-",$fila['fecha_vencimiento']);
	$aa=$fecha_vencimiento[0];
	$mm=$fecha_vencimiento[1];
	$dd=$fecha_vencimiento[2];
	$fecha_vencimiento=$aa.$mm.$dd;
	$fecha_actual=date("Y").date("m").date("d");
	$diferencia=$fecha_vencimiento-$fecha_actual;
	
	switch ($diferencia)
	{
		case 2: //normal
			$color="#FFFFFF";
		break;
		case 1: //vence mañana
			$color="#99FF66";
		break;
		case 0: //vence hoy
			$color="#FF0000";
		break;
		default:
			if ($diferencia > 2)
				$color="#FFFFFF"; //normal
			if ($diferencia < 0)
				$color="#6666CC"; //vencidas
		break;
	}	
	//*********************************************************************************************

	//carga datos
	$sql2="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 2". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$nvuelo=$fila2['nvuelo'];

	//carga datos
	$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 3". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$aerolinea=$fila2['nombre'];
	
	//Ubica la Posicion en Bodega
	$sql_posiscion="SELECT p.*,pc.* FROM posicion_carga pc LEFT JOIN posicion p ON pc.id_posicion=p.id WHERE pc.id_guia='$id_guia'";
	$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit('Error '.mysql_error()));
	while($fila_posicion=mysql_fetch_array($consulta_posicion))
	{
		$plaqueta=$fila_posicion['rack']."-".$fila_posicion['seccion']."-".$fila_posicion['nivel']."-".$fila_posicion['lado']." ".$fila_posicion['fondo'];
		if ($fila_posicion['rack'] < "J")
			$mapa_destino="ubicacion_mapa.php";
		else
			$mapa_destino="ubicacion_mapa2.php";		
		$posicion=$posicion." - <a href=\"$mapa_destino?id_guia=$id_guia\"><font color=\"blue\">$plaqueta</font></a>";
	}
	$impresion=$impresion."
	  <tr bgcolor='$color'>
		<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
			<button type='button' $activacion  onclick='document.location=\"guia_sobrante.php?id_guia=$id_guia\";'>
				<img src=\"imagenes/aceptar-act.png\" title=\"Seleccionar\" />
			</button>
		</td>
		<td class=\"celda_tabla_principal celda_boton\"><a href=\"consulta_guia.php?id_guia=$id_guia\">$hija</a></td>
		<td class=\"celda_tabla_principal celda_boton\">$nvuelo</td>
		<td class=\"celda_tabla_principal celda_boton\">$aerolinea</td>
		<td align=\"right\" class=\"celda_tabla_principal celda_boton\">$piezas</td>
		<td align=\"right\" class=\"celda_tabla_principal celda_boton\">$peso</td>
		<td align=\"right\" class=\"celda_tabla_principal celda_boton\">$volumen</td>
		<td class=\"celda_tabla_principal celda_boton\">$fecha_vencimiento_registrada</td>
		<td class=\"celda_tabla_principal celda_boton\">$posicion</td>
	  </tr>";
	 $posicion="";
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
<?php 
require("menu.php"); 
//Privilegios Consultar Todo el Modulo
$id_objeto=52; 
include("config/provilegios_modulo.php");  
//---------------------------

?>
<p class="titulo_tab_principal">Inventario de Sobrantes</p>
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Solucionar</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Vuelo</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Vencimiento</div></td>
	<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Ubicacion</div></td>
  </tr>
  <?php echo $impresion ?>
</table>
<hr>
<p>
	Total Piezas: <?php echo $piezastotal ?><br>
    Total Peso: <?php echo number_format($pesototal,2,",","."); ?><br>
    Total Volumen: <?php echo number_format($volumentotal,2,",","."); ?><br>
    Total Cantidad de Gu&iacute;as: <?php echo $cantidadguias ?><br>
</p>
</body>
</html>
<?php
mysql_free_result($consulta);
mysql_free_result($consulta2);
mysql_close($conexion);
?>