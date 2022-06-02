<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_usuario=$_SESSION['id_usuario'];
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");

if(isset($_REQUEST["id_guia"]))
{
	$id_guia=$_REQUEST['id_guia'];
	$id_registro=$_REQUEST['id_registro'];

	//consulta el nombre de la imagen para saber si actualiza o no la foto
	if (is_uploaded_file ($_FILES['foto']['tmp_name']))
	{
		$nombreDirectorio = "fotos/cumplidos/";
		$idUnico = time();
		$nombrefoto = $idUnico . "-" . $_FILES['foto']['name'];
		
		//SUBE LA IMAGEN LUEGO DE CREAR LOS DATOS
		move_uploaded_file ($_FILES['foto']['tmp_name'],$nombreDirectorio . $nombrefoto);
	}
	else
		$nombrefoto="sinfoto.jpg";

	//1. Actualiza datos de la pre-inspeccion
	$sql="UPDATE preinspeccion SET foto='$nombrefoto' WHERE id='$id_registro'";
	mysql_query($sql,$conexion) or die (mysql_error());

	//2. almacenamiento del traking
	$sql_trak="INSERT INTO tracking (id_guia,
									 fecha_creacion,
									 hora,
									 evento,
									 tipo_tracking,
									 id_usuario) 
										VALUE ('$id_guia',
											   '$fecha',
											   '$hora',
											   'VALIDACION DE PRE-INSPECCION No.$id_registro',
											   '1',
											   '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die (mysql_error());
	
	//3. Aviso de Guardado Exitoso
	echo '
	<script>
		alert("Registro almacenado de manera Exitosa");
		document.location="consulta_preinspeccion.php";
	</script>';	
}


