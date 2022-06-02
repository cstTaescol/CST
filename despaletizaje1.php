<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//require("config/control_tiempo.php");
$impresion="";
$impresion2="";

//Privielegios de Objeto
//Aplica para elboton de registro fotografico de bodega.
$id_objeto=98; 
include("config/provilegios_objeto.php");
//******************************************************
if(isset($_REQUEST["vuelo"]))
	{
		$id_vuelo=$_REQUEST["vuelo"];
		$sql3="SELECT * FROM vuelo WHERE id='$id_vuelo'";
		//Escribirla sobre el archivo actualizado con las alertas de sobrantes
		
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$id_aerolinea=$fila3["id_aerolinea"];
		$fecha_arribo=$fila3["fecha_arribo"];
		$despaletizaje_inicio=$fila3["despaletizaje_inicio"];
		$despaletizaje_fin=$fila3["despaletizaje_fin"];
		$despaletizaje_cantidad_pallets=$fila3["despaletizaje_cantidad_pallets"];
		$despaletizaje_coordinador_tae=$fila3["despaletizaje_coordinador_tae"];
		$despaletizaje_coordinador_seguridad=$fila3["despaletizaje_coordinador_seguridad"];
		$despaletizaje_observaciones=$fila3["despaletizaje_observaciones"];
		$despaletizaje_auxiliar1=$fila3["despaletizaje_auxiliar1"];
		$despaletizaje_auxiliar2=$fila3["despaletizaje_auxiliar2"];
		$despaletizaje_auxiliar3=$fila3["despaletizaje_auxiliar3"];
		$despaletizaje_operario1=$fila3["despaletizaje_operario1"];
		$despaletizaje_operario2=$fila3["despaletizaje_operario2"];
		$despaletizaje_operario3=$fila3["despaletizaje_operario3"];
		$despaletizaje_comercio_exterior=$fila3["despaletizaje_comercio_exterior"];
		$despaletizaje_elaboradopor=$fila3["despaletizaje_elaboradopor"];
		$despaletizaje_elaboradopor_cargo=$fila3["despaletizaje_elaboradopor_cargo"];
		$despaletizaje_fecha_hora_doc=$fila3["despaletizaje_fecha_hora_doc"];
		$datos_fecha_hora=explode(" ",$despaletizaje_fecha_hora_doc);
		
		
		$fecha_save=explode("-",$fecha_arribo);
		$ano=$fecha_save[0];
		$mes=$fecha_save[1];
		$dia=$fecha_save[2];

		$ano2=$fecha_save[0];
		$mes2=$fecha_save[1];
		$dia2=$fecha_save[2];

		$hora_llegada=$fila3["hora_llegada"];
		$hora_save=explode(":",$hora_llegada);
		$hh=$hora_save[0];
		$mm=$hora_save[1];
		$ss=$hora_save[2];

		$hh2=$hora_save[0];
		$mm2=$hora_save[1];
		$ss2=$hora_save[2];
		
		//Calculamos las horas y fechas de despaletizaje
		//------------------------------------------------------------------------------------------------------
		$adicionar_horas=12; // Horas Dian
		include("config/evaluador_depaletizaje.php");
		$fecha_dian=explode("-",$fecha_limite);
		$hora_dian=explode(":",$hora_limite);
		$ano_dian=$fecha_dian[0];
		$mes_dian=$fecha_dian[1];
		$dia_dian=$fecha_dian[2];
		$hh_dian=$hora_dian[0];
		$mm_dian=$hora_dian[1];
		$ss_dian=$hora_dian[2];
		switch($mes_dian){
			case(1):
				$mes_dian="Jan";
			break;
			case(2):
				$mes_dian="Feb";
			break;
			case(3):
				$mes_dian="Mar";
			break;
			case(4):
				$mes_dian="Apr";
			break;
			case(5):
				$mes_dian="May";
			break;
			case(6):
				$mes_dian="Jun";
			break;
			case(7):
				$mes_dian="Jul";
			break;
			case(8):
				$mes_dian="Aug";
			break;
			case(9):
				$mes_dian="Sep";
			break;
			case(10):
				$mes_dian="Oct";
			break;
			case(11):
				$mes_dian="Nov";
			break;
			case(12):
				$mes_dian="Dec";
			break;
		}
		
		//------------------------------------------------------------------------------------------------------
		//Consultas
		$sql2="SELECT nombre,horas_despaletizaje FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$id_aerolinea=$fila2["nombre"];
		$horas_despaletizaje=$fila2["horas_despaletizaje"];
		//*********************
		
		//Calculamos las horas y fechas de despaletizaje x Aerolinea
		//------------------------------------------------------------------------------------------------------
		$adicionar_horas2=$horas_despaletizaje; // Horas Aero
		include("config/evaluador_depaletizaje_aero.php");
		$fecha_aero=explode("-",$fecha_limite);
		$hora_aero=explode(":",$hora_limite);
		$ano_aero=$fecha_aero[0];
		$mes_aero=$fecha_aero[1];
		$dia_aero=$fecha_aero[2];
		$hh_aero=$hora_aero[0];
		$mm_aero=$hora_aero[1];
		$ss_aero=$hora_aero[2];
		switch($mes_aero){
			case(1):
				$mes_aero="Jan";
			break;
			case(2):
				$mes_aero="Feb";
			break;
			case(3):
				$mes_aero="Mar";
			break;
			case(4):
				$mes_aero="Apr";
			break;
			case(5):
				$mes_aero="May";
			break;
			case(6):
				$mes_aero="Jun";
			break;
			case(7):
				$mes_aero="Jul";
			break;
			case(8):
				$mes_aero="Aug";
			break;
			case(9):
				$mes_aero="Sep";
			break;
			case(10):
				$mes_aero="Oct";
			break;
			case(11):
				$mes_aero="Nov";
			break;
			case(12):
				$mes_aero="Dec";
			break;
		}
		
		$nvuelo=$fila3["nvuelo"];
		$nmanifiesto=$fila3["nmanifiesto"];
		$matricula=$fila3["matricula"];
	}
	else
		{
			$vuelo= "Error al obtener el Vuelo";
			exit();
		}
