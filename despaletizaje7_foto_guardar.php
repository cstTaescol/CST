<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

if (array_key_exists('HTTP_X_FILE_NAME', $_SERVER) && array_key_exists('CONTENT_LENGTH', $_SERVER)) {
    $fileName = $_SERVER['HTTP_X_FILE_NAME'];
    $contentLength = $_SERVER['CONTENT_LENGTH'];
} else throw new Exception("Error retrieving headers");

$path = 'fotos/mercancia/';

if (!$contentLength > 0) {
    throw new Exception('No file uploaded!');
}

$nombre_archivo = time()."-". $fileName;
//1. Almacenamos el registro de la foto
$id_guia=$_SESSION["id_guia"];
unset($_SESSION["id_guia"]);
$sql="INSERT INTO registro_fotografico (id_guia,nombre,seccion) VALUE ('$id_guia','$nombre_archivo','foto_bodega')";
mysql_query($sql,$conexion) or die (mysql_error());
			
//2. almacenamiento del traking
$id_usuario=$_SESSION['id_usuario'];
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('$id_guia','$fecha','$hora','FOTO DESPALETIZAJE DE ALMACENADA','1','$id_usuario')";
			mysql_query($sql_trak,$conexion);


file_put_contents(
    $path . $nombre_archivo,
    file_get_contents("php://input")
);

chmod($path.$fileName, 0777);

?>