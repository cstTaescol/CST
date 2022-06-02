<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
set_time_limit(0); // Quita el limitante de tiempo para realizar una consulta grande
$impresion="";
$pagina="0";
$paginador=0;
$impresion_paginador="";
$estado_tabla="A";
if(isset($_REQUEST["pagina"]))
	$pagina=$_REQUEST["pagina"];
if(isset($_REQUEST["estado_tabla"]))
	$estado_tabla=$_REQUEST["estado_tabla"];

//Contador de paginas
$sql="SELECT id FROM vehiculo_courier WHERE estado='$estado_tabla'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
$cantidad_paginas=($nfilas/100);
for ($i=0; $i <= $cantidad_paginas; $i++)
{
	$impresion_paginador .= "<option value=\"$paginador\">$i</option>\n";
	$paginador += 100;
}
//*****************
$sql="SELECT v.*, c.nombre FROM vehiculo_courier v LEFT JOIN couriers c ON v.id_consignatario = c.id WHERE v.estado='$estado_tabla' ORDER BY v.placa ASC LIMIT $pagina , 100";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
	{
		$impresion=$impresion."
		<tr>
		  <td class=\"celda_tabla_principal celda_boton\">".$fila['placa']."</td>
		  <td class=\"celda_tabla_principal celda_boton\">".$fila['nombre']."</td>
		  <td class=\"celda_tabla_principal celda_boton\">".$fila['estado']."</td>
		  <td class=\"celda_tabla_principal celda_boton\" align=\"center\">
		  	<button type=\"button\" onclick=\"document.location='cambiar_estado_parametricas.php?tabla=vehiculo_courier&id_registro=".$fila['id']."&estado_actual=".$fila['estado']."'\">X
			</button>	
		  </td>
		  <td bgcolor=\"#FFFFFF\" align=\"center\">
		  	<button type=\"button\" onclick=\"document.location='modificar_parametrica.php?tabla=vehiculo_courier&id_registro=".$fila['id']."'\">Modificar</button>	
		  </td>			  
		</tr>";
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
<p class="titulo_tab_principal">Lista de Veh&iacute;culos Courier</p>
<?php require("paginador_listar.php");?>
<table align="center">
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Placa</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Courier</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Estado</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Des/Activar</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Modificar</div></td>
    </tr>
    <?php echo $impresion; 	?>
</table>
<br />
<hr />
<strong>Estados:</strong>
<em>
<br />A: Activo.
<br />I: Inactivo.
</em>
</body>
</html>
<script language="JavaScript">
	function cambiar_pagina(pagina)
	{
		var estado = document.forms[0].estado_tabla.value;
		document.location='<?php echo $_SERVER['SCRIPT_NAME']; ?>?pagina='+pagina+'&estado_tabla='+estado+'';		
	}
</script>