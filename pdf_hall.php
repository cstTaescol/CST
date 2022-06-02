<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$impresion="";
$id_registro=$_REQUEST["id_registro"];
//carga datos de la remesa
$sql="SELECT * FROM hall WHERE id='$id_registro' AND estado='A'";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
$fila=mysql_fetch_array($consulta);
$id_guia=$fila["id_guia"];
$peso=$fila["peso"];
$piezas=$fila["piezas"];
$volumen=$fila["volumen"];
$observaciones=$fila["observaciones"];
$fecha=$fila["fecha"];
$hora=$fila["hora"];
$id_usuario=$fila["id_usuario"];
$sigla=SIGLA;
//carga datos de la guia
$sql="SELECT g.hija,g.id_aerolinea,a.nombre FROM guia g LEFT JOIN aerolinea a ON g.id_aerolinea = a.id WHERE g.id='$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
$fila=mysql_fetch_array($consulta);
$guia=$fila["hija"];
$aerolinea=$fila["nombre"];


//carga nombre de usuario
$sql="SELECT nombre FROM usuario WHERE id ='$id_usuario'";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
$fila=mysql_fetch_array($consulta);
$usuario=$fila['nombre'];

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
$html = <<<EOD
<table border="0" cellspacing="0" cellpadding="0" width="650">
  <tr>
    <td width="450"><strong><font size="+3">PLANILLA DE DESPACHO HALL No.</font><font color="#990000" size="+4"> $id_registro</font></strong></td>
    <td width="200"><img src="imagenes/logo.jpg" width="140" /></td>
  </tr>
</table>
<hr /> 
<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><strong>AEROLINEA: <br /><font color="#990000" size="+4">$aerolinea</font></strong></td>
  </tr>
</table>
<br />
<table width="650" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong>GUIA</strong></td>
    <td align="center">$guia</td>
  </tr>
  <tr>
    <td><strong>PIEZAS</strong></td>
    <td align="left">$piezas</td>
  </tr>
  <tr>
    <td><strong>PESO Kg</strong></td>
    <td align="left">$peso</td>
  </tr>
  <tr>
    <td><strong>PESO VOLUMEN</strong></td>
    <td align="left">$volumen</td>
  </tr>
  <tr>
    <td colspan="2"><strong>DETALLE:</strong><br>$observaciones</td>
  </tr>
</table>
<br />
<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" align="left"><strong>ELABORADO POR:</strong></td>
    <td width="450" align="center">__<u>$usuario</u>__<br />$fecha / $hora</td>
  </tr>
</table>
<br />
<br />
<img src="imagenes/recuadro_firma1.jpg" />
<br />
<br />
<img src="imagenes/recuadro_firma2.jpg" />
<br />
<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right"><font size="-1">VERSION: 01</font><br />$sigla</td>
  </tr>
</table>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Hall'.$id_registro.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>