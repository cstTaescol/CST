<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */     						
require("config/configuracion.php");
if(isset($_SESSION["id_usuario"]))
{
	$fecha=date("Y-m-d");
	$hora=date("H:i:s");
	$usuario=$_SESSION["id_usuario"];	
	$courier_dato_fin = $fecha . " " . $hora;
    //Recepcion de datos del Formulario
    $id_guia=isset($_GET['id_guia']) ? $_GET['id_guia'] : $_POST['id_guia'];
    $no_acta =isset($_GET['acta']) ? strtoupper($_GET['acta']) : strtoupper($_POST['acta']);
    $fecha_acta=isset($_GET['fechaActa']) ? $_GET['fechaActa'] : $_POST['fechaActa'];
    $justificacion=isset($_GET['justificacion']) ? strtoupper($_GET['justificacion']) : strtoupper($_POST['justificacion']);
    $placa_vehiculo=isset($_GET['vehiculo']) ? strtoupper($_GET['vehiculo']) : strtoupper($_POST['vehiculo']);
    $ccConductor=isset($_GET['ccConductor']) ? $_GET['ccConductor'] : $_POST['ccConductor'];
    $nombreConductor=isset($_GET['nombreConductor']) ? strtoupper($_GET['nombreConductor']) : strtoupper($_POST['nombreConductor']);
    $id_funcionario_autorizador=isset($_GET['autorizador']) ? $_GET['autorizador'] : $_POST['autorizador'];
    $id_funcionario_courier=isset($_GET['courier']) ? $_GET['courier'] : $_POST['courier'];
    $tipoActuacion=isset($_GET['id_tipo_actuacion_aduanera']) ? $_GET['id_tipo_actuacion_aduanera'] : $_POST['id_tipo_actuacion_aduanera'];
    if($tipoActuacion == "3")
    {
        $planillaEnvio=isset($_GET['planillaEnvio']) ? strtoupper($_GET['planillaEnvio']) : strtoupper($_POST['planillaEnvio']);
        $nombreDeposito=isset($_GET['deposito']) ? strtoupper($_GET['deposito']) : strtoupper($_POST['deposito']);
    }
    else
    {
        $planillaEnvio='';
        $nombreDeposito='';
    }

    //Consulta  de PIEZAS y PESO
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
                    id_tipo_bloqueo='4' 
                WHERE 
                    id='$id_guia'";
    mysql_query($sql_update,$conexion) or die (exit('Error 2 '.mysql_error()));


    //2. Crear Datos del Despacho
    $sql2="INSERT INTO courier_despacho_hija (id_guia,
                                        piezas,
                                        peso,
                                        no_acta,
                                        fecha_acta,
                                        justificacion,
                                        placa_vehiculo,
                                        ccConductor,
                                        nombreConductor,
                                        id_funcionario_autorizador,
                                        id_funcionario_courier,
                                        planillaEnvio,
                                        nombreDeposito,
                                        id_usuario,
                                        fecha,
                                        hora) 
                                            VALUE ('$id_guia',
                                                    '$piezas',
                                                    '$peso',
                                                    '$no_acta',
                                                    '$fecha_acta',
                                                    '$justificacion',
                                                    '$placa_vehiculo',
                                                    '$ccConductor',
                                                    '$nombreConductor',
                                                    '$id_funcionario_autorizador',
                                                    '$id_funcionario_courier',
                                                    '$planillaEnvio',
                                                    '$nombreDeposito',
                                                    '$usuario',
                                                    '$fecha',
                                                    '$hora')";
    mysql_query($sql2,$conexion) or die (exit('Error 3 '.mysql_error()));
    $id_despacho = mysql_insert_id($conexion); //Obtiene el id de la ultima insercion    


    //3. Ingresar los datos del Tracking de la guia
    $sql2="INSERT INTO tracking (id_guia,
                      evento,
                      fecha_creacion,
                      hora,
                      tipo_tracking,
                      id_usuario) 
                        VALUE ('$id_guia',
                                'Guia Finalizada en planilla de Entrega No. $id_despacho',
                                '$fecha',
                                '$hora',
                                '1',
                                '$usuario')";
    mysql_query($sql2,$conexion) or die (exit('Error 4 '.mysql_error()));

    //Retorno de datos
    echo $id_despacho;       
}
else
{
	echo "Error 0";
}
?>