<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_remesa=$_POST["id_remesa"];
$cantidad_guias=$_POST["cantidad_guias"];
$id_usuario=$_SESSION['id_usuario'];
$fecha_creacion=date("Y").date("m").date("d");
$hora_registro=date("H:i:s");

for ($i=1; $i <= $cantidad_guias; $i++)
{
	$id_guia=$_POST["id_guia$i"];
	$descripcion=strtoupper($_POST["descripcion$i"]);
	if ($descripcion=="")
		$descripcion="SIN OBSERVACIONES";

	//1. Actualizamos observaciones del cumplido en la guia
	$sql="UPDATE guia SET observaciones_cumplido='$descripcion' WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die ("Error al modificar la Guia" . mysql_error());
	
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
												   'REPORTE DE CUMPLIDO INGRESADO CON LA SIGUEINTE OBSERVACION:$descripcion',
												   '1',
												   '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die (mysql_error());
}
	
//consulta el nombre de la imagen para saber si actualiza o no la foto
if (is_uploaded_file ($_FILES['scan']['tmp_name']))
{
	$nombreDirectorio = "fotos/cumplidos/";
	$idUnico = time();
	$nombrefoto = $idUnico . "-" .$id_remesa . "-" . $_FILES['scan']['name'];
	
	//SUBE LA IMAGEN LUEGO DE CREAR LOS DATOS
	move_uploaded_file ($_FILES['scan']['tmp_name'],$nombreDirectorio . $nombrefoto);
}
else
	$nombrefoto="";
	
$sql="UPDATE remesa SET foto_cumplido='$nombrefoto', estado='C' WHERE id='$id_remesa'";
mysql_query ($sql,$conexion) or die ("Error de Actualizacion de Datos:" . mysql_error());
echo '
		<script language="javascript">
			alert("El Cumplido Fue Reportado a la Remesa No.'.$id_remesa.' Satisfactoriamente");
			document.location="base.php";
		</script>
';
?>
