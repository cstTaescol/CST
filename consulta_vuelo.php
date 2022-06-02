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
$hora_estimada=$fila["hora_estimada"];
$nmanifiesto=$fila["nmanifiesto"];
$hora_manifiesto=$fila["hora_manifiesto"];
$hora_llegada=$fila["hora_llegada"];
$hora_fin_descargue=$fila["hora_fin_descargue"];
$hora_finalizado=$fila["hora_finalizado"];
$id_ruta=$fila["id_ruta"];
$fecha_manifiesto=$fila["fecha_manifiesto"];
$fecha_arribo=$fila["fecha_arribo"];
$fecha_fin_descargue=$fila["fecha_fin_descargue"];
$fecha_finalizacion=$fila["fecha_finalizacion"];
$cod_pais_embarque=$fila["cod_pais_embarque"];
$cod_ciudad_embarque=$fila["cod_ciudad_embarque"];
$usuario_manifesto=$fila["usuario_manifesto"];
$usuario_arribo=$fila["usuario_arribo"];
$usuario_findescargue=$fila["usuario_findescargue"];
$id_usuario_finalizador=$fila["id_usuario_finalizador"];


//carga dato adicioenales
$sql2="SELECT nombre FROM pais WHERE codigo='$cod_pais_embarque'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila2=mysql_fetch_array($consulta2);
$pais=$fila2['nombre'];

//carga dato adicioenales
$sql2="SELECT nombre FROM ciudad_embarque WHERE id='$cod_ciudad_embarque'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila2=mysql_fetch_array($consulta2);
$ciudad=$fila2['nombre'];

//carga dato adicioenales
$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 4: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila2=mysql_fetch_array($consulta2);
$aerolinea=$fila2['nombre'];

//carga dato adicioenales
$sql2="SELECT descripcion FROM ruta WHERE id='$id_ruta'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 5: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila2=mysql_fetch_array($consulta2);
$ruta=$fila2['descripcion'];

//carga dato adicioenales
$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 6: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila2=mysql_fetch_array($consulta2);
$aerolinea=$fila2['nombre'];

//carga dato adicioenales
$sql2="SELECT nombre FROM usuario WHERE id='$usuario_manifesto'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila2=mysql_fetch_array($consulta2);
$usuario_manifesto=$fila2['nombre'];

//carga dato adicioenales
$sql2="SELECT nombre FROM usuario WHERE id='$usuario_arribo'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 8: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila2=mysql_fetch_array($consulta2);
$usuario_arribo=$fila2['nombre'];

//carga dato adicioenales
$sql2="SELECT nombre FROM usuario WHERE id='$usuario_findescargue'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 9: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila2=mysql_fetch_array($consulta2);
$usuario_findescargue=$fila2['nombre'];

//carga dato adicioenales
$sql2="SELECT nombre FROM usuario WHERE id='$id_usuario_finalizador'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 10: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila2=mysql_fetch_array($consulta2);
$id_usuario_finalizador=$fila2['nombre'];



//Consulta guias asociadas al vuelo
if(isset($_REQUEST['tipo_guias']))
{
	$tipo_guias=$_REQUEST['tipo_guias'];
	switch ($tipo_guias)
	{
		case("Todas"):
			$clausula="";
		break;
		case("Manifestadas"):
			$clausula="AND id_tipo_bloqueo = 2";
		break;
		case("Finalizadas por Inconsistencias"):
			$clausula="AND (id_tipo_bloqueo = 3 OR id_tipo_bloqueo = 4 OR id_tipo_bloqueo = 5 OR id_tipo_bloqueo = 6 OR id_tipo_bloqueo = 9 OR id_tipo_bloqueo = 10)";
		break;
	}
}
else
{
	$clausula="";
}


