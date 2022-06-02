<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
$id_departamento=$_GET["id_departamento"];
$sql="SELECT codigo,nombre FROM ciudad_destino WHERE estado='A' AND cod_departamento ='$id_departamento' ORDER BY nombre ASC";
?>
<table width="360" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="160"><font color="#FFFFFF">CIUDAD</font></td>
    <td>
    <select name="ciudad" onchange="pasar_ciudad(this.value)">
	<option value="" selected="selected">Seleccione Uno</option>
  	<?php
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		while($fila=mysql_fetch_array($consulta))
		{
			echo '<option value="'.$fila['codigo'].'">'.$fila['nombre'].'</option>';
		}
    ?>
	</select>
    </td>
  </tr>
</table>