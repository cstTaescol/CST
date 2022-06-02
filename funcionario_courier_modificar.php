<?php
	$sql="SELECT * FROM courier_funcionario WHERE id='$id_registro'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$id_registro=$fila['id'];
	$no_documento=$fila['no_documento'];	
	$nombre=$fila['nombre'];
	$id_consignatario=$fila['id_consignatario'];	
	
?>
<p class="titulo_tab_principal">Funcionarios del Courier</p>
<div id="respuesta" class="opaco_ie" style="background-image:url(imagenes/background.png); width:100%; height:30px"></div>
<form method="post" name="frm_guardar" id="frm_guardar">
	<table align="center">
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">No. Documento</div></td>
        <td class="celda_tabla_principal celda_boton">
          <input name="no_documento" type="text" id="no_documento" tabindex="1" size="20" maxlength="10" onkeypress="return numeric(event)" value="<?php echo $no_documento ?>"> 
          <font color="#FF0000"><strong>(*)</strong></font>
          <script>document.forms[0].no_documento.focus();</script>
        </td>          
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Nombre</div></td>
        <td class="celda_tabla_principal celda_boton">
          <input name="nombre" type="text" id="nombre" tabindex="2" size="20" maxlength="50" value="<?php echo $nombre ?>">
          <font color="#FF0000"><strong>(*)</strong>
        </td>          
      </tr>            
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Courier</div></td>
        <td class="celda_tabla_principal celda_boton">
          <select name="id_courier" id="id_courier" tabindex="3">
            <?php
      				$sql="SELECT id,nombre FROM couriers WHERE estado='A' ORDER BY nombre ASC";
      				$consulta=mysql_query ($sql,$conexion) or die ("ERROR 01: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
      				while($fila=mysql_fetch_array($consulta))
      				{           
                  		if ($fila['id'] == $id_consignatario)
                  			$seleccionado='selected="selected"';
                  		else
                  			$seleccionado='';
                  		echo '<option value="'.$fila['id'].'" '.$seleccionado.'>'.substr($fila['nombre'], 0,30).'</option>';            					
      				}
    			 ?>
          </select>
      	</td>
      </tr> 
	</table>
	<input type="hidden" name="id_registro" id="id_registro" value="<?php echo $id_registro ?>">
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" onclick="document.location='listar_courier_funcionario.php'">
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


// funcion para validar
function validar()
{ 
  if (document.forms[0].no_documento.value=="")
  {
    alert("Atencion: Se requiere el NUMERO DE DOCUMENTO");
    document.forms[0].no_documento.focus();
    return(false);
  }
  if (document.forms[0].nombre.value=="")
  {
    alert("Atencion: Se requiere el NOMBRE");
    document.forms[0].nombre.focus();
    return(false);
  } 
  guardar_form(); 
}



function guardar_form()
{
	var peticion = new Request(
	{
		url: 'funcionario_courier_modificar_salvar.php',
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
					document.location='listar_courier_funcionario.php';
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
