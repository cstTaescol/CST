<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$aduana=$_GET["aduana"];
$tipodeposito=$_GET["tipodeposito"];
//echo "Aduana-$aduana-";
//echo "tipod-$tipodeposito";
	//cargamos los depositos de acuerdo a la administracion aduanera y al tipo de deposito 
	echo '<font size="-2" color="red">NOMBRE: </font><select name="deposito" id="deposito" onchange="completar(this.value);">';
	echo '<option value="" selected="selected">Seleccione Uno</option>';
	$sql="SELECT id,nombre FROM deposito WHERE estado='A' AND id_admon_aduana = '$aduana' AND id_tipo_deposito = '$tipodeposito' ORDER BY nombre ASC";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
		{
			echo '<option value="'.$fila['id'].'">'.substr($fila['nombre'],0,35).'</option>';
		}
	echo '</select>';
?>
<button type="button" name="btn_deposito" id="btn_deposito" onClick="openPopup('deposito_nuevo_popup.php','new','730','530','scrollbars=0',true)">
    <img src="imagenes/home-act.png" title="Agregar" align="absmiddle"/>
</button>