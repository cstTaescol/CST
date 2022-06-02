<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

$id_consolidado=$_GET['id_guia'];
$cont=0;
$totalpiezas=0;
$totalpeso=0;
$totalvolumen=0;
$resultados1="";

$sql="SELECT * FROM guia WHERE master='$id_consolidado' AND id_tipo_guia !='2'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
//Cuando no tenga asociada ninguna Guia hija en la master
if ($nfilas == 0)
	{
		echo "
			<script language=\"javascript\">
				alert(\"ERROR:No se han asociado GUIAS HIJAS a esta GUIA CONSOLIDADA.\");
				document.location='consulta_consolidado1.php';
			</script>";
		exit();
	}
else
	{
			
		$sql_consol="SELECT id_agentedecarga,id_consignatario FROM guia WHERE id ='$id_consolidado'";
		$consulta_consol=mysql_query ($sql_consol,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_consol=mysql_fetch_array($consulta_consol);	
		//Consulta adicional
		$agentedecarga=$fila_consol["id_agentedecarga"];	

		$sql2="SELECT razon_social FROM agente_carga WHERE id='$agentedecarga'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		if ($fila2['razon_social'] == ""){
			$agentedecarga= "";
		}
		else {
			$agentedecarga= "Agente de Carga: ". $fila2['razon_social'];	
		}
		
		//Consulta adicional
		$consignatario=$fila_consol["id_consignatario"];
		$sql2="SELECT nombre FROM consignatario WHERE id='$consignatario'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		if ($fila2['nombre'] == ""){
			$consignatario= "";
		}
		else {
			$consignatario= "Consignatario: ". $fila2['nombre'];	
		}		
	}
while($fila=mysql_fetch_array($consulta))
{
	$cont++;
	include("config/evaluador_inconsistencias.php");
	$id_guia=$fila["id"];
	$hija=$fila["hija"];
	$master=$fila["master"];
	include("config/master.php");
	$totalpiezas=$totalpiezas+$piezas;
	$totalpeso=$totalpeso+$peso;
	$totalvolumen=$totalvolumen+$volumen;
	$peso=number_format($peso,2,",",".");
	$volumen=number_format($volumen,2,",",".");
	$resultados1=$resultados1."
	<tr>
		<td class=\"celda_tabla_principal celda_boton\">$cont</td>
		<td class=\"celda_tabla_principal celda_boton\"><a href=\"consulta_guia.php?id_guia=$id_guia\">$hija</a></td>
		<td align='right' class=\"celda_tabla_principal celda_boton\">$piezas</td>
		<td align='right' class=\"celda_tabla_principal celda_boton\">$peso</td>
		<td align='right' class=\"celda_tabla_principal celda_boton\">$volumen</td>
	</td>
	";
}	
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
//Privilegios Consultar Todo el Modulo
$id_objeto=51; 
include("config/provilegios_modulo.php");  
//---------------------------
?>
<p class="titulo_tab_principal">Datos de Consolidado</p>
<p align="center">Consolidado No. <font color="red"><?php echo $master ?></font></p>
<p align="center">
	<?php
		echo $agentedecarga . "<br>" .$consignatario;	
	?>
</p>
<p align="center" class="asterisco">Gu&iacute;as Asociadas</p>
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No.</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
  </tr>
  <tr>
  	<?php echo $resultados1 ?>  
  </tr>
</table>
<hr>
<p align="left">
	<strong>Total de Piezas:</strong><?php echo $totalpiezas ?><br>
	<strong>Total de Peso:</strong><?php echo number_format($totalpeso,2,",",".") ?><br>
	<strong>Total de Volumen:</strong><?php echo number_format($totalvolumen,2,",",".") ?><br>
</p>
</body>
</html>