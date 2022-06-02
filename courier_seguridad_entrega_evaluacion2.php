<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */     						
require("config/configuracion.php");
if(isset($_SESSION["id_usuario"]))
{
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");
	$usuario=$_SESSION["id_usuario"];	
	$id_guia=isset($_GET['id_guia']) ? $_GET['id_guia'] : $_POST['id_guia'];	

	//Consulta 
    $sql="SELECT courier_id_linea, courier_dato_inicio  FROM guia WHERE id = '$id_guia'";
    $consulta=mysql_query($sql,$conexion) or die (exit('Error 1'.mysql_error()));
    $fila=mysql_fetch_array($consulta);
    $id_linea=$fila['courier_id_linea'];
    $courier_dato_inicio=$fila['courier_dato_inicio'];




    if (($id_linea != NULL) && ($courier_dato_inicio != NULL))
    {
    	echo '1';	
    }
    else
    {
    	echo '0';		
    }	
}
else
{
	echo "Error 0";
}
?>