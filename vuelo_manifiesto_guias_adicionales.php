<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

$id_vuelo=$_GET['id_vuelo'];
$id_aerolinea=$_GET['id_aerolinea'];
$habilitacion="disabled=\"disabled\"";
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
	<title>GUIAS SIN ASIGNAR</title>
</head>

<body>
<p class="titulo_tab_principal">Guias Sin Asignar</p>
<p class="asterisco" align="center"><?php echo $aerolinea ?></p>
<hr />
<form name="guias_nuevas" action="vuelo_manifiesto_guias_adicionales_salvar.php" method="post">
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
$sql="SELECT master,hija,piezas,peso,id,id_deposito,id_tipo_bloqueo,id_tipo_guia,id_disposicion FROM guia WHERE id_tipo_guia != '2' AND id_aerolinea='$id_aerolinea' AND id_tipo_bloqueo='1' AND (ISNULL(id_vuelo) OR id_vuelo='') ORDER BY id_aerolinea,master,id_deposito ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nguias=mysql_num_rows($consulta);
if ($nguias > 0)
{
	for ($i=1; $i<=$nguias; $i++)
	{
		$habilitacion="";
		$color='';
		$fila=mysql_fetch_array($consulta);
		//identificacion 
		$hija=$fila["hija"];
		$master=$fila["master"];
		require("config/master.php");
		if ($master=="")
			$master="-";
		
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];	
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
		echo '<tr>
				<td align="left" class="celda_tabla_principal celda_boton">'.$master.'</td>
				<td align="left" class="celda_tabla_principal celda_boton">'.$hija.'</td>
				<td align="center" class="celda_tabla_principal celda_boton">'.$piezas.'</td>
				<td align="center" class="celda_tabla_principal celda_boton">'.$peso.'</td>
				<td align="left" class="celda_tabla_principal celda_boton"><font size="-1" '.$color.'>'.$destino.'</font></td>
				<td align="center" class="celda_tabla_principal celda_boton">
					<input type="checkbox" name="chkacepto'.$i.'" value="'.$id_guia.'"/>
				</td>
			  </tr>';
	}
}
?>
</table>
<input type="hidden" name="contador" value="<?php echo $i ?>" />
<input type="hidden" name="aerolinea" value="<?php echo $id_aerolinea ?>" />
<input type="hidden" name="vuelo" value="<?php echo $id_vuelo ?>" />

<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
        <button type="reset" name="reset" id="reset"> <img src="imagenes/descargar-act.png" alt="" title="Limpiar" /></button>
        <button type="submit" name="guardar" id="guardar" <?php echo $habilitacion ?>> <img src="imagenes/guardar-act.png" alt="" title="Guardar" /> </button>
      </td>
    </tr>
</table>
</form>
</body>
</html>