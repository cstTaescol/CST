<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$impresion="";

$id_registro=$_REQUEST["id_registro"];
//carga datos de la remesa
$sql="SELECT * FROM courier_despacho_hija WHERE id='$id_registro' AND estado='A'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$id_guia=$fila["id_guia"];
$peso=$fila["peso"];
$piezas=$fila["piezas"];
$id_usuario=$fila["id_usuario"];
$fecha=$fila["fecha"];
$hora=$fila["hora"];
$no_acta=$fila["no_acta"];
$fecha_acta=$fila["fecha_acta"];
$justificacion=$fila["justificacion"];
$placa_vehiculo=$fila["placa_vehiculo"];
$ccConductor=$fila["ccConductor"];
$nombreConductor=$fila["nombreConductor"];
$id_funcionario_autorizador=$fila["id_funcionario_autorizador"];
$id_funcionario_courier=$fila["id_funcionario_courier"];
$planillaEnvio=$fila["planillaEnvio"];
$nombreDeposito=$fila["nombreDeposito"];


//carga datos de la guia hija
$sql="SELECT master,hija,observaciones,courier_docAprehension,id_tipo_actuacion_aduanera FROM guia WHERE id='$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 2". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$observaciones=$fila["observaciones"];
$hija=$fila["hija"];
$courier_docAprehension=$fila["courier_docAprehension"];  
$id_tipo_actuacion_aduanera=$fila["id_tipo_actuacion_aduanera"];  

// identificando guia master    
$id_master=$fila["master"];
$sql_aux="SELECT master,id_aerolinea,id_consignatario FROM guia WHERE id='$id_master'";
$consulta_aux=mysql_query ($sql_aux,$conexion) or die (exit('Error 2: '.mysql_error()));
$fila_aux=mysql_fetch_array($consulta_aux);
$master=$fila_aux["master"];
$aerolinea=$fila_aux["id_aerolinea"];
$id_courier=$fila_aux["id_consignatario"];

//carga datos de la aerolinea
$sql="SELECT nombre FROM aerolinea WHERE id = '$aerolinea'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$aerolinea=$fila["nombre"];

//carga nombre de usuario
$sql="SELECT nombre FROM usuario WHERE id ='$id_usuario'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 4". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$usuario=$fila['nombre'];

//carga nombre de COURIER
$sql="SELECT nombre FROM couriers WHERE id ='$id_courier'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 5". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$consignatario=$fila['nombre'];

//carga nombre de FUNCIONARIO QUE AUTORIZA
$sql="SELECT nombre FROM courier_funcionario WHERE id ='$id_funcionario_autorizador'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 6". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$id_funcionario_autorizador=$fila['nombre'];

//carga nombre de COURIER
$sql="SELECT nombre FROM courier_funcionario WHERE id ='$id_funcionario_courier'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 7". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$id_funcionario_courier=$fila['nombre'];


// identificando el tipo de actuación aduanera
$sql3="SELECT nombre FROM tipo_actuacion_aduanera WHERE id='$id_tipo_actuacion_aduanera'";
$consulta3=mysql_query ($sql3,$conexion) or die (exit('Error 8: '.mysql_error()));
$fila3=mysql_fetch_array($consulta3);
$actuacion_aduanera=$fila3["nombre"];
if($id_tipo_actuacion_aduanera==3)
{
  $metadataPlanilla ='  <tr>
    <td bgcolor="#CCCCCC"><strong>Planilla de Envio</strong></td>
    <td align="left">'.$planillaEnvio.'</td>
  </tr>  
  <tr>
    <td bgcolor="#CCCCCC"><strong>Deposito</strong></td>
    <td align="left">'.$nombreDeposito.'</td>
  </tr>  
';
}
else
{
  $metadataPlanilla ='';
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
<table border="2" cellspacing="0" cellpadding="0" width="677">
  <tr>
    <td width="140"><img src="imagenes/logo.jpg" width="140" /></td>
    <td width="421" align="center">
        <font size="-1">SISTEMA DE GESTION DE CALIDAD NTC ISO 9001:2008</font><br />
        <strong><font size="+3">PLANILLA ENTREGA ZONA DE VERIFICACION</font></strong>
        <strong><font size="+1">GUIA CON ACTUACION ADUANERA</font></strong>
    </td>
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
    <strong>COURIER: $consignatario</strong>
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
    <td width="300" align="center"><font color="#990000" size="+4">$id_registro</font></td>
  </tr>
</table>
<br />

<table width="677" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><strong>Actuación Aduanera</strong></td>
    <td align="left"><font color="#990000" size="+4"><u>$actuacion_aduanera</u></font></td>
  </tr>
  <tr>
    <td><strong>No. Acta</strong></td>
    <td align="left">$courier_docAprehension</td>
  </tr>
  <tr>
    <td><strong>Aerolínea</strong></td>
    <td align="left"><u>$aerolinea</u></td>
  </tr>
  <tr>
    <td><strong>No. MAWB</strong></td>
    <td align="left">$master</td>
  </tr>
  <tr>
    <td><strong>No. HIJA</strong></td>
    <td align="left">$hija</td>
  </tr>
  <tr>
    <td><strong>Piezas</strong></td>
    <td align="left">$piezas</td>
  </tr>
  <tr>
    <td><strong>Peso</strong></td>
    <td align="left">$peso</td>
  </tr>  

</table>
<br>

<table width="677" border="1" cellspacing="0" cellpadding="0">  
  <tr>
    <td bgcolor="#CCCCCC" colspan="2" align="center"><strong>Datos De la Entrega</strong></td>    
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><strong>Acta de Entrega</strong></td>
    <td align="left">$no_acta / $fecha_acta</td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC"><strong>Vehiculo</strong></td>
    <td align="left">$placa_vehiculo<br>Conductor: $nombreConductor  / CC:$ccConductor        
    </td>
  </tr>  
  <tr>
    <td bgcolor="#CCCCCC"><strong>Funcionario Autorizador</strong></td>
    <td align="left">$id_funcionario_autorizador</td>
  </tr>  
  <tr>
    <td bgcolor="#CCCCCC"><strong>Funcionario Courier</strong></td>
    <td align="left">$id_funcionario_courier</td>
  </tr>  
  $metadataPlanilla
  <tr>
    <td align="center" bgcolor="#CCCCCC" colspan="2"><strong>Justificacion</strong></td>    
  </tr>  
  <tr>
    <td align="left" colspan="2">$justificacion</td>
  </tr>  


</table>

<br />
<br />
<br />
<br />

<table width="677" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="135" align="left"><strong>RECIBIDO POR:</strong></td>
    <td width="230" align="center">__________________________</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><strong></strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="48">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left"><strong>ELABORADO POR:</strong></td>
    <td align="center"><u>$usuario</u></td>
    <td>&nbsp;</td>
    <td rowspan="2" align="left">
    <font size="-2">AAAA-MM-DD - HOR<br />
            $fecha - $hora 
    </font>
  </td>
  </tr>
  <tr>
    <td></td>
    <td align="center"><strong>&nbsp;</strong></td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<br />
<br />
<strong>OBSERVACIONES</strong>
<br />
$observaciones
<br />
<hr>
Todas estas novedades se evidenciaron al momento recibir la carga en la zona de verificación.
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('DespachoCourier_'.$id_registro.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

?>

