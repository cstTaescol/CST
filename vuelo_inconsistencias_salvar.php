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
				height:100px;
				margin-right: auto;
				top: 30%;
			}
		</style>
    </head>
    <body>
		<div id="cargando"><p align="center"><img src="imagenes/cargando.gif" width="20" height="21" /><br>Procesando</p></div>
<?php
unset($_SESSION["id_vuelo"]);
$nguias=$_SESSION["nguias"];
$id_usuario=$_SESSION['id_usuario'];
//datos del vuelo
$vuelo=$_POST["vuelo"];

$sql3="SELECT id_aerolinea,nvuelo FROM vuelo WHERE id='$vuelo'";
$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila3=mysql_fetch_array($consulta3);
$id_aerolinea=$fila3["id_aerolinea"];
$nvuelo=$fila3["nvuelo"];

$sql3="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila3=mysql_fetch_array($consulta3);
$aerolinea=$fila3["nombre"];


$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");

$fecha_inconsistencia=$_POST["fecha"];
$hora_inconsistencia=$_POST["hh"].":".$_POST["mm"].":".$_POST["ss"];

//Calcula rangos de fecha y hora frente a las registradas y del servidor
$sql3="SELECT fecha_fin_descargue,hora_fin_descargue FROM vuelo WHERE id='$vuelo'";
$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila3=mysql_fetch_array($consulta3);
$fecha_fin_descargue=explode("-",$fila3["fecha_fin_descargue"]);
$fecha_fin_descargue=$fecha_fin_descargue[0].$fecha_fin_descargue[1].$fecha_fin_descargue[2];
$fecha_registrada=explode("-",$_POST["fecha"]);
$fecha_registrada=$fecha_registrada[0].$fecha_registrada[1].$fecha_registrada[2];
$fecha_servidor=date("Y").date("m").date("d");
$hora_fin_descargue=explode(":",$fila3["hora_fin_descargue"]);
$hora_fin_descargue=$hora_fin_descargue[0].$hora_fin_descargue[1].$hora_fin_descargue[2];
$hora_registrada=$_POST["hh"].$_POST["mm"].$_POST["ss"];
$hora_servidor=date("His");
$url_retorno="vuelo_inconsistencias.php";
//Validacion de fechas frente al proceso anterior del vuelo y al servidor en horas y fechas

switch ($fecha_registrada)
{
	case ($fecha_registrada == $fecha_fin_descargue):
/*
		if ($hora_registrada > $hora_servidor)
		{
			$errores=true;
			echo "<script language=\"javascript\">
					alert ('ERROR: No puede ingresar una HORA superior a la del Servidor');
					document.location='$url_retorno?vuelo=$vuelo';
				</script>";
			exit();
		}
*/
		if ($hora_registrada < $hora_fin_descargue)
		{
			$errores=true;
			echo "<script language=\"javascript\">
					alert ('ERROR: La hora de INCONSISTENCIAS no puede ser inferior a la hora de FINALIZAR EL DESCARGUE del Vuelo');
					document.location='$url_retorno?vuelo=$vuelo';
				</script>";
			exit();
		}
		else //mismo dia, pero hora correcta
			{
				//Si supero las validaciones, se actualiza el vuelo
				$errores=false;
			}
	break;
	
	case ($fecha_registrada < $fecha_fin_descargue):
		$errores=true;
		echo "<script language=\"javascript\">
				alert ('ERROR: La FECHA ingresada no puede ser inferior a la de FINALIZAR EL DESCARGUE del Vuelo');
				document.location='$url_retorno?vuelo=$vuelo';
			</script>";
		exit();
	break;
	
	case ($fecha_registrada > $fecha_fin_descargue):
		switch($fecha_servidor)
		{
			case ($fecha_servidor == $fecha_registrada):   //Cuando el día de registro sea el dia en curso, no puede tener una hora mayor a la del servidor
				if ($hora_registrada > $hora_servidor)
					{
						$errores=true;
						echo "<script language=\"javascript\">
								alert ('ERROR: No puede ingresar una HORA superior a la del Servidor');
								document.location='$url_retorno?vuelo=$vuelo';
							</script>";
						exit();
					}
					else
					{
						//Si supero las validaciones, se actualiza el vuelo
						$errores=false;
					}
			break;
			case ($fecha_servidor < $fecha_registrada):  //La fecha del servidor no puede ser inferior a la fecha registrada
				$errores=true;
				echo "<script language=\"javascript\">
						alert ('ERROR: FECHA no puede ser superior a la fecha del servidor');
						document.location='$url_retorno?vuelo=$vuelo';
					</script>";
				exit();
			break;
			case ($fecha_servidor > $fecha_registrada):  //entrada correcta
				//Si supero las validaciones, se actualiza el vuelo
				$errores=false;
			break;
		}
	break;
}

