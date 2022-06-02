<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$i=0;
$transportador="";
$fecha_registrada="";
$id_vehiculo="";
$conductor="";
$deposito="";
$id_remesa="";
$impresion="";
$estado_botones='disabled="disabled"';
if (isset($_REQUEST["nremesa"]))
{
	
	$id_remesa=$_REQUEST["nremesa"];
	$sql="SELECT * FROM remesa WHERE id = '$id_remesa' AND estado='A'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		$estado_botones='';
		$fecha_registrada=$fila["fecha"];
		$id_deposito=$fila["id_deposito"];
		$id_transportador=$fila["id_transportador"]; 
		$id_vehiculo=$fila["id_vehiculo"];
		$id_conductor=$fila["id_conductor"];
		
		//carga datos
		$sql2="SELECT nombre FROM deposito WHERE id='$id_deposito'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 2". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$deposito=$fila2['nombre'];
	
		//carga datos
		$sql2="SELECT nombre FROM transportador WHERE id='$id_transportador'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 3". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$transportador=$fila2['nombre'];

		//carga datos
		$sql2="SELECT nombre FROM conductor WHERE id='$id_conductor'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 4". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$conductor=$fila2['nombre'];
		
		//carga datos
		$sql_guias="SELECT * FROM carga_remesa WHERE id_remesa='$id_remesa'";
		$consult_guias=mysql_query ($sql_guias,$conexion) or die ("ERROR: 5". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$impresion=$impresion.'<table border="1" cellpadding="0" cellspacing="0">';
		while($fila_guias=mysql_fetch_array($consult_guias))
		{
			$i++;
			$id_guia=$fila_guias['id_guia'];
			$piezas=$fila_guias['piezas'];
			$peso=$fila_guias['peso'];
			$volumen=$fila_guias['volumen'];
			
			//carga datos
			$sql2="SELECT hija FROM guia WHERE id='$id_guia'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: 6". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila2=mysql_fetch_array($consulta2);
			$hija=$fila2['hija'];
			$impresion=$impresion.'
			  <tr>
				<td bgcolor="green">GUIA:</td><td>'.$hija.'</td>
				<td bgcolor="green">PIEZAS:</td><td>'.$piezas.'</td>
				<td bgcolor="green">PESO:</td><td>'.$peso.'</td>
				<td bgcolor="green">VOLUMEN:</td><td>'.$volumen.'</td>
				<td bgcolor="green">OBSERVACIONES:</td><td><input type="text" name="descripcion'.$i.'" value=""></td>
				<input type="hidden" name="id_guia'.$i.'" value="'.$id_guia.'">
			  </tr>
			';
		}
		$impresion=$impresion.'</table>';
	}	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function abrir(url)
{
	popupWin = window.open(url,'','directories, status, scrollbars, resizable, dependent, width=640, height=480, left=100, top=100')
	//  popupWin = window.open('pdf_remesa.php','nombre_ventana','menubar, toolbar, location, directories, status, scrollbars, resizable, dependent, width=640, height=480, left=0, top=0')
}
// funcion para validar
function validar()
{	
	if (document.registro.scan.value=="")
	{
		alert("Atencion: Se requiere que Adjunte el  ARCHIVO DE CUMPLIDO");
		return(false);
	}
}

</script>
</head>
<body>
<?php 
require("menu.php"); 
//Privilegios Consultar Todo el Modulo
$id_objeto=78; 
include("config/provilegios_modulo.php");  
//---------------------------

?>
<p align="center"><font size="+2" color="#0066FF"><strong>REPORTAR CUMPLIDOS</strong></font> <img src="imagenes/sello.gif" width="50" height="50" align="absmiddle" />
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>
        <form method="post" name="consulta" id="consulta" action="cumplidos1.php" >
            Remesa No.<input type="text" name="nremesa" /><input type="submit" value="Buscar" /><br />
        	<p align="center">Ingrese el n&uacute;mero de la Remesa a la que desea reportar el cumplido.</p>
        </form>
    </td>
  </tr>
</table>
</p>
<hr />
<p>
<form name="registro" id="registro" onsubmit="return validar();"  action="cumplidos2.php"   enctype="multipart/form-data" method="post">
<table width="50%" border="1" align="center" bordercolor="#0066CC">
  <tr>
    <td>
        <p align="center"><font color="red" size="+1">DESCRIPCION DE LA REMESA</font><br></p>
        <font color="blue"><strong>REMESA No:</strong></font>
        <button onClick="abrir('pdf_remesa.php?id_remesa=<?php echo $id_remesa ?>')" <?php echo $estado_botones?>>&nbsp;<?php echo $id_remesa ?>&nbsp;</button><br>
        <font color="blue"><strong>TRANSPORTADOR:</strong></font> <?php echo $transportador ?><br>
        <font color="blue"><strong>FECHA:</strong></font> <?php echo $fecha_registrada ?><br>
        <font color="blue"><strong>VEHICULO:</strong></font> <?php echo $id_vehiculo ?><br>
        <font color="blue"><strong>CONDUCTOR:</strong></font> <?php echo $conductor ?><br>		
        <font color="blue"><strong>DEPOSITO:</strong></font> <?php echo $deposito ?><br>		
        <input type="hidden" name="id_remesa" value="<?php echo $id_remesa ?>">
        <input type="hidden" name="cantidad_guias" value="<?php echo $i ?>">
    </td>
  </tr>
  <tr>
    <td>
        <p><font color="blue"><strong>DESCRIPCION DEL DESPACHO</strong></font></p>
        <p align="center"><?php echo $impresion ?></p>
    </td>
  </tr>
  <tr>
    <td><p><font color="blue"><strong>REMESA ESCANEADA</strong></font></p><p align="center">
    	<input name="scan" id="scan" type="file" size="15" <?php echo $estado_botones?> /></p><br>
    </td>
  </tr>
  <tr>
    <td align="center">
    <input type="reset" value="Limpiar">
    <input type="submit" value="Guardar" id="guardar" <?php echo $estado_botones?> ></td>
  </tr>
</table>
</form>
</p>
</body>
</html>