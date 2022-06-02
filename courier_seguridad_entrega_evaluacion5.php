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
    $actuacion_aduanera=isset($_GET['actuacion_aduanera']) ? $_GET['actuacion_aduanera'] : $_POST['actuacion_aduanera'];

	$courier_dato_fin = date("Y-m-d") . " " . $hora;

    //Consulta 
    $sql="SELECT piezas,peso FROM guia WHERE id = '$id_guia'";
    $consulta=mysql_query($sql,$conexion) or die (exit('Error 1'.mysql_error()));
    $fila=mysql_fetch_array($consulta);
    $piezas=$fila['piezas'];
    $peso=$fila['peso'];

    //1. Insertamos datos nuevos
    $sql_update="UPDATE 
                    guia 
                SET                     
                    courier_dato_fin='$courier_dato_fin',
                    id_tipo_bloqueo='3',
                    courier_actuacion_aduanera = '$actuacion_aduanera'
                WHERE 
                    id='$id_guia'";
    mysql_query($sql_update,$conexion) or die (exit('Error 2 '.mysql_error()));

/*
    //2. Crear Datos del Despacho
    $sql2="INSERT INTO courier_despacho (id_guia,
                                        piezas,
                                        peso,
                                        id_usuario,
                                        fecha,
                                        hora) 
                                            VALUE ('$id_guia',
                                                    '$piezas',
                                                    '$peso',
                                                    '$usuario',
                                                    '$fecha',
                                                    '$hora')";
    mysql_query($sql2,$conexion) or die (exit('Error 4 '.mysql_error()));
    $id_despacho = mysql_insert_id($conexion); //Obtiene el id de la ultima insercion    

*/
    //3. Ingresar los datos del Tracking de la guia
    $sql2="INSERT INTO tracking (id_guia,
                      evento,
                      fecha_creacion,
                      hora,
                      tipo_tracking,
                      id_usuario) 
                        VALUE ('$id_guia',
                                'Confirmaci&oacute;n de seguridad de entrega de la gu&iacute;a',
                                '$fecha',
                                '$hora',
                                '1',
                                '$usuario')";
    mysql_query($sql2,$conexion) or die (exit('Error 3 '.mysql_error()));

    //Retorno de datos
    echo $id_despacho;       
}
else
{
	echo "Error 0";
}
?>