if ($errores == false)
{
	for ($i=1; $i<=$nguias; $i++)
	{
		//Chequeamos si seleccionaron las guias para finalizarlas.
		$eventotraking="";
		$inconsistencias=false;
		$nombrechek="finalizar".$i;
		$peso="";
		$piezas="";
		if (isset($_POST["$nombrechek"]))
		{
			$finalizador=$_POST["$nombrechek"];
			if ($finalizador=="1") // si seleccionaron esta guía para finalizarla.
			{
				$nombreguia="guia".$i;	
				$id_guia=$_POST["$nombreguia"]; //recuperamos el Id de la guia a modificar
				
				//*******************************************************************************************
				//                     VENCIMIENTOS
				$fecha_finalizacion=$_POST["fecha"];
				$limiteportipo=2; //Todas las guias NOMALES vencen en 2 días habiles. Las sobrantes vencen en 5 dias.
				include("config/calculador_vencimientos.php");
				//*******************************************************************************************

				if (isset($_POST["faltante_total$i"]))
				{
					$faltante_total=true;
					$sql3="SELECT peso,piezas,volumen,hija FROM guia WHERE id='$id_guia'";
					$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR FALTANTE TOTAL: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					$fila3=mysql_fetch_array($consulta3);
					$piezas_faltantes=$fila3["piezas"];
					$peso_faltante=$fila3["peso"];
					$hija_rec=$fila3["hija"];
					$sqladd=",piezas_inconsistencia='0',peso_inconsistencia='0',volumen_inconsistencia='0',piezas_faltantes='$piezas_faltantes',peso_faltante='$peso_faltante',faltante_total='S'";
					$eventotraking="INCONSISTENCIA RECIBIDA POR FALTANTE TOTAL";
					$inconsistencias=true;					
				}
				else
				{
					$faltante_total=false;
					if (isset($_POST["piezas$i"]) && isset($_POST["peso$i"]))
					{
						$piezas_despaletizado=$_POST["piezas$i"];
						$peso_despaletizado=$_POST["peso$i"];	
						
						//Error por que pizas o peso esta en blanco
						if (($piezas_despaletizado=="") || ($piezas_despaletizado=="0") || ($peso_despaletizado=="") || ($peso_despaletizado=="0"))
						{
							echo '<script type="text/javascript">
									alert("ERROR: Piezas y Peso no pueden quedar en 0");
								</script>';
							echo '<meta http-equiv="Refresh" content="0;url=vuelo_inconsistencias.php?vuelo='.$vuelo.'">';
							exit();
						}
						else
							{
								//Validacion de la guia
								$sql3="SELECT peso,piezas,volumen,hija FROM guia WHERE id='$id_guia'";
								$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
								$fila3=mysql_fetch_array($consulta3);
								$piezas_digitado=$fila3["piezas"];
								$peso_digitado=$fila3["peso"];
								$volumen_digitado=$fila3["volumen"];
								$hija_rec=$fila3["hija"];
								
								//Aca hay que evaluar que no se descuenten mas piezas de las que se digitaron.
								$piezas_faltantes=$piezas_digitado-$piezas_despaletizado;
								$peso_faltante=$peso_digitado-$peso_despaletizado;
								$volumen_inconsistencia=number_format($piezas_despaletizado*($volumen_digitado/$piezas_digitado),2,".",""); //limita el valor proratiado a 2 decimales				
								$sqladd=",piezas_inconsistencia='$piezas_despaletizado',peso_inconsistencia='$peso_despaletizado',volumen_inconsistencia='$volumen_inconsistencia',piezas_faltantes='$piezas_faltantes',peso_faltante='$peso_faltante'";
								$eventotraking="PROCESO DE INCONSISTENCIAS FINALIZADO:<br>
												PIEZAS:$piezas_despaletizado - PESO:$peso_despaletizado - VOLUMEN:$volumen_inconsistencia<br>
												DIFERENCIAS:<br>
												PIEZAS DIFERENCIA:$piezas_faltantes - PESO DIFERENCIA:$peso_faltante<br>
												FECHA Y HORA FINALIZACION:$fecha_inconsistencia/$hora_inconsistencia
												";
								$inconsistencias=true;
							}
					}
					else
					{
						echo '<script type="text/javascript">
								alert("ERROR: No se pudieron recibir los datos, vuelva a intentarlo o contacte con el SOPROTE TECNICO.");
							</script>';
						echo '<meta http-equiv="Refresh" content="0;url=vuelo_inconsistencias.php?vuelo='.$vuelo.'">';
						exit();
					}					
				}
			}
			//1 Actualiza la guia si ha sido modificada en sus piezas o peso
			$sql="UPDATE guia SET id_tipo_bloqueo='3',
									fecha_inconsistencia='$fecha_inconsistencia',
									hora_inconsistencia='$hora_inconsistencia',
									fecha_vencimiento='$fecha_vencimiento' 
									$sqladd WHERE id='$id_guia'";	
			mysql_query($sql,$conexion) or die (mysql_error()."1");
			
			//2 Crea el tracking para el seguimiento de la guia
			$sql_trak="INSERT INTO tracking (id_guia,evento,fecha_creacion,hora,tipo_tracking,id_usuario) value ('$id_guia','$eventotraking','$fecha','$hora','2','$id_usuario')";	
			mysql_query($sql_trak,$conexion) or die (mysql_error()."2");
			
			//3 Si se generan Inconsistencias, crear el reporte de inconsistencias
			if ($inconsistencias == true)
			{
				if($piezas == "")$piezas=0;
				if($peso == "")$peso=0;
				$sql="INSERT INTO inconsistencias (id_guia,
												   id_vuelo,
												   variacion_piezas,
												   variacion_peso,
												   observaciones,
												   id_usuario,
												   fecha,
												   hora) VALUE 
															('$id_guia',
															 '$vuelo',
															 '$piezas_faltantes',
															 '$peso_faltante',
															 'REPORTE CREADO AUTOMATICAMENTE POR EL SISTEMA EN EL MOMENTO DE CREAR LAS INCONSISTENCIAS DE LA GUIA',
															 '$id_usuario',
															 '$fecha',
															 '$hora')";
				mysql_query($sql,$conexion) or die ("Error al crear la INCOSNSISTENCIA ". mysql_error());	
			}

			//4 Registra evento en Correo de Salida cuando NO sea faltante total
			if ($faltante_total==false)
			{				
				$addmensaje="RECIBIDA EN BODEGA. ->AEROLINEA:$aerolinea. ->VUELO No:$nvuelo.";
				include("config/mail.php");
				//***********************************				
			}

			//5 Finalizar el vuelo si no existen mas guias por finalizar (es decir si no hay mas guias en bloqueo 2 que es manifestadas)
			$sql="SELECT id FROM guia WHERE id_vuelo='$vuelo' AND id_tipo_bloqueo='2' AND id_tipo_guia != '2'";
			$consulta=mysql_query($sql,$conexion) or die (mysql_error()."4");
			$nfilas=mysql_num_rows($consulta);
			if ($nfilas == 0)
			{
				$sql="UPDATE vuelo SET estado ='F', id_usuario_finalizador='$id_usuario', hora_finalizado='$hora', fecha_finalizacion='$fecha' WHERE id='$vuelo'";
				mysql_query($sql,$conexion) or die (mysql_error());
				echo '<p align="center"><font size="4" color="green">Operaci&oacute;n Realizada Correctamente</font></p>';
			}
			else
				{
					echo '<p align="center"><font size="4" color="green">Operaci&oacute;n Realizada Correctamente</font></p>';				
				}
		}
		else
		{
			echo '<p align="center"><font size="4" color="purple">Datos no seleccionados</font></p>';				
		}
	}
	
	//Validacion y Registro de Guias SOBRANTES
	//------------------------------------------------------------------------------------------------------		
	//                     VENCIMIENTOS
	$limiteportipo=5; //Todas las guias NOMALES vencen en 2 días habiles. Las sobrantes vencen en 5 dias.
	include("config/calculador_vencimientos.php");
	//*******************************************************************************************

	$sql="SELECT * FROM despaletizaje_sobantes WHERE id_vuelo='$vuelo' AND estado='A'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR GUIAS SOBRANTES: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$nguias=mysql_num_rows($consulta);	
	if ($nguias > 0)
	{
		for ($i=1; $i<=$nguias; $i++)
		{
			$fila=mysql_fetch_array($consulta);
			$id_sobrante=$fila["id"];
			$sobrante_guia=$fila["guia"];
			$sobrante_piezas=$fila["piezas"];
			$sobrante_peso=$fila["peso"];
			$sobrante_volumen=$fila["volumen"];
			//1 Ingresamos datos de la guia sobrante
			$sql="INSERT INTO guia (id_tipo_guia,
									hija,
									piezas,
									peso,
									volumen,
									id_vuelo,
									fecha_creacion,
									hora,
									id_tipo_bloqueo,
									fecha_vencimiento,
									fecha_inconsistencia,
									hora_inconsistencia,
									id_aerolinea,
									id_usuario)
									value ('1',
										   '$sobrante_guia',
										   '$sobrante_piezas',
										   '$sobrante_peso',
										   '$sobrante_volumen',
										   '$vuelo',
										   '$fecha',
										   '$hora',
										   '9',
										   '$fecha_vencimiento',
										   '$fecha',
										   '$hora_inconsistencia',
										   '$id_aerolinea',
										   '$id_usuario')";
				mysql_query($sql,$conexion) or die (mysql_error() . "Error en guia nueva");
				$id_guia = mysql_insert_id($conexion); //obtiene el id de la ultima inserción
			
				//2 Crea el tracking para el seguimiento de la guia
				$sql_trak="INSERT INTO tracking (id_guia,
												 evento,
												 fecha_creacion,
												 hora,
												 tipo_tracking,
												 id_usuario) 
													value ('$id_guia',
														   'GUIA CREADA COMO SOBRANTE DEL VUELO $nvuelo',
														   '$fecha',
														   '$hora',
														   '1',
														   '$id_usuario')";	
				mysql_query($sql_trak,$conexion) or die (mysql_error()."Error en Tracking");	
				echo '<p align="center"><font size="4" color="blue">Guia sobrante '. $sobrante_guia .' CREADA satisfactoriamente</font></p>';
				
				//3 Elimina las guias 
				$sql_trak="UPDATE despaletizaje_sobantes SET estado='I' WHERE id='$id_sobrante'";	
				mysql_query($sql_trak,$conexion) or die (mysql_error()."Error al Soportar Guia Sobrante");					
		}
	}
}
echo '<meta http-equiv="Refresh" content="1;url=inventario_general.php">';
?>
	</body>
</html>