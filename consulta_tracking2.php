<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$parametro1="";
$otro1="";
if(isset($_REQUEST["origen"]))
	$origen=$_REQUEST["origen"];
else
	$origen="";
	
switch($origen)
{
	case ("buscador"): //Cuando proviene del buscador x numero de guia
		$url_destino="consulta_tracking3.php";
	break;

	case ("TRACKING"): // Cuando proviene de la pantalla consultar guia por traking
		$url_destino="consulta_tracking3.php";
	break;

	case ("preinspeccion"): // Cuando se consulta por preinspeccion
		$url_destino="preinspeccion1.php";
	break;
	
	case ("verificacion"): //Cuando proviene del buscador x registro de repeso de la guia
		$url_destino="seguridad_verificar_peso.php";
		//$parametro1="AND (id_tipo_bloqueo = '3' OR id_tipo_bloqueo = '6' OR id_tipo_bloqueo = '10') AND faltante_total = 'N'";
		$parametro1="AND faltante_total = 'N'";
	break;
	
	case ("registro_fotografico"): // Cuando se consulta por preinspeccion
		$url_destino="registro_fotografico_acciones.php";
		$otro1="&boton=".$_REQUEST["otro1"];
	break;
	
	case ("correccion_inconsistencias"): // Cuando se consulta por modificacion de la guia por correccion en las inconsistencias
		$url_destino="correccion_inconsistencias1.php";
		$parametro1="AND id_tipo_bloqueo = 3";
	break;	

	case ("correccion_postdespacho"): // Cuando se consulta por modificacion de la guia ya despachada
		$url_destino="modificar_guia_despachada.php";
		$parametro1="AND id_tipo_bloqueo = 4 OR id_tipo_bloqueo = 6";
	break;	
		
	default:
		$url_destino="consulta_guia.php";
	break;
}
//Discriminacion de aerolinea de usuario TIPO 2
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id_aerolinea = '$id_aerolinea_user'";	
//*************************************
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<?php
require("menu.php");
?>
<p align="center">
<?php
if (isset($_REQUEST["ta"]))
{
	$hija=$_REQUEST["ta"];
	$sql="SELECT id,hija,master,fecha_creacion,hora,id_consignatario,id_vuelo,id_aerolinea FROM guia WHERE hija LIKE '%$hija%' $sql_aerolinea $parametro1 ORDER BY fecha_creacion, hora DESC";
	$consulta=mysql_query($sql,$conexion) or die ("Error 1".mysql_error());
	$nfilas=mysql_num_rows($consulta);
	if ($nfilas > 0)
	{
		echo '<p class="asterisco" align="center"> Seleccione la Gu&iacute;a que desea consultar del listado de coincidencias</p>';
		while($fila=mysql_fetch_array($consulta))
		{
			$id=$fila["id"];
			$hija=$fila["hija"];
			$master=$fila["master"];
			require("config/master.php");
			$fecha_creacion=$fila["fecha_creacion"];
			$hora=$fila["hora"];
			$id_consignatario=$fila["id_consignatario"];
			$id_vuelo=$fila["id_vuelo"];
			$id_aerolinea=$fila["id_aerolinea"];
			//aerolinea
			$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila2=mysql_fetch_array($consulta2);
			$aerolinea=$fila2['nombre'];
			//vuelo
			$sql2="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila2=mysql_fetch_array($consulta2);
			$nvuelo=$fila2['nvuelo'];	
			//consignatario
			$sql2="SELECT nombre FROM consignatario WHERE id='$id_consignatario'";
			$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila2=mysql_fetch_array($consulta2);
			$consignatario=$fila2['nombre'];
	
			//carga datos de la guia
			echo '<table width="850" align="center" class="decoracion_tabla">
					  <tr>
						<td width="170" rowspan="2" align="center" valign="middle" class="celda_tabla_principal">
								<button onclick="' ."document.location='$url_destino?id_guia=$id&ta=$hija$otro1'".'"">
									<img src="imagenes/aceptar-act.png" title="Seleccionar"/>
								</button>            
						</td>
						<td class="celda_tabla_principal"><div class="letreros_tabla">Consolidado</div></td>
						<td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
						<td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
						<td class="celda_tabla_principal"><div class="letreros_tabla">Vuelo</div></td>
                        <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
					  </tr>
					  <tr>
						<td class="celda_tabla_principal celda_boton">'.$master.'</td>
						<td class="celda_tabla_principal celda_boton">'.$hija.'</td>
						<td width="135" class="celda_tabla_principal celda_boton"><font size="-1">'.$fecha_creacion.' - '.$hora.'</font></td>
						<td width="90" class="celda_tabla_principal celda_boton">'.$nvuelo.'</td>
						<td class="celda_tabla_principal celda_boton">'.$aerolinea.'</td>
					  </tr>
					  <tr>
						<td class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
						<td colspan="5" class="celda_tabla_principal celda_boton">'.$consignatario.'</td>
					  </tr>
				</table>
				<br>';
		}
	}
	else
		{
			echo "<p align=\"center\"><font size=\"+3\" color=\"red\">No Puede Seleccionar Esta Gu&iacute;a</font></p>";
		}
}
?>
</p>
</body>
</html>  
