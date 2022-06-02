<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("../config/configuracion.php");
$totalpiezas=0;
$totalpeso=0;
$totalvolumen=0;
$id_usuario=$_SESSION['id_usuario'];
//Usuario
$sql3="SELECT nombre FROM usuario WHERE id='$id_usuario'";
$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila3=mysql_fetch_array($consulta3);
$usuario=$fila3["nombre"];
//****************************************
$contador=1;
//Discriminacion de aerolinea de usuario TIPO 3
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND v.id_aerolinea = '$id_aerolinea_user'";	
//*************************************

$ano=date("Y");
$mes=date("m");
$dia=date("d");
$hora_registro=date("H:i:s");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../tema/estilo.css" rel="stylesheet" type="text/css" />
    <title>Reporte de Inventario</title>
</head>
<body>
<div id="cargando">
	<p align="center">Espere mientras es generado su reporte...<img src="../imagenes/cargando.gif" width="20" height="21" align="absmiddle" /></p>
</div>
<?php
//1. Identificamos los vuelos Finalizados
$sql_invent="SELECT DISTINCT id_vuelo FROM guia WHERE (id_tipo_bloqueo = '3' OR id_tipo_bloqueo = '5' OR id_tipo_bloqueo = '6' OR id_tipo_bloqueo = '9' OR id_tipo_bloqueo = '10')";
$consulta_invent=mysql_query ($sql_invent,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas_invent=mysql_num_rows($consulta_invent);
if ($nfilas_invent > 0)
{
	//Creacion de Archivo
	$nombre_archivo="csv/".time()."-".$ano."-".$mes."-".$dia."_reporte_inventario.csv";
	$CL=fopen("$nombre_archivo","a") or die("Problemas en la creacion del archivo de Plano, consulete con el soporte tecnico");
	fputs($CL,"No;CONSOLIDADO;GUIA;VUELO;AEROLINEA;DESTINO;CONSIGNATARIO;PIEZAS;PESO;VOLUMEN;VENCIMIENTO;MANIFIESTO;POSICION; ; \n");	
	for ($i=1; $i<=$nfilas_invent; $i++)
	{
		$fila_invent=mysql_fetch_array($consulta_invent);
		$resultado=$fila_invent["id_vuelo"];
		$sql="SELECT v.id_aerolinea,v.nvuelo,v.hora_finalizado,v.nmanifiesto,v.id,g.* FROM vuelo v LEFT JOIN guia g ON v.id = g.id_vuelo WHERE g.id_vuelo='$resultado' AND g.id_tipo_guia !='2' AND (id_tipo_bloqueo = '3' OR id_tipo_bloqueo = '5' OR id_tipo_bloqueo = '6' OR id_tipo_bloqueo = '9' OR id_tipo_bloqueo = '10') AND (g.faltante_total='N') $sql_aerolinea ORDER BY g.id_vuelo ASC";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$nfilas=mysql_num_rows($consulta);
		for ($j=1; $j<=$nfilas; $j++)
		{
			$fila=mysql_fetch_array($consulta);
			$id_guia=$fila["id"];
			$nmanifiesto=$fila["nmanifiesto"];
			$nvuelo=$fila["nvuelo"];
			$hija=$fila["hija"];
			$id_tipo_guia=$fila["id_tipo_guia"];
			if ($id_tipo_guia==3)
			{
				$master=$fila["master"];
				require("../config/master.php");
			}
			else
			{
				$sql3="SELECT nombre FROM tipo_guia WHERE id='$id_tipo_guia'";
				$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila3=mysql_fetch_array($consulta3);
				$master=$fila3["nombre"];
			}		
			// identificando aerolinea
			$aerolinea=$fila["id_aerolinea"];
			$sql3="SELECT nombre FROM aerolinea WHERE id='$aerolinea'";
			$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila3=mysql_fetch_array($consulta3);
			$aerolinea=$fila3["nombre"];
			
			//calculo del valor pendiente por entregar
			include("../config/evaluador_inconsistencias.php"); //calcula y general el valor de $piezas, $peso y $volumen luego de las inconsistencias.		
			$piezas_pendientes_despachar=$piezas-$fila["piezas_despacho"];
			$peso_pendientes_despachar=$peso-$fila["peso_despacho"];		
			$volumen_pendientes_despachar=$volumen-$fila["volumen_despacho"];
			
			$totalpiezas=$totalpiezas+$piezas_pendientes_despachar;
			$totalpeso=$totalpeso+$peso_pendientes_despachar;
			$totalvolumen=$totalvolumen+$volumen_pendientes_despachar;
		
			//formateamos la presentacion de los valores
			$peso_pendientes_despachar=number_format($peso_pendientes_despachar,2,",",".");
			$volumen_pendientes_despachar=number_format($volumen_pendientes_despachar,2,",",".");
			
			//identificando consignatario
			$consignatario=$fila["id_consignatario"];
			$sql3="SELECT nombre FROM consignatario WHERE id='$consignatario'";
			$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila3=mysql_fetch_array($consulta3);
			$consignatario=$fila3["nombre"];		
				
			// Destino
			$id_disposicion=$fila["id_disposicion"];
			//Evaluar si la disposicion no exige ningun tipo de deposito
			if ($id_disposicion ==28 || $id_disposicion ==21 || $id_disposicion ==20 || $id_disposicion ==19 || $id_disposicion ==25 || $id_disposicion ==29 || $id_disposicion ==23 || $id_disposicion ==13 || $id_disposicion ==15)
				{
					$sql3="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
					$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 6: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					$fila3=mysql_fetch_array($consulta3);
					$destino=$fila3["nombre"];
				}
			else
			{
				$id_deposito=$fila["id_deposito"];
				$sql3="SELECT nombre FROM deposito WHERE id='$id_deposito'";
				$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila3=mysql_fetch_array($consulta3);
				$destino=$fila3["nombre"];
			}
			if ($id_disposicion == 26 || $id_disposicion == 27) 
			{
				$destino="BODEGA DIAN";
			}				
			//FECHAS
			$fecha_vencimiento=explode("-",$fila['fecha_vencimiento']);
			$aa=$fecha_vencimiento[0];
			$mm=$fecha_vencimiento[1];
			$dd=$fecha_vencimiento[2];
			$fecha_vencimiento=$aa."-".$mm."-".$dd;
			
			//Posicion en BODEGA
			$posicion="";
			//Ubica la Posicion en Bodega
			$sql_posiscion="SELECT p.*,pc.* FROM posicion_carga pc LEFT JOIN posicion p ON pc.id_posicion=p.id WHERE pc.id_guia='$id_guia'";
			$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit('Error '.mysql_error()));
			while($fila_posicion=mysql_fetch_array($consulta_posicion))
			{
				$plaqueta=$fila_posicion['rack'] . $fila_posicion['seccion'] . $fila_posicion['nivel'] . $fila_posicion['lado'] . $fila_posicion['fondo'];
				$posicion=$posicion." - ".$plaqueta;
			}
			fputs($CL,"$contador;$master;$hija;$nvuelo;$aerolinea;$destino;$consignatario;$piezas_pendientes_despachar;$peso_pendientes_despachar;$volumen_pendientes_despachar;$fecha_vencimiento;$nmanifiesto;$posicion; ; \n");
			$contador++;
		}
		
	}
	
	fputs($CL,";REPORTE CREADO POR;$usuario; \n");
	fputs($CL,";FECHA;$ano-$mes-$dia; \n");
	fputs($CL,";HORA;$hora_registro; \n");
	fputs($CL,";FIN DEL REPORTE; \n");
	fclose($CL);
	echo '
		<p class="titulo_tab_principal">Reporte de Inventario</p>
		<hr>
		<br>
		<br>El archivo se ha generado de manera Exitosa, oprima el bot&oacute;n para descargarlo.
		<br>
		<br>
		<p align="center">
			<button type="button" onclick="document.location=\''.$nombre_archivo.'\'";>
					<img src="../imagenes/descargar-act2.png" title="Descargar"/><br>
					Descargar
			</button>
		</p>
		<p>
			<img src="../imagenes/excel.jpg" width="45" height="43" align="absmiddle" />
			Recomendamos el uso de Microsoft Excel para la lectura de este archivo.
		</p>';
	
}
else
{
	echo '
	<p align="center"><font size="+3"><strong>ATENCION</strong></font></p>
	<hr>
	<br>
	<p>No existen GU&Iacute;AS en INVENTARIO para Generar un REPORTE</p>
	';
}





$sql="SELECT v.id_aerolinea,v.nvuelo,v.hora_finalizado,v.nmanifiesto,v.id,g.* FROM vuelo v LEFT JOIN guia g ON v.id = g.id_vuelo WHERE g.id_tipo_guia !='2' AND (id_tipo_bloqueo = '3' OR id_tipo_bloqueo = '5' OR id_tipo_bloqueo = '6' OR id_tipo_bloqueo = '10') $sql_aerolinea ORDER BY g.id_vuelo ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
if ($nfilas > 0)
{

	
	for ($i=1; $i<=$nfilas; $i++)
	{
	}
	
}

?>
<script language="javascript">
	document.getElementById("cargando").innerHTML="";
</script>
</body>
</html>