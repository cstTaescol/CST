<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$disposicion=$_REQUEST["disposicion"];
$tipodeposito=$_REQUEST["tipodeposito"];
$admon_aduana=$_REQUEST["admon_aduana"];

if ($disposicion==12 or $disposicion==16 or $disposicion==17 or $disposicion==24)
{
	/* Para las dispociciones de cabotaje:
		 -Cabotaje
		 -Cabotaje Especial
		 -Trancito Nacional
		 -Continuacion de viaje
		Para estas dispociciones de carga es necesario cargar de nuevo los sitios de aduanas y seleccionar el deposito basandose en la ciudad destino 
	 */
	echo '
		<font size="-2" color="red">Aduana Destno: </font>
		<select name="aduanas" id="aduanas" onchange="nueva_aduana(this.value)">
			<option value="" selected="selected">Seleccione Uno</option>
		';
	$sql="SELECT id,nombre FROM admon_aduana WHERE estado='A' ORDER BY nombre";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
		{
			echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
		}
	echo '</select>';
}
else
{
	//cargamos los depositos de acuerdo a la administracion aduanera y al tipo de deposito 
	echo '<font size="-2" color="red">Nombre: </font>
			<select name="deposito" id="deposito" onchange="pasar_deposito(this.value);">
				<option value="">Seleccione Uno</option>
			';
	$sql="SELECT id,nombre FROM deposito WHERE estado='A' AND id_admon_aduana = '$admon_aduana' AND id_tipo_deposito = '$tipodeposito' ORDER BY nombre ASC";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
		{
			echo '<option value="'.$fila['id'].'">'.substr($fila['nombre'],0,35).'</option>';
		}
	echo '</select>';
	?>
    <button type="button" onClick="openPopup('deposito_nuevo_popup.php','new','730','520','scrollbars=0',true)">
    	<img src="imagenes/home-act.png" align="absmiddle" title="Agregue un nuevo DEPOSITO" />
    </button>     
    <?php
}
?>