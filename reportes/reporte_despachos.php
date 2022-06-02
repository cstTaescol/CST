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
$ano=date("Y");
$mes=date("m");
$dia=date("d");
$contador=0;
$hora_registro=date("H:i:s");
$contenido=false;
$buffer="";
$tipoDespacho="";
$titulos="No.;TIPO;FECHA;No DESPACHO;GUIA;PIEZAS;PESO;VOLUMEN;AEROLINEA;VUELO;DISPOSICION;DESTINO";

//Resepcion de datos
$rangoini=$_POST['rangoini'];
$rangofin=$_POST['rangofin'];
$aerolineaFiltro=$_POST['id_aerolinea']; //revisar como aplicar el filtro por aerolinea en cada consulta

//Consulta despachos por REMESA
$sql="SELECT r.*,c.* FROM remesa r LEFT JOIN carga_remesa c ON r.id = c.id_remesa WHERE r.estado != 'I' AND r.fecha BETWEEN '$rangoini' AND '$rangofin' ORDER BY r.fecha ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
if ($nfilas > 0)
{
	$contenido=true;
	$tipoDespacho="REMESA";
	for ($i=1; $i<=$nfilas; $i++)
	{
		$fila=mysql_fetch_array($consulta);
		$fechaDespacho=$fila["fecha"];
		$nDespacho=$fila["id"];
		$id_guia=$fila["id_guia"];
		$piezasDespacho=$fila["piezas"];
		$pesoDespacho=number_format($fila["peso"],2,",",".");
		$volumenDespacho=number_format($fila["volumen"],2,",",".");	

		//carga dato de guia adicionales
		$sql_add="SELECT hija,id_deposito,id_aerolinea,id_vuelo,id_disposicion FROM guia WHERE id='$id_guia'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);				
		$guiaDespacho=$fila_add["hija"];
		$id_aerolinea=$fila_add["id_aerolinea"];
		$id_vuelo=$fila_add["id_vuelo"];
		$id_disposicion=$fila_add["id_disposicion"];
		$id_deposito=$fila_add["id_deposito"];

		//Evalua Coincidencia de Aerolinea seleccionada
		if($aerolineaFiltro != "*"){
			if($aerolineaFiltro != $id_aerolinea)
			continue;
		}
		/*******************************/

		//carga dato adicionales
		$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$aerolineaDespacho=$fila_add["nombre"];		
		//************************
		
		//carga dato adicionales
		$sql_add="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$vueloDespacho=$fila_add["nvuelo"];
		//************************						
		
		//carga dato adicionales
		$sql_add="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$disposicionDespacho=$fila_add["nombre"];
		//************************
		
		//carga dato adicionales
		$sql_add="SELECT nombre FROM deposito WHERE id='$id_deposito'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 6: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$destinoDespacho=$fila_add["nombre"];
		//************************

		$contador++;
		$buffer .= "$contador;$tipoDespacho;$fechaDespacho;$nDespacho;$guiaDespacho;$piezasDespacho;$pesoDespacho;$volumenDespacho;$aerolineaDespacho;$vueloDespacho;$disposicionDespacho;$destinoDespacho;\n";		
	}			
}

