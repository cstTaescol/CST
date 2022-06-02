<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

//Discriminacion de aerolinea de usuario TIPO 4
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND g.id_aerolinea = '$id_aerolinea_user'";	
//*************************************

$tablas="";
$sql="SELECT v.nvuelo,g.* FROM vuelo v LEFT JOIN guia g ON v.id = g.id_vuelo WHERE (id_disposicion='15' OR id_disposicion='25' OR  id_disposicion='28' OR id_disposicion='29') AND (id_tipo_bloqueo='3' OR id_tipo_bloqueo='6' OR id_tipo_bloqueo='10') AND g.faltante_total != 'S' $sql_aerolinea";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$i=0;
while($fila=mysql_fetch_array($consulta))
{
	$estado="";
	$i++;
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
	if ($piezas == 0 || $peso ==0)
	{
		$estado='disabled="disabled"';
	}
	
	$id_guia=$fila['id'];
	$nvuelo=$fila['nvuelo'];
	//recuperando datos de consignatario		
	$id_consignatario=$fila['id_consignatario'];
	$sql2="SELECT nombre FROM consignatario WHERE id='$id_consignatario'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$cliente=$fila2['nombre'];
	
	$tablas=$tablas.'
      <tr>
        <td align="center" class="celda_tabla_principal celda_boton"><input type="checkbox" name="accion'.$i.'" value="'.$id_guia.'" '.$estado.' /></td>
        <td align="left" class="celda_tabla_principal celda_boton">'.$fila['hija'].'</td>
        <td align="left" class="celda_tabla_principal celda_boton">'.$piezas.'</td>
        <td align="right" class="celda_tabla_principal celda_boton">'.number_format($peso,2,",","").'</td>
        <td align="left" class="celda_tabla_principal celda_boton">'.$cliente.'</td>
        <td align="left" class="celda_tabla_principal celda_boton">'.$fila['nvuelo'].'</td>
      </tr>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
// funcion para validaacin general
function validar()
{
	if (document.forms[0].descripcion.value=="")
	{
		alert("Atencion: Debe escribir una DESCRIPCION");
		document.forms[0].descripcion.focus();
		return(false);
	}
}
</script>
</head>
<body>
<?php
require("menu.php");
$id_objeto=61;
include("config/provilegios_modulo.php");
?>
<p class="titulo_tab_principal">Despacho de Otras Guias</p>
<form method="post" action="despacho_otros2.php" onsubmit="return validar();">
  <table align="center" >
    <tr>
      <td align="center"class="celda_tabla_principal"><div class="letreros_tabla">Descripcion:</div>
        <p>
          <input type="hidden" name="cantidadguias" value="<?php echo $i; ?>" />
          <textarea name="descripcion" id="descripcion" cols="55" rows="7" tabindex="1"></textarea>
          <script type="text/javascript">document.forms[0].descripcion.focus();</script> 
        </p>
      </td>
    </tr>
  </table>
<br />
    <table align="center">
      <tr>
        	<td class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
            <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia:</div></td>
            <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas:</div></td>
            <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso:</div></td>
            <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Consignatario:</div></td>
            <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Vuelo:</div></td>
       </tr>
      <?php echo $tablas ?>
    </table>

    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" onClick="document.location='base.php'">
                <img src="imagenes/al_principio-act.png" title="Atras" />
            </button>
            <button type="reset" name="reset" id="reset">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="submit" name="guardar" id="guardar">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
          </td>
        </tr>
     </table>    
</form>
</body>
</html>