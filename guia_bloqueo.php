<?php
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
if(isset($_GET["id_guia"])) 
{
	$id_guia=$_GET["id_guia"];
	$sql="SELECT * FROM guia WHERE id ='$id_guia'";
	$consulta=mysql_query($sql,$conexion) or die (exit('Error '.mysql_error()));
	$fila=mysql_fetch_array($consulta);
	$guia=$fila["hija"];
	$id_tipo_bloqueo=$fila["id_tipo_bloqueo"];
	$movilizacion=$fila["id_inmovilizacion"];
	if ($id_tipo_bloqueo==10)
	{
		$visibilidad_div="visible";
		$radio_cantidad_todos="";
		$radio_cantidad_parcial="checked";
	}
	else
		{
			$visibilidad_div="hidden";
			$radio_cantidad_todos="checked";
			$radio_cantidad_parcial="";
			
		}
	$n_acta_inmoforzosa=$fila["n_acta_inmoforzosa"];
	if ($n_acta_inmoforzosa == "")
	{
		$chek_inclusion="";
	}
	else
		{
			$chek_inclusion="checked";
		}
	$n_acta_bloqmanual=$fila["n_acta_bloqmanual"];
	if ($n_acta_bloqmanual == "")
	{
		$chek_manual="";
	}
	else
		{
			$chek_manual="checked";
		}
	
	
	$descripcion_bloqueo=$fila["descripcion_bloqueo"];
	$bloqueo_peso=$fila["bloqueo_peso"];
	$bloqueo_piezas=$fila["bloqueo_piezas"];
	include("config/evaluador_inconsistencias.php");
}
else
{
	exit ("Error al obtener la Gu&iacute;a, Consulte con el Soporte T&eacute;cnico");
}
?>
<html>
<head>
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
//Funcion para mostrar los valores.
function mostrar(eleccion)
{
	document.forms[0].piezasb.value="";
	document.forms[0].pesob.value="";
	document.forms[0].eleccion.value=eleccion;
	document.getElementById('datos_bloqueo').style.visibility='visible';
}

//Funcion para ocultar los valores.
function ocultar(eleccion)
{
	document.forms[0].piezasb.value="";
	document.forms[0].pesob.value="";
	document.forms[0].eleccion.value=eleccion;
	document.getElementById('datos_bloqueo').style.visibility='hidden';
}

