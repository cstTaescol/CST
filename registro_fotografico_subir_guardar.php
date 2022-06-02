<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if(isset($_REQUEST['id_guia']) && isset($_REQUEST['tipo']) && is_uploaded_file ($_FILES['scan']['tmp_name']))
{
	$id_guia=$_REQUEST['id_guia'];
	$tipo=$_REQUEST['tipo'];
	$id_usuario=$_SESSION['id_usuario'];
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");
	$nombreDirectorio = "fotos/mercancia/";
	$idUnico = time();
	
	if ($_FILES['scan']['size'] > FOTO_MAX_SIZE)
	{
		?>
		 <script>
			alert('ERROR: El ARCHIVO supera el tamano maximo de subida de archivos');
			window.close();
		</script>
		<?php
		exit();
	}
	else
	{
		//SUBE LA IMAGEN LUEGO DE CREAR LOS DATOS
		$nombrefoto = $idUnico . "-" .$fecha . "-" . $_FILES['scan']['name'];
		move_uploaded_file ($_FILES['scan']['tmp_name'],$nombreDirectorio . $nombrefoto);	
		/*******************/	
		include("config/redimensionar.php");
		
		$sql="INSERT INTO registro_fotografico (id_guia,nombre,seccion) VALUE ('$id_guia','$nombrefoto','$tipo')";
		mysql_query($sql,$conexion) or die (mysql_error());
		
		//2. almacenamiento del traking
		$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('$id_guia','$fecha','$hora','FOTO ALMACENADA: $nombrefoto','1','$id_usuario')";
		mysql_query($sql_trak,$conexion);
		?>
		 <script>
			alert('ARCHIVO almacenado de manera CORRECTA');
			window.close();
		</script>
		<?php	
	}
}
else
{
	?>
    <script>
		alert('Error: El servidor no pudo obtener la informacion, intentelo de nuevo');
		window.close();
	</script>
    <?php
}
?>
