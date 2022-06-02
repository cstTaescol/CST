<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$id_usuario=$_SESSION['id_usuario'];
$id_guia=$_GET['id_guia'];

//Consulta de datos
$sql="SELECT hija,piezas,peso,volumen,id_vuelo,id_aerolinea FROM guia WHERE id='$id_guia'";
$consulta=mysql_query($sql,$conexion);
$fila=mysql_fetch_array($consulta);
$hija=$fila['hija'];
$piezas=$fila['piezas'];
$peso=$fila['peso'];
$volumen=$fila['volumen'];
$id_vuelo=$fila['id_vuelo'];
$id_aerolinea=$fila['id_aerolinea'];

//Consulta de datos
$sql="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
$consulta=mysql_query ($sql,$conexion) or die (exit("Error".mysql_error()));
$fila=mysql_fetch_array($consulta);
$nvuelo=$fila['nvuelo'];

//Consulta de datos
$sql="SELECT nombre FROM aerolinea WHERE id='$id_aerolinea'";
$consulta=mysql_query ($sql,$conexion) or die (exit("Error".mysql_error()));
$fila=mysql_fetch_array($consulta);
$aerolinea=$fila['nombre'];

?>
<html>
<head>
<script type="text/javascript">
// ********************************************* Documento JavaScript Original de documento externo
function llamadasin(url, contenedor, boton){
//limpia los datos del campo que debe recargarse, no es original de la funcion
if(boton=="embarcador")
{
	document.forms[0].cod_embarcador.value='';
}

if(boton=="consignatario")
{
	document.forms[0].cod_consignatario.value='';
}
//////////////////////////////
var pagina_requerida = false
if (window.XMLHttpRequest) {
// comprueba si el navegador es opera, safari, mozilla, etc.
	pagina_requerida = new XMLHttpRequest()
}
else if (window.ActiveXObject){ 
// comprueba si el navegador es internet explorer
	try {
		pagina_requerida = new ActiveXObject("Msxml2.XMLHTTP")
	} 
	catch (e){ 
// caso de versión antigua de internet explorer
		try{
			pagina_requerida = new ActiveXObject("Microsoft.XMLHTTP")
		}
		catch (e){
		}
	}
}
else {
	return false
}

pagina_requerida.onreadystatechange=function(){ 
// llamada a la función que carga la página
		pintapagina(pagina_requerida, contenedor)
}
// métodos open y send
	pagina_requerida.open('GET', url, true) 
	pagina_requerida.send(null)
}

// función que presenta la información 
function pintapagina(pagina_requerida,contenedor)
{
	if (pagina_requerida.readyState == 4 && (pagina_requerida.status==200 || window.location.href.indexOf("http")==-1))
	{
		document.getElementById(contenedor).innerHTML=pagina_requerida.responseText
	}
		//si no se ha cargado complente muestra imagen de loading
		else
		{
				document.getElementById(contenedor).innerHTML='Cargando..<img src="imagenes/cargando.gif" align="middle"/><br />'
		}
}
// ********************************************* Documento JavaScript Original de documento externo



//**************Autocompletar busqueda de consignatario
function autocompletar_congnatario(valor)
{
	document.forms[0].auto_consgnatario.value="";
	document.forms[0].cod_consignatario.value=valor;
}

