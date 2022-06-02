<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
set_time_limit (0); // Elimina la restriccion en el tiempo limite para la ejecicion del modulo.
require("config/configuracion.php");
require("config/control_tiempo.php");
$totalpiezas=0;
$totalpeso=0;
$totalvolumen=0;
$globalpiezas=0;
$globalpeso=0;
$globalvolumen=0;
$globalguias=0;
$cont=0;
$posicion="";
$color_activo="#FFCC00";
$color_inactivo="#C6DFE6";

//Discriminacion de aerolinea de usuario TIPO 3
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND v.id_aerolinea = '$id_aerolinea_user'";	
//*************************************

//Privilegios Consultar la Guia.
$id_objeto=51; 
include("config/provilegios_objeto.php");  
$activacion1=$activacion;
//---------------------------

//Privilegios Agregar Novedad.
$id_objeto=94; 
include("config/provilegios_objeto.php");  
$activacion2=$activacion;
//---------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language="javascript">
    <!-- función que permite abrir ventanas emergentes con las propiedades deseadas -->
    function openPopup(url,name,w,h,props,center){
        l=18;t=18
        if(center){l=(screen.availWidth-w)/2;t=(screen.availHeight-h)/2}
        url=url.replace(/[ ]/g,'%20')
        popup=window.open(url,name,'left='+l+',top='+t+',width='+w+',height='+h+',scrollbars=1'+((props)?','+props:''))
        props=props||''
        if(props.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
        popup.focus()
    }
    <!-- -------------------------------------------------------------------------- -->
    </script>
</head>
<body>
<?php
require("menu.php");
//Privilegios Consultar Todo el Modulo
$id_objeto=48; 
include("config/provilegios_modulo.php");  
//---------------------------

if(isset($_GET["order"]))
{
	switch ($_GET["order"])
	{
		case "v":
		$parametro="g.id_vuelo";
		$campo="id_vuelo";
		$colorv=$color_activo;
		$colord=$color_inactivo;
		$colorc=$color_inactivo;
		$colora=$color_inactivo;
		break;
		
		case "d":
		$parametro="g.id_deposito";
		$campo="id_deposito";
		$colorv=$color_inactivo;
		$colord=$color_activo;
		$colorc=$color_inactivo;
		$colora=$color_inactivo;
		break;

		case "c":
		$parametro="id_consignatario";
		$campo="id_consignatario";
		$colorv=$color_inactivo;
		$colord=$color_inactivo;
		$colorc=$color_activo;
		$colora=$color_inactivo;
		break;
		
		case "a":
		$parametro="g.id_aerolinea";
		$campo="id_aerolinea";
		$colorv=$color_inactivo;
		$colord=$color_inactivo;
		$colorc=$color_inactivo;
		$colora=$color_activo;
		break;
	}
}
else
{ 
	$parametro="g.id_vuelo";
	$campo="id_vuelo";
	$colorv=$color_activo;
	$colord=$color_inactivo;
	$colorc=$color_inactivo;
	$colora=$color_inactivo;
}
?>
<p class="titulo_tab_principal">Inventario General</p>
<table width="2700">
  <tr>
    <td width="100" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Acciones</div></td>  
    <td width="170" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Consolidado</div></td>
    <td width="180" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td width="260" align="center" class="celda_tabla_principal" style="background-color:<?php echo $colorv ?>" >Vuelo <a href="inventario_general.php?order=v"><img src="imagenes/abajo-act.png" title="Ordenar" border="0" align="absmiddle" /></a></td>
    <td width="260" align="center" class="celda_tabla_principal" style="background-color:<?php echo $colora ?>">Aerolinea<a href="inventario_general.php?order=a"><img src="imagenes/abajo-act.png" title="Ordenar" border="0" align="absmiddle" /></a></td>
    <td width="400" align="center" class="celda_tabla_principal" style="background-color:<?php echo $colord ?>">
    	Destino<br />
        <button type="button" title="DEPOSITOS" onclick="document.location='inventario_destino.php?order=d'">DE</button>
        <button type="button" title="DESCARGUE DIRECTO" onclick="document.location='inventario_destino.php?order=i'">DD</button>
        <button type="button" title="CORREO" onclick="document.location='inventario_destino.php?order=b'">CO</button>
        <button type="button" title="TRASBORDO" onclick="document.location='inventario_destino.php?order=t'">TR</button>
        <button type="button" title="CABOTAJE" onclick="document.location='inventario_destino.php?order=c'">CA</button>
        <button type="button" title="OTROS" onclick="document.location='inventario_destino.php?order=o'">OT</button>
        <button type="button" title="BLOQUEADAS" onclick="document.location='inventario_bloqueos.php'">BL</button>                  
    </td>
	<td width="270" align="center" class="celda_tabla_principal" style="background-color:<?php echo $colorc ?>;">Consignatario <a href="inventario_general.php?order=c"><img src="imagenes/abajo-act.png" title="Ordenar" border="0" align="absmiddle" /></a></td>
    <td width="60" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td width="70" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td width="70" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
    <td width="130" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Vencimiento</div></td>    
    <td width="150" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Manifiesto</div></td>    
    <td width="200" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Ubicacion</div></td>
  </tr>
<?php
$color=""; //color segun el tipo de alerta.
$buffer_vuelo = 0;
$buffer_piezas_vuelo = 0;
$buffer_peso_vuelo = 0;
$buffer_volumen_vuelo = 0;

$sql="SELECT v.id_aerolinea,v.nvuelo,v.hora_finalizado,v.nmanifiesto,v.id AS 'id_vuelo',g.* FROM vuelo v LEFT JOIN guia g ON v.id = g.id_vuelo WHERE  g.id_tipo_guia !='2' AND (id_tipo_bloqueo = '3' OR id_tipo_bloqueo = '5' OR id_tipo_bloqueo = '6' OR id_tipo_bloqueo = '9' OR id_tipo_bloqueo = '10') AND (g.faltante_total='N') $sql_aerolinea ORDER BY $parametro ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
for ($j=1; $j<=$nfilas; $j++)
{	
	$fila=mysql_fetch_array($consulta);
	$id_guia=$fila["id"];
	$nmanifiesto=$fila["nmanifiesto"];
	$nvuelo=$fila["nvuelo"];	
	$hija=$fila["hija"];
	if ($fila["id_inmovilizacion"]!="")
		$imagen1=' / <img src="imagenes/alerta-act.png" title="Esta GUIA pas&oacute; por el proceso de Bloqueo" align="absmiddle" />';
		else
			$imagen1="";				
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
	
	// identificando aerolinea
	$aerolinea=$fila["id_aerolinea"];
	$sql3="SELECT nombre FROM aerolinea WHERE id='$aerolinea'";
	$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila3=mysql_fetch_array($consulta3);
	$aerolinea=$fila3["nombre"];
	
	//calculo del valor pendiente por entregar
	include("config/evaluador_inconsistencias.php"); //calcula y general el valor de $piezas, $peso y $volumen luego de las inconsistencias.		
	$piezas_pendientes_despachar=$piezas-$fila["piezas_despacho"];
	$peso_pendientes_despachar=$peso-$fila["peso_despacho"];		
	$volumen_pendientes_despachar=$volumen-$fila["volumen_despacho"];
	//Totales del inventarios
	$totalpiezas=$totalpiezas+$piezas_pendientes_despachar;
	$totalpeso=$totalpeso+$peso_pendientes_despachar;
	$totalvolumen=$totalvolumen+$volumen_pendientes_despachar;

	//identificando consignatario
	$consignatario=$fila["id_consignatario"];
	$sql3="SELECT nombre FROM consignatario WHERE id='$consignatario'";
	$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila3=mysql_fetch_array($consulta3);
	$consignatario=$fila3["nombre"];		
		

	// Destino
	$id_disposicion=$fila["id_disposicion"];
	include("lib_destino_guia.php");
	//************************************		
		
	//Alertas por colores
	if ($fila["id_tipo_bloqueo"]==5) //BLOQUEADA TOTAL
	{
		$color="#99CCFF"; 
		$urlbloqueo="guia_desbloqueo.php";
	}
		else if ($fila["id_tipo_bloqueo"]==10) //BLOQUEADA PARCIAL
		{
			$color="#99CCFF";
			$urlbloqueo="guia_desbloqueo.php";
			$imagen1=' / <img src="imagenes/stop.jpg" width="22" height="22" title="GUIA con Bloqueo Parcial" align="absmiddle" />';
		}
		else if ($fila["id_tipo_bloqueo"]==9) //SOBRANTE
		{
			$color="#FF9999";
		}
		else
		{
			$urlbloqueo="guia_bloqueo.php";
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
					if ($id_disposicion == 19 || $id_disposicion == 20 || $id_disposicion == 21) //alerta Naranja por vencimiento de descargue directo
						$color="#FF9900";
						else
							$color="#99FF66"; //vence mañana guia diferente a descargue directo
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
		}
	
	//Ubica la Posicion en Bodega
	$sql_posiscion="SELECT p.*,pc.* FROM posicion_carga pc LEFT JOIN posicion p ON pc.id_posicion=p.id WHERE pc.id_guia='$id_guia'";
	$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit('Error '.mysql_error()));
	while($fila_posicion=mysql_fetch_array($consulta_posicion))
	{
		$plaqueta=$fila_posicion['rack']."-".$fila_posicion['seccion']."-".$fila_posicion['nivel']."-".$fila_posicion['lado'];
		if ($fila_posicion['rack'] < "J")
			$mapa_destino="ubicacion_mapa.php";
		else
			$mapa_destino="ubicacion_mapa2.php";
		$posicion=$posicion." - <a href=\"$mapa_destino?id_guia=$id_guia\"><font color=\"blue\">$plaqueta</font></a>";
	}
	
	//Comprobacion de linea de fin de filtro
	if ($buffer_vuelo != 0)
	{
		if($buffer_vuelo == $fila["$campo"])
		{			
				$buffer_piezas_vuelo += $piezas_pendientes_despachar;
				$buffer_peso_vuelo += $peso_pendientes_despachar;
				$buffer_volumen_vuelo += $volumen_pendientes_despachar;
		}
		else
		{
				//Formateamos la presentacion de los valores
				$buffer_peso_vuelo=number_format($buffer_peso_vuelo,2,",",".");
				$buffer_volumen_vuelo=number_format($buffer_volumen_vuelo,2,",",".");	
				echo '<tr >
						<td align="center" style="background:#06F"><font color="white" size="+1"><strong>TOTALES</strong></font</td>
						<td align="left" colspan="12" bgcolor="black">
						<font color="white">CANTIDAD DE GUIAS='.$cont.' <> PIEZAS='.$buffer_piezas_vuelo.' <> PESO='.$buffer_peso_vuelo.'  <> VOLUMEN='.$buffer_volumen_vuelo.'</font>
						</td>
					</tr>';
				$cont=0;
				$buffer_piezas_vuelo = $piezas_pendientes_despachar;
				$buffer_peso_vuelo = $peso_pendientes_despachar;
				$buffer_volumen_vuelo = $volumen_pendientes_despachar;
		}
	}
	else
	{
			$buffer_piezas_vuelo += $piezas_pendientes_despachar;
			$buffer_peso_vuelo += $peso_pendientes_despachar;
			$buffer_volumen_vuelo += $volumen_pendientes_despachar;
	}
	$buffer_vuelo = $fila["$campo"];
	//***************************************

	//Formateamos la presentacion de los valores
	$peso_pendientes_despachar=number_format($peso_pendientes_despachar,2,",",".");
	$volumen_pendientes_despachar=number_format($volumen_pendientes_despachar,2,",",".");

	echo "
		<tr style=\"background-color:$color\">
			<td width=\"170px\" align=\"left\">
				<button type=\"button\" title=\"Bloquear o Desbloquear la Gu&iacute;a\" onclick=\"document.location='guia_selector_bloqueo.php?id_guia=$id_guia'\">B</button>
				<button type=\"button\" title=\"Acceder a la INFORMACION de la Gu&iacute;a\" onclick=\"document.location='consulta_guia.php?id_guia=$id_guia'\" $activacion1>i</button>
				<button type=\"button\" title=\"Agregar una NOVEDAD a la Gu&iacute;a\" onclick=\"openPopup('guia_novedad.php?id_guia=$id_guia','new','700','450','scrollbars=1',true);\" $activacion2>N</button>
				$imagen1
			</td>
			<td align=\"left\">$master</td>
			<td align=\"left\">$hija</td>
			<td align=\"left\">$nvuelo</td>
			<td align=\"left\">$aerolinea</td>
			<td align=\"left\" width=\"250px\">$destino</td>
			<td align=\"left\">$consignatario</td>
			<td align=\"right\">$piezas_pendientes_despachar</td>
			<td align=\"right\">$peso_pendientes_despachar</td>
			<td align=\"right\">$volumen_pendientes_despachar</td>
			<td align=\"right\">".$fila['fecha_vencimiento']."</td>
			<td align=\"left\">$nmanifiesto</td>
			<td align=\"left\">$posicion</font></td>
		</tr>";
	$posicion="";
	$cont++;

	//Aumentamos  totales GLOBALES
	$globalpiezas=$globalpiezas+$piezas_pendientes_despachar;
	//*******************************	
} 
	
