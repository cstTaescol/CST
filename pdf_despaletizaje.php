<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$totalpiezas=0;
$totalpeso=0;
$totalpiezas_bascula=0;
$totalpeso_bascula=0;
$impresion="";

//carga datos del documento
//Carga de Líneas
if(isset($_REQUEST['id_vuelo']))
{
	//Consulta datos del vuelo
	$id_vuelo=$_REQUEST['id_vuelo'];
	$sql="SELECT * FROM vuelo WHERE id = '$id_vuelo'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$fecha_arribo=$fila["fecha_arribo"];
	$hora_llegada=$fila["hora_llegada"];
	$nvuelo=$fila["nvuelo"];
	$despaletizaje_inicio=$fila["despaletizaje_inicio"];
	$despaletizaje_fin=$fila["despaletizaje_fin"];
	$despaletizaje_coordinador_tae=$fila["despaletizaje_coordinador_tae"];
	$despaletizaje_cantidad_pallets=$fila["despaletizaje_cantidad_pallets"];
	$despaletizaje_coordinador_seguridad=$fila["despaletizaje_coordinador_seguridad"];
	$despaletizaje_observaciones=$fila["despaletizaje_observaciones"];
	$despaletizaje_auxiliar1=$fila["despaletizaje_auxiliar1"];
	$despaletizaje_auxiliar2=$fila["despaletizaje_auxiliar2"];
	$despaletizaje_auxiliar3=$fila["despaletizaje_auxiliar3"];
	$despaletizaje_operario1=$fila["despaletizaje_operario1"];
	$despaletizaje_operario2=$fila["despaletizaje_operario2"];
	$despaletizaje_operario3=$fila["despaletizaje_operario3"];
	$despaletizaje_comercio_exterior=$fila["despaletizaje_comercio_exterior"];
	$despaletizaje_elaboradopor=$fila["despaletizaje_elaboradopor"];
	$despaletizaje_elaboradopor_cargo=$fila["despaletizaje_elaboradopor_cargo"];
	$despaletizaje_fecha_hora_doc=$fila["despaletizaje_fecha_hora_doc"];
	
	//Consulta Guia
	$sql="SELECT * FROM guia WHERE id_vuelo = '$id_vuelo'  AND id_tipo_guia != '2'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		$id_guia=$fila["id"];
		$master=$fila["master"];
		include("config/master.php");
		$hija=$fila["hija"];
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		$busqueda = array("<hr>", "<br />");
		$observaciones_bodega=trim(str_replace($busqueda," ",$fila["observaciones_bodega"]));
		$totalpiezas += $piezas;
		$totalpeso += $peso;
		
		//Consulta para cargar las piezas y peso que ya se despaletizaron de esta guia.		
		include("lib_despaletizaje_valores.php");
		$piezas_faltantes=$piezas-$piezas_recibido;
		$peso_faltante=$peso-$peso_recibido;
		$totalpiezas_bascula +=$piezas_recibido;
		$totalpeso_bascula +=$peso_recibido;

		//Definir Rango y tono de fondo de PORCENTAJE X Guia 
		$porcentaje=(($peso_recibido * 100)/$peso)-100;
		$porcentaje=number_format($porcentaje,1)."%";

		//Ubica la Posicion en Bodega
		$posicion="";
		$sql_posiscion="SELECT p.*,pc.* FROM posicion_carga pc LEFT JOIN posicion p ON pc.id_posicion=p.id WHERE pc.id_guia='$id_guia'";
		$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit('Error '.mysql_error()));
		while($fila_posicion=mysql_fetch_array($consulta_posicion))
		{
			$plaqueta=$fila_posicion['rack'] ."-". $fila_posicion['seccion'] ."-". $fila_posicion['nivel'] ."-". $fila_posicion['lado'] ."-". $fila_posicion['fondo'];
			$posicion=$posicion." / ".$plaqueta;
		}
		//**************************************
		
		//CARGA DE TIPOS DE EMBALAJE Y ESTADO
		$estiba="";
		$huacal="";
		$yute="";
		$caja="";
		$caneca="";
		$larguero="";
		$abollada="";
		$recintada="";
		$abierta="";
		$rota="";
		$destilacion="";
		$humeda="";
		$observacion="";
		
		$sql2="SELECT * FROM despaletizaje WHERE id_guia = '$id_guia'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		while($fila2=mysql_fetch_array($consulta2))
		{
			if($fila2['estiba']=="S")$estiba="X";
			if($fila2['huacal']=="S")$huacal="X";
			if($fila2['yute']=="S")$yute="X";
			if($fila2['caja']=="S")$caja="X";
			if($fila2['caneca']=="S")$caneca="X";
			if($fila2['larguero']=="S")$larguero="X";
			if($fila2['abollada']=="S")$abollada="X";
			if($fila2['recintada']=="S")$recintada="X";
			if($fila2['abierta']=="S")$abierta="X";
			if($fila2['rota']=="S")$rota="X";
			if($fila2['destilacion']=="S")$destilacion="X";
			if($fila2['humeda']=="S")$humeda="X";
		}
		$impresion .= "
		  <tr>
			<td>$master</td>
			<td>$hija</td>
			<td>$piezas</td>
			<td>$peso</td>
			<td>$piezas_recibido</td>
			<td>$peso_recibido</td>
			<td>$piezas_faltantes</td>
			<td>$peso_faltante</td>
			<td>$porcentaje</td>
			<td>$caja</td>
			<td>$yute</td>
			<td>$estiba</td>
			<td>$caneca</td>
			<td>$huacal</td>
			<td>$larguero</td>
			<td>$rota</td>
			<td>$abollada</td>
			<td>$abierta</td>
			<td>$humeda</td>
			<td>$destilacion</td>
			<td>$recintada</td>
			<td>$posicion</td>
			<td>$observaciones_bodega</td>
		  </tr>		
		";
		
	}
}

