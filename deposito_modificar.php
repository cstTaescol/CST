<?php
	$sql="SELECT * FROM deposito WHERE id='$id_registro'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$id=$fila['id'];	
	$nombre=$fila['nombre'];	
	$direccion=$fila['direccion'];
	$telefono=$fila['telefono'];
	$id_admon_aduana=$fila['id_admon_aduana'];
	$id_tipo_deposito=$fila['id_tipo_deposito'];
	$fpu=$fila['fpu'];
?>
<p class="titulo_tab_principal">Dep&oacute;sito</p>
<div id="respuesta" class="opaco_ie" style="background-image:url(imagenes/background.png); width:100%; height:30px"></div>
<form method="post" name="frm_guardar" id="frm_guardar">
	<table align="center">
      <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Cod. Dian</div></td>
	    <td class="celda_tabla_principal celda_boton"><input type="hidden" name="cod_dian" id="cod_dian" value="<?php echo $id; ?>" tabindex="1" /><?php echo $id; ?></td>
	  </tr>      
      <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
	    <td class="celda_tabla_principal celda_boton"><input name="nombre" id="nombre" type="text" size="30" maxlength="200"  value="<?php echo $nombre; ?>" tabindex="2" /><font color="#FF0000"><strong> (*)</strong></strong></font></td>
	  </tr>
	  <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Direccion</div></td>
	    <td class="celda_tabla_principal celda_boton"><input name="direccion" type="text" size="30" maxlength="200"  value="<?php echo $direccion; ?>" tabindex="3"/><font color="#FF0000"><strong> (*)</strong></strong></font></td>
	  </tr>
	  <tr>
	    <td class="celda_tabla_principal"><div class="letreros_tabla">Telefono</div></td>
	    <td class="celda_tabla_principal celda_boton"><input name="telefono" type="text" size="15" maxlength="10" onkeypress="return numeric(event)" value="<?php echo $telefono; ?>" tabindex="4" /></td>
	  </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Aduana</div></td>
        <td class="celda_tabla_principal celda_boton">
            <select name="admon_aduana" id="admon_aduana" tabindex="5">
              <?php
                    $sql="SELECT id,nombre FROM admon_aduana WHERE estado='A' ORDER BY nombre ASC";
                    $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
					while($fila=mysql_fetch_array($consulta))
                    {
						if ($id_admon_aduana == $fila['id'])
						{
							$seleccionado="selected";
						}
						else
							{
								$seleccionado="";
							}						
                        echo '<option value="'.$fila['id'].'"'.$seleccionado.'>'.$fila['nombre'].'</option>';
                    }
                ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Tipo</div></td>
        <td class="celda_tabla_principal celda_boton">
            <select name="tipo_deposito" id="tipo_deposito" tabindex="6">
              <?php
                    $sql="SELECT id,nombre FROM tipo_deposito WHERE estado='A' ORDER BY nombre ASC";
                    $consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
                    while($fila=mysql_fetch_array($consulta))
                    {
						if ($id_tipo_deposito == $fila['id'])
						{
							$seleccionado="selected";
						}
						else
							{
								$seleccionado="";
							}						
						
                        echo '<option value="'.$fila['id'].'"'.$seleccionado.'>'.$fila['nombre'].'</option>';
                    }
                ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">FPU</div></td>
        <td class="celda_tabla_principal celda_boton">
        	<?php 
				if($fpu=='N')
				{
					$checked_n="checked";
					$checked_s="";
				}
				else
					{
						$checked_n="";
						$checked_s="checked";

					}
			?>
            Si<input type="radio" name="deposito_fpu" value="S"  tabindex="7" <?php echo $checked_s;?>/>
            No<input type="radio" name="deposito_fpu" value="N"  tabindex="8" <?php echo $checked_n;?>/>
            <br />
            (Fuera del per&iacute;metro Urbano)
        </td>
      </tr>
	</table>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" onclick="document.location='listar_deposito.php'">
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
	if (document.frm_guardar.cod_dian.value=="")
	{
		alert("Atencion: Se requiere el CODIGO DE LA DIAN del Deposito.");
		document.frm_guardar.cod_dian.focus();
		return(false);
	}

	if (document.frm_guardar.nombre.value=="")
	{
		alert("Atencion: Se requiere el NOMBRE del Deposito.");
		document.frm_guardar.nombre.focus();
		return(false);
	}

	if (document.frm_guardar.direccion.value=="")
	{
		alert("Atencion: Se requiere la DIRECCION del Deposito.");
		document.frm_guardar.direccion.focus();
		return(false);
	}
	guardar_form();				
}

function guardar_form()
{
	var peticion = new Request(
	{
		url: 'deposito_modificar_salvar.php',
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
					document.location='listar_deposito.php';
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
