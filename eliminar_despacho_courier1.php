<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$fecha=date("Y").date("m").date("d");
$hora=date("H:i:s");
$id_usuario=$_SESSION['id_usuario'];

/*
PROCEDIMIENTO:
1. consulta la guia asociada al despacho
2. Modificar el registro a estado I
3. Elimina los datos agregados
4. Agregamos el cambio al historial de la guia
*/

if(isset($_GET["id_registro"]))
{
	$id_registro=$_GET["id_registro"];
	//1.
	$sql2="SELECT id,id_guia FROM courier_despacho WHERE id = '$id_registro'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$id_registro=$fila2['id'];
	$id_guia=$fila2['id_guia'];	

	//2. valores que deben regresar al total de la guia al cambiar de estado
	$sql_guia="UPDATE guia SET 
						id_tipo_bloqueo='3',
						courier_dato_fin=null
				WHERE id = '$id_guia'";
	mysql_query($sql_guia,$conexion) or die ("ERROR 2: ".mysql_error());
	
	//3 Elimina los datos del despacho.
	$sql_despacho="DELETE FROM courier_despacho WHERE id='$id_registro'";
	mysql_query($sql_despacho,$conexion) or die ("ERROR 3: ".mysql_error());
	
	//4. almacenamiento del traking
	$sql_trak="INSERT INTO tracking (id_guia,
									 fecha_creacion,
									 hora,
									 evento,
									 tipo_tracking,
									 id_usuario) value 
											('$id_guia',
											 '$fecha',
											 '$hora',
											 'Planilla de Entrega No.$id_registro, Eliminada',
											 '1',
											 '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die ("ERROR 4: ".mysql_error());
	
	$opcion=$_GET["opcion"];
	switch($opcion)
	{
		case "eliminar":
			echo "<script language=\"javascript\">
						alert(\"Planilla Eliminada Satisfactoriamente, Las GUIAS asociadas estaran disponibles para generar una nueva Planilla de Entrega.\");
						document.location='courier_inventario_entrega.php';
				 </script>";
		break;
	}
}
else
	{
		echo "
			<script language=\"javascript\">
				alert(\"ERROR:No se pudo obtener el DESPACHO\");
				document.location='base.php';
			</script>";
	}