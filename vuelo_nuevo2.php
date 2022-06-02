<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$total_peso=0;
$total_volumen=0;
$total_piezas=0;
//Activar o desactivar todas las guia
$activaciones="";
$desactivaciones="";
//***********************************
if(isset($_REQUEST["nvuelo"]))
	{
		//1. obtener datos del vuelo
		$hh_estimada=$_REQUEST["hh_estimada"];
		$mm_estimada=$_REQUEST["mm_estimada"];
		$ss_estimada=$_REQUEST["ss_estimada"];
		$hora_estimada=$hh_estimada.":".$mm_estimada.":".$ss_estimada;
		$id_aerolinea=$_REQUEST["aerolinea"];
		$ruta=$_REQUEST["ruta"];
		$nvuelo=strtoupper($_REQUEST["nvuelo"]);
		$matricula=strtoupper($_REQUEST["matricula"]);

		//carga datos
		$sql="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila=mysql_fetch_array($consulta);
		$naerolinea=$fila['nombre'];
		
		//carga datos
		$sql="SELECT descripcion FROM ruta WHERE id='$ruta'";
		$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila=mysql_fetch_array($consulta);
		$descripcion=$fila['descripcion'];		
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
<p class="titulo_tab_principal">Creacion de Vuelo</p>
<form name="ingresovuelo" method="post" action="vuelo_nuevo3.php">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $naerolinea ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Ruta</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $descripcion ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Vuelo</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $nvuelo ?></td>
  </tr>
  <tr>
     <td class="celda_tabla_principal"><div class="letreros_tabla">Matricula</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $matricula ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Hora de Llegada</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $hora_estimada ?></td>
  </tr>
</table>
<table align="center" >
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Consolidado</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Destino</div></td>
   	<td class="celda_tabla_principal">
    	<button type="button" onclick="seleccionar();">
        	<img src="imagenes/aceptar-act.png" height="33" width="29" title="Seleccionar Todos" />
        </button>
        <button type="button" onclick="deseleccionar();">
        	<img src="imagenes/aceptar-in.png" height="33" width="29" title="Quitar todas las Selecciones" />
        </button>   		
   </td>
  </tr>
<?php
$sql="SELECT master,hija,piezas,peso,volumen,id,id_deposito,id_tipo_bloqueo,id_tipo_guia,id_disposicion,id_vuelo FROM guia WHERE id_aerolinea = '$id_aerolinea' AND id_tipo_guia != '2' AND id_tipo_bloqueo='1' ORDER BY master, id_deposito ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nguias=mysql_num_rows($consulta);
$cantidadguias=$nguias;		
if ($nguias > 0)
{
	$disponibilidad='';
	for ($i=1; $i<=$nguias; $i++)
	{
		$color='';
		$fila=mysql_fetch_array($consulta);
		//identificacion tipo de guia
		$id_tipo_guia=$fila["id_tipo_guia"];
		if ($id_tipo_guia==3)
			$id_tipo_guia="CONSOLIDADO";
		else
		{
			$sql3="SELECT nombre FROM tipo_guia WHERE id='$id_tipo_guia'";
			$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila3=mysql_fetch_array($consulta3);
			$id_tipo_guia=$fila3["nombre"];
		//************************
		}
		$hija=$fila["hija"];
		$master=$fila["master"];
		include("config/master.php");
		if ($master=="")
			$master="-";
		
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		$total_peso=$total_peso+$peso;
		$total_piezas=$total_piezas+$piezas;
		$total_volumen=$total_volumen+$fila["volumen"];

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
		
		if ($id_disposicion == 26 || $id_disposicion == 27) 
			{
				$destino="BODEGA DIAN";
				$color='color="green"';
			}
		$id_guia=$fila["id"];
	
		echo '<tr>
				<td align="left" class="celda_tabla_principal celda_boton">'.$id_tipo_guia.'</td>
				<td align="left" class="celda_tabla_principal celda_boton">'.$master.'</td>
				<td align="left" class="celda_tabla_principal celda_boton"><a href="consulta_guia.php?id_guia='.$id_guia.'">'.$hija.'</a></td>
				<td align="center" class="celda_tabla_principal celda_boton">'.$piezas.'</td>
				<td align="center" class="celda_tabla_principal celda_boton">'.$peso.'</td>
				<td align="left" class="celda_tabla_principal celda_boton"><font size="-1" '.$color.'>'.$destino.'</font></td>
				<td align="center" class="celda_tabla_principal celda_boton"><input type="checkbox" name="chkacepto'.$i.'" id="chkacepto'.$i.'" value="'.$id_guia.'"/></td>
			  </tr>';
		$activaciones=$activaciones."document.getElementById('chkacepto$i').checked=true;\n";
		$desactivaciones=$desactivaciones."document.getElementById('chkacepto$i').checked=false;\n";
	}
}
else
	{
		echo "<br><font color='red' size='+1'>No existen GU&Iacute;AS Digitadas para esta Aerol&iacute;nea.</font>";
		$i=1;
		$disponibilidad='disabled="disabled"';
	}
?>
</table>
<script language="javascript">
function seleccionar()
{
	<?php echo $activaciones ?>
}
function deseleccionar()
{
	<?php echo $desactivaciones ?>
}
</script>
<hr />
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
      	<button type="reset" name="reset" id="reset"> <img src="imagenes/descargar-act.png" alt="" title="Limpiar" /></button>
        <button type="submit" name="guardar" id="guardar" <?php echo $disponibilidad ?>> <img src="imagenes/guardar-act.png" alt="" title="Guardar" /> </button>
      </td>
    </tr>
</table>
<?php 
$total_peso=number_format($total_peso,2,",",".");
$total_volumen=number_format($total_volumen,2,",",".");
$total_piezas=number_format($total_piezas,0,",",".");
$total_guias=$i-1;
echo "
<strong><font color='blue' size='+1'>TOTALES</font><br>
<strong>CANTIDAD DE GUIAS:</strong>......... $total_guias<br>
<strong>PIEZAS TOTALES:</strong>................. $total_piezas<br>
<strong>PESO TOTALES:</strong>.................... $total_peso<br>
<strong>VOLUMEN TOTALES:</strong>.......... $total_volumen<br>";
?>
<input type="hidden" name="cantidadguias" value="<?php echo $cantidadguias ?>" />
<input type="hidden" name="id_aerolinea" value="<?php echo $id_aerolinea ?>" />
<input type="hidden" name="ruta" value="<?php echo $ruta ?>" />
<input type="hidden" name="nvuelo" value="<?php echo $nvuelo ?>" />
<input type="hidden" name="matricula" value="<?php echo $matricula ?>" />
<input type="hidden" name="hora_estimada" value="<?php echo $hora_estimada ?>" />
<input type="hidden" name="cod_ciudad_embarcadora" value="<?php echo $_REQUEST['cod_ciudad_embarcadora']; ?>" />
<input type="hidden" name="pais_origen" value="<?php echo $_REQUEST['pais_origen']; ?>" />
</form>
</body>
</html>