<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if(isset($_GET["vuelo"]))
	{
		$vuelo=$_GET["vuelo"];
		$id_vuelo=$vuelo;
		$sql3="SELECT id_aerolinea,nvuelo FROM vuelo WHERE id='$vuelo'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$id_aerolinea=$fila3["id_aerolinea"];
		$nvuelo=$fila3["nvuelo"];
	}
	else
		{
			$vuelo= "Error al obtener el Vuelo";
		}
$datos="";
$impresion2="";
//Activar o desactivar todas las guia
$activaciones="";
$desactivaciones="";
$total_peso=0;
//***********************************
$sql="SELECT v.id_aerolinea,v.nvuelo,g.hija,g.piezas,g.peso,g.id, g.id_deposito, g.id_tipo_bloqueo, g.id_tipo_guia,g.id_disposicion,g.faltante_total FROM vuelo v LEFT JOIN guia g ON v.id = g.id_vuelo WHERE v.id = '$vuelo' AND g.id_tipo_guia != '2' AND g.id_tipo_bloqueo = '2' ORDER BY g.id_deposito, v.id, g.master";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nguias=mysql_num_rows($consulta);
$_SESSION["nguias"]=$nguias;		
if ($nguias > 0)
{
	//$encendido=""; //Habilita el boton de agregar guias nuevas al vuelo por que aun existen guias en el vuelo pendiente por inconsistencias
	for ($i=1; $i<=$nguias; $i++)
	{
		$color="";
		$fila=mysql_fetch_array($consulta);
		//identificacion tipo de guia
		$id_tipo_guia=$fila["id_tipo_guia"];
		if ($id_tipo_guia==3)
			$id_tipo_guia="CONSOLIDADO";
		else
		{
			$sql3="SELECT nombre FROM tipo_guia WHERE id='$id_tipo_guia'";
			$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila3=mysql_fetch_array($consulta3);
			$id_tipo_guia=$fila3["nombre"];
		}
		//************************
		$hija=$fila["hija"];
		$nvuelo=$fila['nvuelo'];
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		$total_peso += $peso;
		// Destino
		$id_disposicion=$fila["id_disposicion"];
		//Evaluar si la disposicion no exige ningun tipo de deposito
		if ($id_disposicion ==28 || $id_disposicion ==21 || $id_disposicion ==20 || $id_disposicion ==19 || $id_disposicion ==25 || $id_disposicion ==29 || $id_disposicion ==23 || $id_disposicion ==13 || $id_disposicion ==15)
			{
				$sql3="SELECT nombre FROM disposicion_cargue WHERE id='$id_disposicion'";
				$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				$fila3=mysql_fetch_array($consulta3);
				$destino=$fila3["nombre"];
			}
		else
		{
			$id_deposito=$fila["id_deposito"];
			$sql3="SELECT nombre FROM deposito WHERE id='$id_deposito'";
			$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila3=mysql_fetch_array($consulta3);
			$destino=$fila3["nombre"];
		}
		if ($id_disposicion == 26 || $id_disposicion == 27) 
		{
			$destino="Bodega Dian";
			$color='color="green"';
		}

		$id_guia=$fila["id"];
		$id_tipo_bloqueo=$fila["id_tipo_bloqueo"];
		switch ($id_tipo_bloqueo)
		{
			case 2:
			$img='';
			break;
			
			case 3:
			$img='<img src="imagenes/chek-green.jpg" width="13" height="13" />';
			break;			
		}
		$faltante_total=$fila["faltante_total"];
		if ($faltante_total=="S")
		{
			$faltante_total='checked="checked"';	
		}
		else
			{
				$faltante_total='';
			}
		
		//SOLO HABILITAR ESTA OPCION EN LOS CLIENTES QUE COMPREN EL SERVICIO DE DESPALETIZAJE.
		//consulta de Piezas y peso Despaletizado
		$sql3="SELECT SUM(piezas_recibido) AS total_piezas, SUM(peso_recibido) AS total_peso FROM despaletizaje WHERE id_vuelo='$vuelo' AND id_guia='$id_guia'";
		$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila3=mysql_fetch_array($consulta3);
		$piezas_despaletizado=round($fila3["total_piezas"],1);
		$peso_despaletizado=round($fila3["total_peso"],2);
		//if ($piezas_recibido==0)$piezas_recibido="";
		//if ($peso_recibido==0)$peso_recibido="";
		//******************************************/
		//$piezas_recibido="";
		//$peso_recibido="";
		$datos=$datos.'
			<tr>
				<td align="left" class="celda_tabla_principal celda_boton">'.$id_tipo_guia.'</td>
				<td align="center" class="celda_tabla_principal celda_boton">'.$nvuelo.'</td>
				<td align="center" class="celda_tabla_principal celda_boton">'.$hija.'</td>
				<td align="center" class="celda_tabla_principal celda_boton">'.$piezas.'</td>
				<td align="center" class="celda_tabla_principal celda_boton">'.$peso.'</td>				
				<td align="center" class="celda_tabla_principal celda_boton"><input type="text" name="piezas'.$i.'" size="5" maxlength="10" value="'.$piezas_despaletizado.'" onKeyPress="return numeric(event)"></td>
				<td align="center" class="celda_tabla_principal celda_boton"><input type="text" name="peso'.$i.'" size="5" maxlength="10" value="'.$peso_despaletizado.'" onKeyPress="return numeric2(event)"></td>
				<td align="center" class="celda_tabla_principal celda_boton"><input type="checkbox" name="faltante_total'.$i.'" id="faltante_total'.$i.'" value="1" '.$faltante_total.'></td>
				<td align="center" class="celda_tabla_principal celda_boton"><input type="checkbox" name="finalizar'.$i.'" id="finalizar'.$i.'" value="1"></td>
				<td align="left" class="celda_tabla_principal celda_boton"><font size="-1" '.$color.'>'.$destino.'</font></td>
				<input type="hidden" name="guia'.$i.'" value="'.$id_guia.'">
				<input type="hidden" name="vuelo" value="'.$vuelo.'">
			  </tr>';
		$activaciones=$activaciones."document.getElementById('finalizar$i').checked=true;\n";
		$desactivaciones=$desactivaciones."document.getElementById('finalizar$i').checked=false;\n";

	}
}
else
	{
		//$encendido='disabled="disabled"'; ////Des-Habilita el boton de agregar guias nuevas al vuelo por que ya no hay guias disponibles para inconsistencias
	}
