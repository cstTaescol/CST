<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$otro1="";
if(isset($_REQUEST['origen']))
{
	$origen=$_REQUEST['origen'];
}
else
{
	$origen="";
}
switch($origen)
{
	case "verificacion":
		$origen="verificacion";
	break;

	case("preinspeccion"):
		$origen="preinspeccion";
	break;

	case ("registro_fotografico"): // Cuando se consulta por preinspeccion
		$origen="registro_fotografico";
		$otro1=$_REQUEST['boton'];
	break;	

	case ("correccion_inconsistencias"): // Cuando se consulta por correccion de insconsistencias
		$origen="correccion_inconsistencias";
	break;	
	
	case ("correccion_postdespacho"): // Cuando se consulta por correccion postdespacho
		$origen="correccion_postdespacho";
	break;	

	default:
		$origen="buscador";
	break;
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
<p class="titulo_tab_principal">Buscador de Guias</p>
<form name="buscador" method="post" action="consulta_tracking2.php" onsubmit="return validar();">
<table align="center">
    <tr>
        <td class="celda_tabla_principal">
            <p class="asterisco">Gu&iacute;a No.</p>
        </td>
        <td class="celda_tabla_principal">
			<input name="ta" type="text" id="guia" tabindex="1" size="35" maxlength="20" />            
        </td>
        <td class="celda_tabla_principal">
			<button type="submit" tabindex="2">
            	<img src="imagenes/buscar-act.png" title="Buscar" align="absmiddle" />
            </button>
        </td>        
    </tr>
</table>
  <input type="hidden" name="origen" value="<?php echo $origen ?>"/>
  <input type="hidden" name="otro1" value="<?php echo $otro1 ?>"/>
  
	<script>document.forms[0].ta.focus();</script>  
</form>
</body>
</html>
<script language="javascript">
function validar()
{	
	if (document.forms[0].guia.value=="")
	{
		alert("Atencion: Digite el numero de GUIA");
		document.forms[0].guia.focus();
		return(false);
	}
}
</script>