<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="tema/estilo.css" rel="stylesheet" type="text/css" />
<script language="javascript">
// 1. Evalua cuando la disposicion fue cabotaje y ahora trae una unava admin aduanera y un nuevo tipo de deposito para cargar el deposito basado ene stos parametros
function showTdocumento(str_tipo_doc)
{
//Limpia datos
 if (str_tipo_doc=="")
   {
   document.getElementById("dv_campos").innerHTML="";
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
     	document.getElementById("dv_campos").innerHTML=xmlhttp.responseText;
     }
	 else
	 {
		 document.getElementById("dv_campos").innerHTML='Cargando..<img src="imagenes/cargando.gif" align="middle"/><br />';
	 }
   }
 xmlhttp.open("GET","ajax_campos_tipodoc.php?tipodoc="+str_tipo_doc,true);
 xmlhttp.send(); 
document.getElementById("dv_campos").innerHTML="";
document.forms[0].identificacion.value="";
document.forms[0].primer_apellido.value="";
document.forms[0].segundo_apellido.value="";
document.forms[0].primer_nombre.value="";
document.forms[0].otros_nombres.value="";
document.forms[0].razon_social.value="";
document.forms[0].dv.value="";
//******************************************************************************
}

//
//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
//***********************

//Paso de valores a campos fijos para evaluacion
function pasar_identificacion(dato){
	document.forms[0].identificacion.value=dato;
}

function pasar_papellido(dato){
	document.forms[0].primer_apellido.value=dato;
}

function pasar_sapellido(dato){
	document.forms[0].segundo_apellido.value=dato;
}

function pasar_pnombre(dato){
	document.forms[0].primer_nombre.value=dato;
}

function pasar_snombre(dato){
	document.forms[0].otros_nombres.value=dato;
}

function pasar_razonsocial(dato){
	document.forms[0].razon_social.value=dato;
}

function pasar_dv(dato){
	document.forms[0].dv.value=dato;
}
//************************************************


// funcion para validar
function validar()
{
	if (document.forms[0].tipo_documento.value=="")
	{
		alert("Atencion: Debe Seleccionar un TIPO DE DOCUMENTO");
		document.forms[0].tipo_documento.focus();
		return(false);
	}
		
	//Evalua el caso de Nit
	if (document.forms[0].tipo_documento.value=="31")  
	{
		if (document.forms[0].identificacion.value=="")
		{
			alert("Atencion: Ingrese un Numero de IDENTIFICACION");
			return(false);
		}
		
		if (document.forms[0].dv.value=="")
		{
			alert("Atencion: Debe Ingresar un codigo identificador del NIT, Ingrese el numero sigueinte al guion (-)");
			return(false);
		}

		if (document.forms[0].razon_social.value=="")
		{
			alert("Atencion: Debe Ingresar una RAZON SOCIAL");
			return(false);
		}
	}
	
	//Evalua el caso sin identificaion y el Resto de posibles opciones
	else if (document.forms[0].tipo_documento.value=="43")   //SIN  identificar por la DIAN
	{
		if (document.forms[0].razon_social.value=="")
		{
			alert("Atencion: Debe Ingresar una RAZON SOCIAL");
			return(false);
		}
	}
	else //El Resto de Opciones
	{
		if (document.forms[0].identificacion.value=="")
		{
			alert("Atencion: Ingrese un Numero de IDENTIFICACION");
			return(false);
		}
		if (document.forms[0].primer_apellido.value=="")
		{
			alert("Atencion: Debe Ingresar el PRIMER APELLIDO");
			return(false);
		}
		if (document.forms[0].segundo_apellido.value=="")
		{
			alert("Atencion: Debe Ingresar el SEGUNDO APELLIDO");
			return(false);
		}
		if (document.forms[0].primer_nombre.value=="")
		{
			alert("Atencion: Debe Ingresar el PRIMER NOMBRE");
			return(false);
		}
	}
}
</script>
</head>
<body>
<p class="titulo_tab_principal">Embarcador</p>
<form  action="#" onSubmit="return validar();" method="post">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo de Documento</div></td>
    <td class="celda_tabla_principal celda_boton">
	<select name="tipo_documento" id="tipo_documento" tabindex="1" onChange="showTdocumento(this.value);">
		<option value="" selected="selected">Seleccione Uno</option>
        <?php
			$sql="SELECT id,nombre FROM tipo_documento_id WHERE estado='A' ORDER BY nombre ASC";
			$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
			while($fila=mysql_fetch_array($consulta))
				{
					echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
				}
		?>
        </select>
        <script>document.forms[0].tipo_documento.focus();</script>
        <input type="hidden" name="identificacion" id="identificacion">
        <input type="hidden" name="primer_apellido" id="primer_apellido">
        <input type="hidden" name="segundo_apellido" id="segundo_apellido">
        <input type="hidden" name="primer_nombre" id="primer_nombre">
        <input type="hidden" name="otros_nombres" id="otros_nombres">
		<input type="hidden" name="razon_social" id="razon_social">
        <input type="hidden" name="dv" id="dv">
    </td>
  </tr>
  <tr>
    <td colspan="2" class="celda_tabla_principal"><div id="dv_campos"></div></td>
  </tr> 
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono</div></td>
    <td class="celda_tabla_principal celda_boton"><input name="telefono" id="telefono" type="text" size="30" maxlength="10" tabindex="3" onkeypress="return numeric(event)" /></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Direccion</div></td>
    <td class="celda_tabla_principal celda_boton"><input name="direccion" id="direccion" type="text" size="30" tabindex="4" maxlength="200" /></td>
  </tr>
</table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="reset" name="reset" id="reset" tabindex="6">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="submit" name="guardar" id="guardar" tabindex="5">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
          </td>
        </tr>
     </table>    

</form>
<?php
if(isset($_POST["tipo_documento"]))
{
	$tipo_documento=$_POST["tipo_documento"];
	$identificacion=$_POST["identificacion"];
	$primer_apellido=strtoupper($_POST["primer_apellido"]);
	$segundo_apellido=strtoupper($_POST["segundo_apellido"]);
	$primer_nombre=strtoupper($_POST["primer_nombre"]);
	$otros_nombres=strtoupper($_POST["otros_nombres"]);
	$razon_social=strtoupper($_POST["razon_social"]);
	$dv=$_POST["dv"];
	$telefono=$_POST["telefono"];
	$direccion=strtoupper($_POST["direccion"]);
	
	switch ($tipo_documento)
	{
		case 31:
			$razon_social=strtoupper($_POST["razon_social"]);
		break;
		
		case 43:
			$razon_social=strtoupper($_POST["razon_social"]);
		break;
		
		default:
			$razon_social=$primer_nombre." ".$otros_nombres." ".$primer_apellido." ".$segundo_apellido;
		break;
	}
	
	$sql="INSERT INTO embarcador (id_tipo_documento,
									nit,
									dv,
									primer_apellido,
									segundo_apellido,
									primer_nombre,
									otros_nombres,
									nombre,
									telefono,
									direccion) 
										value ('$tipo_documento',
											   '$identificacion',
											   '$dv',
											   '$primer_apellido',
											   '$segundo_apellido',
											   '$primer_nombre',
											   '$otros_nombres',
											   '$razon_social',
											   '$telefono',
											   '$direccion')";
	mysql_query($sql,$conexion) or die (mysql_error());	
	echo '
	<script language="javascript">
		alert("Registro Almacenado Con Exito");
		window.close();
	</script>';
}
?>
</body>
</html>