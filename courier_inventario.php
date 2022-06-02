<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

//Evaluación de filtro en la cuadrícula
$filtro='id_aerolinea';
$colorAerolinea='style="background-color: #FFCC00"';
$colorCourier='';					
if(isset($_REQUEST['filtro']))
{
	switch ($_REQUEST['filtro']) {
		case 'id_aerolinea':
			$filtro='id_aerolinea';
			$colorAerolinea='style="background-color: #FFCC00"';
			$colorCourier='';
		break;
		
		case 'id_consignatario':
			$filtro='id_consignatario';
			$colorAerolinea='';
			$colorCourier='style="background-color: #FFCC00"';				
		break;

		default:
			$filtro='id_aerolinea';
			$colorAerolinea='style="background-color: #FFCC00"';
			$colorCourier='';				
		break;
	}
}

//Filtro por casilla de búsqueda
if(isset($_REQUEST['busqueda']))
{
	$busqueda=$_REQUEST['busqueda'];
	$sql="SELECT * FROM guia WHERE id_tipo_guia ='5' AND id_tipo_bloqueo='1' AND master LIKE '%$busqueda%'";	
}
else
{
	$sql="SELECT * FROM guia WHERE id_tipo_guia ='5' AND id_tipo_bloqueo='1' ORDER BY $filtro ASC";	
}
	$impresion=""; 	
