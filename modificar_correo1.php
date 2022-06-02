<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");

$tablas="";
$i=0;
if (isset($_GET['id']))
{
	$id_correo=$_GET['id'];
	$sql="SELECT * FROM correo WHERE id='$id_correo'";
	$consulta=mysql_query ($sql,$conexion) or die ("ERROR 1: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	$fila=mysql_fetch_array($consulta);
	$aux_entrega_am=$fila['aux_entrega_am'];
	$oper_entrega_am=$fila['oper_entrega_am'];
	$aux_entrega_pm=$fila['aux_entrega_pm'];
	$oper_entrega_pm=$fila['oper_entrega_pm'];
	$tpallets=$fila['tpallets'];
	$tmallas=$fila['tmallas'];
	$tcorreas=$fila['tcorreas'];
	$tdollys=$fila['tdollys'];
	$coordinador=$fila['coordinador'];
	$jefe=$fila['jefe'];
	$supervisor=$fila['supervisor'];
	$tipo_entrega=$fila['tipo_entrega'];
	
	
	$sql2="SELECT * FROM carga_correo WHERE id_correo = '$id_correo'";
	$consulta2=mysql_query ($sql2,$conexion) or die ("ERROR 2: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
	while($fila2=mysql_fetch_array($consulta2))
	{
		$i++;
		$id_registro=$fila2['id'];
		$id_correo=$fila2['id_correo'];
		$npallets=$fila2['npallets'];
		$npcs=$fila2['npcs'];
		
		$hora_inicio=$fila2['hora_inicio'];
		$hora_inicio=explode(":",$hora_inicio);
		$hhi=$hora_inicio[0];
		$mmi=$hora_inicio[1];
		$ssi=$hora_inicio[2];

		$hora_salida=$fila2['hora_salida'];
		$hora_salida=explode(":",$hora_salida);
		$hhs=$hora_salida[0];
		$mms=$hora_salida[1];
		$sss=$hora_salida[2];

		
		$observaciones=$fila2['observaciones'];
		//Carga datos de la guia
		$id_guia=$fila2['id_guia'];
		$sql_guia="SELECT hija FROM guia WHERE id='$id_guia'";
		$consulta_guia=mysql_query ($sql_guia,$conexion) or die ("ERROR 3: ". mysql_error(). " INFORME AL SOPORTE TECNICO");
		$fila_guia=mysql_fetch_array($consulta_guia);
		$hija=$fila_guia['hija'];
		$tablas=$tablas.'
		  <tr>
			<td class="celda_tabla_principal celda_boton">'.$hija.'</td>
			<td class="celda_tabla_principal celda_boton"><input type="text" name="palet'.$i.'" size="20" maxlength="50" value="'.$npallets.'" /></td>
			<td class="celda_tabla_principal celda_boton"><input type="text" name="pcs'.$i.'" size="2" maxlength="5" onKeyPress="return numeric2(event)" value="'.$npcs.'"/></td>
			<td class="celda_tabla_principal celda_boton">
				<input type="text" name="hhi'.$i.'" size="2" maxlength="2" onKeyPress="return numeric2(event)" value="'.$hhi.'"/>:
				<input type="text" name="mmi'.$i.'" size="2" maxlength="2" onKeyPress="return numeric2(event)" value="'.$mmi.'"/>
				<input type="hidden" name="ssi'.$i.'" maxlength="2" size="2" onKeyPress="return numeric2(event)" value="'.$ssi.'"/>
			</td>
			<td class="celda_tabla_principal celda_boton">
				<input type="text" name="hhs'.$i.'" size="2" maxlength="2" onKeyPress="return numeric2(event)" value="'.$hhs.'"/>:
				<input type="text" name="mms'.$i.'" size="2" maxlength="2" onKeyPress="return numeric2(event)" value="'.$mms.'"/>
				<input type="hidden" name="sss'.$i.'" maxlength="2" size="2" onKeyPress="return numeric2(event)" value="'.$sss.'"/>
			</td>
			<td class="celda_tabla_principal celda_boton">
				<input type="text" name="observaciones'.$i.'" size="20"  value="'.$observaciones.'"/>
				<input type="hidden" name="id_registro'.$i.'" value="'.$id_registro.'"/>
			</td>
		  </tr>';
	}
}
else
	{
		echo "
			<script language=\"javascript\">
				alert(\"ERROR:No se pudo obtener el DESPACHO\");
				document.location='base.php';
			</script>";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
function numeric2(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9\n]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
</script>
</head>
<body>
<?php
require("menu.php");
?>
<p class="titulo_tab_principal">Modificacion de Despacho</p>
<p align="center" class="asterisco">Correo No. <?php echo $id_correo ?></p>

<form method="post" action="modificar_correo2.php">
  <table align="center">
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Auxiliar que entrega AM</div></td>
      <td class="celda_tabla_principal celda_boton"><input type="text" name="auxiliaram" tabindex="2" value="<?php echo $aux_entrega_am ?>"/></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Operario AM</div></td>
      <td class="celda_tabla_principal celda_boton"><input type="text" name="operarioam" tabindex="3" value="<?php echo $oper_entrega_am ?>"/></td>
    </tr>
    <tr>
      <td class="celda_tabla_principal"><div class="letreros_tabla">Auxiliar que entrega PM</div></td>
      <td class="celda_tabla_principal celda_boton"><input type="text" name="auxiliarpm" tabindex="4" value="<?php echo $aux_entrega_pm ?>"/></td>
    </tr>
    <tr>
      <td align="right" class="celda_tabla_principal"><div class="letreros_tabla">Operario PM</div></td>
      <td class="celda_tabla_principal celda_boton"><input type="text" name="operariopm" tabindex="5" value="<?php echo $oper_entrega_pm ?>"/></td>
    </tr>
  </table><br />
  <p align="center"><font color="#0066CC">ENTREGA DIRECTA</font> <input type="radio" name="entrega" value="D" <?php if ($tipo_entrega=="D") echo "checked=\"checked\""; ?>/></p>
  <p align="center"><font color="#009933">ENTREGA EN BODEGA 1</font> <input type="radio" name="entrega" value="B" <?php if ($tipo_entrega=="B") echo "checked=\"checked\""; ?> /></p>
  <table width="80%" align="center">
	<tr> 
        <td class="celda_tabla_principal"><div class="letreros_tabla">Guia</div></td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">No. Pallet Utilizado</div></td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">No. PCS x Pallet</div></td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Hora de Inicio<br />hh:mm</div></td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Hora de Salida<br />hh:mm</div></td>
        <td class="celda_tabla_principal"><div class="letreros_tabla">Observaciones</div></td>
     </tr>
      <?php echo $tablas ?>
    </table>
   <br />
   <table align="center">
     <tr>
       <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Pallets Utilizados</div></td>
       <td class="celda_tabla_principal celda_boton"><input name="tpallets" type="text" tabindex="6" size="5" maxlength="5" onkeypress="return numeric2(event)" value="<?php echo $tpallets ?>"/></td>
     </tr>
     <tr>
       <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Mallas Utilizadas</div></td>
       <td class="celda_tabla_principal celda_boton"><input name="tmallas" type="text" tabindex="7" size="5" maxlength="5" onkeypress="return numeric2(event)" value="<?php echo $tmallas ?>"/></td>
     </tr>
     <tr>
       <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Correas Utilizadas</div></td>
       <td class="celda_tabla_principal celda_boton"><input name="tcorreas" type="text" tabindex="8" size="5" maxlength="5" onkeypress="return numeric2(event)" value="<?php echo $tcorreas ?>"/></td>
     </tr>
     <tr>
	   <td class="celda_tabla_principal"><div class="letreros_tabla">Total de Dollys Utilizados</div></td>       
       <td class="celda_tabla_principal celda_boton"><input name="tdollys" type="text" tabindex="9" size="5" maxlength="5" onkeypress="return numeric2(event)" value="<?php echo $tdollys ?>"/></td>
     </tr>
  </table>
  <table align="center" >
     <tr>
     	<td class="celda_tabla_principal"><div class="letreros_tabla">Supervisor</div></td>
       	<td class="celda_tabla_principal celda_boton"><input type="text" name="supervisor" tabindex="10" value="<?php echo $supervisor ?>"/></td>
    </tr>
     <tr>
		<td class="celda_tabla_principal"><div class="letreros_tabla">Jefe de Bodega</div></td>     
       	<td class="celda_tabla_principal celda_boton"><input type="text" name="jefe" tabindex="11" value="<?php echo $jefe ?>"/></td>
     </tr>
     <tr>
     	<td class="celda_tabla_principal"><div class="letreros_tabla">Coordinador Responsable</div></td>
       <td class="celda_tabla_principal celda_boton">
       		<input type="text" name="coordinador" tabindex="12" value="<?php echo $coordinador ?>"/>
         	<input type="hidden" name="cantidad_guias" value="<?php echo $i ?>"/>
            <input type="hidden" name="id" value="<?php echo $id_correo ?>"/>
        </td>
     </tr>
  </table>
<table width="450" align="center">
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
    </tr>
    <tr>
      <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button name="Limpiar" type="reset">
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            
            <button name="Guardar" type="submit">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
      </td>
    </tr>
</table>

</form>
</body>
</html>