$total_peso=0;


//Si se realiza una busqueda de guia
if(isset($_REQUEST['guia']))
{
	$guia=$_REQUEST['guia'];
	if ($guia != "")
		$sql_filtro="AND (hija LIKE '%$guia%')";
	else
		$sql_filtro="";
}
else
{
	$sql_filtro="";
}
//************************************

$sql="SELECT hija,master,piezas,peso,id,id_disposicion,id_deposito,faltante_total,cuarto_frio FROM guia WHERE id_vuelo = '$id_vuelo' AND id_tipo_guia != '2' AND id_tipo_bloqueo = '2' $sql_filtro ORDER BY cuarto_frio DESC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nguias=mysql_num_rows($consulta);	
if ($nguias > 0)
{
	for ($i=1; $i<=$nguias; $i++)
	{
		$fila=mysql_fetch_array($consulta);
		$master=$fila["master"];
		include("config/master.php");
		$id_guia=$fila["id"];
		$hija=$fila["hija"];
		$cuarto_frio=$fila["cuarto_frio"];
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		$total_peso += $peso;
		$faltante_total=$fila["faltante_total"];
		if ($faltante_total=="S")
		{
			$faltante_total='checked="checked"';
			$btn_despaletizaje='disabled="disabled"';
		}
		else
			{
				$faltante_total="";
				$btn_despaletizaje="";
			}

		// Destino
		$id_disposicion=$fila["id_disposicion"];
		//Evaluar si la disposicion no exige ningun tipo de deposito
		if ($id_disposicion ==28 || $id_disposicion ==21 || $id_disposicion ==20 || $id_disposicion ==19 || $id_disposicion ==25 || $id_disposicion ==29 || $id_disposicion ==23 || $id_disposicion ==13 || $id_disposicion ==15)
			{
				$sql3="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
				$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila3=mysql_fetch_array($consulta3);
				$destino=$fila3["nombre"];
			}
		else
		{
			$id_deposito=$fila["id_deposito"];
			$sql3="SELECT nombre FROM deposito WHERE id='$id_deposito'";
			$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila3=mysql_fetch_array($consulta3);
			$destino=$fila3["nombre"];
		}
		if ($id_disposicion == 26 || $id_disposicion == 27) 
		{
			$destino="<font color=\"green\">Bodega Dian</green>";
		}
		include("lib_despaletizaje_valores.php");
		//Consulta para cargar las piezas y peso que ya se despaletizaron de esta guia.
		$sql3="SELECT SUM(piezas_recibido) AS total_piezas, SUM(peso_recibido) AS total_peso FROM despaletizaje WHERE id_vuelo='$id_vuelo' AND id_guia='$id_guia'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$piezas_recibido=$fila3["total_piezas"];
		$peso_recibido=round($fila3["total_peso"],2);
		if ($piezas_recibido == "")$piezas_recibido=0;
		if ($peso_recibido == "")$peso_recibido=0;
		
		//Dëfinir Rango y tono de fondo de PORCENTAJE X Guia 
		$porcentaje=(($peso_recibido * 100)/$peso)-100;
		$porcentaje=number_format($porcentaje,1)."%";
		if (($porcentaje >= 5) || ($porcentaje <= -5))
			$color_porcentaje="red";
		else
			$color_porcentaje="#FFFFFF";
		//******************************************************************************
		
		//Ayuda visual para guia completa y refrigerada
		if ($piezas_recibido >= $piezas) 
			$imgchek='<img src="imagenes/check_green.png" width="20" height="20" align="absmiddle" title="Piezas Completas"  />';
		else
			$imgchek="";

		if ($cuarto_frio == "S")
		{ 
			$imgCF='<img src="imagenes/ice.fw.png" width="20" height="20" align="absmiddle" title="Cuarto Frio"  />';
			$colorCF='';
		}
		else
			{
				$imgCF="";
				$colorCF='bgcolor="#FFFFFF"';
			}
		//*******************************************************************************
		
		$impresion=$impresion."
		  <tr $colorCF>
			<td>$i $imgchek$imgCF</td>\n
			<td>$master</td>\n
			<td><div style=\"text-decoration:underline;\"><a href=\"consulta_guia.php?id_guia=$id_guia\">$hija</a></div></td>\n
			<td>$destino</td>\n
			<td>$piezas</td>\n
			<td>$peso</td>\n
			<td align=\"center\" valign=\"middle\">$piezas_recibido</td>\n
			<td align=\"center\" valign=\"middle\">$peso_recibido</td>\n
			<td align=\"center\" bgcolor=\"$color_porcentaje\"><strong>$porcentaje</strong></td>\n		
			<td align=\"center\" valign=\"middle\">
				<button style=\"width:40px; height:40px\" type=\"button\" onclick=\"openPopup('despaletizaje5_faltantetotal_popup.php?id_guia=$id_guia','new','900','450','scrollbars=1',true);\" title=\"Faltante Total $hija\">
					F.T
				</button>        
				<input type=\"checkbox\" style=\"background-color:#900\" value=\"S\" name=\"faltante_total\" $faltante_total disabled=\"disabled\"/>				
			</td>\n
			<td align=\"center\" valign=\"middle\">
				<button type=\"button\" onclick=\"document.location='despaletizaje2_popup.php?id_vuelo=$id_vuelo&id_guia=$id_guia'\",'new','900','450','scrollbars=1',true);\" title=\"Despaletizaje $hija\" $btn_despaletizaje>
					<img src=\"imagenes/quitar_link-act.png\" width=\"40\" height=\"40\" align=\"absmiddle\"  />
				</button>        
				
			</td>\n
		  </tr>
		";
	}
}
$total_peso_sobrante=0;
$sql="SELECT * FROM despaletizaje_sobantes WHERE id_vuelo='$id_vuelo' AND estado='A'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nguias=mysql_num_rows($consulta);	
if ($nguias > 0)
{
	for ($i=1; $i<=$nguias; $i++)
	{
		$fila=mysql_fetch_array($consulta);
		$id_sobrante=$fila["id"];
		$sobrante_guia=$fila["guia"];
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		$volumen=$fila["volumen"];
		$total_peso_sobrante +=$peso;
		$impresion2=$impresion2."
		  <tr>
			<td align=\"center\" bgcolor=\"#FFFFFF\"><strong>$i</strong></td>
			<td align=\"center\" bgcolor=\"#FFFFFF\"><strong>$sobrante_guia</strong></td>
			<td align=\"center\" bgcolor=\"#FFFFFF\"><strong>$piezas</strong></td>
			<td align=\"center\" bgcolor=\"#FFFFFF\"><strong>$peso</strong></td>
			<td align=\"center\" bgcolor=\"#FFFFFF\"><strong>$volumen</strong></td>			
			<td align=\"center\" bgcolor=\"#FFFFFF\">
				<button type=\"button\" onclick=\"openPopup('guia_sobrante_eliminar.php?id_sobrante=$id_sobrante','new','900','450','scrollbars=1',true);\",'new','900','450','scrollbars=1',true);\">
					<img src=\"imagenes/eliminar-act.png\" width=\"32\" height=\"29\" align=\"absmiddle\"  />
				</button>        
			</td>
		  </tr>";
	}
}

