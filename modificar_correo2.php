<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
/*Procedimiento
1. Recopilar los datos que fueron o no digitados.
2. Modificar la tabla de corro.
3. Modificar la tabla de carga_correo.
*/
if (isset($_POST['id']))
{
	//1-----------
	$id_correo=$_POST['id'];
	if (isset($_POST['auxiliaram']))
		$auxiliaram=strtoupper($_POST['auxiliaram']);
		else
			$auxiliaram="";
			
	if (isset($_POST['operarioam']))
		$operarioam=strtoupper($_POST['operarioam']);
		else
			$operarioam="";
			
	if (isset($_POST['auxiliarpm']))
		$auxiliarpm=strtoupper($_POST['auxiliarpm']);
		else
			$auxiliarpm="";
			
	if (isset($_POST['operariopm']))
		$operariopm=strtoupper($_POST['operariopm']);
		else
			$operariopm="";
			
	if (isset($_POST['tpallets']))
		$tpallets=$_POST['tpallets'];
		else
			$tpallets="";
			
	if (isset($_POST['tmallas']))
		$tmallas=$_POST['tmallas'];
		else
			$tmallas="";

	if (isset($_POST['tcorreas']))
		$tcorreas=$_POST['tcorreas'];
		else
			$tcorreas="";

	if (isset($_POST['tdollys']))
		$tdollys=$_POST['tdollys'];
		else
			$tdollys="";

	if (isset($_POST['supervisor']))
		$supervisor=strtoupper($_POST['supervisor']);
		else
			$supervisor="";

	if (isset($_POST['jefe']))
		$jefe=strtoupper($_POST['jefe']);
		else
			$jefe="";

	if (isset($_POST['coordinador']))
		$coordinador=strtoupper($_POST['coordinador']);
		else
			$coordinador="";

	if (isset($_POST['entrega']))
		$entrega=strtoupper($_POST['entrega']);

	//2-----------
	$sql="UPDATE correo SET aux_entrega_am='$auxiliaram',
							oper_entrega_am='$operarioam',
							aux_entrega_pm='$auxiliarpm',
							oper_entrega_pm='$operariopm',
							tpallets='$tpallets',
							tmallas='$tmallas',
							tcorreas='$tcorreas',
							tdollys='$tdollys',
							coordinador='$coordinador',
							jefe='$jefe',
							supervisor='$supervisor',
							tipo_entrega='$entrega'
								WHERE id='$id_correo'";
	mysql_query($sql,$conexion) or die ("ERROR 1:". mysql_error());
	//3
	$cantidad_guias=$_POST['cantidad_guias'];
	for ($i=1; $i<=$cantidad_guias; $i++)
	{
		$palet=strtoupper($_POST["palet$i"]);
		$pcs=$_POST["pcs$i"];
		$hhi=$_POST["hhi$i"];
		$mmi=$_POST["mmi$i"];
		$ssi=$_POST["ssi$i"];
		
		$hhs=$_POST["hhs$i"];
		$mms=$_POST["mms$i"];
		$sss=$_POST["sss$i"];
		
		
		$observaciones=strtoupper($_POST["observaciones$i"]);
		$id_registro=$_POST["id_registro$i"];
		
		$sql2="UPDATE carga_correo SET npallets='$palet',
										npcs='$pcs',
										hora_inicio='$hhi:$mmi:$ssi',
										hora_salida='$hhs:$mms:$sss',
										observaciones='$observaciones'
											WHERE id='$id_registro'";
		mysql_query($sql2,$conexion) or die ("ERROR 2:". mysql_error());
	}
	echo "
	<script language=\"javascript\">
		alert(\"DESPACHO modificado con exito\");
		document.location='consulta_identificar_correo.php';
	</script>";
}
else
	{
		echo "
			<script language=\"javascript\">
				alert(\"ERROR:No se pudo obtener el DESPACHO\");
				document.location='base.php';
			</script>";
	}
