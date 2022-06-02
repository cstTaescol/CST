<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
//Discriminacion de aerolinea de usuario  TIPO 1
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user == "*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id = '$id_aerolinea_user'";	
//*************************************/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript">
// 1 Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

// 2 Pasar Valores
function pasar_ciudad_origen(txt_ciudad_origen){
	document.forms[0].cod_ciudad_embarcadora.value=txt_ciudad_origen;
}


// 3. Carga ciudades basandose en el pais seleccionado.
function showPais(str_cod_pais)
{
 if (str_cod_pais=="")
   {
    document.getElementById("dv_ciudad_origen").innerHTML="Cargando";
   	return;
   } 
if (window.XMLHttpRequest)
   {// code for IE7+, Firefox, Chrome, Opera, Safari
   	xmlhttp=new XMLHttpRequest();
   }
else
   {// code for IE6, IE5
   	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
xmlhttp.onreadystatechange=function()
   {
   if (xmlhttp.readyState==4 && xmlhttp.status==200)
     {
     	document.getElementById("dv_ciudad_origen").innerHTML=xmlhttp.responseText;
     }
	 else
	 {
		 document.getElementById("dv_ciudad_origen").innerHTML='Cargando..<img src="imagenes/cargando.gif" align="middle"/><br />';
	 }
   }
xmlhttp.open("GET","ajax_pais.php?cod_pais="+str_cod_pais,true);
xmlhttp.send(); 
//Lipieza de Campos a Usar  
document.forms[0].cod_ciudad_embarcadora.value="";
}
//-------------------------------------------

// funcion para validaacin general
function validar()
{
	if (document.forms[0].aerolinea.value=="*")
	{
		alert("Atencion: Debe seleccionar alguna AEROLINEA");
		document.forms[0].aerolinea.focus();
		return(false);
	}
	if (document.forms[0].ruta.value=="*")
	{
		alert("Atencion: Debe seleccionar alguna RUTA");
		document.forms[0].ruta.focus();
		return(false);
	}
	if (document.forms[0].cod_ciudad_embarcadora.value=="")
	{
		alert("Atencion: Seleccione PAIS y CIUDAD que embarco el vuelo");
		document.forms[0].pais_origen.focus();
		return(false);
	}	
	if (document.forms[0].nvuelo.value=="")
	{
		alert("Atencion: Se requiere el NUMERO de vuelo");
		document.forms[0].nvuelo.focus();
		return(false);
	}
	
	if (document.forms[0].matricula.value=="")
	{
		alert("Atencion: Se requiere el NUMERO de matricula");
		document.forms[0].matricula.focus();
		return(false);
	}
	if (document.forms[0].hh_estimada.value=="")
	{
		alert("Atencion: Se requiere la HORA estimada del Llegada del Vuelo");
		document.forms[0].hh_estimada.focus();
		return(false);
	}
	if (document.forms[0].mm_estimada.value=="")
	{
		alert("Atencion: Se requieren los MINUTOS estimados la hora de LLegada");
		document.forms[0].mm_estimada.focus();
		return(false);
	}
	if (document.forms[0].ss_estimada.value=="")
	{
		alert("Atencion: Se requieren los SEGUNDOS estimados la hora de LLegada");
		document.forms[0].ss_estimada.focus();
		return(false);
	}
	if (document.forms[0].hh_estimada.value > 23)
	{
		alert("Atencion: La HORA Maxima es 23");
		document.forms[0].hh_estimada.focus();
		return(false);
	}
	if (document.forms[0].mm_estimada.value > 59)
	{
		alert("Atencion: La los MINUTOS Maximos son 59");
		document.forms[0].mm_estimada.focus();
		return(false);
	}	
	if (document.forms[0].ss_estimada.value > 59)
	{
		alert("Atencion: La los SEGUNDOS Maximos son 59");
		document.forms[0].ss_estimada.focus();
		return(false);
	}
}
//-->
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <!-Hoja de estilos del calendario -->
  <link rel="stylesheet" type="text/css" media="all" href="js/calendar-color.css" title="win2k-cold-1" />

  <!-- librería principal del calendario -->
  <script type="text/javascript" src="js/calendar.js"></script>

  <!-- librería para cargar el lenguaje deseado -->
  <script type="text/javascript" src="js/calendar-es.js"></script>

  <!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código -->
  <script type="text/javascript" src="js/calendar-setup.js"></script>
</head>
<body>
<?php
require("menu.php");
//Privilegios Consultar Todo el Modulo
$id_objeto=38; 
include("config/provilegios_modulo.php");  
//---------------------------
?>
<p class="titulo_tab_principal">Creacion de Vuelo</p>
<form name="ingresovuelo" method="post" action="vuelo_nuevo2.php" onsubmit="return validar();">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td class="celda_tabla_principal celda_boton">
       <select name="aerolinea" tabindex="1" id="aerolinea">
       		<option value="*">Seleccione una Aerol&iacute;ea</option>
			<?php
				$sql="SELECT id,nombre FROM aerolinea WHERE estado='A' AND importacion = TRUE $sql_aerolinea ORDER BY nombre ASC";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila=mysql_fetch_array($consulta))
				{
					echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
				}
			?>
    </select> <font color="#FF0000"><strong>(*)</strong></font>
