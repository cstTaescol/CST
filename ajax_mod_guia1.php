<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$disposicion=$_GET["disposicion"];
switch ($disposicion)
{
	case 10: //ingreso a DEPOSITO
		$sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' AND id !='30' AND id !='48' AND id !='54' AND id !='55'  ORDER BY nombre ASC";
	break;

	case 11: //ingreso a zona franca BOGOTA-OCCIDENTE-TOCANCIPA
		$sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' AND (id='30' OR id='54' OR id='55')  ORDER BY nombre ASC";
	break;

	case 18: //deposito franco
		$sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' AND id='21'  ORDER BY nombre ASC";
	break;

	case 21: // Entrega En Lugar De Arribo
		echo "<font size=\"-2\" color=\"red\">NOMBRE DEPOSITO: </font>No Aplica";
		exit;
	break;
	
	case 28: // Convenios Internacionales
		echo "<font size=\"-2\" color=\"red\">NOMBRE DEPOSITO: </font>No Aplica";
		exit;
	break;

}
?>
<font size="-2" color="red">TIPO DEPOSITO: </font>
<select name="tipo_deposito" id="tipo_deposito" onchange="cargardeposito(this.value,document.forms[0].cod_admon_aduana.value);">
	<option value=" ">Seleccione Uno</option>
  	<?php
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		while($fila=mysql_fetch_array($consulta))
		{
			echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
		}
    ?>
</select>