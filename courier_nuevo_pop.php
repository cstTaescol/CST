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
<script type="text/javascript" src="js/mootools-core-1.4.5-full-nocompat.js"></script>
<script type="text/javascript" src="js/mootools-more-1.4.0.1.js"></script>
<script language="javascript">

// 1. Evaluar Departamento para cargar ciudades 
function showCiudad(str_id_departamento)
{
//Limpia datos
 if (str_id_departamento=="")
   {
   document.getElementById("dv_ciudad").innerHTML="";
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
     document.getElementById("dv_ciudad").innerHTML=xmlhttp.responseText;
     }
   }
 xmlhttp.open("GET","ajax_ciudad.php?id_departamento="+str_id_departamento,true);
 xmlhttp.send(); 
//Lipieza de Campos a Usar 
document.getElementById("dv_ciudad").innerHTML="";
document.forms[0].id_ciudad.value="";
}

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
function pasar_ciudad(dato){
	document.forms[0].id_ciudad.value=dato;
}
//************************************************

// funcion para validar
function validar()
{
	if (document.forms[0].identificacion.value=="")
	{
		alert("Atencion: Ingrese un Numero de IDENTIFICACION");
		return(false);
	}
	
	if (document.forms[0].dv.value=="")
	{
		alert("Atencion: Debe Ingresar un codigo identificador del NIT, Ingrese el numero siguinte al guion (-)");
		return(false);
	}

	if (document.forms[0].razon_social.value=="")
	{
		alert("Atencion: Debe Ingresar una RAZON SOCIAL");
		return(false);
	}						
	
	if (document.forms[0].id_ciudad.value=="")
	{
		alert("Atencion: Seleccione un DEPARTAMENTO y una CIUDAD");
		return(false);
	}
}

function verificar_nit()
{
	var peticion = new Request(
	{
		url: 'ajax_verificar_nit.php',
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
			//alert (respuesta);
			//900144535
			var arreglos = respuesta.split("-");
			var error = eval(arreglos[0]);
			var accion = arreglos[1];
			var mensaje = arreglos[2];
			if (error != 1)
			{
				if (accion == 1)
				{
					$('respuesta').innerHTML='<p align="center">El Consignatario YA EXISTE con el nombre<br>'+mensaje+'</p>';
					alert("Atencion: El Consignatario YA existe.");
					$('guardar').disabled=true;
					$('reset').disabled=false;
				}
				else
				{
					$('respuesta').innerHTML='';
					$('guardar').disabled=false;
					$('reset').disabled=false;					
				}
			}
			else
			{
				$('respuesta').innerHTML='<p align="center">Error al Procesar' + mensaje +', Intente de nuevo...</p>';
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
	peticion.send('nit='+$('identificacion').value);
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
</head>
<body>
<p class="titulo_tab_principal">Courier</p>
<form  action="#" onSubmit="return validar();" method="post">
    <table align="center">
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">NIT</div></td>
        <td class="celda_tabla_principal celda_boton">
            <input type="text" name="identificacion" id="identificacion"  maxlength="20" onkeypress="return numeric(event)" onchange="verificar_nit();"> -
            <input type="text" name="dv" id="dv" maxlength="1" size="1" onkeypress="return numeric(event)"><font color="red">*</font>
            <input type="hidden" name="id_ciudad" id="id_ciudad" value="5">
            <script>document.forms[0].identificacion.focus();</script>            
        </td>
      </tr>
      <tr><td colspan="2" class="celda_tabla_principal"><div class="letreros_tabla"><div id="respuesta"></div></div></td></tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Razon Social</div></td>
        <td class="celda_tabla_principal celda_boton"><input type="text" name="razon_social" id="razon_social" size="40" ><font color="red">*</font></td>
      </tr> 
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono</div></td>
        <td class="celda_tabla_principal celda_boton"><input name="telefono" id="telefono" type="text" size="30" maxlength="10" onkeypress="return numeric(event)" tabindex="2" /></td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Departamento</div></td>
        <td class="celda_tabla_principal celda_boton">
        <select name="departamento" id="departamento" onchange="showCiudad(this.value)" tabindex="3">
          <option value="11">BOGOTA, D.C.</option>
          <?php
                $sql="SELECT codigo,nombre FROM departamento WHERE estado='A' ORDER BY nombre";
                $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                while($fila=mysql_fetch_array($consulta))
                {
                    echo '<option value="'.$fila['codigo'].'">'.$fila['nombre'].'</option>';
                }
            ?>
          </select></td>
      </tr>
      <tr>
        <td colspan="2" class="celda_tabla_principal"><div id="dv_ciudad"></div></td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Direccion</div></td>
        <td class="celda_tabla_principal celda_boton"><input name="direccion" id="direccion" type="text" size="30" maxlength="200" tabindex="4" /></td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">E-mails</div></td>
        <td class="celda_tabla_principal celda_boton">
            <p><input name="emails" id="emails" type="email" size="30" tabindex="5"/></p>
            <p class="asterisco">Separe todas las direcciones de correo con una coma (,)</p>
            <p><font color="#00CC66"><strong>Ejemplo:</strong>correo1@email.com, correo2@email.com, etc</font></p>
         </td>
      </tr>
    </table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="reset" name="reset" id="reset">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="submit" name="guardar" id="guardar">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
          </td>
        </tr>
     </table>    
</form>
<?php
if(isset($_POST["identificacion"]))
{
	$identificacion=$_POST["identificacion"];
	$razon_social=strtoupper($_POST["razon_social"]);
	$dv=$_POST["dv"];
	$telefono=$_POST["telefono"];
	$id_ciudad=$_POST["id_ciudad"];
	$departamento=$_POST["departamento"];
	$direccion=strtoupper($_POST["direccion"]);
	$emails=$_POST["emails"];	
	
	$sql="INSERT INTO couriers (id_tipo_documento,
									nit,
									dv,
									nombre,
									telefono,
									direccion,
									id_departamento,
									id_ciudad,
									emails) 
										value ('31',
											   '$identificacion',
											   '$dv',
											   '$razon_social',
											   '$telefono',
											   '$direccion',
											   '$departamento',
											   '$id_ciudad',
											   '$emails')";
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