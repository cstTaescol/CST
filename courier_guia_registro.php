<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
//<!-- Cuadricula para guia nuevas -->    
$impresion='';
$tab = 10;
for ($i=1; $i <= 100; $i++) { 
	
	$tab1= $tab+1;
	$tab2= $tab+2;
	$tab3= $tab+3;
	$tab4= $tab+4;
	$tab=$tab4;
	$impresion .= '
			      <tr>     
			      	<td class="celda_tabla_principal celda_boton">'.$i.'</td>   
			        <td class="celda_tabla_principal celda_boton">
			            <input type="text" maxlength="30" size="20" name="no_guia'.$i.'" id="no_guia'.$i.'" tabindex="'.$tab1.'" />
			        </td>    
			        <td class="celda_tabla_principal celda_boton">
			            <input type="text" maxlength="5" size="5" onkeypress="return numeric(event)" name="piezas'.$i.'" id="piezas'.$i.'" tabindex="'. $tab2 .'"/>
			        </td>
			        <td class="celda_tabla_principal celda_boton">
			            <input type="text" maxlength="10" size="5" onKeyPress="return numeric2(event)" name="peso'.$i.'" id="peso'.$i.'" tabindex="'. $tab3 .'"/>                
			        </td>
			        <td class="celda_tabla_principal celda_boton">
			            <input type="text" size="20" name="observaciones'.$i.'" id="observaciones'.$i.'" tabindex="'. $tab4 .'"/>                
			        </td>                
			      </tr>
				';
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
  $id_objeto=118;
  include("config/provilegios_modulo.php");
?>
<p class="titulo_tab_principal">Guia de Courier</p>
<p class="asterisco" align="center">Crear</p>
<form name="guardar_datos" id="guardar_datos" method="post">
  <table align="center">
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
        <td class="celda_tabla_principal celda_boton">
            <select name="id_aerolinea" id="id_aerolinea" tabindex="1">
                <option value="" >Seleccione una</option>
                <script type="text/javascript">			
                    document.getElementById("id_aerolinea").focus();
                </script>        
                <?php
                    $sql="SELECT id,nombre FROM aerolinea WHERE estado='A' AND courier = TRUE ORDER BY nombre ASC";
                    $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                    while($fila=mysql_fetch_array($consulta))
                    {
                        echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                    }
                ?>
            </select>    
        </td>
      </tr>    
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Plan&iacute;lla 1178</div></td>
        <td class="celda_tabla_principal celda_boton">
            <input type="text" maxlength="15" size="30" tabindex="2" name="cod_1178" id="cod_1178" value="1178" />
        </td>
      </tr>
      <!-- Courier -->
      <tr>
        <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Courier</div></td>
        <td class="celda_tabla_principal celda_boton">
            <div id="dv_consignatario">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="25%"  class="celda_tabla_principal"><font size="-1">Buscar..........................</font></td>
                      <td width="75%"  class="celda_tabla_principal">
                            <font color="#FF0000"><strong>(*)</strong></font>
                            <input type="text" name="coincidencia" id="coincidencia" tabindex="3"/>
                            <button type="button" onclick="buscar_consignatario(coincidencia.value)" tabindex="4"> 
                                <img src="imagenes/buscar-act.png" alt="" title="Buscar" align="absmiddle"/> 
                            </button>
                            <button type="button" name="btn_consignatarioPop" id="btn_consignatarioPop" onclick="openPopup('courier_nuevo_pop.php','new','750','400','scrollbars=1',true)" tabindex="5" <?php  $id_objeto=121; include("config/provilegios_objeto.php");  echo $activacion ?>> 
                                <img src="imagenes/agregar-act.png" alt="" title="Agregar" align="absmiddle"/> 
                            </button>
                      </td>
                    </tr>
                    <tr>
                      <td class="celda_tabla_principal" align="center">
                          <button name="btn_consignatario" id="btn_consignatario" onclick="listar_consignatario()" tabindex="6"> 
                                <img src="imagenes/buscar.png" width="60" height="60" alt="Activar el selector" /> <br />
                                <strong>Seleccionar</strong> 
                          </button>
                      </td>
                      <td class="celda_tabla_principal celda_boton"><div id="resultado_consignatario"></div></td>
                    </tr>
                    <tr>
                      <td colspan="2" class="celda_tabla_principal celda_boton" >&nbsp;</td>
                    </tr>                    
                  </table>
            </div>
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
        <td class="celda_tabla_principal celda_boton" align="center">		            
	        <table class="celda_tabla_principal">
	        	<tr>
	        		<td colspan="2">
	        			Fecha<input style="text-align: center" readonly="true" name="date" id="date" size="14" /> <button id="f_btn1" tabindex="7">...</button>
	        		</td>
	        	</tr>		            	
	        	<tr>
	        		<td>Horas</td>
	        		<td>Minutos</td>
	        	</tr>
	        	<tr>
	        		<td>
			            <select name="hour" id="hour" tabindex="8">
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
			            <select name="minute" id="minute" tabindex="9">
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
 	<!-- Cuadricula de guias -->
 	<table align="center">
      <!-- Titulos cuadricula guias -->      
      <tr>
        <td align="left" class="celda_tabla_principal" style="width:40px;"><div class="letreros_tabla">No</div></td>
        <td align="left" class="celda_tabla_principal" style="width:180px;"><div class="letreros_tabla">No Gu&iacute;a</div></td>
		<td align="left" class="celda_tabla_principal" style="width:70px;"><div class="letreros_tabla">Piezas</div></td>        
        <td align="left" class="celda_tabla_principal" style="width:70px;"><div class="letreros_tabla">Peso</div></td>
        <td align="left" class="celda_tabla_principal" style="width:180px;"><div class="letreros_tabla">Observaciones</div></td>
      </tr>
    </table>
	      <!-- Cuadricula para guia nuevas -->    
	<div id="global">
	  <div id="mensajes">
	    <table  align="center">
	      <tr>        
			<?php echo $impresion ?>
	      </tr>
	    </table>
	  </div>
	</div>    
	<!-- Datos invisibles -->       
	<input type="hidden" name="cod_consignatario" id="cod_consignatario" />
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
	
	if (document.forms[0].cod_consignatario.value=="")
	{
		alert("Atencion: Debe seleccionar un COURIER.");		
		return(false);
	}

	if (document.forms[0].date.value=="")
	{
		alert("Atencion: Debe seleccionar una FECHA DE LLEGADA.");
    	document.getElementById("f_date").focus();    		
		return(false);
	}

	if (document.forms[0].no_guia1.value=="")
	{
		alert("Atencion: Debe digitar el NUMERO DE GUIA.");
		document.getElementById("no_guia1").focus();
		return(false);
	}

	if (document.forms[0].piezas1.value=="")
	{
		alert("Atencion: Debe digitar el NUMERO DE PIEZAS.");
		document.getElementById("piezas1").focus();
		return(false);
	}	

	if (document.forms[0].peso1.value=="")
	{
		alert("Atencion: Debe digitar la cantidad de PESO.");
		document.getElementById("peso1").focus();
		return(false);
	}

  //Procedimiento de Guardado
	guardar_form();		
}