$consulta=mysql_query($sql,$conexion) or die ("ERROR 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
{
	$id_guia=$fila['id'];
	$id_aerolinea=$fila['id_aerolinea'];
	$id_linea=$fila['courier_id_linea'];
	$id_consignatario=$fila['id_consignatario'];
	$piezas=$fila["piezas"]; 
	$peso=$fila["peso"];		
	
	//**consulta Auxiliar
	$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$aerolinea=$fila2['nombre'];

	//**consulta Auxiliar
	$sql2="SELECT nombre FROM couriers WHERE id='$id_consignatario'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$consignatario=$fila2['nombre'];		
	
	//**consulta Auxiliar
	$sql2="SELECT nombre FROM courier_linea WHERE id='$id_linea'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 03: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$linea=($fila2['nombre'] == "") ? "Sin Asignar" : $fila2['nombre'];   

	$placa="";
	$sql_aux="SELECT v.placa FROM vehiculo_courier v LEFT JOIN courier_transportador t ON v.id = t.id_placa WHERE t.id_guia='$id_guia'";
	$consulta_aux=mysql_query ($sql_aux,$conexion) or die (exit('Error 1'.mysql_error()));
	while($fila_aux=mysql_fetch_array($consulta_aux))
	{
		$placa .= $fila_aux['placa'] . "<br>";			
	}		
	
	//Identificaion de los privilegios de los botones de acciones
	$id_objeto=122; 
	include("config/provilegios_objeto.php");  
	$actBtn1=$activacion;

	$actBtn2="";
	$actBtn3="";

	$id_objeto=129; 
	include("config/provilegios_objeto.php");  
	$actBtn4=$activacion;

	$id_objeto=136; 
	include("config/provilegios_objeto.php");  
	$actBtn5=$activacion;

	$impresion .= '	<tr>
						<td class="celda_tabla_principal celda_boton">
							<button type="button" style="width:50px; height:50px;" title="Acceder a la INFORMACION de la Gu&iacute;a" onclick="document.location=\'consulta_guia_courier.php?id_guia='.$id_guia.'\'" '.$actBtn1.'>
								<img src="imagenes/info.png" height="29" width="33" />
							</button>
							<button type="button" style="width:50px; height:50px;" title="Seguridad" onclick="openPopup(\'courier_seguridad_menu.php?id_guia='.$id_guia.'\',\'new\',\'800\',\'500\',\'scrollbars=1\',true);" '.$actBtn2.'>
								<img src="imagenes/poli1.png" height="29" width="33" />
								</button>												
							<button type="button" style="width:50px; height:50px;" title="Asignacion de Funcionarios" onclick="document.location=\'courier_registro_linea.php?id_guia='.$id_guia.'\'" '.$actBtn3.'>
								<img src="imagenes/scanner_1.png" height="29" width="33" />
							</button>
							<button type="button" style="width:50px; height:50px;" title="Actuaci&oacute;n Aduanera" onclick="document.location=\'courier_registro_hija.php?id_guia='.$id_guia.'\'" '.$actBtn4.'>
								<img src="imagenes/LogoDian.png" height="29" width="33" />
							</button>
							<button type="button" style="width:50px; height:50px;" title="Conteo de Piezas" onclick="openPopup(\'courier_conteo.php?id_guia='.$id_guia.'\',\'new\',\'800\',\'500\',\'scrollbars=1\',true);" '.$actBtn5.'>
								<img src="imagenes/bascula3.png" height="29" width="33" />
							</button>												
						</td>						
						<td class="celda_tabla_principal celda_boton">'.$fila['master'].'</td>
						<td class="celda_tabla_principal celda_boton">'.$aerolinea.'</td>
						<td class="celda_tabla_principal celda_boton">'.$consignatario.'</td>
						<td align="right" class="celda_tabla_principal celda_boton">'.$piezas.'</td>
						<td align="right" class="celda_tabla_principal celda_boton">'.$peso.'</td>
						<td class="celda_tabla_principal celda_boton">'.$linea.'</td>
						<td class="celda_tabla_principal celda_boton">'.$placa.'</td>
					</tr>
					';
}  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<style>
		button:
		{
			width:50px;
			height:50px;
		}
		button:hover 
		{
			background: rgba(0,0,0,0);
			color: #3a7999;
			box-shadow: inset 0 0 0 3px #3a7999;
		}	
	</style>
</head>
<body>
<?php
	require("menu.php");
	$id_objeto=119;
	include("config/provilegios_modulo.php");
?>
<p class="titulo_tab_principal">Inventario General Courier</p>
<table align="center">
	<tr>
		<td align="center" class="celda_tabla_principal" valign=""><div class="letreros_tabla">Buscar Gu&iacute;a</div></td>
		<td align="center" class="celda_tabla_principal celda_boton"><input type="text" name="busqueda" id="busqueda"></td>
		<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">
			<button type="button" valign="center" onclick="document.location='<?php echo $_SERVER['SCRIPT_NAME'] ?>?busqueda='+document.getElementById('busqueda').value"><img src="imagenes/buscar-act.png" height="20"></button></div>			
		</td>
	</tr>
</table>
<br>
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal" valign=""><div class="letreros_tabla">Acciones</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td align="center" class="celda_tabla_principal" valign="center">
    	<div class="letreros_tabla" <?php echo $colorAerolinea ?>>Aerolinea 
    		<button type="button" valign="center" onclick="document.location='<?php echo $_SERVER['SCRIPT_NAME'] ?>?filtro=id_aerolinea'"><img src="imagenes/filtro.png" height="20"></button>
    	</div>
    </td>
    <td align="center" class="celda_tabla_principal" valign="center">
    	<div class="letreros_tabla" <?php echo $colorCourier ?>>Courier 
    		<button type="button" valign="center" onclick="document.location='<?php echo $_SERVER['SCRIPT_NAME'] ?>?filtro=id_consignatario'"><img src="imagenes/filtro.png" height="20"></button>
    	</div>
    </td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">L&iacute;nea</div></td>    
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Veh&iacute;culo</div></td>    
  </tr>
  <?php echo $impresion; ?>
</table>
</body>
</html>
<script language="javascript">
	// función que permite abrir ventanas emergentes con las propiedades deseadas
	function openPopup(url,name,w,h,props,center)
	{
		l=18;t=18
		if(center){l=(screen.availWidth-w)/2;t=(screen.availHeight-h)/2}
		url=url.replace(/[ ]/g,'%20')
		popup=window.open(url,name,'left='+l+',top='+t+',width='+w+',height='+h+',scrollbars=1'+((props)?','+props:''))
		props=props||''
		if(props.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
		popup.focus()
	}
</script>
