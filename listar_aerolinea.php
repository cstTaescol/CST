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
$sql="SELECT * FROM aerolinea WHERE estado='$estado_tabla'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1 ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
$cantidad_paginas=($nfilas/100);
for ($i=0; $i <= $cantidad_paginas; $i++)
{
	$impresion_paginador .= "<option value=\"$paginador\">$i</option>\n";
	$paginador += 100;
}
//*****************
$sql="SELECT * FROM aerolinea WHERE estado='$estado_tabla' ORDER BY nombre ASC LIMIT $pagina , 100";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
	{		
		$tipo_importacion=$fila['importacion'];
		$tipo_courier=$fila['courier'];
		
		if ($tipo_importacion == true)
		{
			if ($tipo_courier == true)
			{
				$tipo="Importacion<br>Courier";				
			}
			else
			{
				$tipo="Importacion";		
			}
		}
		else
		{
			if ($tipo_courier == true)
			{
				$tipo="Courier";				
			}			
		}
		$impresion=$impresion."
		<tr>
		  <td class=\"celda_tabla_principal celda_boton\">".$fila['nit']."</td>
		  <td class=\"celda_tabla_principal celda_boton\">".$fila['nombre']."</td>
		  <td class=\"celda_tabla_principal celda_boton\">".$fila['telefono1']." - ".$fila['telefono2']."</td>
		  <td class=\"celda_tabla_principal celda_boton\">".$fila['direccion']."</td>
		  <td class=\"celda_tabla_principal celda_boton\">".$fila['contacto']."</td>
		  <td class=\"celda_tabla_principal celda_boton\">".$fila['email']."</td>
		  <td class=\"celda_tabla_principal celda_boton\">".$tipo."</td>
		  <td class=\"celda_tabla_principal celda_boton\">".$fila['estado']."</td>
		  <td class=\"celda_tabla_principal celda_boton\" align=\"center\">
			<button type=\"button\" onclick=\"document.location='cambiar_estado_parametricas.php?tabla=aerolinea&id_registro=".$fila['id']."&estado_actual=".$fila['estado']."'\">X
			</button>		  	
		  </td>
		  <td bgcolor=\"#FFFFFF\" align=\"center\">
			<button type=\"button\" onclick=\"document.location='modificar_parametrica.php?tabla=aerolinea&id_registro=".$fila['id']."'\">Modificar</button>							  		  </td>					  
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
<p class="titulo_tab_principal">Lista de Aerolineas</p>
<?php require("paginador_listar.php");?>
<table align="center">
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Nit</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Direccion</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Contacto</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">E-mail</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Estado</div></td>
      <td  class="celda_tabla_principal"><div class="letreros_tabla">Des/Activar</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Modificar</div></td>
  </tr>
  <?php echo $impresion; ?>
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