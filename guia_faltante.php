<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_usuario=$_SESSION['id_usuario'];
$id_guia=$_GET['id_guia'];

//Consulta de datos
$sql="SELECT * FROM guia WHERE id='$id_guia'";
$consulta=mysql_query($sql,$conexion);
$fila=mysql_fetch_array($consulta);
$id_aerolinea=$fila['id_aerolinea'];
$hija=$fila['hija'];
$id_embarcador=$fila['id_embarcador'];
$id_consignatario=$fila['id_consignatario'];
$piezas_faltantes=$fila['piezas_faltantes'];
$peso_faltante=$fila['peso_faltante'];
$volumen=$fila['volumen'];
$descripcion=$fila['descripcion'];
$flete=$fila['flete'];
$observaciones=$fila['observaciones'];
$id_tipo_bloqueo=1;
$fecha_corte=$fila['fecha_corte'];
$fecha_creacion=$fila['fecha_creacion'];
$id_usuario=$_SESSION['id_usuario'];
$id_deposito=$fila['id_deposito'];
$id_disposicion=$fila['id_disposicion'];
$id_vuelo=$fila['id_vuelo'];
$id_administracion_aduana=$fila['id_administracion_aduana'];
$fecha_actual=date("Y").date("m").date("d");
$hora_actual=date("H:i:s");


//Consulta de datos
$sql="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$nvuelo=$fila['nvuelo'];

//Consulta de datos
$sql="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$aerolinea=$fila['nombre'];

//Consulta de datos
$sql="SELECT nombre FROM embarcador WHERE id='$id_embarcador'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$embarcador=$fila['nombre'];

//Consulta de datos
$sql="SELECT nombre FROM consignatario WHERE id='$id_consignatario'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$consignatario=$fila['nombre'];

//Consulta de datos
$sql="SELECT nombre FROM deposito WHERE id='$id_deposito'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$deposito=$fila['nombre'];

//Consulta de datos
$sql="SELECT nombre FROM admon_aduana WHERE id='$id_administracion_aduana'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$aduana=$fila['nombre'];

//Consulta de datos
$sql="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$disposicion=$fila['nombre'];
?>
<html>
<head>
<script type="text/javascript">
//Validacion de campos numéricos
function numeric2(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9-.]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

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
	maximo_peso=<?php echo $peso_faltante ?>;
	maximo_piezas=<?php echo $piezas_faltantes ?>;
	
	if (document.forms[0].piezas_restantes.value > maximo_piezas)
	{
		alert("Atencion: PIEZAS debe ser MAXIMO "+ maximo_piezas);
		document.forms[0].piezas_restantes.focus();
		return(false);
	}

	if (document.forms[0].piezas_restantes.value=="")
	{
		alert("Atencion: Debe diligenciar una cantidad de PIEZAS");
		return(false);
	}

	if (document.forms[0].peso_restante.value=="")
	{
		alert("Atencion: Debe diligenciar una cantidad de PESO");
		document.forms[0].peso_restante.focus();
		return(false);
	}

	if (document.forms[0].peso_restante.value > maximo_peso)
	{
		alert("Atencion: PESO debe ser MAXIMO "+ maximo_peso);
		document.forms[0].peso_restante.focus();
		return(false);
	}
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p align="center">
	<font color="#3399CC" size="+2">REGISTRO DE GUIA FALTANTES</font><br />
    <font color="green" size="+1">GUIA:</font> <?php echo $hija ?><br />
    <font color="red" size="+1">AEROLINEA:</font> <?php echo $aerolinea ?>
</p>
<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center" style="background-color:#969">
<form action="guia_faltante_salvar.php" method="post" onSubmit="return validar();">
<input type="hidden" name="id_aerolinea" value="<?php echo $id_aerolinea ?>">
<input type="hidden" name="id_guia" value="<?php echo $id_guia ?>">
  <tr>
    <td><br />
    <table width="90%" border="1" cellspacing="0" cellpadding="0" align="center" style="background:#FFF">
          <tr>
            <td>ADMINISTRACION ADUANERA</td>
            <td><?php echo $aduana ?><input type="hidden" name="id_aduana" value="<?php echo $id_administracion_aduana ?>"></td>
          </tr>
          <tr>
            <td>DISPOSICION DE CARGA</td>
            <td><p><?php echo $disposicion ?><input type="hidden" name="id_disposicion" value="<?php echo $id_disposicion ?>"></p>
            </td>
          </tr>
          <tr>
            <td>GUIA </td>
            <td><?php echo $hija ?><input type="hidden" name="hija" value="<?php echo $hija ?>"></td>
          </tr>
          <tr>
            <td width="40%">EMBARCADOR</td>
            <td width="60%"><?php echo $embarcador ?><input type="hidden" name="id_embarcador" value="<?php echo $id_embarcador ?>"></td>
          </tr>
          <tr>
            <td>CONSIGNATARIO</td>
            <td><?php echo $consignatario ?><input type="hidden" name="id_consignatario" value="<?php echo $id_consignatario ?>"></td>
          </tr>
          <tr bgcolor="#CCFFFF">
            <td bgcolor="#FFFFFF">DATOS DEL DEPOSITO</td>
            <td bgcolor="#FFFFFF"><?php echo $deposito ?><input type="hidden" name="id_deposito" value="<?php echo $id_deposito ?>"></td>
          </tr>
          <tr>
            <td>PIEZAS RESTANTES</td>
            <td><input name="piezas_restantes" type="text" id="piezas_restantes" value="<?php echo $piezas_faltantes ?>" size="5" maxlength="10" onKeyPress="return numeric(event)" tabindex="1"></td>
          </tr>
          <tr>
            <td>PESO RESTANTE</td>
            <td><input name="peso_restante" type="text" id="peso_restante" value="<?php echo $peso_faltante ?>" size="5" maxlength="10" onKeyPress="return numeric2(event)" tabindex="2"></td>
          </tr>
          <tr>
            <td>VOLUMEN</td>
            <td><input name="volumen_restante" type="text" id="volumen_restante" size="5" maxlength="10" onKeyPress="return numeric2(event)" tabindex="3"></td>
          </tr>
          <tr>
            <td>DESCRIPCION</td>
            <td><?php echo $descripcion ?><input type="hidden" name="descripcion" value="<?php echo $descripcion ?>"></td>
          </tr>
          <tr>
            <td>FLETE</td>
            <td>$<?php echo $flete ?><input type="hidden" name="flete" value="<?php echo $flete ?>"></td>
          </tr>
          <tr>
            <td>FECHA CORTE</td>
            <td><?php echo $fecha_corte ?><input type="hidden" name="fecha_corte" value="<?php echo $fecha_corte ?>"></td>
          </tr>
          <tr>
            <td>OBSERVACION</td>
            <td><textarea name="observaciones" cols="40" rows="5" tabindex="4"></textarea></td>
          </tr>
          <tr>
            <td style="background-color:#969"><input type="reset" value="........ Limpiar ........" tabindex="6"/></td>
            <td style="background-color:#969"><input type="submit" value="........ Guardar ........" tabindex="5" /></td>
          </tr>
        </table>
    <p>&nbsp;</p></td>
  </tr>
</form>
</table>
</body>
</html>
