<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
?>
<font color="#FF0000"><strong>(*)</strong></font>
<select name="embarcador" id="embarcador" tabindex="5" onfocus="pasar_embarcador(this.value);" onchange="pasar_embarcador(this.value);">
  <option value="" selected="selected">Seleccione Uno</option>
  <?php
            $sql="SELECT id,nombre FROM embarcador WHERE estado='A' ORDER BY nombre";
            $consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
            while($fila=mysql_fetch_array($consulta))
            {
                echo '<option value="'.$fila['id'].'">'.substr($fila['nombre'],0,35).'</option>';
            }
        ?>
</select>

<button type="button" name="btn__reload_embarcador" id="btn__reload_embarcador" onclick="llamadasin('ajax_embarcador.php', 'dv_embarcador', 'embarcador')">
    <img src="imagenes/recargar-act.png" title="Recargar"/>
</button>
<button type="button" name="btn_embarcador" id="btn_embarcador" onClick="openPopup('embarcador_nuevo_popup.php','new','750','400','scrollbars=1',true)">
    <img src="imagenes/agregar-act.png" title="Agregar"/>
</button>
