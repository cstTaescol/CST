<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

if(isset($_REQUEST["id"]))
{
	$id_trasbordo=$_REQUEST["id"];
	$sql="SELECT observaciones, destinatario FROM trasbordo WHERE id=$id_trasbordo";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$observaciones=$fila["observaciones"];
	$destinatario=$fila["destinatario"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
// funcion para validar
function validar()
{
	if (document.forms[0].destinatario.value=="")
	{
		alert("Atencion: Debe digitar un DESTINATARIO");
		document.forms[0].destinatario.focus();
		return(false);
	}	
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Modificar Trasbordo</p>
<p class="asterisco" align="center">Despacho N&uacute;mero: <font size="+2" color="006600"><?php echo $id_trasbordo; ?></font></p>
<form name="complementarios" method="post" action="modificar_trasbordo2.php" onsubmit="return validar();">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Trasbordado a:</div></td>
    <td class="celda_tabla_principal celda_boton">
    <input name="destinatario" type="text" id="destinatario" tabindex="1" size="50" maxlength="40" value="<?php echo $destinatario ?>" />
    <input name="id" type="hidden" value="<?php echo $id_trasbordo ?>" />
    </td>
    <script type="text/javascript">
		document.forms[0].destinatario.focus();
	</script>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<textarea name="observaciones" cols="40" rows="5" tabindex="2"><?php echo $observaciones ?></textarea>
    </td>
  </tr>
</table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
        <button type="reset" tabindex="14">
            <img src="imagenes/descargar-act.png" alt="" title="Limpiar" />
        </button>
        <button type="submit" tabindex="13">
            <img src="imagenes/guardar-act.png" alt="" title="Guardar" />
        </button>
      </td>
    </tr>
</table>

</form>
</body>
</html>
