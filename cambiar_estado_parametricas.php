<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<html>
<body>
<p align="center">
	Espere un Momento, se est&aacute; procesando su informaci&oacute;n<br>
	<img src="imagenes/cargando.gif" width="20" height="21" alt="cargando">
</p>
<?php
$tabla=$_REQUEST["tabla"];
$id_registro=$_REQUEST["id_registro"];
$estado_actual=$_REQUEST["estado_actual"];
if ($estado_actual == "A")
	$estado_actual="I";
else
	$estado_actual="A";

switch ($tabla) 
{
	case 'courier_funcionario_entidad':
		mysql_query ("UPDATE courier_funcionario SET estado='$estado_actual' WHERE id='$id_registro'",$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$retorno='courier_funcionario_entidad';
	break;
	
	default:
		mysql_query ("UPDATE $tabla SET estado='$estado_actual' WHERE id='$id_registro'",$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$retorno=$tabla;
	break;
}

echo '
<script>
	alert ("EL REGISTRO FUE MODIFICADO SATISFACTORIAMENTE");
	document.location=\'listar_'.$retorno.'.php\';
</script>
';
?>
</body>
</html>