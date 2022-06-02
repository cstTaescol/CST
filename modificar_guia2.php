<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$id_usuario=$_SESSION['id_usuario'];
$sql_actualizaciones="";
$sql_tracking="";
$respuesta = 1; //0 = error ,  1- ok
//datos obligatorios
$id_guia=$_POST['id_guia'];

//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
$sql="SELECT * FROM guia WHERE id=$id_guia";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
$fila=mysql_fetch_array($consulta);

//1 Actualiza la guia con los datos modificados
// evaluar datos que se modificaran.
$dato_capturado=strtoupper($_POST['hija']);
$dato_original=$fila["hija"];
if ($dato_capturado != $dato_original)
{
	$sql_actualizaciones="hija='$dato_capturado'";
	$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (mysql_error());
	$sql_tracking=$sql_tracking."CAMBIO DE No.GUIA (DE:$dato_original - A:$dato_capturado)<br>";
}

// evaluar datos que se modificaran.
$dato_capturado=$_POST['piezas'];
$dato_original=$fila["piezas"];
if ($dato_capturado != $dato_original)
{
	$sql_actualizaciones="piezas_inconsistencia='$dato_capturado', piezas='$dato_capturado'";
	$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (mysql_error());
	$sql_tracking=$sql_tracking."CAMBIO DE No.PIEZAS (DE:$dato_original - A:$dato_capturado)<br>";
}

// evaluar datos que se modificaran.
$dato_capturado=strtoupper($_POST['peso']);
$dato_original=$fila["peso"];
if ($dato_capturado != $dato_original)
{
	$sql_actualizaciones="peso_inconsistencia='$dato_capturado', peso='$dato_capturado'";
	$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (mysql_error());
	$sql_tracking=$sql_tracking."CAMBIO DE PESO (DE:$dato_original - A:$dato_capturado)<br>";
}

//datos opcionales
if (isset($_POST['volumen']))
{
	// evaluar datos que se modificaran.
	$dato_capturado=strtoupper($_POST['volumen']);
	$dato_original=$fila["volumen"];
	if ($dato_capturado != $dato_original)
	{
		$sql_actualizaciones="volumen_inconsistencia='$dato_capturado', volumen='$dato_capturado'";
		$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
		mysql_query($sql,$conexion) or die (mysql_error());
		$sql_tracking=$sql_tracking."CAMBIO DE VOLUMEN (DE:$dato_original - A:$dato_capturado)<br>";
	}
}


// evaluar datos que se modificaran.
$dato_capturado=strtoupper($_POST['descripcion']);
$dato_original=$fila["descripcion"];
if ($dato_capturado != $dato_original)
{
	$sql_actualizaciones="descripcion='$dato_capturado'";
	$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (mysql_error());
	$sql_tracking=$sql_tracking."CAMBIO DE DESCRIPCION (DE:$dato_original - A:$dato_capturado)<br>";
}


// evaluar datos que se modificaran.
$dato_capturado=strtoupper($_POST['cuarto_frio']);
$dato_original=$fila["cuarto_frio"];
if ($dato_capturado != $dato_original)
{
	$sql_actualizaciones="cuarto_frio='$dato_capturado'";
	$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (mysql_error());
	$sql_tracking=$sql_tracking."CAMBIO DE CONSULTA POR REQUIERE CUERTO FRIO (DE:$dato_original -> A:$dato_capturado)<br>";
}

// evaluar datos que se modificaran.
$dato_capturado=strtoupper($_POST['precursor']);
$dato_original=$fila["precursores"];
if ($dato_capturado != $dato_original)
{
	$sql_actualizaciones="precursores='$dato_capturado'";
	$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (mysql_error());
	$sql_tracking=$sql_tracking."CAMBIO DE CONSULTA POR PRECURSORES (DE:$dato_original -> A:$dato_capturado)<br>";
}

// evaluar datos que se modificaran.
$dato_capturado=strtoupper($_POST['asignacion_directa']);
$dato_original=$fila["asignacion_directa"];
if ($dato_capturado != $dato_original)
{
	$sql_actualizaciones="asignacion_directa='$dato_capturado'";
	$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (mysql_error());
	$sql_tracking=$sql_tracking."CAMBIO DE ASIGNACION DIRECTA A DEPOSTO (DE:$dato_original - A:$dato_capturado)<br>";
}

