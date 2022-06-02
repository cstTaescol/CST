<?php 
session_start(); /*"This product includes PHP software, freely available from
     				      <http://www.php.net/software/>". */
require("config/configuracion.php");
?>
<font color="#FF0000"><strong>(*)</strong></font>
<select name="consignatario" id="consignatario" onfocus="pasar_consignatario(this.value);" onchange="pasar_consignatario(this.value);">
	<option value="">Seleccione Uno</option>
<?php
	$sql="SELECT id,nombre FROM consignatario WHERE estado='A' ORDER BY nombre";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		echo '<option value="'.$fila['id'].'">'.substr($fila['nombre'],0,35).'</option>';
	}
?>
</select>

<button type="button" name="btn_reload_consignatario" id="btn_reload_consignatario" onclick="llamadasin('ajax_consignatario.php', 'dv_consignatario','consignatario')">
    <img src="imagenes/recargar-act.png" title="Recargar"/>
</button>
<button type="button" name="btn_consignatario" id="btn_consignatarios" onClick="openPopup('consignatario_nuevo_pop.php','new','750','400','scrollbars=1',true)">
    <img src="imagenes/agregar-act.png" title="Agregar"/>
</button>