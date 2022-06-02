<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");

//obtenido atravez de ajax 
$q=$_GET["q"];
//buscar la coincidencia
$sql="SELECT id,nombre FROM couriers WHERE nombre LIKE '%$q%' AND estado='A' ORDER BY nombre ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfila=mysql_num_rows($consulta);
if ($nfila > 0)
{	
	while($fila=mysql_fetch_array($consulta))
	{	
		$a=$fila["nombre"];
		$b=$fila["id"];
		echo "<input type='radio' name='autocomp' value='$b' onClick=\"autocompletar_congnatario(this.value)\" >$a<br />";
	}
}
?>
 