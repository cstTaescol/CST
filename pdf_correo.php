<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$id_correo=$_REQUEST["id"];
$impresion="";
$cont=0;

//carga datos de la remesa
$sql="SELECT * FROM correo WHERE id='$id_correo' AND estado='A'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);

$id_aerolinea=$fila["id_aerolinea"];
$hora_entrega=$fila["hora_entrega"];
$aux_entrega_am=$fila["aux_entrega_am"];
$oper_entrega_am=$fila["oper_entrega_am"];
$aux_entrega_pm=$fila["aux_entrega_pm"];
$oper_entrega_pm=$fila["oper_entrega_pm"];
$tpallets=$fila["tpallets"];
$tmallas=$fila["tmallas"];
$tcorreas=$fila["tcorreas"];
$tdollys=$fila["tdollys"];
$coordinador=$fila["coordinador"];
$jefe=$fila["jefe"];
$supervisor=$fila["supervisor"];
$hora=$fila["hora"];
$fecha=$fila["fecha"];
$id_usuario=$fila["id_usuario"];
$tipo_entrega=$fila["tipo_entrega"];
if ($tipo_entrega=="D")
	$tipo_entrega="DESPACHADO EN LA AEROLINEA";
else
	$tipo_entrega="DESPACHADO HACIA BODEGA 1";

//carga de guias despachadas con la salida
$postdespacho="";
$sql="SELECT * FROM carga_correo WHERE id_correo='$id_correo'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
	{
		$cont++;
		$id_guia=$fila["id_guia"];
		$npallets=$fila["npallets"];
		$npcs=$fila["npcs"];
		$hora_i=$fila["hora_inicio"];
		$hora_s=$fila["hora_salida"];
		$observaciones=$fila["observaciones"];
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		$volumen=$fila["volumen"];
		
		//carga dato de la Guia
		$sql2="SELECT hija,piezas,peso,volumen,piezas_inconsistencia,peso_inconsistencia,volumen_inconsistencia,id_consignatario,id_vuelo,id_aerolinea,update_postdespacho FROM guia WHERE id='$id_guia'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$guia=$fila2['hija'];
		$id_consignatario=$fila2['id_consignatario'];
    if ($fila2['update_postdespacho'] == 1){
      $postdespacho = "<strong>ADVERTENCIA:</strong>Se crearon cambios en la información de algunas guias, posterior a su despacho";
    }

		//identificando consignatario
		$sql3="SELECT nombre FROM consignatario WHERE id = '$id_consignatario'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$consignatario=$fila3["nombre"];
		
		//identificando vuelo
		$id_vuelo=$fila2["id_vuelo"];
		$sql3="SELECT nvuelo, fecha_arribo FROM vuelo WHERE id = '$id_vuelo'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$nvuelo=$fila3["nvuelo"];
		$fecha_arribo=$fila3['fecha_arribo'];
				
		//identificando Aerolinea
		$id_aerolinea=$fila2["id_aerolinea"];
		$sql3="SELECT nombre FROM aerolinea WHERE id = '$id_aerolinea'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$aerolinea=$fila3["nombre"];

		$impresion=$impresion.
					"<tr>
						<td>$cont</td>
						<td>$nvuelo</td>
						<td>$guia</td>
						<td>$piezas</td>
						<td>$peso</td>
						<td>$consignatario</td>
						<td>$npallets</td>
						<td>$npcs</td>
						<td>$hora_i</td>
						<td>$hora_s</td>
						<td>$observaciones</td>
					  </tr>
					  ";
	}

