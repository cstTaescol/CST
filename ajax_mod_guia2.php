<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$tipodeposito=$_GET['tipodeposito'];
$aduana=$_GET['aduana'];
//cargamos los depositos de acuerdo a la administracion aduanera y al tipo de deposito 
echo '<font size="-2" color="red">Nombre: </font>
	<select name="deposito" id="deposito" onChange="completar_deposito(this.value)">
	<option value="">Seleccione un Deposito</option>
	';
$sql="SELECT id,nombre FROM deposito WHERE estado='A' AND id_admon_aduana = '$aduana' AND id_tipo_deposito = '$tipodeposito' ORDER BY nombre ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
	{
		echo '<option value="'.$fila['id'].'">'.substr($fila['nombre'],0,35).'</option>';
	}
echo '</select>';
?>