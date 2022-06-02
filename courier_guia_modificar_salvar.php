<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
if(isset($_SESSION["id_usuario"]))
{
	$fecha=date("Y-m-d");
	$hora=date("H:i:s");
	$usuario=$_SESSION["id_usuario"];	
	//Captura de datos del formulario
	if(isset($_POST["master"]))$master_N=strtoupper($_POST["master"]);
	if(isset($_POST["piezas"]))$piezas_N=$_POST["piezas"];
	if(isset($_POST["peso"]))$peso_N=$_POST["peso"];
	if(isset($_POST["id_aerolinea"]))$id_aerolinea_N=$_POST["id_aerolinea"];
	if(isset($_POST["cod_1178"]))$cod_1178_N=$_POST["cod_1178"];	
	if(isset($_POST["id_courier"]))$id_courier_N=$_POST["id_courier"];
	if(isset($_POST["date"]))$courier_dato_llegada_N=$_POST["date"] . " " . $_POST["hour"] . ":" . $_POST["minute"];
	if(isset($_POST["id_guia"]))$id_guia=$_POST["id_guia"];

	//Consulta de datos actuales
	$sql_query="SELECT id,master,id_aerolinea,courier_1178,id_consignatario,piezas,peso,courier_dato_llegada FROM guia WHERE id ='$id_guia'";
	$consulta=mysql_query($sql_query,$conexion) or die ("Error 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$master=$fila['master'];
	$id_aerolinea=$fila['id_aerolinea'];
	$courier_1178=$fila['courier_1178'];
	$id_courier=$fila['id_consignatario'];
	$piezas=$fila['piezas'];
	$peso=$fila['peso'];	
	$courier_dato_llegada=explode(":", $fila['courier_dato_llegada']);
	$fecha_llegada=$courier_dato_llegada[0].":".$courier_dato_llegada[1];
	
	if($master_N != $master)
	{
		$sql_querry="UPDATE guia SET master='$master_N' WHERE id='$id_guia'";
		mysql_query($sql_querry,$conexion) or die ("Error 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

		$sql_querry="INSERT INTO tracking (id_guia,
						  evento,
						  fecha_creacion,
						  hora,
						  tipo_tracking,
						  id_usuario) 
							VALUE ('$id_guia',
								  	'Guia Modificada:<br>No Guia: $master<br>Por:$master_N',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql_querry,$conexion) or die (exit('Error 3'.mysql_error()));			
	}

	if($piezas_N != $piezas)
	{
		$sql_querry="UPDATE guia SET piezas='$piezas_N' WHERE id='$id_guia'";
		mysql_query($sql_querry,$conexion) or die ("Error 04: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

		$sql_querry="INSERT INTO tracking (id_guia,
						  evento,
						  fecha_creacion,
						  hora,
						  tipo_tracking,
						  id_usuario) 
							VALUE ('$id_guia',
								  	'Guia Modificada:<br>No Piezas: $piezas<br>Por:$piezas_N',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql_querry,$conexion) or die (exit('Error 4'.mysql_error()));			
	}

	if($peso_N != $peso)
	{
		$sql_querry="UPDATE guia SET peso='$peso_N' WHERE id='$id_guia'";
		mysql_query($sql_querry,$conexion) or die ("Error 05: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

		$sql_querry="INSERT INTO tracking (id_guia,
						  evento,
						  fecha_creacion,
						  hora,
						  tipo_tracking,
						  id_usuario) 
							VALUE ('$id_guia',
								  	'Guia Modificada:<br>No Peso: $peso<br>Por:$peso_N',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql_querry,$conexion) or die (exit('Error 6'.mysql_error()));			
	}

	if($cod_1178_N != $courier_1178)
	{
		$sql_querry="UPDATE guia SET courier_1178='$cod_1178_N' WHERE id='$id_guia'";
		mysql_query($sql_querry,$conexion) or die ("Error 05: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$sql_querry="INSERT INTO tracking (id_guia,
						  evento,
						  fecha_creacion,
						  hora,
						  tipo_tracking,
						  id_usuario) 
							VALUE ('$id_guia',
								  	'Guia Modificada:<br>Planilla 1178: $courier_1178<br>Por:$cod_1178_N',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql_querry,$conexion) or die (exit('Error 6'.mysql_error()));			
	}

	if($courier_dato_llegada_N != $fecha_llegada)
	{
		$sql_querry="UPDATE guia SET courier_dato_llegada='$courier_dato_llegada_N' WHERE id='$id_guia'";
		mysql_query($sql_querry,$conexion) or die ("Error 05: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

		$sql_querry="INSERT INTO tracking (id_guia,
						  evento,
						  fecha_creacion,
						  hora,
						  tipo_tracking,
						  id_usuario) 
							VALUE ('$id_guia',
								  	'Guia Modificada:<br>Fecha de Llegada: $fecha_llegada<br>Por:$courier_dato_llegada_N',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql_querry,$conexion) or die (exit('Error 6'.mysql_error()));			
	}

	if($id_aerolinea_N != $id_aerolinea)
	{
		$sql_querry="UPDATE guia SET id_aerolinea='$id_aerolinea_N' WHERE id='$id_guia'";
		mysql_query($sql_querry,$conexion) or die ("Error 05: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

        //consulta del nombre del dato para mostrar en historial        
        $sql="SELECT nombre FROM aerolinea WHERE id ='$id_aerolinea'";
        $consulta=mysql_query ($sql,$conexion) or die ("Error 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
        $fila=mysql_fetch_array($consulta);
        $datoexistente=$fila['nombre'];

        $sql="SELECT nombre FROM aerolinea WHERE id ='$id_aerolinea_N'";
        $consulta=mysql_query ($sql,$conexion) or die ("Error 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
        $fila=mysql_fetch_array($consulta);
        $datonuevo=$fila['nombre'];

		$sql_querry="INSERT INTO tracking (id_guia,
						  evento,
						  fecha_creacion,
						  hora,
						  tipo_tracking,
						  id_usuario) 
							VALUE ('$id_guia',
								  	'Guia Modificada:<br>Aerolinea: $datoexistente<br>Por:$datonuevo',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql_querry,$conexion) or die (exit('Error 6'.mysql_error()));			
	}


	if($id_courier_N != $id_courier)
	{
		$sql_querry="UPDATE guia SET id_consignatario='$id_courier_N' WHERE id='$id_guia'";
		mysql_query($sql_querry,$conexion) or die ("Error 05: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

        //consulta del nombre del dato para mostrar en historial        
        $sql="SELECT nombre FROM couriers WHERE id ='$id_courier'";
        $consulta=mysql_query ($sql,$conexion) or die ("Error 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
        $fila=mysql_fetch_array($consulta);
        $datoexistente=$fila['nombre'];

        $sql="SELECT nombre FROM couriers WHERE id ='$id_courier_N'";
        $consulta=mysql_query ($sql,$conexion) or die ("Error 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
        $fila=mysql_fetch_array($consulta);
        $datonuevo=$fila['nombre'];

		$sql_querry="INSERT INTO tracking (id_guia,
						  evento,
						  fecha_creacion,
						  hora,
						  tipo_tracking,
						  id_usuario) 
							VALUE ('$id_guia',
								  	'Guia Modificada:<br>Courier: $datoexistente<br>Por:$datonuevo',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql_querry,$conexion) or die (exit('Error 6'.mysql_error()));			
	}


	echo "ok";
}
else
{
	echo "Error 0";
}	
?>
