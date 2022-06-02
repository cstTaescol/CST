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
$titulos="No.;REMESA;FECHA;TRANSPORTADOR;AEROLINEA;VUELO;GUIA;PIEZAS;PESO;VOLUMEN;DEPOSITO;FPU;REFRIGERADO;EXCLUSIVO;VEHICULO;CONDUCTOR;USUARIO;HORA";
//Resepcion de datos
$id_transportador=$_POST['id_transportador'];
if ($id_transportador=="*")
{
	$sql_transportador="";
}
else
	{
		$sql_transportador="AND r.id_transportador ='$id_transportador'";
	}

$rangoini=$_POST['rangoini'];
$rangofin=$_POST['rangofin'];

$sql="SELECT r.*,c.* FROM remesa r LEFT JOIN carga_remesa c ON r.id = c.id_remesa WHERE r.estado != 'I' AND r.fecha BETWEEN '$rangoini' AND '$rangofin' $sql_transportador ORDER BY r.fecha ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);

//Discriminacion de aerolinea de usuario  TIPO 2
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id_aerolinea = '$id_aerolinea_user'";	
//*************************************

if ($nfilas > 0)
{
	//Creacion de Archivo
	$nombre_archivo="csv/".time()."-".$ano."-".$mes."-".$dia."_reporte_transportador.csv";
	$CL=fopen("$nombre_archivo","a") or die("Problemas en la creacion del archivo de Plano, consulete con el soporte tecnico");
	fputs($CL,"$titulos;\n");
	
	for ($i=1; $i<=$nfilas; $i++)
	{
		$fila=mysql_fetch_array($consulta);
		$fecha_transporte=$fila["fecha"];
		$transportador=$fila["id_transportador"];
		//carga dato adicionales
		$sql_add="SELECT nombre FROM transportador WHERE id='$transportador'";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$transportador=$fila_add["nombre"];
		//************************
		
		$id_guia=$fila["id_guia"];
		//carga dato de guia adicionales
		$sql_add="SELECT hija,id_deposito,id_aerolinea,id_vuelo FROM guia WHERE id='$id_guia' $sql_aerolinea";
		$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_add=mysql_fetch_array($consulta_add);
		$hija=$fila_add["hija"];
		if ($hija != "")
		{		
			$contador++;
			$id_deposito=$fila_add["id_deposito"];
			$id_aerolinea=$fila_add["id_aerolinea"];
			$id_vuelo=$fila_add["id_vuelo"];
			//************************
	
			//carga dato adicionales
			$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila_add=mysql_fetch_array($consulta_add);
			$id_aerolinea=$fila_add["nombre"];
			//************************
	
			//carga dato adicionales
			$sql_add="SELECT nombre,fpu FROM deposito WHERE id='$id_deposito'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila_add=mysql_fetch_array($consulta_add);
			$id_deposito=$fila_add["nombre"];
			$fpu=$fila_add["fpu"];
			//************************

			//carga dato adicionales
			$sql_add="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila_add=mysql_fetch_array($consulta_add);
			$nvuelo=$fila_add["nvuelo"];
			//************************
			
			
			
			$piezas=$fila["piezas"];
			$peso=number_format($fila["peso"],2,",",".");
			$volumen=number_format($fila["volumen"],2,",",".");
			$refrigerado=$fila["refrigerado"];
			$exclusivo=$fila["exclusivo"];
			$placa_vehiculo=$fila["id_vehiculo"];
			$remesa=$fila["id_remesa"];
			
			$conductor=$fila["id_conductor"];
			//carga dato adicionales
			$sql_add="SELECT nombre FROM conductor WHERE id='$conductor'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila_add=mysql_fetch_array($consulta_add);
			$conductor=$fila_add["nombre"];
			//************************
	
			$operario=$fila["id_usuario"];
	
			//carga dato adicionales
			$sql_add="SELECT nombre FROM usuario WHERE id='$operario'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila_add=mysql_fetch_array($consulta_add);
			$operario=$fila_add["nombre"];
			//************************
	
			$hora=$fila["hora"];
			
			fputs($CL,"$contador;$remesa;$fecha_transporte;$transportador;$id_aerolinea;$nvuelo;$hija;$piezas;$peso;$volumen;$id_deposito;$fpu;$refrigerado;$exclusivo;$placa_vehiculo;$conductor;$operario;$hora; \n");
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
		<p align="center">No existen GU&Iacute;AS en EN ESE RANGO para generar un REPORTE</p>
		';
}
?>
<script language="javascript">
	document.getElementById("cargando").innerHTML="";
</script>
</body>
</html>