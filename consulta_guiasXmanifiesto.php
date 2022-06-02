<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$totalpiezas=0;
$totalpeso=0;
$totalvolumen=0;
$datos="";
if (isset($_POST["codigo"]))
{
	$nmanifiesto=$_POST["codigo"];
	$sql="SELECT v.nvuelo,g.* FROM vuelo v LEFT JOIN guia g ON v.id = g.id_vuelo WHERE v.nmanifiesto='$nmanifiesto'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$i=0;
	$datos='
	<table align="center">
	  <tr>
		<td class="celda_tabla_principal"><div class="letreros_tabla">No.</div></td>
		<td class="celda_tabla_principal"><div class="letreros_tabla">Consolidado</div></td>
		<td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
		<td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
		<td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
		<td class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
		<td class="celda_tabla_principal"><div class="letreros_tabla">Vuelo</div></td>
		<td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
	  </tr>
	';
	
	while($fila=mysql_fetch_array($consulta))
	{
		$i++;
		include("config/evaluador_inconsistencias.php");
		//recuperando datos de guias		
		$id_guia=$fila['id'];
		$hija=$fila['hija'];
		$master=$fila['master'];
		include("config/master.php");
		$totalpiezas=$totalpiezas+$piezas;
		$totalpeso=$totalpeso+$peso;
		$totalvolumen=$totalvolumen+$volumen;
		$peso=number_format($peso,2,",",".");
		$volumen=number_format($volumen,2,",",".");

		$nvuelo=$fila['nvuelo'];
		$id_aerolinea=$fila['id_aerolinea'];
		//identificando datos
		$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$aerolinea=$fila2['nombre'];		
		//******************
		$datos=$datos."
		  <tr>
			<td class=\"celda_tabla_principal celda_boton\">$i</td>
			<td class=\"celda_tabla_principal celda_boton\">$master</td>
			<td class=\"celda_tabla_principal celda_boton\"><a href=\"consulta_guia.php?id_guia=$id_guia\">$hija</a></td>
			<td class=\"celda_tabla_principal celda_boton\">$piezas</td>
			<td class=\"celda_tabla_principal celda_boton\">$peso</td>
			<td class=\"celda_tabla_principal celda_boton\">$volumen</td>
			<td class=\"celda_tabla_principal celda_boton\">$nvuelo</td>
			<td class=\"celda_tabla_principal celda_boton\">$aerolinea</td>
		  </tr>";
	}
	$datos=$datos."
	</table>
	<hr>
	<strong>Total de Piezas:</strong>$totalpiezas<br>
	<strong>Total de Peso:</strong>".number_format($totalpeso,2,",",".")."<br>
	<strong>Total de Volumen:</strong>".number_format($totalvolumen,2,",",".")."<br>	
	";
}
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
<p class="titulo_tab_principal">Buscador de Guias</p>
<form name="buscador" method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
<table align="center">
    <tr>
        <td class="celda_tabla_principal">
            <p class="asterisco">Manifiesto No.</p>
        </td>
        <td class="celda_tabla_principal">
			<input name="codigo" type="text" id="codigo" tabindex="1" size="35" maxlength="20" />   
            <script>document.forms[0].codigo.focus();</script>         
        </td>
        <td class="celda_tabla_principal">
			<button type="submit" tabindex="2">
            	<img src="imagenes/buscar-act.png" align="absmiddle" />
            </button>
        </td>        
    </tr>
</table>
</form>
<?php echo $datos; ?>
</body>
</html>