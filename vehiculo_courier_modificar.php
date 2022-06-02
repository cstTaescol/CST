<?php
	$sql="SELECT * FROM vehiculo_courier WHERE id='$id_registro'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$id=$fila['id'];
	$placa=$fila['placa'];
	$id_consignatario=$fila['id_consignatario'];	
	$no_acta=$fila['no_acta'];	
?>
<p class="titulo_tab_principal">Vehiculos Courier</p>
<div id="respuesta" class="opaco_ie" style="background-image:url(imagenes/background.png); width:100%; height:30px"></div>
<form method="post" name="frm_guardar" id="frm_guardar">
	<table align="center">
	    <tr>
      	  <td class="celda_tabla_principal"><div class="letreros_tabla">Placa</div></td>
	      <td class="celda_tabla_principal celda_boton"><input type="hidden" name="id" id="id" value="<?php echo $id; ?>" /><?php echo $placa; ?></td>
	    </td>
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
	      <td class="celda_tabla_principal celda_boton">
	          <select name="id_consignatario" id="id_consignatario" tabindex="2">
	            <?php
	      			$sql="SELECT id,nombre FROM couriers WHERE estado='A' ORDER BY nombre ASC";
	      			$consulta=mysql_query ($sql,$conexion) or die ("ERROR 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	      			while($fila=mysql_fetch_array($consulta))
	      			{
		                if($id_consignatario == $fila['id'])
		            	   	$seleccion='selected="selected"';
		                else
		                    $seleccion='';              
		                echo '<option value="'.$fila['id'].'" '.$seleccion.'>'.substr($fila['nombre'], 0,30).'</option>';            					
	      			}
	    		?>
	          </select>
	      </td>
	    <tr>
	       <td class="celda_tabla_principal"><div class="letreros_tabla">Acta</div></td>
	       <td class="celda_tabla_principal celda_boton">
	          <input name="no_acta" type="text" id="no_acta" tabindex="3" size="15" maxlength="20" value="<?php echo $no_acta ?>">
	        </td>          
	     </tr>      
	</table>

    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" onclick="document.location='listar_vehiculo_courier.php'">
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
	if (document.frm_guardar.id_consignatario.value=="")
	{
		alert("Atencion: Seleccione un CONSIGNATARIO.");
		document.frm_guardar.id_consignatario.focus();
		return(false);
	}
	guardar_form();				
}
	

function guardar_form()
{
	var peticion = new Request(
	{
		url: 'vehiculo_courier_modificar_salvar.php',
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
					document.location='listar_vehiculo_courier.php';
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
