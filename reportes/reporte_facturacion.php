<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("../config/configuracion.php");
set_time_limit(0); // Quita el limitante de tiempo para realizar una consulta grande
$id_usuario=$_SESSION['id_usuario'];
//Usuario
$sql3="SELECT nombre FROM usuario WHERE id='$id_usuario'";
$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila3=mysql_fetch_array($consulta3);
$usuario=$fila3["nombre"];
//****************************************
$buffer_master[]="";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../tema/estilo.css" rel="stylesheet" type="text/css" />
    <title>Reporte</title>
</head>
<body>
<div id="cargando">
	<p align="center">Espere mientras es generado su reporte...<img src="../imagenes/cargando.gif" width="20" height="21" align="absmiddle" /></p>
</div>
<?php
$buffer_master[]="";
$ano=date("Y");
$mes=date("m");
$dia=date("d");
$hora_registro=date("H:i:s");
$titulos="No.;FECHA FACTURA;No FACTURA;FACTURADO A;VALOR;IVA;MASTER;No GUIA;AEROLINEA;PIEZAS;PESO;VOLUMEN";
$rangoini=$_POST['rangoini'];
$rangofin=$_POST['rangofin'];
$id_aerolinea=$_POST['id_aerolinea'];
if ($id_aerolinea=="*")
{
	$sql_aerolinea="";
}
else
	{
		$sql_aerolinea="AND id_aerolinea ='$id_aerolinea'";
	}

$sql_P="SELECT * FROM guia_factura WHERE fecha_factura BETWEEN '$rangoini' AND '$rangofin' AND estado ='A' ORDER BY fecha_factura ASC";
$consulta_P=mysql_query ($sql_P,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta_P);
if ($nfilas > 0)
{
	//Creacion de Archivo
	$nombre_archivo="csv/".time()."-".$ano."-".$mes."-".$dia."_reporte_facturacion.csv";
	$CL=fopen("$nombre_archivo","a") or die("Problemas en la creacion del archivo de Plano, consulete con el soporte tecnico" . exit());
	fputs($CL,"$titulos;\n");
	$j=1;
	for ($i=1; $i<=$nfilas; $i++)
	{
		$fila_P=mysql_fetch_array($consulta_P);		
		//Carga Datos de la Tabla de Facturacion de la Guias
		$fecha_factura=$fila_P["fecha_factura"];		
		$nfactura=$fila_P["nfactura"];
		$valor_factura=number_format($fila_P["valor_factura"],0,",",".");
		$iva=number_format($fila_P["iva"],0,",",".");
		$fecha_factura=$fila_P["fecha_factura"];
		$facturadoa=$fila_P["facturadoa"];		
		$id_guia=$fila_P["id_guia"];
		
		//carga dato adicionales DE LA GUIA
		$sql="SELECT * FROM guia WHERE id='$id_guia' $sql_aerolinea";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila=mysql_fetch_array($consulta);
		$master=$fila["master"];
		$hija=$fila["hija"];
		include("../config/evaluador_inconsistencias.php");
		$peso=number_format($peso,0,",",".");
		$volumen=number_format($volumen,0,",",".");	
		$id_tipo_guia=$fila["id_tipo_guia"];		
		$aerolinea=$fila["id_aerolinea"];
		//carga dato adicionales
		$sql_add="SELECT nombre FROM aerolinea WHERE id='$aerolinea'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$aerolinea=$fila_add["nombre"];
		//************************				
	
		//CONSULTAMOS VALORES DE GUIA MASTER
		if ($id_tipo_guia != 3)
		{
			if($id_tipo_guia == 2)
			{
				$hija="-";
				$sql_aux="SELECT SUM(piezas) AS piezas, SUM(peso) AS peso, SUM(volumen) AS volumen FROM guia WHERE master = $id_guia AND id_tipo_bloqueo != 8";
				$consulta_aux=mysql_query ($sql_aux,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila_aux=mysql_fetch_array($consulta_aux);
				$piezas=$fila_aux["piezas"];
				$peso=number_format($fila_aux["peso"],0,",",".");
				$volumen=number_format($fila_aux["volumen"],0,",",".");					
			}
			else
			{
				$master="-";
			}
			fputs($CL,"$j;$fecha_factura;$nfactura;$facturadoa;$valor_factura;$iva;$master;$hija;$aerolinea;$piezas;$peso;$volumen\n");
			$j++;
		}		
	}
	fputs($CL,";REPORTE CREADO POR;$usuario; \n");
	fputs($CL,";FECHA;$ano-$mes-$dia; \n");
	fputs($CL,";HORA;$hora_registro; \n");
	fputs($CL,";FIN DEL REPORTE; \n");
	fclose($CL);
	echo '
			<p class="titulo_tab_principal">Archivo de Reporte</p>
			<hr>
			<br>
			<p align="center">El archivo se ha generado de manera Exitosa, oprima el bot&oacute;n para descargarlo.</p>
			<br>
			<br>
			<p align="center">
				<button type="button" onclick="document.location=\''.$nombre_archivo.'\'";>
						<img src="../imagenes/descargar-act2.png" title="Descargar"/><br>
						Descargar
				</button>
			</p>
			<p align="center">
				<img src="../imagenes/excel.jpg" width="45" height="43" align="absmiddle" />
				Recomendamos el uso de Microsoft Excel para la lectura de este archivo.
			</p>
		';
}
else
{
	echo '
		<p align="center"><font size="+3"><strong>ATENCION</strong></font></p>
		<hr>
		<br>
		<p align="center">No Facturas en en ese RANGO de FECHA para generar un REPORTE</p>
		';
}

?>
<script language="javascript">
	document.getElementById("cargando").innerHTML="";
</script>
</body>
</html>
