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
<script language="javascript">
<!--
// funcion para validar
function validar()
{	
	if (document.forms[0].nit.value=="")
	{
		alert("Atencion: Se requiere el NIT de la Transportadora");
		document.forms[0].nit.focus();
		return(false);
	}

	if (document.forms[0].nombre.value=="")
	{
		alert("Atencion: Se requiere el NOMBRE de la Transportadora");
		document.forms[0].nombre.focus();
		return(false);
	}
}
//-->

//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9\n]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
</script>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Transportador</p>
<form method="post" name="transportador" onsubmit="return validar();" action="transportador_registro.php">
<table align="center">
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Nit</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="nit" type="text" id="nit" tabindex="1" size="15" maxlength="30" onKeyPress="return numeric(event)"> <font color="#FF0000"><strong>(*)</strong></font></td>
      <script>document.forms[0].nit.focus();</script>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="nombre" type="text" tabindex="2" size="40" maxlength="50"> <font color="#FF0000"><strong>(*)</strong></font></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="telefono1" type="text" tabindex="3" size="15" maxlength="15"></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono 2</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="telefono2" type="text" tabindex="4" size="15" maxlength="15"></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Direccion</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="direccion" type="text" tabindex="5" size="40" maxlength="100"></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Contacto</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="contacto" type="text" tabindex="6" size="40" maxlength="50"></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">E-mail</div></td>
      <td><input name="mail" type="text" tabindex="7" size="50" maxlength="100"></td>
    </tr> 
</table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" onClick="document.location='consulta_identificar_parametricas.php?tabla=transportador'">
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
<p align="center"><br />
<?php 
if(isset($_POST["nit"]))
	{
		$nit=strtoupper($_POST["nit"]);
		$nombre=strtoupper($_POST["nombre"]);
		$telefono1=strtoupper($_POST["telefono1"]);
		$telefono2=strtoupper($_POST["telefono2"]);
		$direccion=strtoupper($_POST["direccion"]);
		$contacto=strtoupper($_POST["contacto"]);
		$mail=strtoupper($_POST["mail"]);
		$sql="INSERT INTO transportador (id,nombre,telefono1,telefono2,direccion,contacto,correo) value('$nit','$nombre','$telefono1','$telefono2','$direccion','$contacto','$mail')";
		mysql_query($sql,$conexion) or die ("<font color='red'>ERROR:Esta Empresa Ya Existe</font><br />");
		echo '<p align="center"><font size="4" color="green"><strong>Empresa TRANSPORTADORA Ingresada correctamente</strong></font></p>';
	}
?>
</p>
</body>
</html>