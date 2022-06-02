<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if (isset($_GET['id_vuelo']))
{
$id_vuelo=$_GET["id_vuelo"];
$sql="SELECT g.id,g.id_vuelo,v.nvuelo FROM guia g LEFT JOIN vuelo v ON g.id_vuelo = v.id WHERE g.id_vuelo='$id_vuelo'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$id_usuario=$_SESSION['id_usuario'];
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
while($fila=mysql_fetch_array($consulta))
{
	$id_guia=$fila['id'];
	$nvuelo=$fila['nvuelo'];
	//1. almacenamiento del traking
	$sql_trak="INSERT INTO tracking (id_guia,
									 fecha_creacion,
									 hora,
									 evento,
									 tipo_tracking,
									 id_usuario) 
											value ('$id_guia',
												   '$fecha',
												   '$hora',
												   'GUIA RETIRADA DEL VUELO $nvuelo',
												   '1',
												   '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die (mysql_error());
	
	//2. liberamos la guia
	$sql="UPDATE guia SET id_vuelo='', id_tipo_bloqueo='1' WHERE id_vuelo = '$id_vuelo'";
	mysql_query($sql,$conexion) or die ("Error 2".mysql_error());	
}	
//3. Eliminamos el vuelo
$sql="DELETE FROM vuelo WHERE id = '$id_vuelo'";
mysql_query($sql,$conexion) or die ("Error 3".mysql_error());

echo "
<script language=\"javascript\">
	alert(\"VUELO eliminado de manera exitosa\");
	document.location='base.php';
</script>";
}
?>
