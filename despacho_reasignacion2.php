<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$cantidadguias=$_POST['cantidadguias'];
$id_deposito=$_POST['id_deposito'];
$id_usuario=$_SESSION['id_usuario'];
$id_tipo_deposito=$_POST['tipo_deposito'];
$informe="";
$cont=0;
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
for($i=1; $i <= $cantidadguias; $i++)
{
	if (isset($_POST["ck$i"]))
	{
		$cont++;
		$id_guia=$_POST["ck$i"];
		switch ($id_tipo_deposito)
		{
			case 21: //franco
				$id_disposicion=18;
			break;

			case 48: //trafico postal
				$id_disposicion=26;
			break;

			case 30: //zona franca
				$id_disposicion=11;
			break;

			case 54: //zona franca occidente
				$id_disposicion=11;
			break;

			case 55: //zona franca tocancipa
				$id_disposicion=11;
			break;

			default: // el resto de tipos de deposito
				$id_disposicion=10;
			break;
		}
		
		//1 Actualiza los datos de la guia
		$sql="UPDATE guia SET 	id_disposicion='$id_disposicion',
								id_deposito='$id_deposito', 
								reasignacion='S'
									WHERE id='$id_guia'";
		mysql_query($sql,$conexion) or die (exit("Error".mysql_error()));
		
		//recuperando datos del deposito
		$sql2="SELECT nombre FROM deposito WHERE id='$id_deposito'";
		$consulta2=mysql_query ($sql2,$conexion) or die (exit("Error".mysql_error()));
		$fila2=mysql_fetch_array($consulta2);
		$destino=$fila2['nombre'];		
		
		//2. almacenamiento del traking
		$sql_trak="INSERT INTO tracking (id_guia,
										 fecha_creacion,
										 hora,evento,
										 tipo_tracking,
										 id_usuario) 
												value ('$id_guia',
													   '$fecha',
													   '$hora',
													   'GUIA RE-ASIGNADA HACIA EL DEP&Oacute;SITO. $destino',
													   '2',
													   '$id_usuario')";
		mysql_query($sql_trak,$conexion) or die (exit("Error".mysql_error()));
	}
}
//cuando registra el proceso del almenos una guia.
if ($cont > 0)
{
	echo '<script type="text/javascript">
			alert("ATENCION: '.$cont.' Guias Re-asignadas de Manera Exitosa");
		</script>';
	echo '<meta http-equiv="Refresh" content="0;url=base.php">';
}
else
	{
			echo '<script type="text/javascript">
					alert("ERROR: No Selecciono Ninguna Guia");
				</script>';
			echo '<meta http-equiv="Refresh" content="0;url=despacho_reasignacion1.php">';
	}
?>