$msg_alerta_sobrante="";
//Calculo de PORCENTAJE sobrante X Vuelo
$porcentaje_sobrante=($total_peso_sobrante * 100) / $total_peso;
$porcentaje_sobrante=number_format($porcentaje_sobrante,1)."%";
//$msg_alerta_sobrante -= $total_peso_sobrante;
if (($porcentaje_sobrante >= 20))
	$msg_alerta_sobrante .='<br><img src="imagenes/alerta_red.gif" height="28" width="37" align="absmiddle"/><span style="color:red">Alerta:</span>Sobrepas&oacute; el limite de PORCENTAJE Total de Guias sobrantes en '.$porcentaje_sobrante.'
							<script>alert("ALERTA: Sobrepaso el PORCENTAJE Total de peso Guias sobrante")</script>
							';
else
	$msg_alerta_sobrante .='PORCENTAJE Total de Guias sobrante. '.$porcentaje_sobrante.'';
//**************************************
//Alerta de Conteo de Guias Sobrantes
if ($nguias > 5)
{
	$msg_alerta_sobrante .='<br><img src="imagenes/alerta_red.gif" height="28" width="37" align="absmiddle"/><span style="color:red">Alerta:</span>Sobrepaso el limite de Guias Sobrantes
							<script>alert("ALERTA: Sobrepaso el LIMITE de Guias Sobrantes")</script>
							';
}
//**************************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    /*Codigo para Hover Desplegable*/
    #show,#hide {
        display:none;
    }
    
    div#content {
      display:none;
      padding:10px;
      background-color:#f6f6f6;
      width:100%;
      cursor:pointer;
    }
    
    input#show:checked ~ div#content {
        display:block;
    }
    
    input#hide:checked ~ div#content {
        display:none;
    }
	/* Cronometro del modulo*/
	.cronometro{
		color:#009900; 
		font-size:x-large; 
		font-weight:bold;
		background-color:#000
	}