//datos opcionales
if (isset($_POST['observaciones']))
{	
	// evaluar datos que se modificaran.
	$dato_capturado=strtoupper($_POST['observaciones']);
	$dato_original=$fila["observaciones"];
	if ($dato_capturado != $dato_original)
	{
		$sql_actualizaciones="observaciones='$dato_capturado'";
		$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
		mysql_query($sql,$conexion) or die (mysql_error());
		$sql_tracking=$sql_tracking."CAMBIO DE OBSERVACIONES (DE:$dato_original - A:$dato_capturado)<br>";
	}
}


//datos opcionales
if (isset($_POST['flete']))
{
	// evaluar datos que se modificaran.
	$dato_capturado=strtoupper($_POST['flete']);
	$dato_original=$fila["flete"];
	if ($dato_capturado != $dato_original)
	{
		$sql_actualizaciones="flete='$dato_capturado'";
		$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
		mysql_query($sql,$conexion) or die (mysql_error());
		$sql_tracking=$sql_tracking."CAMBIO DE FLETE (DE:$dato_original - A:$dato_capturado)<br>";
	}
}	

// evaluar datos que se modificaran agregando nombre y no codigo al tracking.
$dato_capturado=strtoupper($_POST['agente_carga']);
$dato_original=$fila["id_agentedecarga"];
//echo "Capturado=$dato_capturado. ";
if ($dato_capturado != "")
{
	if ($dato_capturado != $dato_original)
	{
		//VALIDAMOS LA POSIBILIDAD DE ACTUALIZAR EL CAMPO SIN DATOS
		if ($dato_original != "")
		{
			$sql2="SELECT razon_social FROM agente_carga WHERE id=$dato_original";
			$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 1'.mysql_error()));
			$fila2=mysql_fetch_array($consulta2);
			$nombre_original=$fila2['razon_social'];
		
			//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
			$sql2="SELECT razon_social FROM agente_carga WHERE id=$dato_capturado";
			$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 2'.mysql_error()));
			$fila2=mysql_fetch_array($consulta2);
			$nombre_capturado=$fila2['razon_social'];
		}
		else
		{
			$nombre_original="SIN DATO";
		}
		
		$sql_actualizaciones="id_agentedecarga='$dato_capturado'";
		$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
		mysql_query($sql,$conexion) or die (exit('Error 2.1'.mysql_error()));
		$sql_tracking=$sql_tracking."CAMBIO DE AGENTE DE CARGA (DE:$nombre_original - A:$nombre_capturado)<br>";
	}
}
else
{
	$sql_actualizaciones="id_agentedecarga=NULL";
	$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (exit('Error 2.2'.mysql_error()));
	$sql_tracking=$sql_tracking."CAMBIO DE AGENTE DE CARGA (A:SIN DATO)<br>";
}
// evaluar datos que se modificaran agregando nombre y no codigo al tracking.
$dato_capturado=strtoupper($_POST['id_tipo_carga']);
$dato_original=$fila["id_tipo_carga"];
if ($dato_capturado != $dato_original)
{
	//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
	$sql2="SELECT nombre FROM tipo_carga WHERE id=$dato_original";
	$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 3'.mysql_error()));
	$fila2=mysql_fetch_array($consulta2);
	$nombre_original=$fila2['nombre'];

	//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
	$sql2="SELECT nombre FROM tipo_carga WHERE id=$dato_capturado";
	$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 4'.mysql_error()));
	$fila2=mysql_fetch_array($consulta2);
	$nombre_capturado=$fila2['nombre'];

	$sql_actualizaciones="	id_tipo_carga='$dato_capturado'";
	$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (mysql_error());
	$sql_tracking=$sql_tracking."CAMBIO DE TIPO DE CARGA (DE:$nombre_original - A:$nombre_capturado)<br>";
}

