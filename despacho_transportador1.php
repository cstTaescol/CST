<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
//Discriminacion de aerolinea de usuario  TIPO 1
$id_aerolinea_user=$_SESSION['id_aerolinea_user'];
if ($id_aerolinea_user == "*")
	$sql_aerolinea="";
else
	$sql_aerolinea="AND id = '$id_aerolinea_user'";	
//*************************************/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head></head>
<body>
<?php
require("menu.php");
$id_objeto=56;
include("config/provilegios_modulo.php");
?>
<p class="titulo_tab_principal">Despachos de Mercancia</p>
<p class="asterisco" align="center">Transportador</p>
<p align="center"><img src="imagenes/1.jpg" width="186" height="67" alt="PASO 1" style="border-radius: 15px;" /></p>
<form name="despacho" method="post" action="despacho_transportador2.php" onsubmit="return validar();">
  <table align="center">
  <tr>
    <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Transportador</div></td>
    <td class="celda_tabla_principal celda_boton">
       <select name="transportador" tabindex="1" id="transportador" onchange="filtro(this.value);">
			<option value="">Seleccione uno</option>
			<?php
				$sql="SELECT id,nombre FROM transportador WHERE estado='A' ORDER BY nombre ASC";
				$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
				while($fila=mysql_fetch_array($consulta))
				{
					echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
				}
			?>
    </select>
    </td>
        <script type="text/javascript">
			document.forms[0].transportador.focus();
		</script>
  </tr>
  <tr>
    <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Vehiculo</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<input type="hidden" name="vehiculo" id="vehiculo" />
    	<div id="seccion_vehiculo">
            <select name="select_vehiculo" id="select_vehiculo" tabindex="2" >
            	<option value="">Seleccione uno</option>
            </select>
          </div>
    </td>
  </tr>
  <tr>
    <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Conductor</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<input type="hidden" name="conductor" id="conductor" />
    	<div id="seccion_conductor">
            <select name="select_conductor" id="select_conductor" tabindex="2" >
            	<option value="">Seleccione uno</option>
            </select>
          </div>
    </td>
  </tr>
  <tr>
    <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Deposito</div></td>
    <td class="celda_tabla_principal celda_boton">
      <select name="deposito" id="deposito" tabindex="4">
        <?php
			// Descarta nombres repetidos de depositos y disposiciones de carga que no concuerden con entregas a depositos.
			$sql="SELECT DISTINCT d.id,d.nombre,g.id_deposito FROM deposito d LEFT JOIN guia g ON d.id = g.id_deposito WHERE (g.id_tipo_bloqueo = '3' OR g.id_tipo_bloqueo = '6' OR g.id_tipo_bloqueo = '10') AND (g.id_disposicion ='10' OR g.id_disposicion ='11' OR g.id_disposicion ='18' OR g.id_disposicion ='22') ORDER BY d.nombre ASC";
			$consulta=mysql_query ($sql,$conexion) or die (exit('Error '.mysql_error()));
			while($fila=mysql_fetch_array($consulta))
			{
				echo '<option value="'.$fila['id'].'">'.$fila['nombre'].'</option>';
			}
		?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Aerolinea</div></td>
    <td class="celda_tabla_principal celda_boton">
        <select name="id_aerolinea" id="id_aerolinea" tabindex="5">
        <?php
            $sql="SELECT id,nombre FROM aerolinea WHERE estado='A' AND importacion = TRUE $sql_aerolinea ORDER BY nombre ASC";
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
    <td align="left" class="celda_tabla_principal"><div class="letreros_tabla">Planilla de Envio</div></td>
    <td class="celda_tabla_principal celda_boton"><input type="text" id="planilla_envio" name="planilla_envio" maxlength="50" size="30" tabindex="6" /></td>
  </tr>
</table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
      	<button type="button" tabindex="9" onclick="document.location='base.php'">
        	<img src="imagenes/al_principio-act.png" title="Atras" />
        </button>
        <button type="reset" tabindex="8">
        	<img src="imagenes/descargar-act.png" title="Limpiar" />
        </button>
      	<button type="submit" tabindex="7">
        	<img src="imagenes/al_final-act.png" title="Continuar" />
        </button>
      </td>
    </tr>
</table>
</form>
</body>
</html>
<script language="javascript">
// funcion para validar
function validar()
{	
	if (document.forms[0].vehiculo.value=="")
	{
		alert("Atencion: Debe seleccionar un VEHICULO.");
		return(false);
	}

	if (document.forms[0].conductor.value=="")
	{
		alert("Atencion: Debe seleccionar un CONDUCTOR.");
		return(false);
	}

	if (document.forms[0].deposito.value=="")
	{
		alert("Atencion: Se requiere un Deposito, deben existir Guias disponibles para despachar a los depositos.");
		document.forms[0].deposito.focus();
		return(false);
	}	

	if (document.forms[0].planilla_envio.value=="")
	{
		alert("Atencion: Se requiere un numero de PLANILLA DE ENVIO.");
		document.forms[0].planilla_envio.focus();
		return(false);
	}
}

function filtro(id_transportador){
//Solicitud Asincrona de Vehiculo
	$('vehiculo').value="";
	$('conductor').value="";
	
	var myRequest = new Request({
		url: 'despacho_transportador1_filtro_vehiculo.php',
		method: 'get',
		onRequest: function(){
			$('seccion_vehiculo').innerHTML = "Procesando <img src='imagenes/cargando.gif'/>";
		},
		onSuccess: function(responseText){
			$('seccion_vehiculo').innerHTML = responseText;
		},
		onFailure: function(){
			$('seccion_vehiculo').innerHTML = "Error";
		}
	});	
	myRequest.send('id_transportador=' + id_transportador);

//Solicitud Asincrona de conductor
	var myRequest = new Request({
		url: 'despacho_transportador1_filtro_conductor.php',
		method: 'get',
		onRequest: function(){
			$('seccion_conductor').innerHTML = "Procesando <img src='imagenes/cargando.gif'/>";
		},
		onSuccess: function(responseText){
			$('seccion_conductor').innerHTML = responseText;
		},
		onFailure: function(){
			$('seccion_conductor').innerHTML = "Error";
		}
	});	
	myRequest.send('id_transportador=' + id_transportador);
}
	
function pasar_vehiculo(registro){
	$('vehiculo').value=registro; 	
}
function pasar_conductor(registro){
	$('conductor').value=registro; 	
}

</script>