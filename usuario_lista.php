<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
set_time_limit(0); // Quita el limitante de tiempo para realizar una consulta grande
$impresion="";
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
<p class="titulo_tab_principal">Lista de Usuarios</p>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button name="activo" type="button" onclick="document.location='usuario_lista.php'">
                <img src="imagenes/aceptar-act.png" title="Usuarios Activos" width="33" height="29" /><br />
              <strong>Activo</strong>
            </button>
            
            <button name="activo" type="button" onclick="document.location='usuario_lista.php?filtro=I'">
                <img src="imagenes/aceptar-in.png" title="Usuarios Inactivos" width="33" height="29"/><br />
              <strong>Inactivo</strong>
            </button>
      </td>
    </tr>
</table>

<table align="center">
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Identifiacion</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Estado</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Reset Clave</div></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Modificar</div></td>
    <?php 
	//Filtra para Usuarios Activos	
	if(isset($_REQUEST['filtro']))
	{
		$filtro="AND estado = 'I'";
	}
	else
	{
		$filtro="AND estado = 'A'";
	}
	//**********************
	
	//consulta principal
	$sql="SELECT * FROM usuario WHERE id != 1 $filtro ORDER BY nombre ASC";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
		{
			$id_usuario_modificar=$fila['id'];
			$cc=$fila['cc'];
			$nombre=$fila['nombre'];
			$estado=$fila['estado'];
			$id_aerolinea=$fila['id_aerolinea'];
			if ($id_aerolinea=="*")
				$aerolinea="Todas";
			else
			{
				//Consulta adicional
				$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
				$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila2=mysql_fetch_array($consulta2);
				$aerolinea=$fila2['nombre'];
			}
			$impresion=$impresion."
			</tr>
			  <td class=\"celda_tabla_principal celda_boton\">$cc</td>
			  <td class=\"celda_tabla_principal celda_boton\">$nombre</td>
			  <td class=\"celda_tabla_principal celda_boton\">$aerolinea</td>
			  <td class=\"celda_tabla_principal celda_boton\">$estado</td>
			  <td class=\"celda_tabla_principal celda_boton\" align=\"center\">
			  	<button type=\"button\" onclick=\"document.location='usuario_reset_clave.php?id_usuario_modificar=$id_usuario_modificar'\">Reset</button>			  	
			  </td>
			<td class=\"celda_tabla_principal celda_boton\" align=\"center\">
				<button type=\"button\" onclick=\"document.location=document.location='usuario_modificar.php?id_usuario_modificar=$id_usuario_modificar'\">Modificar</button>			  	
			</td>
			<tr>";
		}
	echo $impresion; 
	?>
  </tr>
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