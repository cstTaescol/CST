<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<?php
if(isset($_REQUEST['id_guia']))
{
	$id_guia=$_REQUEST['id_guia'];
	
	//Carga datos de la Guia
	$sql="SELECT piezas,peso,volumen,piezas_inconsistencia,peso_inconsistencia,hija FROM guia WHERE id='$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$piezas=$fila["piezas"];
	$peso=$fila["peso"];
	$volumen=$fila["volumen"];
	$piezas_inconsistencia=$fila["piezas_inconsistencia"];
	$peso_inconsistencia=$fila["peso_inconsistencia"];
	$hija=$fila["hija"];
}
else
{
	?>
    <script>
		alert('Error: El servidor no pudo obtener la informacion, intentelo de nuevo');
		document.location='base.php';
	</script>
    <?php
}
?>
<head>
	<style>
        .titulo_add{
            color:#FFF;
        }
		.opaco_ie { 
 			filter: alpha(opacity=30);
		}
 
    </style>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Correccion De Inconsistencias</p>
<p align="center" class="asterisco">Guia No. <a href="consulta_guia.php?id_guia=<?php echo $id_guia?>"><?php echo $hija?></a></p>
<form method="post" id="guardar_datos">
<table width="600" align="center">
  <tr>
    <td width="70%" class="celda_tabla_principal"><div class="letreros_tabla">Piezas de la Gu&iacute;a:</div></td>
    <td width="30%" align="center" valign="middle" class="celda_tabla_principal celda_boton"><font color="#FF0000"><strong>(*) </strong></font><input type="text" name="piezas" id="piezas" onkeypress="return numeric(event)" size="5" maxlength="10" value="<?php echo $piezas ?>" />
      <script language="JavaScript" type="text/javascript">
                document.getElementById('piezas').focus();
            </script></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso de la Gu&iacute;a:</div></td>
    <td align="center" valign="middle" class="celda_tabla_principal celda_boton"><font color="#FF0000"><strong>(*) </strong></font><input name="peso" type="text" id="peso" onkeypress="return numeric2(event)" size="5" maxlength="10" value="<?php echo $peso ?>"/></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Volumen de la Gu&iacute;a:</div></td>
    <td align="center" valign="middle" class="celda_tabla_principal celda_boton"> <font color="#FF0000"><strong>(*) </strong></font><input name="volumen" type="text" id="volumen" onkeypress="return numeric2(event)" size="5" maxlength="10" value="<?php echo $volumen ?>"/></td>
  </tr>
  <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas Despaletizadas:</div></td>
    <td align="center" valign="middle" class="celda_tabla_principal celda_boton"><font color="#FF0000"><strong>(*) </strong></font><input type="text" name="piezas_inconsistencia" id="piezas_inconsistencia" onkeypress="return numeric(event)" size="5" maxlength="10" value="<?php echo $piezas_inconsistencia ?>" /></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso Despaletizado:</div></td>
    <td align="center" valign="middle" class="celda_tabla_principal celda_boton"><font color="#FF0000"><strong>(*) </strong></font><input name="peso_inconsistencia" type="text" id="peso_inconsistencia" onkeypress="return numeric2(event)" size="5" maxlength="10" value="<?php echo $peso_inconsistencia ?>"/></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2">
    	<div id="respuesta" class="opaco_ie" style="position:relative;opacity:0.3; background-image:url(imagenes/background.png); width:100%; height:30px"></div>
    </td>
  </tr>
</table>
<input type="hidden" name="id_guia" value="<?php echo $id_guia ?>" />
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
      	<button type="button" onclick="document.location='correcciones.php'">
        	<img src="imagenes/al_principio-act.png" title="Atras" />
        </button>
        <button type="reset">
        	<img src="imagenes/descargar-act.png" title="Limpiar" />
        </button>
      	<button type="button" id="guardar" onclick="return validar();">
        	<img src="imagenes/guardar-act.png" title="Guardar" />
        </button>
      </td>
    </tr>
 </table>
</form>
</body>
</html>
<script language="JavaScript">
//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

//Validacion de campos numéricos
function numeric2(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9-.]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

// funcion para validar
function validar()
{	
	if (document.forms[0].piezas.value=="" || document.forms[0].piezas.value==0)
	{
		alert("Atencion: Se requiere cantidad de PIEZAS de la Guia");
		document.forms[0].piezas.focus();
		return(false);
	}
	if (document.forms[0].peso.value=="" || document.forms[0].peso.value==0)
	{
		alert("Atencion: Se requiere cantidad de PESO de la Guia");
		document.forms[0].peso.focus();
		return(false);
	}
	if (document.forms[0].volumen.value=="" || document.forms[0].volumen.value==0)
	{
		alert("Atencion: Se requiere cantidad de VOLUMEN de la Guia");
		document.forms[0].volumen.focus();
		return(false);
	}	
	if (document.forms[0].piezas_inconsistencia.value==0 || document.forms[0].piezas_inconsistencia.value=="")
	{
		if (document.forms[0].peso_inconsistencia.value!=0 || document.forms[0].peso_inconsistencia.value!="")
		{		
			alert("Atencion: Se requiere cantidad de PIEZAS y PESO DESPALETIZADOS");
			document.forms[0].faltante_piezas.focus();
			return(false);
		}
	}
	if (document.forms[0].peso_inconsistencia.value==0 || document.forms[0].peso_inconsistencia.value=="")
	{
		if (document.forms[0].piezas_inconsistencia.value!=0 || document.forms[0].piezas_inconsistencia.value!="")
		{		
			alert("Atencion: Se requiere cantidad de PIEZAS y PESO DESPALETIZADOS");
			document.forms[0].faltante_peso.focus();
			return(false);
		}
	}
	guardar_form();
}


function guardar_form()
{
	var peticion = new Request(
	{
		url: 'correccion_inconsistencias1_salvar.php',
		method: 'post',
		onRequest: function()
		{
			
			mostrar_div($('respuesta'));
			$('respuesta').innerHTML='<p align="center">Procesando...<image src="imagenes/cargando.gif"></p>';
			$('guardar').disabled=true;
		},			
		onSuccess: function(responseText)
		{
			alert(responseText);
			$('respuesta').innerHTML='<p align="center">Proceso Finalizado</p>';
			$('guardar').disabled=false;
			document.location='correcciones.php';
		},
		onFailure: function()
		{
			$('respuesta').innerHTML='<p align="center">Error al guardar, Intente de nuevo...</p>';
			$('guardar').disabled=false;
		}	}
	);
	peticion.send($('guardar_datos'));
}

function mostrar_div(id_div)
{
	id_div.set('morph',{ 
	duration: 200, 
	transition: 'linear'
	});
	id_div.morph({
		'opacity': 1 
	});
}
</script>