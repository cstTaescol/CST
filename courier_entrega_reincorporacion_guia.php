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
	$courier_dato_fin = date("Y-m-d") . " " . $hora;

    //1. Insertamos datos nuevos
    $sql_update="UPDATE 
                    guia 
                SET                     
                    id_tipo_bloqueo='1' 
                WHERE 
                    id='$id_guia'";
    mysql_query($sql_update,$conexion) or die (exit('Error 1 '.mysql_error()));


    //3. Ingresar los datos del Tracking de la guia
    $sql2="INSERT INTO tracking (id_guia,
                      evento,
                      fecha_creacion,
                      hora,
                      tipo_tracking,
                      id_usuario) 
                        VALUE ('$id_guia',
                                'Guia Reincorporada al Inventario',
                                '$fecha',
                                '$hora',
                                '1',
                                '$usuario')";
    mysql_query($sql2,$conexion) or die (exit('Error 2 '.mysql_error()));

    //Retorno de datos
    echo $id_despacho;       
}
else
{
	echo "Error 0";
}
?>