//Consulta despachos por DESCARGUE DIRECTO
$sql="SELECT d.id,d.fecha,d.piezas,d.peso,d.volumen,d.agencia AS destino,g.hija,g.id_aerolinea,g.id_vuelo,g.id_disposicion FROM descargue_directo d LEFT JOIN guia g ON g.id = d.id_guia WHERE d.estado != 'I' AND d.fecha BETWEEN '$rangoini' AND '$rangofin' ORDER BY d.fecha ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
if ($nfilas > 0)
{
	$contenido=true;
	$tipoDespacho="DESCARGUE DIRECTO";
	for ($i=1; $i<=$nfilas; $i++)
	{
		$fila=mysql_fetch_array($consulta);
		$fechaDespacho=$fila["fecha"];
		$nDespacho=$fila["id"];
		$guiaDespacho=$fila["hija"];		
		$piezasDespacho=$fila["piezas"];
		$pesoDespacho=number_format($fila["peso"],2,",",".");
		$ctnPuntos=substr_count($fila["volumen"],".");
		if ($ctnPuntos <= 1)	{
			$volumenDespacho=number_format(floatval($fila["volumen"]),2,",",".");	
		}	
		else{
			$subNumero=explode(".",$fila["volumen"]);
			$volumenDespacho=number_format(floatval($subNumero[0].$subNumero[1].",".$subNumero[2]),2,",",".");	
		}
		$id_aerolinea=$fila["id_aerolinea"];
		$id_vuelo=$fila["id_vuelo"];
		$id_disposicion=$fila["id_disposicion"];
		$destinoDespacho=$fila["destino"];

		//Evalua Coincidencia de Aerolinea seleccionada
		if($aerolineaFiltro != "*"){
			if($aerolineaFiltro != $id_aerolinea)
			continue;
		}
		/*******************************/

		//carga dato adicionales
		$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 8: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$aerolineaDespacho=$fila_add["nombre"];
		//************************
		
		//carga dato adicionales
		$sql_add="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 9: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$vueloDespacho=$fila_add["nvuelo"];
		//************************						
		
		//carga dato adicionales
		$sql_add="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 10: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$disposicionDespacho=$fila_add["nombre"];
		//************************

		$contador++;
		$buffer .= "$contador;$tipoDespacho;$fechaDespacho;$nDespacho;$guiaDespacho;$piezasDespacho;$pesoDespacho;$volumenDespacho;$aerolineaDespacho;$vueloDespacho;$disposicionDespacho;$destinoDespacho;\n";				
	}
}

//Consulta despachos por CABOTAJE
$sql="SELECT d.id,d.fecha,d.piezas,d.peso,d.volumen,d.destinatario AS destino,g.hija,g.id_aerolinea,g.id_vuelo,g.id_disposicion FROM cabotaje d LEFT JOIN guia g ON g.id = d.id_guia WHERE d.estado != 'I' AND d.fecha BETWEEN '$rangoini' AND '$rangofin' ORDER BY d.fecha ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 11: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
if ($nfilas > 0)
{
	$contenido=true;
	$tipoDespacho="CABOTAJE";
	for ($i=1; $i<=$nfilas; $i++)
	{
		$fila=mysql_fetch_array($consulta);
		$fechaDespacho=$fila["fecha"];
		$nDespacho=$fila["id"];
		$guiaDespacho=$fila["hija"];		
		$piezasDespacho=$fila["piezas"];
		$pesoDespacho=number_format($fila["peso"],2,",",".");
		$ctnPuntos=substr_count($fila["volumen"],".");
		if ($ctnPuntos <= 1)	{
			$volumenDespacho=number_format(floatval($fila["volumen"]),2,",",".");	
		}	
		else{
			$subNumero=explode(".",$fila["volumen"]);
			$volumenDespacho=number_format(floatval($subNumero[0].$subNumero[1].",".$subNumero[2]),2,",",".");	
		}
		$id_aerolinea=$fila["id_aerolinea"];
		$id_vuelo=$fila["id_vuelo"];
		$id_disposicion=$fila["id_disposicion"];
		$destinoDespacho=$fila["destino"];

		//Evalua Coincidencia de Aerolinea seleccionada
		if($aerolineaFiltro != "*"){
			if($aerolineaFiltro != $id_aerolinea)
			continue;
		}
		/*******************************/

		//carga dato adicionales
		$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 12: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$aerolineaDespacho=$fila_add["nombre"];
		//************************
		
		//carga dato adicionales
		$sql_add="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 13: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$vueloDespacho=$fila_add["nvuelo"];
		//************************						
		
		//carga dato adicionales
		$sql_add="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 14: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$disposicionDespacho=$fila_add["nombre"];
		//************************

		$contador++;
		$buffer .= "$contador;$tipoDespacho;$fechaDespacho;$nDespacho;$guiaDespacho;$piezasDespacho;$pesoDespacho;$volumenDespacho;$aerolineaDespacho;$vueloDespacho;$disposicionDespacho;$destinoDespacho;\n";				
	}
}

