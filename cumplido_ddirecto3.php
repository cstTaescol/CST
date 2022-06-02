<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_despacho=$_POST["id_despacho"];
$nombre=strtoupper($_POST["nombre"]);
$documento=$_POST["documento"];

$id_usuario=$_SESSION['id_usuario'];
$fecha_creacion=date("Y").date("m").date("d");
$hora_registro=date("H:i:s");

$sql2="SELECT id_guia,piezas,peso FROM descargue_directo WHERE id = '$id_despacho' AND estado='A'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila2=mysql_fetch_array($consulta2))
{
	$id_guia=$fila2['id_guia'];
	$piezas=$fila2['piezas'];
	$peso=$fila2['peso'];
	
	//1. Actualizamos observaciones del cumplido en la guia
	$sql="UPDATE guia SET observaciones_cumplido='CUMPLIDO GENERADO CORRECTAMENTE EL DIA $fecha_creacion A LA HORA $hora_registro' WHERE id='$id_guia'";
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
												   'REPORTE DE CUMPLIDO AL DESPACHAR:<br>
												   	PIEZAS:$piezas<br>
													PESO:$peso<br>
													DATOS DEL CLIENTE QUE RECOGE LA MERCANCIA:
													NOMBRE:$nombre.<br>
													DOCUMENTO:$documento',
												   '1',
												   '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die (mysql_error());	
}
	
//consulta el nombre de la imagen para saber si actualiza o no la foto
if (is_uploaded_file ($_FILES['foto']['tmp_name']))
{
	$nombreDirectorio = "fotos/cumplidos/";
	$idUnico = time();
	$nombrefoto = $idUnico . "-" .$id_despacho . "-" . $_FILES['foto']['name'];
	
	//SUBE LA IMAGEN LUEGO DE CREAR LOS DATOS
	move_uploaded_file ($_FILES['foto']['tmp_name'],$nombreDirectorio . $nombrefoto);
}
else
	$nombrefoto="";
	
$sql="UPDATE descargue_directo SET cumplido_nombre='$nombre', cumplido_documento='$documento', cumplido_foto='$nombrefoto' WHERE id='$id_despacho'";
mysql_query ($sql,$conexion) or die ("Error de Actualizacion de Datos");
echo '
		<script language="javascript">
			alert("El Cumplido Fue Reportado a el Despacho No.'.$id_despacho.' Satisfactoriamente");
			document.location="cumplido_ddirecto1.php";
		</script>
';
?>