function showHint(str)
 {
	var respuesta="";
 if (str.length==0)
   { 
  document.getElementById("txtConsignatario").innerHTML="";
   return;
   }
if (window.XMLHttpRequest)
   {// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
   }
 else
   {// code for IE6, IE5
   xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
 xmlhttp.onreadystatechange=function()
   {
   if (xmlhttp.readyState==4 && xmlhttp.status==200)
     {
	 var respuesta=xmlhttp.responseText;
	 document.getElementById("txtConsignatario").innerHTML=xmlhttp.responseText;
     }
		//si no se ha cargado complente muestra imagen de loading
		else
		{
				document.getElementById("txtConsignatario").innerHTML='Cargando..<img src="imagenes/cargando.gif" align="middle"/><br />'
		}	 
   }
 xmlhttp.open("GET","ajax_consignatario_autocompletar.php?q="+str,true);
 xmlhttp.send();
 }
//************************************************************

//*************************************
//Cargar Valores al campo texto fijo.
function pasar_embarcador(txt_embarcador){
	document.forms[0].cod_embarcador.value=txt_embarcador;
}

function pasar_consignatario(txt_consignatario){
	document.forms[0].cod_consignatario.value=txt_consignatario;
}

function pasar_deposito(txt_deposito){
	document.forms[0].cod_deposito.value=txt_deposito;
}

function pasar_destinos(txt_departamento,txt_ciudad){
	document.forms[0].cod_departamento_destino.value=txt_departamento;
	document.forms[0].cod_ciudad_destino.value=txt_ciudad;
}

function pasar_ciudad_origen(txt_ciudad_origen){
	document.forms[0].cod_ciudad_embarcadora.value=txt_ciudad_origen;
}

function pasar_asignacion_directa(txt_asignacion_directa){
	document.forms[0].asignacion_directa.value=txt_asignacion_directa;
}
//*************************************


// 1. Carga el listado de los tipos de deposito segun la disposicion de cargue seleccionada
function showTipoDeposito(str_disposicion)
{
 if (str_disposicion=="")
   {
   document.getElementById("dv_tipo_deposito").innerHTML="";
   return;
   } 
if (window.XMLHttpRequest)
   {// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
   }
 else
   {// code for IE6, IE5
   xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
 xmlhttp.onreadystatechange=function()
   {
   if (xmlhttp.readyState==4 && xmlhttp.status==200)
     {
     document.getElementById("dv_tipo_deposito").innerHTML=xmlhttp.responseText;
     }
	else
	 {
		 document.getElementById("dv_tipo_deposito").innerHTML='Cargando..<img src="imagenes/cargando.gif" align="middle"/><br />';
	 }
   }
 xmlhttp.open("GET","ajax_tipo_deposito.php?disposicion="+str_disposicion,true);
 xmlhttp.send();

 document.forms[0].cod_disposicion.value=str_disposicion;
 //Limpia datos inutilizados
 document.getElementById("dv_evaluador").innerHTML=""; 
 document.getElementById("dv_deposito").innerHTML="";
 document.forms[0].cod_deposito.value="";
 document.forms[0].cod_departamento_destino.value="11";
 document.forms[0].cod_ciudad_destino.value="11001";
 document.forms[0].asignacion_directa.value="S";
}
//-------------------------------------------

// 2. Evalua si se cargarán los nombres de los depositos o se seleccionará una ciudad cuando sea cabotaje.
function showEvaluadorAjax(str_disposicion,str_tipodeposito,str_admon_aduana)
 {
 if (str_disposicion=="")
   {
   document.getElementById("dv_evaluador").innerHTML="";
   return;
   } 
if (window.XMLHttpRequest)
   {// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
   }
 else
   {// code for IE6, IE5
   xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
 xmlhttp.onreadystatechange=function()
   {
   if (xmlhttp.readyState==4 && xmlhttp.status==200)
     {
     document.getElementById("dv_evaluador").innerHTML=xmlhttp.responseText;
     }
	else
	 {
		 document.getElementById("dv_evaluador").innerHTML='Cargando..<img src="imagenes/cargando.gif" align="middle"/><br />';
	 }	 
   }
 document.getElementById("dv_deposito").innerHTML="";
 xmlhttp.open("GET","ajax_evaluador.php?disposicion="+str_disposicion+"&tipodeposito="+str_tipodeposito+"&admon_aduana="+str_admon_aduana,true);
 xmlhttp.send(); 
 //Limpia datos que se reutilizaran
 document.forms[0].cod_deposito.value="";
 document.forms[0].cod_cabotaje.value="";
 }
//-------------------------------------------

// 3. Evalua cuando la disposicion fue cabotaje y ahora trae una unava admin aduanera y un nuevo tipo de deposito para cargar el deposito basado ene stos parametros
function showDeposito(str_aduana,str_t_deposito)
{
 if (str_aduana=="")
   {
   document.getElementById("dv_deposito").innerHTML="";
   return;
   } 
if (window.XMLHttpRequest)
   {// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
   }
 else
   {// code for IE6, IE5
   xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
 xmlhttp.onreadystatechange=function()
   {
   if (xmlhttp.readyState==4 && xmlhttp.status==200)
     {
     document.getElementById("dv_deposito").innerHTML=xmlhttp.responseText;
     }
	else
	 {
		 document.getElementById("dv_deposito").innerHTML='Cargando..<img src="imagenes/cargando.gif" align="middle"/><br />';
	 }	 	 
   }
 xmlhttp.open("GET","ajax_deposito.php?aduana="+str_aduana+"&tipodeposito="+str_t_deposito,true);
 xmlhttp.send(); 
 //Agrega Valores por cabotaje
 document.forms[0].cod_cabotaje.value=str_aduana;
 document.forms[0].cod_departamento_destino.value="";
 document.forms[0].cod_ciudad_destino.value="";
}
//-------------------------------------------

// 4. Carga ciudades basandose en el pais seleccionado.
function showPais(str_cod_pais)
{
 if (str_cod_pais=="")
   {
    document.getElementById("dv_ciudad_origen").innerHTML="Cargando";
   	return;
   } 
if (window.XMLHttpRequest)
   {// code for IE7+, Firefox, Chrome, Opera, Safari
   	xmlhttp=new XMLHttpRequest();
   }
else
   {// code for IE6, IE5
   	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
xmlhttp.onreadystatechange=function()
   {
   if (xmlhttp.readyState==4 && xmlhttp.status==200)
     {
     	document.getElementById("dv_ciudad_origen").innerHTML=xmlhttp.responseText;
     }
	 else
	 {
		 document.getElementById("dv_ciudad_origen").innerHTML='Cargando..<img src="imagenes/cargando.gif" align="middle"/><br />';
	 }
   }
xmlhttp.open("GET","ajax_pais.php?cod_pais="+str_cod_pais,true);
xmlhttp.send(); 
//Lipieza de Campos a Usar  
document.forms[0].cod_ciudad_embarcadora.value="";
}
//-------------------------------------------

</script>

<script language="javascript">
//Validacion de campos numéricos
function numeric2(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9-.]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

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
		if (document.forms[0].disposicion.value=="")
		{
			alert("Atencion: Debe seleccionar una DISPOSICION DE CARGUE");
			document.forms[0].disposicion.focus();
			return(false);
		}
		if (document.forms[0].cod_embarcador.value=="")
		{
			alert("Atencion: Debe seleccionar un EMBARCADOR");
			return(false);
		}
		if (document.forms[0].cod_consignatario.value=="")
		{
			alert("Atencion: Debe seleccionar un CONSIGNATARIO");
			return(false);
		}
		//************EVALUAR LOS QUE NO REQUIEREN DEPOSITO****************
		if (document.forms[0].disposicion.value !=28 && document.forms[0].disposicion.value !=21 && document.forms[0].disposicion.value !=20 && document.forms[0].disposicion.value !=19 && document.forms[0].disposicion.value !=25 && document.forms[0].disposicion.value !=29 && document.forms[0].disposicion.value !=23 && document.forms[0].disposicion.value !=13 && document.forms[0].disposicion.value !=15) 
		{		
			if (document.forms[0].cod_deposito.value=="")
			{
				//************Cuando deposito este en blanco****************
				alert("Atencion: Debe seleccionar un DEPOSITO");
				return(false);
			}
		}
		
		if (document.forms[0].cod_ciudad_destino.value=="")
		{
			alert("Atencion: Debe seleccionar una CIUDAD de DESTINO");
			return(false);
		}

		if (document.forms[0].descripcion.value=="")
		{
			alert("Atencion: Debe digitar la DESCRIPCION");
			document.forms[0].descripcion.focus();
			return(false);
		}
		if (document.forms[0].fecha_corte.value=="")
		{
			alert("Atencion: Debe asignar una FECHA DE CORTE");
			document.forms[0].lanzador.focus();
			return(false);
		}
/*
		if (document.forms[0].cod_ciudad_embarcadora.value=="")
		{
			alert("Atencion: Debe asignar un PAIS y una CIUDAD DE ORIGEN");
			return(false);
		}
*/
		if (document.forms[0].fecha_finalizacion.value=="")
		{
			alert("Atencion: Debe asignar una FECHA DE FINALIZACION para volver a calcular la fecha de VENCIMIENTO.");
			document.forms[0].lanzador2.focus();
			return(false);
		}
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-Hoja de estilos del calendario -->
<!-- librería principal del calendario -->
<link rel="stylesheet" type="text/css" media="all" href="js/calendar-color.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/calendar.js"></script>

<!-- librería para cargar el lenguaje deseado -->
<script type="text/javascript" src="js/calendar-es.js"></script>

<!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código -->
<script type="text/javascript" src="js/calendar-setup.js"></script>

<script language="javascript" type="text/javascript">
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
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Registro de Guia Sobrante</p>
<p align="center">
    <font color="green" size="+1">GUIA:</font> <?php echo $hija ?><br />
    <font color="red" size="+1">AEROLINEA:</font> <?php echo $aerolinea ?><br /> 
    <font color="purple" size="+1">VUELO:</font> <?php echo $nvuelo ?><br />    
</p>
<form name="registro_guia" action="guia_sobrante_salvar.php" method="post" onSubmit="return validar();">
<table width="90%" align="center" class="celda_tabla_principal">
  <tr>
    <td>
    <table width="90%" align="center">
      <tr>
        <td class="asterisco" colspan="2" align="center">Guia Sobrante</td>
        </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Administracion Aduanera</div></td>
        <td class="celda_tabla_principal celda_boton">
        	<font color="#FF0000"><strong>(*)</strong></font>
            <select name="admon_aduana" id="admon_aduana" tabindex="1">
              <option value="3">ADUANAS DE BOGOT&Aacute;</option>
            </select>
        </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Disposicion de Carga</div></td>
        <td class="celda_tabla_principal celda_boton">
            <p>
            <font color="#FF0000"><strong>(*)</strong></font>
            <input type="hidden" name="id_guia" value="<?php echo $id_guia ?>">
            <input type="hidden" name="hija" value="<?php echo $hija ?>">
            <select name="disposicion" id="disposicion" tabindex="3" onchange="showTipoDeposito(this.value)">
              <option value="">Seleccione Uno</option>
              <?php
                                $sql="SELECT id,nombre FROM disposicion_cargue WHERE estado='A' ORDER BY nombre";
                                $consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
                                while($fila=mysql_fetch_array($consulta))
                                {
                                    echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                                }
                            ?>
            </select>
            <input name="cod_disposicion" type="hidden" id="cod_disposicion" />
              <input name="cod_embarcador" type="hidden" id="cod_embarcador" />
              <input name="cod_consignatario" type="hidden" id="cod_consignatario" />
              <input name="cod_deposito" type="hidden" id="cod_deposito" />
              <input name="cod_cabotaje" type="hidden" id="cod_cabotaje" />
              <input name="cod_departamento_destino" type="hidden" id="cod_departamento_destino"  value="11" />
              <input name="cod_ciudad_destino" type="hidden" id="cod_ciudad_destino" value="11001" />
              <input name="cod_ciudad_embarcadora" type="hidden" id="cod_ciudad_embarcadora" />
              <input name="asignacion_directa" type="hidden" id="asignacion_directa" value="S" />
        </p>
        </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">No Guia</div></td>
        <td class="celda_tabla_principal celda_boton"><?php echo $hija ?></td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Embarcador</div></td>
        <td width="60%"  class="celda_tabla_principal celda_boton">
            <div id="dv_embarcador">
                  <p><font color="#FF0000"><strong>(*)</strong></font>
                    <select name="embarcador" id="embarcador" tabindex="5" onfocus="pasar_embarcador(this.value);" onchange="pasar_embarcador(this.value);">
                      <option value="" selected="selected">Seleccione Uno</option>
                      <?php
							$sql="SELECT id,nombre FROM embarcador WHERE estado='A' ORDER BY nombre";
							$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
							while($fila=mysql_fetch_array($consulta))
							{
								echo '<option value="'.$fila['id'].'">'.substr($fila['nombre'],0,35).'</option>';
							}
                        ?>
                    </select>
                    <button type="button" name="btn__reload_embarcador" id="btn__reload_embarcador" onclick="llamadasin('ajax_embarcador.php', 'dv_embarcador', 'embarcador')"> 
                    	<img src="imagenes/recargar-act.png" alt="" title="Recargar"/>
                    </button>
                    <button type="button" name="btn_embarcador" id="btn_embarcador" onclick="openPopup('embarcador_nuevo_popup.php','new','750','400','scrollbars=1',true)"> <img src="imagenes/agregar-act.png" alt="" title="Agregar"/></button>
                  </p>
              </div>
          </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
        <td>
                <div id="dv_consignatario">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="25%"  class="celda_tabla_principal"><font size="-1">Buscar..........................</font></td>
                          <td width="75%"  class="celda_tabla_principal"><font color="#FF0000"><strong>(*)</strong></font>
                            <input type="text" name="auto_consgnatario" id="auto_consgnatario" tabindex="6"/>
                            <button type="button" onclick="showHint(auto_consgnatario.value)" tabindex="7"> <img src="imagenes/buscar-act.png" alt="" title="Buscar"/> </button></td>
                        </tr>
                        <tr>
                          <td class="celda_tabla_principal" align="center"><button name="btn_consignatario" id="btn_consignatario" onclick="llamadasin('ajax_consignatario.php', 'dv_consignatario', 'consignatario')" tabindex="8"> <img src="imagenes/buscar.png" width="60" height="60" alt="Activar el selector" /> <br />
                            <strong>Seleccionar</strong> </button></td>
                          <td class="celda_tabla_principal celda_boton"><span id="txtConsignatario"></span></td>
                        </tr>
                        <tr>
                          <td colspan="2" class="celda_tabla_principal celda_boton" >&nbsp;</td>
                        </tr>
                      </table>
                </div>        
        </td>
      </tr>
      <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Datos del Deposito</div></td>
            <td class="celda_tabla_principal celda_boton">
            	<div id="dv_tipo_deposito"></div>
              	<div id="dv_evaluador"></div>
              	<div id="dv_ciudad"></div>
              	<div id="dv_deposito"></div>
              	<input type="hidden" name="tipo_disposicion" id="tipo_disposicion" value=""/>
             </td>
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
        <td class="celda_tabla_principal celda_boton"><font color="#FF0000"><strong>(*)</strong></font><input name="descripcion" type="text" id="descripcion" tabindex="12" size="50"/></td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha de corte</div></td>
        <td class="celda_tabla_principal celda_boton">
        <p>
          <input type="text" name="fecha_corte" id="fecha_corte" readonly="readonly"/>
          <input type="button" id="lanzador" value="..." tabindex="13"/>
          <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
          <!-- script que define y configura el calendario-->
          <script type="text/javascript">
                    Calendar.setup({
                        inputField     :    "fecha_corte",      // id del campo de texto
                        ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                        button         :    "lanzador"   // el id del botón que lanzará el calendario
                    });
                </script>
        </p>
          <p><img src="imagenes/alerta-act.png" width="33" height="29" align="absmiddle" />Recuerde que la fecha no puede ser superior a la fecha actual </p></td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo de Carga</div></td>
        <td class="celda_tabla_principal celda_boton">
            <select name="tipo_carga" id="tipo_carga" tabindex="14">
              <option value="1" selected="selected">SUELTA</option>
              <?php
                                $sql="SELECT id,nombre FROM tipo_carga WHERE estado='A' ORDER BY nombre";
                                $consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
                                while($fila=mysql_fetch_array($consulta))
                                {
                                    echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
                                }
                            ?>
              </select>
         </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Precursores</div></td>
        <td class="celda_tabla_principal celda_boton">
        	NO <input type="radio" name="precursor" value="N" checked="checked" tabindex="15" />
          	SI <input type="radio" name="precursor" value="S" tabindex="16"/></td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Agente de Carga</div></td>
        <td  class="celda_tabla_principal celda_boton">
                <div id="dv_agente">
                  <p>
                    <select name="agente_carga" id="agente_carga" tabindex="17" >
                      <option value="" selected="selected">Seleccione Uno</option>
                      <?php
                                $sql="SELECT id,razon_social FROM agente_carga WHERE estado='A' ORDER BY razon_social";
                                $consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
                                while($fila=mysql_fetch_array($consulta))
                                {
                                    echo '<option value="'.$fila['id'].'">'.$fila['razon_social'].'</option>';
                                }
                            ?>
                    </select>
                    <button type="button" name="btn__reload_acarga" id="btn__reload_acarga" onclick="llamadasin('ajax_agente_carga.php', 'dv_agente', 'agente')"> <img src="imagenes/recargar-act.png" alt="" title="Recargar"/></button>
                    <button type="button" name="btn_acarga" id="btn_acarga" onclick="openPopup('agente_carga_popup.php','new','750','400','scrollbars=1',true)"> <img src="imagenes/agregar-act.png" alt="" title="Agregar"/> </button>
                  </p>
                </div>
        </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Flete</div></td>
        <td class="celda_tabla_principal celda_boton">$<input name="flete" type="text" size="15" maxlength="15" tabindex="18" onKeyPress="return numeric2(event)"/></td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
        <td class="celda_tabla_principal celda_boton"><textarea name="observaciones" cols="40" rows="5" tabindex="19"></textarea></td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha de Finalizacion</div></td>
        <td class="celda_tabla_principal celda_boton">
            <p>
              <font color="#FF0000"><strong>(*)</strong></font>
              <input type="text" name="fecha_finalizacion" id="fecha_finalizacion" readonly="readonly"/>
              <input type="button" id="lanzador2" value="..." tabindex="20"/>
              <!-- formulario con el campo de texto y el botón para lanzar el calendario-->
              <!-- script que define y configura el calendario-->
              <script type="text/javascript">
                        Calendar.setup({
                            inputField     :    "fecha_finalizacion",      // id del campo de texto
                            ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
                            button         :    "lanzador2"   // el id del botón que lanzará el calendario
                        });
                    </script>
              <input type="hidden" name="tipo" value="1" />
            </p>
        </td>
      </tr>
    </table>
  </tr>
</table>

<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
      	<button type="reset" name="reset" id="reset" tabindex="22"> <img src="imagenes/descargar-act.png" alt="" title="Limpiar" /></button>
        <button type="submit" name="guardar" id="guardar" tabindex="21"> <img src="imagenes/guardar-act.png" alt="" title="Guardar" /> </button>
      </td>
    </tr>
</table>
  
</form>
</body>
</html>
