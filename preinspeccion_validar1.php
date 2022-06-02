<?php session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

//Discriminacion de aerolinea de usuario TIPO 2
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user=="*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id_aerolinea = '$id_aerolinea_user'";	
//*************************************
$id_guia="";
$nombre="";
$documento="";
$telefono="";
$agencia="";
$imagen="sinfoto.jpg";
$estadoboton="";

if(isset($_REQUEST["registro"]))
{
	$id_preispeccion=$_REQUEST['registro'];
	//1. consulta cuando encuentra coincidencias
	$sql="SELECT * FROM preinspeccion WHERE id='$id_preispeccion'";
	mysql_query($sql,$conexion) or die (mysql_error());
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$id_guia=$fila["id_guia"];
	
	//Validacion Aerolinea
	$sql2="SELECT id FROM guia WHERE id='$id_guia' $sql_aerolinea";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$id=$fila2['id'];
	if ($id == "")
	{
		echo "<script>
					alert('ALERTA:No tiene privilegios de ver esta informacion.');
					document.location='preinspeccion_validar0.php';
			</script>";
		exit();
	}
	//****************************************************

	$nombre=$fila["nombre"];
	if ($nombre=="")
	{
		//Aviso que no ha creado el registro
		echo '<script>
					alert("No exiten registros con ese Valor.");
					document.location="preinspeccion_validar0.php";
			  </script>';
		$estadoboton="disabled=\"disabled\"";
	}
	$documento=$fila["documento"];
	$telefono=$fila["telefono"];
	$agencia=$fila["agencia"];
	$imagen=$fila["foto"];
	if ($imagen=="")
		$imagen="sinfoto.jpg";
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
<?php include("menu.php");?>
<p align="center"><font size="+3"><strong>PRE-INSPECCION</strong></font></p>
<form name="preinspeccion" action="preinspeccion_validar2.php" enctype="multipart/form-data" method="post" onsubmit="return validar();">
<table width="700" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" rowspan="4" align="center" valign="middle">
    	<font color="#0099FF" size="-1"><em> foto del registro</em></font><br />
      <img src="fotos/cumplidos/<?php echo $imagen ?>" width="136" height="141" /></td>
    <td width="300" align="center" bgcolor="#CCCCCC"><strong>NOMBRE</strong></td>
    <td width="100" align="center" bgcolor="#CCCCCC"><strong>DOCUMENTO</strong></td>
    <td width="100" align="center" bgcolor="#CCCCCC"><strong>TELEFONO</strong></td>
  </tr>
  <tr>
    <td><?php echo $nombre ?></td>
    <td><?php echo $documento ?></td>
    <td><?php echo $telefono ?></td>
  </tr>
  <tr>
    <td colspan="3" align="center" bgcolor="#CCCCCC"><strong>AGENCIA</strong></td>
  </tr>
  <tr>
    <td height="120" colspan="3"><?php echo $agencia ?></td>
  </tr>
  <tr>
    <td height="55" colspan="2" align="center" valign="middle">
    	<input type="file" name="foto" id="foto" value="Foto" tabindex="2" />
        <input type="hidden" name="id_guia" value="<?php echo $id_guia ?>" />
        <input type="hidden" name="id_registro" value="<?php echo $id_preispeccion ?>" />
      <img src="imagenes/camara.jpg" alt="Registro Fotografico" width="45" height="43" align="absmiddle" /></td>
    <td colspan="2" align="center" valign="middle">Ingrese la Foto del Encargado de Realizar la<br /> Pre-Inspecci&oacute;n.</td>
  </tr>
  <tr>
    <td colspan="4" align="center" valign="middle">
         <button type="button" name="cancelar" onClick="document.location='consulta_preinspeccion.php'" tabindex="3"><img src="imagenes/error.png" width="59" height="59" alt="Cancelar" /><br />
         <font color="#FF0000"><strong>Cancelar</strong></font>
         </button>
        <button type="submit" name="guardar" tabindex="2" <?php echo $estadoboton ?>><img src="imagenes/save.jpg" width="59" height="59" alt="Guardar Este Documento" /><br />
           <strong><font color="#009900">Validar</font></strong><font color="#009900"><strong></strong></font>
        </button>    
    </td>
  </tr>
</table>
</form>
</body>
</html>
