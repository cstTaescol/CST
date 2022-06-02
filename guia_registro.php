<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
//Funcion que redirecciona la pagina hacia cerrar la cesion automaticamente en 10 minutos
//Se aplica por que phpinfo() indica las sesion con duracion máxima de 24 minutos
//Controla el ID de Usuario sin iniciar el contador de tiempo que afecta el registro de las guias.
if (!isset($_SESSION['id_usuario']))
{
	echo "
	<script tipe='JavaScript'>
		alert('Sesion finalizada, Vuelva a iniciarla');
		function actulizar(url){parent.location=url;}javascript:actulizar('index.php');
	</script>";	
	exit;
}
//***************************************************************************

$id_usuario=$_SESSION['id_usuario'];


//Cuando llegua a este punto por alguna selección de vuelo
if (isset($_REQUEST["aerolinea"]))
{
	$_SESSION["aerolinea"]=$_REQUEST["aerolinea"];
	$aerolinea=$_REQUEST["aerolinea"];
}
else
{
	$aerolinea=$_SESSION["aerolinea"];
}

if (isset($_REQUEST["id_vuelo"]))
{
	$_SESSION["id_vuelo"]=$_REQUEST["id_vuelo"];
}

if (isset($_REQUEST["addGuia"]))
{
	$_SESSION["addGuia"]=$_REQUEST["addGuia"];
}


$sql="SELECT nombre FROM aerolinea WHERE id='$aerolinea' AND estado='A' ORDER BY nombre";
$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
$fila=mysql_fetch_array($consulta);
$nombre_aerolinea=$fila['nombre'];
//*****************************************************************************

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
			document.getElementById("txtConsignatario").innerHTML='Cargando..<img src="imagenes/cargando.gif" align="middle"/><br />';
		}	 
   }
 xmlhttp.open("GET","ajax_consignatario_autocompletar.php?q="+str,true);
 xmlhttp.send();
 }
//************************************************************

//Busca coincidencias de guia en el mismo vuelo
function coincidencia_guia(guia,tipo_guia)
{
var respuesta="";
if (guia.length==0)
	{ 
  		document.getElementById("txtguia").innerHTML="";
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
	 	document.getElementById("txtguia").innerHTML=xmlhttp.responseText;
     }
   }
xmlhttp.open("GET","ajax_guia_autocompletar.php?guia="+guia+"&tipo_guia="+tipo_guia,true);
xmlhttp.send();
}
//*****************************************************************************************

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
</script>

<script language="javascript">
//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
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

