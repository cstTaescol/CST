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
	if (document.forms[0].descripcion.value=="")
	{
		alert("Atencion: Debe digitar una RUTA");
		document.forms[0].descripcion.focus();
		return(false);
	}
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Rutas</p>
<form name="deposito" action="ruta_nuevo.php" onSubmit="return validar();" method="post">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Descripcion</div></td>
    <td class="celda_tabla_principal celda_boton"><input name="descripcion" id="descripcion" type="text" size="15" maxlength="15" tabindex="1" /></td>
    <script type="text/javascript">
		document.forms[0].descripcion.focus();
	</script>
    
  </tr>
</table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" onClick="document.location='consulta_identificar_parametricas.php?tabla=rutas'">
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
<?php
if(isset($_POST["descripcion"]))
{
	$descripcion=strtoupper($_POST["descripcion"]);
	$sql="INSERT INTO ruta (descripcion) value ('$descripcion')";
	mysql_query($sql,$conexion) or die (mysql_error());
	echo '<p align="center"><font color="green" size="4">Ruta Guardada</font></p>';
}
?>
</body>
</html>