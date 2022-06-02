<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="js/sha1.js"><!--// funcion incluir encriptacion a la contraseña //--></script>
<script language="javascript">
// funcion para validar
function validar()
{	
	if (document.forms[0].actual.value=="")
	{
		alert("Atencion: Se requiere la CLAVE ACTUAL");
		document.forms[0].actual.focus();
		return(false);
	}
	if (document.forms[0].nueva.value=="")
	{
		alert("Atencion: Se requiere la CLAVE NUEVA");
		document.forms[0].nueva.focus();
		return(false);
	}
	if (document.forms[0].retipe.value=="")
	{
		alert("Atencion: Se requiere la confirmacion de la CLAVE NUEVA");
		document.forms[0].retipe.focus();
		return(false);
	}
document.forms[0].actual.value=SHA1(document.forms[0].actual.value);
document.forms[0].nueva.value=SHA1(document.forms[0].nueva.value);
document.forms[0].retipe.value=SHA1(document.forms[0].retipe.value);
}

</script>
</head>
<body>
<?php
require("menu.php");
$id_objeto=1;
include("config/provilegios_modulo.php");
?>
<p class="titulo_tab_principal">Cambiar Clave</p>
<form method="post" name="vehiculo" onsubmit="return validar();" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
<table width="610" align="center" >
    <tr>
      <td width="239" class="celda_tabla_principal"><div class="letreros_tabla">Clave Actual</div></td>
      <td width="306" class="celda_tabla_principal celda_boton">
      <div class="asterisco">(*) <input name="actual" type="password" id="actual" tabindex="1" size="30" maxlength="15" /></div>
      <script>document.forms[0].actual.focus();</script>
      </td>
      
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Clave Nueva</div></td>
      <td class="celda_tabla_principal celda_boton">
      	<div class="asterisco">(*) <input name="nueva" type="password" id="nueva" tabindex="2" size="30" maxlength="15" /></div>        
      </td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Repita la Clave Nueva</div></td>
      <td class="celda_tabla_principal celda_boton">
      	<div class="asterisco">(*) <input name="retipe" type="password" id="retipe" tabindex="3" size="30" maxlength="15" /></div>         
      </td>
    </tr> 
    <tr>
      <td class="celda_tabla_principal celda_boton" align="center">
      	<button type="reset" tabindex="5" style="width:100px">Limpiar</button>
      </td>
      <td class="celda_tabla_principal celda_boton" align="center">
      	<button type="submit" tabindex="4" style="width:100px">Guardar</button>
      </td>
    </tr>
</table>
</form>
<?php 
if(isset($_POST["actual"]) && isset($_POST["nueva"]) && isset($_POST["retipe"]))
	{
		$actual=$_POST["actual"];
		$nueva=$_POST["nueva"];
		$retipe=$_POST["retipe"];
		$id_usuario=$_SESSION['id_usuario'];
		if ($id_usuario == 1) //administrador 
		{
			echo '<p align="center"><font size="4" color="red"><strong>ERROR: Este Usuario No Puede Ser Modificado</strong></font><img src="imagenes/error.png" width="30" height="30" align="absmiddle" /></p>';
			exit;
		}
		
		$sql="SELECT clave FROM usuario WHERE id = '$id_usuario='";
		$consulta=mysql_query($sql,$conexion) or die (mysql_error());
		$fila=mysql_fetch_array($consulta);
		$clave=$fila['clave'];
		
		if ($actual == $clave)
		{
			if ($nueva == $retipe)
			{
				$sql="UPDATE usuario SET clave='$nueva' WHERE id=$id_usuario";
				mysql_query($sql,$conexion) or die (mysql_error());
				echo '
				<script language="javascript">
					alert ("CLAVE Actualizada de manera correcta");
				</script>';
			}
			else
				echo '
				<script language="javascript">
					alert ("ERROR: La NUEVA CLAVE no coincide con la CONFIRMACION");
				</script>';			
		}
		else
			echo '
				<script language="javascript">
					alert ("ERROR: Su calve actual no coincide con la registrada en el Sistema");
				</script>';			
	}
?>
</p>

</body>
</html>