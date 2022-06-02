<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */     						
require("config/configuracion.php");
if(isset($_SESSION["id_usuario"]))
{
	$fecha=date("Y").date("m").date("d");
	$hora=date("H:i:s");
	$usuario=$_SESSION["id_usuario"];
	$id_registro=isset($_GET['id_registro']) ? $_GET['id_registro'] : $_POST['id_registro'];
	$id_guia=isset($_GET['id_guia']) ? $_GET['id_guia'] : $_POST['id_guia'];

	//Crea registro en el historial
	$sql_aux="SELECT v.placa, t.nombre, t.no_documento FROM vehiculo_courier v LEFT JOIN courier_transportador t ON v.id = t.id_placa WHERE t.id='$id_registro'";
	$consulta_aux=mysql_query ($sql_aux,$conexion) or die (exit('Error 1'.mysql_error()));
	$fila_aux=mysql_fetch_array($consulta_aux);
	$placa=$fila_aux['placa'];
	$courier_nombreConductor=$fila_aux['nombre'];
	$courier_ccConductor=$fila_aux['no_documento'];
	$registro_novedad ="Placa del vehiculo Eliminado: ".$placa.
						"<br>".
						"Conductor: ".$courier_nombreConductor.
						"<br>".
						"CC: ".$courier_ccConductor
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
	mysql_query($sql_trak,$conexion) or die (exit('Error 2'.mysql_error()));

	//Se elimina de la BD el registro solicitado
	$sql="DELETE FROM courier_transportador WHERE id ='$id_registro'";
	mysql_query($sql,$conexion) or die (exit('Error 3'.mysql_error()));

	$impresion = '<table>
	    			<tr>
	    				<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Placa</div></td>
	    				<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Nombre</div></td>
	    				<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Doc.</div></td>
	    				<td class="celda_tabla_principal"><div class="letreros_tabla asterisco">Borrar</div></td>
	    			</tr>';

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