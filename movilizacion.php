<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<html>
<head>
<script language="javascript">
// funcion para validar
function validar()
{
	if (document.forms[0].nombre.value=="")
	{
		alert("Atencion: Debe digitar un NOMBRE");
		document.forms[0].nombre.focus();
		return(false);
	}
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">In/Movilizaciones</p>
<form name="movilizacion" action="movilizacion.php" onSubmit="return validar();" method="post">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
    <td class="celda_tabla_principal celda_boton"><input name="nombre" id="nombre" type="text" size="30" maxlength="30" tabindex="1" /></td>
    <script type="text/javascript">
		document.forms[0].nombre.focus();
	</script>
  </tr>
  <tr>
   <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<input type="radio" name="tipo" value="M" tabindex="2" checked >Movilizacion<br>
        <input type="radio" name="tipo" value="I">Inmovilizacion<br>
    </td>
  </tr> 
</table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" onClick="document.location='consulta_identificar_parametricas.php?tabla=movilizacion'">
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

<p>Recuerde que este espacio est&aacute; dise&ntilde;ado para que ingrese los tipos de <em><strong>Bloqueos</strong></em>  y/o <em><strong>Desbloqueos</strong></em> de la gu&iacute;a seg&uacute;n corresponda</p>
</form>
<?php
if(isset($_POST["nombre"]))
{
	$nombre=strtoupper($_POST["nombre"]);
	$tipo=strtoupper($_POST["tipo"]);
	$sql="INSERT INTO movilizacion (nombre,tipo) value ('$nombre','$tipo')";
	mysql_query($sql,$conexion) or die (mysql_error());
	echo '<p align="center"><font color="green" size="4">Movilizacion Parametrizada</font></p>';
}
?>
</body>
</html>