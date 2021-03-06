<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

$cc="";
$nombre="";
$telefono="";
$cargo="";
$login="";

if(isset($_REQUEST["cc"]))$cc=$_REQUEST["cc"];
if(isset($_REQUEST["nombre"]))$nombre=$_REQUEST["nombre"];
if(isset($_REQUEST["telefono"]))$telefono=$_REQUEST["telefono"];
if(isset($_REQUEST["cargo"]))$cargo=$_REQUEST["cargo"];
if(isset($_REQUEST["login"]))$login=$_REQUEST["login"];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="js/sha1.js"><!--// funcion incluir encriptacion a la contraseña //--></script>
<script language="javascript">
//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

// funcion para validar
function validar()
{	
	if (document.forms[0].cc.value=="")
	{
		alert("Atencion: Se requiere el Numero de Identificacion");
		document.forms[0].cc.focus();
		return(false);
	}
	
	if (document.forms[0].nombre.value=="")
	{
		alert("Atencion: Se requiere el NOMBRE de usuario");
		document.forms[0].nombre.focus();
		return(false);
	}

	if (document.forms[0].login.value=="")
	{
		alert("Atencion: Se requiere el LOGIN de inicio de sesion.");
		document.forms[0].login.focus();
		return(false);
	}

	if (document.forms[0].grupo.value=="*")
	{
		alert("Atencion: Seleccione un Grupo de Usuarios");
		document.forms[0].grupo.focus();
		return(false);
	}
	document.forms[0].clave.value=SHA1(document.forms[0].clave.value);
}
//-->
  </script>
  
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Registro de Usuarios</p>
<form method="post" name="usuario" onsubmit="return validar();" action="usuario_registro2.php">
<table width="610" align="center" >
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Documento</div></td>
      <td class="celda_tabla_principal celda_boton">      	
      	<div class="asterisco"><input name="cc" type="text" id="cc" tabindex="1" size="15" maxlength="15" onkeypress="return numeric(event)" value="<?php echo $cc ?>">(*)</div></td>
      	<script>document.forms[0].cc.focus();</script>
      </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
      <td class="celda_tabla_principal celda_boton"><div class="asterisco"><input name="nombre" id="nombre" type="text" tabindex="2" size="40" maxlength="60" value="<?php echo $nombre ?>">(*)</div></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="telefono" type="text" tabindex="3" size="15" maxlength="15" onkeypress="return numeric(event)" value="<?php echo $telefono ?>" /></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Cargo</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="cargo" id="cargo" type="text" tabindex="4" size="40" maxlength="50" value="<?php echo $cargo ?>"><br /></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal" style="vertical-align: top;"><div class="letreros_tabla">Login</div></td>
      <td class="celda_tabla_principal celda_boton"><p>
        <div class="asterisco"><input name="login" id="login" type="text" tabindex="5" size="40" maxlength="50" value="<?php echo $login ?>">
        (*)</div>
        <p><em>Nombre que se usar&aacute; para el inicio de la sesi&oacute;n del cliente</em><br />
      </p></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Clave</div></td>
      <td class="celda_tabla_principal celda_boton">1234<input type="hidden" name="clave" id="clave" value="1234" /><br />
      </td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
      <td class="celda_tabla_principal celda_boton">
      	<select name="id_aerolinea" id="id_aerolinea" tabindex="6">
        	<option value="*">Todas</option>
            <?php
				$sql="SELECT id,nombre FROM aerolinea WHERE estado='A' ORDER BY nombre ASC";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila=mysql_fetch_array($consulta))
				{
					echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
				}
			?>
        </select>
      </td>
    </tr> 
</table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
      	<button type="button" onclick="document.location='usuario_lista.php'">
        	<img src="imagenes/al_principio-act.png" title="Atras" />
        </button>
        <button type="reset">
        	<img src="imagenes/descargar-act.png" title="Limpiar" />
        </button>
      	<button type="submit" tabindex="7">
        	<img src="imagenes/al_final-act.png" title="Continuar" />
        </button>
      </td>
    </tr>
 </table>

</form>
</body>
</html>