<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$fecha=date("Y").date("m").date("d");
$fecha_mostrar=date("Y")."-".date("m")."-".date("d");
$hora=date("H:i:s");
$url_retorno="despacho_cabotaje1.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
if (isset($_POST["id_guia"]))
{
	if ($_POST["piezas"]== "" || $_POST["peso"]== "" || $_POST["volumen"]== "" || $_POST["piezas"]==0 || $_POST["peso"]== 0 || $_POST["volumen"]== 0)
	{
		?>	<script>
				alert("ERROR:El servidor no registro PIEZAS, PESO o VOLUMEN para despachar.");
				document.location="<?php echo $url_retorno ?>";
			</script>
         <?php
		 exit();
	}
	$id_guia=$_POST["id_guia"];
	
	$sql="SELECT * FROM guia WHERE id='$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	include("config/evaluador_inconsistencias.php");
	//Captura de datos a despachar
	$recibido_piezas =  $_POST["piezas"];
	$recibido_peso =  $_POST["peso"];
	$recibido_volumen =  $_POST["volumen"];
	
	$piezas_despacho=$fila['piezas_despacho'] + $recibido_piezas;
	$peso_despacho=$fila['peso_despacho'] + $recibido_peso;
	$volumen_despacho=$fila['volumen_despacho'] + $recibido_volumen;
	//Verificacion de despacho NO Superrior al existente
	if ($piezas_despacho > $piezas || $peso_despacho > $peso || $volumen_despacho > $volumen)
	{
		?>
        	<script>
				alert("ERROR:No puede despachar mas PIEZAS, PESO o VOLUMEN del que hay disponible.");
				document.location="<?php echo $url_retorno ?>";
			</script>
        <?php
		exit();
	}	
	//************************
	//Verificacion de despacho TOTAL o PARCIAL para TODOS los items.
	if (($piezas-$piezas_despacho) == 0)
	{
		if (($peso-$peso_despacho) != 0 || ($volumen-$volumen_despacho) != 0)
		{
			?>
				<script>
					alert("ERROR: No puede dejar CARGA PARCIAL en solo un ITEM");
					document.location="<?php echo $url_retorno ?>";
				</script>
			<?php
			exit();
		}
	}
	if (($peso-$peso_despacho) == 0)
	{
		if (($piezas-$piezas_despacho) != 0 || ($volumen-$volumen_despacho) != 0)
		{
			?>
				<script>
					alert("ERROR: No puede dejar CARGA PARCIAL en solo un ITEM");
					document.location="<?php echo $url_retorno ?>";
				</script>
			<?php
			exit();
		}
	}
	if (($volumen-$volumen_despacho) == 0)
	{
		if (($piezas-$piezas_despacho) != 0 || ($piezas-$piezas_despacho) != 0)
		{
			?>
				<script>
					alert("ERROR: No puede dejar CARGA PARCIAL en solo un ITEM");
					document.location="<?php echo $url_retorno ?>";
				</script>
			<?php
			exit();
		}
	}
	//******************************
	
	//recuperando datos de vuelo		
	$id_vuelo=$fila['id_vuelo'];
	$descripcion=$fila['descripcion']; //***
	$sql2="SELECT id_aerolinea,nmanifiesto FROM vuelo WHERE id='$id_vuelo'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$id_aerolinea=$fila2['id_aerolinea'];
	$nmanifiesto=$fila2['nmanifiesto']; //***
	//recuperando datos de aerolinea
	$sql2="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila2=mysql_fetch_array($consulta2);
	$aerolinea=$fila2['nombre']; //***
}
?>
</p>
<p class="titulo_tab_principal">Despacho por Cabotaje</p>
<table align="center">
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
    <td class="celda_tabla_principal celda_boton"><?php echo $recibido_piezas ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo number_format($recibido_peso,2,".",""); ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo number_format($recibido_volumen,2,".",""); ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Descripcion</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $descripcion ?></td>
  </tr>
</table>
<hr />
<p align="center" class="asterisco">Datos Adicionales</p>
<form name="guardar_datos" id="guardar_datos" method="post" onsubmit="return validar();">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Trasbordo a:</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<input name="destinatario" type="text" id="aerolinea" tabindex="1" size="50" maxlength="40" />
        <input type="hidden" name="id_guia" value="<?php echo $id_guia ?>"/>        
        <input type="hidden" name="fecha" value="<?php echo $fecha ?>"/> 
        <input type="hidden" name="hora" value="<?php echo $hora ?>"/>
        <input type="hidden" name="piezas" value="<?php echo $recibido_piezas ?>"/>
        <input type="hidden" name="peso" value="<?php echo $recibido_peso ?>"/>
        <input type="hidden" name="volumen" value="<?php echo $recibido_volumen ?>"/>
    </td>
    <script type="text/javascript">
		document.forms[0].aerolinea.focus();
	</script>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Observaciones:</div></td>
    <td class="celda_tabla_principal celda_boton"><textarea name="observaciones" cols="40" rows="5" tabindex="2"></textarea></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $fecha_mostrar ?></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Hora</div></td>
    <td class="celda_tabla_principal celda_boton"><?php echo $hora ?></td>
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
// funcion para validar
function validar()
{
	if (document.forms[0].aerolinea.value=="")
	{
		alert("Atencion: Debe digitar un DESTINATARIO");
		document.forms[0].aerolinea.focus();
		return(false);
	}
	guardar_form();
}
function guardar_form()
{
	var peticion = new Request(
	{
		url: 'despacho_cabotaje3.php',
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
				document.location='despacho_cabotaje4.php?id_registro='+id_despacho;
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
