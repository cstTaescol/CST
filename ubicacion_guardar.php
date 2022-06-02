<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_usuario=$_SESSION['id_usuario'];
$fecha_creacion=date("Y").date("m").date("d");
$hora_registro=date("H:i:s");
$suma=0;
$plaqueta="";
$observaciones="";
$id_guia=$_REQUEST['id_guia'];
$id_posicion=$_REQUEST['id_posicion'];
$peso_ubicar=$_REQUEST['peso_ubicar'];
$piezas_ubicar=$_REQUEST['piezas_ubicar'];
$observaciones_ubicar=strtoupper($_REQUEST['observaciones_ubicar']);
$fondo=$_REQUEST['fondo'];

if(isset($_REQUEST['id_vuelo']))
{
	$id_vuelo=$_REQUEST['id_vuelo'];
	if(isset($_REQUEST['retorno']))
		$retorno=$_REQUEST['retorno'] . "?vuelo=" . $id_vuelo ;
	else
		$retorno="ubicacion_ubicar.php";
}	
else
	$retorno="ubicacion_ubicar.php";			




if ($fondo != "F") $fondo = "";
	
echo '<p align="center"><img src="imagenes/cargando.gif" width="20" height="21" /><br>Procesando</p>';

$sql="SELECT * FROM guia WHERE id=$id_guia";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
while($fila=mysql_fetch_array($consulta))
	{
		//calculo del valor pendiente por entregar
		include("config/evaluador_inconsistencias.php"); //calcula y general el valor de $piezas, $peso y $volumen luego de las inconsistencias.		
		$piezas_pendientes_despachar=$piezas-$fila["piezas_despacho"];
		$peso_pendientes_despachar=$peso-$fila["peso_despacho"];
		
		$hija=$fila["hija"];
		$ob_bodega=$fila["observaciones_bodega"];
		if ($ob_bodega == "")
			$observaciones=$observaciones_ubicar;
		else
			$observaciones=$observaciones_ubicar."<hr>".$ob_bodega; 
	}

//Evaluamos que el peso y las puezas digitadas no sean mayores a las disponibles
/* Funcion desahilidata por solicitud del cliente - Al despaletizar controlan el tema

if ($piezas_ubicar > $piezas_pendientes_despachar)
{
	echo " 
	<script>
		alert('ERROR, ha Ingresado MAS PIEZAS de las que Estan Disponibles en el INVENTARIO - ($piezas_pendientes_despachar).');
		document.location='ubicacion_ubicar.php';
	</script>";
	exit();
}

if ($peso_ubicar > $peso_pendientes_despachar)
{
	echo " 
	<script>
		alert('ERROR, ha Ingresado MAS PESO del que Esta Disponible en el INVENTARIO - ($peso_pendientes_despachar).');
		document.location='ubicacion_ubicar.php';
	</script>";
	exit();
}
*/

//Agrega Guia a la Posicion.
$sql_posiscion="INSERT INTO posicion_carga (id_guia,id_posicion,piezas,peso,observacion,fondo) VALUE ('$id_guia','$id_posicion','$piezas_ubicar','$peso_ubicar','$observaciones_ubicar','$fondo')";
mysql_query ($sql_posiscion,$conexion) or die ("ERROR AL ALMACENAR POSICION: ". mysql_error(). " INFORME AL SOPORTE TECNICO");

//Identificacion de Posiciones.
$sql_posiscion="SELECT * FROM posicion WHERE id='$id_posicion'";
$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila_posicion=mysql_fetch_array($consulta_posicion);
$plaqueta=$fila_posicion['rack']."-".$fila_posicion['seccion']."-".$fila_posicion['nivel']."-".$fila_posicion['lado'];

if ($observaciones != "")
{
	//Actualizacion de Observaciones de BODEGA para la guia
	$sql="UPDATE guia SET observaciones_bodega='$observaciones' WHERE id='$id_guia'";
	mysql_query($sql,$conexion) or die (exit('Error al actualizar la guia'.mysql_error()));
	$observaciones="OBSERVACIONES DE BODEGA ACTUALIZADAS CON:<br />".$observaciones;
}

//Almacenamiento del traking
$sql_trak="INSERT INTO tracking (id_guia,
								 fecha_creacion,
								 hora,
								 evento,
								 tipo_tracking,
								 id_usuario) 
										value ('$id_guia',
											   '$fecha_creacion',
											   '$hora_registro',
											   'GUIA UBICADA EN BODEGA, PRESENTA LAS SIGUIENTES POSICIONES:<br />
											   $plaqueta<br />
											   $observaciones',
											   '1',
											   '$id_usuario')";
mysql_query($sql_trak,$conexion) or die (exit('Error '.mysql_error()));
echo '
<script language="javascript">
	alert("Ubicacion Almacenada en '.$plaqueta.'");
	document.location=\''.$retorno.'\';	
</script>';

?>