</style>

</head>

<body onLoad="getTime()">
<?php 
include("menu.php");
?>
<p class="titulo_tab_principal">Planilla de Despaletizaje</p>
<table width="90%" align="center" class="celda_tabla_principal">
  <tr>
    <td colspan="3" align="center" valign="middle"><font size="-1" color="#009900"><strong><em>TIEMPO RESTANTE PARA FINALIZAR DESPALETIZAJE</em></strong></font></td>
  </tr>
  <tr>
    <td width="40%" align="center" valign="middle">
    	<p>
        	<strong>AEROLINEA</strong><br />
      		<font color="#0066CC" size="-1"><?php echo $id_aerolinea ?></font>
        </p>
      <table border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center" valign="middle" bgcolor="#CCCCCC"><em>DIAS</em></td>
          <td align="center" valign="middle" bgcolor="#CCCCCC"><em>hh</em></td>
          <td align="center" valign="middle" bgcolor="#CCCCCC"><em>mm</em></td>
          <td align="center" valign="middle" bgcolor="#CCCCCC"><em>ss</em></td>
        </tr>
        <tr>
          <td align="center" valign="middle"><div class="cronometro"  id="dias2"></div></td>
          <td align="center" valign="middle"><div class="cronometro" id="horas2"></div></td>
          <td align="center" valign="middle"><div class="cronometro" id="minutos2"></div></td>
          <td align="center" valign="middle"><div class="cronometro" id="segundos2"></div></td>
        </tr>
    </table>
    </td>
    <td width="20%" align="center" valign="middle"><p><img src="imagenes/cronometro1.gif" alt="Tiempo restante estipulado por la Dian para finalizar el despaletizaje" width="64" height="80" align="absmiddle" />	</td>
    <td width="40%" align="center" valign="middle"><img src="imagenes/logo_dian.jpg" width="151" height="61" />
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" bgcolor="#CCCCCC"><em>DIAS</em></td>
    <td align="center" valign="middle" bgcolor="#CCCCCC"><em>hh</em></td>
    <td align="center" valign="middle" bgcolor="#CCCCCC"><em>mm</em></td>
    <td align="center" valign="middle" bgcolor="#CCCCCC"><em>ss</em></td>
    </tr>
  <tr>
    <td align="center" valign="middle"><div class="cronometro" id="dias"></div></td>
    <td align="center" valign="middle"><div class="cronometro" id="horas"></div></td>
    <td align="center" valign="middle"><div class="cronometro" id="minutos"></div></td>
    <td align="center" valign="middle"><div class="cronometro" id="segundos"></div></td>
    </tr>
