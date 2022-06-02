<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$cont=0;
$impresion="";
$piezas_ubicado="";
$peso_ubicado="";
//Discriminacion de aerolinea de usuario TIPO 3
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND v.id_aerolinea = '$id_aerolinea_user'";	
//*************************************

//Si se realiza una busqueda de guia
if(isset($_REQUEST['guia']))
{
	$guia=$_REQUEST['guia'];
	if ($guia != "")
		$sql_filtro="AND (g.hija LIKE '%$guia%')";
	else
		$sql_filtro="";
}
else
{
	$sql_filtro="";
}
//************************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
input{
	border-width:1px;
	border-color:#36C;
	border-style:solid;
	padding-left:3px;
	background:#EFEFEF;
}
</style>
<script language="javascript">
//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9\n]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
function numeric2(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9-.\n]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
</script>
</head>
<body>
<?php 
include("menu.php");
//Privilegios Consultar Todo el Modulo
$id_objeto=84; 
include("config/provilegios_modulo.php");  
//---------------------------

$sql="SELECT v.id,v.nvuelo,v.estado,g.* FROM vuelo v LEFT JOIN guia g ON v.id = g.id_vuelo WHERE (v.estado = 'F' OR v.estado = 'D' OR v.estado = 'M' OR v.estado = 'L') AND (g.id_tipo_bloqueo = '2' OR g.id_tipo_bloqueo = '3' OR g.id_tipo_bloqueo = '5' OR g.id_tipo_bloqueo = '6' OR g.id_tipo_bloqueo = '9' OR g.id_tipo_bloqueo = '10') AND (g.id_tipo_guia != '2') $sql_aerolinea $sql_filtro ORDER BY g.hija ASC";

$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
{
	//VUELVE EL CONTADOR DE UBICACIONES A 0
	$piezas_ubicado="";
	$peso_ubicado="";
	$impresion_off=false;
	//*************************************
	$id_guia=$fila['id'];
	$hija=$fila['hija'];
	$nvuelo=$fila['nvuelo'];
	include("config/evaluador_inconsistencias.php"); //calcula y general el valor de $piezas, $peso y $volumen luego de las inconsistencias.		
	$piezas_pendientes_ubicar=$piezas-$fila["piezas_despacho"];
	$peso_pendientes_ubicar=$peso-$fila["peso_despacho"];	
	
	//Calculas los valores previamente ubicados en Bodega
	$sql_ubicacion="SELECT piezas, peso FROM posicion_carga WHERE id_guia = '$id_guia'";
	$consulta_ubicacion=mysql_query ($sql_ubicacion,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila_ubicacion=mysql_fetch_array($consulta_ubicacion))
	{
		$piezas_ubicado=$piezas_ubicado+$fila_ubicacion["piezas"];
		$peso_ubicado=$peso_ubicado+$fila_ubicacion["peso"];
	}
	//Se compara para mostrar o no el registro
	$piezas_pendientes_ubicar=$piezas_pendientes_ubicar-$piezas_ubicado;
	$peso_pendientes_ubicar=$peso_pendientes_ubicar-$peso_ubicado;
	if ($piezas_pendientes_ubicar == 0)
		$impresion_off=true;
	
	
	if ($fila["id_tipo_bloqueo"] == 2)
	{
		//$impresion_off=true; //Programacion diseñada unicamente para modulos CON DESPALETIZAJE
		$msg_estado="SIN INCONSISTENCIAS"; //Programacion diseñada unicamente para modulos SIN DESPALETIZAJE
	}
	else
		$msg_estado="EN BODEGA";
		
	// Identificacion de Destino
	$id_disposicion=$fila["id_disposicion"];
	include("lib_destino_guia.php");
	//************************************		
		
	
	if ($impresion_off==false)
		$impresion=$impresion.
			' <tr>
				<form action="ubicacion_seleccionar_bodega.php" method="post">
					<td align="center" class="celda_tabla_principal celda_boton">'.$msg_estado.'</td>
					<td align="center" class="celda_tabla_principal celda_boton">'.$nvuelo.'</td>
					<td align="right" class="celda_tabla_principal celda_boton">
						<a href="consulta_guia.php?id_guia='.$id_guia.'">'.$hija.'</a><input name="id_guia" type="hidden" size="10" maxlength="10" value="'.$id_guia.'" />
					</td>
					<td align="right" class="celda_tabla_principal celda_boton">'.$destino.'</td>
					<td align="center" class="celda_tabla_principal celda_boton">
						<input name="piezas" type="text" size="10" maxlength="10" value="'.$piezas_pendientes_ubicar.'" onKeyPress="return numeric(event)"/>
					</td>
					<td align="center" class="celda_tabla_principal celda_boton">
						<input name="peso" type="text" size="10" maxlength="10" value="'.$peso_pendientes_ubicar.'" onKeyPress="return numeric2(event)"/>
					</td>
					<td align="center" class="celda_tabla_principal celda_boton">
						<input name="observaciones" type="text" size="30" value="" />
					</td>
					<td align="center" class="celda_tabla_principal celda_boton">
						<input name="fondo" type="checkbox" value="F" />
					</td>					
					<td align="center" class="celda_tabla_principal celda_boton">
						<input name="evento" type="hidden" value="insertar" />
						<button type="submit">
							<img src="imagenes/home-act.png" title="Ubicar" />
						</button>
						
					</td>
				</form>
			  </tr>
			  ';
}
?>
<p class="titulo_tab_principal">Guias Para Ubicar</p>
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post">
<table width="450" align="center">
    <tr>
		<td colspan="3" align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
    	<td class="celda_tabla_principal">No. Gu&iacute;a</td>
        <td class="celda_tabla_principal celda_boton"><input type="text" name="guia" id="guia" size="20"></td>
        <td align="center" valign="middle" class="celda_tabla_principal celda_boton">            
            <button name="activo" type="submit">
                <img src="imagenes/buscar-act.png" title="Buscar la Guia" width="33" height="29"/><br />
              	<strong>Buscar</strong>
            </button>
        </td>
    </tr>
</table>
</form>
<table align="center">
  <tr>
  	<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Estado</div></td>
  	<td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Vuelo</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Destino</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Fondo</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
  </tr>  
  <?php echo $impresion; ?>
</table>
<div id="cargando"><p align="center"><img src="imagenes/cargando.gif" width="20" height="21" /><br>Procesando</p></div>
<script language="javascript">
	document.getElementById("cargando").innerHTML="";
</script>
</body>
</html>
