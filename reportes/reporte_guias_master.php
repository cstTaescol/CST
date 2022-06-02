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
$error=false;

if(isset($_REQUEST['rangoini']))
	$rangoini=$_REQUEST['rangoini'];
else
	$error=true;

if(isset($_REQUEST['rangofin']))
	$rangofin=$_REQUEST['rangofin'];
else
	$error=true;

if(isset($_REQUEST['id_aerolinea']))
{
	$id_aerolinea=$_REQUEST['id_aerolinea'];
	if ($id_aerolinea=="*")
	{
		$sql_aerolinea="";
	}
	else
		{
			$sql_aerolinea="AND g.id_aerolinea ='$id_aerolinea'";
		}
	}
else
	$error=true;

if ($error==true)
{
	echo "Error: El servidor no pudo obtener la informacion necesaria, vuelva a intentarlo";
}
else
{

	$ano=date("Y");
	$mes=date("m");
	$dia=date("d");
	$hora_registro=date("H:i:s");

	$sql="SELECT v.*,g.* FROM vuelo v LEFT JOIN guia g ON v.id = g.id_vuelo WHERE g.id_tipo_bloqueo='7' AND g.fecha_creacion BETWEEN '$rangoini' AND '$rangofin' $sql_aerolinea ORDER BY g.fecha_creacion DESC";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$nfilas=mysql_num_rows($consulta);
//	echo $nfilas;
	if ($nfilas > 0)
	{
		$nombre_archivo="csv/".time()."-".$ano."-".$mes."-".$dia."_reporte_general.csv";
		$titulos="FECHA DE CREACION;AEROLINEA;VUELO;GUIA;DESCRIPCION;CONSIGNATARIO;FACTURADO A;FACTURA;VALOR FACTURA;";
		$CL=fopen("$nombre_archivo","a") or die("Problemas en la creacion del archivo de Plano, consulete con el soporte tecnico" . exit());
		fputs($CL,"$titulos;\n");
		for ($i=1; $i<=$nfilas; $i++)
		{
			$fila=mysql_fetch_array($consulta);
			$id_guia=$fila["id"];
			$fecha_creacion=$fila["fecha_creacion"];
			$id_aerolinea=$fila["id_aerolinea"];
			//carga dato adicionales
			$sql_add="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila_add=mysql_fetch_array($consulta_add);
			$aerolinea=$fila_add["nombre"];
			//************************
			$nvuelo=$fila["nvuelo"];
			$master=$fila["master"];
			$descripcion=$fila["descripcion"];
			$id_consignatario=$fila["id_consignatario"];
			//carga dato adicionales
			$sql_add="SELECT nombre FROM consignatario WHERE id='$id_consignatario'";
			$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila_add=mysql_fetch_array($consulta_add);
			$consignatario=$fila_add["nombre"];
			//************************
			$facturadoa=$fila["facturadoa"];
			$nfactura=$fila["nfactura"];
			$valor_factura=$fila["valor_factura"];
			fputs($CL,"$fecha_creacion;$aerolinea;$nvuelo;$master;$descripcion;$consignatario;$facturadoa;$nfactura;$valor_factura;\n");
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

	
}
?>
<script language="javascript">
	document.getElementById("cargando").innerHTML="";
</script>
</body>
</html>