// evaluar datos que se modificaran agregando nombre y no codigo al tracking.
$dato_capturado=strtoupper($_POST['embarcador']);
$dato_original=$fila["id_embarcador"];
if ($dato_capturado != $dato_original)
{
	//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
	$sql2="SELECT nombre FROM embarcador WHERE id=$dato_original";
	$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 5'.mysql_error()));
	$fila2=mysql_fetch_array($consulta2);
	$nombre_original=$fila2['nombre'];

	//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
	$sql2="SELECT nombre FROM embarcador WHERE id=$dato_capturado";
	$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 6'.mysql_error()));
	$fila2=mysql_fetch_array($consulta2);
	$nombre_capturado=$fila2['nombre'];

	$sql_actualizaciones="id_embarcador='$dato_capturado'";
	$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (mysql_error());
	$sql_tracking=$sql_tracking."CAMBIO DE EMBARCADOR (DE:$nombre_original - A:$nombre_capturado)<br>";
}

// evaluar datos que se modificaran agregando nombre y no codigo al tracking.
$dato_capturado=$_POST['consignatario'];
$dato_original=$fila["id_consignatario"];
if ($dato_capturado != $dato_original)
{
	//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
	$sql2="SELECT nombre FROM consignatario WHERE id=$dato_original";
	$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 7'.mysql_error()));
	$fila2=mysql_fetch_array($consulta2);
	$nombre_original=$fila2['nombre'];

	//CARGA LOS DATOS CAPTURADOS
	$sql2="SELECT nombre FROM consignatario WHERE id=$dato_capturado";
	$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 8'.mysql_error()));
	$fila2=mysql_fetch_array($consulta2);
	$nombre_capturado=$fila2['nombre'];

	$sql_actualizaciones="id_consignatario='$dato_capturado'";
	$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (mysql_error());
	$sql_tracking=$sql_tracking."CAMBIO DE CONSIGNATARIO (DE:$nombre_original - A:$nombre_capturado)<br>";
}

