<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if (isset($_GET['id_guia']))
{
	$id_guia=$_GET["id_guia"];
	$sql="SELECT id_tipo_bloqueo,master FROM guia WHERE id = '$id_guia'";
	$consulta=mysql_query($sql,$conexion) or die ("Error 1:".mysql_error());
	$fila=mysql_fetch_array($consulta);
	$id_tipo_bloqueo=$fila["id_tipo_bloqueo"];
	$master=$fila["master"];
	
	switch ($id_tipo_bloqueo)
	{
		case (1):
			//elimina de la tabla de guia
			$sql="DELETE FROM guia WHERE id = '$id_guia'";
			mysql_query($sql,$conexion) or die ("Error 3:".mysql_error());
			
			//elimina de la tabla de traking
			$sql="DELETE FROM tracking WHERE id_guia = '$id_guia'";
			mysql_query($sql,$conexion) or die ("Error 4:".mysql_error());
			
			//elimina funcionarios asociados a la guia
			$sql="DELETE FROM courier_funcionarios_guia WHERE id_guia = '$id_guia'";
			mysql_query($sql,$conexion) or die ("Error 5:".mysql_error());

			//elimina guias asociadas a un turno
			$sql="DELETE FROM courier_turno_guia WHERE id_guia = '$id_guia'";
			mysql_query($sql,$conexion) or die ("Error 6:".mysql_error());
			
			echo "<script type=\"text/javascript\">
					alert(\"La Guia $master se ELIMINO de manera Exitosa\");
					document.location='base.php';
				</script>";
		break;		
	}
}
?>
