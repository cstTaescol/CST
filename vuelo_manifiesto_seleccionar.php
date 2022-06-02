<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
set_time_limit (0); // Elimina la restriccion en el tiempo limite para la ejecicion del modulo.
require("config/configuracion.php");
require("config/control_tiempo.php");
$total_peso=0;
$total_volumen=0;
$total_piezas=0;
$total_guias=0;
if(isset($_GET["vuelo"]))
	$id_vuelo=$_GET["vuelo"];
	else
		$id_vuelo= "Error al obtener el Vuelo";
$hh_manifiesto="";
$mm_manifiesto="";
$ss_manifiesto="";
//Activar o desactivar todas las guia
$activaciones="";
$desactivaciones="";
//***********************************
//carda los datos del manifiesto si ya han sido ingresados
$sql2="SELECT nmanifiesto,fecha_manifiesto,hora_manifiesto,nvuelo,id_aerolinea FROM vuelo WHERE id='$id_vuelo' AND estado !='F' AND estado !='I'";
$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
while($fila2=mysql_fetch_array($consulta2))
	{
		$nmanifiesto=$fila2['nmanifiesto'];
		$fecha_manifiesto=$fila2['fecha_manifiesto'];
		$hora_manifiesto=$fila2['hora_manifiesto'];
		$nvuelo=$fila2['nvuelo'];
		$id_aerolinea=$fila2['id_aerolinea'];
		if ($hora_manifiesto != "")
		{
			$hora_manifiesto=explode(":",$hora_manifiesto);
			$hh_manifiesto=$hora_manifiesto[0];
			$mm_manifiesto=$hora_manifiesto[1];
			$ss_manifiesto=$hora_manifiesto[2];
		}
	}
//**********************************

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
//Validacion de campos numéricos
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
	if (document.forms[0].nmanifiesto.value=="")
	{
		alert("Atencion: Debe digitar el NUMERO DE MANIFIESTO");
		document.forms[0].nmanifiesto.focus();
		return(false);
	}
	if (document.forms[0].fecha_manifiesto.value=="")
	{
		alert("Atencion: Debe seleccionar la FECHA DEL MANIFIESTO");
		document.forms[0].lanzador.focus();
		return(false);
	}
	if (document.forms[0].hh_manifiesto.value=="")
	{
		alert("Atencion: Se requiere la HORA del Manifieso");
		document.forms[0].hh_manifiesto.focus();
		return(false);
	}
	if (document.forms[0].mm_manifiesto.value=="")
	{
		alert("Atencion: Se requieren los MINUTOS del Manifiesto");
		document.forms[0].mm_manifiesto.focus();
		return(false);
	}
	if (document.forms[0].ss_manifiesto.value=="")
	{
		alert("Atencion: Se requieren los SEGUNDOS del Manifiesto");
		document.forms[0].ss_manifiesto.focus();
		return(false);
	}
	if (document.forms[0].hh_manifiesto.value > 23)
	{
		alert("Atencion: La HORA Manifiesto Maxima es 23");
		document.forms[0].hh_manifiesto.focus();
		return(false);
	}
	if (document.forms[0].mm_manifiesto.value > 59)
	{
		alert("Atencion: Los MINUTOS Manifiesto Maximos son 59");
		document.forms[0].mm_manifiesto.focus();
		return(false);
	}	
	if (document.forms[0].ss_manifiesto.value > 59)
	{
		alert("Atencion: Los SEGUNDOS Manifiesto Maximos son 59");
		document.forms[0].ss_manifiesto.focus();
		return(false);
	}
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
<!-- -------------------------------------------------------------------------- -->

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
<p class="titulo_tab_principal">Manifiesto de Vuelo</p>
<p class="asterisco" align="center">Vuelo No.  <?php if (isset($nvuelo)) echo $nvuelo ?></p>
<form name="manifiesto" method="post" action="vuelo_manifiesto_salvar.php" onsubmit="return validar();">
<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>
    <table width="680" align="center">
      <tr>
        <td width="186px" rowspan="2" align="center">
            	<img src="imagenes/1.jpg" width="186" height="67" style="border-radius: 15px;" />
        </td>
        <td colspan="3" align="center">
        	<div class="letreros_tabla asterisco">PASO 1, Agregar las Gu&iacute;as que falten a este vuelo.</div>
        </td>
        </tr>
      <tr>
        <td>
        	<button type="button" onclick="document.location='guia_registro.php?aerolinea=<?php echo $id_aerolinea?>&amp;id_vuelo=<?php echo $id_vuelo ?>'"> 
            	Agregar Guia Nueva
            </button>
         </td>
        <td>
            <button type="button" onclick="openPopup('vuelo_manifiesto_guias_adicionales.php?id_aerolinea=<?php echo $id_aerolinea?>&id_vuelo=<?php echo $id_vuelo ?>','new','700','450','scrollbars=1',true);"> 
                Agregar Guia Existente
            </button>
        </td>
        <td>
            <button type="button" onclick="openPopup('vuelo_manifiesto_guias_faltantes.php?id_aerolinea=<?php echo $id_aerolinea?>&id_vuelo=<?php echo $id_vuelo ?>','new','700','450','scrollbars=1',true);"> 
                Agregar Guia Faltante
            </button>
        </td>
      </tr>
    </table>
    <p>&nbsp;</p>
