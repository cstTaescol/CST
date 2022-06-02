<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$cod_pais=$_GET["cod_pais"];
$sql="SELECT id,nombre FROM ciudad_embarque WHERE estado='A' AND cod_pais ='$cod_pais' ORDER BY nombre ASC";
?>
<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="86"><font color="#000000">CIUDAD</font></td>
    <td>
        <select name="ciudad_origen" onchange="pasar_ciudad_origen(this.value)">
            <option value="" selected="selected">Seleccione Uno</option>
            <?php
                $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                while($fila=mysql_fetch_array($consulta))
                {
                    echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                }
            ?>
        </select>
    </td>
  </tr>
</table>