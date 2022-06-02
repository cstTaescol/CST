<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
?>
<select name="agente_carga" id="agente_carga" >
    <option value="" selected="selected">Seleccione Uno</option>
    <?php
        $sql="SELECT id,razon_social FROM agente_carga WHERE estado='A' ORDER BY razon_social";
        $consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
        while($fila=mysql_fetch_array($consulta))
        {
            echo '<option value="'.$fila['id'].'">'.$fila['razon_social'].'</option>';
        }
    ?>
</select>

<button type="button" name="btn__reload_acarga" id="btn__reload_acarga" onclick="llamadasin('ajax_agente_carga.php', 'dv_agente', 'agente')">
    <img src="imagenes/recargar-act.png" title="Recargar"/>
</button>
<button type="button" name="btn_acarga" id="btn_acarga" onClick="openPopup('agente_carga_popup.php','new','750','400','scrollbars=1',true)">
    <img src="imagenes/agregar-act.png" title="Agregar"/>
</button>