//evaluacion de Cambio de Destino 
if (isset($_POST['ck_nuevodeposito']))
{
	// evaluar datos que se modificaran agregando nombre y no codigo al tracking.
	$dato_capturado=$_POST['cod_disposicion'];
	$dato_original=$fila["id_disposicion"];
	if ($dato_capturado != $dato_original)
	{
		//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
		$sql2="SELECT nombre FROM disposicion_cargue WHERE id=$dato_original";
		$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 9'.mysql_error()));
		$fila2=mysql_fetch_array($consulta2);
		$nombre_original=$fila2['nombre'];
		
		//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
		$sql2="SELECT nombre FROM disposicion_cargue WHERE id=$dato_capturado";
		$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 10'.mysql_error()));
		$fila2=mysql_fetch_array($consulta2);
		$nombre_capturado=$fila2['nombre'];
	
		$sql_actualizaciones="id_disposicion='$dato_capturado'";
		$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
		mysql_query($sql,$conexion) or die (mysql_error());
		$sql_tracking=$sql_tracking."CAMBIO DE DISPOSICION (DE:$nombre_original - A:$nombre_capturado)<br>";
	}
	
	$id_deposito_original=$fila["id_deposito"];
	if ($id_deposito_original != "")
	{
		//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
		$sql2="SELECT nombre FROM deposito WHERE id=$id_deposito_original";
		$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 11'.mysql_error()));
		$fila2=mysql_fetch_array($consulta2);
		$nombre_original=$fila2['nombre'];
	}
	else
		$nombre_original="";
	
	switch ($dato_capturado)
	{
		//Cambio de Aduana y datos obligados
		case 12:
			$id_administracion_aduana=$_POST['id_nueva_aduana'];		
			$cod_departamento_destino_nuevo=$_POST['cod_departamento_destino'];
			$cod_ciudad_destino_nuevo=$_POST['cod_ciudad_destino'];
			$id_deposito_nuevo=$_POST['cod_deposito'];			
			
			//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
			$sql2="SELECT nombre FROM deposito WHERE id='$id_deposito_nuevo'";
			$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 12'.mysql_error()));
			$fila2=mysql_fetch_array($consulta2);
			$nombre_capturado=$fila2['nombre'];
		
			$sql="UPDATE guia SET id_administracion_aduana='$id_administracion_aduana',cod_departamento_destino='$cod_departamento_destino_nuevo',cod_ciudad_destino='$cod_ciudad_destino_nuevo',id_disposicion='$dato_capturado',id_deposito='$id_deposito_nuevo' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (exit('Error al guardar deposito:'.mysql_error()));
			$sql_tracking=$sql_tracking."CAMBIO DE DEPOSITO (DE:$nombre_original - A:$nombre_capturado)<br>";
		break;
		case 16:
			$id_administracion_aduana=$_POST['id_nueva_aduana'];
			$cod_departamento_destino_nuevo=$_POST['cod_departamento_destino'];
			$cod_ciudad_destino_nuevo=$_POST['cod_ciudad_destino'];
			$id_deposito_nuevo=$_POST['cod_deposito'];			
		
			//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
			$sql2="SELECT nombre FROM deposito WHERE id='$id_deposito_nuevo'";
			$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 13'.mysql_error()));
			$fila2=mysql_fetch_array($consulta2);
			$nombre_capturado=$fila2['nombre'];
		
			$sql="UPDATE guia SET id_administracion_aduana='$id_administracion_aduana',cod_departamento_destino='$cod_departamento_destino_nuevo',cod_ciudad_destino='$cod_ciudad_destino_nuevo',id_disposicion='$dato_capturado',id_deposito='$id_deposito_nuevo' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (exit('Error al guardar deposito:'.mysql_error()));
			$sql_tracking=$sql_tracking."CAMBIO DE DEPOSITO (DE:$nombre_original - A:$nombre_capturado)<br>";
		break;
		case 17:  
			$id_administracion_aduana=$_POST['id_nueva_aduana'];
			$cod_departamento_destino_nuevo=$_POST['cod_departamento_destino'];
			$cod_ciudad_destino_nuevo=$_POST['cod_ciudad_destino'];
			$id_deposito_nuevo=$_POST['cod_deposito'];			
					
			//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
			$sql2="SELECT nombre FROM deposito WHERE id='$id_deposito_nuevo'";
			$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 14'.mysql_error()));
			$fila2=mysql_fetch_array($consulta2);
			$nombre_capturado=$fila2['nombre'];
		
			$sql="UPDATE guia SET id_administracion_aduana='$id_administracion_aduana',cod_departamento_destino='$cod_departamento_destino_nuevo',cod_ciudad_destino='$cod_ciudad_destino_nuevo',id_disposicion='$dato_capturado',id_deposito='$id_deposito_nuevo' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (exit('Error al guardar deposito:'.mysql_error()));
			$sql_tracking=$sql_tracking."CAMBIO DE DEPOSITO (DE:$nombre_original - A:$nombre_capturado)<br>";
		break;
		case 24:
			$id_administracion_aduana=$_POST['id_nueva_aduana'];
			$cod_departamento_destino_nuevo=$_POST['cod_departamento_destino'];
			$cod_ciudad_destino_nuevo=$_POST['cod_ciudad_destino'];
			$id_deposito_nuevo=$_POST['cod_deposito'];			
					
			//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
			$sql2="SELECT nombre FROM deposito WHERE id='$id_deposito_nuevo'";
			$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 15'.mysql_error()));
			$fila2=mysql_fetch_array($consulta2);
			$nombre_capturado=$fila2['nombre'];
		
			$sql="UPDATE guia SET id_administracion_aduana='$id_administracion_aduana',cod_departamento_destino='$cod_departamento_destino_nuevo',cod_ciudad_destino='$cod_ciudad_destino_nuevo',id_disposicion='$dato_capturado',id_deposito='$id_deposito_nuevo' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (exit('Error al guardar deposito:'.mysql_error()));
			$sql_tracking=$sql_tracking."CAMBIO DE DEPOSITO (DE:$nombre_original - A:$nombre_capturado)<br>";
		break;
		
		//Cambio de deposito unicamente
		case 10:  
			$id_deposito_nuevo=$_POST['cod_deposito'];			
		
			//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
			$sql2="SELECT nombre FROM deposito WHERE id='$id_deposito_nuevo'";
			$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 16'.mysql_error()));
			$fila2=mysql_fetch_array($consulta2);
			$nombre_capturado=$fila2['nombre'];
		
			$sql="UPDATE guia SET id_disposicion='$dato_capturado',id_deposito='$id_deposito_nuevo' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (exit('Error al guardar deposito:'.mysql_error()));
			$sql_tracking=$sql_tracking."CAMBIO DE DEPOSITO (DE:$nombre_original - A:$nombre_capturado)<br>";
		break;

		case 11:  
			$id_deposito_nuevo=$_POST['cod_deposito'];			
		
			//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
			$sql2="SELECT nombre FROM deposito WHERE id='$id_deposito_nuevo'";
			$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 17'.mysql_error()));
			$fila2=mysql_fetch_array($consulta2);
			$nombre_capturado=$fila2['nombre'];
		
			$sql="UPDATE guia SET id_disposicion='$dato_capturado',id_deposito='$id_deposito_nuevo' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (exit('Error al guardar deposito:'.mysql_error()));
			$sql_tracking=$sql_tracking."CAMBIO DE DEPOSITO (DE:$nombre_original - A:$nombre_capturado)<br>";
		break;

		case 18:  
			$id_deposito_nuevo=$_POST['cod_deposito'];			
		
			//CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
			$sql2="SELECT nombre FROM deposito WHERE id='$id_deposito_nuevo'";
			$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 18'.mysql_error()));
			$fila2=mysql_fetch_array($consulta2);
			$nombre_capturado=$fila2['nombre'];
		
			$sql="UPDATE guia SET id_disposicion='$dato_capturado',id_deposito='$id_deposito_nuevo' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (exit('Error al guardar deposito:'.mysql_error()));
			$sql_tracking=$sql_tracking."CAMBIO DE DEPOSITO (DE:$nombre_original - A:$nombre_capturado)<br>";
		break;
		
		// CARGA LOS DATOS ORIGINALES DE LA GUIA PARA COMPARAR LOS QUE SERÁN CAMBIADOS
		case 22:  
			$id_deposito_nuevo=$_POST['cod_deposito'];			
			$sql2="SELECT nombre FROM deposito WHERE id='$id_deposito_nuevo'";
			$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error 19'.mysql_error()));
			$fila2=mysql_fetch_array($consulta2);
			$nombre_capturado=$fila2['nombre'];
		
			$sql="UPDATE guia SET id_disposicion='$dato_capturado',id_deposito='$id_deposito_nuevo' WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (exit('Error al guardar deposito:'.mysql_error()));
			$sql_tracking=$sql_tracking."CAMBIO DE DEPOSITO (DE:$nombre_original - A:$nombre_capturado)<br>";
		break;
		
		// DISPOCICIONES QUE BORRAN DEPOSITO
		case 21:  
			$sql="UPDATE guia SET id_disposicion='$dato_capturado',id_deposito=NULL WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (exit('Error al guardar deposito:'.mysql_error()));
			$sql_tracking=$sql_tracking."CAMBIO DE DEPOSITO (DE:$nombre_original - A:NO APLICA)<br>";
		break;		

		case 28:  
			$sql="UPDATE guia SET id_disposicion='$dato_capturado',id_deposito=NULL WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (exit('Error al guardar deposito:'.mysql_error()));
			$sql_tracking=$sql_tracking."CAMBIO DE DEPOSITO (DE:$nombre_original - A:NO APLICA)<br>";
		break;

		case 20:  
			$sql="UPDATE guia SET id_disposicion='$dato_capturado',id_deposito=NULL WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (exit('Error al guardar deposito:'.mysql_error()));
			$sql_tracking=$sql_tracking."CAMBIO DE DEPOSITO (DE:$nombre_original - A:NO APLICA)<br>";
		break;	
		
		case 13:  
			$sql="UPDATE guia SET id_disposicion='$dato_capturado',id_deposito=NULL WHERE id='$id_guia'";
			mysql_query($sql,$conexion) or die (exit('Error al guardar deposito:'.mysql_error()));
			$sql_tracking=$sql_tracking."CAMBIO DE DEPOSITO (DE:$nombre_original - A:NO APLICA)<br>";
		break;		
		


		
	}	
}


// evaluar Si es modificación a una guia DESPACHADA (Solicitud Julio 2020)
$dato_original=$fila["id_tipo_bloqueo"];
if (($dato_original == 4) || ($dato_original == 6))
{
	$sql_actualizaciones="update_postdespacho=1";
	$sql="UPDATE guia SET $sql_actualizaciones WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (mysql_error());
	$sql_tracking=$sql_tracking."<strong>ADVERTENCIA:</strong>CAMBIOS EN LA GUIA POSTERIOR A SU DESPACHO<br>";
}


//2. almacenamiento del traking
$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('$id_guia','$fecha','$hora','LA GUIA $sql_tracking','1','$id_usuario')";
mysql_query($sql_trak,$conexion) or die (exit('Error 20'.mysql_error()));

echo $respuesta;
?>