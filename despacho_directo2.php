<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if (isset($_POST["id_guia"]))
{
	$id_guia=$_POST["id_guia"];
	$piezas_digitadas=$_POST["piezas"];
	$peso_digitado=$_POST["peso"];

	$sql="SELECT * FROM guia WHERE id='$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila=mysql_fetch_array($consulta))
	{
		include("config/evaluador_inconsistencias.php");
		if ($fila['id_tipo_bloqueo'] == 10) //Con bloqueo parcial
		{
			$peso=$peso-$fila["bloqueo_peso"]-$fila["peso_despacho"];
			$piezas_sinbloqueo=$piezas-$fila['bloqueo_piezas'];
			$volumen=(($volumen / $piezas) * $piezas_sinbloqueo)-$fila["volumen_despacho"];
			$piezas=$piezas-$fila['bloqueo_piezas']-$fila["piezas_despacho"];	
		}
		else // Sin bloqueo
		{
			$peso=$peso-$fila["peso_despacho"];
			$piezas_sinbloqueo=$piezas-$fila['piezas_despacho'];
			$volumen=$volumen -$fila["volumen_despacho"];
			$piezas=$piezas-$fila["piezas_despacho"];		
		}
		//Evaluacion de Valores incorrectos como superiores, 0, en blanco.
		if($piezas_digitadas > $piezas || $peso_digitado > $peso || $peso_digitado == 0 || $peso_digitado == "" || $piezas_digitadas == 0 || $piezas_digitadas == "")
		{
			echo "<script>
					alert('ALERTA:Esta digitando un valor incorrecto para el despacho en PIEZAS o PESO');
					document.location='despacho_directo1.php';
				</script>";
			exit();			
		}
		else
		{
			$volumen=($volumen/$piezas)*$piezas_digitadas;
			$piezas=$piezas_digitadas;
			$peso=$peso_digitado;			
		}
		
		
		if ($piezas == 0 || $peso ==0)
		{
			echo "<script>
					alert('ALERTA:Esta GUIA se encuentra BLOQUEADA y no tienes PIEZAS PARCIALES desbloqueadas');
					document.location='consulta_guia.php?id_guia=$id_guia';
				</script>";
			exit();
		}	
		$volumen=number_format($fila["volumen"],2,".",".");
		$hija=$fila["hija"];
		//recuperando datos de vuelo		
		$id_vuelo=$fila['id_vuelo'];
		$descripcion=$fila['descripcion']; //***
		$sql2="SELECT id_aerolinea,nmanifiesto FROM vuelo WHERE id='$id_vuelo'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$id_aerolinea=$fila2['id_aerolinea'];
		$nmanifiesto=$fila2['nmanifiesto']; //**
		//recuperando datos de aerolinea
		$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
		$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila2=mysql_fetch_array($consulta2);
		$aerolinea=$fila2['nombre']; //**		
	}
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
<p class="titulo_tab_principal">Despacho a Cliente</p>
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $hija ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $aerolinea ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Manifiesto</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $nmanifiesto ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $piezas ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $peso ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $volumen ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Descripcion</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $descripcion ?></td>
  </tr>
</table>
<hr />
<p align="center" class="asterisco">Datos Adicionales</p>
<form name="guardar_datos" id="guardar_datos" method="post" >
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">No. Levante</div></td>
    <td colspan="3" class="celda_tabla_principal celda_boton">
    	<input name="levante" type="text" id="levante" tabindex="1" size="50" maxlength="40" />
        <input type="hidden" name="id_guia" value="<?php echo $id_guia ?>"/>
        <input type="hidden" name="piezas" value="<?php echo $piezas ?>"/>
        <input type="hidden" name="peso" value="<?php echo $peso ?>"/>
        <input type="hidden" name="volumen" value="<?php echo $volumen ?>"/>
    </td>
    <script type="text/javascript">
		document.forms[0].levante.focus();
	</script>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Declaracion</div></td>
    <td colspan="3" class="celda_tabla_principal celda_boton"><input name="declaracion" type="text" id="declaracion" tabindex="2" size="50" maxlength="40" /></td>
  </tr>
  <tr>
    <td colspan="4" class="celda_tabla_principal"><div class="letreros_tabla">Entregado A:</div></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
    <td width="36%"class="celda_tabla_principal celda_boton">
    	<input name="nombre" type="text" id="nombre" tabindex="3" size="40" maxlength="40" />
       	<button type="button" name="cancelar" onclick="conductor.value=nombre.value" tabindex="4" >
        	<img src="imagenes/adelante-act.png" title="Pasar" align="absmiddle"/>
         </button>
    </td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Conductor</div></td>
    <td width="35%" class="celda_tabla_principal celda_boton"><input name="conductor" type="text" id="conductor" tabindex="10" size="40" maxlength="40" /></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Agencia</div></td>
    <td colspan="3" class="celda_tabla_principal celda_boton"><input name="agencia" type="text" id="agencia" tabindex="5" size="50" maxlength="50" /></td>
    </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Cedula</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<input name="cedula" type="text" id="cedula" tabindex="6" size="40" maxlength="20" />
       	<button type="button" name="cancelar" onclick="cedula_conductor.value=cedula.value" tabindex="7" >
        	<img src="imagenes/adelante-act.png" title="Pasar" align="absmiddle"/>
         </button>
    </td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Cedula Conductor</div></td>
    <td class="celda_tabla_principal celda_boton"><input name="cedula_conductor" type="text" id="cedula_conductor" tabindex="11" size="40" maxlength="20" /></td>
    </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono</div></td>
    <td class="celda_tabla_principal celda_boton"><input name="telefono" type="text" id="telefono" tabindex="9" size="20" maxlength="20" /></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Placa</div></td>
    <td class="celda_tabla_principal celda_boton"><input name="placa" type="text" id="placa" tabindex="12" value="" size="6" maxlength="6" /></td>
    </tr>
</table>
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
      	<button type="button" tabindex="15" name="atras" id="atras" onclick="document.location='despacho_directo1.php'">
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
// funcion para validar
function validar()
{
	if (document.forms[0].levante.value=="")
	{
		alert("Atencion: Debe digitar un Numero de Levante");
		document.forms[0].levante.focus();
		return(false);
	}
	
	if (document.forms[0].declaracion.value=="")
	{
		alert("Atencion: Debe digitar un Numero de Declaracion");
		document.forms[0].declaracion.focus();
		return(false);
	}
	if (document.forms[0].nombre.value=="")
	{
		alert("Atencion: Debe digitar un Nombre de quien Recibe la carga.");
		document.forms[0].nombre.focus();
		return(false);
	}
	if (document.forms[0].agencia.value=="")
	{
		alert("Atencion: Debe digitar un Nombre de Agencia");
		document.forms[0].agencia.focus();
		return(false);
	}
	if (document.forms[0].cedula.value=="")
	{
		alert("Atencion: Debe digitar un Numero de cedula de quien Recibe la carga.");
		document.forms[0].cedula.focus();
		return(false);
	}
	if (document.forms[0].conductor.value=="")
	{
		alert("Atencion: Debe digitar un Nombre de Conductor");
		document.forms[0].conductor.focus();
		return(false);
	}
	if (document.forms[0].cedula_conductor.value=="")
	{
		alert("Atencion: Debe digitar un Numero de Cedula de Conductor");
		document.forms[0].cedula_conductor.focus();
		return(false);
	}
	if (document.forms[0].placa.value=="")
	{
		alert("Atencion: Debe digitar un Numero de Placa O ingrese la palabra MANUAL para un retiro manual de Mercancias");
		document.forms[0].placa.focus();
		return(false);
	}
	
	guardar_form();
}

function guardar_form()
{
	var peticion = new Request(
	{
		url: 'despacho_directo3.php',
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
				document.location='despacho_directo4.php?id_registro='+id_despacho;
				$('guardar').disabled=false;
				$('reset').disabled=false;
			}
			else
			{
				$('respuesta').innerHTML='<p align="center">Error Codigo '+ coderror + mensaje +' al guardar, Intente de nuevo...</p>';
				$('guardar').disabled=false;
				$('reset').disabled=false;
			}
			
		},
		onFailure: function()
		{
			$('respuesta').innerHTML='<p align="center">Error al guardar, Intente de nuevo...</p>';
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
