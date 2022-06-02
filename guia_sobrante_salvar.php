<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");


if (isset($_POST["id_guia"]))
{
	$id_guia=$_POST["id_guia"];
	$hija=$_POST["hija"];
	$id_usuario=$_SESSION['id_usuario'];
	$fecha_creacion=date("Y").date("m").date("d");
	$hora_registro=date("H:i:s");
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");

	$fecha_corte=explode("-",$_POST["fecha_corte"]);
	$fecha_corte=$fecha_corte[0].$fecha_corte[1].$fecha_corte[2];
	if ($fecha_corte > $fecha_creacion)
		{
			echo "<strong><font color='red'>Error:</strong>Fecha de Corte <font color='blue'>(".$_POST["fecha_corte"].")</font> no puede ser superior a la fecha actual...Espere</font>";
			echo '<meta http-equiv="Refresh" content="3;url=guia_sobrante.php?id_guia='.$id_guia.'">';
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

	$asignacion_directa=$_POST["asignacion_directa"];
	$tipo_carga=$_POST["tipo_carga"];
	$precursor=$_POST["precursor"];
	$cod_ciudad_destino=$_POST["cod_ciudad_destino"];
	$cod_departamento_destino=$_POST["cod_departamento_destino"];
//	$pais_origen=$_POST["pais_origen"];
//	$cod_ciudad_embarcadora=$_POST["cod_ciudad_embarcadora"];

	
	$fecha_finalizacion=$_POST["fecha_finalizacion"];
	//------------------------------------------------------------------------------------------------------		
	//                     VENCIMIENTOS
	$limiteportipo=2; //Todas las guias NOMALES vencen en 2 días habiles. Las sobrantes vencen en 5 dias.
	include("config/calculador_vencimientos.php");
	//*******************************************************************************************

	//1. almacenamiento de los datos en la tabla de guia
	$sql="UPDATE guia SET id_embarcador='$embarcador',
							id_consignatario='$consignatario',
							id_tipo_bloqueo='3',
							descripcion='$descripcion',
							flete='$flete',
							observaciones='$observaciones',
							fecha_corte='$fecha_corte',
							id_deposito='$id_deposito',
							id_disposicion='$id_disposicion',
							id_administracion_aduana='$id_administracion_aduana',
							id_agentedecarga='$agente_carga',
							asignacion_directa='$asignacion_directa',
							id_tipo_carga='$tipo_carga',
							precursores='$precursor',
							cod_ciudad_destino='$cod_ciudad_destino',
							cod_departamento_destino='$cod_departamento_destino',
							fecha_vencimiento='$fecha_vencimiento'
							WHERE id='$id_guia'"; 
	mysql_query($sql,$conexion) or die (exit("Error".mysql_error()));
	
	//2. almacenamiento del traking
	$sql_trak="INSERT INTO tracking (id_guia,
									 fecha_creacion,
									 hora,
									 evento,
									 tipo_tracking,
									 id_usuario) 
										value ('$id_guia',
											   '$fecha_creacion',
											   '$hora_registro',
											   'GUIA SOBRANTE COMPLETADA',
											   '1',
											   '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die (exit("Error".mysql_error()));

	//3 Registra evento en Correo de Salida
//	$addmensaje="ADICIONAL AL VUELO HA SIDO DOCUMENTADA";
//	include("config/mail.php");
	//***********************************

	//4. Aviso de Guardado Exitoso
	echo '<p align="center"><font color="green" size="4">Guia '. $hija.' Completada</font></p>';
	echo '<meta http-equiv="Refresh" content="1;url=inventario_general.php">';
}
?>