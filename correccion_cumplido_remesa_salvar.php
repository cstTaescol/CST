<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if (isset($_POST["id_remesa"]))
{
	$id_remesa=$_POST["id_remesa"];
	$id_usuario=$_SESSION['id_usuario'];
	$fecha_creacion=date("Y").date("m").date("d");
	$hora_registro=date("H:i:s");
	//Actualizacion de guias
	$sql_guias="SELECT id_guia FROM carga_remesa WHERE id_remesa='$id_remesa'";
	$consult_guias=mysql_query ($sql_guias,$conexion) or die ("ERROR: 1". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila_guias=mysql_fetch_array($consult_guias))
	{
		$id_guia=$fila_guias['id_guia'];
		//1. Actualizamos observaciones del cumplido en la guia
		$sql="UPDATE guia SET observaciones_cumplido='' WHERE id='$id_guia'";
		mysql_query($sql,$conexion) or die ("Error 01 al modificar la Guia" . mysql_error());
		
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
													   'CUMPLIDO CORREGIDO',
													   '1',
													   '$id_usuario')";
		mysql_query($sql_trak,$conexion) or die (mysql_error());
	}
	
	//Elimina archivo actual de cumplido
	$sql_remesa="SELECT foto_cumplido FROM remesa WHERE id='$id_remesa'";
	$consult_remesa=mysql_query ($sql_remesa,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila_remesa=mysql_fetch_array($consult_remesa);
	$cumplido_actual=$fila_remesa["foto_cumplido"];
	if(file_exists("fotos/cumplidos/$cumplido_actual"))
	{
		unlink("fotos/cumplidos/$cumplido_actual");
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
	mysql_query ($sql,$conexion) or die ("Error de Actualizacion de Datos");
	echo '<script language="javascript">
				alert("El Cumplido Fue Reportado a la Remesa No.'.$id_remesa.' Satisfactoriamente");
				document.location="base.php";
			</script>';
}
else
{
	?>
		<script language="javascript">
			alert("ERROR: El servidor no pudo obtener la informacion");
			document.location="base.php";
		</script>    
    <?php	
}

?>
