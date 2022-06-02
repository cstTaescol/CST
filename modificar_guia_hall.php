<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if(isset($_REQUEST['id_guia']))
{
	$id_guia=$_REQUEST['id_guia'];
	
	//Carga datos de la Guia
	$sql="SELECT piezas,peso,volumen,hija FROM guia WHERE id='$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$piezas=$fila["piezas"];
	$peso=$fila["peso"];
	$volumen=$fila["volumen"];
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
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
<div id="datos" style="position:relative;background:url(imagenes/recuadro1.jpg) no-repeat;height:500px; width:530px; left:300px;">
    <br />
    <p align="center" style="font-size:24px;color:#FFF">
    	<strong>MODIFICACION DE GUIA EN HALL</strong><br />
    	<!-- <font size="-1>">GUIA No. <a href="consulta_guia.php?id_guia=<?php echo $id_guia?>" style="color:#FF6" ><?php echo $hija?></a> -->
    </p>
	<div id="contenido" style="position:relative;top:5px;left:30px; width:450px">
          <form method="post" id="guardar_datos">
            <table width="100%" border="1" cellspacing="0" cellpadding="0">
              <tr>
                <td width="30%" bgcolor="#999999"><span class="titulo_add">No. Gu&iacute;a</span></td>
                <td width="70%" align="center" valign="middle">
                	<input name="guia" type="text" id="guia" size="20" maxlength="20" tabindex="2" value="<?php echo $hija ?>" />
                	<input name="id_guia" type="hidden" id="id_guia" value="<?php echo $id_guia ?>" />
                </td>
              </tr>
                <td bgcolor="#999999"><label class="titulo_add">Piezas:</label></td>
                <td align="center" valign="middle">
                    <input type="text" name="piezas" id="piezas" onKeyPress="return numeric(event)" size="5" maxlength="10" tabindex="3" value="<?php echo $piezas ?>"/>
                </td>
              </tr>
              <tr>
                <td bgcolor="#999999"><label class="titulo_add">Peso:</label></td>
                <td align="center" valign="middle"><input name="peso" type="text" id="peso" onKeyPress="return numeric2(event)" size="5" maxlength="10" tabindex="4" value="<?php echo $peso ?>"/></td>
              </tr>              
              <tr>
                <td bgcolor="#999999"><label class="titulo_add">Volumen:</label></td>
                <td align="center" valign="middle"><input name="volumen" type="text" id="volumen" onKeyPress="return numeric2(event)" size="5" maxlength="10" tabindex="5" value="<?php echo $volumen ?>"/></td>
              </tr>
              <tr>
                <td align="center" valign="middle" colspan="2"><div id="respuesta" class="opaco_ie" style="position:relative;opacity:0.3; background-color:#9F0; width:100%; height:30px"></div></td>
              </tr>
            </table>
            <table width="90%" border="0">
                <tr>
                    <td><img src="imagenes/kservices.png" width="59" height="59" /></td>
                    <td>
                    	<button type="button" name="guardar" id="guardar"  onClick="return validar();" tabindex="6">
                    		<img src="imagenes/save.jpg" width="59" height="59" /><br />Guardar
                        </button>
                    	<button type="reset" name="reset" id="reset" tabindex="7"> 
                    		<img src="imagenes/limpiar.jpg" width="59" height="59" /><br />Limpiar
                        </button>
                    	<button type="button" name="cancelar" id="cancelar" onclick="document.location='base.php'" tabindex="8">
                    		<img src="imagenes/error.png" width="59" height="59" /><br />Cancelar
                        </button>                                                
                  </td>
                </tr>
                <tr>
                	<td colspan="2">&nbsp;</td>
                </tr>
            </table>
          </form>     
    </div>
</div>
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
	if (document.forms[0].guia.value=="")
	{
		alert("Atencion: Se requiere No de GUIA");
		document.forms[0].guia.focus();
		return(false);
	}
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
	guardar_form();
}


function guardar_form()
{
	var peticion = new Request(
	{
		url: 'modificar_guia_hall_salvar.php',
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
			document.location='base.php';			
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