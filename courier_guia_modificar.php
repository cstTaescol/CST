<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if(isset($_GET['id_guia']))
{
	$id_guia=$_GET['id_guia'];
	$sql_query="SELECT id,master,id_aerolinea,courier_1178,id_consignatario,piezas,peso,courier_dato_llegada FROM guia WHERE id ='$id_guia'";
	$consulta=mysql_query($sql_query,$conexion) or die ("Error 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$master=$fila['master'];
	$id_aerolinea=$fila['id_aerolinea'];
	$courier_1178=$fila['courier_1178'];
	$id_courier=$fila['id_consignatario'];
	$piezas=$fila['piezas'];
	$peso=$fila['peso'];	
	$courier_dato_llegada=explode(" ", $fila['courier_dato_llegada']);
	$fecha_llegada=$courier_dato_llegada[0];
	$hora=explode(":", $courier_dato_llegada[1]);
	$hh=$hora[0];
	$mm=$hora[1];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script src="js/srcNewCalendar/js/jscal2.js"></script>
    <script src="js/srcNewCalendar/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/steel/steel.css" />	    
	<style type="text/css">
		#global {
			height: 100px;
			width: 800px;
			border: 1px solid #ddd;
			background: #f1f1f1;
			overflow-y: scroll;
			margin-right: auto;
			margin-left: auto;
		}	
    </style>
</head>
<body>
<?php
  require("menu.php");
  $id_objeto=124;
  include("config/provilegios_modulo.php");
?>
<p class="titulo_tab_principal">Guia de Courier</p>
<p class="asterisco" align="center">Modificar Guia:<?php echo $master; ?></p>
<form name="guardar_datos" id="guardar_datos" method="post">
  <table align="center">
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">No Guia</div></td>
        <td class="celda_tabla_principal celda_boton"><input type="text" name="master" id="master" tabindex="1" maxlength="30" size="20" value="<?php echo $master; ?>"></td>
      </tr>
    <script type="text/javascript">			
        document.getElementById("master").focus();
    </script>             
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Piezas</div></td>
        <td class="celda_tabla_principal celda_boton"><input type="text" name="piezas" id="piezas"  tabindex="2" maxlength="5" size="5" onkeypress="return numeric(event)" value="<?php echo $piezas; ?>"></td>
      </tr>
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Peso</div></td>
        <td class="celda_tabla_principal celda_boton"><input type="text" name="peso" id="peso" tabindex="3" maxlength="5" size="5" onKeyPress="return numeric2(event)" value="<?php echo $peso; ?>"></td>
      </tr>

      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
        <td class="celda_tabla_principal celda_boton">
            <select name="id_aerolinea" id="id_aerolinea" tabindex="4">
                <?php
                    $sql="SELECT id,nombre FROM aerolinea WHERE estado='A' AND courier = TRUE ORDER BY nombre ASC";
                    $consulta=mysql_query ($sql,$conexion) or die ("Error 02: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                    while($fila=mysql_fetch_array($consulta))
                    {
                        if($fila['id']==$id_aerolinea)
                        	$seleccionar='selected="selected"';
                        else
                        	$seleccionar='';
                        echo '<option value="'.$fila['id'].'" '.$seleccionar.'>'.$fila['nombre'].'</option>';
                    }
                ?>
            </select>    
        </td>
      </tr>    
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Plan&iacute;lla 1178</div></td>
        <td class="celda_tabla_principal celda_boton">
            <input type="text" maxlength="15" size="30" tabindex="5" name="cod_1178" id="cod_1178" value="<?php echo $courier_1178 ?>" />
        </td>
      </tr>
      <!-- Consignatario -->
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Courier</div></td>
        <td class="celda_tabla_principal celda_boton">
            <select name="id_courier" id="id_courier" tabindex="6">
                <?php
                    $sql="SELECT id,nombre FROM couriers WHERE estado='A' ORDER BY nombre ASC";
                    $consulta=mysql_query ($sql,$conexion) or die ("Error 03: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                    while($fila=mysql_fetch_array($consulta))
                    {
                        if($fila['id']==$id_courier)
                        	$seleccionar='selected="selected"';
                        else
                        	$seleccionar='';
                        echo '<option value="'.$fila['id'].'" '.$seleccionar.'>'.$fila['nombre'].'</option>';
                    }
                ?>
            </select>            	
        </td>        
      </tr>      
      <!-- Calendario -->
      <tr>
        <td align="left" class="celda_tabla_principal">
        	<div class="letreros_tabla">
                	Datos de Llegada<br />
                    <img src="imagenes/linea_courier.png" height="50" width="50">                    
        	</div>
        </td>
        <td class="celda_tabla_principal celda_boton">

          <table class="celda_tabla_principal">
            <tr>
              <td colspan="2">
                Fecha<input style="text-align: center" readonly="true" name="date" id="date" size="14" value="<?php echo $fecha_llegada ?>" /> <button id="f_btn1" tabindex="7">...</button>
              </td>
            </tr>                 
            <tr>
              <td>Horas</td>
              <td>Minutos</td>
            </tr>
            <tr>
              <td>
                  <select name="hour" id="hour">
                    <option value="<?php echo $hh ?>"><?php echo $hh ?></option>
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                  </select>
              </td>
              <td>
                  <select name="minute" id="minute">
                    <option value="<?php echo $mm ?>"><?php echo $mm ?></option>
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                    <option value="32">32</option>
                    <option value="33">33</option>
                    <option value="34">34</option>
                    <option value="35">35</option>
                    <option value="36">36</option>
                    <option value="37">37</option>
                    <option value="38">38</option>
                    <option value="39">39</option>
                    <option value="40">40</option>
                    <option value="41">41</option>
                    <option value="42">42</option>
                    <option value="43">43</option>
                    <option value="44">44</option>
                    <option value="45">45</option>
                    <option value="46">46</option>
                    <option value="47">47</option>
                    <option value="48">48</option>
                    <option value="49">49</option>
                    <option value="50">50</option>
                    <option value="51">51</option>
                    <option value="52">52</option>
                    <option value="53">53</option>
                    <option value="54">54</option>
                    <option value="55">55</option>
                    <option value="56">56</option>
                    <option value="57">57</option>
                    <option value="58">58</option>
                    <option value="59">59</option>                  
                  </select>
              </td>
            </tr>
          </table>               
        </td>
      </tr>         
 	</table>
    <table align="center">
        <tr>
            <td align="center" valign="middle" colspan="2">
                <div id="respuesta" class="opaco_ie" style="position:relative; background-image:url(imagenes/background.png);width:100%; height:30px"></div>
            </td>
        </tr>
    </table>
    <input type="hidden" name="id_guia" id="id_guia" value="<?php echo $id_guia ?>">
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" title="Atras" onclick="document.location='base.php'">
                <img src="imagenes/al_principio-act.png" title="Atras" />
            </button>
            <button type="reset" name="reset" id="reset" title="Limpiar">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="button" name="guardar" id="guardar" title="Guardar" onClick="return validar();">
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
	if (document.forms[0].master.value=="")
	{
		alert("Atencion: Debe digitar un NUMERO DE GUIA.");		
		document.getElementById("master").focus();
		return(false);
	}	

	if (document.forms[0].piezas.value=="")
	{
		alert("Atencion: Debe digitar un NUMERO DE PIEZAS.");		
		document.getElementById("piezas").focus();
		return(false);
	}	
	
	if (document.forms[0].peso.value=="")
	{
		alert("Atencion: Debe digitar un NUMERO DE PESO.");		
		document.getElementById("peso").focus();
		return(false);
	}	

	if (document.forms[0].id_aerolinea.value=="")
	{
		alert("Atencion: Debe seleccionar una AEROLINEA.");		
		document.getElementById("id_aerolinea").focus();
		return(false);
	}
		
	if (document.forms[0].cod_1178.value=="")
	{
		alert("Atencion: Debe ingresar un Numero de PLANILLA 1178.");
		document.getElementById("cod_1178").focus();
		return(false);
	}
	
	if (document.forms[0].id_courier.value=="")
	{
		alert("Atencion: Debe seleccionar un COURIER.");	
		document.getElementById("id_courier").focus();	
		return(false);
	}

	if (document.forms[0].date.value=="")
	{
		alert("Atencion: Debe ingresar una FECHA de INICIO DE REVISION");
		document.getElementById("f_btn1").focus();
		return(false);
	}
	if (document.forms[0].hour.value=="")
	{
		alert("Atencion: Debe ingresar una HORA de INICIO DE REVISION");
		document.getElementById("hour").focus();
		return(false);
	}

	if (document.forms[0].minute.value=="")
	{
		alert("Atencion: Debe ingresar unos MINUTOS de INICIO DE REVISION");
		document.getElementById("minute").focus();
		return(false);
	}



  //Procedimiento de Guardado
	guardar_form();		
}

function guardar_form()
{
	var peticion = new Request(
	{
		url: 'courier_guia_modificar_salvar.php',
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
	        var respuesta = responseText.substring(0, 5);
	        var coderror = responseText.substring(6, 7);	        
	        switch(respuesta)
	        {
	            case "Error":
	            	switch(coderror)
	            	{
	            		case "0":
	            			document.location="cerrar_sesion.php";
	            		break;

	            		case "1":
	            			alert(responseText);
	            			$('respuesta').innerHTML='<p align="center">Datos No guardados</p>';
							$('guardar').disabled=false;
							$('reset').disabled=false;					            			
	            		break;

	            		default:
	            			alert(responseText);
							$('guardar').disabled=false;
							$('reset').disabled=false;				
	            		break;
	            	}
	            break;
	            default:
	  				$('respuesta').innerHTML='<p align="center">Proceso Finalizado</p>';
					alert ("Guia Modificada Satisfactoriamente");     					
		      		document.location='consulta_guia_courier.php?id_guia=<?php echo $id_guia ?>';				            
	            break;
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

//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}

//Validacion de campos numéricos
function numeric2(e) { 
        tecla = (document.all) ? e.keyCode : e.which; // 2
        if (tecla==8) return true; // 3
        patron =/[0-9-.]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
} 

//Calendario
Calendar.setup({
	inputField : "date",
	trigger    : "f_btn1",
	onSelect   : function() { this.hide() },
	//showTime   : 12,
	//Con Hora--> dateFormat : "%Y-%m-%d %I:%M %p"
	dateFormat : "%Y-%m-%d"
});
</script>
