<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
set_time_limit (0); // Elimina la restriccion en el tiempo limite para la ejecicion del modulo.
require("config/configuracion.php");
require("config/control_tiempo.php");

//Discriminacion de aerolinea de usuario TIPO 2
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id_aerolinea = '$id_aerolinea_user'";	
//*************************************

if(isset($_GET["opcion"]))
{
	//opciones de seleccion de vuelo segun el origen del menu
	switch ($_GET["opcion"])
	{	
		case ("addguia"): //direcciona hacia manifestar un vuelo
			//Privilegios Consultar Todo el Modulo
			$id_objeto=39; 
			include("config/provilegios_modulo.php");  
			//---------------------------
			$msgubicacion="Modificar la Asignacion de Guias al Vuelo";
			$condicion="WHERE estado = 'A'";
		break;
		
		case ("manifestar"): //direcciona hacia manifestar un vuelo
			//Privilegios Consultar Todo el Modulo
			$id_objeto=41; 
			include("config/provilegios_modulo.php");  
			//---------------------------			
			$msgubicacion="Manifestar un Vuelo";
			$condicion="WHERE estado = 'A'";
		break;
		
		case ("modmanifiesto"): //direcciona vuelos manifestados
			//Privilegios Consultar Todo el Modulo
			$id_objeto=42; 
			include("config/provilegios_modulo.php");  
			//---------------------------
			$msgubicacion="Manifestar un Vuelo";
			$condicion="WHERE estado = 'M'";
		break;

		case ("arribo"): //direcciona hacia Completar la fecha y hora de llegada del vuelo
			//Privilegios Consultar Todo el Modulo
			$id_objeto=43; 
			include("config/provilegios_modulo.php");  
			//---------------------------
			$msgubicacion="Aviso de Llegada";
			$condicion="WHERE estado = 'M'";
		break;	
		
		case ("findescargue"): //direcciona hacia Completar la fecha y hora de descargue
			//Privilegios Consultar Todo el Modulo
			$id_objeto=44; 
			include("config/provilegios_modulo.php");  
			//---------------------------
			$msgubicacion="Fin de Descargue";
			$condicion="WHERE estado = 'L'";
		break;
		
		case ("inconsistencias"): //direcciona hacia Completar la fecha y hora de descargue
			//Privilegios Consultar Todo el Modulo
			$id_objeto=45; 
			include("config/provilegios_modulo.php");  
			//---------------------------
			$msgubicacion="Inconsistencias";
			$condicion="WHERE estado = 'D'";
		break;

		case ("despaletizaje"): //direcciona hacia Completar la fecha y hora de descargue
			//Privilegios Consultar Todo el Modulo
			$id_objeto=86; 
			include("config/provilegios_modulo.php");  
			//---------------------------
			$msgubicacion="Despaletizar";
			$condicion="WHERE estado = 'D' OR estado = 'L'";
		break;

		
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
//Funcion para confirmar la eliminacion
function conf_eliminar(url)
{
var respuesta=confirm('ATENCION: Confirma que Desea Eliminar el Vuelo?, Las Guias asociadas al vuelo seran liberadas');
if (respuesta)
	{
		window.location="eliminar_vuelo1.php?id_vuelo="+url;
	}
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Vuelos Activos</p>
<p class="asterisco" align="center"><?php echo $msgubicacion ?></p>
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Vuelo</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Matricula</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Ruta</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha Creacion</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Seleccionar</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Eliminar</div></td>
  </tr>
<?php
$sql="SELECT nvuelo,id,id_aerolinea,matricula,id_ruta,fecha_creacion,hora_estimada FROM vuelo $condicion $sql_aerolinea ORDER BY fecha_creacion DESC ";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nvuelos=mysql_num_rows($consulta);
if ($nvuelos >= 1)
{
	echo '<tr>';
	while($fila=mysql_fetch_array($consulta))
	{
		//******************
		//Verificamos desabiliar el boton de Eliminar cuando existan guias finalizadas asociadas a este vuelo.
		$id_vuelo=$fila["id"];
		$sql2="SELECT id FROM guia WHERE id_vuelo='$id_vuelo' AND (id_tipo_bloqueo !=1 AND id_tipo_bloqueo !=2 AND id_tipo_bloqueo !=7 AND id_tipo_bloqueo !=8)";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$nfilas=mysql_num_rows($consulta2);
		if ($nfilas == 0)
		{
			$bloqueo_btn="";
		}
		else
		{
			$bloqueo_btn="disabled=\"disabled\"";
		}
		//***********************************
		
		//***********************************
		$id_aerolinea=$fila["id_aerolinea"];
		$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea' AND estado ='A'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$nombre_aerolinea=$fila2["nombre"];
		//***********************************

		//***********************************
		$id_ruta=$fila["id_ruta"];
		$sql2="SELECT descripcion FROM ruta WHERE id='$id_ruta' AND estado ='A'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$nombre_ruta=$fila2["descripcion"];
		//***********************************
		$nvuelo=$fila["nvuelo"];
		$id=$fila["id"];
		$matricula=$fila["matricula"];
		$fecha_creacion=$fila["fecha_creacion"];
		$hora_estimada=$fila["hora_estimada"];
		
		if(isset($_GET["opcion"]))
		{
			//opciones de seleccion de vuelo segun el origen del menu
			switch ($_GET["opcion"])
			{		
				case ("addguia"): //direcciona hacia agregar guias
				$boton = '<td align="center" class="celda_tabla_principal celda_boton">
							  <button onclick="' ."document.location='vuelo_modificar_guias1.php?id_vuelo=$id&nvuelo=$nvuelo&aerolinea=$id_aerolinea&ruta=$id_ruta&matricula=$matricula&hora_estimada=$hora_estimada'".'">
								<img src="imagenes/aceptar-act.png" title="Seleccionar este Vuelo"/>
							</button>            
						</td>';
				break;	
				
				case ("manifestar"): //direcciona hacia manifestar un vuelo
				$boton = '<td align="center" class="celda_tabla_principal celda_boton">
							  <button onclick="' ."document.location='vuelo_manifiesto_seleccionar.php?vuelo=$id'".'">
								<img src="imagenes/aceptar-act.png" title="Seleccionar este Vuelo"/>
							</button>            
						</td>';
				break;				

				case ("modmanifiesto"): //direcciona hacia manifestar un vuelo
				$boton = '<td align="center" class="celda_tabla_principal celda_boton">
							  <button onclick="' ."document.location='vuelo_manifiesto_seleccionar.php?vuelo=$id'".'">
								<img src="imagenes/aceptar-act.png" title="Seleccionar este Vuelo"/>
							</button>            
						</td>';
				break;				

				case ("arribo"): //direcciona hacia Completar la fecha y hora de llegada del vuelo
				$boton = '<td align="center" class="celda_tabla_principal celda_boton">
							  <button onclick="' ."document.location='vuelo_arribo.php?vuelo=$id'".'"">
								<img src="imagenes/aceptar-act.png" title="Seleccionar este Vuelo"/>
							</button>            
						</td>';
				break;	
				
				case ("findescargue"): //direcciona hacia Completar la fecha y hora de descargue
				$boton = '<td align="center" class="celda_tabla_principal celda_boton">
							  <button onclick="' ."document.location='vuelo_fin_descargue.php?vuelo=$id'".'">
								<img src="imagenes/aceptar-act.png" title="Seleccionar este Vuelo"/>
							</button>            
						</td>';
				
				break;
				
				case ("inconsistencias"): //direcciona hacia seleccionar inconsistencias
				$boton = '<td align="center" class="celda_tabla_principal celda_boton">
							  <button onclick="' ."document.location='vuelo_inconsistencias.php?vuelo=$id'".'">
								<img src="imagenes/aceptar-act.png" title="Seleccionar este Vuelo"/>
							</button>            
						</td>';
				
				break;

				case ("despaletizaje"): //direcciona hacia seleccionar inconsistencias
				$boton = '<td align="center" class="celda_tabla_principal celda_boton">
							  <button onclick="' ."document.location='despaletizaje1.php?vuelo=$id'".'">
								<img src="imagenes/aceptar-act.png" title="Seleccionar este Vuelo"/>
							</button>            
						</td>';
				
				break;

			}
		}
		//Privilegio de Eliminacion de Vuelo
		$id_objeto=40; 
		include("config/provilegios_objeto.php");  
		echo '
		<tr>
			<td align="center" class="celda_tabla_principal celda_boton">'.$nvuelo.'</td>
			<td align="left" class="celda_tabla_principal celda_boton">'.$nombre_aerolinea.'</td>
			<td align="left" class="celda_tabla_principal celda_boton">'.$matricula.'</td>
			<td align="left" class="celda_tabla_principal celda_boton">'.$nombre_ruta.'</td>
			<td align="left" class="celda_tabla_principal celda_boton">'.$fecha_creacion.'</td>
			'.$boton.'
			<td align="center" class="celda_tabla_principal celda_boton">
				<button '.$bloqueo_btn.' onClick="conf_eliminar('.$id.');" '.$activacion.'>
					<img src="imagenes/cancelar-act.png" title="Eliminar este Vuelo"/>
				</button>
			</td>
		</tr>';
	}
}
?>
</table>
</body>
</html>
