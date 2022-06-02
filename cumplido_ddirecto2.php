<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_usuario=$_SESSION['id_usuario'];
$id_despacho=$_REQUEST['id_despacho'];
?>
<html>
<head>
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
	if (document.forms[0].foto.value=="")
	{
		alert("Atencion: Se requiere una FOTO");
		document.forms[0].foto.focus();
		return(false);
	}
}
//-->
</script>
</head>
<body>
<?php require("menu.php"); ?>
<p align="center"><font size="+2" color="#0066FF"><strong>REPORTAR CUMPLIDOS</strong></font>
  <img src="imagenes/sello.gif" width="50" height="50" align="absmiddle" />
</p>
<form action="cumplido_ddirecto3.php" method="post" enctype="multipart/form-data" onSubmit="return validar();">
<table width="50%" border="1" align="center" bordercolor="#0066CC">
  <tr>
    <td colspan="2" bgcolor="#CCCCCC">NOMBRE</td>
  </tr>
  <tr>
    <td align="right"><img src="imagenes/flecha1.jpg" width="22" height="24" /></td>
    <td >
    	<input name="nombre" type="text" id="nombre" size="50" maxlength="50" tabindex="1"/>
        <script language="javascript">
			document.getElementById('nombre').focus();
		</script>

    </td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#CCCCCC">DOCUMENTO DE IDENTIFICACION</td>
  </tr>
  <tr>
    <td align="right"><img src="imagenes/flecha1.jpg" width="22" height="24" /></td>
    <td><input name="documento" type="text" id="documento" size="50" maxlength="15" tabindex="2" onKeyPress="return numeric(event);"/></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#CCCCCC">FOTO</td>
  </tr>
  <tr>
    <td align="right"><img src="imagenes/flecha1.jpg" width="22" height="24" /></td>
    <td><input name="foto" type="file" size="35" tabindex="3" /></td>
  </tr>
</table>
<p align="center">
<input type="hidden" name="id_despacho" value="<?php echo $id_despacho ?>">
<button type="button" name="cancelar" onClick="document.location='base.php'" tabindex="5">
    <img src="imagenes/error.png" width="59" height="59" alt="Cancelar..." /><br />
    <font color="#FF0000"><strong>Cancelar</strong></font>
</button>
<button type="submit" name="guardar" tabindex="4"><img src="imagenes/save.jpg" width="59" height="59" alt="Guardar..." /><br />
    <font color="#009900"><strong>Guardar</strong></font>
</button>
</p>
</form>    
</body>
</html>

