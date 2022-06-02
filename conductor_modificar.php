<?php
	$sql="SELECT * FROM conductor WHERE id='$id_registro'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$id=$fila['id'];
	$nombre=$fila['nombre'];
	$telefono1=$fila['telefono1'];
	$telefono2=$fila['telefono2'];
	$id_transportador=$fila['id_transportador'];	
?>
<p class="titulo_tab_principal">Conductor</p>
<div id="respuesta" class="opaco_ie" style=" background-image:url(imagenes/background.png); width:100%; height:30px"></div>
<form method="post" name="frm_guardar" id="frm_guardar">
	<table align="center">
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">Identificacion</div></td>
	      <td class="celda_tabla_principal celda_boton"><input type="hidden" name="id" id="id" value="<?php echo $id; ?>" /><?php echo $id; ?></tr>
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
	      <td class="celda_tabla_principal celda_boton"><input name="nombre" id="nombre" type="text" tabindex="2" size="30" maxlength="50" value="<?php echo $nombre; ?>"></td>
	    </tr>
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono</div></td>
	      <td class="celda_tabla_principal celda_boton"><input name="telefono1" id="telefono1" type="text" tabindex="3" size="10" maxlength="15" value="<?php echo $telefono1; ?>" onkeypress="return numeric(event)"></td>
	    </tr>
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono 2</div></td>
	      <td class="celda_tabla_principal celda_boton"><input name="telefono2" id="telefono2" type="text" tabindex="4" size="10" maxlength="15" value="<?php echo $telefono2; ?>" onkeypress="return numeric(event)"></tr>
	    <tr>
	      <td class="celda_tabla_principal"><div class="letreros_tabla">Transportador</div></td>
	      <td class="celda_tabla_principal celda_boton">
	      	<select name="id_transportador" id="id_transportador" tabindex="5">
		        <?php
					$sql="SELECT id,nombre FROM transportador WHERE id='$id_transportador'";
					$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					$fila=mysql_fetch_array($consulta);
					echo '<option value="'.$fila['id'].'" selected="selected">'.$fila['nombre'].'</option>';
				?>
		        <?php
					$sql="SELECT id,nombre FROM transportador WHERE estado='A' ORDER BY nombre ASC";
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
	</table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" onClick="document.location='listar_conductor.php'">
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
	if (document.frm_guardar.nombre.value=="")
	{
		alert("Atencion: Se requiere el NOMBRE.");
		document.frm_guardar.nombre.focus();
		return(false);
	}
	if (document.frm_guardar.id_transportador.value=="")
	{
		alert("Atencion: Se requiere el TRANSPORTADOR.");
		document.frm_guardar.id_transportador.focus();
		return(false);
	}
	guardar_form();				
}
	

function guardar_form()
{
	var peticion = new Request(
	{
		url: 'conductor_modificar_salvar.php',
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
					document.location='listar_conductor.php';
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