$total_peso_sobrante=0;
$sql="SELECT * FROM despaletizaje_sobantes WHERE id_vuelo='$id_vuelo' AND estado='A'";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nguias=mysql_num_rows($consulta);	
if ($nguias > 0)
{
	for ($i=1; $i<=$nguias; $i++)
	{
		$fila=mysql_fetch_array($consulta);
		$id_sobrante=$fila["id"];
		$sobrante_guia=$fila["guia"];
		$piezas=$fila["piezas"];
		$peso=$fila["peso"];
		$total_peso_sobrante +=$peso;
		$volumen=$fila["volumen"];
		$impresion2=$impresion2.'
		  <tr>
			<td align="center" class="celda_tabla_principal celda_boton"><strong>'.$i.'</strong></td>
			<td align="center" class="celda_tabla_principal celda_boton"><strong>'.$sobrante_guia.'</strong></td>
			<td align="center" class="celda_tabla_principal celda_boton"><strong>'.$piezas.'</strong></td>
			<td align="center" class="celda_tabla_principal celda_boton"><strong>'.$peso.'</strong></td>
			<td align="center" class="celda_tabla_principal celda_boton"><strong>'.$volumen.'</strong></td>
			<td align="center" class="celda_tabla_principal celda_boton">
				<button type="button" onclick="openPopup(\'guia_sobrante_eliminar.php?id_sobrante='.$id_sobrante.'\',\'new\',\'900\',\'450\',\'scrollbars=1\',true);">
					X
				</button>
			</td>
		  </tr>';
	}
}

