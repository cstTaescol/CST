<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$cont=0;
$id_usuario_modificar="";
if(isset($_REQUEST["cc"]))
{
	$cc=$_REQUEST["cc"];
	$nombre_usuario=strtoupper($_REQUEST["nombre"]);
	$telefono=strtoupper($_REQUEST["telefono"]);
	$cargo=strtoupper($_REQUEST["cargo"]);
	$login=strtoupper($_REQUEST["login"]);
	$id_aerolinea=$_REQUEST["id_aerolinea"];	
}

//Verificar que no exista otro usuario con el mismo login
$sql="SELECT * FROM usuario WHERE cuenta='$login' AND estado='A'";
$consulta=mysql_query($sql,$conexion) or die (mysql_error());
$nfilas=mysql_num_rows($consulta);
if ($nfilas != 0)
{
	echo "<script>
			alert('ERROR:Ya existe un usuario con el mismo LOGIN de Ingreso');
			document.location='usuario_registro.php?cc=$cc&nombre=$nombre_usuario&telefono=$telefono&cargo=$cargo&login=$login';
		</script>";
	exit();
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
<p class="titulo_tab_principal">Asignacion de Privilegios</p>
<table width="610" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="celda_tabla_principal" width="150"><div class="letreros_tabla">Usuario</div></td>
    <td class="celda_tabla_principal"><?php echo $nombre_usuario ?>.</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Cargo</div></td>
    <td class="celda_tabla_principal"><?php echo $cargo ?>.</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Login</div></td>
    <td class="celda_tabla_principal"><?php echo $login ?></td>
  </tr>
</table>
<form action="usuario_registro3.php" method="post" name="registro">
<?php 
include ("usuario_tabla_privilegios.php"); 
?>
<input type="hidden" name="cc" value="<?php echo $cc ?>" />
<input type="hidden" name="nombre" value="<?php echo $nombre_usuario ?>" />
<input type="hidden" name="telefono" value="<?php echo $telefono ?>" />
<input type="hidden" name="cargo" value="<?php echo $cargo ?>" />
<input type="hidden" name="login" value="<?php echo $login ?>" />
<input type="hidden" name="clave" value="<?php echo $clave ?>" />
<input type="hidden" name="id_aerolinea" value="<?php echo $id_aerolinea ?>" />
<input type="hidden" name="ncampos" value="<?php echo $cont ?>" />
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
      	<button type="button" onclick="document.location='usuario_registro.php?cc=<?php echo $cc ?>&nombre=<?php echo $nombre_usuario ?>&telefono=<?php echo $telefono ?>&login=<?php echo $login ?>&cargo=<?php echo $cargo ?>'">
        	<img src="imagenes/al_principio-act.png" title="Atras" />
        </button>
        <button type="reset">
        	<img src="imagenes/descargar-act.png" title="Limpiar" />
        </button>
      	<button type="submit">
        	<img src="imagenes/al_final-act.png" title="Continuar" />
        </button>
      </td>
    </tr>
 </table>
</form>
</body>
</html>