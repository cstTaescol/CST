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
	$impresion="";

	//Consulta 
    $sql="SELECT piezas_inconsistencia, peso_inconsistencia  FROM guia WHERE id = '$id_guia'";
    $consulta=mysql_query($sql,$conexion) or die (exit('Error 1'.mysql_error()));
    $fila=mysql_fetch_array($consulta);
    $piezas=$fila['piezas_inconsistencia'];
    $peso=$fila['peso_inconsistencia'];

    if (($piezas != NULL) AND $peso != NULL)
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