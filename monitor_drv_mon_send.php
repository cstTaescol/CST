<?php
//1. Consulta para verificar la disponibilidad del Servidor de Correo
include("config/configuracion.php");
$respuesta=0; //error por defecto

$sql="SELECT v.*,g.hija FROM historial_correo v LEFT JOIN guia g ON g.id = v.id_guia WHERE v.estado='P'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
if ($nfilas > 0)
{
	$fila=mysql_fetch_array($consulta);
	$servidordecorreo=SERVIDORDECORREO;
	if ($servidordecorreo != "")
	{
		$mensaje=$fila['mensaje'];
		$servidordecorreo .="?destinatario=".CORREOSEGUIMIENTO.",".$fila['destinatario']."&asunto=".$fila['asunto']."&hija=".$fila['hija']."&mensaje=".$fila['mensaje']."&fecha=".$fila['fecha']."&hora=".$fila['hora']."&cliente=".CLIENTE;		
		$id_registro=$fila['id'];
		//Actualizamos el registro de envio de correo
		$sql="UPDATE historial_correo SET estado='E' WHERE id=$id_registro";
		mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$respuesta=$servidordecorreo; // Envio correcto
	}
	else
	{
		$respuesta=2; //No se ha configurado el sevidor de correo.
	}
}
else
{
	$respuesta=3; //No se existen datos para enviar por correo.
}
echo $respuesta;
?>