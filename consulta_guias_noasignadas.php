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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Guias No Asignadas a Vuelos</p>
<table align="center" >
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Consolidado</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Destino</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Eliminar</div></td>
  </tr>
<?php
//Privilegio de eliminacion de guia
$id_objeto=71; 
include("config/provilegios_objeto.php");  

$sql="SELECT master,hija,piezas,peso,id,id_deposito,id_tipo_bloqueo,id_tipo_guia,id_disposicion,id_aerolinea FROM guia WHERE id_tipo_guia != '2' AND id_tipo_bloqueo='1' AND (ISNULL(id_vuelo) OR id_vuelo='') $sql_aerolinea ORDER BY id_aerolinea,master,id_deposito ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nguias=mysql_num_rows($consulta);
if ($nguias > 0)
{
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
		
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		
		//carga datos
		$id_aerolinea=$fila["id_aerolinea"];
		$sql3="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$naerolinea=$fila3['nombre'];
		
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
		
		if ($id_disposicion == 26) 
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
				<td align="center" class="celda_tabla_principal celda_boton">'.$naerolinea.'</td>
				<td class="celda_tabla_principal celda_boton">
					<button type="button" '.$activacion.'  onClick="conf_eliminar('.$id_guia.');"> 
      					<img src="imagenes/eliminar-act.png" title="Eliminar" />
					</button>	    
				</td>
			  </tr>';
	}
}
?>
</table>
</body>
</html>
<script language="javascript">
	//Funcion para confirmar la eliminacion de una guia
	function conf_eliminar(url)
	{
	var respuesta=confirm('ATENCION: Confirma que, Desea Eliminar la Guia?');
	if (respuesta)
		{
			window.location="eliminar_guia1.php?id_guia="+url;
		}
	}
</script>