$msg_alerta_sobrante="";
//Calculo de PORCENTAJE sobrante X Vuelo
$porcentaje_sobrante=($total_peso_sobrante * 100) / $total_peso;
$porcentaje_sobrante=number_format($porcentaje_sobrante,1)."%";
//$msg_alerta_sobrante -= $total_peso_sobrante;
if (($porcentaje_sobrante >= 20))
	$msg_alerta_sobrante .='<br><img src="imagenes/alerta_red.gif" height="28" width="37" align="absmiddle"/><span style="color:red">- Alerta:</span>Sobrepas&oacute; el limite de PORCENTAJE Total de Guias sobrantes en '.$porcentaje_sobrante.'
							<script>alert("ALERTA: Sobrepaso el PORCENTAJE Total de peso Guias sobrante")</script>
							';
else
	$msg_alerta_sobrante .='<br>- PORCENTAJE Total de Guias sobrante. '.$porcentaje_sobrante.'';
//**************************************
//Alerta de Conteo de Guias Sobrantes
if ($nguias > 5)
{
	$msg_alerta_sobrante .='<br><img src="imagenes/alerta_red.gif" height="28" width="37" align="absmiddle"/><span style="color:red">- Alerta:</span>Sobrepaso el limite de Guias Sobrantes
							<script>alert("ALERTA: Sobrepaso el LIMITE de Guias Sobrantes")</script>
							';
}
//**************************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function seleccionar()
{
	<?php echo $activaciones ?>
}
function deseleccionar()
{
	<?php echo $desactivaciones ?>
}
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

// funcion para validar
function validar()
{
	document.getElementById('mensaje').innerHTML="Guardando el Vuelo, por favor espere";
	document.getElementById('borrar').disabled=true;
	document.getElementById('guardar').disabled=true;
	document.getElementById('guardar').disabled=true;
	
	if (document.forms[0].fecha.value=="")
	{
		alert("Atencion: Debe seleccionar la FECHA");
		document.forms[0].lanzador.focus();
		return(false);
	}

	if (document.forms[0].hh.value=="")
	{
		alert("Atencion: Debe digitar la HORA COMPLETA");
		document.forms[0].hh.focus();
		return(false);
	}
	if (document.forms[0].mm.value=="")
	{
		alert("Atencion: Debe digitar la HORA COMPLETA");
		document.forms[0].mm.focus();
		return(false);
	}
	if (document.forms[0].ss.value=="")
	{
		alert("Atencion: Debe digitar la HORA COMPLETA");
		document.forms[0].ss.focus();
		return(false);
	}
	if (document.forms[0].hh.value > 23)
	{
		alert("Atencion: La HORA Maxima es 23");
		document.forms[0].hh.focus();
		return(false);
	}
	if (document.forms[0].mm.value > 59)
	{
		alert("Atencion: La los MINUTOS Maximos son 59");
		document.forms[0].mm.focus();
		return(false);
	}	
	if (document.forms[0].ss.value > 59)
	{
		alert("Atencion: La los SEGUNDOS Maximos son 59");
		document.forms[0].ss.focus();
		return(false);
	}	
	
}

//Activador automatico de los checksboxs segun el caso de solicitud.
function activar1(campo)
{
	document.getElementById(campo).checked=true
}

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
?>
<p class="titulo_tab_principal">Inconsistencias</p>
<p class="asterisco" align="center">Vuelo No. <?php echo $nvuelo ?></p>
<form name="inconsistencias" action="vuelo_inconsistencias_salvar.php" method="post" onsubmit="return validar();">
<table width="900" align="center">
        <tr>
        <td width="186px"><img src="imagenes/1.jpg" width="186" height="67" style="border-radius: 15px;" /></td>
        <td align="center">
            <div class="letreros_tabla asterisco">
            	PASO 1, Agregue o Elimine las Gu&iacute;as Sobrantes del Vuelo.
                <?php echo $msg_alerta_sobrante; ?>
            </div>
        </td>
      </tr>
    </table>
