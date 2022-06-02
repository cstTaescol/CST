<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$totalpiezas=0;
$totalpeso=0;
$totalvolumen=0;
$impresion="";
$sigla=SIGLA;
$id_remesa=$_REQUEST["id_remesa"];
//carga datos de la remesa
$sql="SELECT * FROM remesa WHERE id='$id_remesa'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$transportador=$fila["id_transportador"];
$vehiculo=$fila["id_vehiculo"];
$conductor=$fila["id_conductor"];
$deposito=$fila["id_deposito"];
$fecha=$fila["fecha"];
$hora=$fila["hora"];
$usuario=$fila["id_usuario"];
$refrigerado=$fila["refrigerado"];
$exclusivo=$fila["exclusivo"];
$observacion=$fila["observacion"];
$planilla_envio=$fila["planilla_envio"];
$cliente=CLIENTE;
//carga nombre del conductor
$sql="SELECT nombre FROM conductor WHERE id='$conductor'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$nconductor=$fila['nombre'];

//carga nombre del transportador
$sql="SELECT nombre,telefono1 FROM transportador WHERE id='$transportador'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 2". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$ntransportador=$fila['nombre'];
$teltransportador=$fila['telefono1'];

//carga placa del vehiculo
$sql="SELECT placa,carroceria FROM vehiculo WHERE placa='$vehiculo'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 3". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$nvehiculo=$fila['placa'];
$carroceria=$fila['carroceria'];

//carga nombre del deposito
$sql="SELECT nombre,direccion FROM deposito WHERE id='$deposito'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$ndeposito=$fila['nombre'];
$direccion=$fila['direccion'];

