<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$impresion="";
$id_trasbordo=$_REQUEST["id_trasbordo"];
//carga datos de la remesa
$sql="SELECT * FROM trasbordo WHERE id='$id_trasbordo' AND estado='A'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$id_guia=$fila["id_guia"];
$fecha=$fila["fecha"];
$hora=$fila["hora"];
$destinatario=$fila["destinatario"];
$id_usuario=$fila["id_usuario"];
$observaciones=$fila["observaciones"];
$peso=$fila["peso"];
$piezas=$fila["piezas"];
$volumen=$fila["volumen"];


//carga datos de la guia
$postdespacho="";
$sql="SELECT * FROM guia WHERE id='$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 2". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$peso=number_format($peso,2,",",".");
$volumen=number_format($volumen,2,",",".");
$guia=$fila["hija"];
$master=$fila["master"];
require("config/master.php");
$descripcion=$fila["descripcion"];
$id_vuelo=$fila["id_vuelo"];
$id_aerolinea=$fila["id_aerolinea"];
if ($fila['update_postdespacho'] == 1){
  $postdespacho = "<strong>ADVERTENCIA:</strong>Se crearon cambios en la información de algunas guias, posterior a su despacho";
}

//carga datos del vuelo
$sql="SELECT id_aerolinea,nmanifiesto FROM vuelo WHERE id = '$id_vuelo'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$nmanifiesto=$fila["nmanifiesto"];

//carga datos de la aerolinea
$sql="SELECT nombre FROM aerolinea WHERE id = '$id_aerolinea'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$aerolinea=$fila["nombre"];

//carga nombre de usuario
$sql="SELECT nombre FROM usuario WHERE id ='$id_usuario'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 5". mysql_error(). " INFORME AL SOPORTE TECNICO");
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
<table border="2" cellspacing="0" cellpadding="0" width="650">
  <tr>
    <td width="150"><img src="imagenes/logo.jpg" width="140" /></td>
    <td width="380" align="center"><strong><font size="+3"><br />PLANILLA DE TRASBORDO</font></strong></td>
    <td width="120" align="center"><font size="-1">VERSION: 01</font>
    </td>
  </tr>
</table>
<br />
<table width="650" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td>
		<br />
		<strong>TRANSFERIDO A: <font color="#990000" size="+4">$destinatario</font></strong>
		<br />
	</td>
  </tr>
</table>
<br />
<table width="650" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="65" bgcolor="#CCCCCC">
	  <strong>FECHA:</strong>
	</td>
    <td width="230">$fecha - $hora</td>
    <td width="120" bgcolor="#CCCCCC"><strong>CONSECUTIVO:</strong></td>
    <td width="235" align="center"><font color="#990000" size="+4">$id_trasbordo</font></td>
  </tr>
</table>
<br />
<table width="650" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200"><strong>MANIFIESTO DE CARGA</strong></td>
    <td width="450" align="center">$nmanifiesto</td>
  </tr>
  <tr>
    <td><strong>No. MAWB</strong></td>
    <td align="center">$master</td>
  </tr>
  <tr>
    <td><strong>No. HAWB</strong></td>
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
    <td><strong>AEROLINEA</strong></td>
    <td align="left">$aerolinea</td>
  </tr>
  <tr>
    <td height="112"><strong>DESCRIPCION:</strong></td>
    <td align="left">$descripcion</td>
  </tr>
</table>
<br />
<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="176" align="left"><strong>ELABORADO POR:</strong></td>
    <td width="501" align="left">__<u>$usuario</u>__</td>
  </tr>
</table>
<br />
<table width="650" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td>
		<br />
		<strong>OBSERVACIONES:</strong> $observaciones
		<br />
	</td>
  </tr>
</table>
<br />
<img src="imagenes/recuadro_firma1.jpg" />
<br />
<img src="imagenes/recuadro_firma2.jpg" />
<br />
$postdespacho
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Reporte_Trasbordo'.$id_trasbordo.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>

