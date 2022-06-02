<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<html>
	<head>
    	<style>
			body{
				background-image:url(imagenes/background.png);
				background-repeat:repeat;
				margin:0;
				padding:0;
			}
			.msg{
				border-radius: 50px;
				background-color: #999999;
				position: relative;
				color:#FFF;
				text-align:center;
				font-family: Arial, Helvetica, sans-serif;
				font-size:25px;	
				font-weight:bold;
				margin-left: auto;
				width: 500px;
				height:200px;
				margin-right: auto;
				top: 30%;
			}
		</style>
    </head>
    <body>
<?php
if (isset($_POST["tipo"]))
	{
		$aerolinea=$_SESSION["aerolinea"];
		$id_usuario=$_SESSION['id_usuario'];
		$fecha_creacion=date("Y").date("m").date("d");
		$hora_registro=date("H:i:s");
		$id_tipo_bloqueo=1; 
		$evalVencimiento1="";
		$evalVencimiento2="";
		$msgHistorial='CREACION DE GUIA';				
		
		//Revision de Origen y tipo de bloqueo de guia			
		if (isset($_SESSION["id_vuelo"]))
		{
			//Si contiene unicamente un id_vuelo, indica que proviene de una adicion de guias en las inconsistencias, de lo contrario, es una guia nueva
			$id_vuelo=$_SESSION["id_vuelo"];
			$url_regreso="vuelo_manifiesto_seleccionar.php?vuelo=$id_vuelo";
			unset($_SESSION["id_vuelo"]);

			//En el caso de ser corrección de Vuelo con adicion de Guia
			if (isset($_SESSION["addGuia"]))
			{
				$url_regreso="inventario_general.php";
				unset($_SESSION["addGuia"]);
				$id_tipo_bloqueo=3;
				$fecha_finalizacion=date("Y-m-d");
				$limiteportipo=2; //Todas las guias NOMALES vencen en 2 días habiles. Las sobrantes vencen en 5 dias.
				include("config/calculador_vencimientos.php");
				//*******************************************************************************************
				$evalVencimiento1="fecha_vencimiento,";
				$evalVencimiento2="'$fecha_vencimiento',";
				$msgHistorial='CREACION DE GUIA DESDE LA CORRECCION DE VUELO';				
			}
		}
		else
		{
			$id_vuelo="";
			$url_regreso="guia_registro.php";
		}
		//*********************************

		switch ($_POST["tipo"])
		{
			case (1): //cuando sea una guia directa
				$guia=strtoupper($_POST["guia"]);
				$fecha_corte=explode("-",$_POST["fecha_corte"]);
				$fecha_corte=$fecha_corte[0].$fecha_corte[1].$fecha_corte[2];
				if ($fecha_corte > $fecha_creacion)
					{
						echo '<div class="msg">
								<br />Error: Fecha de Corte <font color="blue">('.$_POST["fecha_corte"].')</font> no puede ser superior a la fecha actual...Espere
							</div>';
						echo '<meta http-equiv="Refresh" content="3;url=guia_registro.php">';
						exit();
					}
				$id_administracion_aduana=$_POST["admon_aduana"];
				$id_disposicion=$_POST["disposicion"];
				$embarcador=$_POST["cod_embarcador"];
				$consignatario=$_POST["cod_consignatario"];
				if ($id_disposicion==28 or $id_disposicion==21 or $id_disposicion==20 or $id_disposicion==19 or $id_disposicion==25 or $id_disposicion==29 or $id_disposicion==23 or $id_disposicion==13 or $id_disposicion==15)
					{
						$id_deposito=0;
					}
					else
						{
							$id_deposito=$_POST["cod_deposito"];
						}
				$piezas=$_POST["piezas"];
				$peso=$_POST["peso"];
				$volumen=$_POST["volumen"];
				if ($volumen=="")
					$volumen=$peso;
				$observaciones=strtoupper($_POST["observaciones"]);
				$descripcion=strtoupper($_POST["descripcion"]);
				//Caracteres especiales
				$texto=$descripcion;
				include("config/caracteres_especiales.php");
				$descripcion=$texto;
				//**********************				
				//Caracteres especiales
				$texto=$observaciones;
				include("config/caracteres_especiales.php");
				$observaciones=$texto;
				//**********************

				$flete=$_POST["flete"];
				if ($flete=="")
					$flete=0;
				$fecha_corte=$_POST["fecha_corte"];
				
				$agente_carga=$_POST["agente_carga"];
				if ($agente_carga=="")
					$agente_carga=0;
									
				$cod_departamento_destino=$_POST["cod_departamento_destino"];
				$cod_ciudad_destino=$_POST["cod_ciudad_destino"];
				$precursor=$_POST["precursor"];
				$tipo_carga=$_POST["tipo_carga"];
				$asignacion_directa=$_POST["asignacion_directa"];
				$cuarto_frio=$_POST["cuarto_frio"];
				
				//1. almacenamiento de los datos en la tabla de guia
				$sql="INSERT INTO guia (
										id_tipo_guia,
										id_aerolinea,
										hija,
										id_embarcador,
										id_consignatario,
										piezas,
										peso,
										volumen,
										descripcion,
										flete,
										observaciones,
										id_tipo_bloqueo,
										fecha_corte,
										fecha_creacion,
										hora,
										id_usuario,
										id_deposito,
										id_disposicion,
										id_vuelo,
										id_administracion_aduana,
										id_tipo_carga,
										cod_departamento_destino,
										cod_ciudad_destino,
										precursores,
										id_agentedecarga,
										asignacion_directa,
										$evalVencimiento1
										cuarto_frio) 
										value (
										'1',
										'$aerolinea',
										'$guia',
										'$embarcador',
										'$consignatario',
										'$piezas',
										'$peso',
										'$volumen',
										'$descripcion',
										'$flete',
										'$observaciones',
										'$id_tipo_bloqueo',
										'$fecha_corte',
										'$fecha_creacion',
										'$hora_registro',
										'$id_usuario',
										'$id_deposito',
										'$id_disposicion',
										'$id_vuelo',
										'$id_administracion_aduana',
										'$tipo_carga',
										'$cod_departamento_destino',
										'$cod_ciudad_destino',
										'$precursor',
										'$agente_carga',
										'$asignacion_directa',
										$evalVencimiento2
										'$cuarto_frio')";
				mysql_query($sql,$conexion) or die (mysql_error());
				$id_guia = mysql_insert_id($conexion); //obtiene el id de la ultima inserción
				
				//2. almacenamiento del traking
				$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('$id_guia','$fecha_creacion','$hora_registro','$msgHistorial','1','$id_usuario')";
				mysql_query($sql_trak,$conexion) or die (mysql_error());
	
				//3. Aviso de Guardado Exitoso
				echo '<div class="msg">
						<br>
						Guia '. $guia.' Guardada
					  </div>';
			break;
			
			case (2): //cuando sea un consolidado
				$guia=strtoupper($_POST["guia"]);
				$consignatario=$_POST["cod_consignatario"];
				$embarcador=$_POST["cod_embarcador"];
				$observaciones=strtoupper($_POST["observaciones"]);
				$descripcion=strtoupper($_POST["descripcion"]);
				//Caracteres especiales
				$texto=$descripcion;
				include("config/caracteres_especiales.php");
				$descripcion=$texto;
				//**********************				
				//Caracteres especiales
				$texto=$observaciones;
				include("config/caracteres_especiales.php");
				$observaciones=$texto;
				//**********************
				
				//1. almacenamiento de los datos en la tabla de guia
				$sql="INSERT INTO guia (id_tipo_guia,
										id_aerolinea,
										master,
										id_consignatario,
										id_embarcador,
										descripcion,
										observaciones,
										fecha_creacion,
										hora,
										id_usuario,
										id_vuelo,
										id_tipo_bloqueo) 
										value ('2',
											   '$aerolinea',
											   '$guia',
											   '$consignatario',
											   '$embarcador',
											   '$descripcion',
											   '$observaciones',
											   '$fecha_creacion',
											   '$hora_registro',
											   '$id_usuario',
											   '$id_vuelo',
											   '1')";
				mysql_query($sql,$conexion) or die (mysql_error());
				$id_guia = mysql_insert_id($conexion); //obtiene el id de la ultima inserción
	
				//2. almacenamiento del traking
				$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('$id_guia','$fecha_creacion','$hora_registro','$msgHistorial','1','$id_usuario')";
				mysql_query($sql_trak,$conexion) or die (mysql_error());

				//3. Aviso de Guardado Exitoso
				echo '<div class="msg">
						<br>
						Guia '. $guia.' Guardada
					  </div>';
			break;
			
			case (3): //cuando sea una guia hija
				$master=$_POST["master"];
				$guia=strtoupper($_POST["guia"]);
				$fecha_corte=explode("-",$_POST["fecha_corte"]);
				$fecha_corte=$fecha_corte[0].$fecha_corte[1].$fecha_corte[2];
				if ($fecha_corte > $fecha_creacion)
					{
						echo '<div class="msg">
								<br />Error: Fecha de Corte <font color="blue">('.$_POST["fecha_corte"].')</font> no puede ser superior a la fecha actual...Espere
							</div>';
						echo '<meta http-equiv="Refresh" content="3;url=guia_registro.php">';
						exit();
					}
	
				$id_administracion_aduana=$_POST["admon_aduana"];
				$id_disposicion=$_POST["cod_disposicion"];
				$embarcador=$_POST["cod_embarcador"];
				$consignatario=$_POST["cod_consignatario"];
				if ($id_disposicion==28 or $id_disposicion==21 or $id_disposicion==20 or $id_disposicion==19 or $id_disposicion==25 or $id_disposicion==29 or $id_disposicion==23 or $id_disposicion==13 or $id_disposicion==15)
					{
						$id_deposito=0;
					}
					else
						{
							$id_deposito=$_POST["cod_deposito"];
						}
				$piezas=$_POST["piezas"];
				$peso=$_POST["peso"];
				$volumen=$_POST["volumen"];
				if ($volumen=="")
					$volumen=$peso;
				$observaciones=strtoupper($_POST["observaciones"]);
				$descripcion=strtoupper($_POST["descripcion"]);
				//Caracteres especiales
				$texto=$descripcion;
				include("config/caracteres_especiales.php");
				$descripcion=$texto;
				//**********************				
				//Caracteres especiales
				$texto=$observaciones;
				include("config/caracteres_especiales.php");
				$observaciones=$texto;
				//**********************
				$flete=$_POST["flete"];
				if ($flete=="")
					$flete=0;
				$agente_carga=$_POST["agente_carga"];
				if ($agente_carga=="")
					$agente_carga=0;
	
				$fecha_corte=$_POST["fecha_corte"];
				$cod_departamento_destino=$_POST["cod_departamento_destino"];
				$cod_ciudad_destino=$_POST["cod_ciudad_destino"];
				$precursor=$_POST["precursor"];
				$tipo_carga=$_POST["tipo_carga"];
				$asignacion_directa=$_POST["asignacion_directa"];
				$cuarto_frio=$_POST["cuarto_frio"];
				
				//1. almacenamiento de los datos en la tabla de guia
				$sql="INSERT INTO guia (
										id_tipo_guia,
										id_aerolinea,
										master,
										hija,
										id_embarcador,
										id_consignatario,
										piezas,
										peso,
										volumen,
										descripcion,
										flete,
										observaciones,
										id_tipo_bloqueo,
										fecha_corte,
										fecha_creacion,
										hora,
										id_usuario,
										id_deposito,
										id_disposicion,
										id_vuelo,
										id_administracion_aduana,
										id_tipo_carga,
										cod_departamento_destino,
										cod_ciudad_destino,
										precursores,
										id_agentedecarga,
										asignacion_directa,
										$evalVencimiento1
										cuarto_frio) 
										value (
										'3',
										'$aerolinea',
										'$master',
										'$guia',
										'$embarcador',
										'$consignatario',
										'$piezas',
										'$peso',
										'$volumen',
										'$descripcion',
										'$flete',
										'$observaciones',
										'$id_tipo_bloqueo',
										'$fecha_corte',
										'$fecha_creacion',
										'$hora_registro',
										'$id_usuario',
										'$id_deposito',
										'$id_disposicion',
										'$id_vuelo',
										'$id_administracion_aduana',
										'$tipo_carga',
										'$cod_departamento_destino',
										'$cod_ciudad_destino',
										'$precursor',
										'$agente_carga',
										'$asignacion_directa',
										$evalVencimiento2
										'$cuarto_frio')";
				mysql_query($sql,$conexion) or die ("ERROR" . mysql_error());
				$id_guia = mysql_insert_id($conexion); //obtiene el id de la ultima inserción
				
				//2. almacenamiento del traking
				$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('$id_guia','$fecha_creacion','$hora_registro','$msgHistorial','1','$id_usuario')";
				mysql_query($sql_trak,$conexion) or die (mysql_error());
	
				//3. Aviso de Guardado Exitoso
				echo '<div class="msg">
						<br>
						Guia '. $guia.' Guardada
					  </div>';
			break;

			case (4): //cuando sea correo
				$id_disposicion=$_POST["disposicion"];
				$guia=strtoupper($_POST["guia"]);
				$fecha_corte=explode("-",$_POST["fecha_corte"]);
				$fecha_corte=$fecha_corte[0].$fecha_corte[1].$fecha_corte[2];
				if ($fecha_corte > $fecha_creacion)
					{
						echo '<div class="msg">
								<br />Error: Fecha de Corte <font color="blue">('.$_POST["fecha_corte"].')</font> no puede ser superior a la fecha actual...Espere
							</div>';
						echo '<meta http-equiv="Refresh" content="3;url=guia_registro.php">';
						exit();
					}
					
				$embarcador=$_POST["cod_embarcador"];
				$consignatario=$_POST["cod_consignatario"];
				$observaciones=strtoupper($_POST["observaciones"]);
				$descripcion=strtoupper($_POST["descripcion"]);
				//Caracteres especiales
				$texto=$descripcion;
				include("config/caracteres_especiales.php");
				$descripcion=$texto;
				//**********************				
				//Caracteres especiales
				$texto=$observaciones;
				include("config/caracteres_especiales.php");
				$observaciones=$texto;
				//**********************
				$piezas=$_POST["piezas"];
				$peso=$_POST["peso"];
				$volumen=$_POST["volumen"];
				if ($volumen=="")
					$volumen=$peso;
				$flete=$_POST["flete"];
				if ($flete=="")
					$flete=0;
				$fecha_corte=$_POST["fecha_corte"];
				
				$tipo_carga=$_POST["tipo_carga"];
				$precursor=$_POST["precursor"];
				$agente_carga=$_POST["agente_carga"];
				if ($agente_carga=="")
					$agente_carga=0;
				$cuarto_frio=$_POST["cuarto_frio"];
				
				//1. almacenamiento de los datos en la tabla de guia
				$sql="INSERT INTO guia (
										id_tipo_guia,									
										id_aerolinea,
										hija,
										id_embarcador,
										id_consignatario,
										descripcion,
										observaciones,
										piezas,
										peso,
										volumen,
										flete,
										fecha_corte,
										fecha_creacion,
										hora,
										id_usuario,
										id_tipo_bloqueo,
										id_vuelo,
										id_disposicion,
										id_tipo_carga,
										precursores,
										id_agentedecarga,
										id_administracion_aduana,
										cod_departamento_destino,
										cod_ciudad_destino,
										$evalVencimiento1
										cuarto_frio
										)
										value 
										(
										'4',
										'$aerolinea',
										'$guia',
										'$embarcador',
										'$consignatario',
										'$descripcion',
										'$observaciones',
										'$piezas',
										'$peso',
										'$volumen',
										'$flete',
										'$fecha_corte',
										'$fecha_creacion',
										'$hora_registro',
										'$id_usuario',
										'$id_tipo_bloqueo',
										'$id_vuelo',
										'$id_disposicion',
										'$tipo_carga',
										'$precursor',
										'$agente_carga',
										'3',
										'11',
										'001',
										$evalVencimiento2
										'$cuarto_frio')";

				mysql_query($sql,$conexion) or die (mysql_error()."err");
				$id_guia = mysql_insert_id($conexion); //obtiene el id de la ultima inserción
	
				//2. almacenamiento del traking
				$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('$id_guia','$fecha_creacion','$hora_registro','$msgHistorial','1','$id_usuario')";
				mysql_query($sql_trak,$conexion) or die (mysql_error());

				//3. Aviso de Guardado Exitoso
				echo '<div class="msg">
						<br>
						Guia '. $guia.' Guardada
					  </div>';
			break;

			default:
				echo '<div class="msg">NO se ha almacenado ningun dato</div>';
			break;
		}
		echo "<meta http-equiv=\"Refresh\" content=\"1;url=$url_regreso\">";
	}
?>