<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$tablas="";
$id_aerolinea=$_REQUEST["aerolinea"];
$sql="SELECT a.nombre,g.* FROM aerolinea a LEFT JOIN guia g ON a.id = g.id_aerolinea WHERE (id_disposicion='26' OR id_disposicion='27') AND (id_tipo_bloqueo='3' OR id_tipo_bloqueo='6' OR id_tipo_bloqueo='10') AND id_aerolinea='$id_aerolinea' AND g.faltante_total != 'S'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$i=0;
while($fila=mysql_fetch_array($consulta))
{
	$estado="";
	$i++;
	include("config/evaluador_inconsistencias.php");
	//recuperando datos de consignatario		
	$id_consignatario=$fila['id_consignatario'];
	$sql2="SELECT nombre FROM consignatario WHERE id='$id_consignatario'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$aerolinea=$fila["nombre"];
	$cliente=$fila2['nombre'];
	
	$id_guia=$fila['id'];
	if ($fila['id_tipo_bloqueo'] == 10)
	{		
		$peso=$peso-$fila["bloqueo_peso"]-$fila["peso_despacho"];
		$piezas_sinbloqueo=$piezas-$fila['bloqueo_piezas'];
		$volumen=(($volumen / $piezas) * $piezas_sinbloqueo)-$fila["volumen_despacho"];
		$piezas=$piezas-$fila['bloqueo_piezas']-$fila["piezas_despacho"];	
	}
	else
	{
		$peso=$peso-$fila["peso_despacho"];
		$piezas_sinbloqueo=$piezas-$fila['piezas_despacho'];
		$volumen=$volumen -$fila["volumen_despacho"];
		$piezas=$piezas-$fila["piezas_despacho"];		
	}
	if ($piezas == 0 || $peso ==0)
	{
		$estado='disabled="disabled"';
	}
	
	$tablas=$tablas.'
      <tr>
        <td align="center" class="celda_tabla_principal celda_boton"><input type="checkbox" name="accion'.$i.'" value="'.$id_guia.'" '.$estado.' /></td>
        <td align="left" class="celda_tabla_principal celda_boton"><a href="consulta_guia.php?id_guia='.$id_guia.'">'.$fila['hija'].'</a></td>
        <td align="center" class="celda_tabla_principal celda_boton"><input type="text" onKeyPress="return numeric(event)" size="5" maxlength="10" name="piezas'.$i.'" value="'.$piezas.'"/></td>
        <td align="center" class="celda_tabla_principal celda_boton"><input type="text" onKeyup="numeric2(this)" onblur="numeric2(this)" size="5" maxlength="10" name="peso'.$i.'" value="'.$peso.'"/></td>
        <td align="left" class="celda_tabla_principal celda_boton">'.$cliente.'</td>
        <td align="center" class="celda_tabla_principal celda_boton"><input type="text" name="palet'.$i.'" size="20" maxlength="50" /></td>
        <td align="center" class="celda_tabla_principal celda_boton"><input type="text" name="pcs'.$i.'" size="2" maxlength="5" onKeyPress="return numeric2(event)"/></td>
        <td align="center" class="celda_tabla_principal celda_boton">
			<input type="text" name="hhi'.$i.'" size="2" maxlength="2" onKeyPress="return numeric2(event)"/>:
			<input type="text" name="mmi'.$i.'" size="2" maxlength="2" onKeyPress="return numeric2(event)"/>
			<input type="hidden" name="ssi'.$i.'" maxlength="2" value="00" size="2" onKeyPress="return numeric2(event)"/>
		</td>
		<td align="center" class="celda_tabla_principal celda_boton">
			<input type="text" name="hh'.$i.'" size="2" maxlength="2" onKeyPress="return numeric2(event)"/>:
			<input type="text" name="mm'.$i.'" size="2" maxlength="2" onKeyPress="return numeric2(event)"/>
			<input type="hidden" name="ss'.$i.'" maxlength="2" value="00" size="2" onKeyPress="return numeric2(event)"/>
		</td>
        <td align="left" class="celda_tabla_principal celda_boton"><input type="text" name="observaciones'.$i.'" size="20"/></td>
      </tr>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Despacho de Correo</p>
<form name="guardar_datos" id="guardar_datos" method="post">
  <table align="center">
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea:</div></td>
      <td class="celda_tabla_principal celda_boton"><?php echo $aerolinea ?></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Auxiliar que Entrega - A.M.</div></td>
      <td class="celda_tabla_principal celda_boton"><input type="text" name="auxiliaram" tabindex="2"/></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Operario - A.M.</div></td>
      <td class="celda_tabla_principal celda_boton"><input type="text" name="operarioam" tabindex="3"/></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Auxiliar que Entrega - P.M.</div></td>
      <td class="celda_tabla_principal celda_boton"><input type="text" name="auxiliarpm" tabindex="4"/></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Operario - P.M.</div></td>
      <td class="celda_tabla_principal celda_boton"><input type="text" name="operariopm" tabindex="5"/></td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="celda_tabla_principal">
        	<p>
            	<div id="directa"><strong><font size='+3'>ENTREGA DIRECTA</font></strong></div>
                <input type="radio" name="entrega" checked="checked" value="D" onchange="ddirecta();" onclick="ddirecta();"/>
            </p>
      		<p>
            	<div id="bodega"><strong><font size='+1'>ENTREGA EN BODEGA 1</font></strong></div>
                <input type="radio" name="entrega" value="B" onchange="dbodega();" onclick="dbodega();"/>
            </p>
      </td>
    </tr>
  </table>
<br />
    <table align="center">
      <tr> 
            <td class="celda_tabla_principal"><div class="letreros_tabla">...</div></td>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Cliente</div></td>
            <td class="celda_tabla_principal"><div class="letreros_tabla">No. Pallet Utilizado</div></td>
            <td class="celda_tabla_principal"><div class="letreros_tabla">No. PCS x Pallet</div></td>
            <td width="110px" class="celda_tabla_principal"><div class="letreros_tabla">Hora Inicio <br /> hh:mm</div></td>
            <td width="110px" class="celda_tabla_principal"><div class="letreros_tabla">Hora Salida <br /> hh:mm</div></td>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
       </tr>
      <?php echo $tablas ?>
    </table>
   <br />
  <table align="center">
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Pallets Utilizados</div></td>
      <td class="celda_tabla_principal celda_boton">
      	<input name="tpallets" type="text" tabindex="6" size="5" maxlength="5" onKeyPress="return numeric2(event)"/>
        <input type="hidden" name="cantidadguias" value="<?php echo $i; ?>" />
        <input type="hidden" name="id_aerolinea" value="<?php echo $id_aerolinea; ?>" />
      </td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Mallas Utilizadas</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="tmallas" type="text" tabindex="7" size="5" maxlength="5" onKeyPress="return numeric2(event)"/></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Correas Utilizadas</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="tcorreas" type="text" tabindex="8" size="5" maxlength="5" onKeyPress="return numeric2(event)"/></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Dollys Utilizados</div></td>
      <td class="celda_tabla_principal celda_boton"><input name="tdollys" type="text" tabindex="9" size="5" maxlength="5" onKeyPress="return numeric2(event)"/></td>
    </tr>
  </table>
  <br />
  <table align="center">
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Supervisor</div></td>
      <td class="celda_tabla_principal celda_boton"><input type="text" name="supervisor" tabindex="10"/></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Jefe de Bodega</div></td>
      <td class="celda_tabla_principal celda_boton"><input type="text" name="jefe" tabindex="11"/></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Coordinador Responsable</div></td>
      <td class="celda_tabla_principal celda_boton"><input type="text" name="coordinador" tabindex="12"/></td>
    </tr>
  </table>
  <br />
<table align="center">
	<tr>
		<td align="center" valign="middle" colspan="2">
        	<div id="respuesta" class="opaco_ie" style="position:relative; background-image:url(imagenes/background.png);width:100%; height:30px"></div>
        </td>
	</tr>
</table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
      	<button type="button" tabindex="15" name="atras" id="atras" onclick="javascript:history.go(-1);">
        	<img src="imagenes/al_principio-act.png" title="Atras" />
        </button>
        <button type="reset"  tabindex="14" name="reset" id="reset">
        	<img src="imagenes/descargar-act.png" title="Limpiar" />
        </button>
        <button type="button" tabindex="13" name="guardar" id="guardar" onClick="return validar();">
            <img src="imagenes/guardar-act.png" title="Guardar" />
        </button>     
      </td>
    </tr>
</table>
</form>
</body>
</html>

<script language="javascript">
function numeric(e) { // 1
	tecla = (document.all) ? e.keyCode : e.which; // 2
	if (tecla==8) return true; // 3
	patron =/[0-9\n]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
	te = String.fromCharCode(tecla); // 5
	return patron.test(te); // 6
} 
//Validacion de Campos numericos incluyendo el punto
function numeric2(n) { // 1
	permitidos=/[^0-9.]/;
	if(permitidos.test(n.value))
	{
		alert("Solo se Pueden Ingresar Numeros y Puntos");
		n.value="";
		n.focus();
	}
} 

function ddirecta()
{
	document.getElementById("directa").innerHTML='<strong><font size="+3">ENTREGA DIRECTA</font></strong>';
	document.getElementById("bodega").innerHTML='<strong><font size="+1">ENTREGA EN BODEGA 1</font></strong>';
}

function dbodega()
{
	document.getElementById("directa").innerHTML='<strong><font size="+1">ENTREGA DIRECTA</font></strong>';
	document.getElementById("bodega").innerHTML='<strong><font size="+3">ENTREGA EN BODEGA 1</font></strong>';
}

function validar()
{
	var peticion = new Request(
	{
		url: 'despacho_correo2.php',
		method: 'post',
		onRequest: function()
		{
			mostrar_div($('respuesta'));
			$('respuesta').innerHTML='<p align="center">Procesando...<image src="imagenes/cargando.gif"></p>';
			$('guardar').disabled=true;
			$('reset').disabled=true;			
		},			
		onSuccess: function(responseText)
		{
			
			var respuesta=responseText;
			var arreglos = respuesta.split("-");
			var accion = eval(arreglos[0]);
			var coderror = eval(arreglos[1]);
			var mensaje = arreglos[3];
			if (accion == 1)
			{
				var id_despacho = eval(arreglos[2]);
				$('respuesta').innerHTML='<p align="center">Proceso Finalizado</p>';
				document.location='despacho_correo3.php?id_registro='+id_despacho;
				$('guardar').disabled=false;
				$('reset').disabled=false;
			}
			else
			{
				/*
				alert ("accion "+accion);
				alert ("coderror "+coderror);
				alert ("mensaje "+mensaje);
				*/
				switch(coderror)
				{
					case 2:
						var msgerror = 'Error Codigo '+ coderror +' al guardar. <br>DESCRIPCION:'+ mensaje;						
					break;
					
					case 4:
						var msgerror = 'Error Codigo '+ coderror +' al guardar. <br>DESCRIPCION:'+ mensaje;		
					break;
					
					case 5:
						var msgerror = 'Error Codigo '+ coderror +' al guardar. <br>DESCRIPCION:'+ mensaje;		
					break;

					case 8:
						var msgerror = 'Error Codigo '+ coderror +' al guardar. <br>DESCRIPCION:'+ mensaje;		
					break;

					case 14:
						var msgerror = 'Error Codigo '+ coderror +' al guardar. <br>DESCRIPCION:'+ mensaje;
					break;

					default:
						var msgerror = 'Error Codigo '+ coderror +' al guardar. <br>DESCRIPCION: Informe al soporte tecnico. '+ mensaje;	
					break;
				}
				$('respuesta').innerHTML='<p align="center">'+ msgerror+'</p>';
				$('guardar').disabled=false;
				$('reset').disabled=false;
			}
			
		},
		onFailure: function()
		{
			$('respuesta').innerHTML='<p align="center">Error al guardar. <br>DESCRIPCIONIintente de nuevo, si el problema persiste Informe al soporte tecnico.</p>';
			$('guardar').disabled=false;
			$('reset').disabled=false;
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
