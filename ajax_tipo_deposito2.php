<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$disposicion=$_REQUEST['disposicion'];
switch ($disposicion)
{
	default:
		$sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' AND id !='30' AND id !='48' AND id !='54' AND id !='55'  ORDER BY nombre ASC";
	break;
	//cabotajes ***************************
	case 12: //continuacion de viaje
		$sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' AND id !='48' ORDER BY nombre ASC";
	break;

	case 16: //transito nacional
		$sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' AND id !='48' ORDER BY nombre ASC";
	break;

	case 17: //cabotaje
		$sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' AND id !='48' ORDER BY nombre ASC";
	break;
	
	case 24: //cabotaje especial
		$sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' AND id !='48' ORDER BY nombre ASC";
	break;
	//*************************************

	case 18: //deposito franco
		$sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' AND id='21'  ORDER BY nombre ASC";
	break;

	case 26: //envio correspondencia
		$sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' AND id='48'  ORDER BY nombre ASC";
	break;

	case 11: //ingreso a zona franca BOGOTA-OCCIDENTE-TOCANCIPA
		$sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' AND (id='30' OR id='54' OR id='55')  ORDER BY nombre ASC";
	break;

	case 27: //mensajeria especializada
		$sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' AND id='48'  ORDER BY nombre ASC";
	break;

	case 28: //convenio internacional
		echo '<font size="-2" color="red">Tipo Deposito:</font>No Aplica';
		exit();
	break;

	case 21: // Entrega En Lugar De Arribo
		echo '<font size="-2" color="red">Tipo Deposito:</font>No Aplica';
		exit();
	break;

	case 20: // Entrega Urgente
		echo '<font size="-2" color="red">Tipo Deposito:</font>No Aplica';
		exit();
	break;

	case 19: // Equipaje No Acompañado
		echo '<font size="-2" color="red">Tipo Deposito:</font>No Aplica';
		exit();
	break;

	case 25: // Equipaje acompañado ingresado como carga
		echo '<font size="-2" color="red">Tipo Deposito:</font>No Aplica';
		exit();
	break;

	case 29: // Sellos electrónicos reutilizables
		echo '<font size="-2" color="red">Tipo Deposito:</font>No Aplica';
		exit();
	break;

	case 23: // Transbordo De Salida
		echo '<font size="-2" color="red">Tipo Deposito:</font>No Aplica';
		exit();
	break;

	case 13: // Transbordo Directo
		echo '<font size="-2" color="red">Tipo Deposito:</font>No Aplica';
		exit();
	break;

	case 15: // Tránsito Internacional
		echo '<font size="-2" color="red">Tipo Deposito:</font>No Aplica';
		exit();
	break;
}
?>
<font size="-2" color="red">Tipo Deposito: </font>
<select name="tipo_deposito" id="tipo_deposito" onchange="evaluador(this.value)">
	<option value="" selected="selected">Seleccione Uno</option>
  	<?php
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		while($fila=mysql_fetch_array($consulta))
		{
			echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
		}
    ?>
</select>
