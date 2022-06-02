<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

if(isset($_REQUEST["id_remesa"]))
{
	$id_remesa=$_REQUEST["id_remesa"];
	$sql="SELECT * FROM remesa WHERE id=$id_remesa";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR: 1". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$id_transportador=$fila["id_transportador"];
	$id_vehiculo=$fila["id_vehiculo"];
	$id_conductor=$fila["id_conductor"];
	$observacion=$fila["observacion"];
	$planilla_envio=$fila["planilla_envio"];
	
	$exclusivo=$fila["exclusivo"];
	if ($exclusivo=="S")
		$activo1='checked="checked"';
		else
			$activo1='';

	$refrigerado=$fila["refrigerado"];
	if ($refrigerado=="S")
		$activo2='checked="checked"';
		else
			$activo2='';
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
<p class="titulo_tab_principal">Modificacion de la Remesa</p>
<p class="asterisco" align="center">Remesa No: <font color="#FF0000"><?php echo $id_remesa ?></font></p>
<form name="modificar" method="post" action="modificar_remesa2.php" onsubmit="return validar();">
<table align="center">
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Transportador</div></td>
    <td class="celda_tabla_principal celda_boton">
       <select name="transportador" tabindex="1" id="transportador">
			<?php
				$sql="SELECT id,nombre FROM transportador WHERE estado='A' ORDER BY nombre ASC";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila=mysql_fetch_array($consulta))
				{
	                    if ($fila['id'] == $id_transportador) 
                        $seleccion="selected='selected'";
                        else
                            $seleccion="";

					echo '<option value="'.$fila['id'].'" '.$seleccion.'>'.$fila['nombre'].'</option>';
				}
			?>
    	</select>
        <font color="#FF0000">(*)</font>
	</td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Conductor</div></td>
    <td class="celda_tabla_principal celda_boton">
       <select name="conductor" tabindex="2" id="conductor">
			<?php
				$sql="SELECT id,nombre FROM conductor WHERE estado='A' ORDER BY nombre ASC";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila=mysql_fetch_array($consulta))
				{
	                    if ($fila['id'] == $id_conductor) 
                        $seleccion="selected='selected'";
                        else
                            $seleccion="";

					echo '<option value="'.$fila['id'].'" '.$seleccion.'>'.$fila['nombre'].'</option>';
				}
			?>
    	</select>
        <font color="#FF0000">(*)</font>
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Vehiculos</div></td>
    <td class="celda_tabla_principal celda_boton">
       <select name="vehiculo" tabindex="3" id="vehiculo">
			<?php
				$sql="SELECT placa FROM vehiculo WHERE estado='A' ORDER BY placa ASC";
				$consulta=mysql_query ($sql,$conexion) or die ("ERROR: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
				while($fila=mysql_fetch_array($consulta))
				{
	                    if ($fila['placa'] == $id_vehiculo) 
                        $seleccion="selected='selected'";
                        else
                            $seleccion="";

					echo '<option value="'.$fila['placa'].'" '.$seleccion.'>'.$fila['placa'].'</option>';
				}
			?>
    	</select>
        <font color="#FF0000">(*)</font>    
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Exclusivo <input type="checkbox" name="exclusivo" value="S" <?php echo $activo1; ?>/></div></td>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Refrigerado <input type="checkbox" name="refrigerado" value="S" <?php echo $activo2; ?>/></div></td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
    <td class="celda_tabla_principal celda_boton">
    	<textarea name="observacion" cols="40" rows="6"><?php echo $observacion; ?></textarea>
        <input type="hidden" name="id_remesa" value="<?php echo $id_remesa; ?>" />
    </td>
  </tr>
  <tr>
    <td class="celda_tabla_principal"><div class="letreros_tabla">Planilla de Envio</div></td>
	<td class="celda_tabla_principal celda_boton"><input type="text" id="planilla_envio" name="planilla_envio" maxlength="50" size="30" value="<?php echo $planilla_envio; ?>"/><font color="#FF0000">(*)</font></td>
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
<script language="javascript">
// funcion para validar
function validar()
{	
	if (document.forms[0].planilla_envio.value=="")
	{
		alert("Atencion: Se requiere un numero de PLANILLA DE ENVIO.");
		document.forms[0].planilla_envio.focus();
		return(false);
	}	
}
</script>