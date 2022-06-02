<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

if(isset($_REQUEST["id_ddirecto"]))
{
	$id_ddirecto=$_REQUEST["id_ddirecto"];
	$sql="SELECT * FROM descargue_directo WHERE id=$id_ddirecto";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$levante=$fila["levante"];
	$declaracion=$fila["declaracion"];
	$nombre_entregado=$fila["nombre_entregado"];
	$agencia=$fila["agencia"];
	$cedula_entregado=$fila["cedula_entregado"];
	$telefono_entregado=$fila["telefono_entregado"];
	$nombre_conductor=$fila["nombre_conductor"];
	$cedula_conductor=$fila["cedula_conductor"];
	$placa=$fila["placa"];
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
	if (document.forms[0].levante.value=="")
	{
		alert("Atencion: Debe digitar un Numero de Levante");
		document.forms[0].levante.focus();
		return(false);
	}
	
	if (document.forms[0].declaracion.value=="")
	{
		alert("Atencion: Debe digitar un Numero de Declaracion");
		document.forms[0].declaracion.focus();
		return(false);
	}
	if (document.forms[0].nombre.value=="")
	{
		alert("Atencion: Debe digitar un Nombre de quien Recibe la carga.");
		document.forms[0].nombre.focus();
		return(false);
	}
	if (document.forms[0].agencia.value=="")
	{
		alert("Atencion: Debe digitar un Nombre de Agencia");
		document.forms[0].agencia.focus();
		return(false);
	}
	if (document.forms[0].cedula.value=="")
	{
		alert("Atencion: Debe digitar un Numero de cedula de quien Recibe la carga.");
		document.forms[0].cedula.focus();
		return(false);
	}
	if (document.forms[0].conductor.value=="")
	{
		alert("Atencion: Debe digitar un Nombre de Conductor");
		document.forms[0].conductor.focus();
		return(false);
	}
	if (document.forms[0].cedula_conductor.value=="")
	{
		alert("Atencion: Debe digitar un Numero de Cedula de Conductor");
		document.forms[0].cedula_conductor.focus();
		return(false);
	}
	if (document.forms[0].placa.value=="")
	{
		alert("Atencion: Debe digitar un Numero de Placa O ingrese la palabra MANUAL para un retiro manual de Mercancias");
		document.forms[0].placa.focus();
		return(false);
	}
}
</script>
</head>

<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Modificacion de D. Directo</p>
<p class="asterisco" align="center">Descargue Directo No.  <font color="#FF0000"><?php echo $id_ddirecto ?></font></p>

<form name="modificar" method="post" action="modificar_ddirecto2.php" onsubmit="return validar();">
  <table align="center">
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">No. Levante</div></td>
      <td colspan="3" class="celda_tabla_principal celda_boton"><input name="levante" type="text" id="levante" tabindex="1" size="50" maxlength="40" value="<?php echo $levante ?>" />
      <input type="hidden" name="id_ddirecto" value="<?php echo $id_ddirecto ?>" /></td>
      <script type="text/javascript">
		document.forms[0].levante.focus();
	</script>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Declaracion</div></td>
      <td colspan="3" class="celda_tabla_principal celda_boton"><input name="declaracion" type="text" id="declaracion" tabindex="2" size="50" maxlength="40" value="<?php echo $declaracion ?>"/></td>
    </tr>
    <tr>
      <td colspan="4"  class="celda_tabla_principal"><div class="letreros_tabla">Entregado A:</div></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
      <td class="celda_tabla_principal celda_boton">
      	<input name="nombre" type="text" id="nombre" tabindex="3" size="40" maxlength="40" value="<?php echo $nombre_entregado ?>"/>
        <button type="button" name="cancelar" onclick="conductor.value=nombre.value" tabindex="4" >
        	<img src="imagenes/adelante-act.png" title="Pasar" align="absmiddle"/>
        </button>
      </td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Conductor</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="conductor" type="text" id="conductor" tabindex="10" size="40" maxlength="40" value="<?php echo $nombre_conductor ?>"/></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Agencia</div></td>
      <td colspan="3" class="celda_tabla_principal celda_boton"><input name="agencia" type="text" id="agencia" tabindex="5" size="50" maxlength="50" value="<?php echo $agencia ?>"/></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Cedula</div></td>
      <td class="celda_tabla_principal celda_boton">
      	<input name="cedula" type="text" id="cedula" tabindex="6" size="40" maxlength="20" value="<?php echo $cedula_entregado ?>"/>
        	<button type="button" name="cancelar" onclick="cedula_conductor.value=cedula.value" tabindex="7" >
            	<img src="imagenes/adelante-act.png" title="Pasar" align="absmiddle"/>
        </button>
      </td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Cedula Conductor</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="cedula_conductor" type="text" id="cedula_conductor" tabindex="11" size="40" maxlength="20" value="<?php echo $cedula_conductor ?>"/></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="telefono" type="text" id="telefono" tabindex="9" size="20" maxlength="20" value="<?php echo $telefono_entregado ?>"/></td>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Placa</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="placa" type="text" id="placa" tabindex="12" size="6" maxlength="6" value="<?php echo $placa ?>"/></td>
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