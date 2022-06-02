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
$sql="SELECT id FROM courier_funcionario WHERE estado='$estado_tabla'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nfilas=mysql_num_rows($consulta);
$cantidad_paginas=($nfilas/100);
for ($i=0; $i <= $cantidad_paginas; $i++)
{
	$impresion_paginador .= "<option value=\"$paginador\">$i</option>\n";
	$paginador += 100;
}
//*****************
$sql="SELECT * FROM courier_funcionario WHERE estado='$estado_tabla' AND id_entidad !='6' AND id_entidad !='7' ORDER BY nombre ASC LIMIT $pagina , 100";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
	{
		//consulta de courier
		$id_entidad=$fila['id_entidad'];
		$sql2="SELECT nombre FROM courier_entidades WHERE id='$id_entidad'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");		
		$fila2=mysql_fetch_array($consulta2);
		$entidad=$fila2['nombre'];

		$impresion=$impresion."
		<tr>
		  <td class=\"celda_tabla_principal celda_boton\">".$entidad."</td>
		  <td class=\"celda_tabla_principal celda_boton\">".$fila['nombre']."</td>
		  <td class=\"celda_tabla_principal celda_boton\">".$fila['estado']."</td>
		  <td class=\"celda_tabla_principal celda_boton\" align=\"center\">
		  	<button type=\"button\" onclick=\"document.location='cambiar_estado_parametricas.php?tabla=courier_funcionario_entidad&id_registro=".$fila['id']."&estado_actual=".$fila['estado']."'\">X
			</button>	
		  </td>
		  <td bgcolor=\"#FFFFFF\" align=\"center\">
		  	<button type=\"button\" onclick=\"document.location='modificar_parametrica.php?tabla=courier_funcionario_entidad&id_registro=".$fila['id']."'\">Modificar</button>	
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
<p class="titulo_tab_principal">Lista de Personal del Courier</p>
<?php require("paginador_listar.php");?>
<table align="center">
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Courier</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
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