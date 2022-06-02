<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */     						
require("config/configuracion.php");
if(isset($_SESSION["id_usuario"]))
{
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");
	$usuario=$_SESSION["id_usuario"];
	$id_placa=isset($_GET['placa']) ? $_GET['placa'] : $_POST['placa'];
	$courier_ccConductor=isset($_GET['cc']) ? $_GET['cc'] : $_POST['cc'];
	$courier_nombreConductor=isset($_GET['nombre']) ? strtoupper($_GET['nombre']) : strtoupper($_POST['nombre']);
	$id_guia=isset($_GET['id_guia']) ? $_GET['id_guia'] : $_POST['id_guia'];
	$impresion="";

	//Insertamos datos nuevos
	$sql_trak="INSERT INTO courier_transportador (
													id_guia,
									 				id_placa,
									  				no_documento,
									  				nombre
									  			) 
												VALUE (
														'$id_guia',
													   	'$id_placa',
													   	'$courier_ccConductor',
													   	'$courier_nombreConductor'
													   )";
	mysql_query($sql_trak,$conexion) or die (exit('Error 1'.mysql_error()));
	$id_registro = mysql_insert_id($conexion); //Obtiene el id de la ultima insercion

	//consulta de la placa del vehiculo
	$sql_aux2="SELECT placa FROM vehiculo_courier WHERE id='$id_placa'";
	$consulta_aux2=mysql_query($sql_aux2,$conexion) or die (exit('Error 2'.mysql_error()));
	$fila_aux2=mysql_fetch_array($consulta_aux2);
	$placa=$fila_aux2["placa"];

	//Crea registro en el historial
	$registro_novedad ="Placa del vehiculo que retira la carga: ".$placa.
						"<br>".
						"Conductor:".$courier_nombreConductor.
						"<br>".
						"CC:".$courier_ccConductor
						;
	$sql_trak="INSERT INTO tracking (id_guia,
									 fecha_creacion,
									 hora,
									 evento,
									 tipo_tracking,
									 id_usuario) 
										VALUE ('$id_guia',
											   '$fecha',
											   '$hora',
											   '$registro_novedad',
											   '1',
											   '$id_usuario')";
	mysql_query($sql_trak,$conexion) or die (exit('Error 3'.mysql_error()));

	//consultas auxiliares
	$sql_aux="SELECT id,id_placa,nombre,no_documento FROM courier_transportador WHERE id_guia='$id_guia'";
	$consulta_aux=mysql_query ($sql_aux,$conexion) or die (exit('Error 4'.mysql_error()));
	while($fila_aux=mysql_fetch_array($consulta_aux))
	{
		$id=$fila_aux["id"];
		$no_documento=$fila_aux["no_documento"];
		$nombre=$fila_aux["nombre"];
		//consulta de la placa del vehiculo
		$id_placa=$fila_aux["id_placa"];
		$sql_aux2="SELECT placa FROM vehiculo_courier WHERE id='$id_placa'";
		$consulta_aux2=mysql_query($sql_aux2,$conexion) or die (exit('Error 5'.mysql_error()));
		$fila_aux2=mysql_fetch_array($consulta_aux2);
		$placa=$fila_aux2["placa"];

		$impresion .= 	'<tr>
							<td class="celda_tabla_principal celda_boton">'.$placa .'</td>'. 
							'<td class="celda_tabla_principal celda_boton">'.$nombre .'</td>'.
							'<td class="celda_tabla_principal celda_boton">'.$no_documento .'</td>'. 
							'<td class="celda_tabla_principal celda_boton"><button type="button" onclick="procesarEliminar('.$id.')"><img src="imagenes/eliminar-act.png" title="Eliminar"></button></td>
						</tr>';
	}
	echo $impresion;
}
else
{
	echo "Error 0";
}
?>