function guardar_form()
{
	var peticion = new Request(
	{
		url: 'courier_guia_registro_salvar.php',
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
					alert ("Guia Creada Satisfactoriamente");          
		      		document.location='courier_inventario.php';				            
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

//Busqueda Asincrona de Consignatario
function buscar_consignatario(texto){  
  //Limpieza del campo con el codigo del consignatario antes de aplicar la búsqueda
  autocompletar_congnatario("");

  //Busqueda Asincrona
	var myRequest = new Request({
		url: 'ajax_couriers_autocompletar.php',
		method: 'get',
		onRequest: function(){
			$('resultado_consignatario').innerHTML = "Procesando <img src='imagenes/cargando.gif'/>";
		},
		onSuccess: function(responseText){
			$('resultado_consignatario').innerHTML = responseText;
		},
		onFailure: function(){
			$('resultado_consignatario').innerHTML = "Error";
		}
	});	
	myRequest.send('q=' + texto);
}

//Lista  Asincrona de Consignatario
function listar_consignatario(){  
  //Limpieza del campo con el codigo del consignatario antes de aplicar la búsqueda
  autocompletar_congnatario("");

  //Busqueda Asincrona
	var myRequest = new Request({
		url: 'courier_guia_registro_lista_consignatario.php',
		method: 'get',
		onRequest: function(){
			$('dv_consignatario').innerHTML = "Procesando <img src='imagenes/cargando.gif'/>";
		},
		onSuccess: function(responseText){
			$('dv_consignatario').innerHTML = responseText;
		},
		onFailure: function(){
			$('dv_consignatario').innerHTML = "Error";
		}
	});	
	myRequest.send();
}
	
function autocompletar_congnatario(registro){
	$('cod_consignatario').value=registro; 	
}

function pasar_consignatario(registro){
	$('cod_consignatario').value=registro; 	
}

// función que permite abrir ventanas emergentes con las propiedades deseadas
function openPopup(url,name,w,h,props,center){
  l=18;t=18
  if(center){l=(screen.availWidth-w)/2;t=(screen.availHeight-h)/2}
  url=url.replace(/[ ]/g,'%20')
  popup=window.open(url,name,'left='+l+',top='+t+',width='+w+',height='+h+',scrollbars=1'+((props)?','+props:''))
  props=props||''
  if(props.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
  popup.focus()
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
