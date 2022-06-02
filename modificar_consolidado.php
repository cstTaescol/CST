<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

if(isset($_REQUEST['id_guia']))
{
	$id_guia=$_REQUEST['id_guia'];
	$sql="SELECT master,id_embarcador,id_consignatario,descripcion,observaciones FROM guia WHERE id='$id_guia'";
	$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
	$fila=mysql_fetch_array($consulta);
	$master=$fila['master'];
	$id_embarcador=$fila['id_embarcador'];
	$descripcion=$fila['descripcion'];
	$id_consignatario=$fila['id_consignatario'];
	$descripcion=$fila['descripcion'];
	$observaciones=$fila['observaciones'];
}
else
{
	echo '
    <script language="javascript">
    	alert("Atencion: El Servidor No Pudo Obtener La Informacion De La Guia, Vuelva a Intentarlo.");
		document.location="consulta_consolidado1.php";
	</script>
    ';	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Guia Master</p>
<form name="frm_guardar" id="frm_guardar" method="post">
	<input type="hidden" name="id_guia" id="id_guia" value="<?php echo $id_guia ?>" />
    <table align="center">
      <tr>
        <td width="40%" class="celda_tabla_principal"><div class="letreros_tabla">No Guia</div></td>
        <td width="60%" class="celda_tabla_principal celda_boton">
            <font color="#FF0000"><strong>(*)</strong></font>
            <input type="text" name="guia" id="guia"  tabindex="1" size="20" maxlength="20" value="<?php echo $master ?>"/>
            <script> $('guia').focus(); </script>
        </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Embarcador</div></td>
        <td class="celda_tabla_principal celda_boton">
            <font color="#FF0000"><strong>(*)</strong></font>
            <select name="embarcador" id="embarcador" tabindex="2">
              <?php
                    $seleccion='';
					$sql="SELECT id,nombre FROM embarcador WHERE estado='A' ORDER BY nombre ASC";
                    $consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
                    while($fila=mysql_fetch_array($consulta))
                    {
                        $seleccion='';
						$id_registro=$fila['id'];
						if ($id_registro == $id_embarcador)
							$seleccion='selected="selected"';
						echo '<option value="'.$fila['id'].'" '.$seleccion.'>'.substr($fila['nombre'],0,35).'</option>'."\n" ;
                    }
                ?>
            </select>
        </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Consignatario</div></td>
        <td class="celda_tabla_principal celda_boton">
            <font color="#FF0000"><strong>(*)</strong></font>
            <select name="consignatario" id="consignatario" tabindex="3">
              <?php
                    $seleccion='';
					$sql="SELECT id,nombre FROM consignatario WHERE estado='A' ORDER BY nombre ASC";
                    $consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
                    while($fila=mysql_fetch_array($consulta))
                    {
                        $seleccion='';
						$id_registro=$fila['id'];
						if ($id_registro == $id_consignatario)
							$seleccion='selected="selected"';
						echo '<option value="'.$fila['id'].'" '.$seleccion.'>'.substr($fila['nombre'],0,35).'</option>'."\n" ;
                    }
                ?>
            </select>    
        </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Descripcion</div></td>
        <td class="celda_tabla_principal celda_boton">
          <font color="#FF0000"><strong>(*)</strong></font>
          <input type="text" name="descripcion" id="descripcion" size="40" tabindex="4" value="<?php echo $descripcion ?>"/>
        </td>
      </tr>
      <tr>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Observacion</div></td>
        <td class="celda_tabla_principal celda_boton">
        	<textarea name="observaciones" id="observaciones" cols="40" rows="5" tabindex="5"><?php echo $observaciones ?></textarea>
        </td>
      </tr>
    </table>
    <div id="respuesta" align="center"></div>
    <table width="450" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="button" name="cancelar" id="cancelar" tabindex="8" onclick="document.location='consulta_consolidado1.php'">
                <img src="imagenes/al_principio-act.png" title="Atras" />
            </button>
            <button type="reset" name="reset" id="reset" tabindex="7">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="button" name="guardar" id="guardar" tabindex="6" onClick="return validar();">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
          </td>
        </tr>
     </table>        
</form>
</body>
</html>
<script language="javascript">
function validar()
{	
	if (document.frm_guardar.guia.value=="")
	{
		alert("Atencion: Se requiere el NUMERO DE GUIA.");
		document.frm_guardar.guia.focus();
		return(false);
	}
	if (document.frm_guardar.descripcion.value=="")
	{
		alert("Atencion: Se requiere una DESCRIPCION.");
		document.frm_guardar.descripcion.focus();
		return(false);
	}
	guardar_form();				
}
	

function guardar_form()
{
	var peticion = new Request(
	{
		url: 'modificar_consolidado_salvar.php',
		method: 'post',
		onRequest: function()
		{
			$('respuesta').innerHTML='<p align="center">Procesando...<img src="imagenes/cargando.gif"></p>';
			$('guardar').disabled=true;
			$('reset').disabled=true;
		},			
		onSuccess: function(responseText)
		{
			var respuesta;
			respuesta=responseText;
			switch (respuesta)
			{
				case "0":
					$('respuesta').innerHTML='<p align="center">Error al guardar los datos...</p>';
				break;
				case "1":
					$('respuesta').innerHTML='<p align="center">Registro almacenado de manera exitosa...</p>';
					alert('Registro almacenado de manera exitosa...');
					document.location='consulta_consolidado1.php?coincidencia=<?php echo $master ?>';
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
</script>