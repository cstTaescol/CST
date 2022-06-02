<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$evento=$_REQUEST["evento"];
$impresion="";
$hijabuffer="";

//Discriminacion de aerolinea de usuario TIPO 4
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="WHERE g.id_aerolinea = '$id_aerolinea_user'";	
//*************************************

//Si se realiza una busqueda de guia
if(isset($_REQUEST['guia']))
{
	$guia=$_REQUEST['guia'];
	if ($guia != "")
	{
		if ($sql_aerolinea == "")
			$sql_filtro="WHERE (g.hija LIKE '%$guia%')";
		else
			$sql_filtro="AND (g.hija LIKE '%$guia%')";
	}
	else
		$sql_filtro="";
}
else
{
	$sql_filtro="";
}
//************************************

switch ($evento)
{
	case "d":
		$url_destino="ubicacion4.php";
	break;
	
	case "consulta":
		$url_destino="ubicacion_celda.php";
	break;
	
	default:
		$url_destino="ubicacion_mapa.php";
	break;
}

$impresion='<table width="300" align="center" class="decoracion_tabla">';
$sql="SELECT p.*,g.hija,g.id FROM posicion_carga p LEFT JOIN guia g ON p.id_guia=g.id $sql_aerolinea $sql_filtro ORDER BY g.hija ASC";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
while($fila=mysql_fetch_array($consulta))
	{
		$id_posicion=$fila["id_posicion"];
		//Identificacion de Posiciones.
		$sql_posiscion="SELECT * FROM posicion WHERE id='$id_posicion'";
		$consulta_posicion=mysql_query ($sql_posiscion,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_posicion=mysql_fetch_array($consulta_posicion);
		$plaqueta=$fila_posicion['rack']."-".$fila_posicion['seccion']."-".$fila_posicion['nivel']."-".$fila_posicion['lado'];
		$hija=$fila['hija'];
		$id_guia=$fila['id'];
		if ($hija == $hijabuffer) // Cuando existen varias ubicaciones para la misma guia.
		{
			$impresion=$impresion. "<tr class=\"celda_tabla_principal celda_boton\">
										<td align=\"center\" colspan=\"2\">
											<a href=\"$url_destino?id_posicion=$id_posicion\">$plaqueta</a>
										</td>
									</tr>";
		}
		else
		{
			$impresion=$impresion. "<tr class=\"celda_tabla_principal\">
										<td><strong>GUIA:</strong>$hija</td>
										<td align=\"center\">
											<button onClick=\"document.location='ubicacion_seleccionar_bodega.php?evento=$evento&id_guia=$id_guia'\">
												<img src=\"imagenes/home-act.png\" width=\"40\" title=\"Ver Guia $hija en el Mapa \" /><br>Mapa
											</button>
										</td>
									</tr>
									<tr class=\"celda_tabla_principal\">
										<td align=\"center\" colspan=\"2\">
											POSICIONES
										</td>
									</tr>
									<tr>
										<td align=\"center\" colspan=\"2\" class=\"celda_tabla_principal celda_boton\">
											<a href=\"$url_destino?id_posicion=$id_posicion\">$plaqueta</a>
										</td>
									</tr>
									";
		}
		$hijabuffer=$hija;
	}
$impresion=$impresion."</table>";
?>
<html>
<head>
<style type="text/css">
<!--
.isla {
	color: #0FC;
}
.seccion {
	color: #9F3;
}
.nivel {
	color: #FF6;
}
.lado {
	color: #9FF;
}
-->
</style>
</head>
<body>
<p>
<?php 
include("menu.php");?>
<p class="titulo_tab_principal">Tabla de Guias en Bodega</p>
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post">
<table width="450" align="center">
    <tr>
		<td colspan="3" align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
    	<td class="celda_tabla_principal">No. Gu&iacute;a</td>
        <td class="celda_tabla_principal celda_boton">
        	<input type="text" name="guia" id="guia" size="20">
            <input type="hidden" name="evento" id="evento" value="<?php echo $evento; ?>">
        </td>
        <td align="center" valign="middle" class="celda_tabla_principal celda_boton">            
            <button name="activo" type="submit">
                <img src="imagenes/buscar-act.png" title="Buscar la Guia" width="33" height="29"/><br />
              	<strong>Buscar</strong>
            </button>
        </td>
    </tr>
</table>
</form>

<div id="cargando"><p align="center"><img src="imagenes/cargando.gif" width="20" height="21" /><br>Procesando</p></div>
<table width="300" align="center" class="celda_tabla_principal">
  <tr>
    <td align="center"><strong>POSICIONES EN BODEGA</strong></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal celda_boton" align="center">
    	<i>Descipci&oacute;n de Ubicaciones:</i><br />
        Isla-Seccion-Nivel-Lado
    </td>
  </tr>
</table>
<br>
<?php 
echo $impresion;
?>
<p>
</p>
<script language="javascript">
	document.getElementById("cargando").innerHTML="";
</script>
</p>
</body>
</html>