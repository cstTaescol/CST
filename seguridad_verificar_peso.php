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
	// Verificacion de Datos Recibidos 
	$sql="SELECT * FROM guia WHERE id = '$id_guia'";
	$consulta=mysql_query($sql,$conexion) or die (exit('Error '.mysql_error()));
	$fila=mysql_fetch_array($consulta);
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
<body>
<?php
require("menu.php");
//Privilegios Consultar Todo el Modulo
$id_objeto=93; 
include("config/provilegios_modulo.php");  
//---------------------------
?>
<p class="titulo_tab_principal">Verificacion de Peso</p>
<p class="asterisco" align="center">Guia No. <a href="consulta_guia.php?id_guia=<?php echo $id_guia?>"><?php echo $hija?></a></p>
<form method="post" id="guardar_datos">
<table width="300" align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td class="celda_tabla_principal celda_boton">
        <input type="text" name="piezas" id="piezas" onKeyPress="return numeric(event)" size="5" maxlength="10" />
        <script language="JavaScript" type="text/javascript">
            document.getElementById('piezas').focus();
        </script>
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td class="celda_tabla_principal celda_boton"><input name="peso" type="text" id="peso" onKeyPress="return numeric2(event)" size="5" maxlength="10"/></td>
  </tr>
  <tr>
    <td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
  </tr>
  <tr>
    <td colspan="2" class="celda_tabla_principal celda_boton"><textarea name="observacion" id="observacion" rows="3" cols="40"></textarea></td>
  </tr>
  <tr>
    <td align="center" valign="middle" colspan="2"><div id="respuesta" style="position:relative;opacity:0.3; background-image:url(imagenes/background.png); width:100%; height:30px"></div></td>
  </tr>
</table>
<input type="hidden" name="id_guia" value="<?php echo $id_guia ?>" />
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
        <button type="reset" name="reset" id="reset">
            <img src="imagenes/descargar-act.png" title="Limpiar" />
        </button>
        <button type="button" name="guardar" id="guardar" onClick="return validar();">
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
	if (document.forms[0].piezas.value=="")
	{
		alert("Atencion: Se requiere cantidad de Piezas");
		document.forms[0].piezas.focus();
		return(false);
	}
	if (document.forms[0].peso.value=="")
	{
		alert("Atencion: Se requiere cantidad de Peso");
		document.forms[0].peso.focus();
		return(false);
	}
	guardar_form();
}


function guardar_form()
{
	var peticion = new Request(
	{
		url: 'seguridad_verificar_peso_salvar.php',
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
			document.location='consulta_guia_buscar.php?origen=verificacion';
			$('guardar').disabled=false;
		},
		onFailure: function()
		{
			$('respuesta').innerHTML='<p align="center">Error al guardar, Intente de nuevo...</p>';
			$('guardar').disabled=false;
		}
	}
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