$sql="SELECT * FROM guia WHERE id_vuelo = '$id_vuelo' AND id_tipo_guia != 2 AND faltante_total = 'N' $clausula";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 7: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
{
	$cont++;
	include("config/evaluador_inconsistencias.php");
	$id_guia=$fila["id"];
	$hija=$fila["hija"];
	$master=$fila["master"];
	include("config/master.php");
	$id_tipo_bloqueo=$fila["id_tipo_bloqueo"];
	//carga dato adicioenales
	$sql2="SELECT nombre FROM tipo_bloqueo_guia WHERE id='$id_tipo_bloqueo'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 8: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$estado=$fila2['nombre'];

	//consulta de Valores Despaletizados
	include("lib_despaletizaje_valores.php");
	//**********************************

  //consulta de Valores Despaletizados
  include("lib_valores_sobrantes_vuelo.php");
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
		<td align="right"class="celda_tabla_principal celda_boton">'.$volumen.'</td>
		<td align="right"class="celda_tabla_principal celda_boton">'.$piezas_recibido.'</td>
		<td align="right"class="celda_tabla_principal celda_boton">'.number_format($peso_recibido,2,",",".").'</td>
		<td align="center" class="celda_tabla_principal celda_boton">'.$estado.'</td>
	</td>
	';
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Datos de Origen</div></td>
    <td class="celda_tabla_principal celda_boton" >
		Pais: <?php echo $pais ?><br />
        Ciudad: <?php echo $ciudad ?><br />
        Ruta: <?php echo $ruta ?>        
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha Creacion</div></td>
    <td class="celda_tabla_principal celda_boton" ><?php echo $fecha_creacion ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Hora Estimada de Llegada</div></td>
    <td class="celda_tabla_principal celda_boton" ><?php echo $hora_estimada ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Datos de Manifiesto</div></td>
    <td class="celda_tabla_principal celda_boton" align="center" >
    	<div class="letreros_tabla">No: <?php echo $nmanifiesto ?></div>
		<div class="letreros_tabla">Fecha y Hora</div> <?php echo "$fecha_manifiesto / $hora_manifiesto <br> $usuario_manifesto" ?>         
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Datos de Llegada</div></td>
    <td class="celda_tabla_principal celda_boton" align="center">
		<div class="letreros_tabla">Fecha y Hora</div> <?php echo "$fecha_arribo / $hora_llegada <br> $usuario_arribo" ?>
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Datos de Finalizacion Descargue</div></td>
    <td class="celda_tabla_principal celda_boton" align="center">
		<div class="letreros_tabla">Fecha y Hora</div> <?php echo "$fecha_fin_descargue / $hora_fin_descargue <br> $usuario_findescargue" ?><br />         
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Datos de Finalizacion Vuelo</div></td>
    <td class="celda_tabla_principal celda_boton" align="center">
		<div class="letreros_tabla">Fecha y Hora</div> <?php echo "$fecha_finalizacion / $hora_finalizado <br> $id_usuario_finalizador" ?><br />         
    </td>
  </tr>
</table>

<table width="850" align="center">
  <tr>
    <td rowspan="2" class="celda_tabla_principal"><div class="letreros_tabla">No.</div></td>
    <td rowspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Master</div></td>
    <td rowspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td colspan="3" class="celda_tabla_principal"><div class="letreros_tabla">Manifestado</div></td>
    <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Despaletizado</div></td>
    <td rowspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Estado</div></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
  </tr>
  <?php echo $impresion; ?>	  
</table>
<hr>
<table width="850" align="center">
  <tr>
    <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Totales del Vuelo</div></td>
  </tr>
  <tr>
    <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Manifestado</div></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Piezas:</div></td>
    <td class="celda_tabla_principal celda_boton" align="right"><?php echo $totalpiezas ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Peso:</div></td>
    <td class="celda_tabla_principal celda_boton" align="right"><?php echo number_format($totalpeso,2,",",".") ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Volumen:</div></td>
    <td class="celda_tabla_principal celda_boton" align="right"><?php echo number_format($totalvolumen,2,",",".") ?></td>
  </tr>
  <tr>
    <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Despaletizado</div></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Piezas:</div></td>
    <td class="celda_tabla_principal celda_boton" align="right"><?php echo $totalpiezas_despaletizado ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Peso:</div></td>
    <td class="celda_tabla_principal celda_boton" align="right"><?php echo number_format($totalpeso_despaletizado,2,",",".") ?></td>
  </tr>
  <tr>
    <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Sobrantes</div></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Piezas:</div></td>
    <td class="celda_tabla_principal celda_boton" align="right"><?php echo $piezasSobrantes ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Peso:</div></td>
    <td class="celda_tabla_principal celda_boton" align="right"><?php echo number_format($pesoSobrante,2,",",".") ?></td>
  </tr>

</table>
</body>
</html>