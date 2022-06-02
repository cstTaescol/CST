<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$impresion="";
$cont="";
$totalpiezas=0;
$totalpeso=0;
$totalvolumen=0;
$totalpiezas_despaletizado=0;
$totalpeso_despaletizado=0;

if(isset($_REQUEST['id_vuelo']))
{
	$id_vuelo=$_REQUEST['id_vuelo'];
}
else
{
	echo "
	<script>
		alert('ERROR:El servidor no pudo obtener los datos necesarios, intente nuevamente');
		document.location='base.php';
	</script>
	";
	exit();
}
//Consulta datos del vuelo
$sql="SELECT * FROM vuelo WHERE id='$id_vuelo'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$nvuelo=$fila["nvuelo"];
$fecha_creacion=$fila["fecha_creacion"];
$id_aerolinea=$fila["id_aerolinea"];

//Carga dato adicionales
$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila2=mysql_fetch_array($consulta2);
$aerolinea=$fila2['nombre'];

//consulta de carga de guias
$sql="SELECT * FROM guia WHERE id_vuelo = '$id_vuelo' AND id_tipo_guia != 2 AND faltante_total = 'N'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
{
	$cont++;
	include("config/evaluador_inconsistencias.php");
	$id_guia=$fila["id"];
	$hija=$fila["hija"];
	$master=$fila["master"];
	include("config/master.php");
	$id_tipo_bloqueo=$fila["id_tipo_bloqueo"];

	//consulta de Valores Despaletizados
	include("lib_despaletizaje_valores.php");
	//**********************************

	$totalpiezas=$totalpiezas+$piezas;
	$totalpeso=$totalpeso+$peso;
	$totalvolumen=$totalvolumen+$volumen;
	$peso=number_format($peso,2,",",".");
	$volumen=number_format($volumen,2,",",".");
	$totalpiezas_despaletizado +=$piezas_recibido;
	$totalpeso_despaletizado +=$peso_recibido;
	$impresion=$impresion.'
	<tr>
		<td class="celda_tabla_principal celda_boton">'.$cont.'</td>
		<td class="celda_tabla_principal celda_boton">'.$master.'</td>
		<td class="celda_tabla_principal celda_boton"><a href="consulta_guia.php?id_guia='.$id_guia .'">'.$hija.'</a></td>
		<td align="right"class="celda_tabla_principal celda_boton">'.$piezas.'</td>
		<td align="right"class="celda_tabla_principal celda_boton">'.$peso.'</td>
		<td align="right"class="celda_tabla_principal celda_boton">
			<input type="text" name="volumen'.$cont.'" id="volumen'.$cont.'" value="'.$volumen.'" size="8" maxlength="8" onKeyPress="return numeric2(event)">
		</td>
		<td align="center" class="celda_tabla_principal celda_boton">
			<button type="button" onclick="guardar('.$cont.','.$id_guia.')" title="Guardar"><img src="imagenes/guardar-act.png" title="Guardar"></button>
		</td>
		<td align="center" class="celda_tabla_principal celda_boton">
			<div id="respuesta'.$cont.'"></div>
		</td>
	</td>
	';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="js/mootools-core-1.4.5-full-nocompat.js"></script>
	<script type="text/javascript" src="js/mootools-more-1.4.0.1.js"></script>
</head>

<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Consulta de Vuelo</p>
<table width="650" align="center" class="decoracion_tabla">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Vuelo</div></td>
    <td class="celda_tabla_principal celda_boton" ><?php echo $nvuelo ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td class="celda_tabla_principal celda_boton" ><?php echo $aerolinea ?></td>
  </tr>
</table>

<table width="850" align="center">
  <tr>
    <td rowspan="2" class="celda_tabla_principal"><div class="letreros_tabla">No.</div></td>
    <td rowspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Master</div></td>
    <td rowspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Gu&iacute;a</div></td>
    <td colspan="3" class="celda_tabla_principal"><div class="letreros_tabla">Manifestado</div></td>    
    <td rowspan="2" colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Acci&oacute;n</div></td>    
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
  </tr>
  <?php echo $impresion; ?>	  
</table>
</body>
</html>
<script type="text/javascript">
//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

function numeric2(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9-.]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
//***********************

//Guardado Asincrono
function guardar(cont,id_guia)
{
	var peticion = new Request(
	{
		url: 'ajax_corregir_volumen.php',
		method: 'post',
		onRequest: function()
		{
			mostrar_div($('respuesta'+cont));
			$('respuesta'+cont).innerHTML='<image src="imagenes/cargando.gif">';
		},			
		onSuccess: function(responseText)
		{
			
			var respuesta=responseText;
			var arreglos = respuesta.split("-");
			var error = eval(arreglos[0]);
			var accion = arreglos[1];
			var mensaje = arreglos[2];
			if (error != 1)
			{
				mostrar_div($('respuesta'+cont));
				$('respuesta'+cont).innerHTML='<image src="imagenes/check_green.png" height="33" width="29" title="'+mensaje+'">';	
			}
			else
			{
				mostrar_div($('respuesta'+cont));
				$('respuesta'+cont).innerHTML='<image src="imagenes/error.png"  height="33" width="29" title="'+mensaje+'">';
			}
			
		},
		onFailure: function()
		{
			mostrar_div($('respuesta'+cont));
			$('respuesta'+cont).innerHTML='<image src="imagenes/error.png"  height="33" width="29" title="Error al enviar los datos">';
		}
	}
	);
	peticion.send('volumen='+$('volumen'+cont).value+'&id_guia='+id_guia);	
}

//Mensaje de Respuesta
function mostrar_div(id_div)
{
	id_div.set('morph',{ 
	duration: 200, 
	transition: 'linear'
	});
	id_div.morph({
		'opacity': 1 
	});
}
</script>