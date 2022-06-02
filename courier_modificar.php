<?php
	$sql="SELECT * FROM couriers WHERE id='$id_registro'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$id=$fila['id'];
	$nombre=$fila['nombre'];
	$telefono1=$fila['telefono'];
	$direccion=$fila['direccion'];
	$emails=$fila['emails'];
?>
<p class="titulo_tab_principal">Courier</p>
<div id="respuesta" class="opaco_ie" style="background-image:url(imagenes/background.png); width:100%; height:30px"></div>
<form method="post" name="frm_guardar" id="frm_guardar">
	<table align="center">
	  <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
	    <td class="celda_tabla_principal celda_boton"><input type="hidden" name="id" id="id" value="<?php echo $id; ?>" /><?php echo $nombre; ?></td>
	  </tr>
	  <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono</div></td>
	    <td class="celda_tabla_principal celda_boton"><input name="telefono" id="telefono" type="text" size="30" maxlength="10" onkeypress="return numeric(event)" tabindex="2" value="<?php echo $telefono1; ?>" /></td>
	  </tr>
	  <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Direccion</div></td>
	    <td class="celda_tabla_principal celda_boton"><input name="direccion" id="direccion" type="text" size="30" maxlength="200" tabindex="3" value="<?php echo $direccion; ?>"/></td>
	  </tr>
	  <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">E-mail</div></td>
	    <td class="celda_tabla_principal celda_boton">
        	<p><input name="emails" id="emails" type="text" size="30" tabindex="4" value="<?php echo $emails; ?>"/></p>
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
            <button type="button" name="cancelar" id="cancelar" onclick="document.location='listar_couriers.php'">
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
		url: 'courier_modificar_salvar.php',
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
					document.location='listar_couriers.php';
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
