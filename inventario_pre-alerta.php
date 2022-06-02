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

//Privilegios Consultar Todo el Modulo
$id_objeto=109; 
include("config/provilegios_modulo.php");  
//---------------------------

//carga de guias sobrantes
$sql="SELECT * FROM guia WHERE id_tipo_bloqueo = '2' $sql_aerolinea ORDER BY id_vuelo ASC";
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
	$piezastotal += $piezas;
	$pesototal += $fila["peso"];
	$volumentotal += $fila["volumen"];
	$cantidadguias++;

	//carga datos
	$sql2="SELECT nvuelo,nmanifiesto FROM vuelo WHERE id='$id_vuelo'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 2". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$nvuelo=$fila2['nvuelo'];
	$nmanifiesto=$fila2['nmanifiesto'];

	//carga datos
	$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 3". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$aerolinea=$fila2['nombre'];
	
	// identificando columna de consoliado
	$id_tipo_guia=$fila["id_tipo_guia"];
	if ($id_tipo_guia==3)
	{
		$master=$fila["master"];
		require("config/master.php");
	}
	else
	{
		$sql3="SELECT nombre FROM tipo_guia WHERE id='$id_tipo_guia'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$master=$fila3["nombre"];
	}
	
	$impresion=$impresion."
	  <tr>
		<td class=\"celda_tabla_principal celda_boton\">$master</td>	  
		<td class=\"celda_tabla_principal celda_boton\"><a href=\"consulta_guia.php?id_guia=$id_guia\">$hija</a></td>
		<td class=\"celda_tabla_principal celda_boton\">$nvuelo</td>
		<td class=\"celda_tabla_principal celda_boton\">$aerolinea</td>
		<td align=\"right\" class=\"celda_tabla_principal celda_boton\">$piezas</td>
		<td align=\"right\" class=\"celda_tabla_principal celda_boton\">$peso</td>
		<td align=\"right\" class=\"celda_tabla_principal celda_boton\">$volumen</td>
		<td class=\"celda_tabla_principal celda_boton\">$nmanifiesto</td>
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
<?php require("menu.php"); ?>
<p class="titulo_tab_principal">Inventario de Pre-Alerta</p>
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Master</div></td>  
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Vuelo</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Manifiesto</div></td>
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