<hr />
    <table width="680" align="center">
      <tr>
        <td width="186px">
        	<img src="imagenes/2.jpg" width="186" height="67" style="border-radius: 15px;"  />
        </td>
        <td align="center">
        	<div class="letreros_tabla asterisco">PASO 2, Seleccione las Gu&iacute;as que desea Manifestar.</div>
        </td>
      </tr>
    </table>
    <br />
    <p align="center"><div class="letreros_tabla asterisco">Guias de Este Vuelo</div></p>
 <table align="center" >
  <tr>
    <td width="10%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Tipo</div></td>
    <td width="15%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Consolidado</div></td>
    <td width="15%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td width="5%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
    <td width="5%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
    <td width="5%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Volumen</div></td>
    <td width="30%" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Destino</div></td>
   	<td width="15%" align="center" class="celda_tabla_principal">
        <button type="button" onclick="seleccionar();">
        	<img src="imagenes/aceptar-act.png" height="33" width="29" title="Seleccionar Todos" />
        </button>
        <button type="button" onclick="deseleccionar();">
        	<img src="imagenes/aceptar-in.png" height="33" width="29" title="Quitar todas las Selecciones" />
        </button>
	</td>
  </tr>
<?php
$sql="SELECT v.id_aerolinea,g.master,g.hija,g.piezas,g.peso,g.volumen,g.id,g.piezas_faltantes,peso_faltante,g.id_deposito,g.piezas_inconsistencia,g.peso_inconsistencia,g.volumen_inconsistencia, g.id_tipo_bloqueo, g.id_tipo_guia, g.id_disposicion FROM vuelo v LEFT JOIN guia g ON v.id = g.id_vuelo WHERE v.id = '$id_vuelo' AND g.id_tipo_guia != '2' ORDER BY v.id, g.master, g.id_deposito ASC";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$nguias=mysql_num_rows($consulta);
$_SESSION["nguias"]=$nguias;		
if ($nguias > 0)
{
	for ($i=1; $i<=$nguias; $i++)
	{
		$color='';
		$fila=mysql_fetch_array($consulta);
		//identificacion deposito
		$id_tipo_guia=$fila["id_tipo_guia"];
		if ($id_tipo_guia==3)
			$id_tipo_guia="CONSOLIDADO";
		else
		{
			$sql3="SELECT nombre FROM tipo_guia WHERE id='$id_tipo_guia'";
			$consulta3=mysql_query ($sql3,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila3=mysql_fetch_array($consulta3);
			$id_tipo_guia=$fila3["nombre"];
		//************************
		}
		$hija=$fila["hija"];
		$master=$fila["master"];
		require("config/master.php");
		include("config/evaluador_inconsistencias.php");

		$total_peso=$total_peso+$peso;
		$total_piezas=$total_piezas+$piezas;
		$total_volumen=$total_volumen+$volumen;
		
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
			$checked='checked="checked"';
			$valor="1";
			break;
			
			default:
			$checked='';
			$valor="0";
			break;			
		}
		echo '<tr>
				<td align="left" class="celda_tabla_principal celda_boton">'.$id_tipo_guia.'</td>
				<td align="left" class="celda_tabla_principal celda_boton">'.$master.'</td>
				<td align="left" class="celda_tabla_principal celda_boton"><a href="consulta_guia.php?id_guia='.$id_guia.'">'.$hija.'</a></td>
				<td align="center" class="celda_tabla_principal celda_boton">'.$piezas.'</td>
				<td align="center" class="celda_tabla_principal celda_boton">'.$peso.'</td>
				<td align="center" class="celda_tabla_principal celda_boton">'.$volumen.'</td>
				<td align="left" class="celda_tabla_principal celda_boton"><font size="-1" '.$color.'>'.$destino.'</font></td>
				<td align="center" class="celda_tabla_principal celda_boton">
				<input type="hidden" name="guia'.$i.'" value="'.$id_guia.'">
				<input type="hidden" name="valor'.$i.'" value="'.$valor.'">
				<input type="checkbox" name="chkacepto'.$i.'" id="chkacepto'.$i.'" value="1" '.$checked.'/></td>
			  </tr>';
		$activaciones=$activaciones."document.getElementById('chkacepto$i').checked=true;\n";
		$desactivaciones=$desactivaciones."document.getElementById('chkacepto$i').checked=false;\n";
	}
	$disponibilidad='';
}
else
	{
		echo "<br><font color='red' size='+1'>No existen GU&Iacute;AS Asociadas para este VUELO, Modifique el VUELO y agregue GUIAS.</font>";
		$i=1;
		$disponibilidad='disabled="disabled"';
	}

