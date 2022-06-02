<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
/*Respuesta emitida
1-1-1

Valor 1
Codigo de Error: 0= Sin error

Valor 2
Existen Datos: 0=No - 1=Si

Valor 3
Dato de Retorno: 0=No - Nombre de Consignatario

*/
require("config/configuracion.php");

$nit=$_REQUEST["nit"];
$sql="SELECT nombre FROM consignatario WHERE nit='$nit' AND estado='A'";
$consulta=mysql_query ($sql,$conexion) or die ("1-0-". mysql_error());
$nfilas=mysql_num_rows($consulta);
if ($nfilas != 0)
{
	$fila=mysql_fetch_array($consulta);
	$nombre_consignatario=$fila['nombre'];
	echo "0-1-$nombre_consignatario"; //Consignatario ya existente
}
else
{
	echo "0-0-0"; //Consignatario Nuevo
}

?>