//carga de guias despachadas con la remesa
$sql="SELECT * FROM carga_remesa WHERE id_remesa='$id_remesa'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$postdespacho="";
while($fila=mysql_fetch_array($consulta))
	{
		$id_guia=$fila["id_guia"];
		//carga dato de la guia
		$sql2="SELECT hija,master,id_vuelo,update_postdespacho FROM guia WHERE id='$id_guia'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$master=$fila2['master'];
		include("config/master.php");
		$consolidado=$master;
		$guia=$fila2['hija'];
		$id_vuelo=$fila2['id_vuelo'];
		//control de mensaje postdespacho
    if ($fila2['update_postdespacho'] == 1){
      $postdespacho = "<strong>ADVERTENCIA:</strong>Se crearon cambios en la información de algunas guias, posterior a su despacho";
    }
    
		//identificando la aerolinea
		$sql3="SELECT id_aerolinea FROM vuelo WHERE id = '$id_vuelo'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$aerolinea=$fila3["id_aerolinea"];
		$sql3="SELECT nombre FROM aerolinea WHERE id = '$aerolinea'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$aerolinea=$fila3["nombre"];
		
		$totalpiezas=$totalpiezas+$fila["piezas"];
		$totalpeso=$totalpeso+$fila["peso"];
		$totalvolumen=$totalvolumen+$fila["volumen"];
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		$volumen=$fila["volumen"];
		
		$impresion="<tr>
      						<td>$aerolinea</td>
      						<td>$consolidado</td>
      						<td>$guia</td>
      						<td align=\"right\">$piezas</td>
      						<td align=\"right\">$peso</td>
      						<td align=\"right\">$volumen</td>
      					</tr>".$impresion;
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
$pdf->SetFont('dejavusans', '', 10, '', true);
$pdf->AddPage();

// Set some content to print
//<img src="imagenes/logo.jpg" width="200" align="absmiddle" />
$html = <<<EOD
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td rowspan="4" align="center" valign="middle">
		<strong><font size="+3" color="#660000">$ntransportador</strong></font><br>
		<strong><font color="#660000">NIT:$transportador</strong></font><br>
		<strong><font color="#660000">TEL:$teltransportador</strong></font><br>
		<strong><font color="#660000">$sigla</strong></font><br>
	</td>
    <td><strong><font size="+2" color="#660000">REMESA No. $id_remesa</strong></font></td>    
  </tr>
  <tr>
  	<td><strong>Planilla de Envio No.</strong> $planilla_envio</td>
  </tr>
  <tr>
    <td><strong>Fecha:</strong> $fecha</td>
  </tr>
  <tr>
    <td valign="middle"><strong>Hora:</strong> $hora</td>
  </tr>
</table>

<hr /><p align="center"><font size="+2"><strong>DATOS DETALLADOS DEL TRANSPORTE</strong></font><hr /></p>
<table width="600" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="150"><strong>REMITENTE:</strong></td>
    <td><font size="-1">$cliente</font></td>
  </tr>
  <tr>
    <td width="150"><font size="-1"><strong>DEPOSITO y/o CONSIGNATARIO:</strong></font></td>
    <td width="270"><font size="-1">$ndeposito</font></td>
    <td width="110"><strong>VEHICULO:</strong></td>
    <td width="70"><font size="-1">$nvehiculo</font></td>
  </tr>
  <tr>
    <td><strong>DIRECCION:</strong></td>
    <td><font size="-1">$direccion</font></td>
    <td><strong>CARROCERIA:</strong></td>
    <td><font size="-1">$carroceria</font></td>
  </tr>
  <tr>
    <td><strong>TRANSPORTADOR:</strong></td>
    <td><font size="-1">$ntransportador</font></td>
    <td><strong>EXCLUSIVO:</strong></td>
    <td>$exclusivo</td>
  </tr>
  <tr>
    <td><strong>CONDUCTOR:</strong></td>
    <td><font size="-1">$nconductor</font></td>
    <td><strong>REFRIGERADO:</strong></td>
    <td>$refrigerado</td>
  </tr>
  <tr>
    <td><strong>CEDULA:</strong></td>
    <td><font size="-1">$conductor</font></td>
  </tr>
</table>
<hr /><p align="center"><font size="+2"><strong>GUIAS DESPACHADAS</strong></font><hr /></p>
<table width="600" border="1" cellspacing="0" cellpadding="0">
  <tr>
 	<td width="185" align="center" bgcolor="#CCCCCC"><strong>AEROLINEA</strong></td> 
    <td width="126" align="center" bgcolor="#CCCCCC"><strong>CONSOLIDADO</strong></td>
    <td width="126" align="center" bgcolor="#CCCCCC"><strong>GUIA</strong></td>
    <td width="56" align="center" bgcolor="#CCCCCC"><strong>PIEZAS</strong></td>
    <td width="55" align="center" bgcolor="#CCCCCC"><strong>PESO</strong></td>
    <td width="85" align="center" bgcolor="#CCCCCC"><strong>VOLUMEN</strong></td>
  </tr>
  $impresion
</table>
<p></p>
<p><strong><em><font size="+2" color="#660000">TOTALES</font></em>: </strong></p>
<p><strong>Piezas</strong>=$totalpiezas <strong>Peso</strong>=$totalpeso. <strong>Volumen</strong>=$totalvolumen.</p>
<p><strong><font size="+2" color="#660000">OBSERVACIONES GENERALES</font></strong>:</p>
<p>$observacion</p>
<p><strong>$ntransportador</strong> se compromete a obtener la firma del destinatario a conformidad. DECLARO he hemos recibido en el estado indicado el cargamento a que se refiere esta Remesa.</p>
<table width="600" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100" align="center" valign="bottom">&nbsp;</td>
    <td height="100" align="center" valign="bottom">&nbsp;</td>
    <td height="100" align="center" valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td height="42" width="200" align="center"><font size="-1"><strong>Funcionario de Seguridad</strong><br />Firma/Sello</font></td>
    <td height="42" width="200" align="center"><font size="-1"><strong>Funcionario de Trasportadora</strong><br />Firma/Sello</font></td>
    <td height="42" width="200" align="center"><font size="-1"><strong>Destino</strong><br />Firma/Sello/Fecha/hora</font></td>
  </tr>
  <tr>
    <td height="80" width="600" align="center"><font size="-1"><strong>OBSERVACIONES DESTINATARIO</strong></font></td>
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
$pdf->Output('remesa.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+


?>

