<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if (isset($_GET['id_guia']))
{
	$id_guia=$_GET["id_guia"];
	$sql="SELECT id_tipo_bloqueo,hija,id_vuelo FROM guia WHERE id = '$id_guia'";
	$consulta=mysql_query($sql,$conexion) or die ("Error 1:".mysql_error());
	$fila=mysql_fetch_array($consulta);
	$id_tipo_bloqueo=$fila["id_tipo_bloqueo"];
	$hija=$fila["hija"];
	$id_vuelo=$fila["id_vuelo"];
	
	//carga dato adicionales
	$sql2="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("Error 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$vuelo=$fila2["nvuelo"];
	
	switch ($id_tipo_bloqueo)
	{
		case (1):
			//elimina de la tabla de guia
			$sql="DELETE FROM guia WHERE id = '$id_guia'";
			mysql_query($sql,$conexion) or die ("Error 3:".mysql_error());
			
			//elimina de la tabla de traking
			$sql="DELETE FROM tracking WHERE id_guia = '$id_guia'";
			mysql_query($sql,$conexion) or die ("Error 4:".mysql_error());
			
			//elimina de la tabla de INCONSISTENCIAS
			$sql="DELETE FROM inconsistencias WHERE id_guia = '$id_guia'";
			mysql_query($sql,$conexion) or die ("Error 5:".mysql_error());

			
			echo "<script type=\"text/javascript\">
					alert(\"La Guia $hija se ELIMINO de manera Exitosa\");
					document.location='base.php';
				</script>";
		break;		
		case ($id_tipo_bloqueo==4 || $id_tipo_bloqueo==5 || $id_tipo_bloqueo==10):
			//BLOQUEA LA ELIMINACION POR QUE LA GUIA ESTA BLOQUEADA, DESPACHADA
			echo "<script type=\"text/javascript\">
					alert(\"La Guia:$hija, NO SE PUEDE ELIMINAR\");
					document.location='consulta_guia.php?id_guia=$id_guia';
				</script>";
		break;
		
		case ($id_tipo_bloqueo==8):
			//BLOQUEA LA ELIMINACION POR QUE LA GUIA ESTA ANULADA
			echo "<script type=\"text/javascript\">
					alert(\"La Guia:$hija, esta ANULADA y no es posible eliminarla\");
					document.location='consulta_guia.php?id_guia=$id_guia';
				</script>";
		break;

		case ($id_tipo_bloqueo==6 || $id_tipo_bloqueo==3 || $id_tipo_bloqueo==7 || $id_tipo_bloqueo==2 || $id_tipo_bloqueo==9 || $id_tipo_bloqueo==11):
			//BLOQUEA LA ELIMINACION POR QUE LA GUIA ESTA DEVUELTA O INCONSISTENCIAS, ASOCIADA A UN VUELO, MANIFESTADA O SOBRANTE.  PERO PODRA SER INACTIVADA 		
			echo "
					<br>
					<br>
					<br>
					<br>
					<table width=\"400\" height=\"200\"  align=\"center\">
					  <tr align=\"center\">
						<td width=\"391\" bgcolor=\"#3366FF\" align=\"center\">
						<table width=\"380\" align=\"center\" >
						  <tr bgcolor=\"#FFFFFF\">
							<td height=\"67\" colspan=\"2\">
								<div align=\"center\">
									Esta gu&iacute;a No. $hija se encuentra asociada a otros procesos<br>
									NO PODRA ELIMINARLA, pero podr&aacute; ANULARLA para que no sea utilizada.<br>
									&iquest;Confirma que desea ANULARLA?
								</div>
							</td>
							</tr>
						  <tr>
							<td width=\"172\" height=\"70\" align=\"center\" bgcolor=\"#99FF00\"><input type=\"button\" value=\"Aceptar\" onclick=\"document.location='eliminar_guia2.php?id_guia=$id_guia&procedimiento=A'\"></td>
							<td width=\"196\" align=\"center\" bgcolor=\"#FF0000\"><input type=\"button\" value=\"Cancelar\" onclick=\"document.location='consulta_guia.php?id_guia=$id_guia'\"></td>
						  </tr>
						</table>
						</td>
					  </tr>
					</table>";
		break;
		
		/*case ($id_tipo_bloqueo==7 || $id_tipo_bloqueo==2):
			////ASIGNADA A VUELO O MANIFESTADA
			echo "
					<br>
					<br>
					<br>
					<br>
					<table width=\"400\" height=\"200\"  align=\"center\">
					  <tr align=\"center\">
						<td width=\"391\" bgcolor=\"#3366FF\" align=\"center\">
						<table width=\"380\" align=\"center\" >
						  <tr bgcolor=\"#FFFFFF\">
							<td height=\"67\" colspan=\"2\"><div align=\"center\">Esta gu&iacute;a No. $hija se encuentra asociada al vuelo<br>
							$vuelo<br>
							&iquest;Confirma que desea efectuar su eliminaci&oacute;n? </div></td>
							</tr>
						  <tr>
							<td width=\"172\" height=\"70\" align=\"center\" bgcolor=\"#99FF00\"><input type=\"button\" value=\"Aceptar\" onclick=\"document.location='eliminar_guia2.php?id_guia=$id_guia&procedimiento=E'\"></td>
							<td width=\"196\" align=\"center\" bgcolor=\"#FF0000\"><input type=\"button\" value=\"Cancelar\" onclick=\"document.location='consulta_guia.php?id_guia=$id_guia'\"></td>
						  </tr>
						</table>
						</td>
					  </tr>
					</table>";
		break;*/
	}
}
?>