//CREACION DEL ARCHIVO PDF
require_once('config/pdf/config/lang/spa.php');
require_once('config/pdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->setFontSubsetting(true);
$pdf->SetFont('dejavusans', '', 8, '', true);
$pdf->AddPage();

// Set some content to print
$html = <<<EOD
<table width="650" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="150" rowspan="3"><img src="imagenes/logo.jpg" width="120"/></td>
    <td width="350" align="center">SISTEMA DE GESTION DE CALIDAD NTC ISO 9001/2008</td>
    <td width="150">COD R-GCE-047</td>
  </tr>
  <tr>
    <td align="center"><strong>LISTADO CONTROL TRASLADO DE MATERIAL COURIER</strong></td>
    <td>VERSION 02</td>
  </tr>
  <tr>
    <td align="center"><strong>BODEGA / DIAN</strong></td>
    <td></td>
  </tr>
</table>
<table width="650" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#CCCCCC" height="20"><strong><font size="+4">AEROLINEA:$aerolinea</font></strong></td>
	<td height="20" align="center"><strong><font size="+5" color="#FF0000">No.$id_correo</font></strong></td>
  </tr>
</table>
<table width="650" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="325">DATOS DE TRASLADO (SALIDA DE BODEGA)</td>
    <td width="325">$fecha / $hora</td>
  </tr>
  <tr>
    <td>HORA DE FINALIZACION (ENTREGA BOD.UNO)</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" bgcolor="#CCCCCC">LISTADO TOTAL DE CORREO</td>
  </tr>
  <tr>
    <td align="right">AUXILIAR QUE ENTREGA - A.M.</td>
    <td>$aux_entrega_am</td>
  </tr>
  <tr>
    <td align="right">OPERARIO - A.M.</td>
    <td>$oper_entrega_am</td>
  </tr>
  <tr>
    <td align="right">AUXILIAR QUE ENTREGA - P.M.</td>
    <td>$aux_entrega_pm</td>
  </tr>
  <tr>
    <td align="right">OPERARIO - P.M.</td>
    <td>$oper_entrega_pm</td>
  </tr>
</table>
<p align="center"><strong><font size="+1">$tipo_entrega</font></strong></p>
<table width="650" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="15" align="center" bgcolor="#CCCCCC"><strong><font size="-1"><br />N.</font></strong></td>
    <td width="52" align="center" bgcolor="#CCCCCC"><strong><font size="-1"><br />VUELO</font></strong></td>
	<td width="73" align="center" bgcolor="#CCCCCC"><strong><font size="-1"><br />AWB</font></strong></td>
    <td width="34" align="center" bgcolor="#CCCCCC"><strong><font size="-1"><br />Pz</font></strong></td>
    <td width="31" align="center" bgcolor="#CCCCCC"><strong><font size="-1"><br />Ps</font></strong></td>
    <td width="120" align="center" bgcolor="#CCCCCC"><strong><font size="-1"><br />CLIENTE</font></strong></td>
    <td width="100" align="center" bgcolor="#CCCCCC"><strong><font size="-1"><br />N. PALLET UTILIZADO</font></strong></td>
    <td width="40" align="center" bgcolor="#CCCCCC"><strong><font size="-1">N. PCS x PALLET</font></strong></td>
    <td width="45" align="center" bgcolor="#CCCCCC"><strong><font size="-1">HORA DE INICIO</font></strong></td>
	<td width="45" align="center" bgcolor="#CCCCCC"><strong><font size="-1">HORA DE SALIDA</font></strong></td>
    <td width="95" align="center" bgcolor="#CCCCCC"><strong><font size="-1"><br />OBSERVACIONES</font></strong></td>
  </tr>
	$impresion
</table>
<br />
<table width="650" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td width="325" bgcolor="#CCCCCC">TOTAL DE PALLETS UTILIZADOS</td>
    <td width="325" align="left">$tpallets</td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC">TOTAL DE MALLAS UTILIZADAS</td>
    <td align="left">$tmallas</td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC">TOTAL DE CORREAS UTILIZADAS</td>
    <td  align="left">$tcorreas</td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC">TOTAL DE DOLLYS UTILIZADOS</td>
    <td  align="left">$tdollys</td>
  </tr>
</table>
<br />
<table width="650" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#CCCCCC"> SUPERVISOR</td>
    <td align="left">$supervisor</td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"> JEFE DE BODEGA</td>
    <td align="left">$jefe</td>
  </tr>
  <tr>
    <td width="325" bgcolor="#CCCCCC"> COORDINADOR RESPONSABLE</td>
    <td width="325" align="left">$coordinador</td>
  </tr>

</table>
<br />
<table width="650" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td height="55">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" bgcolor="#CCCCCC"><strong>NOMBRE Y FIRMA DEL AUX. DE SEGURIDAD</strong></td>
  </tr>
</table>
<br />
$postdespacho
EOD;
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('correo.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+


?>

