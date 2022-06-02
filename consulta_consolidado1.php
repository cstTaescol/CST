<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
//Discriminacion de aerolinea de usuario TIPO 2
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id_aerolinea = '$id_aerolinea_user'";	
//*************************************
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Buscador de Guias</p>
<form name="buscar" method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
<table align="center">
    <tr>
        <td class="celda_tabla_principal">
            <p class="asterisco">Consolidado No.</p>
        </td>
        <td class="celda_tabla_principal">
			<input name="coincidencia" type="text" id="coincidencia" tabindex="1" size="35" maxlength="20" />   
            <script>document.forms[0].coincidencia.focus();</script>         
        </td>
        <td class="celda_tabla_principal">
			<button type="submit" tabindex="2">
            	<img src="imagenes/buscar-act.png" align="absmiddle" />
            </button>
        </td>        
    </tr>
</table>
</form>
<?php
if (isset($_REQUEST["coincidencia"]))
{
	//Privilegios modificar
	$id_objeto=72; 
	include("config/provilegios_objeto.php");  

	$coincidencia=$_REQUEST["coincidencia"];
	if ($coincidencia != "")
	{
		$sql="SELECT * FROM guia WHERE master LIKE '%$coincidencia%' AND id_tipo_guia='2' AND id_tipo_bloqueo !='8' $sql_aerolinea";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$nfilas=mysql_num_rows($consulta);
		if ($nfilas > 0)
		{
			echo '
			<table align="center">
				<tr>
					<td class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Modificar</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Consolidado</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
					<td class="celda_tabla_principal"><div class="letreros_tabla">Hora</div></td>
				</tr>
			';
	
			while($fila=mysql_fetch_array($consulta))
				{
					$id_aerolinea=$fila["id_aerolinea"];
					$fecha=$fila["fecha_creacion"];
					$hora=$fila["hora"];
					$master=$fila["master"];
					$id_guia=$fila["id"];
					
					//carga dato adicionales
					$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
					$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					$fila2=mysql_fetch_array($consulta2);
					$aerolinea=$fila2["nombre"];
					
					echo "<tr>
							<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
								<button type=\"button\" onClick=\"document.location='consulta_consolidado2.php?id_guia=$id_guia'\">
									<img src=\"imagenes/aceptar-act.png\" title=\"Seleccionar\">
								</button>
							</td>					
							<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
								<button type=\"button\" $activacion onClick=\"document.location='modificar_consolidado.php?id_guia=$id_guia'\">
									<img src=\"imagenes/settings-act.png\" title=\"Modificar\">
								</button>
							</td>					
							<td class=\"celda_tabla_principal celda_boton\"> $master </td>
							<td class=\"celda_tabla_principal celda_boton\"> $aerolinea </td>
							<td class=\"celda_tabla_principal celda_boton\"> $fecha </td>
							<td class=\"celda_tabla_principal celda_boton\"> $hora </td>
						</tr>";
				}
				echo "</table>";
		}
		else
			{
				echo "<p align=\"left\"> <font color=\"red\">No se encontraron resultados que coincidan</font></p>";
			}
	}//cierra el if de consulta en blanco
}
?>
</body>
</html>
