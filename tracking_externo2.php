<?php 
/*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
//require("config/control_tiempo.php");
?>
<html>
<head>
    <noscript>
    <h3>Se requiere JavaScript para utilizar este Portal</h3>
    <strong>*&nbsp;Este explorador web no admite JavaScript o las secuencias de comandos est&aacute;n bloqueadas.</strong>
    <meta http-equiv="Refresh" content="2;url=<?php echo URLCLIENTE ?>">
    </noscript>
    <title>TRACKING SIC-CST</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<table align="center">
        <tr>
        	<td>
            	<a href="tracking_externo.php"><img src="imagenes/traking.jpg" border="0"></a>
            </td>
        </tr>
     </table>
<p align="center">
  <?php
if (isset($_REQUEST["ta"]))
{
	$guia=$_REQUEST["ta"];
	if ($guia == "")
	{
		echo "<p align=\"center\"><font size=\"+3\" color=\"red\">La Gu&iacute;a Solicitada No Existe</font></p>";
	}
	else
	{
		$tipo_guia=$_REQUEST["tipo_guia"];
		$sql="SELECT id,hija,master,fecha_creacion,hora,id_consignatario,id_vuelo,id_aerolinea FROM guia WHERE $tipo_guia = '$guia' ORDER BY fecha_creacion, hora DESC";
		$consulta=mysql_query($sql,$conexion) or die ("Error 1".mysql_error());
		$nfilas=mysql_num_rows($consulta);
		if ($nfilas > 0)
		{
			echo '<p align="center"><font size="+1" color="green">Seleccione la Gu&iacute;a que desea consultar del listado de coincidencias</font></p>';
			while($fila=mysql_fetch_array($consulta))
			{
				$id=$fila["id"];
				$hija=$fila["hija"];
				$master=$fila["master"];
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

				if ($tipo_guia=="hija")
				{
					require("config/master.php");
					$url_destino="tracking_externo3.php";
					$datos_hija="&ta=$hija";
				}
				else
				{
					$url_destino="tracking_externo_consolidado.php";
					$datos_hija="";
				}

				//carga datos de la guia
				?>
					<hr>
					<table width="850" align="center" class="decoracion_tabla">
						  <tr>
							<td width="170" rowspan="2" align="center" valign="middle" class="celda_tabla_principal">
									<button onClick="document.location='<?php echo $url_destino."?id_guia=".$id.$datos_hija ?>'">
										<img src="imagenes/aceptar-act.png" />
									</button>            
							</td>
                            <td class="celda_tabla_principal"><div class="letreros_tabla">Consolidado</div></td>
                            <td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
                            <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
                            <td class="celda_tabla_principal"><div class="letreros_tabla">Vuelo</div></td>
                            <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
						  </tr>
						  <tr>
							<td class="celda_tabla_principal celda_boton"><?php echo $master ?></td>
							<td class="celda_tabla_principal celda_boton"><?php echo $hija ?></td>
							<td width="135" class="celda_tabla_principal celda_boton"><font size="-1"><?php echo $fecha_creacion.' - '.$hora ?></font></td>
							<td width="88" class="celda_tabla_principal celda_boton"><?php echo $nvuelo ?></td>
							<td class="celda_tabla_principal celda_boton"><?php echo $aerolinea ?></td>
						  </tr>
						  <tr>
							<td class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
							<td colspan="5" class="celda_tabla_principal celda_boton"><?php echo $consignatario ?></td>
						  </tr>
						</table>
				<?php
			}
		}
	else
		{
			echo '<p align="center"><font size="+3" color="red">La Gu&iacute;a Solicitada No Existe</font></p>';
		}
	}
	if(isset($_POST["origen"])) // si proviene desde la gusqueda de la guia
	{		
		echo '
				<table width="850" align="center">
				  <tr>
					<td align="center" class="celda_tabla_principal celda_boton">
						<button onclick="' ."document.location='tracking_externo.php'".'"">
							<img src="imagenes/anterior.jpg"/>
						</button>					
					</td>
				  </tr>
				</table>
			  ';
	}
	else // si proviene desde el traking
	{
		echo '
			<table width="450" align="center">
				<tr>
				  <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
				</tr>
				<tr>
				  <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
					<button type="button" name="cancelar" id="cancelar" onClick="document.location=\''.URLCLIENTE.'\'">
						<img src="imagenes/al_principio-act.png" title="'.CLIENTE.'" /><br>
                		Volver a '.CLIENTE.'
					</button>
					<button type="button" name="cancelar" id="cancelar" onClick="document.location=\'tracking_externo.php\'">
						<img src="imagenes/buscar-act.png" title="Buscar Otra" /><br>
                		Nueva busqueda
					</button>
				  </td>
				</tr>
			 </table>    
	  		';
	}
}
?>
</p>
</body>
</html>  
