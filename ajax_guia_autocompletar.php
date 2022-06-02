<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
/*
PROCEDIMIENTO
1. BUSCAR GUIAS EN INCONSISTENCIAS FALTANTES Y DAR LA OPCION DE COMPLETAR LA GUIA.
2. CONSULTAR POR COINCIDENCIA DE GUIA PARA ALERTAR DE GUIA REPETIDA
*/

//obtenido atravez de ajax 
$guia=$_GET["guia"];
$tipo_guia=$_GET["tipo_guia"];
/*
$sql="SELECT id FROM guia WHERE hija = '$guia' AND id_tipo_bloqueo !='8' AND id_tipo_bloqueo !='9'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfila=mysql_num_rows($consulta);
if ($nfila == 1)
{
	$fila=mysql_fetch_array($consulta);
	$id_guia=$fila['id'];
	//consultamos que la inconsistencia exista y que no se ubiese solucionado
	$sql2="SELECT id FROM inconsistencias WHERE id_guia = '$id_guia' AND estado ='A'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$nfila2=mysql_num_rows($consulta2);	
	if ($nfila2 == 1)
		echo "	<br><strong><font color='red'>ALERTA:</strong> Gu&iacute;a con INCONSISTENCIAS FALTANTES</font>
				<br>&iquest;Desea solucionar la inconsistencia basandose en la Gu&iacute;a Original? <input type=\"button\" value=\"SI\" onclick=\"document.location='guia_faltante.php?id_guia=$id_guia'\" />
			";
		
}
*/

//buscar la coincidencia por tipo de guia
if ($tipo_guia==2)
	$sql="SELECT master FROM guia WHERE master = '$guia' AND id_tipo_bloqueo !='4'";
else
	$sql="SELECT hija FROM guia WHERE hija = '$guia' AND (id_tipo_bloqueo !='4' OR  id_tipo_bloqueo !='8')";

$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfila=mysql_num_rows($consulta);
if ($nfila > 0)
	echo "<strong><font color='red'>ALERTA:</strong> Gu&iacute;a Repetida</font>";
?>
 