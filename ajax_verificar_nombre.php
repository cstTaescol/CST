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
$valorBusqueda=$_REQUEST["valorBusqueda"];
$tabla=$_REQUEST["tbl"];
if($tabla == 'consignatario')
{	
	$campo="nombre";
}
else if ($tabla == 'agente_carga')
{
	$campo="razon_social";	
}
else
{
	echo "1-0-Error";
	die();
}

$size = strlen($valorBusqueda);
if ($size >= 2)
{
	$sql="SELECT $campo FROM $tabla WHERE $campo like '%$valorBusqueda%' AND estado='A'";
	$consulta=mysql_query ($sql,$conexion) or die ("1-0-". mysql_error());
	$nfilas=mysql_num_rows($consulta);
	if ($nfilas != 0)
	{
        $buffer="";
        while($fila=mysql_fetch_array($consulta))
        {
            $buffer .= "* " . $fila["$campo"] ."<br>";
        }		
		$campoConsultado=$buffer;
		echo "0-1-$campoConsultado"; //Dato ya existente
	}
	else
	{
		echo "0-0-0"; //Dato Nuevo
	}
}
?>