//cierra el for de parciales
//calculamos totales PARCIALES DEL VUELO
$totalpformateado=number_format($totalpeso,2,",",".");
$totalvformateado=number_format($totalvolumen,2,",",".");
if ($cont != 0)
{
	//Formateamos la presentacion de los valores
	$buffer_peso_vuelo=number_format($buffer_peso_vuelo,2,",",".");
	$buffer_volumen_vuelo=number_format($buffer_volumen_vuelo,2,",",".");		
	echo '<tr >
			<td align="center" style="background:#06F"><font color="white" size="+1"><strong>TOTALES</strong></font</td>
			<td align="left" colspan="12" bgcolor="black">
			<font color="white">CANTIDAD DE GUIAS='.$cont.' <> PIEZAS='.$buffer_piezas_vuelo.' <> PESO='.$buffer_peso_vuelo.'  <> VOLUMEN='.$buffer_volumen_vuelo.'</font>
			</td>
		</tr>';
}

//Aumentamos  totales GLOBALES DEL INVENTARIO
$globalguias=$globalguias+$cont;
$globalpeso=$globalpeso+$totalpeso;
$globalvolumen=$globalvolumen+$totalvolumen;

//limpiamos contenedores PARCIALES
$cont=0;
$totalpiezas=0;
$totalpeso=0;
$totalvolumen=0;
?>
</table>
<br />
<p align="left" class="asterisco"><strong>Convenci&oacute;n de Colores</strong></p>
<table width="880" align="left">
    <tr>
        <td width="30" bgcolor="#FFFFFF">&nbsp;</td>
        <td width="270" class="celda_tabla_principal"><div class="letreros_tabla">Normal</div></td>
    <td width="23" rowspan="7" bgcolor="#FFFFFF">&nbsp;</td>
        <td colspan="3" rowspan="3" align="center" valign="middle" class="celda_tabla_principal"><font size="+3"><strong>TOTALES EN BODEGA</strong></font></td>
    <td width="27" rowspan="7" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr>
        <td bgcolor="#99FF66">&nbsp;</td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Vence Mana&ntilde;a</div></td>
    </tr>
    <tr>
        <td bgcolor="#FF9900">&nbsp;</td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Vence Terminos de Cliente</div></td>
    </tr>
    <tr>
        <td bgcolor="#FF0000">&nbsp;</td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Vence Hoy</div></td>
        <td width="243"  class="celda_tabla_principal"><div class="letreros_tabla">Total de Guias</div></td>
        <td width="139" bgcolor="#FFFFFF" align="right"><?php echo $j-1; ?></td>
    </tr>
    <tr>
        <td bgcolor="#6666CC">&nbsp;</td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Vencida</div></td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas Totales</div></td>
        <td bgcolor="#FFFFFF" align="right"><?php echo $globalpiezas ?></td>
    </tr>
    <tr>
      <td bgcolor="#FF9999">&nbsp;</td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Sobrante</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Peso Total</div></td>
      <td bgcolor="#FFFFFF" align="right"><?php echo number_format($globalpeso,2,",",".") ?></td>
      </tr>
    <tr>
        <td bgcolor="#99CCFF">&nbsp;</td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Bloqueada</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Volumen Total</div></td>
        <td bgcolor="#FFFFFF" align="right"><?php echo number_format($globalvolumen,2,",",".") ?></td>
    </tr>
</table>
</body>
</html>
<?php
mysql_free_result($consulta);
mysql_free_result($consulta3);
mysql_close($conexion);
?>