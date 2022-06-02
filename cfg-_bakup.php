<?php
session_start(); /*     "This product includes PHP software, freely available from */
include("config/configuracion.php");

//fijo el date de hoy
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
//Archivo
$filename = "backup_".$fecha."_".$hora.".sql";

//Datos BD
$usuario = USUARIOBD;
$passwd = CLAVEBD;
$bd = BD;

header("Pragma: no-cache");
header("Expires: 0");
header("Content-Transfer-Encoding: binary");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=$filename");

//para Unix
$executa = "mysqldump -u $usuario --password=$passwd --opt $bd";
system($executa, $resultado);
if ($resultado) { echo "<H1>Error ejecutando comando: $executa</H1>\n"; }
?>