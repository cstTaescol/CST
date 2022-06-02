<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("../config/configuracion.php");
$id_usuario=$_SESSION['id_usuario'];

$sql3="SELECT nombre FROM usuario WHERE id='$id_usuario'";
$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila3=mysql_fetch_array($consulta3);
$usuario=$fila3["nombre"];
$ano=date("Y");
$mes=date("m");
$dia=date("d");
$hora_registro=date("H:i:s");
$cont=1;
//Carga de Líneas
if(isset($_REQUEST['id_vuelo']))
{
	//Consulta datos del vuelo
	$id_vuelo=$_REQUEST['id_vuelo'];
	$sql="SELECT v.nvuelo,v.matricula,v.fecha_finalizacion,v.hora_finalizado,a.nombre FROM vuelo v LEFT JOIN aerolinea a ON v.id_aerolinea = a.id WHERE v.id = '$id_vuelo'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		$nvuelo=$fila["nvuelo"];
		$matricula=$fila["matricula"];
		$hora_finalizado=$fila["hora_finalizado"];
		$aerolinea=$fila["nombre"];
		$fecha_finalizacion=$fila["fecha_finalizacion"];
		if($fecha_finalizacion=="")$fecha_finalizacion="NO HA FINALIZADO";
		
	}	
	//Creacion de Archivo
	$nombre_archivo="csv/".time()."-".$ano."-".$mes."-".$dia."_reporte_despaletizaje.csv";
	$CL=fopen("$nombre_archivo","a") or die("Problemas en la creacion del archivo de Plano, consulete con el soporte tecnico");
	fputs($CL,";AEROLINEA;$aerolinea \n");	
	fputs($CL,";No.VUELO;$nvuelo \n");	
	fputs($CL,";No.MATRICULA;$matricula \n");	
	fputs($CL,";FINALIZACION;$fecha_finalizacion - $hora_finalizado \n");	
	fputs($CL,"No;GUIA MASTER;GUIA HIJA;PIEZAS GUIA;PESO GUIA;PIEZAS BASCULA;PESO BASCULA;DIF PIEZAS;DIF PESO;PORCENTAJE;ESTIBA;GUACAL;YUTE;CAJA;CANECA;LARGUERO;ABOLLADA;RECINTADA;ABIERTA;ROTA;DESTILACION;HUMEDA;POSICION;OBSERVACIONES \n");	
	
	//Consulta Guia
	$sql="SELECT * FROM guia WHERE id_vuelo = '$id_vuelo'  AND id_tipo_guia != '2'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		$id_guia=$fila["id"];
		$master=$fila["master"];
		include("../config/master.php");
		$hija=$fila["hija"];
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		
		//Consulta para cargar las piezas y peso que ya se despaletizaron de esta guia.		
		include("../lib_despaletizaje_valores.php");
		
		$piezas_faltantes=$piezas-$piezas_recibido;
		$peso_faltante=$peso-$peso_recibido;

		
		//Definir Rango y tono de fondo de PORCENTAJE X Guia 
		$porcentaje=(($peso_recibido * 100)/$peso)-100;
		$porcentaje=number_format($porcentaje,1)."%";

		//Ubica la Posicion en Bodega
		$posicion="";
		$sql_posiscion="SELECT p.*,pc.* FROM posicion_carga pc LEFT JOIN posicion p ON pc.id_posicion=p.id WHERE pc.id_guia='$id_guia'";
		$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die (exit('Error '.mysql_error()));
		while($fila_posicion=mysql_fetch_array($consulta_posicion))
		{
			$plaqueta=$fila_posicion['rack'] ."-". $fila_posicion['seccion'] ."-". $fila_posicion['nivel'] ."-". $fila_posicion['lado'] ."-". $fila_posicion['fondo'];
			$posicion=$posicion." / ".$plaqueta;
		}
		//**************************************
		
		//CARGA DE TIPOS DE EMBALAJE Y ESTADO
		$estiba="";
		$huacal="";
		$yute="";
		$caja="";
		$caneca="";
		$larguero="";
		$abollada="";
		$recintada="";
		$abierta="";
		$rota="";
		$destilacion="";
		$humeda="";
		$observacion="";
		
		$sql2="SELECT * FROM despaletizaje WHERE id_guia = '$id_guia'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		while($fila2=mysql_fetch_array($consulta2))
		{
			if($fila2['estiba']=="S")$estiba="X";
			if($fila2['huacal']=="S")$huacal="X";
			if($fila2['yute']=="S")$yute="X";
			if($fila2['caja']=="S")$caja="X";
			if($fila2['caneca']=="S")$caneca="X";
			if($fila2['larguero']=="S")$larguero="X";
			if($fila2['abollada']=="S")$abollada="X";
			if($fila2['recintada']=="S")$recintada="X";
			if($fila2['abierta']=="S")$abierta="X";
			if($fila2['rota']=="S")$rota="X";
			if($fila2['destilacion']=="S")$destilacion="X";
			if($fila2['humeda']=="S")$humeda="X";
			if($fila2['observacion']=="S")$observacion .= $fila2['observacion'];
		}
		//Impresion del Final del grupo la guia
		fputs($CL,"$cont;$master;$hija;$piezas;$peso;$piezas_recibido;$peso_recibido;$piezas_faltantes;$peso_faltante;$porcentaje;$estiba;$huacal;$yute;$caja;$caneca;$larguero;$abollada;$recintada;$abierta;$rota;$destilacion;$humeda;$posicion;$observacion; \n");
		$cont++;
	}
}
	
fputs($CL,";REPORTE CREADO POR;$usuario; \n");
fputs($CL,";FECHA;$ano-$mes-$dia; \n");
fputs($CL,";HORA;$hora_registro; \n");
fputs($CL,";FIN DEL REPORTE; \n");
fclose($CL);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="../tema/estilo.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
        <p class="titulo_tab_principal">Reporte de Despaletizaje</p>
        <hr>
        <br>
        <br>El archivo se ha generado de manera Exitosa, oprima el bot&oacute;n para descargarlo.
        <br>
        <br>
        <p align="center">
            <button type="button" onclick="document.location='<?php echo $nombre_archivo; ?>'";>
                    <img src="../imagenes/descargar-act2.png" title="Descargar"/><br>
                    Descargar
            </button>
        </p>
        <p>
            <img src="../imagenes/excel.jpg" width="45" height="43" align="absmiddle" />
            Recomendamos el uso de Microsoft Excel para la lectura de este archivo.
        </p>
    </body>
</html>