</table></td>
  </tr>
</table>
<hr />


<table align="center" width="90%">
  <tr>
    <td colspan="2" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Datos del Vuelo</div></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Vuelo</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $nvuelo?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Matr&iacute;cula</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $matricula ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerol&iacute;nea</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $id_aerolinea ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Manifiesto</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $nmanifiesto ?></td>
  </tr>
</table>
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post" name="buscar_guia">
<input type="hidden" name="vuelo" id="vuelo" value="<?php echo $id_vuelo; ?>" />
<table width="450" align="center">
    <tr>
		<td colspan="4" align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
    	<td class="celda_tabla_principal">No. Gu&iacute;a</td>
        <td class="celda_tabla_principal celda_boton"><input type="text" name="guia" id="guia" size="20"></td>
        <td align="center" valign="middle" class="celda_tabla_principal celda_boton">            
            <button name="activo" type="submit">
                <br />
                <img src="imagenes/buscar-act.png" title="Buscar la Guia" width="40" height="40"/><br />
              	<strong>Buscar</strong>
            </button>
        </td>
        <td align="center" valign="middle" class="celda_tabla_principal celda_boton">    
            <button type="button" onclick="openPopup('pdf_despaletizaje.php?id_vuelo=<?php echo $id_vuelo ?>','new','900','450','scrollbars=1',true);" <?php  $id_objeto=108; include("config/provilegios_objeto.php");  echo $activacion ?>><br />
                <img src="imagenes/pdf.jpg" title="Reporte Parcial" width="40" height="40"/>
                <strong>Reporte</strong>
            </button>								
        </td>
    </tr>
</table>
</form>

<table width="90%" class="celda_tabla_principal" align="center">
  <tr>
    <td colspan="8" class="celda_tabla_principal"><div class="letreros_tabla">Verificaion de Datos</div></td>
    <td rowspan="3" align="center" valign="middle" class="celda_tabla_principal">%</td>
    <td rowspan="3" align="center" valign="middle" class="celda_tabla_principal"><img src="imagenes/titulo_FALTO.jpg" width="15" height="125" /></td>
    <td rowspan="3" align="center" valign="middle"class="celda_tabla_principal"><img src="imagenes/titulo_DESPALETIZAJE.jpg" width="15" height="125"/></td>
  </tr>
  <tr>
    <td rowspan="2" class="celda_tabla_principal celda_boton"><strong>No.</strong></td>
    <td rowspan="2" class="celda_tabla_principal celda_boton"><strong>MASTER</strong></td>
    <td rowspan="2" class="celda_tabla_principal celda_boton"><strong>HIJA</strong></td>
    <td rowspan="2" class="celda_tabla_principal celda_boton"><strong>DESTINO</strong></td>
    <td colspan="2" align="center" bgcolor="#99CC00"><strong>DIGITADOS</strong></td>
    <td colspan="2" align="center" bgcolor="#FF9900"><strong>DESPALETIZADOS</strong></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#99CC00"><strong>PIEZAS</strong></td>
    <td align="center" bgcolor="#99CC00"><strong>PESO</strong></td>
    <td align="center" bgcolor="#FF9900"><strong>PIEZAS</strong></td>
    <td align="center" bgcolor="#FF9900"><strong>PESO</strong></td>
  </tr>
  <?php echo $impresion ?>
