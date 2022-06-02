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
    $sql="SELECT id FROM courier_funcionarios_guia WHERE id_guia = '$id_guia' AND tipo = 'C'";    
    $consulta=mysql_query($sql,$conexion) or die (exit('Error 1 '.mysql_error()));
    $nfilas=mysql_num_rows($consulta);
    if ($nfilas > 0)
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