<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if(isset($_REQUEST["id_guia"]))
{
	$id_guia=$_REQUEST['id_guia'];
	$sql="SELECT hija, piezas, peso, volumen,piezas_inconsistencia,peso_inconsistencia,volumen_inconsistencia, id_aerolinea FROM guia WHERE id = '$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	include("config/evaluador_inconsistencias.php"); //calcula y general el valor de $piezas, $peso y $volumen luego de las inconsistencias.		
	$aerolinea=$fila["id_aerolinea"];	
	$hija=$fila["hija"];
	//aerolinea
	$sql_add="SELECT nombre FROM aerolinea WHERE id='$aerolinea'";
	$consulta2=mysql_query ($sql_add,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$aerolinea=$fila2['nombre'];
	//*******************************
	
}
else
{
	echo"
	<script>
		alert('Error Al Obtener Los Datos, Informe al Soporte Tecnico');
		document.location='base.php';
	</script>
	";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
	if (document.forms[0].nombre.value=="")
	{
		alert("Atencion: Se requiere el NOMBRE");
		document.forms[0].nombre.focus();
		return(false);
	}
	if (document.forms[0].documento.value=="")
	{
		alert("Atencion: Se requiere un NUMERO DE DOCUMENTO");
		document.forms[0].documento.focus();
		return(false);
	}
	if (document.forms[0].agencia.value=="")
	{
		alert("Atencion: Se requiere una AGENCIA");
		document.forms[0].agencia.focus();
		return(false);
	}
}
//-->
</script>

</head>
<body>
<?php include("menu.php");?>
<p class="titulo_tab_principal">Pre-Inspecci&oacute;n</p>
<table align="center">
  <tr>
    <td width="180" class="celda_tabla_principal"><div class="letreros_tabla">Aerol&iacute;nea</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $aerolinea ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Gu&iacute;a No.</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $hija ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $piezas ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $peso ?>k</td>
  </tr>
</table>
<p align="center"><strong>Autorizo el ingreso de</strong>:</p>
<form name="preinspeccion" action="preinspeccion2.php" enctype="multipart/form-data" method="post" onsubmit="return validar();">
<table align="center">
  <tr>
    <td width="200" rowspan="4" align="center" valign="middle" class="celda_tabla_principal celda_boton">
    	<font color="#0099FF" size="-1"><em>Seleccione una foto</em></font><br />
        <img src="imagenes/camara.png" width="112" height="68" />
        <input type="file" name="foto" id="foto" size="15" value="Foto" tabindex="7" />
    </td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Documento</div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Tel&eacute;fono</div></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal celda_boton">
    	<input type="text" name="nombre" id="nombre" maxlength="250" size="40" tabindex="1" />
      <script>document.getElementById("nombre").focus();</script>
    </td>
    <td class="celda_tabla_principal celda_boton"><input type="text" name="documento" id="documento" maxlength="50" size="10" tabindex="2" onKeyPress="return numeric(event)" /></td>
    <td class="celda_tabla_principal celda_boton"><input type="text" name="telefono" id="telefono" maxlength="50" size="10" tabindex="3" /></td>
  </tr>
  <tr>
    <td colspan="3" class="celda_tabla_principal"><div class="letreros_tabla">Agencia</div></td>
  </tr>
  <tr>
    <td colspan="3" class="celda_tabla_principal celda_boton">
    	<input type="text" name="agencia" id="agencia" maxlength="250" size="50" tabindex="4" /> 
        <input type="hidden" name="id_guia" id="id_guia" value="<?php echo $id_guia ?>"/> 
     </td>
  </tr>
</table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
      	<button type="button" tabindex="9" onclick="document.location='base.php'">
        	<img src="imagenes/al_principio-act.png" title="Atras" />
        </button>
        <button type="reset" tabindex="8">
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