</table>
<hr />
<table width="90%" class="celda_tabla_principal" align="center">
  <tr>
    <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Guias Sobrantes</div></td>
    <td colspan="4" class="celda_tabla_principal celda_boton">
    	<div class="letreros_tabla">
            <button type="button" onclick="openPopup('guia_sobrante_nueva.php?id_vuelo=<?php echo $id_vuelo ?>','new','900','450','scrollbars=1',true);">
                <img src="imagenes/agregar-act.png" width="32" height="29" align="absmiddle"  />
                Agregar
            </button>        
        </div>
    </td>
    </tr>
  <tr>
    <td width="10%" class="celda_tabla_principal"><div class="letreros_tabla">No.</div></td>
    <td width="50%" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td width="10%" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td width="10%" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td width="10%" class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
    <td width="10%" class="celda_tabla_principal"><div class="letreros_tabla">Eliminar</div></td>
  </tr>
  <?php echo $impresion2 ?>
</table>
<table width="90%" class="celda_tabla_principal" align="center">
	<tr>
    	<td><div class="letreros_tabla asterisco"><div align="left"><?php echo $msg_alerta_sobrante ?></div></div></td>
    </tr>    
</table>
<hr />
<p align="center">
	<font size="+2" color="#0066FF"><strong>DATOS DEL PERSONAL DE DESPALETIZAJE</strong></font>	
