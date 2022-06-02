<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");

$id_registro=$_REQUEST["id_registro"];
$posicion="";
//carga datos de la remesa
$sql="SELECT * FROM preinspeccion WHERE id='$id_registro'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 0 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$id_guia=$fila["id_guia"];
$nombre=$fila["nombre"];
$documento=$fila["documento"];
$telefono=$fila["telefono"];
$agencia=$fila["agencia"];
$fecha=$fila["fecha"];
$hora=$fila["hora"];
$usuario=$fila["id_usuario"];
$imagen=$fila["foto"];
if ($imagen=="")
	$imagen="imagen_no_disponible.jpg";
	
$foto="src=\"fotos/cumplidos/$imagen\"";
$cliente=CLIENTE;
$sigla=SIGLA;

//carga nombre del conductor
$sql="SELECT nombre FROM usuario WHERE id='$usuario'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$usuario=$fila['nombre'];


$sql="SELECT hija,id_aerolinea,piezas,peso,volumen,piezas_inconsistencia,peso_inconsistencia,volumen_inconsistencia FROM guia WHERE id='$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 2 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$hija=$fila['hija'];
$aerolinea=$fila['id_aerolinea'];
include("config/evaluador_inconsistencias.php");

$sql="SELECT nombre FROM aerolinea WHERE id = '$aerolinea'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 3 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$aerolinea=$fila["nombre"];


//Ubica la Posicion en Bodega
$sql_posiscion="SELECT p.*,pc.* FROM posicion_carga pc LEFT JOIN posicion p ON pc.id_posicion=p.id WHERE pc.id_guia='$id_guia'";
$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit('Error '.mysql_error()));
while($fila_posicion=mysql_fetch_array($consulta_posicion))
{
	$plaqueta=$fila_posicion['rack']."-".$fila_posicion['seccion']."-".$fila_posicion['nivel']."-".$fila_posicion['lado']." ".$fila_posicion['fondo'];
	$posicion=$posicion." - $plaqueta";
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
$html = <<<EOD
<table width="600" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
  		<td width="200" align="center"><br><img src="imagenes/logo.jpg" width="180" align="absmiddle" /></td>
    	<td width="250" align="center" valign="middle">
			<font size="7">SISTEMA DE GESTION DE CALIDAD NTC ISO 9001:2008<br></font>				
			<strong>CONTROL DE PREINSPECCIONES</strong><br>
			DESCARGUES DIRECTOS<br>
			PREINSPECCION <strong>No.</strong> $id_registro
		</td>
    	<td width="150" align="left">
			<strong>CODIGO: R-GCE-17</strong><br>
			VERSION: 02<br>
			PAGINA 1 DE 1			
		</td>
  </tr>
</table>

<table width="600" border="1" align="center" cellpadding="0" cellspacing="0" align="left">
  <tr>
		<td width="86" rowspan="6" bgcolor="#CCCCCC"><br><br><br>AGENTE</td>
		<td ><strong>Fecha</strong></td>
		<td><em>$fecha</em></td>
		<td><strong>Hora</strong></td>
		<td><em>$hora</em></td>
		<td><strong>Guia No.</strong></td>
		<td><em>$hija</em></td>
  </tr>
   <tr>
		<td colspan="2"><strong>Agencia:</strong><br><em>$agencia</em></td>
		<td colspan="2"><strong>Inspecciono:</strong><br><em>$nombre</em></td>
		<td colspan="2"><strong>Tel/Cel:</strong><br><em>$telefono</em></td>
  </tr>
  <tr>
    <td><strong>Total Piezas</strong></td>
    <td>&nbsp;&nbsp;$piezas</td>
    <td><strong>Total Peso</strong></td>
    <td>&nbsp;&nbsp;$peso</td>
    <td><strong>Piezas Insp.</strong></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Ubicacion</strong></td>
    <td colspan="5">$posicion</td>
  </tr>
  
</table>

<table width="600" border="1" align="center" cellpadding="0" cellspacing="0" align="left">
  <tr>
    <td width="86" rowspan="6"  bgcolor="#CCCCCC"><br><br><br><br><br><br>BODEGA</td>
    <td width="129"><strong>Fecha</strong></td>
    <td width="128">&nbsp;</td>
    <td width="129"><strong>Hora</strong></td>
    <td width="128">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" height="80"><strong>Observaciones</strong></td>
  </tr>
  <tr>
 	<td colspan="4" height="80" align="right" valign="bottom"><br><br><br><br><br><strong>Nombre y Firma Recibido</strong></td>  
  </tr>
</table>

<table width="600" border="1" align="center" cellpadding="0" cellspacing="0" align="left">
  <tr>
    <td width="86" rowspan="6" bgcolor="#CCCCCC"><br><br><br><br><br><br>SEGURIDAD</td>
    <td width="129"><strong>Fecha</strong></td>
    <td width="128">&nbsp;</td>
    <td width="129"><strong>Hora</strong></td>
    <td width="128">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" height="80"><strong>Observaciones</strong></td>
  </tr>
  <tr>
 	<td colspan="4" height="80" align="right" valign="bottom"><br><br><br><br><br><strong>Nombre y Firma Recibido</strong></td>  
  </tr>
</table>

<table width="600" border="1" align="center" cellpadding="0" cellspacing="0" >
  <tr >
    <td height="80" width="400" align="left" valign="top">Observaciones:</td>
    <td height="80" width="200" align="center" valign="bottom"><br><br><br><br><br><br><strong>Firma y Sello Quien Inspecciono</strong></td>
  </tr>
</table>
<table width="600" border="0" align="left" cellpadding="0" cellspacing="0" >
  <tr>
    	<td>
			<br><br>
			<strong>INFORMACION IMPORTANTE PARA PREINSPECCIONES:</strong> Por regulaci&oacute;n SG-SST (Sistemas de Gesti&oacute;n de la Seguridad y Salud en el Trabajo) el ingreso a nuestras instalaciones para todo el personal solo ser&aacute; autorizado siempre y cuando:
			<ul>				
				<li>Presentar planilla &uacute;ltimo pago seguridad social</li>
				<li>Carnet ARL</li>
				<li>Porte los elementos de protecci&oacute;n personal <br>(Botas punta de seguridad y Chaleco reflectivo)</li>
			</ul>
		</td>
  </tr>
</table>



EOD;



// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('preinspeccion.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+


?>

