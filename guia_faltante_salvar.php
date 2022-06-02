<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_usuario=$_SESSION['id_usuario'];
$fecha_actual=date("Y").date("m").date("d");
$fecha_creacion=date("Y")."-".date("m")."-".date("d");
$hora_actual=date("H:i:s");

$id_guia_recuperada=$_POST['id_guia'];
$id_vuelo_recuperado=$_POST['id_vuelo'];
//***********************************
$sql2="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo_recuperado'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila2=mysql_fetch_array($consulta2);
$nvuelo=$fila2["nvuelo"];
//***********************************
$fecha_finalizacion=$_POST['fecha_finalizacion'];

$piezas_restantes=$_POST['piezas_restantes'];
$peso_restante=$_POST['peso_restante'];
$volumen_restante=$_POST['volumen_restante'];
if ($volumen_restante== "" || $volumen_restante== 0)
	$volumen_restante=$peso_restante;

$observaciones=strtoupper($_POST["observaciones"]);
$faltante_total=$_POST['faltante_total'];

//*******************************************************************************************
//                     VENCIMIENTOS
$limiteportipo=2; //Todas las guias NOMALES vencen en 2 días habiles. Las sobrantes vencen en 5 dias.
$fecha_recibida=$fecha_finalizacion;
include("config/calculador_vencimientos.php");
//*******************************************************************************************


//1. almacenamiento de los datos en la tabla de guia
if ($faltante_total=='S')
{
	$sql="UPDATE guia SET piezas_faltantes=0, peso_faltante=0, piezas_inconsistencia=NULL, peso_inconsistencia=NULL, volumen_inconsistencia=NULL, piezas='$piezas_restantes', peso='$peso_restante', volumen='$volumen_restante',faltante_total='N', id_vuelo='$id_vuelo_recuperado',id_tipo_bloqueo='3',fecha_inconsistencia='$fecha_finalizacion', fecha_vencimiento='$fecha_vencimiento'  WHERE id='$id_guia_recuperada'";	
	$msgtraking="GUIA ACTUALIZADA Y COMPLETADA POR FALTANTE TOTAL DE LA GUIA.";
}
else
{
	$id_aerolinea=$_POST['id_aerolinea'];
	$id_aduana=$_POST['id_aduana'];
	if ($id_aduana == "" || $id_aduana == 0)
		$id_aduana=0;

	$id_disposicion=$_POST['id_disposicion'];
	$hija=$_POST['hija'];
	$id_embarcador=$_POST['id_embarcador'];
	$id_consignatario=$_POST['id_consignatario'];
	
	$id_deposito=$_POST['id_deposito'];
	if ($id_deposito == "" || $id_deposito == 0)
		$id_deposito=0;	
	
	$flete=$_POST['flete'];
	$fecha_corte=$_POST['fecha_corte'];
	
	$descripcion=strtoupper($_POST["descripcion"]);
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
							fecha_vencimiento,
							hora,
							id_usuario,
							id_deposito,
							id_disposicion,
							id_vuelo,
							id_administracion_aduana) 
							value (
							'1',
							'$id_aerolinea',
							'$hija',
							'$id_embarcador',
							'$id_consignatario',
							'$piezas_restantes',
							'$peso_restante',
							'$volumen_restante',
							'$descripcion',
							'$flete',
							'$observaciones',
							'3',
							'$fecha_corte',
							'$fecha_creacion',
							'$fecha_vencimiento',
							'$hora_actual',
							'$id_usuario',
							'$id_deposito',
							'$id_disposicion',
							'$id_vuelo_recuperado',
							'$id_aduana')";
	$msgtraking="GUIA CREADA Y COMPLETADA CON BASE EN DATOS DE GUIA FALTANTE.";
}
mysql_query($sql,$conexion) or die ("Error al insertar datos de guia: " .mysql_error());
$id_guia = mysql_insert_id($conexion); //obtiene el id de la ultima inserción

//2. almacenamiento del traking
$sql_trak="INSERT INTO tracking (id_guia,
								 fecha_creacion,
								 hora,
								 evento,
								 tipo_tracking,
								 id_usuario) 
									value ('$id_guia_recuperada',
										   '$fecha_actual',
										   '$hora_actual',
										   'CREACION DE GUIA CON DATOS DE GUIA FALTANTE.<br />
										   ASIGNADA AL VUELO No. $nvuelo.<br />
										   $msgtraking',
										   '1',
										   '$id_usuario')";
mysql_query($sql_trak,$conexion) or die (mysql_error());

//3. Aviso de Guardado Exitoso
$sql="UPDATE inconsistencias SET estado='F' WHERE id_guia='$id_guia_recuperada'";
mysql_query($sql,$conexion) or die ("Error al Modificar las Inconsistencias ". mysql_error());

//4. Aviso de Guardado Exitoso
echo '<script language="javascript">
		alert("Atencion: Guia CREADA Satisfactoriamente");
		document.location="base.php";
	</script>';
?>