// funcion para validar
function validar()
{
	activador=false;
	if (document.forms[0].ckinclusionf.checked==true)
	{
		num_caracteres = document.forms[0].txtinclusionf.value.length; 
		if (num_caracteres < 15)
		{
			alert("Atencion: Debe digitar una INCLUSION FORZOSA de 15 Caracteres.");
			document.forms[0].txtinclusionf.focus();
			return(false);		
		}
		activador=true;
	}
	
	if (document.forms[0].ckactamanual.checked==true)
	{
		num_caracteres = document.forms[0].txtactamanual.value.length; 
		if (num_caracteres < 6)
		{
			alert("Atencion: Debe digitar un ACTA MANUAL de 6 Caracteres.");
			document.forms[0].txtactamanual.focus();
			return(false);		
		}
		activador=true;
	}

	if (activador==false)
	{
		alert("Atencion: Debe SELECCIONAR un tipo de INMOVILIZACION, ya sea por INCLUSION FORZOSA o por ACTA MANUAL.");
		document.forms[0].ckinclusionf.focus();
		return(false);				
	}

	if (document.forms[0].eleccion.value=="P")
	{
		if (document.forms[0].piezasb.value=="")
		{
			alert("Atencion: Debe digitar una Cantidad de PIEZAS inferior a <?php echo $piezas; ?>");
			document.forms[0].piezasb.focus();
			return(false);
		}
		
		if (document.forms[0].piezasb.value >= <?php echo $piezas; ?>)
		{
			alert("Atencion: Debe digitar una Cantidad de PIEZAS inferior a <?php echo $piezas; ?>");
			document.forms[0].piezasb.focus();
			return(false);
		}
		
		if (document.forms[0].pesob.value=="")
		{
			alert("Atencion: Debe digitar una Cantidad de PESO inferior a <?php echo $peso; ?>");
			document.forms[0].pesob.focus();
			return(false);
		}
		
		if (document.forms[0].pesob.value >= <?php echo $peso; ?>)
		{
			alert("Atencion: Debe digitar una Cantidad de PESO inferior a <?php echo $peso; ?>");
			document.forms[0].pesob.focus();
			return(false);
		}
	}	
/*	
	if (document.forms[0].descripcion.value=="")
	{
		alert("Atencion: Debe digitar una DESCRIPCION del bloqueo de la Guia");
		document.forms[0].descripcion.focus();
		return(false);
	}
*/
}
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Bloqueo de Guia</p>
<form name="bloqueo" action="guia_bloqueo_salvar.php" onSubmit="return validar();" method="post">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<input type="text" name="mascara" value="<?php echo $guia; ?>" tabindex="1" readonly>
        <input type="hidden" name="id_guia" value="<?php echo $id_guia; ?>">
        <input type="hidden" name="accion" value="B">
   </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Datos del Bloqueo</div></td>
    <td class="celda_tabla_principal celda_boton">
    <select name="movilizacion" id="movilizacion" tabindex="2" >
      <?php
			$sql="SELECT id,nombre FROM movilizacion WHERE estado='A' AND tipo = 'I' ORDER BY nombre ASC";
			$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
			while($fila=mysql_fetch_array($consulta))
			{
				if ($movilizacion == $fila['id'])
				{$seleccion="selected";}
				else
					{$seleccion="";}
					
				echo '<option value="'.$fila['id'].'" '.$seleccion.'>'.$fila['nombre'].'</option>';
			}
		?>
    </select>
      <p>
          <strong>INCLUSION FORZOSA</strong><br><input type="checkbox" name="ckinclusionf" id="ckinclusionf" <?php echo $chek_inclusion ?>>
          										<input type="text" name="txtinclusionf" id="txtinclusionf" size="15" maxlength="15" onFocus="document.forms[0].ckinclusionf.checked=true;" value="<?php echo $n_acta_inmoforzosa ?>" onKeyPress="return numeric(event)">
      </p>
<script type="text/javascript">document.forms[0].ckinclusionf.focus();</script>      
      <p>
          <strong>ACTA MANUAL</strong><br><input type="checkbox" name="ckactamanual" id="ckactamanual" <?php echo $chek_manual ?>>
          								  <input type="text" name="txtactamanual" id="txtactamanual" size="6" maxlength="6" onFocus="document.forms[0].ckactamanual.checked=true" value="<?php echo $n_acta_bloqmanual ?>" onKeyPress="return numeric(event)">
      </p>    
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Cantidad</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<input type="radio" name="rdcantidad" id="rdcantidad" value="T" <?php echo $radio_cantidad_todos ?> onClick="ocultar('T');">
    	Toda la Guia<br>
        <input type="radio" name="rdcantidad" id="rdcantidad" value="P" <?php echo $radio_cantidad_parcial ?> onClick="mostrar('P');">
        Guia Parcial<br>
        <input name="eleccion" type="hidden" id="eleccion" value="T">
        <div id="datos_bloqueo" style="visibility:<?php echo $visibilidad_div  ?>">
       	  	<input name="piezasb" id="piezasb" type="text" size="5" maxlength="10" value="<?php echo $bloqueo_piezas ?>" onKeyPress="return numeric(event)"/>PIEZAS<br>
        	<input name="pesob" id="pesob" type="text" size="5" maxlength="10" value="<?php echo $bloqueo_peso ?>" onKeyPress="return numeric2(event)"/>PESO<br>
        </div>
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
    <td class="celda_tabla_principal celda_boton">
        <textarea name="descripcion" cols="40" rows="5" id="descripcion"><?php echo $descripcion_bloqueo ?></textarea>
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
</body>
</html>