<script type="text/javascript">
	document.forms[0].aerolinea.focus();
</script> 

</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Ruta</div></td>
    <td class="celda_tabla_principal celda_boton"><select name="ruta" tabindex="2" id="ruta">
      <option value="*">Seleccione una Ruta</option>
      <?php
				$sql="SELECT id,descripcion FROM ruta WHERE estado='A' ORDER BY descripcion ASC";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila=mysql_fetch_array($consulta))
				{
					echo '<option value="'.$fila['id'].'">'.$fila['descripcion'].'</option>';
				}
			?>
    </select>
    <font color="#FF0000"><strong>(*)</strong></font>
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Pais de Origen</div></td>
    <td class="celda_tabla_principal celda_boton">
    <input name="cod_ciudad_embarcadora" type="hidden" id="cod_ciudad_embarcadora" />
    <select name="pais_origen" id="pais_origen"  onchange="showPais(this.value);" tabindex="3">
      <option value="" selected="selected">Seleccione Uno</option>
      <?php
					$sql="SELECT codigo,nombre FROM pais WHERE estado='A' ORDER BY nombre";
					$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
					while($fila=mysql_fetch_array($consulta))
					{
						echo '<option value="'.$fila['codigo'].'">'.$fila['nombre'].'</option>';
					}
				?>
    </select>
      <font color="#FF0000"><strong>(*) </strong></font><br />
      <div id="dv_ciudad_origen">CIUDAD</div></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Vuelo</div></td>
    <td class="celda_tabla_principal celda_boton"><input type="text" name="nvuelo" size="30" tabindex="7"/><font color="#FF0000"><strong>(*)</strong></font></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Matricula</div></td>
    <td class="celda_tabla_principal celda_boton"><input type="text" name="matricula" size="30" tabindex="8"/><font color="#FF0000"><strong>(*)</strong></font></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Hora de Llegada</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<input type="text" name="hh_estimada" id="hh_estimada" maxlength="2" size="2" tabindex="9" onKeyPress="return numeric(event)"/>:
        <input type="text" name="mm_estimada" id="mm_estimada" maxlength="2" size="2" tabindex="10" value="00" onKeyPress="return numeric(event)"/>:
        <input type="text" name="ss_estimada" id="ss_estimada" maxlength="2" size="2" tabindex="11" value="00" onKeyPress="return numeric(event)"/>hh:mm:ss
    </td>
  </tr>
</table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
      	<button type="button" tabindex="14" onclick="document.location='base.php'">
        	<img src="imagenes/al_principio-act.png" title="Atras" />
        </button>
        <button type="reset" tabindex="13">
        	<img src="imagenes/descargar-act.png" title="Limpiar" />
        </button>
      	<button type="submit" tabindex="12">
        	<img src="imagenes/al_final-act.png" title="Continuar" />
        </button>
      </td>
    </tr>
</table>
</form>
</body>
</html>