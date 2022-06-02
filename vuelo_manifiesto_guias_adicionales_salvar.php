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
</html>
<?php
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$contador=$_POST['contador']-1;
$id_aerolinea=$_POST['aerolinea'];
$id_vuelo=$_POST['vuelo'];

$sql="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$nvuelo=$fila["nvuelo"];

for ($i=1; $i<=$contador; $i++)
{
	if (isset($_POST["chkacepto$i"]))
	{
		//1. Actualizar datos de la guia
		$id_guia=$_POST["chkacepto$i"];
		$sql="UPDATE guia SET id_tipo_bloqueo='7', id_vuelo='$id_vuelo', id_aerolinea='$id_aerolinea' WHERE id='$id_guia'";
		mysql_query($sql,$conexion) or die ('<font color="red" size="5">ATENCION: Error al ingresar la Gu&iacute;a:'.mysql_error().' Comun&iacute;quese con el Soporte T&eacute;cnico</font>');

		//2. almacenamiento del traking
		$sql_trak="INSERT INTO tracking (id_guia,fecha_creacion,hora,evento,tipo_tracking,id_usuario) value ('$id_guia','$fecha','$hora','GUIA ASIGNADA AL VUELO: $nvuelo','1','$id_usuario')";
		mysql_query($sql_trak,$conexion) or die (mysql_error());
		
		//3. javascript que  permite actualizar la ventana padre y cerrar la propia
		echo '<script language="javascript">
				window.opener.location.reload();
				self.close();
			</script>';
	}
}

?>