<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
if (isset($_GET['id_transportador']))
{
	$id_transportador=$_GET['id_transportador'];
	require("config/configuracion.php");
	$impresion_resultado="<select onchange='pasar_vehiculo(this.value);'><option value=''>Seleccione uno</option>";
	/*/$sql="SELECT placa FROM vehiculo WHERE estado='A' AND (id_transportador = '$id_transportador' OR id_transportador = '' OR id_transportador IS NULL) ORDER BY placa ASC";*/
	$sql="SELECT placa FROM vehiculo WHERE estado='A' AND id_transportador = '$id_transportador' ORDER BY placa ASC";
	
	$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
	while($fila=mysql_fetch_array($consulta))
	{
		$placa=$fila['placa'];
		$impresion_resultado .= "<option value='$placa'>$placa</option>";
	}
	$impresion_resultado .= "</select>";
	echo $impresion_resultado;
}
else
	echo "Error: el servidor no pudo recibir los datos";
?>