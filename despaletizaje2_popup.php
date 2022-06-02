<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_guia=$_GET['id_guia'];
$piezas_totales=0;
$peso_totales=0;
//*************************
$sql_adicional="SELECT hija, piezas, peso FROM guia WHERE id='$id_guia'";
$consulta_adicional=mysql_query ($sql_adicional,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila_adicional=mysql_fetch_array($consulta_adicional);
$hija=$fila_adicional["hija"];
$piezas=$fila_adicional["piezas"];
$peso=$fila_adicional["peso"];
//*************************
$impresion="";
$id_vuelo=$_GET['id_vuelo'];

$sql="SELECT * FROM despaletizaje WHERE id_vuelo='$id_vuelo' AND id_guia='$id_guia' ORDER BY id ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila=mysql_fetch_array($consulta))
{
	$id_despaletizaje=$fila["id"];
	$piezas_recibido=$fila["piezas_recibido"];
	$peso_recibido=$fila["peso_recibido"];
	$piezas_totales=$piezas_totales+$piezas_recibido;
	$peso_totales=$peso_totales+$peso_recibido;	
	$estiba=($fila["estiba"]=="S")?"X":"";
	$huacal=($fila["huacal"]=="S")?"X":"";
	$yute=($fila["yute"]=="S")?"X":"";
	$caja=($fila["caja"]=="S")?"X":"";
	$caneca=($fila["caneca"]=="S")?"X":"";
	$larguero=($fila["larguero"]=="S")?"X":"";
	$abollada=($fila["abollada"]=="S")?"X":"";
	$recintada=($fila["recintada"]=="S")?"X":"";
	$abierta=($fila["abierta"]=="S")?"X":"";
	$rota=($fila["rota"]=="S")?"X":"";
	$destilacion=($fila["destilacion"]=="S")?"X":"";
	$humeda=($fila["humeda"]=="S")?"X":"";
	$impresion=$impresion."
	  <tr>
		<td align=\"center\" bgcolor=\"#FFFFFF\"><a href=\"consulta_guia.php?id_guia=$id_guia\">$hija</a></td>
		<td align=\"center\" bgcolor=\"#FFFFFF\">$piezas_recibido</td>
		<td align=\"center\" bgcolor=\"#FFFFFF\">$peso_recibido</td>
		<td align=\"center\" valign=\"middle\" bgcolor=\"#FFFFFF\">$caja</td>
		<td align=\"center\" valign=\"middle\" bgcolor=\"#FFFFFF\">$yute</td>
		<td align=\"center\" valign=\"middle\" bgcolor=\"#FFFFFF\">$estiba</td>
		<td align=\"center\" valign=\"middle\" bgcolor=\"#FFFFFF\">$caneca</td>
		<td align=\"center\" valign=\"middle\" bgcolor=\"#FFFFFF\">$huacal</td>
		<td align=\"center\" valign=\"middle\" bgcolor=\"#FFFFFF\">$larguero</td>
		<td align=\"center\" valign=\"middle\" bgcolor=\"#FFFFFF\">$rota</td>
		<td align=\"center\" valign=\"middle\" bgcolor=\"#FFFFFF\">$abollada</td>
		<td align=\"center\" valign=\"middle\" bgcolor=\"#FFFFFF\">$abierta</td>
		<td align=\"center\" valign=\"middle\" bgcolor=\"#FFFFFF\">$humeda</td>
		<td align=\"center\" valign=\"middle\" bgcolor=\"#FFFFFF\">$destilacion</td>
		<td align=\"center\" valign=\"middle\" bgcolor=\"#FFFFFF\">$recintada</td>
		<td align=\"center\" valign=\"middle\" class=\"celda_tabla_principal\"><div class=\"letreros_tabla\">
			<button type=\"submit\" title=\"Eliminar\" onclick=\"document.location='despaletizaje4_eliminar.php?id_registro=$id_despaletizaje&id_guia=$id_guia&id_vuelo=$id_vuelo'\">
				<img src=\"imagenes/eliminar-act.png\" />
			</button>
			</div>
		</td>
	  </tr>";
}
$impresion=$impresion."
	  <tr>
		<td align=\"center\" bgcolor=\"#FFFFFF\"><strong>TOTAL</strong></td>
		<td align=\"center\" bgcolor=\"#FFFFFF\"><strong>$piezas_totales</strong></td>
		<td align=\"center\" bgcolor=\"#FFFFFF\"><strong>$peso_totales</strong></td>
		<td align=\"center\" colspan=\"13\" valign=\"middle\" class=\"celda_tabla_principal\"><div class=\"letreros_tabla\"></div>
		</td>
	  </tr>";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>TABLA DE DESPALETIZAJE</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
<!-- General
.titulo_cuadro {	font-weight: bold;
	font-size: 18px;
	color: #039;
}
-->

.checkboxFour {
	width: 40px;
	height: 40px;
	background: #ddd;
	margin: 10px 10px;
	border-radius: 100%;
	position: relative;
	box-shadow: 0px 1px 3px rgba(0,0,0,0.5);
}

.checkboxFour label {
	display: block;
	width: 30px;
	height: 30px;
	border-radius: 100px;

	transition: all .5s ease;
	cursor: pointer;
	position: absolute;
	top: 5px;
	left: 5px;
	z-index: 1;

	background: #333;
	box-shadow:inset 0px 1px 3px rgba(0,0,0,0.5);
}
.checkboxFour input[type=checkbox]:checked + label {
	background: #26ca28;
}

.checkboxFour2 {
	width: 40px;
	height: 40px;
	background: #ddd;
	margin: 10px 10px;
	border-radius: 100%;
	position: relative;
	box-shadow: 0px 1px 3px rgba(0,0,0,0.5);
}

.checkboxFour2 label {
	display: block;
	width: 30px;
	height: 30px;
	border-radius: 100px;

	transition: all .5s ease;
	cursor: pointer;
	position: absolute;
	top: 5px;
	left: 5px;
	z-index: 1;

	background: #333;
	box-shadow:inset 0px 1px 3px rgba(0,0,0,0.5);
}
.checkboxFour2 input[type=checkbox]:checked + label {
	background:#C03;
}


</style>
<script language="javascript">
<!-- función que permite abrir ventanas emergentes con las propiedades deseadas -->
function openPopup(url,name,w,h,props,center){
	l=18;t=18
	if(center){l=(screen.availWidth-w)/2;t=(screen.availHeight-h)/2}
	url=url.replace(/[ ]/g,'%20')
	popup=window.open(url,name,'left='+l+',top='+t+',width='+w+',height='+h+',scrollbars=1'+((props)?','+props:''))
	props=props||''
	if(props.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
	popup.focus()
}
</script>

<script language="javascript">
//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

function numeric2(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9-.\s]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 


function validar()
{
	if (document.forms[0].despaletizaje_piezas.value == "")
	{
		alert("Atencion: Debe digitar una cantidad de PIEZAS");
		document.forms[0].despaletizaje_piezas.focus();
		return(false);
	}
	if (document.forms[0].despaletizaje_piezas.value == 0)
	{
		alert("Atencion: Debe digitar una cantidad de PIEZAS");
		document.forms[0].despaletizaje_piezas.focus();
		return(false);
	}
	if (document.forms[0].despaletizaje_peso.value == "")
	{
		alert("Atencion: Debe digitar una cantidad de PESO");
		document.forms[0].despaletizaje_peso.focus();
		return(false);
	}		
	if (document.forms[0].despaletizaje_peso.value == 0)
	{
		alert("Atencion: Debe digitar una cantidad de PESO");
		document.forms[0].despaletizaje_peso.focus();
		return(false);
	}

	if (document.forms[0].id_guia.value == "")
	{
		alert("ALERTA: ERROR AL OBTENER LOS DATOS, COMUNIQUESE CON EL SOPORTE TECNICO");
		return(false);
	}

	if (document.forms[0].id_vuelo.value == "")
	{
		alert("ALERTA: ERROR AL OBTENER LOS DATOS, COMUNIQUESE CON EL SOPORTE TECNICO");
		return(false);
	}
}
</script>
</head>

<body>
<?php 
include("menu.php");
?>
<p class="titulo_tab_principal">Tabla de Despaletizaje</p>
<table width="90%" align="center" class="celda_tabla_principal">
	<tr>
	  <td class="celda_tabla_principal"><div class="letreros_tabla">Guia No.<?php echo $hija ?></div></td>
      <td class="celda_tabla_principal celda_boton">
                <button type="button" onclick="document.location='despaletizaje1.php?vuelo=<?php echo $id_vuelo ?>'">
                    <img src="imagenes/avion1.gif" width="32" height="29" align="absmiddle"  /><br />
                    Regresar al Vuelo
                </button>        
      </td>
  </tr>
</table>    
<table width="90%" align="center" class="celda_tabla_principal">
  <tr>
    <td width="34%" class="celda_tabla_principal"><div class="letreros_tabla">Piezas Digitadas</div></td>
    <td width="16%" class="celda_tabla_principal celda_boton"><img src="imagenes/btn_amarillo.gif" width="40" height="40" align="absmiddle" /><?php echo $piezas ?></td>
    <td width="38%" class="celda_tabla_principal"><div class="letreros_tabla">Peso Digitado</div></td>
    <td width="12%" class="celda_tabla_principal celda_boton"><img src="imagenes/btn_amarillo.gif" width="40" height="40" align="absmiddle" /><?php echo $peso ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas Despaletizadas</div></td>
    <td class="celda_tabla_principal celda_boton"><img src="imagenes/btn_verde.gif" width="40" height="40" align="absmiddle" /><?php echo $piezas_totales ?></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso Despaletizado</div></td>
    <td class="celda_tabla_principal celda_boton"><img src="imagenes/btn_verde.gif" width="40" height="40" align="absmiddle" /><?php echo $peso_totales ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas Pendeintes.</div></td>
    <td class="celda_tabla_principal celda_boton"><img src="imagenes/btn_rojo.gif" width="40" height="40" align="absmiddle" /><?php echo $piezas-$piezas_totales ?></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso Pendiente.</div></td>
    <td class="celda_tabla_principal celda_boton"><img src="imagenes/btn_rojo.gif" width="40" height="40" align="absmiddle" /><?php echo $peso-$peso_totales ?></td>
  </tr>
</table>
</p>
<form method="post" name="despaletizaje_estado" action="despaletizaje3_guardar_popup.php" onsubmit="return validar();">
<table width="90%" align="center" class="celda_tabla_principal">
  <tr>
    <td colspan="3" rowspan="3" class="celda_tabla_principal"><div class="letreros_tabla">Despaletizaje</div></td>
    <td colspan="6" class="celda_tabla_principal"><div class="letreros_tabla">Empaque</div></td>
    <td colspan="6" class="celda_tabla_principal"><div class="letreros_tabla">Estado</div></td>
  </tr>
  <tr>
    <td rowspan="4" align="center" valign="middle" bgcolor="#CC6600"><img src="imagenes/titulo_CAJA.fw.png" width="15" height="125" /></td>
    <td rowspan="4" align="center" valign="middle" bgcolor="#CC6600"><img src="imagenes/titulo_TULA.fw.png" width="15" height="125" /></td>
    <td rowspan="4" align="center" valign="middle" bgcolor="#CC6600"><img src="imagenes/titulo_ESTIBA.fw.png" width="15" height="125" /></td>
    <td rowspan="4" align="center" valign="middle" bgcolor="#CC6600"><img src="imagenes/titulo_CANECA.fw.png" width="15" height="125" /></td>
    <td rowspan="4" align="center" valign="middle" bgcolor="#CC6600"><img src="imagenes/titulo_HUACAL.fw.png" width="15" height="125" /></td>    
    <td rowspan="4" align="center" valign="middle" bgcolor="#CC6600"><img src="imagenes/titulo_LARGUERO.fw.png" width="15" height="125" /></td>
    <td rowspan="4" align="center" valign="middle" bgcolor="#99FF33"><img src="imagenes/titulo_ROTAS.fw.png" width="15" height="125" /></td>
    <td rowspan="4" align="center" valign="middle" bgcolor="#99FF33"><img src="imagenes/titulo_ABOLLADAS.fw.png" width="15" height="125" /></td>
    <td rowspan="4" align="center" valign="middle" bgcolor="#99FF33"><img src="imagenes/titulo_ABIERTAS.fw.png" width="15" height="125" /></td>
    <td rowspan="4" align="center" valign="middle" bgcolor="#99FF33"><img src="imagenes/titulo_HUMEDA.fw.png" width="15" height="125" /></td>
    <td rowspan="4" align="center" valign="middle" bgcolor="#99FF33"><img src="imagenes/titulo_DESTILACION.fw.png" width="15" height="125" /></td>
    <td rowspan="4" align="center" valign="middle" bgcolor="#99FF33"><img src="imagenes/titulo_RECINTADA.fw.png" width="15" height="125" /></td>
  </tr>
  <tr>    </tr>
  <tr>
    <td rowspan="2" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td colspan="2" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Valores</div></td>
  </tr>
  <tr>
    <td align="center"  class="celda_tabla_principal celda_boton">Piezas</td>
    <td align="center"  class="celda_tabla_principal celda_boton">Peso</td>
  </tr>
  <tr>
    <td align="center"  class="celda_tabla_principal celda_boton">
		<?php echo $hija ?>
        <input type="hidden" name="id_guia" id="id_guia" value="<?php echo $id_guia; ?>" />
        <input type="hidden" name="id_vuelo" id="id_vuelo" value="<?php echo $id_vuelo; ?>"/>

    </td>
    <td align="center"  class="celda_tabla_principal celda_boton">
    	<input type="number" name="despaletizaje_piezas" id="despaletizaje_piezas"  size="5" maxlength="10" onkeypress="return numeric(event)"  value=""/>
    	<script language="javascript">
			document.getElementById("despaletizaje_piezas").focus();
		</script>
    </td>
    <td align="center"  class="celda_tabla_principal celda_boton"><input type="text" name="despaletizaje_peso" id="despaletizaje_peso"  size="5" maxlength="10" value="" onkeypress="return numeric2(event)" /></td>
    <td align="center" valign="middle" bgcolor="#CC6600"><div class="checkboxFour"><input type="checkbox" value="1" id="caja" name="caja" /><label for="caja"></label></div></td>
    <td align="center" valign="middle" bgcolor="#CC6600"><div class="checkboxFour"><input type="checkbox" value="1" id="yute" name="yute" /><label for="yute"></label></div></td>
    <td align="center" valign="middle" bgcolor="#CC6600"><div class="checkboxFour"><input type="checkbox" value="1" id="estiba" name="estiba" /><label for="estiba"></label></div></td>
    <td align="center" valign="middle" bgcolor="#CC6600"><div class="checkboxFour"><input type="checkbox" value="1" id="caneca" name="caneca" /><label for="caneca"></label></div></td>
    <td align="center" valign="middle" bgcolor="#CC6600"><div class="checkboxFour"><input type="checkbox" value="1" id="huacal" name="huacal" /><label for="huacal"></label></div></td>
    <td align="center" valign="middle" bgcolor="#CC6600"><div class="checkboxFour"><input type="checkbox" value="1" id="larguero" name="larguero" /><label for="larguero"></label></div></td>
    <td align="center" valign="middle" bgcolor="#99FF33"><div class="checkboxFour2"><input type="checkbox" value="1" id="rota" name="rota" /><label for="rota"></label></div></td>
    <td align="center" valign="middle" bgcolor="#99FF33"><div class="checkboxFour2"><input type="checkbox" value="1" id="abollada" name="abollada" /><label for="abollada"></label></div></td>
    <td align="center" valign="middle" bgcolor="#99FF33"><div class="checkboxFour2"><input type="checkbox" value="1" id="abierta" name="abierta" /><label for="abierta"></label></div></td>
    <td align="center" valign="middle" bgcolor="#99FF33"><div class="checkboxFour2"><input type="checkbox" value="1" id="humeda" name="humeda" /><label for="humeda"></label></div></td>
    <td align="center" valign="middle" bgcolor="#99FF33"><div class="checkboxFour2"><input type="checkbox" value="1" id="destilacion" name="destilacion" /><label for="destilacion"></label></div></td>
    <td align="center" valign="middle" bgcolor="#99FF33"><div class="checkboxFour2"><input type="checkbox" value="1" id="recintada" name="recintada" /><label for="recintada"></label></div></td>
  </tr>
  <tr>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
    <td colspan="5" align="center" class="celda_tabla_principal celda_boton"><textarea  name="observaciones" rows="3" cols="40"></textarea></td>
    <td colspan="3" align="center" valign="middle"  class="celda_tabla_principal celda_boton">
        <button type="button" onclick="openPopup('despaletizaje6_foto.php?id_guia=<?php echo $id_guia ?>','new','400','400','scrollbars=1',true);" <?php  $id_objeto=98; include("config/provilegios_objeto.php");  echo $activacion ?>>
            <img src="imagenes/camara.png" title="Tomar Foto" height="60" /><br />
            Foto
        </button>                
    </td>
    <td colspan="3" align="center" valign="middle"  class="celda_tabla_principal celda_boton"><button type="reset" title="Limpiar"><img src="imagenes/editar-act.png" height="60"/><br />Limpiar</button></td>
    <td colspan="3" align="center" valign="middle"  class="celda_tabla_principal celda_boton"><button type="submit" title="Guardar"><img src="imagenes/guardar-act.png" height="60"/><br />Guardar</button></td>
  </tr>
</table>
</form>
<hr />
<p align="center"><font size="+2" color="#0066FF"><strong>DATOS REGISTRADOS</strong></font><br />
<table width="90%" align="center" class="celda_tabla_principal">
  <tr>
    <td colspan="3" rowspan="2" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Verificacion de Datos</div></td>
    <td colspan="6" align="center" valign="middle" bgcolor="#CC6600"><strong>Empaque</strong></td>
    <td colspan="6" align="center" valign="middle" bgcolor="#99FF33"><strong>Estado</strong></td>
    <td rowspan="4" align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">Eliminar</div></td>
  </tr>
  <tr>
    <td rowspan="3" align="center" valign="middle" bgcolor="#999999"><img src="imagenes/titulo_CAJA.fw.png" alt="" width="15" height="125" /></td>
    <td rowspan="3" align="center" valign="middle" bgcolor="#999999"><img src="imagenes/titulo_TULA.fw.png" alt="" width="15" height="125" /></td>
    <td rowspan="3" align="center" valign="middle" bgcolor="#999999"><img src="imagenes/titulo_ESTIBA.fw.png" alt="" width="15" height="125" /></td>
    <td rowspan="3" align="center" valign="middle" bgcolor="#999999"><img src="imagenes/titulo_CANECA.fw.png" width="15" height="125" /></td>
    <td rowspan="3" align="center" valign="middle" bgcolor="#999999"><img src="imagenes/titulo_HUACAL.fw.png" width="15" height="125" /></td>
    <td rowspan="3" align="center" valign="middle" bgcolor="#999999"><img src="imagenes/titulo_LARGUERO.fw.png" alt="" width="15" height="125" /></td>
    <td rowspan="3" align="center" valign="middle" bgcolor="#999999"><img src="imagenes/titulo_ROTAS.fw.png" alt="" width="15" height="125" /></td>
    <td rowspan="3" align="center" valign="middle" bgcolor="#999999"><img src="imagenes/titulo_ABOLLADAS.fw.png" alt="" width="15" height="125" /></td>
    <td rowspan="3" align="center" valign="middle" bgcolor="#999999"><img src="imagenes/titulo_ABIERTAS.fw.png" alt="" width="15" height="125" /></td>
    <td rowspan="3" align="center" valign="middle" bgcolor="#999999"><img src="imagenes/titulo_HUMEDA.fw.png" alt="" width="15" height="125" /></td>
    <td rowspan="3" align="center" valign="middle" bgcolor="#999999"><img src="imagenes/titulo_DESTILACION.fw.png" alt="" width="15" height="125" /></td>
    <td rowspan="3" align="center" valign="middle" bgcolor="#999999"><img src="imagenes/titulo_RECINTADA.fw.png" alt="" width="15" height="125" /></td>
  </tr>
  <tr>
    <td rowspan="2" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td colspan="2" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Valores Despaletizados</div></td>
  </tr>
  <tr>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
  </tr>
  <?php echo $impresion; ?>
</table>
</body>
</html>