</p>
<div align="center">
    <label for="show"><span><img src="imagenes/agregar-act.png" title="Abrir Secci&oacute;n" height="60" /></span></label><input type="radio" id="show" name="group">
    <label for="hide"><span><img src="imagenes/quitar-act.png" title="Cerrar Secci&oacute;n" height="60"/></span></label><input type="radio" id="hide" name="group">
    <div id="content" style="background-image:url(imagenes/background.png);">
        <form name="personal_despeltizaje" id="personal_despeltizaje" method="post">
                <input type="hidden" name="vuelo" id="vuelo" value="<?php echo $id_vuelo; ?>" />
                <table width="90%" class="celda_tabla_principal" align="center">
                    <tr>
                        <td class="celda_tabla_principal"><div class="letreros_tabla">Despaletizaje</td>
                        <td class="celda_tabla_principal"><div class="letreros_tabla">
                            Hora Inicio<input type="time" name="despaletizaje_inicio" id="despaletizaje_inicio" maxlength="5" size="5" placeholder="00:00" value="<?php echo $despaletizaje_inicio ?>"/>
                            Hora Final<input type="time" name="despaletizaje_fin" id="despaletizaje_fin" maxlength="5" size="5" placeholder="00:00" value="<?php echo $despaletizaje_fin ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="celda_tabla_principal"><div class="letreros_tabla">Coordinador <?php echo CLIENTE; ?></div></td>
                        <td class="celda_tabla_principal celda_boton"><input type="text" name="despaletizaje_coordinador_tae" id="despaletizaje_coordinador_tae" maxlength="150" size="40" placeholder="Nombre y Apellido" value="<?php echo $despaletizaje_coordinador_tae; ?>"/></td>
                    </tr>
                    <tr>
                        <td class="celda_tabla_principal"><div class="letreros_tabla">Coordinador Seguridad</div></td>
                        <td class="celda_tabla_principal celda_boton"><input type="text" name="despaletizaje_coordinador_seguridad" id="despaletizaje_coordinador_seguridad" maxlength="150" size="40" placeholder="Nombre y Apellido"  value="<?php echo $despaletizaje_coordinador_seguridad ?>"/></td>
                    </tr>
                    <tr>
                        <td class="celda_tabla_principal"><div class="letreros_tabla">Cantidad de Pallets</div></td>
                        <td class="celda_tabla_principal celda_boton"><input type="number" name="despaletizaje_cantidad_pallets" id="despaletizaje_cantidad_pallets" maxlength="2" size="2"  value="<?php echo $despaletizaje_cantidad_pallets ?>"/></td>
                    </tr>
                    <tr>
                        <td class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
                        <td class="celda_tabla_principal celda_boton"><textarea name="despaletizaje_observaciones" id="despaletizaje_observaciones" cols="45" rows="5"><?php echo $despaletizaje_observaciones ?></textarea></td>
                    </tr>
                    <tr>
                        <td class="celda_tabla_principal"><div class="letreros_tabla">Auxiliares</div></td>
                        <td class="celda_tabla_principal celda_boton">
                            -<input type="text" name="despaletizaje_auxiliar1" id="despaletizaje_auxiliar1" maxlength="150" size="40" placeholder="Nombre y Apellido" value="<?php echo $despaletizaje_auxiliar1 ?>"/><br />
                            -<input type="text" name="despaletizaje_auxiliar2" id="despaletizaje_auxiliar2" maxlength="150" size="40" placeholder="Nombre y Apellido" value="<?php echo $despaletizaje_auxiliar2 ?>"/><br />
                            -<input type="text" name="despaletizaje_auxiliar3" id="despaletizaje_auxiliar3" maxlength="150" size="40" placeholder="Nombre y Apellido" value="<?php echo $despaletizaje_auxiliar3 ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="celda_tabla_principal"><div class="letreros_tabla">Operarios</div></td>
                        <td class="celda_tabla_principal celda_boton">
                            -<input type="text" name="despaletizaje_operario1" id="despaletizaje_operario1" maxlength="150" size="40" placeholder="Nombre y Apellido" value="<?php echo $despaletizaje_operario1 ?>"/><br />
                            -<input type="text" name="despaletizaje_operario2" id="despaletizaje_operario2" maxlength="150" size="40" placeholder="Nombre y Apellido" value="<?php echo $despaletizaje_operario2 ?>"/><br />
                            -<input type="text" name="despaletizaje_operario3" id="despaletizaje_operario3" maxlength="150" size="40" placeholder="Nombre y Apellido" value="<?php echo $despaletizaje_operario3 ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="celda_tabla_principal"><div class="letreros_tabla">Comercio Exterior</div></td>
                        <td class="celda_tabla_principal celda_boton"><input type="text" name="despaletizaje_comercio_exterior" id="despaletizaje_comercio_exterior" maxlength="150" size="40" placeholder="Nombre y Apellido" value="<?php echo $despaletizaje_comercio_exterior ?>"/></td>
                    </tr>
                    <tr>
                        <td class="celda_tabla_principal"><div class="letreros_tabla">Elaborado Por</div></td>
                        <td class="celda_tabla_principal celda_boton">
                            <input type="text" name="despaletizaje_elaboradopor" id="despaletizaje_elaboradopor" maxlength="150" size="40" placeholder="Nombre y Apellido" value="<?php echo $despaletizaje_elaboradopor ?>"/><br />
                            <input type="text" name="despaletizaje_elaboradopor_cargo" id="despaletizaje_elaboradopor_cargo" maxlength="150" size="40" placeholder="Cargo" value="<?php echo $despaletizaje_elaboradopor_cargo ?>"/><br />
                            <input type="date" name="despaletizaje_fecha_doc" id="despaletizaje_fecha_doc" value="<?php echo $datos_fecha_hora[0] ?>" /><input type="time" name="despaletizaje_hora_doc" id="despaletizaje_hora_doc" value="<?php echo $datos_fecha_hora[1] ?>"/>Fecha y Hora
                        </td>
                    </tr>    
                </table>

                <div id="respuesta"></div>
                <table width="450" align="center">
                    <tr>
                      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
                    </tr>
                    <tr>
                      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
                        <button type="button" tabindex="15" name="atras" id="atras" onclick="document.location='vuelo_seleccionar.php?opcion=despaletizaje'">
                            <img src="imagenes/al_principio-act.png" title="Atras" />
                        </button>
                        <button type="reset"  tabindex="14" name="reset" id="reset">
                            <img src="imagenes/descargar-act.png" title="Limpiar" />
                        </button>
                        <button type="button" tabindex="13" name="guardar" id="guardar" onClick="return validar();">
                            <img src="imagenes/guardar-act.png" title="Guardar" />
                        </button>
                      </td>
                    </tr>
                </table>
                
        </form>
    </div>            
</div>
</body>
</html>
<script language="javascript">
// funcion para validar
function validar()
{
	/*
	if (document.forms[0].levante.value=="")
	{
		alert("Atencion: Debe digitar un Numero de Levante");
		document.forms[0].levante.focus();
		return(false);
	}
	*/
	guardar_form();
}

