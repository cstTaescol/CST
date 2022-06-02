<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

//Se obtienen los datos de la guia y que se van a transmitir al mapa
if(isset($_REQUEST['id_guia']))
	$id_guia=$_REQUEST['id_guia'];
else
	$id_guia="";
if(isset($_REQUEST['piezas']))
	$piezas=$_REQUEST['piezas'];
else
	$piezas="";
	
if(isset($_REQUEST['peso']))
	$peso=$_REQUEST['peso'];
else
	$peso="";
	
if(isset($_REQUEST['peso']))
	$observaciones=$_REQUEST['observaciones'];	
else
	$observaciones=""
	;	
if(isset($_REQUEST['fondo']))
	$fondo=$_REQUEST['fondo'];
else
	$fondo="";

if(isset($_REQUEST['evento']))
	$evento=$_REQUEST['evento'];
else
	$evento="";

if(isset($_REQUEST['celda']))
	$celda=$_REQUEST['celda'];
else
	$celda="";

if(isset($_REQUEST['msg']))
	$msg=$_REQUEST['msg'];
else
	$msg="";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php 
include ("menu.php");
?>
<body>
<p class="titulo_tab_principal">Mapa de Bodega</p>
<form name="formulario1" id="formulario1" action="ubicacion_mapa.php" method="post">
<input name="id_guia" type="hidden" value="<?php echo $id_guia; ?>" />
<input name="piezas" type="hidden" value="<?php echo $piezas; ?>"/>
<input name="peso" type="hidden" value="<?php echo $peso; ?>"/>
<input name="observaciones" type="hidden" value="<?php echo $observaciones; ?>" />
<input name="fondo" type="hidden" value="<?php echo $fondo; ?>" />
<input name="evento" type="hidden" value="<?php echo $evento; ?>" />
<input name="celda" type="hidden" value="<?php echo $celda; ?>" />
<input name="msg" type="hidden" value="<?php echo $msg; ?>" />

<table width="400" border="0" align="center">
  <tr>
        <td width="200" height="175" align="center" valign="middle" class="celda_tabla_principal">
                <button type="button" id="bodega1" onclick="direccionador(1)"><img src="imagenes/home-act.png" /><br />Bodega 2</button>            
        </td>
        <td width="200" align="center" valign="middle" class="celda_tabla_principal">
            <button type="button" id="bodega2" onclick="direccionador(2)"><img src="imagenes/home-act.png" /><br />Bodega 4</button>
        </td>
    </tr>
</table>
</form>
</body>
</html>
<script language="javascript">
function direccionador(id)
{
	if (id == 1)
	{
		document.formulario1.action="ubicacion_mapa.php";		
	}
	else
		{
			document.formulario1.action="ubicacion_mapa2.php";		
		}
	
	document.formulario1.submit();
}
</script>