?>
        </table>
        <br />
        <hr />
		<?php
        $total_peso=number_format($total_peso,2,",",".");
        $total_volumen=number_format($total_volumen,2,",",".");
        $total_piezas=number_format($total_piezas,0,",",".");
        $total_guias=$i-1;
        echo "
		<strong><font color='blue' size='+1'>TOTALES</font><br>
        <strong>CANTIDAD DE GUIAS:</strong>......... $total_guias<br>
        <strong>PIEZAS TOTALES:</strong>................. $total_piezas<br>
        <strong>PESO TOTALES:</strong>.................... $total_peso<br>
        <strong>VOLUMEN TOTALES:</strong>.......... $total_volumen<br>";
        ?>
<script language="javascript">
function seleccionar()
{
	<?php echo $activaciones ?>
}
function deseleccionar()
{
	<?php echo $desactivaciones ?>
}
</script>
        <hr />
       <table width="680" align="center">
          <tr>
            <td width="186px" rowspan="4" align="center">
            	<img src="imagenes/3.jpg" width="186" height="67" style="border-radius: 15px;" />
            </td>
            <td colspan="2">
                <div class="letreros_tabla asterisco">Paso 3, Ingrese Los Datos del Manifiesto.</div>
             </td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">N. Manifiesto</div></td>
            <td class="celda_tabla_principal celda_boton">
				<input type="text" name="nmanifiesto" id="nmanifiesto" value="<?php echo $nmanifiesto ?>" size="40" maxlength="30" tabindex="1" />
              	<input type="hidden" name="id_vuelo" id="id_vuelo" value="<?php echo $id_vuelo ?>" />
			  <script language="JavaScript" type="text/javascript">document.forms[0].nmanifiesto.focus();</script>
				
		    </td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha Manifiesto</div></td>
            <td class="celda_tabla_principal celda_boton">
              <input type="text" name="fecha_manifiesto" id="fecha_manifiesto" value="<?php echo $fecha_manifiesto ?>" readonly="readonly"/>
              <input type="button" id="lanzador" value="..." tabindex="2"/>
              <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
              <!-- script que define y configura el calendario-->
              <script type="text/javascript">
                Calendar.setup({
                    inputField     :    "fecha_manifiesto",      // id del campo de texto
                    ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                    button         :    "lanzador"   // el id del botón que lanzará el calendario
                });
            </script></td>
          </tr>
          <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Hora Manifiesto</div></td>
            <td class="celda_tabla_principal celda_boton">
            	<input type="text" name="hh_manifiesto" id="hh_manifiesto" maxlength="2" size="2" tabindex="3" value="<?php echo $hh_manifiesto ?>" onkeypress="return numeric(event)"/>
              :
              <input type="text" name="mm_manifiesto" id="mm_manifiesto" maxlength="2" size="2" tabindex="4" value="<?php echo $mm_manifiesto ?>" onkeypress="return numeric(event)"/>
              :
              <input type="text" name="ss_manifiesto" id="ss_manifiesto" maxlength="2" size="2" tabindex="5" value="<?php echo $ss_manifiesto ?>" onkeypress="return numeric(event)"/>
              hh:mm:ss 
            </td>
          </tr>
        </table>
        <hr />
        <table width="450" align="center">
            <tr>
              <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
            </tr>
            <tr>
              <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
                <button type="reset" name="reset" id="reset" tabindex="7"> <img src="imagenes/descargar-act.png" alt="" title="Limpiar" /></button>
                <button type="submit" name="guardar" id="guardar" <?php echo $disponibilidad ?> tabindex="6"> <img src="imagenes/guardar-act.png" alt="" title="Guardar" /> </button>
              </td>
            </tr>
        </table>
    </td>
  </tr>
</table>
</form>
</body>
</html>