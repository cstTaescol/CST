<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$impresion="";

$id_registro=$_REQUEST["id_registro"];
//carga datos de la remesa
$sql="SELECT * FROM courier_despacho WHERE id='$id_registro' AND estado='A'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$id_guia=$fila["id_guia"];
$peso=$fila["peso"];
$piezas=$fila["piezas"];
$usuario=$fila["id_usuario"];
$fecha=$fila["fecha"];
$hora=$fila["hora"];

//carga datos de la guia
$sql="SELECT master,id_aerolinea,id_consignatario, courier_dato_fin, piezas_inconsistencia, peso_inconsistencia,courier_actuacion_aduanera,observaciones FROM guia WHERE id='$id_guia'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 2". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$master=$fila["master"];
$aerolinea=$fila["id_aerolinea"];
$id_courier=$fila["id_consignatario"];
$courier_dato_fin=$fila["courier_dato_fin"];
$piezas_fisico=$fila["piezas_inconsistencia"];
$peso_fisico=$fila["peso_inconsistencia"];
$observaciones=$fila["observaciones"];
$courier_actuacion_aduanera=$fila["courier_actuacion_aduanera"];
$nota="";
$metadata="";
if ($courier_actuacion_aduanera == 1)
{
  
  //identificando guias hijas con actuacion aduanera
  $sql2="SELECT hija,piezas,peso 
       FROM guia  
       WHERE master ='$id_guia'
       ORDER BY hija ASC
       ";
  $consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 9: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
  $nfilas=mysql_num_rows($consulta2);

  if($nfilas != 0)
  {
    $nota='<strong>Nota:</strong>Esta guía contiene piezas con actuación aduanera.<br>';
    $metadata ='<table width="677" border="1" cellspacing="0" cellpadding="0">
                  <tr>
                    <td bgcolor="#CCCCCC" align="center" colspan="3"><strong>DATOS DE LAS GUIAS CON ACTUACION ADUANERA</strong></td>
                  </tr>
                  <tr>
                    <td bgcolor="#CCCCCC" align="center"><strong>Guia</strong></td>
                    <td bgcolor="#CCCCCC" align="center"><strong>Piezas</strong></td>
                    <td bgcolor="#CCCCCC" align="center"><strong>Peso</strong></td>
                  </tr>  
              ';
    while($fila2=mysql_fetch_array($consulta2))
    {      
      //Comprobamos si el peso está registrado en decimales        
      if(ctype_digit($fila2['peso']))
      {
        $decimales=0;
      }
      else
      {  
        $decimales=2;
      }

      $metadata .= '
                    <tr>
                      <td align="right">'.$fila2['hija'].'</td>
                      <td align="right">'.number_format($fila2['piezas'],0,",",".").'</td>
                      <td align="right">'.number_format($fila2['peso'],$decimales,",",".").'</td>        
                    </tr>
                  ';
    }    
    $metadata .='</table>';
  }
  else
  {
      $nota="<strong>Nota:</strong>Esta guía contiene piezas con actuación aduanera. Pendiente relación de guías hijas.";
  }
}
else
{
  $nota="";
}

//carga datos de la aerolinea
$sql="SELECT nombre FROM aerolinea WHERE id = '$aerolinea'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$aerolinea=$fila["nombre"];

//carga nombre de usuario
$sql="SELECT nombre FROM usuario WHERE id ='$usuario'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 4". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$usuario=$fila['nombre'];

//carga nombre de COURIER
$sql="SELECT nombre FROM couriers WHERE id ='$id_courier'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 5". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$consignatario=$fila['nombre'];


//consultas auxiliares de Trasnportadores
$trasnportadores="";
$sql_aux="SELECT id,id_placa,nombre,no_documento FROM courier_transportador WHERE id_guia='$id_guia'";
$consulta_aux=mysql_query ($sql_aux,$conexion) or die ("ERROR 6: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila_aux=mysql_fetch_array($consulta_aux))
{
  $id=$fila_aux["id"];
  $no_documento=$fila_aux["no_documento"];
  $nombre=$fila_aux["nombre"];
  //consulta de la placa del vehiculo
  $id_placa=$fila_aux["id_placa"];
  $sql_aux2="SELECT placa FROM vehiculo_courier WHERE id='$id_placa'";
  $consulta_aux2=mysql_query($sql_aux2,$conexion) or die ("ERROR 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
  $fila_aux2=mysql_fetch_array($consulta_aux2);
  $placa=$fila_aux2["placa"];

  $trasnportadores .=   '<tr>
                              <td>'.$placa .'</td>'. 
                              '<td>'.$nombre .'</td>'.
                              '<td>'.$no_documento .'</td>
                         </tr>';
} 

//consultas auxiliares Nombre de Funcionario registrado por Seguridad
$sql_aux="SELECT fg.id,f.nombre,f.no_documento FROM courier_funcionario f LEFT JOIN courier_funcionarios_guia fg ON fg.id_funcionario = f.id WHERE fg.tipo='C' AND f.id_consignatario='$id_courier' AND fg.id_guia='$id_guia'";
$consulta_aux=mysql_query ($sql_aux,$conexion) or die ("ERROR 8: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila_aux=mysql_fetch_array($consulta_aux);
$nombre_funcionario=$fila_aux["nombre"];

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
      <strong><font size="+3">PLANILLA ENTREGA ZONA DE VERIFICACION</font></strong></td>
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
    <td><strong>Aerolínea</strong></td>
    <td colspan="2" align="left"><u>$aerolinea</u></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>No. MAWB</strong></td>
    <td colspan="2" align="left"><u>$master</u></td>
    <td>&nbsp;</td>
  </tr>
</table>
<br>
<table width="677" border="1" cellspacing="0" cellpadding="0">  
  <tr>
    <td bgcolor="#CCCCCC" colspan="2" align="center"><strong>Datos Según Guía Master</strong></td>
    <td bgcolor="#CCCCCC" colspan="2" align="center"><strong>Datos Según Despaletizaje</strong></td>
  </tr>
  <tr>
    <td><strong>Piezas</strong></td>
    <td align="left">$piezas</td>
    <td><strong>Piezas</strong></td>
    <td align="left">$piezas_fisico</td>
  </tr>
  <tr>
    <td><strong>Peso</strong></td>
    <td align="left">$peso</td>
    <td><strong>Peso</strong></td>
    <td align="left">$peso_fisico</td>
  </tr>
</table>
<br />
<br />
<table width="677" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#CCCCCC" align="center" colspan="3"><strong>DATOS DE VEHICULOS</strong></td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC" align="center"><strong>Placa</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>Conducto</strong></td>
    <td bgcolor="#CCCCCC" align="center"><strong>No. Documento</strong></td>
  </tr>
  $trasnportadores
</table>
<br />
$nota
<br />
$metadata
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
    <td align="center"><strong>$nombre_funcionario</strong></td>
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
    <td align="center">__<u>$usuario</u>__</td>
    <td>&nbsp;</td>
    <td rowspan="2" align="left">
    <font size="-2">AAAA-MM-DD - HOR<br />
            $courier_dato_fin 
    </font>
  </td>
  </tr>
  <tr>
    <td></td>
    <td align="center"><strong>RECEPCION</strong></td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<br />
<br />
<strong>OBSERVACIONES</strong>
<br />
$observaciones
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

