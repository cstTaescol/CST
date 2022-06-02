<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
if (isset($_REQUEST['tabla']) && isset($_REQUEST['id_registro']))
{
	$tabla=$_REQUEST["tabla"];
	$id_registro=$_REQUEST["id_registro"];
	switch($tabla)
	{
		case "consignatario":
			require("consignatario_modificar.php");
		break;		
		case "conductor":
			require("conductor_modificar.php");
		break;
		case "vehiculo":
			require("vehiculo_modificar.php");
		break;
		case "vehiculo_courier":
			require("vehiculo_courier_modificar.php");
		break;
		case "agente_carga":
			require("agente_carga_modificar.php");
		break;
		case "aerolinea":
			require("aerolinea_modificar.php");
		break;	
		case "consignatario":
			require("consignatario_modificar.php");
		break;	
		case "embarcador":
			require("embarcador_modificar.php");
		break;
		case "deposito":
			require("deposito_modificar.php");
		break;
		case "couriers":
			require("courier_modificar.php");
		break;
		case "courier_funcionario":
			require("funcionario_courier_modificar.php");
		break;
		case "courier_funcionario_entidad":
			require("funcionario_courier_modificar_entidad.php");
		break;
	}
}
else
	{
		?>
		<script language="JavaScript">
			alert("ERROR: El servidor no pudo obtener los datos.");
			document.location="base.php";
		</script>
		<?php
		exit();
	}
?>
</body>
</html>