<table width="900" align="center" >
      <tr>
        <td colspan="2" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guias Sobrantes</div></td>
        <td colspan="4" align="center" class="celda_tabla_principal">
        	<button type="button" onclick="openPopup('guia_sobrante_nueva.php?id_vuelo=<?php echo $id_vuelo ?>','new','900','450','scrollbars=1',true);">
            	Agregar
            </button>
        </td>
        </tr>
      <tr>
        <td width="10%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">No</div> </td>
        <td width="50%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
        <td width="10%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
        <td width="10%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
        <td width="10%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
        <td width="10%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Eliminar</div></td>
      </tr>
      <?php echo $impresion2 ?>
    </table>
    <hr />
    <table width="900" align="center">
      <tr>
        <td width="186px"><img src="imagenes/2.jpg" width="186" height="67" style="border-radius: 15px;" /></td>
        <td align="center"><div class="letreros_tabla asterisco">PASO 2, Seleccione las Gu&iacute;as que desea Reportar por inconsistencias, Marcando las piezas y peso faltantes o el Faltante Total de la Gu&iacute;a.</div></td>
      </tr>
  </table>
    <table width="90%" align="center" >
      <tr>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Tipo</div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Vuelo</div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla"><font size="-2"><strong>PIEZAS<br />DESPALETIZADO</strong></font></div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla"><font size="-2"><strong>PESO<br />DESPALETIZADO</strong></font></div></td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla"><font size="-2" color="#FF0000"><strong>FALTANTE TOTAL</strong></font></div></td>
        <td width="15%" align="center" class="celda_tabla_principal">        	
        <button type="button" onclick="seleccionar();">
        	<img src="imagenes/aceptar-act.png" height="33" width="29" title="Seleccionar Todos" />
        </button>
        <button type="button" onclick="deseleccionar();">
        	<img src="imagenes/aceptar-in.png" height="33" width="29" title="Quitar todas las Selecciones" />
        </button>
       
</td>
        <td align="center" class="celda_tabla_principal"><div class="letreros_tabla">Destino</div></td>
      </tr>
      <?php echo $datos ?>
  </table>
	<br />
    <table width="900" align="center">
      <tr>
        <td width="186px" rowspan="2"><img src="imagenes/3.jpg" width="191" height="67" style="border-radius: 15px;"/></td>
        <td rowspan="2" class="celda_tabla_principal"><div class="asterisco">Paso 3, Agregue fecha y hora de Inconsistencias</div></td>
        <td width="200px" height="33" class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
        <td class="celda_tabla_principal">
            <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
              <input type="text" name="fecha" id="fecha" readonly="readonly"/>
              <input type="button" id="lanzador" value="..."/>
              <!-- script que define y configura el calendario-->
              <script type="text/javascript">
                    Calendar.setup({
                        inputField     :    "fecha",      // id del campo de texto
                        ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                        button         :    "lanzador"   // el id del botón que lanzará el calendario
                    });
                </script>
          </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Hora</div></td>
        <td class="celda_tabla_principal" width="250px">
          <input type="text" name="hh" id="hh" value="" size="2" maxlength="2" onkeypress="return numeric(event)" tabindex="1"/>
          :
          <input type="text" name="mm" id="mm" value="" size="2" maxlength="2" onkeypress="return numeric(event)" tabindex="2"/>
          :
          <input type="text" name="ss" id="ss" value="" size="2" maxlength="2" onkeypress="return numeric(event)" tabindex="3"/>
         </td>
      </tr>
    </table>
  	<hr />
	<div id="mensaje" align="center" style="asterisco"><em>Finalmente Guarde Los Cambios</em></div>     
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="reset" name="reset" id="reset" tabindex="5"> <img src="imagenes/descargar-act.png" alt="" title="Limpiar" /></button>
            <button type="submit" name="guardar" id="guardar" tabindex="4"> <img src="imagenes/guardar-act.png" alt="" title="Guardar" /> </button>
          </td>
        </tr>
    </table>
</form>
</body>
</html>