//CREACION DEL ARCHIVO PDF
require_once('config/pdf/config/lang/spa.php');
require_once('config/pdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(PDF_MARGIN_LEFT, 1, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->setFontSubsetting(true);
$pdf->SetFont('dejavusans', '', 10, '', true);
$pdf->AddPage('L'); //Horizontal ('L') ...... Vertical =  ('P')

// Set some content to print
//<img src="imagenes/logo.jpg" width="200" align="absmiddle" />

$html='
<table width="970" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">
		<br><br><img src="imagenes/logo.jpg" width="100" align="absmiddle" />
	</td>
    <td align="center">
		SISTEMA DE GESTI&Oacute;N DE LA CALIDAD ISO 9001<br>
		<strong>DESPALETIZAJE</strong><br>
		BODEGA
	</td>
    <td align="center">
		CODIGO R-GOP-42<br>
		VERSI&Oacute;N: 03
	</td>
  </tr>
</table>
<table width="970" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left">
		FECHA
	</td>
    <td align="center">
		'.$fecha_arribo.'
	</td>
    <td align="center" colspan="2">
		DESPELETIZAJE
	</td>
    <td align="center">
		COORDINADOR TAESCOL
	</td>
    <td align="center">
		CANTIDAD DE PALLETS
	</td>
    <td align="center">
		COORDINADOR DE SEGURIDAD
	</td>	
  </tr>
  <tr>
    <td align="left">
		HORA DE LLEGADA
	</td>
    <td align="center">
		'.$hora_llegada.'
	</td>
    <td align="left">
		INICIO
	</td>
    <td align="center">
		'.$despaletizaje_inicio.'
	</td>
    <td align="center" rowspan="2">
		'.$despaletizaje_coordinador_tae.'
	</td>
    <td align="center" rowspan="2">
		'.$despaletizaje_cantidad_pallets.'
	</td>	
    <td align="center" rowspan="2">
		'.$despaletizaje_coordinador_seguridad.'
	</td>	
  </tr>
  <tr>
    <td align="left">
		VUELO
	</td>
    <td align="center">
		'.$nvuelo.'
	</td>
    <td align="left">
		FINAL
	</td>
    <td align="center">
		'.$despaletizaje_fin.'
	</td>
  </tr>
</table>
<table width="970" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" rowspan="2"  width="100">
		<br><br><br>GUIA MASTER
	</td>
    <td align="center" rowspan="2"  width="100">
		<br><br><br>GUIA HIJA
	</td>
    <td align="center" colspan="2"  width="110">
		GUIA
	</td>
    <td align="center" colspan="2"  width="90">
		BASCULA
	</td>
    <td align="center" colspan="3"  width="160">
		DIFERENCIA
	</td>
    <td align="center" colspan="6" width="90">
		EMPAQUE
	</td>	
    <td align="center" colspan="6" width="90">
		NOVEDADES
	</td>
    <td align="center" rowspan="2" width="90">
		<br><br><br>POSICIONES
	</td>
    <td align="center" rowspan="2" width="140">
		<br><br><br>OBSERVACIONES
	</td>
  </tr>
  <tr>
    <td align="center">
		<br><br><br>PIEZAS
	</td>
    <td align="center">
		<br><br><br>PESO
	</td>
    <td align="center">
		<br><br><br>PIEZAS
	</td>
    <td align="center">
		<br><br><br>PESO
	</td>
    <td align="center">
		<br><br><br>PIEZAS
	</td>
    <td align="center">
		<br><br><br>PESO
	</td>
    <td align="center">
		<br><br><br>%
	</td>
    <td align="center" width="15">
		<img src="imagenes/titulo_CAJA.jpg" width="10" align="absmiddle" />
	</td>
    <td align="center" width="15">
		<img src="imagenes/titulo_TULA.jpg" width="10" align="absmiddle" />
	</td>
    <td align="center" width="15">
		<img src="imagenes/titulo_ESTIBA.jpg" width="10" align="absmiddle" />
	</td>
    <td align="center" width="15">
		<img src="imagenes/titulo_CANECA.jpg" width="10" align="absmiddle" />
	</td>
    <td align="center" width="15">
		<img src="imagenes/titulo_HUACAL2.jpg" width="10" align="absmiddle" />
	</td>
    <td align="center" width="15">
		<img src="imagenes/titulo_LARGUERO.jpg" width="10" align="absmiddle" />
	</td>
    <td align="center" width="15">
		<img src="imagenes/titulo_ROTAS.jpg" width="10" align="absmiddle" />
	</td>
    <td align="center" width="15">
		<img src="imagenes/titulo_ABOLLADAS.jpg" width="10" align="absmiddle" />
	</td>
    <td align="center" width="15">
		<img src="imagenes/titulo_ABIERTAS.jpg" width="10" align="absmiddle" />
	</td>
    <td align="center" width="15">
		<img src="imagenes/titulo_HUMEDAS.jpg" width="10" align="absmiddle" />
	</td>
    <td align="center" width="15">
		<img src="imagenes/titulo_DESTILACION.jpg" width="10" align="absmiddle" />
	</td>
    <td align="center" width="15">
		<img src="imagenes/titulo_RECINTADAS.jpg" width="10" align="absmiddle" />
	</td>
  </tr>
  '.$impresion.'
  <tr>
	<td colspan="2"><strong>TOTAL</strong></td>
	<td>'.$totalpiezas.'</td>
	<td>'.$totalpeso.'</td>
	<td>'.$totalpiezas_bascula.'</td>
	<td>'.$totalpeso_bascula.'</td>
	<td colspan="17"></td>
  </tr>		
</table>
<table width="970" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><strong>OBSERVACIONES</strong></td>
  </tr> 
  <tr>
    <td align="left">'.$despaletizaje_observaciones.'</td>
  </tr> 
</table>
<br>
<table width="970" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left"><strong>Auxiliares:</strong>'.$despaletizaje_auxiliar1.'<br>'.$despaletizaje_auxiliar2.'<br>'.$despaletizaje_auxiliar3.'</td>
	<td align="left"><strong>Operarios:</strong>'.$despaletizaje_operario1.'<br>'.$despaletizaje_operario2.'<br>'.$despaletizaje_operario3.'</td>
  </tr> 
  <tr>
    <td align="left"><strong>Elaborado Por:</strong>'.$despaletizaje_elaboradopor.'<br><strong>Cargo:</strong>'.$despaletizaje_elaboradopor_cargo.'</td>
	<td align="left"><strong>Comercio Exterior:</strong>'.$despaletizaje_comercio_exterior.'<br><strong>Fecha y Hora:</strong>'.$despaletizaje_fecha_hora_doc.'</td>
  </tr> 
</table>

';
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Despaletizaje_Vuelo.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>

