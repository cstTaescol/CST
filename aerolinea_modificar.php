<?php
	$sql="SELECT * FROM aerolinea WHERE id='$id_registro'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$id=$fila['id'];
	$nit=$fila['nit'];
	$nombre=$fila['nombre'];
	$telefono1=$fila['telefono1'];
	$telefono2=$fila['telefono2'];
	$direccion=$fila['direccion'];
	$contacto=$fila['contacto'];
	$email=$fila['email'];
	$importacion=$fila['importacion'];
	$courier=$fila['courier'];
	$horas_despaletizaje=$fila['horas_despaletizaje'];
	
	if ($importacion == true)
		$importacion ='checked="checked"';
	else
		$importacion ='';	

	if ($courier == true)
		$courier ='checked="checked"';
	else
		$courier ='';	
		
		
	
?>
<p class="titulo_tab_principal">Registro de Aerolineas</p>
<div id="respuesta" class="opaco_ie" style="background-image:url(imagenes/background.png); width:100%; height:30px"></div>
<form method="post" name="frm_guardar" id="frm_guardar">
	<table align="center">
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">Nit</div></td>
	      <td class="celda_tabla_principal celda_boton"><input name="nit" type="text" id="nit" tabindex="1" size="15" maxlength="15" onKeyPress="return numeric(event)" value="<?php echo $nit; ?>"></td>
	    </tr>
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
	      <td class="celda_tabla_principal celda_boton"><input type="hidden" name="id" id="id" value="<?php echo $id; ?>" /><?php echo $nombre; ?></td>
	    </tr>
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono 1</div></td>
	      <td class="celda_tabla_principal celda_boton"><input name="telefono1" type="text" tabindex="3" size="15" maxlength="15" value="<?php echo $telefono1; ?>">  </td>
	    </tr>
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono 2</div></td>
	      <td class="celda_tabla_principal celda_boton"><input name="telefono2" type="text" tabindex="4" size="15" maxlength="15" value="<?php echo $telefono2; ?>"></td>
	    </tr>
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">Direccion</div></td>
	      <td class="celda_tabla_principal celda_boton"><input name="direccion" type="text" tabindex="5" size="40" maxlength="100" value="<?php echo $direccion; ?>"></td>
	    </tr>
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">Contacto</div></td>
	      <td class="celda_tabla_principal celda_boton"><input name="contacto" type="text" tabindex="6" size="40" maxlength="50" value="<?php echo $contacto; ?>"></td>
	    </tr>
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">E-Mail</div></td>
	      <td class="celda_tabla_principal celda_boton"><input name="mail" type="text" tabindex="7" size="50" maxlength="100" value="<?php echo $email; ?>"></td>
	    </tr> 	    	
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">Horas Despaletizaje</div></td>
	      <td class="celda_tabla_principal celda_boton"><input type="text" name="horas_despaletizaje" size="5" maxlength="2" tabindex="8" onKeyPress="return numeric(event)" value="<?php echo $horas_despaletizaje; ?>"/></td>
	    </tr> 	    	
        <tr>
          <td class="celda_tabla_principal" colspan="2"><div class="letreros_tabla">Tipo de Aerol&iacute;nea</div></td>
        </tr>    
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Importaci&oacute;n</div></td>
          <td class="celda_tabla_principal celda_boton"><input name="tipo_importacion" id="tipo_importacion" value="1" type="checkbox" tabindex="9" <?php echo $importacion; ?>></td>
        </tr>
        <tr>
          <td class="celda_tabla_principal"><div class="letreros_tabla">Courier</div></td>
          <td class="celda_tabla_principal celda_boton"><input name="tipo_courier" id="tipo_courier" value="1"  type="checkbox" tabindex="10" <?php echo $courier; ?>></td>
        </tr>
	</table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" onclick="document.location='listar_aerolinea.php'">
                <img src="imagenes/al_principio-act.png" title="Atras" />
            </button>
            <button type="reset" name="reset" id="reset">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="button" name="guardar" id="guardar" onClick="return validar();">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
          </td>
        </tr>
     </table>    
</form>
<script language="javascript">
function validar()
{	
	/*
	if (document.frm_guardar.id_transportador.value=="")
	{
		alert("Atencion: Se requiere el TRANSPORTADOR.");
		document.frm_guardar.id_transportador.focus();
		return(false);
	}
	*/
	guardar_form();				
}
	

function guardar_form()
{
	var peticion = new Request(
	{
		url: 'aerolinea_modificar_salvar.php',
		method: 'post',
		onRequest: function()
		{
			$('respuesta').innerHTML='<p align="center">Procesando...<image src="imagenes/cargando.gif"></p>';
			$('guardar').disabled=true;
			$('reset').disabled=true;
		},			
		onSuccess: function(responseText)
		{
			var respuesta;
			respuesta=eval(responseText);
			switch (respuesta)
			{
				case 0:
					$('respuesta').innerHTML='<p align="center">Error al guardar los datos...</p>';
				break;
				case 1:
					$('respuesta').innerHTML='<p align="center">Registro almacenado de manera exitosa...</p>';
					alert('Registro almacenado de manera exitosa...');
					document.location='listar_aerolinea.php';
				break;
			}
			$('guardar').disabled=false;
			$('reset').disabled=false;
		},
		onFailure: function()
		{
			$('respuesta').innerHTML='<p align="center">Error al guardar, Intente de nuevo...</p>';
			$('guardar').disabled=false;
			$('reset').disabled=false;
		}
	}
	);
	peticion.send($('frm_guardar'));
}
	//Validacion de campos numéricos
	function numeric(e) 
	{ // 1
	    tecla = (document.all) ? e.keyCode : e.which; // 2
	    if (tecla==8) return true; // 3
	    patron =/[0-9\n]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
	    te = String.fromCharCode(tecla); // 5
	    return patron.test(te); // 6
	} 
</script>
