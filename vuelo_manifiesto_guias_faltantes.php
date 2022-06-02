<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

$id_vuelo=$_GET['id_vuelo'];
$id_aerolinea=$_GET['id_aerolinea'];
// identificacion de datos
$sql="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$aerolinea=$fila["nombre"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />
	<title>GUIAS FALTANTES</title>
</head>

<body>
<p class="titulo_tab_principal">Guias Faltanes</p>
<p class="asterisco" align="center"><?php echo $aerolinea ?></p>
<hr />
<table width="100%" align="center">
  <tr>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Consolidado</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Destino</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
  </tr>
<?php

//carga de guias sobrantes
$sql_add="SELECT * FROM inconsistencias WHERE estado = 'A'";
$consulta_add=mysql_query ($sql_add,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila_add=mysql_fetch_array($consulta_add))
{
	$id_guia=$fila_add["id_guia"];
	$sql="SELECT master,hija,id_deposito,id_tipo_guia,id_disposicion,peso_faltante,piezas_faltantes,id FROM guia WHERE id_tipo_guia != '2' AND id_aerolinea='$id_aerolinea' AND id='$id_guia' ORDER BY hija ASC";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$nguias=mysql_num_rows($consulta);
	if ($nguias > 0)
	{
		for ($i=1; $i<=$nguias; $i++)
		{
			
			$fila=mysql_fetch_array($consulta);
			//identificacion 
			$hija=$fila["hija"];
			$master=$fila["master"];
			require("config/master.php");
			if ($master=="")
				$master="-";
	
			$id_disposicion=$fila["id_disposicion"];
			//Evaluar si la disposicion no exige ningun tipo de deposito
			if ($id_disposicion ==28 || $id_disposicion ==21 || $id_disposicion ==20 || $id_disposicion ==19 || $id_disposicion ==25 || $id_disposicion ==29 || $id_disposicion ==23 || $id_disposicion ==13 || $id_disposicion ==15)
				{
					$sql3="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
					$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					$fila3=mysql_fetch_array($consulta3);
					$destino=$fila3["nombre"];
				}
			else
			{
				$id_deposito=$fila["id_deposito"];
				$sql3="SELECT nombre FROM deposito WHERE id='$id_deposito'";
				$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila3=mysql_fetch_array($consulta3);
				$destino=$fila3["nombre"];
			}
			
			if ($id_disposicion == 26 or $id_disposicion == 27) 
				{
					$destino="Bodega Dian";
					$color='color="green"';
				}
			$id_guia=$fila["id"];
			$piezas=$fila_add["variacion_piezas"];
			$peso=number_format($fila_add["variacion_peso"],2,",",".");
			echo "
			  <tr>
				<td align=\"center\" class=\"celda_tabla_principal celda_boton\"><strong>$master</strong></td>
				<td align=\"center\" class=\"celda_tabla_principal celda_boton\"><strong>$hija</strong></td>
				<td align=\"center\" class=\"celda_tabla_principal celda_boton\"><strong>$piezas</strong></td>
				<td align=\"center\" class=\"celda_tabla_principal celda_boton\"><strong>$peso</strong></td>
				<td align=\"center\" class=\"celda_tabla_principal celda_boton\"><strong>$destino</strong></td>
				<td align=\"center\" class=\"celda_tabla_principal celda_boton\">
					<button type=\"button\" onclick=\"document.location='vuelo_manifiesto_guias_faltantes_salvar.php?id_guia=$id_guia&vuelo=$id_vuelo&aerolinea=$id_aerolinea'\">
						<img src=\"imagenes/aceptar-act.png\" height=\"33\" width=\"29\" title=\"Seleccionar Todos\" />
					</button>
				</td>
			  </tr>
			";
		}
	}
}
?>
</table>
</body>
</html>