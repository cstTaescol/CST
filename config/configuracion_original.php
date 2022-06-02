<?php
define('IPSERVIDORBD','127.0.0.1');
define('PUERTO','3306');
define('USUARIOBD','taescol');
define('CLAVEBD','inventarios');
define('BD','cst');
define('DRIVER','mysql.php');
require_once(DRIVER);

$sql_cfg="SELECT * FROM configuracion WHERE id='1'";
$consulta_cfg=mysql_query ($sql_cfg,$conexion) or die (exit('Error al Obtener Configuracion '.mysql_error()));
$fila_cfg=mysql_fetch_array($consulta_cfg);

define('VERSION',$fila_cfg['VERSION']);
define('PROGRAMA',$fila_cfg['PROGRAMA']);
define('SIGLA',$fila_cfg['SIGLA']);	
define('PROGINI',$fila_cfg['PROGINI']);
define('EMPRESA',$fila_cfg['EMPRESA']);
define('AUTOR',$fila_cfg['AUTOR']);
define('CLAVE',$fila_cfg['CLAVE']);
define('CLIENTE',$fila_cfg['CLIENTE']);
define('URLCLIENTE',$fila_cfg['URLCLIENTE']);
define('SERVIDORDECORREO',$fila_cfg['SERVIDORDECORREO']);
define('URLAPLICACION',$fila_cfg['URLAPLICACION']);
define('CORREOSOPORTE',$fila_cfg['CORREOSOPORTE']);
define('CORREOSEGUIMIENTO',$fila_cfg['CORREOSEGUIMIENTO']);
define('TIEMPOSESION',$fila_cfg['TIEMPOSESION']);
define('FOTO_MAX_SIZE',$fila_cfg['FOTO_MAX_SIZE']);


date_default_timezone_set('America/Bogota');
function privilegios($identificador)
{
	//Cargamos los objetos autorizados para el usuario
	$objetos=$_SESSION['objetos']; //variable que contiene arreglo de objetos permitidos
	$nregistros=count($_SESSION['objetos']);
	for ($i=0; $i < $nregistros; $i++)
	{
		if ($objetos[$i] == $identificador)
			return true;
	}
}
if (isset($_SESSION['id_usuario']))
	$id_usuario=$_SESSION['id_usuario'];
?>