function guardar_form()
{
	var peticion = new Request(
	{
		url: 'despaletizaje8_gaurdar_personal.php',
		method: 'post',
		onRequest: function()
		{
			mostrar_div($('respuesta'));
			$('respuesta').innerHTML='<p align="center">Procesando...<image src="imagenes/cargando.gif"></p>';
			$('guardar').disabled=true;
			$('reset').disabled=true;
			
		},			
		onSuccess: function(responseText)
		{
			
			var respuesta=responseText;
			var arreglos = respuesta.split("-");
			var accion = eval(arreglos[0]);
			var coderror = eval(arreglos[1]);
			var mensaje = arreglos[3];
			if (accion == 1)
			{
				var id_despacho = eval(arreglos[2]);
				$('respuesta').innerHTML='<p align="center">Registro Almacenado</p>';
				//document.location='despacho_directo4.php?id_registro='+id_despacho;
				$('guardar').disabled=false;
				$('reset').disabled=false;
			}
			else
			{
				$('respuesta').innerHTML='<p align="center">Error Codigo '+ coderror + mensaje +' al guardar, Intente de nuevo...</p>';
				$('guardar').disabled=false;
				$('reset').disabled=false;
			}
			
		},
		onFailure: function()
		{
			$('respuesta').innerHTML='<p align="center">Error al guardar, Intente de nuevo...</p>';
			$('guardar').disabled=false;
			$('reset').disabled=false;
		}
	}
	);
	peticion.send($('personal_despeltizaje'));
}

function mostrar_div(id_div)
{
	id_div.set('morph',{ 
	duration: 200, 
	transition: 'linear'
	});
	id_div.morph({
		'opacity': 1 
	});
}
</script>
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
</script>
<script language="javascript">
function getTime() 
{
	now = new Date();
	//y2k = new Date("july 11 2012 8:00:00");
	y2k = new Date("<?php echo "$mes_dian $dia_dian $ano_dian $hh_dian:$mm_dian:$ss_dian";?>");
	days = (y2k - now) / 1000 / 60 / 60 / 24;
	daysRound = Math.floor(days);
	hours = (y2k - now) / 1000 / 60 / 60 - (24 * daysRound);
	hoursRound = Math.floor(hours);
	minutes = (y2k - now) / 1000 /60 - (24 * 60 * daysRound) - (60 * hoursRound);
	minutesRound = Math.floor(minutes);
	seconds = (y2k - now) / 1000 - (24 * 60 * 60 * daysRound) - (60 * 60 * hoursRound) - (60 * minutesRound);
	secondsRound = Math.round(seconds);
	if(daysRound < 0 )
	{
		document.getElementById("dias").innerHTML = "Vencido";
		document.getElementById("horas").innerHTML = "-";
		document.getElementById("minutos").innerHTML = '<img src="imagenes/btn_rojo.gif" width="20" height="20" />';
		document.getElementById("segundos").innerHTML = "-";
		
	}
	else
		{
			document.getElementById("dias").innerHTML = daysRound;
			document.getElementById("horas").innerHTML = hoursRound+":";
			document.getElementById("minutos").innerHTML = minutesRound+":";
			document.getElementById("segundos").innerHTML = secondsRound;
		}
	
//	newtime = window.setTimeout("getTime();", 1000);	

	now = new Date();
	y2k = new Date("<?php echo "$mes_aero $dia_aero $ano_aero $hh_aero:$mm_aero:$ss_aero";?>");
	days = (y2k - now) / 1000 / 60 / 60 / 24;
	daysRound = Math.floor(days);
	hours = (y2k - now) / 1000 / 60 / 60 - (24 * daysRound);
	hoursRound = Math.floor(hours);
	minutes = (y2k - now) / 1000 /60 - (24 * 60 * daysRound) - (60 * hoursRound);
	minutesRound = Math.floor(minutes);
	seconds = (y2k - now) / 1000 - (24 * 60 * 60 * daysRound) - (60 * 60 * hoursRound) - (60 * minutesRound);
	secondsRound = Math.round(seconds);
	if(daysRound < 0 )
	{
		document.getElementById("dias2").innerHTML = "Vencido";
		document.getElementById("horas2").innerHTML = "-";
		document.getElementById("minutos2").innerHTML = '<img src="imagenes/btn_rojo.gif" width="20" height="20" />';
		document.getElementById("segundos2").innerHTML = "-";
		
	}
	else
		{
			document.getElementById("dias2").innerHTML = daysRound;
			document.getElementById("horas2").innerHTML = hoursRound+":";
			document.getElementById("minutos2").innerHTML = minutesRound+":";
			document.getElementById("segundos2").innerHTML = secondsRound;
		}
	newtime = window.setTimeout("getTime();", 1000);

}
</script>
