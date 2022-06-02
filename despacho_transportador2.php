<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

$transportador=$_REQUEST["transportador"];
$vehiculo=$_REQUEST["vehiculo"];
$conductor=$_REQUEST["conductor"];
$deposito=$_REQUEST["deposito"];
$id_aerolinea=$_REQUEST["id_aerolinea"];
$planilla_envio=strtoupper($_REQUEST["planilla_envio"]);

$_SESSION["transportador"]=$transportador;
$_SESSION["vehiculo"]=$vehiculo;
$_SESSION["conductor"]=$conductor;
$_SESSION["deposito"]=$deposito;
$_SESSION["id_aerolinea"]=$id_aerolinea;
$_SESSION["planilla_envio"]=$planilla_envio;


//Discriminacion de aerolinea de usuario TIPO 2
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id_aerolinea = '$id_aerolinea_user'";	
//*************************************

//carga nombre del conductor
$sql="SELECT nombre FROM conductor WHERE id='$conductor'";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
$fila=mysql_fetch_array($consulta);
$_SESSION["nconductor"]=$fila['nombre'];

//carga nombre del transportador
$sql="SELECT nombre FROM transportador WHERE id='$transportador'";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
$fila=mysql_fetch_array($consulta);
$_SESSION["ntransportador"]=$fila['nombre'];

//carga nombre del deposito
$sql="SELECT nombre FROM deposito WHERE id='$deposito'";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
$fila=mysql_fetch_array($consulta);
$_SESSION["ndeposito"]=$fila['nombre'];

//carga placa del vehiculo
$sql="SELECT placa FROM vehiculo WHERE placa='$vehiculo'";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
$fila=mysql_fetch_array($consulta);
$_SESSION["nvehiculo"]=$fila['placa'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript">
//Validacion de campos num�ricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9\n]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
function numeric2(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9-.\n]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Despachos de Mercancia</p>
<p class="asterisco" align="center">Transportador</p>
<p align="center"><img src="imagenes/2.jpg" width="186" height="67" alt="PASO 2" style="border-radius: 15px;" /></p>
<table align="center">
  <tr>
  
    <td class="celda_tabla_principal"><div class="letreros_tabla">Transportador</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $_SESSION["ntransportador"]; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Vehiculo</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $_SESSION["nvehiculo"]; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Conductor</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $_SESSION["nconductor"]; ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Deposito</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $_SESSION["ndeposito"];?></td>
  </tr>
</table>
<br />
<form name="despacho" action="despacho_transportador3.php" method="post">
<table align="center">
  <tr>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">No Guia</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla"><img src="imagenes/aceptar-act.png"/></td>
  </tr> 
<?php
$sql="SELECT * FROM guia WHERE id_deposito='$deposito' AND id_aerolinea='$id_aerolinea' AND (id_tipo_bloqueo='3' OR id_tipo_bloqueo = '6' OR id_tipo_bloqueo = '10') AND faltante_total != 'S' ORDER BY fecha_inconsistencia DESC";
$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
$cont=0;
while($fila=mysql_fetch_array($consulta))
{
	$cont++;
	include("config/evaluador_inconsistencias.php");
	$piezas=$piezas-$fila["piezas_despacho"];
	$peso=$peso-$fila["peso_despacho"];
	//calculo para bloqueos parciales
	if ($fila["id_tipo_bloqueo"] == 10)
	{
		$peso=$peso-$fila["bloqueo_peso"];
		$piezas_sinbloqueo=$piezas-$fila['bloqueo_piezas'];
		$volumen=number_format(($volumen / $piezas) * $piezas_sinbloqueo,2,",",".");
		$piezas=$piezas-$fila['bloqueo_piezas'];	
	}
	//***********************************
	$peso=round($peso,2);
	
	//carga nombre de la aerolinea
	$aerolinea=$fila["id_aerolinea"];
	$sql2="SELECT nombre FROM aerolinea WHERE id='$aerolinea'";
	$consulta2=mysql_query ($sql2,$conexion) or die (exit('Error '.mysql_error()));
	$fila2=mysql_fetch_array($consulta2);
	$aerolinea=$fila2['nombre'];
	
	echo '<tr>
			<td class="celda_tabla_principal celda_boton"><a href="consulta_guia.php?id_guia='.$fila["id"].'">'.$fila['hija'].'</a></td>
			<td class="celda_tabla_principal celda_boton">'.$aerolinea.'</td>
			<td class="celda_tabla_principal celda_boton"><input type="text" value="'.$piezas.'" name="piezas'.$cont.'" size="5" maxlength="10" onKeyPress="return numeric(event)"></td>
			<td class="celda_tabla_principal celda_boton"><input type="text" value="'.$peso.'" name="peso'.$cont.'" size="5" maxlength="10" onKeyPress="return numeric2(event)"></td>
			<td class="celda_tabla_principal celda_boton"><input type="text" value="" name="observacionesdespacho'.$cont.'" size="20"></td>
			<td align="center" class="celda_tabla_principal celda_boton">
				<input type="checkbox" name="guia'.$cont.'" value="'.$fila["id"].'">
			</td>
		 </tr>';
}
?>
</table>
<input type="hidden" name="cantidadguias" id="cantidadguias" value="<?php echo $cont ?>" />
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
      	<button type="button" tabindex="9" onclick="document.location='despacho_transportador1.php'">
        	<img src="imagenes/al_principio-act.png" title="Atras" />
        </button>
        <button type="reset" tabindex="8">
        	<img src="imagenes/descargar-act.png" title="Limpiar" />
        </button>
      	<button type="submit" tabindex="7">
        	<img src="imagenes/al_final-act.png" title="Continuar" />
        </button>
      </td>
    </tr>
</table>
</form>
</p>
</body>
</html>