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
<script language="javascript">
<!-- Deshabilitar menu contextual del mouse
	//document.oncontextmenu = function(){return false}
//-->
</script>
<script language="javascript">
//Solo aceptar numeros y puntos
function numeric2(n) { 
	permitidos=/[^0-9.]/;
	if(permitidos.test(n.value))
	{
		alert("Solo se Pueden Ingresar Numeros y Puntos");
		n.value="";
		n.focus();
	}
} 

function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
$id_objeto=57;
include("config/provilegios_modulo.php");
?>
<p class="titulo_tab_principal">Despacho a Cliente</p>
<form method="post" action="despacho_directo1.php">
    <table align="center">
      <tr>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Buscar Guia No.</div></td>
        <td align="center" class="celda_tabla_principal">
            <input name="guia" type="text" id="guia" size="30" />
            <script type="text/javascript">
                document.forms[0].guia.focus();
            </script>
         </td>
        <td align="center" class="celda_tabla_principal">
            <button type="submit" name="buscar">
                <img src="imagenes/buscar-act.png" title="Buscar"/>
            </button>
        </td>
      </tr>
    </table>
</form>
<?php
$tablas="";
if (isset($_POST["guia"]))
{
	$guia=$_POST["guia"];
	$sql="SELECT * FROM guia WHERE hija LIKE '%$guia%' AND (id_tipo_bloqueo='3' OR id_tipo_bloqueo='6' OR id_tipo_bloqueo='10') AND (id_disposicion='19' OR id_disposicion='20' OR id_disposicion='21' OR id_disposicion='28') AND faltante_total != 'S' $sql_aerolinea ORDER BY id_consignatario ASC";
}
else
{
	$sql="SELECT * FROM guia WHERE (id_tipo_bloqueo='3' OR id_tipo_bloqueo='6' OR id_tipo_bloqueo='10') AND (id_disposicion='19' OR id_disposicion='20' OR id_disposicion='21' OR id_disposicion='28') AND faltante_total != 'S' $sql_aerolinea ORDER BY id_consignatario ASC";
}
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
{
	include("config/evaluador_inconsistencias.php");
	if ($fila['id_tipo_bloqueo'] == 10)
	{		
		$peso=$peso-$fila["bloqueo_peso"]-$fila["peso_despacho"];
		$piezas_sinbloqueo=$piezas-$fila['bloqueo_piezas'];
		$volumen=(($volumen / $piezas) * $piezas_sinbloqueo)-$fila["volumen_despacho"];
		$piezas=$piezas-$fila['bloqueo_piezas']-$fila["piezas_despacho"];	
	}
	else
	{
		$peso=$peso-$fila["peso_despacho"];
		$piezas_sinbloqueo=$piezas-$fila['piezas_despacho'];
		$volumen=$volumen -$fila["volumen_despacho"];
		$piezas=$piezas-$fila["piezas_despacho"];		
	}
	
	$master=$fila['master'];
	require("config/master.php");
	
	//recuperando datos de consignatario		
	$id_consignatario=$fila['id_consignatario'];
	$sql2="SELECT nombre FROM consignatario WHERE id='$id_consignatario'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$consignatario=$fila2['nombre'];
	$id_guia=$fila['id'];
	$tablas=$tablas.'
	<form method="post" action="despacho_directo2.php">
	<tr>
			<td class="celda_tabla_principal celda_boton">
				<button type="submit"><img src="imagenes/aceptar-act.png" title="Seleccionar"/></button>
			</td>
			<td class="celda_tabla_principal celda_boton">'.$master.'</td>
			<td class="celda_tabla_principal celda_boton"><a href="consulta_guia.php?id_guia='.$id_guia.'">'.$fila['hija'].'</a></td>
			<td class="celda_tabla_principal celda_boton"><input name="piezas" type="text" size="5" maxlength="10" value="'.$piezas.'" onKeyPress="return numeric(event)"/></td>
			<td class="celda_tabla_principal celda_boton"><input name="peso" type="text" size="5" maxlength="10" tabindex="10" value="'.$peso.'" onKeyup="numeric2(this)" onblur="numeric2(this)"/></td>
			<td class="celda_tabla_principal celda_boton">'.$consignatario.'</td>
			<input name="id_guia" type="hidden" value="'.$id_guia.'" />
	</tr>
	</form>
	';
}
?>
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Consolidado</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
  </tr>
  <?php echo $tablas ?>
</table>

</body>
</html>