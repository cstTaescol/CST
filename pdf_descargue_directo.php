<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$impresion="";

$id_ddirecto=$_REQUEST["id_ddirecto"];
//carga datos de la remesa
$sql="SELECT * FROM descargue_directo WHERE id='$id_ddirecto' AND estado='A'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$id_guia=$fila["id_guia"];
$levante=$fila["levante"];
$peso=$fila["peso"];
$piezas=$fila["piezas"];
$volumen=$fila["volumen"];
$declaracion=$fila["declaracion"];
$agencia=$fila["agencia"];
$cedula_entregado=$fila["cedula_entregado"];
$nombre_entregado=$fila["nombre_entregado"];
$telefono_entregado=$fila["telefono_entregado"];
$nombre_conductor=$fila["nombre_conductor"];
$cedula_conductor=$fila["cedula_conductor"];
$placa=$fila["placa"];
$fecha=$fila["fecha"];
$hora=$fila["hora"];
$id_usuario=$fila["id_usuario"];
$posicion=$fila["posicion_carga"];


//carga datos de la guia
$postdespacho="";
$sql="SELECT * FROM guia WHERE id='$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 2". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
//include("config/evaluador_inconsistencias.php");
$guia=$fila["hija"];
$master=$fila["master"];
require("config/master.php");
$descripcion=$fila["descripcion"];
$id_vuelo=$fila["id_vuelo"];
if ($fila['update_postdespacho'] == 1){
  $postdespacho = "<strong>ADVERTENCIA:</strong>Se crearon cambios en la información de algunas guias, posterior a su despacho";
}

//carga datos del vuelo
$sql="SELECT id_aerolinea,nmanifiesto FROM vuelo WHERE id = '$id_vuelo'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$aerolinea=$fila["id_aerolinea"];
$nmanifiesto=$fila["nmanifiesto"];

//carga datos de la aerolinea
$sql="SELECT nombre FROM aerolinea WHERE id = '$aerolinea'";
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

<table border="2" cellspacing="0" cellpadding="0" width="677">
  <tr>
    <td width="140"><img src="imagenes/logo.jpg" width="140" /></td>
    <td width="421" align="center">
   	  	<font size="-1">SISTEMA DE GESTION DE CALIDAD NTC ISO 9001:2008</font><br />
   	 	<strong><font size="+3">PLANILLA DESCARGUES DIRECTOS</font></strong></td>
    <td width="116" align="center"><font size="-1">CODIGO:R-GCE-016</font><br />
      <font size="-1">VERSION: 04<br />PAGINA 1 DE 1</font>
    </td>
  </tr>
</table>
<br />
<table width="670" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td>
		<br />
		<strong>CLIENTE: $aerolinea</strong>
		<br />
	</td>
  </tr>
</table>
<br />
<table width="677" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="66" bgcolor="#CCCCCC">
	  <strong>FECHA:</strong>
	</td>
    <td width="162">$fecha</td>
    <td width="142" bgcolor="#CCCCCC"><strong>CONSECUTIVO:</strong></td>
    <td width="300" align="center"><font color="#990000" size="+4">$id_ddirecto</font></td>
  </tr>
</table>
<br />
<table width="677" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="176"><strong>MANIFIESTO DE CARGA</strong></td>
    <td colspan="2" align="left"><u>$nmanifiesto</u></td>
    <td width="158">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>No. MAWB</strong></td>
    <td colspan="2" align="left"><u>$master</u></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>No. HAWB</strong></td>
    <td colspan="2" align="left"><u>$guia</u></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>PIEZAS</strong></td>
    <td colspan="3" align="left"><u>$piezas</u></td>
  </tr>
  <tr>
    <td><strong>PESO Kg</strong></td>
    <td width="209" align="left"><u>$peso</u></td>
    <td width="134"><strong>PESO VOLUMEN</strong></td>
    <td align="left"><u>$volumen</u></td>
  </tr>
  <tr>
    <td><strong>UBICACION</strong></td>
    <td colspan="3" align="left">$posicion</td>
  </tr>
  <tr>
    <td height="38"><strong>No. LEVANTE</strong></td>
    <td colspan="3" align="left"><u>$levante</u></td>
  </tr>
  <tr>
    <td height="38"><strong>DECLARACION No.</strong></td>
    <td colspan="3" align="left"><u>$declaracion</u></td>
  </tr>
  <tr>
    <td height="90"><strong>DESCRIPCION:</strong></td>
    <td colspan="3" align="left">$descripcion</td>
  </tr>
</table>
<br />
<table width="677" border="1" cellspacing="0" cellpadding="0">
  <tr>
	<td width="117" rowspan="4" bgcolor="#CCCCCC"><strong><br />ENTREGADO A:</strong></td>
    <td width="92"><strong>NOMBRE:</strong></td>
    <td width="169">$nombre_entregado</td>
    <td width="108"><strong>CONDUCTOR:</strong></td>
    <td width="179">$nombre_conductor</td>
  </tr>
  <tr>
    <td><strong>AGENCIA:</strong></td>
    <td colspan="3">$agencia</td>
  </tr>
  <tr>
    <td><strong>CEDULA:</strong></td>
    <td>$cedula_entregado</td>
    <td><strong>CEDULA:</strong></td>
    <td>$cedula_conductor</td>
  </tr>
  <tr>
    <td><strong>TELEFONO:</strong></td>
    <td>$telefono_entregado</td>
    <td><strong>PLACAS VEH.:</strong></td>
    <td>$placa</td>
  </tr>
</table>
<br />
<br />
<table width="677" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="135" align="left"><strong>RECIBIDO POR:</strong></td>
    <td width="211" align="center">__________________________</td>
    <td width="116" align="left"><strong>RECIBIDO POR:</strong></td>
    <td width="209" align="center">__________________________</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><strong>AGENCIA Y/O SIA</strong></td>
    <td>&nbsp;</td>
    <td align="center"><strong>CONDUCTOR</strong></td>
  </tr>
  <tr>
    <td height="48">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left"><strong>ELABORADO POR:</strong></td>
    <td align="center">__<u>$usuario</u>__</td>
    <td>&nbsp;</td>
    <td rowspan="2" align="right">
		<font size="-2">AAAA-MM-DD - HOR<br />
						$fecha - $hora	
		</font>
	</td>
  </tr>
  <tr>
    <td></td>
    <td align="center"><strong>COMERCIO EXTERIOR</strong></td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<br />
<br />
<img src="imagenes/recuadro_firma3.jpg" width="690" />
<br />
$postdespacho
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Descargue_Directo_'.$id_ddirecto.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>

