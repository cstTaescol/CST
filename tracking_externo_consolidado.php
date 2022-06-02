<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
//require("config/control_tiempo.php");

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
				document.location='base.php';
			</script>";
		exit();
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
		<td class=\"celda_tabla_principal celda_boton\"><a href=\"tracking_externo3.php?id_guia=$id_guia\">$hija</a></td>
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
	<title>TRACKING SIC-CST</title>
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<table align="center">
        <tr>
        	<td>
            	<a href="tracking_externo.php"><img src="imagenes/traking.jpg" border="0"></a>
            </td>
        </tr>
     </table>
    <p class="titulo_tab_principal">Consolidado</p>
    <p align="center" class="asterisco">No. <?php echo $master ?> </p>
    <p align="center" class="asterisco">Guias Asociadas:</p>
    <table width="600" align="center">
      <tr>
        <td width="33" class="celda_tabla_principal"><div class="letreros_tabla">No.</div></td>
        <td width="204" class="celda_tabla_principal"><div class="letreros_tabla">Hija</div></td>
        <td width="68" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
        <td width="137" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div> </td>
        <td width="137" class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
      </tr>
      <tr>
        <?php echo $resultados1 ?>
       </tr>
      <tr>
        <td colspan="5" class="celda_tabla_principal">Total de Piezas: </strong><?php echo $totalpiezas ?></td>
      </tr>
      <tr>
        <td colspan="5" class="celda_tabla_principal">Total de Peso: </strong><?php echo number_format($totalpeso,2,",",".") ?></td>
      </tr>
      <tr>
        <td colspan="5" class="celda_tabla_principal">Total de Volumen: </strong><?php echo number_format($totalvolumen,2,",",".") ?></td>
      </tr>
    </table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" onClick="document.location='<?php echo URLCLIENTE ?>'">
                <img src="imagenes/al_principio-act.png" title="<?php echo CLIENTE ?>" /><br>
                Volver al Cliente
            </button>
            <button type="button" name="cancelar" id="cancelar" onClick="document.location='tracking_externo.php'">
                <img src="imagenes/buscar-act.png" title="Buscar Otra" /><br>
                Nueva busqueda
            </button>
          </td>
        </tr>
     </table>    
</body>
</html>