//Consulta despachos por CORREO
$sql="SELECT r.*,c.* FROM correo r LEFT JOIN carga_correo c ON r.id = c.id_correo WHERE r.estado != 'I' AND r.fecha BETWEEN '$rangoini' AND '$rangofin' ORDER BY r.fecha ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 15: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
if ($nfilas > 0)
{
	$contenido=true;
	$tipoDespacho="CORREO";
	for ($i=1; $i<=$nfilas; $i++)
	{
		$fila=mysql_fetch_array($consulta);
		$fechaDespacho=$fila["fecha"];
		$nDespacho=$fila["id"];
		$id_guia=$fila["id_guia"];
		$piezasDespacho=$fila["piezas"];
		$pesoDespacho=number_format($fila["peso"],2,",",".");
		$volumenDespacho=number_format($fila["volumen"],2,",",".");	
		$destinoDespacho=$fila["tipo_entrega"]=="D"?"DIRECTA EN BODEGA":"BODEGA DIAN";

		//carga dato de guia adicionales
		$sql_add="SELECT hija,id_aerolinea,id_vuelo,id_disposicion FROM guia WHERE id='$id_guia'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 16: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$guiaDespacho=$fila_add["hija"];
		$id_aerolinea=$fila_add["id_aerolinea"];
		$id_vuelo=$fila_add["id_vuelo"];
		$id_disposicion=$fila_add["id_disposicion"];

		//Evalua Coincidencia de Aerolinea seleccionada
		if($aerolineaFiltro != "*"){
			if($aerolineaFiltro != $id_aerolinea)
			continue;
		}
		/*******************************/		

		//carga dato adicionales
		$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 17: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$aerolineaDespacho=$fila_add["nombre"];
		//************************
		
		//carga dato adicionales
		$sql_add="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 18: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$vueloDespacho=$fila_add["nvuelo"];
		//************************						
		
		//carga dato adicionales
		$sql_add="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 19: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$disposicionDespacho=$fila_add["nombre"];
		//************************		

		$contador++;
		$buffer .= "$contador;$tipoDespacho;$fechaDespacho;$nDespacho;$guiaDespacho;$piezasDespacho;$pesoDespacho;$volumenDespacho;$aerolineaDespacho;$vueloDespacho;$disposicionDespacho;$destinoDespacho;\n";		
	}			
}


//Consulta despachos por TRASBORDO
$sql="SELECT d.id,d.fecha,d.piezas,d.peso,d.volumen,d.destinatario AS destino,g.hija,g.id_aerolinea,g.id_vuelo,g.id_disposicion FROM trasbordo d LEFT JOIN guia g ON g.id = d.id_guia WHERE d.estado != 'I' AND d.fecha BETWEEN '$rangoini' AND '$rangofin' ORDER BY d.fecha ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 20: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
if ($nfilas > 0)
{
	$contenido=true;
	$tipoDespacho="TRASBORDO";
	for ($i=1; $i<=$nfilas; $i++)
	{
		$fila=mysql_fetch_array($consulta);
		$fechaDespacho=$fila["fecha"];
		$nDespacho=$fila["id"];
		$guiaDespacho=$fila["hija"];		
		$piezasDespacho=$fila["piezas"];
		$pesoDespacho=number_format($fila["peso"],2,",",".");
		$ctnPuntos=substr_count($fila["volumen"],".");
		if ($ctnPuntos <= 1)	{
			$volumenDespacho=number_format(floatval($fila["volumen"]),2,",",".");	
		}	
		else{
			$subNumero=explode(".",$fila["volumen"]);
			$volumenDespacho=number_format(floatval($subNumero[0].$subNumero[1].",".$subNumero[2]),2,",",".");	
		}		
		$id_aerolinea=$fila["id_aerolinea"];
		$id_vuelo=$fila["id_vuelo"];
		$id_disposicion=$fila["id_disposicion"];
		$destinoDespacho=$fila["destino"];

		//Evalua Coincidencia de Aerolinea seleccionada
		if($aerolineaFiltro != "*"){
			if($aerolineaFiltro != $id_aerolinea)
			continue;
		}
		/*******************************/

		//carga dato adicionales
		$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 21: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$aerolineaDespacho=$fila_add["nombre"];
		//************************
		
		//carga dato adicionales
		$sql_add="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 22: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$vueloDespacho=$fila_add["nvuelo"];
		//************************						
		
		//carga dato adicionales
		$sql_add="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 23: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$disposicionDespacho=$fila_add["nombre"];
		//************************

		$contador++;
		$buffer .= "$contador;$tipoDespacho;$fechaDespacho;$nDespacho;$guiaDespacho;$piezasDespacho;$pesoDespacho;$volumenDespacho;$aerolineaDespacho;$vueloDespacho;$disposicionDespacho;$destinoDespacho;\n";				
	}
}

