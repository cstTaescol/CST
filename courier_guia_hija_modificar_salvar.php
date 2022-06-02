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
	if(isset($_POST["destino"]))
		$funcionariosDian=$_POST["destino"];
	else
		$funcionariosDian="";
	if(isset($_POST["no_guia"]))$hija_N=strtoupper($_POST["no_guia"]);
	if(isset($_POST["piezas"]))$piezas_N=$_POST["piezas"];
	if(isset($_POST["peso"]))$peso_N=$_POST["peso"];
	if(isset($_POST["id_tipo_actuacion_aduanera"]))$id_tipo_actuacion_aduanera_N=$_POST["id_tipo_actuacion_aduanera"];
	if(isset($_POST["courier_docAprehension"]))$courier_docAprehension_N=$_POST["courier_docAprehension"];	
	if(isset($_POST["courier_id_posicion"]))$courier_id_posicion_N=$_POST["courier_id_posicion"];	
	if(isset($_POST["id_guia"]))$id_guia=$_POST["id_guia"];
	if(isset($_POST["entidad"]))$courier_id_entidad_N=$_POST["entidad"];
	

	//Consulta datos existentes
	$sql_query="SELECT id,hija,id_tipo_actuacion_aduanera,courier_docAprehension,piezas,peso,courier_id_posicion,courier_id_entidad FROM guia WHERE id ='$id_guia'";
	$consulta=mysql_query($sql_query,$conexion) or die ("Error 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$hija=$fila['hija'];
	$id_tipo_actuacion_aduanera=$fila['id_tipo_actuacion_aduanera'];
	$courier_docAprehension=$fila['courier_docAprehension'];
	$piezas=$fila['piezas'];
	$peso=$fila['peso'];  
	$courier_id_posicion=$fila['courier_id_posicion'];  
	$courier_id_entidad=$fila['courier_id_entidad'];  

	$sql_fun="SELECT id_funcionario FROM courier_funcionarios_guia_hija WHERE id_guia='$id_guia'";
	$consulta_fun=mysql_query($sql_fun,$conexion) or die ("Error 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila_fun=mysql_fetch_array($consulta_fun))
	{
		$id_funcionario[]=$fila_fun['id_funcionario'];	
	}
	
	
	if($courier_id_entidad_N != $courier_id_entidad)
	{
		$sql_querry="UPDATE guia SET courier_id_entidad='$courier_id_entidad_N' WHERE id='$id_guia'";
		mysql_query($sql_querry,$conexion) or die ("Error 05: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

        //consulta del nombre del dato para mostrar en historial        
        $sql="SELECT nombre FROM courier_entidades WHERE id ='$courier_id_entidad'";
        $consulta=mysql_query ($sql,$conexion) or die ("Error 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
        $fila=mysql_fetch_array($consulta);
        $datoexistente=$fila['nombre'];

        $sql="SELECT nombre FROM courier_entidades WHERE id ='$courier_id_entidad_N'";
        $consulta=mysql_query ($sql,$conexion) or die ("Error 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
        $fila=mysql_fetch_array($consulta);
        $datonuevo=$fila['nombre'];

		$sql_querry="INSERT INTO tracking (id_guia,
						  evento,
						  fecha_creacion,
						  hora,
						  tipo_tracking,
						  id_usuario) 
							VALUE ('$id_guia',
								  	'Guia Modificada:<br>De:Entidad= $datoexistente<br>A:$datonuevo',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql_querry,$conexion) or die (exit('Error 6'.mysql_error()));			
	}

	/*
		La variable tiene el nombre de funcionariosDian, pero por solicitud de actualización 2020-07, ahora no se restringe a 
		funcionariosDian únicamente, sino para funcionarios de todas las entidades. Se conserva el mismo nombre de campo pero se aplica 
		cambio a la funcionalidad 
	*/
	if($funcionariosDian != "")
	{
		//Eliminacion de funcionarios asociados a la guia		
		$sql_querry="DELETE FROM courier_funcionarios_guia_hija WHERE id_guia='$id_guia'";
		mysql_query($sql_querry,$conexion) or die ("Error 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

		//Recorrido por el arreglo de los usuarios seleccionados para adicionar en la guia
		for ($i=0;$i<count($funcionariosDian);$i++) 
		{ 
			//1. Consulta de los datos del funcionario a Adicionar
			$id_funcionario_N =$funcionariosDian[$i];
			$sql="SELECT nombre FROM courier_funcionario WHERE id='$id_funcionario_N'";
			$consulta=mysql_query($sql,$conexion) or die (exit('Error 4'.mysql_error()));
			$fila=mysql_fetch_array($consulta);
			$nombreFuncionario=$fila["nombre"];
			//2. Adicion del funcionario
			$sql_querry="INSERT INTO courier_funcionarios_guia_hija (id_guia,id_funcionario) VALUE ('$id_guia','$id_funcionario_N')";
			//3. Adicion del historial de guia
			mysql_query($sql_querry,$conexion) or die ("Error 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$sql_querry="INSERT INTO tracking (id_guia,
							  evento,
							  fecha_creacion,
							  hora,
							  tipo_tracking,
							  id_usuario) 
								VALUE ('$id_guia',
									  	'Funcionario Registrado:<br>$nombreFuncionario',
										'$fecha',
										'$hora',
										'1',
										'$usuario')";
			mysql_query($sql_querry,$conexion) or die (exit('Error 6'.mysql_error()));			
		} 
	}

	if($hija_N != $hija)
	{
		$sql_querry="UPDATE guia SET hija='$hija_N' WHERE id='$id_guia'";
		mysql_query($sql_querry,$conexion) or die ("Error 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

		$sql_querry="INSERT INTO tracking (id_guia,
						  evento,
						  fecha_creacion,
						  hora,
						  tipo_tracking,
						  id_usuario) 
							VALUE ('$id_guia',
								  	'Guia Modificada:<br>De:No Guia= $hija<br>A:$hija_N',
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
								  	'Guia Modificada:<br>De:No Piezas= $piezas<br>A:$piezas_N',
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
								  	'Guia Modificada:<br>De:No Peso= $peso<br>A:$peso_N',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql_querry,$conexion) or die (exit('Error 6'.mysql_error()));			
	}

	if($id_tipo_actuacion_aduanera_N != $id_tipo_actuacion_aduanera)
	{
		$sql_querry="UPDATE guia SET id_tipo_actuacion_aduanera='$id_tipo_actuacion_aduanera_N' WHERE id='$id_guia'";
		mysql_query($sql_querry,$conexion) or die ("Error 05: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

        //consulta del nombre del dato para mostrar en historial        
        $sql="SELECT nombre FROM tipo_actuacion_aduanera WHERE id ='$id_tipo_actuacion_aduanera'";
        $consulta=mysql_query ($sql,$conexion) or die ("Error 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
        $fila=mysql_fetch_array($consulta);
        $datoexistente=$fila['nombre'];

        $sql="SELECT nombre FROM tipo_actuacion_aduanera WHERE id ='$id_tipo_actuacion_aduanera_N'";
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
								  	'Guia Modificada:<br>De:Tipo de Actuacion Aduanera= $datoexistente<br>A:$datonuevo',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql_querry,$conexion) or die (exit('Error 6'.mysql_error()));			
	}


	if($courier_docAprehension_N != $courier_docAprehension)
	{
		$sql_querry="UPDATE guia SET courier_docAprehension='$courier_docAprehension_N' WHERE id='$id_guia'";
		mysql_query($sql_querry,$conexion) or die ("Error 05: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$sql_querry="INSERT INTO tracking (id_guia,
						  evento,
						  fecha_creacion,
						  hora,
						  tipo_tracking,
						  id_usuario) 
							VALUE ('$id_guia',
								  	'Guia Modificada:<br>De:No Actuacion Aduanera= $courier_docAprehension<br>A:$courier_docAprehension_N',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql_querry,$conexion) or die (exit('Error 6'.mysql_error()));			
	}

	if($courier_id_posicion_N != $courier_id_posicion)
	{
		$sql_querry="UPDATE guia SET courier_id_posicion='$courier_id_posicion_N' WHERE id='$id_guia'";
		mysql_query($sql_querry,$conexion) or die ("Error 05: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

        //consulta del nombre del dato para mostrar en historial        
        $sql="SELECT nombre FROM courier_posiciones WHERE id ='$courier_id_posicion'";
        $consulta=mysql_query ($sql,$conexion) or die ("Error 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
        $fila=mysql_fetch_array($consulta);
        $datoexistente=$fila['nombre'];

        $sql="SELECT nombre FROM courier_posiciones WHERE id ='$courier_id_posicion_N'";
        $consulta=mysql_query ($sql,$conexion) or die ("Error 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
        $fila=mysql_fetch_array($consulta);
        $datonuevo=$fila['nombre'];

		$sql_querry="INSERT INTO tracking (id_guia,
						  evento,
						  fecha_creacion,
						  hora,
						  tipo_tracking,
						  id_usuario) 
							VALUE ('$id_guia',
								  	'Guia Modificada:<br>De:Posicion= $datoexistente<br>A:$datonuevo',
									'$fecha',
									'$hora',
									'1',
									'$usuario')";
		mysql_query($sql_querry,$conexion) or die (exit('Error 6'.mysql_error()));			
	}

	echo "Guia Modificada Satisfactoriamente";
}
else
{
	echo "Error 0";
}	
?>