// funcion para validar
function validar()
{
	if (document.forms[0].tipo.value=="1") //CUANDO SEA GUIA DIRECTA
	{
		if (document.forms[0].disposicion.value=="")
		{
			alert("Atencion: Debe seleccionar una DISPOSICION DE CARGUE");
			document.forms[0].disposicion.focus();
			return(false);
		}
		if (document.forms[0].guia.value=="")
		{
			alert("Atencion: Debe digitar un NUMERO DE GUIA");
			document.forms[0].guia.focus();
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

 		if (document.forms[0].piezas.value=="")
		{
			alert("Atencion: Debe digitar la cantidad de PIEZAS");
			document.forms[0].piezas.focus();
			return(false);
		}
		if (document.forms[0].peso.value=="")
		{
			alert("Atencion: Debe digitar la cantidad de PESO");
			document.forms[0].peso.focus();
			return(false);
		}
		if (document.forms[0].volumen.value=="")
		{
			alert("Atencion: Debe digitar la cantidad de VOLUMEN");
			document.forms[0].volumen.focus();
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
		if (document.forms[0].agente_carga.value=="")
		{
			alert("Atencion: Debe asignar un AGENTE DE CARGA");
			document.forms[0].agente_carga.focus();
			return(false);
		}

	}
	if (document.forms[0].tipo.value=="2") //CUANDO SEA UN CONSOLIDADO
	{
		if (document.forms[0].guia.value=="")
		{
			alert("Atencion: Debe digitar un NUMERO DE GUIA");
			document.forms[0].guia.focus();
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
		if (document.forms[0].descripcion.value=="")
		{
			alert("Atencion: Debe digitar la DESCRIPCION");
			document.forms[0].descripcion.focus();
			return(false);
		}
	}
	if (document.forms[0].tipo.value=="3") //CUANDO SEA  GUIA HIJA
	{
		if (document.forms[0].disposicion.value=="")
		{
			alert("Atencion: Debe seleccionar una DISPOSICION DE CARGUE");
			document.forms[0].disposicion.focus();
			return(false);
		}
		if (document.forms[0].master.value=="")
		{
			alert("Atencion: Debe seleccionar un CONSOLIDADO (Master)");
			document.forms[0].master.focus();
			return(false);
		}
		if (document.forms[0].guia.value=="")
		{
			alert("Atencion: Debe digitar un NUMERO DE GUIA");
			document.forms[0].guia.focus();
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

 		if (document.forms[0].piezas.value=="")
		{
			alert("Atencion: Debe digitar la cantidad de PIEZAS");
			document.forms[0].piezas.focus();
			return(false);
		}
		if (document.forms[0].peso.value=="")
		{
			alert("Atencion: Debe digitar la cantidad de PESO");
			document.forms[0].peso.focus();
			return(false);
		}
		if (document.forms[0].volumen.value=="")
		{
			alert("Atencion: Debe digitar la cantidad de VOLUMEN");
			document.forms[0].volumen.focus();
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
		if (document.forms[0].agente_carga.value=="")
		{
			alert("Atencion: Debe asignar un AGENTE DE CARGA");
			document.forms[0].agente_carga.focus();
			return(false);
		}		
	}
	if (document.forms[0].tipo.value=="4") //CUANDO SEA CORREO
	{
		if (document.forms[0].guia.value=="")
		{
			alert("Atencion: Debe digitar un NUMERO DE GUIA");
			document.forms[0].guia.focus();
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
		if (document.forms[0].piezas.value=="")
		{
			alert("Atencion: Debe digitar la cantidad de PIEZAS");
			document.forms[0].piezas.focus();
			return(false);
		}
		if (document.forms[0].peso.value=="")
		{
			alert("Atencion: Debe digitar la cantidad de PESO");
			document.forms[0].peso.focus();
			return(false);
		}
		if (document.forms[0].volumen.value=="")
		{
			alert("Atencion: Debe digitar la cantidad de VOLUMEN");
			document.forms[0].volumen.focus();
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
		if (document.forms[0].agente_carga.value=="")
		{
			alert("Atencion: Debe asignar una AGENTE DE CARGA");
			document.forms[0].agente_carga.focus();
			return(false);
		}				
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
<script language="javascript">
String.prototype.replaceLatinChar = function(){
 return output = this.replace(/á|é|í|ó|ú|ñ|ä|ë|ï|ö|ü|ù|ò|ì|è|à/ig,function (str,offset,s) {
        var str =str=="á"?"a":str=="é"?"e":str=="í"?"i":str=="ó"?"o":str=="ú"?"u":str=="ñ"?"n":str=="ù"?"u":str=="ò"?"o":str=="ì"?"i":str=="è"?"e":str=="à"?"a":str;
		   str =str=="Á"?"A":str=="É"?"E":str=="Í"?"I":str=="Ó"?"O":str=="Ú"?"U":str=="Ñ"?"N":str=="Ù"?"U":str=="Ù"?"U":str=="Ò"?"O":str=="Ì"?"I":str=="È"?"E":str=="À"?"A":str;
		   str =str=="Á"?"A":str=="É"?"E":str=="Í"?"I":str=="Ó"?"O":str=="Ú"?"U":str=="Ñ"?"N":str=="Ù"?"U":str=="Ù"?"U":str=="Ò"?"O":str=="Ì"?"I":str=="È"?"E":str=="À"?"A":str;
		   str =str=="ä"?"a":str=="ë"?"e":str=="ï"?"i":str=="ö"?"o":str=="ü"?"u":str=="ù"?"u":str=="ò"?"o":str=="ì"?"i":str=="è"?"e":str=="à"?"a":str;
		   str =str=="Ä"?"A":str=="Ë"?"E":str=="Ï"?"I":str=="Ö"?"O":str=="Ù"?"U":str=="Ù"?"U":str=="Ò"?"O":str=="Ì"?"I":str=="È"?"E":str=="À"?"A":str;
        return (str);
        })
	
}
//Evaluador de Acentos***************
function acentos(str,name)
{
	document.getElementById(name).value=str.replaceLatinChar();
}
</script>


</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Registro de Guias</p>
<p align="center">
	<font color="#0000FF"><u><?php echo $nombre_aerolinea ?></u></font>
    <?php
	if (isset($_SESSION["id_vuelo"]))
		{
			$id_vuelo=$_SESSION["id_vuelo"];
			$sql="SELECT nvuelo FROM vuelo WHERE id='$id_vuelo'";
			$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			$fila=mysql_fetch_array($consulta);
			$nvuelo=$fila['nvuelo'];
			echo "<br /><font color=\"green\">Vuelo:".$nvuelo."</font>";
		}
	?>
</p>
<table width="30%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td colspan="4" align="center" class="celda_tabla_principal"><div class="letreros_tabla">Tipo de Guia</div></td>
  </tr>
  <tr align="center">
    <td width="24%" class="celda_tabla_principal">
    	<button type="button" name="directa" id="directa" onclick="document.location='guia_registro.php?tipo=1'">
        	Directa
        </button>
    </td>
    <td width="37%" class="celda_tabla_principal">
    	<button type="button" name="directa" id="directa" onclick="document.location='guia_registro.php?tipo=2'">
        	Consolidado
        </button>
    </td>
    <td width="21%" class="celda_tabla_principal">
    	<button type="button" name="directa" id="directa" onclick="document.location='guia_registro.php?tipo=3'">
        	Hija
        </button>
    </td>
    <td width="18%" class="celda_tabla_principal">
    	<button type="button" name="directa" id="directa" onclick="document.location='guia_registro.php?tipo=4'">
        	Correo
        </button>
    </td>
  </tr>
  <tr>
</table>
<?php
if(isset($_GET["tipo"]))  //si recibió algún tipo de guia
{
	$aerolinea=$_SESSION["aerolinea"];
	switch ($_GET["tipo"])
	{
		case 1:
			include ("guia_directa.php");
		break;
		case 2:
			include ("guia_consolidado.php");
		break;
		case 3:
			include ("guia_hija.php");
		break;
		case 4:
			include ("guia_correo.php");
		break;
	}
}
?>
</body>
</html>