//Consulta despachos por OTROS
$sql="SELECT r.*,c.* FROM otros r LEFT JOIN carga_otros c ON r.id = c.id_otros WHERE r.estado != 'I' AND r.fecha BETWEEN '$rangoini' AND '$rangofin' ORDER BY r.fecha ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 24: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
if ($nfilas > 0)
{
	$contenido=true;
	$tipoDespacho="OTROS";
	for ($i=1; $i<=$nfilas; $i++)
	{		
		$fila=mysql_fetch_array($consulta);
		$fechaDespacho=$fila["fecha"];
		$nDespacho=$fila["id"];
		$id_guia=$fila["id_guia"];
		$piezasDespacho=$fila["piezas"];
		$pesoDespacho=number_format($fila["peso"],2,",",".");
		$volumenDespacho=number_format($fila["volumen"],2,",",".");	
		$destinoDespacho=substr($fila["observaciones"],0,30)."...";

		//carga dato de guia adicionales
		$sql_add="SELECT hija,id_aerolinea,id_vuelo,id_disposicion FROM guia WHERE id='$id_guia'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 25: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$guiaDespacho=$fila_add["hija"];
		$id_aerolinea=$fila_add["id_aerolinea"];
		$id_vuelo=$fila_add["id_vuelo"];
		$id_disposicion=$fila_add["id_disposicion"];

		//Evalua Coincidencia de Aerolinea seleccionada
		if($aerolineaFiltro != "*"){
			if($aerolineaFiltro != $id_aerolinea)
			continue;

		}
		/*******************************/
		
		//carga dato adicionales
		$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 26: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$aerolineaDespacho=$fila_add["nombre"];
		//************************
		
		//carga dato adicionales
		$sql_add="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 27: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$vueloDespacho=$fila_add["nvuelo"];
		//************************						
		
		//carga dato adicionales
		$sql_add="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 28: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$disposicionDespacho=$fila_add["nombre"];
		//************************		

		$contador++;			
		$buffer .= "$contador;$tipoDespacho;$fechaDespacho;$nDespacho;$guiaDespacho;$piezasDespacho;$pesoDespacho;$volumenDespacho;$aerolineaDespacho;$vueloDespacho;$disposicionDespacho;$destinoDespacho;\n";		
	}			
}


if($contenido){

	//Creacion de Archivo
	$nombre_archivo="csv/".time()."-".$ano."-".$mes."-".$dia."_reporte_despachos.csv";
	$CL=fopen("$nombre_archivo","a") or die("Problemas en la creacion del archivo de Plano, consulete con el soporte tecnico");
	fputs($CL,"$titulos;\n");		
	fputs($CL,$buffer);
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
		<p align="center">No existen GU&Iacute;AS en EN ESE RANGO para generar un REPORTE</p>
		';
}
?>
<script language="javascript">
	document.getElementById("cargando").innerHTML="";
</script>
</body>
</html>