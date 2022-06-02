<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
//$aduana=$_GET["aduana"];
//$tipo_deposito=$_GET["tipo_deposito"];
?>
<select name="aduana_aux" id="aduana_aux">
<?php
	$sql="SELECT id,nombre FROM admon_aduana WHERE estado='A' ORDER BY nombre";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
	}
?>
</select>
