<?php
require("config/configuracion.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<script language="javascript" src="js/sha1.js"><!--// funcion incluir encriptacion a la contraseña //--></script>
<script language="javascript">
<!--
// funcion para validar
function validar()
{
	if (document.forms[0].cuenta.value=="")
	{
		alert("Atencion: Se requiere la cuenta de usuario");
		
		document.forms[0].cuenta.focus();
		return(false);
	}
	
	if (document.forms[0].clave.value=="")
	{
		alert("Atencion: Se requiere la clave de usuario");
		document.forms[0].clave.focus();
		return(false);
	}
	document.forms[0].clave.value=SHA1(document.forms[0].clave.value);
}
//-->
</script>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />
    <title><?php echo PROGRAMA ." - Version ". VERSION?></title>
</head>

<body>
<p class="titulo_tab_principal">Inicio de Sesi&oacute;n de Usuarios</p>
<p align="center"><font face="Tahoma, Geneva, sans-serif" size="+4" color="#0033FF"><?php echo "C.S.T." ?></font><br>
<p class="asterisco" align="center"><?php echo "Cargo System Taescol" ?></p>
<form name="login" method="post" action="login.php" onSubmit="return validar();">
<table width="450" align="center">
  <tr>
    <td colspan="2" align="center" class="celda_tabla_principal"><img src="imagenes/logo.png" align="absmiddle" /></td>
  </tr>
  <tr>
	<td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Usuario</div></td>
	<td class="celda_tabla_principal celda_boton"><input type="text" name="cuenta" size="40" id="cuenta" /></td>
		<script language="JavaScript" type="text/javascript">
			if(document.getElementById) document.getElementById('cuenta').focus();
		</script>
  </tr>
  <tr>
    <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Clave</div></td>
    <td class="celda_tabla_principal celda_boton"><input type="password" name="clave" size="40" /></td>
  </tr>
  <tr>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Operaci&oacute;n</div></td>
    <td align="center" class="celda_tabla_principal celda_boton">
        <select name="operacion" id="operacion" tabindex="3" >
        	<option value="1" selected="selected">Bogot&aacute;</option>
            <option value="2">Medell&iacute;n</option>
        </select>
    </td>
  </tr>
  <tr>
  	<td colspan="2" align="center" class="celda_tabla_principal celda_boton">
    	<button type="submit">
			Ingresar
        </button>
    </td> 
  </tr>
</table>
</form>
</body>
</html>